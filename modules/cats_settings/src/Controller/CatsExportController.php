<?php

declare(strict_types=1);

namespace Drupal\cats_settings\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Controller for exporting Cats theme configuration.
 */
class CatsExportController extends ControllerBase {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a CatsExportController object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * Exports the Cats theme configuration as JSON.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   A JSON file download response.
   */
  public function export(): Response {
    $config = $this->configFactory->get('cats_settings.settings');
    
    $export_data = [
      'version' => '1.0',
      'exported' => date('Y-m-d H:i:s'),
      'theme' => 'cats',
      'settings' => [
        'colors' => [
          'primary' => $config->get('primary_color') ?? '#3b82f6',
          'secondary' => $config->get('secondary_color') ?? '#64748b',
          'accent' => $config->get('accent_color') ?? '#f59e0b',
          'background' => $config->get('background_color') ?? '#ffffff',
          'foreground' => $config->get('foreground_color') ?? '#1f2937',
          'muted' => $config->get('muted_color') ?? '#6b7280',
          'border' => $config->get('border_color') ?? '#e5e7eb',
          'success' => $config->get('success_color') ?? '#10b981',
          'warning' => $config->get('warning_color') ?? '#f59e0b',
          'error' => $config->get('error_color') ?? '#ef4444',
        ],
        'typography' => [
          'font_family' => $config->get('font_family') ?? 'Inter',
          'font_size_base' => $config->get('font_size_base') ?? '16px',
          'line_height' => $config->get('line_height') ?? '1.5',
          'heading_font' => $config->get('heading_font') ?? 'Inter',
        ],
        'spacing' => [
          'container_width' => $config->get('container_width') ?? '1280px',
          'section_padding' => $config->get('section_padding') ?? '4rem',
          'border_radius' => $config->get('border_radius') ?? '0.5rem',
        ],
      ],
    ];
    
    // Get custom colors if they exist
    $colors_config = $this->configFactory->get('cats_settings.colors');
    $custom_colors = $colors_config->get('custom_colors');
    if (!empty($custom_colors)) {
      $export_data['settings']['custom_colors'] = $custom_colors;
    }
    
    $json = json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    $response = new Response($json);
    $response->headers->set('Content-Type', 'application/json');
    
    $disposition = $response->headers->makeDisposition(
      ResponseHeaderBag::DISPOSITION_ATTACHMENT,
      'cats-theme-config-' . date('Y-m-d') . '.json'
    );
    $response->headers->set('Content-Disposition', $disposition);
    
    return $response;
  }

}
