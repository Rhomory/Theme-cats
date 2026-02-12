<?php

declare(strict_types=1);

namespace Drupal\cats_settings\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Cats theme settings.
 */
class CatsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'cats_settings_form';
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

    $form['global_styles_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable global styles'),
      '#description' => $this->t('When enabled, CSS variables will be injected globally.'),
      '#default_value' => $config->get('global_styles_enabled'),
    ];

    // Colors section.
    $form['colors'] = [
      '#type' => 'details',
      '#title' => $this->t('Theme Colors'),
      '#open' => TRUE,
    ];

    $colors = $config->get('colors') ?? [];

    // Primary colors.
    $form['colors']['primary_group'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Primary Colors'),
    ];

    $form['colors']['primary_group']['primary'] = [
      '#type' => 'color',
      '#title' => $this->t('Primary'),
      '#default_value' => $colors['primary'] ?? '#3b82f6',
    ];

    $form['colors']['primary_group']['primary_light'] = [
      '#type' => 'color',
      '#title' => $this->t('Primary Light'),
      '#default_value' => $colors['primary_light'] ?? '#60a5fa',
    ];

    $form['colors']['primary_group']['primary_dark'] = [
      '#type' => 'color',
      '#title' => $this->t('Primary Dark'),
      '#default_value' => $colors['primary_dark'] ?? '#2563eb',
    ];

    // Secondary colors.
    $form['colors']['secondary_group'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Secondary Colors'),
    ];

    $form['colors']['secondary_group']['secondary'] = [
      '#type' => 'color',
      '#title' => $this->t('Secondary'),
      '#default_value' => $colors['secondary'] ?? '#64748b',
    ];

    $form['colors']['secondary_group']['secondary_light'] = [
      '#type' => 'color',
      '#title' => $this->t('Secondary Light'),
      '#default_value' => $colors['secondary_light'] ?? '#94a3b8',
    ];

    $form['colors']['secondary_group']['secondary_dark'] = [
      '#type' => 'color',
      '#title' => $this->t('Secondary Dark'),
      '#default_value' => $colors['secondary_dark'] ?? '#475569',
    ];

    // Accent colors.
    $form['colors']['accent_group'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Accent Colors'),
    ];

    $form['colors']['accent_group']['accent'] = [
      '#type' => 'color',
      '#title' => $this->t('Accent'),
      '#default_value' => $colors['accent'] ?? '#f59e0b',
    ];

    $form['colors']['accent_group']['accent_light'] = [
      '#type' => 'color',
      '#title' => $this->t('Accent Light'),
      '#default_value' => $colors['accent_light'] ?? '#fbbf24',
    ];

    $form['colors']['accent_group']['accent_dark'] = [
      '#type' => 'color',
      '#title' => $this->t('Accent Dark'),
      '#default_value' => $colors['accent_dark'] ?? '#d97706',
    ];

    // UI colors.
    $form['colors']['ui_group'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('UI Colors'),
    ];

    $form['colors']['ui_group']['background'] = [
      '#type' => 'color',
      '#title' => $this->t('Background'),
      '#default_value' => $colors['background'] ?? '#ffffff',
    ];

    $form['colors']['ui_group']['foreground'] = [
      '#type' => 'color',
      '#title' => $this->t('Foreground'),
      '#default_value' => $colors['foreground'] ?? '#0f172a',
    ];

    $form['colors']['ui_group']['muted'] = [
      '#type' => 'color',
      '#title' => $this->t('Muted'),
      '#default_value' => $colors['muted'] ?? '#f1f5f9',
    ];

    $form['colors']['ui_group']['border'] = [
      '#type' => 'color',
      '#title' => $this->t('Border'),
      '#default_value' => $colors['border'] ?? '#e2e8f0',
    ];

    // Status colors.
    $form['colors']['status_group'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Status Colors'),
    ];

    $form['colors']['status_group']['success'] = [
      '#type' => 'color',
      '#title' => $this->t('Success'),
      '#default_value' => $colors['success'] ?? '#22c55e',
    ];

    $form['colors']['status_group']['warning'] = [
      '#type' => 'color',
      '#title' => $this->t('Warning'),
      '#default_value' => $colors['warning'] ?? '#f59e0b',
    ];

    $form['colors']['status_group']['error'] = [
      '#type' => 'color',
      '#title' => $this->t('Error'),
      '#default_value' => $colors['error'] ?? '#ef4444',
    ];

    $form['colors']['status_group']['info'] = [
      '#type' => 'color',
      '#title' => $this->t('Info'),
      '#default_value' => $colors['info'] ?? '#3b82f6',
    ];

    // Typography section.
    $form['typography'] = [
      '#type' => 'details',
      '#title' => $this->t('Typography'),
      '#open' => FALSE,
    ];

    $typography = $config->get('typography') ?? [];

    $font_options = [
      'system' => 'System Default',
      'inter' => 'Inter',
      'roboto' => 'Roboto',
      'open-sans' => 'Open Sans',
      'lato' => 'Lato',
      'poppins' => 'Poppins',
      'montserrat' => 'Montserrat',
      'playfair' => 'Playfair Display',
      'merriweather' => 'Merriweather',
    ];

    $form['typography']['font_family'] = [
      '#type' => 'select',
      '#title' => $this->t('Body Font Family'),
      '#options' => $font_options,
      '#default_value' => $typography['font_family'] ?? 'system',
    ];

    $form['typography']['heading_font'] = [
      '#type' => 'select',
      '#title' => $this->t('Heading Font Family'),
      '#options' => $font_options,
      '#default_value' => $typography['heading_font'] ?? 'system',
    ];

    // Spacing section.
    $form['spacing'] = [
      '#type' => 'details',
      '#title' => $this->t('Spacing'),
      '#open' => FALSE,
    ];

    $spacing = $config->get('spacing') ?? [];

    $form['spacing']['container_width'] = [
      '#type' => 'number',
      '#title' => $this->t('Container Max Width (px)'),
      '#min' => 960,
      '#max' => 1920,
      '#step' => 10,
      '#default_value' => $spacing['container_width'] ?? 1280,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('cats_settings.settings')
      ->set('global_styles_enabled', $form_state->getValue('global_styles_enabled'))
      ->set('colors', [
        'primary' => $form_state->getValue('primary'),
        'primary_light' => $form_state->getValue('primary_light'),
        'primary_dark' => $form_state->getValue('primary_dark'),
        'secondary' => $form_state->getValue('secondary'),
        'secondary_light' => $form_state->getValue('secondary_light'),
        'secondary_dark' => $form_state->getValue('secondary_dark'),
        'accent' => $form_state->getValue('accent'),
        'accent_light' => $form_state->getValue('accent_light'),
        'accent_dark' => $form_state->getValue('accent_dark'),
        'background' => $form_state->getValue('background'),
        'foreground' => $form_state->getValue('foreground'),
        'muted' => $form_state->getValue('muted'),
        'border' => $form_state->getValue('border'),
        'success' => $form_state->getValue('success'),
        'warning' => $form_state->getValue('warning'),
        'error' => $form_state->getValue('error'),
        'info' => $form_state->getValue('info'),
      ])
      ->set('typography', [
        'font_family' => $form_state->getValue('font_family'),
        'heading_font' => $form_state->getValue('heading_font'),
      ])
      ->set('spacing', [
        'container_width' => $form_state->getValue('container_width'),
      ])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
