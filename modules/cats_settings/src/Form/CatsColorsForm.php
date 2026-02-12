<?php

declare(strict_types=1);

namespace Drupal\cats_settings\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Manage custom color variables for Cats theme.
 */
class CatsColorsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'cats_colors_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['cats_settings.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('cats_settings.settings');
    $custom_colors = $config->get('custom_colors') ?? [];

    // Convert to indexed array if it's an associative array.
    if (!empty($custom_colors) && !isset($custom_colors[0])) {
      $custom_colors = array_values($custom_colors);
    }

    $form['#tree'] = TRUE;

    $form['description'] = [
      '#type' => 'markup',
      '#markup' => '<p>' . $this->t('Add custom color variables that will be available as CSS custom properties. These can be used in your components and styles.') . '</p>',
    ];

    $form['custom_colors'] = [
      '#type' => 'table',
      '#header' => [
        $this->t('Color Name'),
        $this->t('CSS Variable'),
        $this->t('Color Value'),
        $this->t('Preview'),
        $this->t('Operations'),
      ],
      '#empty' => $this->t('No custom colors defined yet.'),
      '#prefix' => '<div id="custom-colors-wrapper">',
      '#suffix' => '</div>',
    ];

    // Get the number of colors to show.
    $num_colors = $form_state->get('num_colors');
    if ($num_colors === NULL) {
      $num_colors = max(count($custom_colors), 0);
      $form_state->set('num_colors', $num_colors);
    }

    for ($i = 0; $i < $num_colors; $i++) {
      $color = $custom_colors[$i] ?? ['name' => '', 'value' => '#000000'];

      $form['custom_colors'][$i]['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name'),
        '#title_display' => 'invisible',
        '#default_value' => $color['name'] ?? '',
        '#size' => 20,
        '#placeholder' => $this->t('e.g., brand-blue'),
        '#attributes' => [
          'class' => ['color-name-input'],
        ],
      ];

      $css_var_name = !empty($color['name']) ? '--cats-' . $this->sanitizeColorName($color['name']) : '--cats-[name]';
      $form['custom_colors'][$i]['css_variable'] = [
        '#type' => 'markup',
        '#markup' => '<code class="css-variable-preview">' . $css_var_name . '</code>',
      ];

      $form['custom_colors'][$i]['value'] = [
        '#type' => 'color',
        '#title' => $this->t('Value'),
        '#title_display' => 'invisible',
        '#default_value' => $color['value'] ?? '#000000',
      ];

      $form['custom_colors'][$i]['preview'] = [
        '#type' => 'markup',
        '#markup' => '<div class="color-preview" style="width: 40px; height: 40px; border-radius: 4px; border: 1px solid #ccc; background-color: ' . ($color['value'] ?? '#000000') . ';"></div>',
      ];

      $form['custom_colors'][$i]['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#name' => 'remove_' . $i,
        '#submit' => ['::removeColorCallback'],
        '#ajax' => [
          'callback' => '::ajaxCallback',
          'wrapper' => 'custom-colors-wrapper',
        ],
        '#limit_validation_errors' => [],
      ];
    }

    $form['actions_wrapper'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['form-actions-wrapper'],
      ],
    ];

    $form['actions_wrapper']['add_color'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add Color'),
      '#submit' => ['::addColorCallback'],
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'wrapper' => 'custom-colors-wrapper',
      ],
      '#limit_validation_errors' => [],
    ];

    $form['css_preview'] = [
      '#type' => 'details',
      '#title' => $this->t('CSS Preview'),
      '#open' => FALSE,
    ];

    $css_preview = ":root {\n";
    foreach ($custom_colors as $color) {
      if (!empty($color['name'])) {
        $css_preview .= "  --cats-" . $this->sanitizeColorName($color['name']) . ": " . $color['value'] . ";\n";
      }
    }
    $css_preview .= "}";

    $form['css_preview']['code'] = [
      '#type' => 'markup',
      '#markup' => '<pre><code>' . htmlspecialchars($css_preview) . '</code></pre>',
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Ajax callback for the form.
   */
  public function ajaxCallback(array &$form, FormStateInterface $form_state): array {
    return $form['custom_colors'];
  }

  /**
   * Submit handler for adding a new color.
   */
  public function addColorCallback(array &$form, FormStateInterface $form_state): void {
    $num_colors = $form_state->get('num_colors');
    $form_state->set('num_colors', $num_colors + 1);
    $form_state->setRebuild();
  }

  /**
   * Submit handler for removing a color.
   */
  public function removeColorCallback(array &$form, FormStateInterface $form_state): void {
    $trigger = $form_state->getTriggeringElement();
    $index = (int) str_replace('remove_', '', $trigger['#name']);

    $custom_colors = $form_state->getValue('custom_colors') ?? [];
    unset($custom_colors[$index]);
    $custom_colors = array_values($custom_colors);

    $form_state->setValue('custom_colors', $custom_colors);
    $form_state->set('num_colors', count($custom_colors));
    $form_state->setRebuild();
  }

  /**
   * Sanitize color name for CSS variable.
   */
  protected function sanitizeColorName(string $name): string {
    $name = strtolower(trim($name));
    $name = preg_replace('/[^a-z0-9\-]/', '-', $name);
    $name = preg_replace('/-+/', '-', $name);
    return trim($name, '-');
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    parent::validateForm($form, $form_state);

    $custom_colors = $form_state->getValue('custom_colors') ?? [];
    $names = [];

    foreach ($custom_colors as $index => $color) {
      if (empty($color['name'])) {
        continue;
      }

      $sanitized_name = $this->sanitizeColorName($color['name']);

      if (empty($sanitized_name)) {
        $form_state->setErrorByName("custom_colors][$index][name", $this->t('Color name contains only invalid characters.'));
        continue;
      }

      if (in_array($sanitized_name, $names)) {
        $form_state->setErrorByName("custom_colors][$index][name", $this->t('Duplicate color name: @name', ['@name' => $color['name']]));
      }

      $names[] = $sanitized_name;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $custom_colors = [];
    $values = $form_state->getValue('custom_colors') ?? [];

    foreach ($values as $color) {
      if (!empty($color['name'])) {
        $custom_colors[] = [
          'name' => $this->sanitizeColorName($color['name']),
          'value' => $color['value'],
        ];
      }
    }

    $this->config('cats_settings.settings')
      ->set('custom_colors', $custom_colors)
      ->save();

    parent::submitForm($form, $form_state);
  }

}
