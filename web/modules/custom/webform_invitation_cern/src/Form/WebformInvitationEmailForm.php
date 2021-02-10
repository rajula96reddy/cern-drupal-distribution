<?php

/**
 *
 * Normal behaviour:
 *   Save the form generates codes for those emails in the list that haven't already code assigned
 *   Send button sends email for all the pairs code/email that has not been already used
 *   Reminder button sends email for all the pairs code/email that has not yet been used
 *   CHANGES:
 *      Now save only saves configuration
 *      Send generates codes and send the messages
 *      Reminder only accessible if any code exists which means codes where already sent at least once
 *      by doing so the logic can be shared between normal and confidential behaviour
 *
 *
 *
 * Confidential behaviour:
 *   Confidential can only be enabled if no code is generated on for the webform, otherwise a message is printed to remove codes and submissions before activating the option
 *   Reminder button dissapears
 *   Save button only saves config changes, it doesn't generate codes
 *
 *   - Option A -  more complex
 *
 *   Send button clear existing codes, existing submissions and generate codes and send emails on the fly without storing link information between code and email
 *
 *   - Option B - Simpler
 *
 *   Send button only enabled when no code exists, if code exists it warns the user to remove all codes and submissions before send another batch
 *

 *
 *
 */
namespace Drupal\webform_invitation_cern\Form;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\webform\WebformInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\webform\WebformTokenManagerInterface;
use Drupal\cern_ldap_api\CERNLdapService;

/**
 * Allows to download the list of generated codes.
 */
class WebformInvitationEmailForm extends FormBase {

  const MODULE_NAME = 'webform_invitation';

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

    /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

    /**
   * A mail manager for sending email.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

    /**
   * A interface to CERN LDAP.
   *
   * @var \Drupal\cern_ldap_api\CERNLdapService
   */
  protected $cernLdap;



  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'webform_invitation_email_form';
  }

  /**
   * Constructs a new WebformInvitationDownloadForm instance.
   *
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   *
   * @param \Drupal\webform\WebformTokenManagerInterface $token_manager
   *   The webform token manager.
   *
   * @param \Drupal\cern_ldap_api\CERNLdapService $cern_ldap
   *   CERN Ldap service to do queries
   */
  public function __construct(Connection $database, TimeInterface $time, MailManagerInterface $mail_manager, WebformTokenManagerInterface $token_manager, CERNLdapService $cern_ldap) {
    $this->database = $database;
    $this->time = $time;
    $this->mailManager = $mail_manager;
    $this->tokenManager = $token_manager;
    $this->cernLdap = $cern_ldap;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('datetime.time'),
      $container->get('plugin.manager.mail'),
      $container->get('webform.token_manager'),
      $container->get('cern_lda_api.cernldap')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, WebformInterface $webform = NULL) {
    $email_message = $webform->getThirdPartySetting(static::MODULE_NAME, 'email_message');
    $email_reminder_message = $webform->getThirdPartySetting(static::MODULE_NAME, 'email_reminder_message');
    $list_emails = $webform->getThirdPartySetting(static::MODULE_NAME, 'list_emails');
    $subject = $webform->getThirdPartySetting(static::MODULE_NAME, 'subject');
    $confidential_invitations = $webform->getThirdPartySetting(static::MODULE_NAME, 'confidential_invitations');
    $webform_id = $webform->id();

    //count used codes just for statistics on the list
    $invitationsUsed = 0;

    //Get all codes
    $query = $this->database->select('webform_invitation_codes', 'c');
    $query->fields('c');
    $query->condition('webform', $webform_id);
    $query->isNotNull("email");
    $totalCodes = $query
    ->countQuery()->execute()->fetchField();

    //Get used codes
    $query = $this->database->select('webform_invitation_codes', 'c');
    $query->fields('c');
    $query->condition('webform', $webform_id);
    $query->condition('used', 0, "<>");
    $query->isNotNull("email");
    $usedCodes = $query
    ->countQuery()->execute()->fetchField();

    //Sortable table for the list of codes
    $header = [
      //The header gives the table the information it needs in order to make the query calls for ordering
      ['data' => $this->t('Code'), 'field' => 'c.code'],
      ['data' => $this->t('Email'), 'field' => 'c.email'],
      ['data' => $this->t('Used'), 'field' => 'c.used', 'sort' => 'desc'],
    ];
    $query = $this->database->select('webform_invitation_codes', 'c')
      ->extend('Drupal\Core\Database\Query\TableSortExtender')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(25);
    $query->fields('c');
    $query->condition('webform', $webform_id);
    $query->isNotNull("email");
    $codes = $query
      ->orderByHeader($header)
      ->execute();

    $rows = [];
    foreach ($codes as $row) {
      if (!empty($row->used)) { $invitationsUsed++; }
      $rows[$row->cid] = [
        'code' => $row->code,
        'email' => ($confidential_invitations) ? $this->t('CONFIDENTIAL') : $row->email,
        'used' => empty($row->used) ? $this->t('no') : $this->t('yes'),
      ];
    }

    $form['webform'] = [
      '#type' => 'value',
      '#value' => $webform,
    ];

    // Confidential option, will not present any link between the email and the invitation code on the interface makign thus the webform a little bit more confidential (at least for non advance users)
    $form['confidential_invitations'] = [
       '#type' => 'checkbox',
       '#title' => $this->t('Enable confidential invitations.'),
       '#description' => $this->t('It will never store the link between each email and the invitation code.') . '<b>' . $this->t(' (Please note that this option cannot be reverted)') . '</b>',
       '#default_value' => $confidential_invitations,
       #'#disabled' => ($confidential_invitations) ? true : false,
    ];

    $form['emails'] = [
      '#type' => 'textarea',
      '#title' => $this->t('List of emails'),
      '#default_value' => $list_emails,
      '#description' => $this->t('Please input the list of emails to send invitation codes. One email per line. It allows the usage of CERN E-Groups'),
    ];

    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email subject'),
      '#default_value' => $subject,
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => TRUE,
    ];

    $form['email_message'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Email message'),
      '#format' => isset($email_message['format']) ? $email_message['format'] : 'full_html',
      '#default_value' => isset($email_message['value']) ? $email_message['value'] : '',
      '#description' => $this->t('Include here the template of the e-mail you want to send to the participants. to include the invitation code url into the e-mail message please check the list of tokens bellow or just copy [webform_invitation:code:url]'),
    ];

    $form['tokens'] = $this->tokenManager->buildTreeElement();

    $form['email_reminder_message'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Reminder Email message'),
      '#description' => $this->t('Email message that will be sent when a Reminder email campaign will be triggered. IIt allows the usage of Tokens, like [webform_invitation:code:url] (check the available Tokens from the link above.'),
      '#format' => isset($email_reminder_message['format']) ? $email_reminder_message['format'] : 'full_html',
      '#default_value' => isset($email_reminder_message['value']) ? $email_reminder_message['value'] : '',
    ];



    $form['webform_invitation'] = [
      '#type' => 'details',
      '#title' => $this->t('Webform Email Only Invitations [ ') . $usedCodes . $this->t(' used / ') . $totalCodes . $this->t(' invitations ]'),
      '#open' => TRUE,
    ];

    $form['webform_invitation']['codes'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No codes assigned, yet. Introduce some emails into the list to assign codes to them.'),
    ];
    $form['webform_invitation']['pager'] = array(
      '#type' => 'pager'
    );

    $form['webform_invitation']['actions'] = [
      '#type' => 'actions',
    ];
    $form['webform_invitation']['actions']['send'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate codes / Send Emails'),
      '#name' => 'email-button',
      '#button_type' => 'primary',
      '#submit' => array('::sendEmailsHandler'),
    ];

    // Reminder button, only available if no confidential option is activated.
    // When confidential option is activated is useless, since there is not
    // recorded information to link each code with an email address
    if (!$confidential_invitations){
      $form['webform_invitation']['actions']['send_reminder'] = [
        '#type' => 'submit',
        '#value' => $this->t('Send Reminder Emails'),
        '#name' => 'email-reminder-button',
        '#button_type' => 'secondary',
        '#submit' => array('::sendEmailsHandler'),
        '#disabled' => ($totalCodes === 0) ? true : false, // If no codes are assigned means no email was still sent, so no reason to send a reminder
      ];
    }
    $form['webform_invitation']['actions']['delete'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete all'),
      '#submit' => array('::deleteInvitationsHandler'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration changes'),
      '#button_type' => 'primary',
    ];

    return $form;
  }


  /**
  * {@inheritdoc}
  */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\webform\Entity\Webform $webform */
    $webform = $form_state->getValue('webform');
    $webform_id = $webform->id();


    // Process confidential_invitations
    $curr_confidential_invitations = $webform->getThirdPartySetting(static::MODULE_NAME, 'confidential_invitations');
    $new_confidential_invitations = $form_state->getValue('confidential_invitations');

    if ($curr_confidential_invitations !== $new_confidential_invitations) {
      if ($curr_confidential_invitations) {
        //Remind users that this setting cannot be undone
        drupal_set_message(t('Confidential invitations option cannot be disabled once enabled.'), 'warning');
      }
      else {
        $webform->setThirdPartySetting(static::MODULE_NAME, 'confidential_invitations', $new_confidential_invitations);
      }
    }


    // Process email message, save it under the webform entity
    $subject = $form_state->getValue('subject');
    $webform->setThirdPartySetting(static::MODULE_NAME, 'subject', $subject);
    $email_message = $form_state->getValue('email_message');
    $webform->setThirdPartySetting(static::MODULE_NAME, 'email_message', $email_message);
    $email_reminder_message = $form_state->getValue('email_reminder_message');
    $webform->setThirdPartySetting(static::MODULE_NAME, 'email_reminder_message', $email_reminder_message);

    //Save the list of emails/e-groups before expansion
    $emails = $form_state->getValue('emails');
    $webform->setThirdPartySetting(static::MODULE_NAME, 'list_emails', $emails);

    $webform->save();
  }

  /**
  * Send Email invitations handler.
  *
  * @param array $form
  *   An associative array containing the structure of the form.
  * @param \Drupal\Core\Form\FormStateInterface $form_state
  *   The current state of the form.
  */
  public function sendEmailsHandler(array &$form, FormStateInterface $form_state) {

    /** @var \Drupal\webform\Entity\Webform $webform */
    $webform = $form_state->getValue('webform');
    $webform_id = $webform->id();
    $email_message = $webform->getThirdPartySetting(static::MODULE_NAME, 'email_message');
    $email_reminder_message = $webform->getThirdPartySetting(static::MODULE_NAME, 'email_reminder_message');
    $emails = $webform->getThirdPartySetting(static::MODULE_NAME, 'list_emails');
    $subject = $webform->getThirdPartySetting(static::MODULE_NAME, 'subject');
    $confidential_invitations = $webform->getThirdPartySetting(static::MODULE_NAME, 'confidential_invitations');
    //Get all codes
    $query = $this->database->select('webform_invitation_codes', 'c');
    $query->fields('c');
    $query->condition('webform', $webform_id);
    $query->isNotNull("email");
    $totalCodes = $query
    ->countQuery()->execute()->fetchField();


    //If confidential is enabled and codes already exists then give warning to delete submissions and codes before sending again codes since a new ones will be generated
    if ($confidential_invitations && $totalCodes > 0){
      drupal_set_message(t('Confidential codes were already sent. In order to send them again, you need to delete existing submissions and existing codes to avoid duplicate responses.'), 'error');
      return;
    }

    //Generate codes if necessary
    $assigned_emails = 0;
    $already_assigned_emails = 0;
    $invalid_emails = 0;

    //First expand emails
    $arrayEmails = $this->expandEgroupsEmails($webform_id, $emails, $invalid_emails);

    // Then generate codes
    $return_codes = $this->generateEmailsCodes($webform_id, $arrayEmails, $confidential_invitations, $assigned_emails, $already_assigned_emails);
    drupal_set_message(t('Number of emails with assigned codes: @number', array('@number' => $assigned_emails)), 'status');
    drupal_set_message(t('@number emails already had an assigned code. skipping them.', array('@number' => $already_assigned_emails)), 'status');
    if($invalid_emails) {
      drupal_set_message(t('@number invalid emails.', array('@number' => $invalid_emails)), 'warning');
    }

    // Get all codes from DB table, generated by email and not used
    // For those already used we don't need to send them an email
    $query = $this->database->select('webform_invitation_codes', 'c')
      ->fields('c');
    $query->condition('webform', $webform_id);
    $query->condition('used', 0);
    $query->isNotNull('email');
    $unused_codes = $query->execute()->fetchAll();
    if(!is_array($unused_codes)){
      $unused_codes = array();
    }
    //Transform array of objects into grouped array per email (since one array can have multiple codes assigned)
    foreach($unused_codes as $unused_code)
    {
      $unused_codes_per_email[$unused_code->email][$unused_code->cid] = $unused_code->code;
    }

    // For the last phase (sending emails) the behavior differes between confidential or not
    if ($confidential_invitations){
      // For condidential just rely on the return_codes just generated (no email stored on the database)
      $sending_array = &$return_codes;
    }
    else{
      // If not confidential, then use the data from the database
      $sending_array = &$unused_codes_per_email;
    }

    // Send emails
    $batch = [
      'title' => $this->t('Sending invitations...'),
      'operations' => [
        ['Drupal\webform_invitation_cern\Form\WebformInvitationEmailForm::batchStart',[]],
      ],
      'finished' => 'Drupal\webform_invitation_cern\Form\WebformInvitationEmailForm::batchFinished',
    ];

    $message = ($form_state->getTriggeringElement()['#name'] === 'email-reminder-button') ? $form_state->getValue('email_reminder_message')['value'] : $form_state->getValue('email_message')['value'];

    foreach($sending_array as $email => $codes){
      $urls = array();
      foreach ($codes as $cid => $code) {
        $url = Url::fromRoute('entity.webform.canonical', [
          'webform' => $webform_id,
        ], [
          'query' => [
            'code' => $code,
          ],
          'absolute' => TRUE,
        ])->toString();
        $urls[] = $url;
      }


      //Create the batch operation to send the email
      $batch['operations'][] = ['Drupal\webform_invitation_cern\Form\WebformInvitationEmailForm::batchProcess', [$email, $urls, $subject, $message, $webform_id]];
    }

    batch_set($batch);
  }

  /**
   * Batch callback; init number of emails sent
   */
  public static function batchStart(&$context){
    $context['results']['emails_sent'] = 0;
  }

  /**
   * Batch callback to process email sending
   */
  public static function batchProcess($email, $url, $subject, $message, $webform_id, &$context){

    $bubbleable_metadata = new BubbleableMetadata();
    $token = \Drupal::service('token');
    $mailManager = \Drupal::service('plugin.manager.mail');

    $final_message = $token->replace($message, ['url' => $url], [], $bubbleable_metadata);

    // Send message.
    // A key to identify the email sent. The final message ID will be {$module}_{$key}
    //$key = $this->getWebform()->id() . '_' . $this->getHandlerId();
    $key = $webform_id;
    $current_langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $system_site_config = \Drupal::config('system.site');
    $from = $system_site_config->get('mail');
    $params['message'] = $final_message;
    $params['subject'] = $subject;

    $result = $mailManager->mail("webform_invitation_cern", $key, $email, $current_langcode, $params, $from);

    \Drupal::logger('webform_invitation_cern_email')->notice("[$webform_id] Email to $email sent.");

    $context['message'] = "Sending emails...";
    $context['results']['emails_sent']++;
    if (!isset($context['results']['webform_id'])){
      $context['results']['webform_id'] = $webform_id;
    }
    // Sleep 0.05 second to not stress mail and/or get blocked (20 mails/sec)
    usleep(50000);
  }

  /**
   * Batch finished callback
   */
  public static function batchFinished($success, $results, $operations) {
    \Drupal::logger('webform_invitation_cern_email')->notice("[@webform_id] Total emails sent @count sent.", array('@webform_id'=> $results['webform_id'], '@count' => $results['emails_sent']));
    if($success){
      if($results['emails_sent']) {
        \Drupal::service('messenger')->addMessage(
          \Drupal::translation()->formatPlural($results['emails_sent'], '1 email sent.','@count emails sent.')
        );
      }
      else{
        \Drupal::service('messenger')
          ->addMessage(t('No emails sent.'));
      }
    }
    else{
      $error_operation = reset($operations);
      \Drupal::service('messenger')
        ->addMessage(t('An error occurred while sending emails.'));

    }
  }

  /**
  * Delete invitations handler.
  *
  * @param array $form
  *   An associative array containing the structure of the form.
  * @param \Drupal\Core\Form\FormStateInterface $form_state
  *   The current state of the form.
  */
  public function deleteInvitationsHandler(array &$form, FormStateInterface $form_state) {

    /** @var \Drupal\webform\Entity\Webform $webform */
    $webform = $form_state->getValue('webform');
    $webform_id = $webform->id();

    // Delete all codes for this webform instance.
    $query = $this->database->delete('webform_invitation_codes');
    $query->condition('webform', $webform_id);
    $num_deleted = $query->execute();
    drupal_set_message($this->t('@number Invitations deleted.',array('@number' => $num_deleted)));
  }


  private function generateNewUnassignedCode($webform_id) {
    # generate a new code, with retries
    $cid=null;
    $retries = 50;
    while ($retries > 0) {
      $code = md5(microtime(1) * rand());
      try {
        // Insert code to DB.
        $cid = $this->database->insert('webform_invitation_codes')->fields([
          'webform' => $webform_id,
          'code' => $code,
          'created' => $this->time->getRequestTime(),
        ])->execute();
        // Succeeded, stop retries
        $retries = -1;
      }
      catch (\Exception $e) {
        // The generated code is already in DB, make another one.
        $retries = $retries--;
      }
    }

    return array('cid' => $cid, 'code' => $code);
  }

  /**
   *
   * Process a list of emails and produces codes for each
   * @param $emails Array with the email addresses
   * @param $confidential bool to enable/disable the confidential mode
   * @param $assigned_emails reference variable to return the number of new codes assigned to emails
   * @param $already_assigned_emails reference variable to return the number emails that has already a code associated
   * @param $invalid_emails reference variable to return the number of invalid emails received as input
   * @return array with the generated codes, key contains the code and value the email
   *
   */
  private function generateEmailsCodes($webform_id, $emails, $confidential, &$assigned_emails, &$already_assigned_emails){
    //Reset counters
    $already_assigned_emails = 0;
    $assigned_emails = 0;
    $return_array = array();

    // Get all codes from DB table.
    $query = $this->database->select('webform_invitation_codes', 'c')
    ->fields('c');
    $query->condition('webform', $webform_id);
    //$query->condition('used', 0);
    $query->isNotNull('email');
    $codes = $query->execute()->fetchAll();

    if(!is_array($codes)){
      $codes = array();
    }


    foreach ($emails as $email => $number) {
      #Check if email is already assigned to a code
      $key = array_search($email, array_column($codes, 'email'));
      # Email already assigned to code/s
      if(!($key === FALSE)){
        $already_assigned_emails++;
        continue;
      }

      #Generate new codes, $number of them
      for ($i=0; $i<$number; $i++) {
        $next = $this->generateNewUnassignedCode($webform_id);
        //$return_array[$next['cid']] = $email;
        $return_array[$email][$next['cid']] = $next['code'];
        #Assign code to this email
        $this->database->update('webform_invitation_codes')
          ->fields(['email' => ($confidential) ? "CONFIDENTIAL" : $email])
          ->condition('cid', $next['cid'])
          ->execute();
      }

      $assigned_emails++;
    }

    return $return_array;
  }

  /**
   *  Given the input text list of emails and e-groups, returns the unique list of email addresses after:
   *    - expanding e-groups
   *    - remove invalid e-groups
   *    - remove invalid e-mails
   *
   *  @param emails String containing one line per email/e-group
   *  @param invalid_emails Integer Counter of invalid emails or e-groups on the list of emails
   *  */
  private function expandEgroupsEmails($webform_id, $emails, &$invalid_emails) {
    //Init counters
    $invalid_emails = 0;

    // Get all codes from DB table.
    $query = $this->database->select('webform_invitation_codes', 'c')
      ->fields('c');
    $query->condition('webform', $webform_id);
    //$query->condition('used', 0);
    $query->isNotNull('email');
    $codes = $query->execute()->fetchAll();

    if(!is_array($codes)){
      $codes = array();
    }

    #process each line
    $processed_emails = array();
    foreach (explode(PHP_EOL, $emails) as $email){
      $email = trim($email);

      //Check if the email contain a counter
      $split = explode("|", $email);
      $email = (isset($split[0])) ? $split[0] : "";
      $number = (isset($split[1])) ? (int)$split[1] : 1;

      //Skip empty lines
      if(empty($email)) {
        continue;
      }

      #validate email syntax
      if (!\Drupal::service('email.validator')->isValid($email)){
        $invalid_emails++;
        //try to get members from e-group
        $new_emails = array();
        $entries_visited = array();
        //Recursively goes to deeper e-groups
        $this->cernLdap->resolveEgroup($email, $new_emails, $entries_visited, TRUE);
        if (!empty($new_emails)) {
          //If some entries are returned we assume this was a valid e-group and therefore not an invalid email address
          $invalid_emails--;
        }
        foreach ($new_emails as $entry) {
          // If the entry already existed, we keep the number that was previously defined, otherwise only 1 code          
          $processed_emails["$entry"] = (isset($processed_emails["$entry"])) ? $processed_emails["$entry"] : $number ;
        }
        continue;
      }
      $processed_emails["$email"] = $number;
    }

    return $processed_emails;
  }

}
