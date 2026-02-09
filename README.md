# Canvas Builder Theme

Tema estilo **Elementor** para Drupal 11 basado en byte_theme, con componentes avanzados de drag-and-drop y opciones de personalización.

## Requisitos

- Drupal 11.x
- [byte_theme](https://www.drupal.org/project/byte)
- [Canvas (Experience Builder)](https://www.drupal.org/project/canvas)

## Instalación

### Mediante Composer (recomendado)

1. Agrega el repositorio a tu `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/Rhomory/Theme---cats"
    }
  ]
}
```

2. Instala el tema:

```bash
composer require drupal/canvas_builder_theme:dev-main
```

3. Habilita el tema:

```bash
drush theme:enable canvas_builder_theme -y
drush config:set system.theme default canvas_builder_theme -y
drush cr
```

### Instalación manual

1. Descarga o clona el repositorio en `web/themes/custom/`
2. Habilita el tema desde la administración o con Drush

## Componentes incluidos

### Layout
| Componente | Descripción |
|------------|-------------|
| `container` | Contenedor flexible con ancho, padding, colores y animaciones |
| `grid` | Sistema de grid responsive (1-12 columnas) |
| `row` | Fila flexible con dirección y alineación |
| `column` | Columna con ancho basado en 12 columnas |
| `spacer` | Espaciador vertical configurable |
| `divider` | Línea divisoria con estilos personalizables |

### Contenido
| Componente | Descripción |
|------------|-------------|
| `tabs` | Pestañas interactivas con 4 estilos |
| `carousel` | Carrusel con autoplay, navegación y efectos |
| `counter` | Contador animado al hacer scroll |
| `progress-bar` | Barra de progreso con animación |
| `video` | Reproductor (YouTube, Vimeo, local) |
| `map` | Mapa embebido (Google Maps, OpenStreetMap) |
| `social-icons` | Iconos de 13 redes sociales |

## Opciones de personalización

Todos los componentes incluyen:
- **Clases CSS personalizadas**: Añade tus propias clases
- **ID personalizado**: Para anclas y JavaScript
- **Animaciones de entrada**: fade-in, slide-up, slide-down, scale-in
- **Colores de fondo**: primary, secondary, accent, muted, white, dark
- **Bordes redondeados**: none, small, medium, large
- **Sombras**: none, small, medium, large

## Uso con Canvas (Experience Builder)

Los componentes aparecen automáticamente en el Experience Builder de Drupal, permitiendo arrastrar y soltar para crear páginas visualmente.

## Licencia

GPL-2.0-or-later
