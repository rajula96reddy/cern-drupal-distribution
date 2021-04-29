<?php

namespace Drupal\filelog;

/**
 * Interface for the LogFileManager service.
 */
interface LogFileManagerInterface {

  /**
   * Ensure that the log directory exists.
   *
   * @return bool
   *   TRUE if the path of the logfile exists and is writeable.
   */
  public function ensurePath(): bool;

  /**
   * Get the complete filename of the log.
   *
   * @return string
   *   The full path (relative or absolute) of the logfile.
   */
  public function getFileName(): string;

}
