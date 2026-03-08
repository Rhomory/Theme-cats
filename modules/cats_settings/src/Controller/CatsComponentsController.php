<?php

declare(strict_types=1);

namespace Drupal\cats_settings\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Controller for the Cats Components gallery.
 */
class CatsComponentsController extends ControllerBase {

  /**
   * The theme handler.
   */
  protected ThemeHandlerInterface $themeHandler;

  /**
   * Constructs a CatsComponentsController object.
   */
  public function __construct(ThemeHandlerInterface $theme_handler) {
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('theme_handler')
    );
  }

  /**
   * Lists all SDC components from the Cats theme.
   *
   * @return array
   *   A render array.
   */
  public function list(): array {
    $components = $this->getComponents();
    $grouped = $this->groupComponentsByCategory($components);

    $build = [
      '#type' => 'container',
      '#attributes' => ['class' => ['cats-components-gallery']],
    ];

    // Add description.
    $build['description'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('Browse all available SDC components in the Cats theme.'),
      '#attributes' => ['class' => ['cats-components-description']],
    ];

    // Add summary.
    $build['summary'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $this->t('Total components: @count', ['@count' => count($components)]),
      '#attributes' => ['class' => ['cats-components-summary', 'color-success']],
    ];

    // Group components by category.
    foreach ($grouped as $category => $categoryComponents) {
      $build[$category] = [
        '#type' => 'details',
        '#title' => ucfirst($category) . ' (' . count($categoryComponents) . ')',
        '#open' => TRUE,
        '#attributes' => ['class' => ['cats-component-category']],
      ];

      $build[$category]['grid'] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['cats-components-grid'],
          'style' => 'display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; margin-top: 1rem;',
        ],
      ];

      foreach ($categoryComponents as $component) {
        $build[$category]['grid'][$component['id']] = $this->buildComponentCard($component);
      }
    }

    return $build;
  }

  /**
   * Gets all SDC components from the Cats theme.
   *
   * @return array
   *   Array of component definitions.
   */
  protected function getComponents(): array {
    $components = [];
    $theme = $this->themeHandler->getTheme('cats');

    if (!$theme) {
      return $components;
    }

    $componentsPath = $theme->getPath() . '/components';

    if (!is_dir($componentsPath)) {
      return $components;
    }

    $directories = scandir($componentsPath);

    foreach ($directories as $dir) {
      if ($dir === '.' || $dir === '..') {
        continue;
      }

      $componentYml = $componentsPath . '/' . $dir . '/' . $dir . '.component.yml';

      if (file_exists($componentYml)) {
        try {
          $definition = Yaml::parseFile($componentYml);
          $components[] = [
            'id' => $dir,
            'name' => $definition['name'] ?? ucfirst(str_replace(['-', '_'], ' ', $dir)),
            'description' => $definition['description'] ?? '',
            'group' => $definition['group'] ?? 'Other',
            'props' => $definition['props'] ?? [],
            'slots' => $definition['slots'] ?? [],
            'path' => $componentsPath . '/' . $dir,
          ];
        }
        catch (\Exception $e) {
          // Skip invalid component files.
          continue;
        }
      }
    }

    // Sort by name.
    usort($components, fn($a, $b) => strcmp($a['name'], $b['name']));

    return $components;
  }

  /**
   * Groups components by their category/group.
   *
   * @param array $components
   *   Array of components.
   *
   * @return array
   *   Grouped components.
   */
  protected function groupComponentsByCategory(array $components): array {
    $grouped = [];

    foreach ($components as $component) {
      $group = strtolower($component['group']);
      $grouped[$group][] = $component;
    }

    // Sort groups.
    ksort($grouped);

    return $grouped;
  }

  /**
   * Builds a card for a single component.
   *
   * @param array $component
   *   Component definition.
   *
   * @return array
   *   Render array for the component card.
   */
  protected function buildComponentCard(array $component): array {
    $propsCount = count($component['props']);
    $slotsCount = count($component['slots']);

    return [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['cats-component-card'],
        'style' => 'background: var(--color-gray-050, #f8fafc); border: 1px solid var(--color-gray-200, #e2e8f0); border-radius: 8px; padding: 1rem;',
      ],
      'name' => [
        '#type' => 'html_tag',
        '#tag' => 'h4',
        '#value' => $component['name'],
        '#attributes' => [
          'style' => 'margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 600;',
        ],
      ],
      'id' => [
        '#type' => 'html_tag',
        '#tag' => 'code',
        '#value' => 'cats:' . $component['id'],
        '#attributes' => [
          'style' => 'display: inline-block; background: var(--color-gray-100, #e2e8f0); padding: 0.125rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-bottom: 0.5rem;',
        ],
      ],
      'description' => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $component['description'] ?: $this->t('No description available.'),
        '#attributes' => [
          'style' => 'margin: 0.5rem 0; font-size: 0.875rem; color: var(--color-gray-600, #64748b);',
        ],
      ],
      'meta' => [
        '#type' => 'container',
        '#attributes' => [
          'style' => 'display: flex; gap: 1rem; margin-top: 0.75rem; font-size: 0.75rem;',
        ],
        'props' => [
          '#type' => 'html_tag',
          '#tag' => 'span',
          '#value' => $this->t('@count props', ['@count' => $propsCount]),
          '#attributes' => [
            'style' => 'background: var(--color-blue-100, #dbeafe); color: var(--color-blue-700, #1d4ed8); padding: 0.125rem 0.5rem; border-radius: 4px;',
          ],
        ],
        'slots' => [
          '#type' => 'html_tag',
          '#tag' => 'span',
          '#value' => $this->t('@count slots', ['@count' => $slotsCount]),
          '#attributes' => [
            'style' => 'background: var(--color-green-100, #dcfce7); color: var(--color-green-700, #15803d); padding: 0.125rem 0.5rem; border-radius: 4px;',
          ],
        ],
      ],
    ];
  }

}
