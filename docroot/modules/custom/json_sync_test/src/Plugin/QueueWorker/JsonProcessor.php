<?php

namespace Drupal\json_sync_test\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class JsonProcessor.
 *
 * @package Drupal\Plugin\QueueWorker
 *
 * @QueueWorker(
 *   id = "json",
 *   title = @Translation("Json processor"),
 *   cron = {"time" = 20}
 * )
 */
class JsonProcessor extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * Entity type interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface*/
  protected $entityTypeManager;

  /**
   * Json Processor constructor.
   *
   * @param array $configuration
   *   Configuration.
   * @param string $plugin_id
   *   Plugin ID.
   * @param mixed $plugin_definition
   *   Plugin definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Our batch manager.
   */
  public function __construct(array $configuration, string $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Create factory method.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Container interfact.
   * @param array $configuration
   *   Configuration.
   * @param mixed $plugin_id
   *   Plugin id.
   * @param mixed $plugin_definition
   *   Plugin definition.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * Process individual items.
   *
   * @param mixed $item
   *   Item JSON object.
   */
  public function processItem($item) {
    $node_storage = $this->entityTypeManager->getStorage('node');
    $user_storage = $this->entityTypeManager->getStorage('user');

    $id = $item['id'];
    $node = NULL;

    /*
     *  Step 1: Create nodes
     */

    if (is_null($node)) {
      // Create node meta.
      $node = $node_storage->create([
        'title' => '',
        'type' => 'page',
        'language' => 'en',
      ]);
    }

    // Set fields here.

    $node->save();
  }

}
