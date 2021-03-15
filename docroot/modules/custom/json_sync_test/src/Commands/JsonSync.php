<?php

namespace Drupal\json_sync_test\Commands;

use Drush\Commands\DrushCommands;
use Drupal\json_sync_test\BatchManager;

/**
 * A drush command file.
 *
 * @package Drupal\json_sync_test\Commands
 */
class JsonSync extends DrushCommands {

  /**
   * Batch manager variable.
   *
   * @var \Drupal\json_sync_test\BatchManager*/
  public $batchManager;

  /**
   * SyncSettings constructor.
   *
   * @param \Drupal\json_sync_test\BatchManager $batch_manager
   *   Our batch manager.
   */
  public function __construct(BatchManager $batch_manager) {
    parent::__construct();

    $this->batchManager = $batch_manager;
  }

  /**
   * Drush command that runs a JSON import.
   *
   * @param string $file
   *   Argument with message to be displayed.
   *
   * @command json_sync_test:process
   *
   * @aliases jsonp
   *
   * @usage json_sync_test:process json
   */
  public function message($file = '') {
    switch ($file) {
      case 'json':
        $result = $this->batchManager->processJson();
        break;

      default:
        $result = FALSE;
    }

    if ($result) {
      $this->output()->writeln([
        "{$result['added']} {$file} added to queue.",
        "{$result['deleted']} {$file} deleted.",
        "Please run `drush queue:run json` to process queue"
      ]);
    }
    else {
      $this->output()->writeln("Could not find '{$file}' in the processors list.");
    }

  }

}
