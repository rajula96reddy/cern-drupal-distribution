<?php


function sendEmail($email, &$context) {
  $context['results'][] = $email;
  $context['message'] = t('Sending email to @email', array('@email' => $email));
}

function sendEmailFinishedCallback($success, $results, $operations) {
  // The 'success' parameter means no fatal PHP errors were detected. All
  // other error management should be handled using 'results'.
  if ($success) {
    $message = \Drupal::translation()->formatPlural(
      count($results),
      'One e-mail sent.', '@count e-mails sent.'
    );
  }
  else {
    $message = t('Finished with an error.');
  }
  drupal_set_message($message);
}
