<?php

namespace Drupal\taxonomy\Tests\Views;

use Drupal\Core\Language\LanguageInterface;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\views\Views;

/**
 * Tests the plugin of the taxonomy: term argument validator.
 *
 * @group taxonomy
 *
 * @see Views\taxonomy\Plugin\views\argument_validator\Term
 */
class ArgumentValidatorTermNameTest extends TaxonomyTestBase {

  /**
   * Stores the taxonomy term used by this test.
   *
   * @var array
   */
  protected $terms = [];

  /**
   * Stores the taxonomy names used by this test.
   *
   * @var array
   */
  protected $names = [];

  /**
   * The vocabulary used for creating terms that will interfere with "tags".
   *
   * @var \Drupal\taxonomy\VocabularyInterface
   */
  protected $vocabulary2;

  /**
   * Stores the taxonomy IDs used by this test.
   *
   * @var array
   */
  protected $ids = [];

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'taxonomy',
    'taxonomy_test_views',
    'views_test_config',
  ];

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = ['taxonomy_term_name_argument_test'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    // Create a second vocabulary for duplicate term and filtering by bundle.
    $this->vocabulary2 = Vocabulary::create([
      'name' => 'Other vocabulary tags',
      'vid' => 'other_testing_tags',
    ]);
    $this->vocabulary2->save();

    // Add a term to the new vocabulary with the same name as a term in the
    // first vocabulary.
    $settings = [
      'name' => $this->term1->getName(),
      'description' => $this->term1->getDescription(),
      'vid' => $this->vocabulary2->id(),
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ];
    $this->createTerm($settings);

    // Add a term to test for terms outside the configured bundle.
    $settings = [
      'name' => 'nonbundle',
      'description' => $this->randomMachineName(),
      'vid' => $this->vocabulary2->id(),
      'langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED,
    ];
    $this->createTerm($settings);
  }

  /**
   * Tests the term argument validator plugin.
   */
  public function testArgumentValidatorTermName() {
    $view = Views::getView('taxonomy_term_name_argument_test');
    $view->initHandlers();

    // Test a term that exists in only one vocabulary.
    $this->assertTrue($view->argument['name']->setArgument($this->term2->getName()));
    $this->assertEqual($view->argument['name']->getTitle(), $this->term2->label());
    $view->argument['name']->validated_title = NULL;
    $view->argument['name']->argument_validated = NULL;

    // Test a term that exists in at least two vocabularies.
    $this->assertTrue($view->argument['name']->setArgument($this->term1->getName()));
    $this->assertEqual($view->argument['name']->getTitle(), $this->term1->label());
    $view->argument['name']->validated_title = NULL;
    $view->argument['name']->argument_validated = NULL;

    // Test that multiple valid terms don't validate because multiple arguments
    // are currently not supported.
    $multiple_terms = $this->term2->getName() . '+' . $this->term1->getName();
    $this->assertFalse($view->argument['name']->setArgument($multiple_terms));
    $view->argument['name']->validated_title = NULL;
    $view->argument['name']->argument_validated = NULL;

    // Pass in an invalid term.
    $this->assertFalse($view->argument['name']->setArgument(rand(1000, 10000)));
    $view->argument['name']->validated_title = NULL;
    $view->argument['name']->argument_validated = NULL;

    // Test filtering by bundle by passing a term from a vocabulary that is not
    // in the bundle settings.
    $this->assertFalse($view->argument['name']->setArgument('nonbundle'));
    $view->argument['name']->validated_title = NULL;
    $view->argument['name']->argument_validated = NULL;
  }

}
