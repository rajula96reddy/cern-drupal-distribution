<?php

namespace Drupal\welcome_message_block\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block with a welcome text and some important information regarding what is offered in the CERN infrastructure.
 *
 * @Block(
 *   id = "welcome_message_block",
 *   admin_label = @Translation("Welcome Message Block"),
 * )
 */
class WelcomeMessageBlock extends BlockBase {

	/**
	 * {@inheritdoc}
	 */
	public function build() {
		$host = \Drupal::request()->getSchemeAndHttpHost();
		return [
				'#title' => 'Welcome to your new Drupal site!',
				'#theme' => 'welcome_message_block_template',
				'#site_domain' => $host,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	protected function blockAccess(AccountInterface $account) {
		return AccessResult::allowedIfHasPermission($account, 'access content');
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$config = $this->getConfiguration();

		return $form;
	}

	/**
	 * {@inheritdoc}
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		$this->configuration['welcome_message_block_settings'] = $form_state->getValue('welcome_message_block_settings');
	}

}