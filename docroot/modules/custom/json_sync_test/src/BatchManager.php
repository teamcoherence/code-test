<?php

namespace Drupal\json_sync_test;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Queue\QueueFactory;
use Drupal\Component\Serialization\Json;

/**
 * BatchManager class.
 */
class BatchManager {

  /**
   * Queue factory.
   *
   * @var \Drupal\Core\Queue\QueueFactoryQueueFactoryvariable
   *   Queue factory.
   */
  protected $queueFactory;

  /**
   * Config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterfaceConfigfactoryinteface
   *    Config factory.
   */
  protected $configFactory;

  /**
   * Logger interface.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterfaceloggerinterface
   *    Logger interface.
   */
  protected $logger;

  /**
   * Entity manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterfaceEntitymanagerinterface
   *    Entity manager.
   */
  protected $entityManager;

  /**
   * Node manager.
   *
   * @var \Drupal\Node\NodeStorageInterfaceNodemanager
   *    Node manager.
   */
  protected $nodeManager;

  /**
   * BatchManager constructor.
   *
   * @param \Drupal\Core\Queue\QueueFactory $queue_factory
   *   Queue factory.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   Logger factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *   Entity manager.
   */
  public function __construct(QueueFactory $queue_factory, ConfigFactoryInterface $config_factory, LoggerChannelFactoryInterface $logger_factory, EntityTypeManagerInterface $entity_manager) {
    $this->queueFactory = $queue_factory;
    $this->configFactory = $config_factory;
    $this->logger = $logger_factory->get('json_sync');
    $this->entityManager = $entity_manager;
    try {
      $this->nodeManager = $entity_manager->getStorage('node');
    }
    catch (\Exception $e) {
      $this->logger->error('Unable to initialise node storage');
    }
  }

  /**
   * Obtain data and create batch jobs.
   */
  public function process() {
    $results = $this->processJson();
    $this->logger->info("{$results['added']} items added to queue, {$results['deleted']} deleted");
  }

  /**
   * Process JSON data.
   *
   * @return array
   *   Returns and array of the JSON queue data
   */
  public function processJson() {
    $config = $this->configFactory->get('json_sync_test.settings');

    $added_to_queue = 0;
    $number_to_remove = 0;

    if ($data = $this->getJsonFileData($config->get('json_file'))) {
      $queue = $this->queueFactory->get('json');

      /*
       * Step 2: Get ID's of existing nodes
       */

      $json_ids = [];

      /*
       * Step 3: Find items only in feed not DB
       */

      /*
       * Looping through the JSON for updating / adding nodes
       */
      foreach ($data as $item) {
        array_push($json_ids, $item['id']);
        $queue->createItem($item);
        $added_to_queue++;
      }

      /*
       * Step 4: Find items in DB not in feed and remove
       */

    }
    else {
      $this->logger->warning('Could not find json file');
    }

    return [
      'added' => $added_to_queue,
      'deleted' => $number_to_remove
    ];
  }

  /**
   * Get the contents of a JSON file as an array.
   *
   * @param string $filename
   *   The Json file path.
   *
   * @return array|bool
   *   Returns JSON array of the contents or FALSE
   */
  public function getJsonFileData($filename) {
    if ($json = file_get_contents(DRUPAL_ROOT . $filename)) {
      return Json::decode($json);
    }

    return FALSE;
  }

}
