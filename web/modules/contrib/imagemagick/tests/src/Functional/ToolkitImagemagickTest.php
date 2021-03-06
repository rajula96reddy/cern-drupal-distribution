<?php

namespace Drupal\Tests\imagemagick\Functional;

use Drupal\Core\Image\ImageInterface;
use Drupal\imagemagick\EventSubscriber\ImagemagickEventSubscriber;
use Drupal\Tests\BrowserTestBase;
use Drupal\imagemagick\ImagemagickExecArguments;
use Drupal\Tests\imagemagick\Kernel\ToolkitSetupTrait;

/**
 * Tests that core image manipulations work properly through Imagemagick.
 *
 * @group Imagemagick
 */
class ToolkitImagemagickTest extends BrowserTestBase {

  use ToolkitSetupTrait;

  // Colors that are used in testing.
  // @codingStandardsIgnoreStart
  protected $black             = [  0,   0,   0,   0];
  protected $red               = [255,   0,   0,   0];
  protected $green             = [  0, 255,   0,   0];
  protected $blue              = [  0,   0, 255,   0];
  protected $yellow            = [255, 255,   0,   0];
  protected $fuchsia           = [255,   0, 255,   0];
  protected $cyan              = [  0, 255, 255,   0];
  protected $white             = [255, 255, 255,   0];
  protected $grey              = [128, 128, 128,   0];
  protected $transparent       = [  0,   0,   0, 127];
  protected $rotateTransparent = [255, 255, 255, 127];

  protected $width = 40;
  protected $height = 20;
  // @codingStandardsIgnoreEnd

  /**
   * Modules to enable.
   *
   * Enable 'file_test' to be able to work with dummy_remote:// stream wrapper.
   *
   * @var array
   */
  public static $modules = ['system', 'imagemagick', 'file_mdm', 'file_test'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Create an admin user.
    $admin_user = $this->drupalCreateUser([
      'administer site configuration',
    ]);
    $this->drupalLogin($admin_user);

    // Set the image factory.
    $this->imageFactory = $this->container->get('image.factory');

    // Prepare a directory for test file results.
    $this->testDirectory = 'public://imagetest';
    // Prepare directory.
    file_unmanaged_delete_recursive($this->testDirectory);
    file_prepare_directory($this->testDirectory, FILE_CREATE_DIRECTORY);
  }

  /**
   * Test removal of temporary files created during operations on remote files.
   *
   * @param string $toolkit_id
   *   The id of the toolkit to set up.
   * @param string $toolkit_config
   *   The config object of the toolkit to set up.
   * @param array $toolkit_settings
   *   The settings of the toolkit to set up.
   *
   * @dataProvider providerToolkitConfiguration
   */
  public function testTemporaryRemoteCopiesDeletion($toolkit_id, $toolkit_config, array $toolkit_settings) {
    $this->setUpToolkit($toolkit_id, $toolkit_config, $toolkit_settings);
    $this->prepareImageFileHandling();

    // Get metadata from a remote file.
    $image = $this->imageFactory->get('dummy-remote://image-test.png');
    $image->getToolkit()->getExifOrientation();
    $this->assertCount(1, file_scan_directory('temporary://', '/ima.*/'), 'A temporary file has been created for getting metadata from a remote file.');

    // Simulate Drupal shutdown.
    $callbacks = drupal_register_shutdown_function();
    foreach ($callbacks as $callback) {
      if ($callback['callback'] === [ImagemagickEventSubscriber::class, 'removeTemporaryRemoteCopy']) {
        call_user_func_array($callback['callback'], $callback['arguments']);
      }
    }

    // Ensure we have no leftovers in the temporary directory.
    $this->assertCount(0, file_scan_directory('temporary://', '/ima.*/'), 'No files left in the temporary directory after the Drupal shutdown.');
  }

  /**
   * Test image toolkit operations.
   *
   * Since PHP can't visually check that our images have been manipulated
   * properly, build a list of expected color values for each of the corners and
   * the expected height and widths for the final images.
   *
   * @param string $toolkit_id
   *   The id of the toolkit to set up.
   * @param string $toolkit_config
   *   The config object of the toolkit to set up.
   * @param array $toolkit_settings
   *   The settings of the toolkit to set up.
   *
   * @dataProvider providerToolkitConfiguration
   */
  public function testManipulations($toolkit_id, $toolkit_config, array $toolkit_settings) {
    $this->setUpToolkit($toolkit_id, $toolkit_config, $toolkit_settings);
    $this->prepareImageFileHandling();

    // Typically the corner colors will be unchanged. These colors are in the
    // order of top-left, top-right, bottom-right, bottom-left.
    $default_corners = [
      $this->red,
      $this->green,
      $this->blue,
      $this->transparent,
    ];

    // A list of files that will be tested.
    $files = [
      'image-test.png',
      'image-test.gif',
      'image-test-no-transparency.gif',
      'image-test.jpg',
    ];

    // Setup a list of tests to perform on each type.
    $operations = [
      'resize' => [
        'function' => 'resize',
        'arguments' => ['width' => 20, 'height' => 10],
        'width' => 20,
        'height' => 10,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'scale_x' => [
        'function' => 'scale',
        'arguments' => ['width' => 20],
        'width' => 20,
        'height' => 10,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'scale_y' => [
        'function' => 'scale',
        'arguments' => ['height' => 10],
        'width' => 20,
        'height' => 10,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'upscale_x' => [
        'function' => 'scale',
        'arguments' => ['width' => 80, 'upscale' => TRUE],
        'width' => 80,
        'height' => 40,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'upscale_y' => [
        'function' => 'scale',
        'arguments' => ['height' => 40, 'upscale' => TRUE],
        'width' => 80,
        'height' => 40,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'crop' => [
        'function' => 'crop',
        'arguments' => ['x' => 12, 'y' => 4, 'width' => 16, 'height' => 12],
        'width' => 16,
        'height' => 12,
        'corners' => array_fill(0, 4, $this->white),
        'tolerance' => 0,
      ],
      'scale_and_crop' => [
        'function' => 'scale_and_crop',
        'arguments' => ['width' => 10, 'height' => 8],
        'width' => 10,
        'height' => 8,
        'corners' => array_fill(0, 4, $this->black),
        'tolerance' => 100,
      ],
      'convert_jpg' => [
        'function' => 'convert',
        'width' => 40,
        'height' => 20,
        'arguments' => ['extension' => 'jpeg'],
        'mimetype' => 'image/jpeg',
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'convert_gif' => [
        'function' => 'convert',
        'width' => 40,
        'height' => 20,
        'arguments' => ['extension' => 'gif'],
        'mimetype' => 'image/gif',
        'corners' => $default_corners,
        'tolerance' => 15,
      ],
      'convert_png' => [
        'function' => 'convert',
        'width' => 40,
        'height' => 20,
        'arguments' => ['extension' => 'png'],
        'mimetype' => 'image/png',
        'corners' => $default_corners,
        'tolerance' => 5,
      ],
      'rotate_5' => [
        'function' => 'rotate',
        'arguments' => [
          'degrees' => 5,
          'background' => '#FF00FF',
          'resize_filter' => 'Box',
        ],
        'width' => 41,
        'height' => 23,
        'corners' => array_fill(0, 4, $this->fuchsia),
        'tolerance' => 5,
      ],
      'rotate_minus_10' => [
        'function' => 'rotate',
        'arguments' => [
          'degrees' => -10,
          'background' => '#FF00FF',
          'resize_filter' => 'Box',
        ],
        'width' => 41,
        'height' => 26,
        'corners' => array_fill(0, 4, $this->fuchsia),
        'tolerance' => 15,
      ],
      'rotate_90' => [
        'function' => 'rotate',
        'arguments' => ['degrees' => 90, 'background' => '#FF00FF'],
        'width' => 20,
        'height' => 40,
        'corners' => [$this->transparent, $this->red, $this->green, $this->blue],
        'tolerance' => 0,
      ],
      'rotate_transparent_5' => [
        'function' => 'rotate',
        'arguments' => ['degrees' => 5, 'resize_filter' => 'Box'],
        'width' => 41,
        'height' => 23,
        'corners' => array_fill(0, 4, $this->transparent),
        'tolerance' => 0,
      ],
      'rotate_transparent_90' => [
        'function' => 'rotate',
        'arguments' => ['degrees' => 90],
        'width' => 20,
        'height' => 40,
        'corners' => [$this->transparent, $this->red, $this->green, $this->blue],
        'tolerance' => 0,
      ],
      'desaturate' => [
        'function' => 'desaturate',
        'arguments' => [],
        'height' => 20,
        'width' => 40,
        // Grayscale corners are a bit funky. Each of the corners are a shade of
        // gray. The values of these were determined simply by looking at the
        // final image to see what desaturated colors end up being.
        'corners' => [
          array_fill(0, 3, 76) + [3 => 0],
          array_fill(0, 3, 149) + [3 => 0],
          array_fill(0, 3, 29) + [3 => 0],
          array_fill(0, 3, 225) + [3 => 127],
        ],
        // @todo tolerance here is too high. Check reasons.
        'tolerance' => 17000,
      ],
    ];

    foreach ($files as $file) {
      $image_uri = 'public://' . $file;
      foreach ($operations as $op => $values) {
        // Load up a fresh image.
        $image = $this->imageFactory->get($image_uri);
        if (!$image->isValid()) {
          $this->fail("Could not load image $file.");
          continue 2;
        }

        // Check that no multi-frame information is set.
        $this->assertSame(1, $image->getToolkit()->getFrames());

        // Perform our operation.
        $image->apply($values['function'], $values['arguments']);

        // Save and reload image.
        $file_path = $this->testDirectory . '/' . $op . substr($file, -4);
        $this->assertTrue($image->save($file_path));
        $image = $this->imageFactory->get($file_path);
        $this->assertTrue($image->isValid());

        // @todo Suite specifics, temporarily adjust tests.
        $package = $image->getToolkit()->getExecManager()->getPackage();
        if ($package === 'graphicsmagick') {
          // @todo Issues with crop and convert on GIF files, investigate.
          if (in_array($file, [
            'image-test.gif', 'image-test-no-transparency.gif',
          ]) && in_array($op, [
            'crop', 'scale_and_crop', 'convert_png',
          ])) {
            continue;
          }
        }
        if ($package === 'imagemagick') {
          // @todo Issues with crop and convert on GIF files, investigate.
          if (in_array($file, [
            'image-test.gif', 'image-test-no-transparency.gif',
          ]) && in_array($op, [
            'crop', 'scale_and_crop',
          ])) {
            continue;
          }
        }

        // Reload with GD to be able to check results at pixel level.
        $image = $this->imageFactory->get($file_path, 'gd');
        $toolkit = $image->getToolkit();
        $toolkit->getResource();
        $this->assertTrue($image->isValid());

        // Check MIME type if needed.
        if (isset($values['mimetype'])) {
          $this->assertEquals($values['mimetype'], $toolkit->getMimeType(), "Image '$file' after '$op' action has proper MIME type ({$values['mimetype']}).");
        }

        // To keep from flooding the test with assert values, make a general
        // value for whether each group of values fail.
        $correct_dimensions_real = TRUE;
        $correct_dimensions_object = TRUE;

        // Check the real dimensions of the image first.
        $actual_toolkit_width = imagesx($toolkit->getResource());
        $actual_toolkit_height = imagesy($toolkit->getResource());
        if ($actual_toolkit_height != $values['height'] || $actual_toolkit_width != $values['width']) {
          $correct_dimensions_real = FALSE;
        }

        // Check that the image object has an accurate record of the dimensions.
        $actual_image_width = $image->getWidth();
        $actual_image_height = $image->getHeight();
        if ($actual_image_width != $values['width'] || $actual_image_height != $values['height']) {
          $correct_dimensions_object = FALSE;
        }

        $this->assertTrue($correct_dimensions_real, "Image '$file' after '$op' action has proper dimensions. Expected {$values['width']}x{$values['height']}, actual {$actual_toolkit_width}x{$actual_toolkit_height}.");
        $this->assertTrue($correct_dimensions_object, "Image '$file' object after '$op' action is reporting the proper height and width values.  Expected {$values['width']}x{$values['height']}, actual {$actual_image_width}x{$actual_image_height}.");

        // JPEG colors will always be messed up due to compression.
        if ($image->getToolkit()->getType() != IMAGETYPE_JPEG) {
          // Now check each of the corners to ensure color correctness.
          foreach ($values['corners'] as $key => $corner) {
            // The test gif that does not have transparency has yellow where the
            // others have transparent.
            if ($file === 'image-test-no-transparency.gif' && $corner === $this->transparent && $op != 'rotate_transparent_5') {
              $corner = $this->yellow;
            }
            // The test jpg when converted to other formats has yellow where the
            // others have transparent.
            if ($file === 'image-test.jpg' && $corner === $this->transparent && in_array($op, ['convert_gif', 'convert_png'])) {
              $corner = $this->yellow;
            }
            // Get the location of the corner.
            switch ($key) {
              case 0:
                $x = 0;
                $y = 0;
                break;

              case 1:
                $x = $image->getWidth() - 1;
                $y = 0;
                break;

              case 2:
                $x = $image->getWidth() - 1;
                $y = $image->getHeight() - 1;
                break;

              case 3:
                $x = 0;
                $y = $image->getHeight() - 1;
                break;

            }
            $color = $this->getPixelColor($image, $x, $y);
            $this->colorsAreClose($color, $corner, $values['tolerance'], $file, $op);
          }
        }
      }
    }

    // Test loading non-existing image files.
    $this->assertFalse($this->imageFactory->get('/generic.png')->isValid());
    $this->assertFalse($this->imageFactory->get('public://generic.png')->isValid());

    // Test creation of image from scratch, and saving to storage.
    foreach ([IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_JPEG] as $type) {
      $image = $this->imageFactory->get();
      $image->createNew(50, 20, image_type_to_extension($type, FALSE), '#ffff00');
      $file = 'from_null' . image_type_to_extension($type);
      $file_path = $this->testDirectory . '/' . $file;
      $this->assertEquals(50, $image->getWidth(), "Image file '$file' has the correct width.");
      $this->assertEquals(20, $image->getHeight(), "Image file '$file' has the correct height.");
      $this->assertEquals(image_type_to_mime_type($type), $image->getMimeType(), "Image file '$file' has the correct MIME type.");
      $this->assertTrue($image->save($file_path), "Image '$file' created anew from a null image was saved.");

      // Reload saved image.
      $image_reloaded = $this->imageFactory->get($file_path, 'gd');
      if (!$image_reloaded->isValid()) {
        $this->fail("Could not load image '$file'.");
        continue;
      }
      $this->assertEquals(50, $image_reloaded->getWidth(), "Image file '$file' has the correct width.");
      $this->assertEquals(20, $image_reloaded->getHeight(), "Image file '$file' has the correct height.");
      $this->assertEquals(image_type_to_mime_type($type), $image_reloaded->getMimeType(), "Image file '$file' has the correct MIME type.");
      if ($image_reloaded->getToolkit()->getType() == IMAGETYPE_GIF) {
        $this->assertEquals('#ffff00', $image_reloaded->getToolkit()->getTransparentColor(), "Image file '$file' has the correct transparent color channel set.");
      }
      else {
        $this->assertEquals(NULL, $image_reloaded->getToolkit()->getTransparentColor(), "Image file '$file' has no color channel set.");
      }
    }

    // Test saving image files with filenames having non-ascii characters.
    $file_names = [
      'greek ???????????? ??????????????.png',
      'russian ???????????????? ??????????????????????.png',
      'simplified chinese ????????????.png',
      'japanese ????????????.png',
      'arabic ???????? ????????????????.png',
      'armenian ???????????????????? ??????????????.png',
      'bengali ????????????????????? ????????????.png',
      'hebraic ?????????? ??????????.png',
      'hindi ????????????????????? ?????????.png',
      'viet h??nh ???nh th??? nghi???m.png',
      'viet \'with quotes\' h??nh ???nh th??? nghi???m.png',
      'viet "with double quotes" h??nh ???nh th??? nghi???m.png',
    ];
    foreach ($file_names as $file) {
      // @todo on Windows, GraphicsMagick fails.
      if (substr(PHP_OS, 0, 3) === 'WIN' && $toolkit_settings['binaries'] === 'graphicsmagick') {
        continue;
      }
      // On Windows, skip filenames with non-allowed characters.
      if (substr(PHP_OS, 0, 3) === 'WIN' && preg_match('/[:*?"<>|]/', $file)) {
        continue;
      }
      $image = $this->imageFactory->get();
      $this->assertTrue($image->createNew(50, 20, 'png'));
      $file_path = $this->testDirectory . '/' . $file;
      $this->assertTrue($image->save($file_path), $file);
      $image_reloaded = $this->imageFactory->get($file_path, 'gd');
      $this->assertTrue($image_reloaded->isValid(), "Image file '$file' loaded successfully.");
    }

    // Test handling a file stored through a remote stream wrapper.
    $image = $this->imageFactory->get('dummy-remote://image-test.png');
    // Source file should be equal to the copied local temp source file.
    $this->assertEquals(filesize('dummy-remote://image-test.png'), filesize($image->getToolkit()->arguments()->getSourceLocalPath()));
    $image->desaturate();
    $this->assertTrue($image->save('dummy-remote://remote-image-test.png'));
    // Destination file should exists, and destination local temp file should
    // have been reset.
    $this->assertTrue(file_exists($image->getToolkit()->arguments()->getDestination()));
    $this->assertEquals('dummy-remote://remote-image-test.png', $image->getToolkit()->arguments()->getDestination());
    $this->assertSame('', $image->getToolkit()->arguments()->getDestinationLocalPath());

    // Test retrieval of EXIF information.
    file_unmanaged_copy(drupal_get_path('module', 'imagemagick') . '/misc/test-exif.jpeg', 'public://', FILE_EXISTS_REPLACE);
    // The image files that will be tested.
    $image_files = [
      [
        'path' => drupal_get_path('module', 'imagemagick') . '/misc/test-exif.jpeg',
        'orientation' => 8,
      ],
      [
        'path' => 'public://test-exif.jpeg',
        'orientation' => 8,
      ],
      [
        'path' => 'dummy-remote://test-exif.jpeg',
        'orientation' => 8,
      ],
      [
        'path' => 'public://image-test.jpg',
        'orientation' => NULL,
      ],
      [
        'path' => 'public://image-test.png',
        'orientation' => NULL,
      ],
      [
        'path' => 'public://image-test.gif',
        'orientation' => NULL,
      ],
      [
        'path' => NULL,
        'orientation' => NULL,
      ],
    ];
    foreach ($image_files as $image_file) {
      // Get image using 'identify'.
      \Drupal::configFactory()->getEditable('imagemagick.settings')
        ->set('use_identify', TRUE)
        ->save();
      $image = $this->imageFactory->get($image_file['path']);
      $this->assertSame($image_file['orientation'], $image->getToolkit()->getExifOrientation());
    }

    // Test multi-frame GIF image.
    $image_files = [
      [
        'source' => drupal_get_path('module', 'imagemagick') . '/misc/test-multi-frame.gif',
        'destination' => $this->testDirectory . '/test-multi-frame.gif',
        'width' => 60,
        'height' => 29,
        'frames' => 13,
        'scaled_width' => 30,
        'scaled_height' => 15,
        'rotated_width' => 33,
        'rotated_height' => 26,
      ],
    ];
    // Get images using 'identify'.
    \Drupal::configFactory()->getEditable('imagemagick.settings')
      ->set('use_identify', TRUE)
      ->save();
    foreach ($image_files as $image_file) {
      $image = $this->imageFactory->get($image_file['source']);
      $this->assertSame($image_file['width'], $image->getWidth());
      $this->assertSame($image_file['height'], $image->getHeight());
      $this->assertSame($image_file['frames'], $image->getToolkit()->getFrames());

      // Scaling should preserve frames.
      $image->scale(30);
      $this->assertTrue($image->save($image_file['destination']));
      $image = $this->imageFactory->get($image_file['destination']);
      $this->assertSame($image_file['scaled_width'], $image->getWidth());
      $this->assertSame($image_file['scaled_height'], $image->getHeight());
      $this->assertSame($image_file['frames'], $image->getToolkit()->getFrames());

      // Rotating should preserve frames.
      $image->rotate(24);
      $this->assertTrue($image->save($image_file['destination']));
      $image = $this->imageFactory->get($image_file['destination']);
      $this->assertSame($image_file['rotated_width'], $image->getWidth());
      $this->assertSame($image_file['rotated_height'], $image->getHeight());
      $this->assertSame($image_file['frames'], $image->getToolkit()->getFrames());

      // Converting to PNG should drop frames.
      $image->convert('png');
      $this->assertTrue($image->save($image_file['destination']));
      $image = $this->imageFactory->get($image_file['destination']);
      $this->assertSame(1, $image->getToolkit()->getFrames());
      $this->assertSame($image_file['rotated_width'], $image->getWidth());
      $this->assertSame($image_file['rotated_height'], $image->getHeight());
      $this->assertSame(1, $image->getToolkit()->getFrames());
    }
  }

  /**
   * Legacy methods tests.
   *
   * @param string $toolkit_id
   *   The id of the toolkit to set up.
   * @param string $toolkit_config
   *   The config object of the toolkit to set up.
   * @param array $toolkit_settings
   *   The settings of the toolkit to set up.
   *
   * @dataProvider providerToolkitConfiguration
   *
   * @group legacy
   */
  public function testManipulationsLegacy($toolkit_id, $toolkit_config, array $toolkit_settings) {
    $this->setUpToolkit($toolkit_id, $toolkit_config, $toolkit_settings);

    // Check package.
    $toolkit = \Drupal::service('image.toolkit.manager')->createInstance('imagemagick');
    $this->assertSame($toolkit_settings['binaries'], $toolkit->getPackage());
    $this->assertNotNull($toolkit->getPackageLabel());
    $this->assertSame([], $toolkit->checkPath('')['errors']);

    // Typically the corner colors will be unchanged. These colors are in the
    // order of top-left, top-right, bottom-right, bottom-left.
    $default_corners = [
      $this->red,
      $this->green,
      $this->blue,
      $this->transparent,
    ];

    // A list of files that will be tested.
    $files = [
      'image-test.png',
      'image-test.gif',
      'image-test-no-transparency.gif',
      'image-test.jpg',
    ];

    // Setup a list of tests to perform on each type.
    $operations = [
      'resize' => [
        'function' => 'resize',
        'arguments' => ['width' => 20, 'height' => 10],
        'width' => 20,
        'height' => 10,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'scale_x' => [
        'function' => 'scale',
        'arguments' => ['width' => 20],
        'width' => 20,
        'height' => 10,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'scale_y' => [
        'function' => 'scale',
        'arguments' => ['height' => 10],
        'width' => 20,
        'height' => 10,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'upscale_x' => [
        'function' => 'scale',
        'arguments' => ['width' => 80, 'upscale' => TRUE],
        'width' => 80,
        'height' => 40,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'upscale_y' => [
        'function' => 'scale',
        'arguments' => ['height' => 40, 'upscale' => TRUE],
        'width' => 80,
        'height' => 40,
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'crop' => [
        'function' => 'crop',
        'arguments' => ['x' => 12, 'y' => 4, 'width' => 16, 'height' => 12],
        'width' => 16,
        'height' => 12,
        'corners' => array_fill(0, 4, $this->white),
        'tolerance' => 0,
      ],
      'scale_and_crop' => [
        'function' => 'scale_and_crop',
        'arguments' => ['width' => 10, 'height' => 8],
        'width' => 10,
        'height' => 8,
        'corners' => array_fill(0, 4, $this->black),
        'tolerance' => 100,
      ],
      'convert_jpg' => [
        'function' => 'convert',
        'width' => 40,
        'height' => 20,
        'arguments' => ['extension' => 'jpeg'],
        'mimetype' => 'image/jpeg',
        'corners' => $default_corners,
        'tolerance' => 0,
      ],
      'convert_gif' => [
        'function' => 'convert',
        'width' => 40,
        'height' => 20,
        'arguments' => ['extension' => 'gif'],
        'mimetype' => 'image/gif',
        'corners' => $default_corners,
        'tolerance' => 15,
      ],
      'convert_png' => [
        'function' => 'convert',
        'width' => 40,
        'height' => 20,
        'arguments' => ['extension' => 'png'],
        'mimetype' => 'image/png',
        'corners' => $default_corners,
        'tolerance' => 5,
      ],
      'rotate_5' => [
        'function' => 'rotate',
        'arguments' => [
          'degrees' => 5,
          'background' => '#FF00FF',
          'resize_filter' => 'Box',
        ],
        'width' => 41,
        'height' => 23,
        'corners' => array_fill(0, 4, $this->fuchsia),
        'tolerance' => 5,
      ],
      'rotate_minus_10' => [
        'function' => 'rotate',
        'arguments' => [
          'degrees' => -10,
          'background' => '#FF00FF',
          'resize_filter' => 'Box',
        ],
        'width' => 41,
        'height' => 26,
        'corners' => array_fill(0, 4, $this->fuchsia),
        'tolerance' => 15,
      ],
      'rotate_90' => [
        'function' => 'rotate',
        'arguments' => ['degrees' => 90, 'background' => '#FF00FF'],
        'width' => 20,
        'height' => 40,
        'corners' => [$this->transparent, $this->red, $this->green, $this->blue],
        'tolerance' => 0,
      ],
      'rotate_transparent_5' => [
        'function' => 'rotate',
        'arguments' => ['degrees' => 5, 'resize_filter' => 'Box'],
        'width' => 41,
        'height' => 23,
        'corners' => array_fill(0, 4, $this->transparent),
        'tolerance' => 0,
      ],
      'rotate_transparent_90' => [
        'function' => 'rotate',
        'arguments' => ['degrees' => 90],
        'width' => 20,
        'height' => 40,
        'corners' => [$this->transparent, $this->red, $this->green, $this->blue],
        'tolerance' => 0,
      ],
      'desaturate' => [
        'function' => 'desaturate',
        'arguments' => [],
        'height' => 20,
        'width' => 40,
        // Grayscale corners are a bit funky. Each of the corners are a shade of
        // gray. The values of these were determined simply by looking at the
        // final image to see what desaturated colors end up being.
        'corners' => [
          array_fill(0, 3, 76) + [3 => 0],
          array_fill(0, 3, 149) + [3 => 0],
          array_fill(0, 3, 29) + [3 => 0],
          array_fill(0, 3, 225) + [3 => 127],
        ],
        // @todo tolerance here is too high. Check reasons.
        'tolerance' => 17000,
      ],
    ];

    // Prepare a copy of test files.
    $this->getTestFiles('image');

    foreach ($files as $file) {
      $image_uri = 'public://' . $file;
      foreach ($operations as $op => $values) {
        // Load up a fresh image.
        $image = $this->imageFactory->get($image_uri);
        if (!$image->isValid()) {
          $this->fail("Could not load image $file.");
          continue 2;
        }

        // Check that no multi-frame information is set.
        $this->assertSame(1, $image->getToolkit()->getFrames());

        // Legacy source tests.
        $this->assertSame($image_uri, $image->getToolkit()->getSource());
        $this->assertSame($image->getToolkit()->arguments()->getSourceLocalPath(), $image->getToolkit()->getSourceLocalPath());
        $this->assertSame($image->getToolkit()->arguments()->getSourceFormat(), $image->getToolkit()->getSourceFormat());

        // Perform our operation.
        $image->apply($values['function'], $values['arguments']);

        // Save image.
        $file_path = $this->testDirectory . '/' . $op . substr($file, -4);
        $this->assertTrue($image->save($file_path));

        // Legacy destination tests.
        $this->assertSame($file_path, $image->getToolkit()->getDestination());
        $this->assertSame('', $image->getToolkit()->getDestinationLocalPath());
        $this->assertNotNull($image->getToolkit()->arguments()->getSourceFormat(), $image->getToolkit()->getDestinationFormat());

        // Reload image.
        $image = $this->imageFactory->get($file_path);
        $this->assertTrue($image->isValid());

        // Legacy set methods.
        $image->getToolkit()->setSourceLocalPath('bar');
        $image->getToolkit()->setSourceFormat('PNG');
        $image->getToolkit()->setDestination('foo');
        $image->getToolkit()->setDestinationLocalPath('baz');
        $image->getToolkit()->setDestinationFormat('GIF');
        $this->assertSame('bar', $image->getToolkit()->arguments()->getSourceLocalPath());
        $this->assertSame('PNG', $image->getToolkit()->arguments()->getSourceFormat());
        $this->assertSame('foo', $image->getToolkit()->arguments()->getDestination());
        $this->assertSame('baz', $image->getToolkit()->arguments()->getDestinationLocalPath());
        $this->assertSame('GIF', $image->getToolkit()->arguments()->getDestinationFormat());
        $image->getToolkit()->setSourceFormatFromExtension('jpg');
        $image->getToolkit()->setDestinationFormatFromExtension('jpg');
        $this->assertSame('JPEG', $image->getToolkit()->arguments()->getSourceFormat());
        $this->assertSame('JPEG', $image->getToolkit()->arguments()->getDestinationFormat());
      }
    }

    // Test loading non-existing image files.
    $this->assertFalse($this->imageFactory->get('/generic.png')->isValid());
    $this->assertFalse($this->imageFactory->get('public://generic.png')->isValid());

    // Test retrieval of EXIF information.
    file_unmanaged_copy(drupal_get_path('module', 'imagemagick') . '/misc/test-exif.jpeg', 'public://', FILE_EXISTS_REPLACE);
    // The image files that will be tested.
    $image_files = [
      [
        'path' => drupal_get_path('module', 'imagemagick') . '/misc/test-exif.jpeg',
        'orientation' => 8,
      ],
      [
        'path' => 'public://test-exif.jpeg',
        'orientation' => 8,
      ],
      [
        'path' => 'dummy-remote://test-exif.jpeg',
        'orientation' => 8,
      ],
      [
        'path' => 'public://image-test.jpg',
        'orientation' => NULL,
      ],
      [
        'path' => 'public://image-test.png',
        'orientation' => NULL,
      ],
      [
        'path' => 'public://image-test.gif',
        'orientation' => NULL,
      ],
      [
        'path' => NULL,
        'orientation' => NULL,
      ],
    ];

    foreach ($image_files as $image_file) {
      // Get image using 'getimagesize'.
      \Drupal::configFactory()->getEditable('imagemagick.settings')
        ->set('use_identify', FALSE)
        ->save();
      $image = $this->imageFactory->get($image_file['path']);
      $this->assertSame($image_file['orientation'], $image->getToolkit()->getExifOrientation());
    }
  }

  /**
   * Function for finding a pixel's RGBa values.
   */
  protected function getPixelColor(ImageInterface $image, $x, $y) {
    $toolkit = $image->getToolkit();
    $color_index = imagecolorat($toolkit->getResource(), $x, $y);

    $transparent_index = imagecolortransparent($toolkit->getResource());
    if ($color_index == $transparent_index) {
      return [0, 0, 0, 127];
    }

    return array_values(imagecolorsforindex($toolkit->getResource(), $color_index));
  }

  /**
   * Function to compare two colors by RGBa, within a tolerance.
   *
   * Very basic, just compares the sum of the squared differences for each of
   * the R, G, B, A components of two colors against a 'tolerance' value.
   *
   * @param int[] $actual
   *   The actual RGBA array.
   * @param int[] $expected
   *   The expected RGBA array.
   * @param int $tolerance
   *   The acceptable difference between the colors.
   * @param string $file
   *   The image file being tested.
   * @param string $op
   *   The image operation being tested.
   *
   * @return bool
   *   TRUE if the colors differences are within tolerance, FALSE otherwise.
   */
  protected function colorsAreClose(array $actual, array $expected, $tolerance, $file, $op) {
    // Fully transparent colors are equal, regardless of RGB.
    if ($actual[3] == 127 && $expected[3] == 127) {
      return TRUE;
    }
    $distance = pow(($actual[0] - $expected[0]), 2) + pow(($actual[1] - $expected[1]), 2) + pow(($actual[2] - $expected[2]), 2) + pow(($actual[3] - $expected[3]), 2);
    $this->assertLessThanOrEqual($tolerance, $distance, "Actual: {" . implode(',', $actual) . "}, Expected: {" . implode(',', $expected) . "}, Distance: " . $distance . ", Tolerance: " . $tolerance . ", File: " . $file . ", Operation: " . $op);
    return TRUE;
  }

  /**
   * Test legacy arguments handling.
   *
   * @group legacy
   */
  public function testArgumentsLegacy() {
    $this->setUpToolkit('imagemagick', 'imagemagick.settings', [
      'binaries' => 'imagemagick',
      'quality' => 100,
      'debug' => TRUE,
    ]);

    // Prepare a copy of test files.
    $this->getTestFiles('image');

    $image_uri = "public://image-test.png";
    $image = $this->imageFactory->get($image_uri);
    if (!$image->isValid()) {
      $this->fail("Could not load image $image_uri.");
    }

    // Setup a list of arguments.
    $image->getToolkit()->addArgument("-resize 100x75!");
    // Internal argument.
    $image->getToolkit()->addArgument(">!>INTERNAL");
    $image->getToolkit()->addArgument("-quality 75");
    $image->getToolkit()->prependArgument("-hoxi 76");

    // Use methods introduced in 8.x-2.3.
    $image->getToolkit()->arguments()
      // Pre source argument.
      ->add("-density 25", ImagemagickExecArguments::PRE_SOURCE)
      // Another internal argument.
      ->add("GATEAU", ImagemagickExecArguments::INTERNAL)
      // Another pre source argument.
      ->add("-auchocolat 90", ImagemagickExecArguments::PRE_SOURCE)
      // Add two arguments with additional info.
      ->add(
        "-addz 150",
        ImagemagickExecArguments::POST_SOURCE,
        ImagemagickExecArguments::APPEND,
        [
          'foo' => 'bar',
          'qux' => 'der',
        ]
      )
      ->add(
        "-addz 200",
        ImagemagickExecArguments::POST_SOURCE,
        ImagemagickExecArguments::APPEND,
        [
          'wey' => 'lod',
          'foo' => 'bar',
        ]
      );

    // Test find arguments skipping identifiers.
    $this->assertSame([
      0 => '-hoxi 76',
      1 => '-resize 100x75!',
      2 => '>!>INTERNAL',
      3 => '-quality 75',
      5 => '>!>GATEAU',
      7 => '-addz 150',
      8 => '-addz 200',
    ], $image->getToolkit()->getArguments());
    $this->assertSame([2], array_keys($image->getToolkit()->arguments()->find('/^INTERNAL/')));
    $this->assertSame([5], array_keys($image->getToolkit()->arguments()->find('/^GATEAU/')));
    $this->assertSame([6], array_keys($image->getToolkit()->arguments()->find('/^\-auchocolat/')));
    $this->assertSame([7, 8], array_keys($image->getToolkit()->arguments()->find('/^\-addz/')));
    $this->assertSame([7, 8], array_keys($image->getToolkit()->arguments()->find('/.*/', NULL, ['foo' => 'bar'])));
    $this->assertSame([], $image->getToolkit()->arguments()->find('/.*/', NULL, ['arw' => 'moo']));
    $this->assertSame(2, $image->getToolkit()->findArgument('>!>INTERNAL'));
    $this->assertSame(5, $image->getToolkit()->findArgument('>!>GATEAU'));
    $this->assertFalse($image->getToolkit()->findArgument('-auchocolat'));

    // Check resulting command line strings.
    $this->assertSame('-density 25 -auchocolat 90', $image->getToolkit()->arguments()->toString(ImagemagickExecArguments::PRE_SOURCE));
    $this->assertSame("-hoxi 76 -resize 100x75! -quality 75 -addz 150 -addz 200", $image->getToolkit()->arguments()->toString(ImagemagickExecArguments::POST_SOURCE));
    $this->assertSame("-hoxi 76 -resize 100x75! -quality 75 -addz 150 -addz 200", $image->getToolkit()->getStringForBinary());
  }

  /**
   * Test deprecation of ImagemagickMimeTypeMapper.
   *
   * @group legacy
   *
   * @expectedDeprecation The Drupal\imagemagick\ImagemagickMimeTypeMapper class is deprecated in ImageMagick 8.x-2.4, will be removed in 8.x-3.0. You should use the FileEye\MimeMap\Type and FileEye\MimeMap\Extension API instead. See https://www.drupal.org/project/imagemagick/issues/3026733.
   * @expectedDeprecation Drupal\imagemagick\ImagemagickMimeTypeMapper::getExtensionsForMimeType is deprecated in ImageMagick 8.x-2.4, will be removed in 8.x-3.0. Use FileEye\MimeMap\Type::getExtensions() instead. See https://www.drupal.org/project/imagemagick/issues/3026733.
   * @expectedDeprecation Drupal\imagemagick\ImagemagickMimeTypeMapper::getMimeTypes is deprecated in ImageMagick 8.x-2.4, will be removed in 8.x-3.0. Use FileEye\MimeMap\AbstractMap::listTypes() instead. See https://www.drupal.org/project/imagemagick/issues/3026733.
   */
  public function testImagemagickMimeTypeMapperDeprecation() {
    $mime_type_mapper = \Drupal::service('imagemagick.mime_type_mapper');
    $format_extensions = $mime_type_mapper->getExtensionsForMimeType('image/jpeg');
    $this->assertEquals(['jpe', 'jpeg', 'jpg'], $format_extensions);
    $this->assertNotEmpty($mime_type_mapper->getMimeTypes());
  }

}
