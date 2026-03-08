<?php

declare(strict_types=1);

namespace Drupal\cats_settings\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Form for exporting and importing Cats theme configuration.
 */
class CatsExportImportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'cats_export_import_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Export section
    $form['export'] = [
      '#type' => 'details',
      '#title' => $this->t('Export Configuration'),
      '#open' => TRUE,
    ];

    $form['export']['description'] = [
      '#markup' => '<p>' . $this->t('Download your current Cats theme configuration as a JSON file.') . '</p>',
    ];

    $form['export']['export_button'] = [
      '#type' => 'link',
      '#title' => $this->t('Download Configuration'),
      '#url' => Url::fromRoute('cats_settings.export'),
      '#attributes' => [
        'class' => ['button', 'button--primary'],
        'download' => '',
      ],
    ];

    // Import section
    $form['import'] = [
      '#type' => 'details',
      '#title' => $this->t('Import Configuration'),
      '#open' => TRUE,
    ];

    $form['import']['description'] = [
      '#markup' => '<p>' . $this->t('Upload a previously exported Cats theme configuration file.') . '</p>',
    ];

    $form['import']['config_file'] = [
      '#type' => 'file',
      '#title' => $this->t('Configuration file'),
      '#description' => $this->t('Upload a .json file exported from Cats theme.'),
    ];

    $form['import']['import_options'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Import options'),
      '#options' => [
        'colors' => $this->t('Colors'),
        'typography' => $this->t('Typography'),
        'spacing' => $this->t('Spacing'),
        'custom_colors' => $this->t('Custom color palette'),
      ],
      '#default_value' => ['colors', 'typography', 'spacing', 'custom_colors'],
    ];

    $form['import']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import Configuration'),
      '#button_type' => 'primary',
    ];

    // Preview section
    $form['preview'] = [
      '#type' => 'details',
      '#title' => $this->t('Paste JSON'),
      '#open' => FALSE,
    ];

    $form['preview']['json_input'] = [
      '#type' => 'textarea',
      '#title' => $this->t('JSON Configuration'),
      '#description' => $this->t('Paste the JSON configuration directly here.'),
      '#rows' => 15,
    ];

    $form['preview']['preview_button'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import from JSON'),
      '#submit' => ['::importFromJson'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $files = $this->getRequest()->files->get('files', []);
    $triggering = $form_state->getTriggeringElement();

    if ($triggering['#value'] == $this->t('Import Configuration')) {
      if (empty($files['config_file'])) {
        $form_state->setErrorByName('config_file', $this->t('Please upload a configuration file.'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $files = $this->getRequest()->files->get('files', []);
    $options = array_filter($form_state->getValue('import_options'));

    if (!empty($files['config_file'])) {
      $file = $files['config_file'];
      $content = file_get_contents($file->getRealPath());
      $this->processImport($content, $options);
    }
  }

  /**
   * Submit handler for importing from JSON textarea.
   */
  public function importFromJson(array &$form, FormStateInterface $form_state): void {
    $json = $form_state->getValue('json_input');
    $options = array_filter($form_state->getValue('import_options'));

    if (!empty($json)) {
      $this->processImport($json, $options);
    }
    else {
      $this->messenger()->addError($this->t('Please paste JSON configuration.'));
    }
  }

  /**
   * Process the import of configuration.
   */
  protected function processImport(string $content, array $options): void {
    $data = json_decode($content, TRUE);

    if (json_last_error() !== JSON_ERROR_NONE) {
      $this->messenger()->addError($this->t('Invalid JSON: @error', ['@error' => json_last_error_msg()]));
      return;
    }

    if (empty($data['settings'])) {
      $this->messenger()->addError($this->t('Invalid configuration format.'));
      return;
    }

    $settings = $data['settings'];
    $config = $this->configFactory()->getEditable('cats_settings.settings');
    $imported = [];

    // Import colors
    if (!empty($options['colors']) && !empty($settings['colors'])) {
      $mapping = [
        'primary' => 'primary_color',
        'secondary' => 'secondary_color',
        'accent' => 'accent_color',
        'background' => 'background_color',
        'foreground' => 'foreground_color',
        'muted' => 'muted_color',
        'border' => 'border_color',
        'success' => 'success_color',
        'warning' => 'warning_color',
        'error' => 'error_color',
      ];

      foreach ($mapping as $key => $config_key) {
        if (!empty($settings['colors'][$key])) {
          $config->set($config_key, $settings['colors'][$key]);
        }
      }
      $imported[] = 'colors';
    }

    // Import typography
    if (!empty($options['typography']) && !empty($settings['typography'])) {
      foreach (['font_family', 'font_size_base', 'line_height', 'heading_font'] as $key) {
        if (!empty($settings['typography'][$key])) {
          $config->set($key, $settings['typography'][$key]);
        }
      }
      $imported[] = 'typography';
    }

    // Import spacing
    if (!empty($options['spacing']) && !empty($settings['spacing'])) {
      foreach (['container_width', 'section_padding', 'border_radius'] as $key) {
        if (!empty($settings['spacing'][$key])) {
          $config->set($key, $settings['spacing'][$key]);
        }
      }
      $imported[] = 'spacing';
    }

    $config->save();

    // Import custom colors
    if (!empty($options['custom_colors']) && !empty($settings['custom_colors'])) {
      $colors_config = $this->configFactory()->getEditable('cats_settings.colors');
      $colors_config->set('custom_colors', $settings['custom_colors']);
      $colors_config->save();
      $imported[] = 'custom colors';
    }

    if (!empty($imported)) {
      $this->messenger()->addStatus($this->t('Imported: @items. Please clear cache.', [
        '@items' => implode(', ', $imported),
      ]));
    }
    else {
      $this->messenger()->addWarning($this->t('No settings imported.'));
    }
  }

}
