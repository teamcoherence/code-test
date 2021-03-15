<?php

namespace Drupal\json_sync_test\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\json_sync_test\BatchManager;

/**
 * Configure example settings for this site.
 */
class SyncSettings extends ConfigFormBase {

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'json_sync_test.settings';

  /**
   * Batch manager.
   *
   * @var \Drupal\json_sync_test\BatchManager
   *    Our batch manager
   * */
  protected $batchManager;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'json_sync_test_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * SyncSettings constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   * @param \Drupal\json_sync_test\BatchManager $batch_manager
   *   Our batch manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, BatchManager $batch_manager) {
    parent::__construct($config_factory);

    $this->batchManager = $batch_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('json.batch_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['json_file'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Path to JSON file'),
      '#default_value' => $config->get('json_file'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Queue JSON for processing'),
      '#submit' => ['::jsonSubmitHandler']
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('json_file', $form_state->getValue('json_file'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * Form submit handler.
   *
   * @param array $form
   *   Form.
   * @param \Drupal\Core\Form\FormStateInterface $formState
   *   Form state.
   */
  public function jsonSubmitHandler(array &$form, FormStateInterface $formState) {
    $result = $this->batchManager->processJson();

    $this->messenger()->addMessage($this->t('@added JSON queued for processing, @deleted items deleted.', [
      '@added' => $result['added'],
      '@deleted' => $result['deleted'],
    ]));
  }

}
