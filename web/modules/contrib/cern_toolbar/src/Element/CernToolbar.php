<?php

namespace Drupal\cern_toolbar\Element;

use Drupal\Component\Utility\Html;

use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Render\Element\RenderElement;
use Drupal\Core\Render\Element;
use Drupal\simplesamlphp_auth\Service\SimplesamlphpAuthManager;
use function Sodium\add;

/**
 * Provides a render element for the default Drupal toolbar.
 *
 * @RenderElement("cern_toolbar")
 */
class CernToolbar extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#pre_render' => [
        [$class, 'preRenderToolbar'],
      ],
      '#theme' => 'cern_toolbar',
      '#attached' => [
        'library' => [
          'cern_toolbar/cern_toolbar',
        ],
      ],
      // Metadata for the toolbar wrapping element.
      '#attributes' => [
        // The id cannot be simply "toolbar" or it will clash with the
        // simpletest tests listing which produces a checkbox with attribute
        // id="toolbar".
        'id' => 'cern-toolbar',
        'role' => 'group',
        'aria-label' => $this->t('CERN Toolbar'),
      ],
      // Metadata for the administration bar.
      '#bar' => [
        '#heading' => $this->t('Toolbar items'),
        '#attributes' => [
          'id' => 'toolbar-bar',
          'role' => 'navigation',
          'aria-label' => $this->t('Toolbar items'),
        ],
      ],
    ];
  }

  /**
   * Builds the Toolbar as a structured array ready for drupal_render().
   *
   * Since building the toolbar takes some time, it is done just prior to
   * rendering to ensure that it is built only if it will be displayed.
   *
   * @param array $element
   *   A renderable array.
   *
   * @return array
   *   A renderable array.
   *
   * @see toolbar_page_top()
   */
  public static function preRenderToolbar($element) {
    $items_toolbar_links = [];
    if(\Drupal::currentUser()->isAnonymous()) {
      $element['#attributes']['class'] =  'user-not-authenticated';
      $items_toolbar_links[] = [
        '#type' => 'link',
        '#title'=> t('Sign in'),
        '#url'=> Url::fromRoute('user.login'),
        '#wrapper_attributes' => [
          'class' => [
            'cern-account-links',
          ],
        ],
        '#attributes' => ['class' => 'cern-account cern-signin cern-single-mobile-signin'],
      ];

      $config = \Drupal::config('cern_toolbar.settings');
      $additional_links_count = count($config->getRawData());
      if($additional_links_count > 0) {
        $items_toolbar_links[0]['#title'] = t('As CERN account');
        $items_toolbar_links[0]['#attributes'] = ['class' => 'cern-account cern-multiple-mobile-signin'];
        for ($i = 0; $i < $additional_links_count / 2; $i++) {
          if(!empty($config->get('additional_link_title_'.$i)) && !empty($config->get('additional_link_'.$i))) {
            $items_toolbar_links[] = [
              '#type' => 'link',
              '#title' => $config->get('additional_link_title_'.$i),
              '#url' => \Drupal\Core\Url::fromUri($config->get('additional_link_'.$i)),
              '#wrapper_attributes' => [
                'class' => [
                  'cern-account-links',
                ],
              ],
              '#attributes' => ['class' => 'cern-account cern-multiple-mobile-signin']
            ];
          }
        }
      }
    } else {
      $element['#attributes']['class'] =  'user-authenticated';
      if (\Drupal::moduleHandler()->moduleExists('simplesamlphp_auth')) {
        $simpleSamlPHPAuthManager = self::getSimpleSamlPHPAuthManager();
        $simplesaml_attributes = $simpleSamlPHPAuthManager->getAttributes();
        $username = $simplesaml_attributes['login'][0];
        $federation = $simplesaml_attributes['http://schemas.xmlsoap.org/claims/Federation'][0];
        $email_address = $simplesaml_attributes['http://schemas.xmlsoap.org/claims/UPN'][0];
        $authlevel = $simplesaml_attributes['http://schemas.xmlsoap.org/claims/AuthLevel'][0];
        $fullname = $simplesaml_attributes['fullname'][0];
        $identity_class = $simplesaml_attributes['identityclass'][0];
      } else {
        $fullname = \Drupal::currentUser()->getDisplayName();
        $username = \Drupal::currentUser()->getAccountName();
        $federation = 'Drupal';
        $authlevel = 'Core';
        $identity_class = NULL;
      }

      $account_info = [
        '#type' => 'link',
        '#title'=> t('%username (%federation)', [
          '%username' => $username,
          '%federation' => $federation,
        ]),
        '#url'=> URL::fromUri('http://cern.ch/account'),
        '#attributes' => [
          'class' => 'account  cern-multiple-mobile-signin',
          'title' => t('Signed in as: %fullname (%username) using %auth_level authentication',
            [
              '%fullname' => $fullname,
              '%username' => $username,
              '%auth_level' => $authlevel,
            ]
          )
        ],
        '#prefix' => '<span>'.t('Signed in as:'),
        '#suffix' => '</span>'
      ];

      switch ($identity_class) {
        case 'CERN Registered':
          //<a href="http://cern.ch/account" title="Signed in as Joe Bloggs (jbloggs)">Signed in as: jbloggs (CERN)</a>
          // this is the default string
          break;
        case 'CERN Shared':
          //<a href="http://cern.ch/account" title="Signed in as Joe Bloggs (libframe)">Signed in as: libframe (CERN)</a>
          // this is the default string
          break;
        case 'HEP Trusted':
          //Signed in as: username (Institute name)</li>
          $account_info = [
            '#markup'=> t('Signed in as: %username %federation',
              ['%username' => $username, '%federation' => $federation]),
          ];
          break;
        case 'Verified External':
          $account_info = [
            '#type' => 'link',
            '#title'=> $email_address,
            '#url'=> '//cern.ch/account',
            '#attributes' => [
              'class' => 'account',
              'title' => t('Signed in with a verified external account')
            ],
            '#prefix' => '<span>'.t('Signed in as:'),
            '#suffix' => '</span>'
          ];
          break;
        case 'Unverified External':
          //Signed in as: johnnylongname@yahoo.fr (Facebook)
          $account_info = [
            '#markup'=> t('Signed in as: %email_address %federation',
              ['%email_address' => $email_address, '%federation' => $federation]),
          ];
          break;
        default:
          // this is the default string
          break;
      }
      $items_toolbar_links[] = $account_info;
      $items_toolbar_links[] = [
        '#type' => 'link',
        '#title'=> t('Sign out'),
        '#url'=> Url::fromRoute('user.logout'),
        '#attributes' => [
          'class' => 'cern-signout  cern-multiple-mobile-signin',
          'title' => t('Sign out of your account'),
        ],
      ];
    }
    $element['#directory_link'] = [
      '#type' => 'link',
      '#title'=> t('Directory'),
      '#url'=> URL::fromUri('//cern.ch/directory'),
      '#attributes' => [
        'class' => 'cern-directory',
        'title' => t('Search CERN resources and browse the directory'),
      ],
      '#wrapper_attributes' => [
        'class' => [
          'cern-directory-link',
        ],
      ],
    ];
    $element['#logged_in'] = !\Drupal::currentUser()->isAnonymous();
    $element['#toolbar_links'] = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#title' => '',
      '#items' => $items_toolbar_links,
      '#attributes' => ['class' => ['cern-signedin', 'toolbar-submenu']],
    ];

    return $element;
  }

  /**
   * @return SimplesamlphpAuthManager
   */
  public static function getSimpleSamlPHPAuthManager() {
    return \Drupal::service('simplesamlphp_auth.manager');
  }
}
