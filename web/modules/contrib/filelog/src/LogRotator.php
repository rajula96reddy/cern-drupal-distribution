<?php

namespace Drupal\filelog;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Component\Render\PlainTextOutput;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Utility\Token;
use function date;
use function dirname;
use function fclose;
use function file_get_contents;
use function file_put_contents;
use function fopen;
use function gzencode;
use function rename;

/**
 * Log rotation cron service.
 */
class LogRotator {

  /**
   * The filelog settings.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * The datetime.time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * The filelog.file_manager service.
   *
   * @var \Drupal\filelog\LogFileManagerInterface
   */
  protected $fileManager;

  /**
   * The file_system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * LogRotator constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config.factory service.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   * @param \Drupal\Core\Utility\Token $token
   *   The token service.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The datetime.time service.
   * @param \Drupal\filelog\LogFileManagerInterface $fileManager
   *   The filelog service.
   * @param \Drupal\Core\File\FileSystemInterface $fileSystem
   *   The file_system service.
   */
  public function __construct(ConfigFactoryInterface $configFactory,
                              StateInterface $state,
                              Token $token,
                              TimeInterface $time,
                              LogFileManagerInterface $fileManager,
                              FileSystemInterface $fileSystem
  ) {
    $this->config = $configFactory->get('filelog.settings');
    $this->state = $state;
    $this->token = $token;
    $this->time = $time;
    $this->fileManager = $fileManager;
    $this->fileSystem = $fileSystem;
  }

  /**
   * Check and rotate if necessary.
   *
   * @param bool $force
   *   Bypass the scheduler and force rotation.
   *
   * @return bool
   *   Returns TRUE if the rotation was successful.
   *
   * @throws \Drupal\filelog\FileLogException
   */
  public function run($force = FALSE): bool {
    if ($force || $this->shouldRun($this->time->getRequestTime())) {
      return $this->rotateFile();
    }
    return FALSE;
  }

  /**
   * Check if sufficient time has passed since the last log rotation.
   *
   * @param int $now
   *   The current timestamp.
   *
   * @return bool
   *   TRUE if the log file should be rotated now.
   */
  public function shouldRun($now): bool {
    $last = $this->state->get('filelog.rotation');
    switch ($this->config->get('rotation.schedule')) {
      case 'monthly':
        return date('m', $last) !== date('m', $now);

      case 'weekly':
        return date('W', $last) !== date('W', $now);

      case 'daily':
        return date('d', $last) !== date('d', $now);
    }

    return FALSE;
  }

  /**
   * Rotate the log file.
   *
   * @throws \Drupal\filelog\FileLogException
   */
  protected function rotateFile(): bool {
    $logFile = $this->fileManager->getFileName();
    $truncate = $this->config->get('rotation.delete');
    $timestamp = $this->state->get('filelog.rotation');

    if (!$truncate) {
      $destination = $this->token->replace(
        $this->config->get('rotation.destination'),
        ['date' => $timestamp]
      );
      $destination = PlainTextOutput::renderFromHtml($destination);
      $destination = $this->config->get('location') . '/' . $destination;
      $directory = dirname($destination);
      $this->fileSystem->prepareDirectory($directory, $this->fileSystem::CREATE_DIRECTORY);
      if ($this->config->get('rotation.gzip')) {
        $truncate = TRUE;
        $data = file_get_contents($logFile);
        if (!file_put_contents($destination . '.gz', gzencode($data))) {
          throw new FileLogException("Log file could not be compressed from $logFile to $destination.gz.");
        }
      }
      elseif (!rename($logFile, $destination)) {
        throw new FileLogException("Log file could not be moved from $logFile to $destination.");
      }
    }

    if ($truncate) {
      // Simply truncate the log file, to save some file-system operations.
      $file = fopen($logFile, 'wb');
      if (!$file || !fclose($file)) {
        throw new FileLogException("Log file $logFile could not be truncated.");
      }
    }

    $this->state->set('filelog.rotation', $this->time->getRequestTime());
    return TRUE;
  }

}
