# Cats Theme

Tema estilo Elementor para Drupal 11 con componentes SDC avanzados, drag-and-drop y opciones de personalización.

## Requisitos

- Drupal 11 / Drupal CMS 2.0+
- Módulo byte_theme como tema base
- Módulo Canvas Experience Builder
- PHP 8.2+

## Instalación

1. Coloca la carpeta `cats` en `web/themes/custom/`
2. Coloca el módulo `cats_settings` en `web/modules/custom/`
3. Activa el tema Cats en Apariencia
4. Activa el módulo Cats Settings
5. Configura los colores en `/admin/config/cats/settings`

## Componentes

### Cats - Layout
| Componente | Descripción |
|------------|-------------|
| Container | Contenedor avanzado con imagen de fondo, padding/margin individual, posición y offset |
| Grid | Sistema de grid de 12 columnas responsive |
| Row | Fila flexible con opciones de alineación |
| Column | Columna con ancho configurable |
| Spacer | Espaciador vertical |
| Divider | Línea divisoria personalizable |

### Cats - Content
| Componente | Descripción |
|------------|-------------|
| Heading | Títulos con sombra de texto, múltiples tamaños y estilos |
| Text | Texto con sombra, colores y tipografía personalizable |
| Text Editor | Contenido de texto enriquecido (WYSIWYG) |
| Button | Botón con múltiples variantes, iconos y efectos |
| Image | Imagen con efectos, lightbox y caption |
| Icon Box | Caja con icono para destacar características |
| Tabs | Pestañas interactivas |
| Carousel | Carrusel de contenido |
| Counter | Contador animado |
| Progress Bar | Barra de progreso animada |
| Video | Reproductor de video (YouTube, Vimeo, local) |
| Map | Mapa embebido (Google Maps, OpenStreetMap) |
| Social Icons | Iconos de redes sociales |

### Cats - Widgets
| Componente | Descripción |
|------------|-------------|
| Testimonial | Widget de testimonio con imagen, rating y cita |
| Pricing | Tabla de precios con características |
| Alert | Alertas y notificaciones |

## Características

### Container Avanzado
- Imagen de fondo con posición, tamaño y overlay
- Padding individual (top, bottom, left, right)
- Margin individual
- Posición (static, relative, absolute, fixed, sticky)
- Offset (top, bottom, left, right)
- Z-index
- Animaciones de entrada

### Sombra de Texto
Los componentes Heading y Text incluyen opciones de sombra:
- Color de sombra personalizable
- Tamaño de sombra (small, medium, large, xl)
- Offset X/Y configurables

### Módulo Cats Settings
Panel de administración para:
- Colores globales (primary, secondary, accent, etc.)
- Colores personalizados (variables CSS propias)
- Tipografía (fuentes para body y headings)
- Espaciado (ancho de contenedor)

## Variables CSS

El tema inyecta automáticamente variables CSS basadas en la configuración:

```css
:root {
  --cats-primary: #3b82f6;
  --cats-primary-light: #60a5fa;
  --cats-primary-dark: #2563eb;
  --cats-secondary: #64748b;
  --cats-accent: #f59e0b;
  --cats-background: #ffffff;
  --cats-foreground: #0f172a;
  --cats-muted: #f1f5f9;
  --cats-border: #e2e8f0;
  --cats-success: #22c55e;
  --cats-warning: #f59e0b;
  --cats-error: #ef4444;
  --cats-info: #3b82f6;
  /* Colores personalizados */
  --cats-mi-color: #custom;
}
```

## Estructura de Archivos

```
cats/
├── cats.info.yml
├── cats.libraries.yml
├── composer.json
├── README.md
├── css/
│   ├── cats.css
│   └── custom.css
├── js/
│   └── cats.js
├── components/
│   ├── container/
│   ├── grid/
│   ├── row/
│   ├── column/
│   ├── heading/
│   ├── text/
│   ├── text-editor/
│   ├── button/
│   ├── image/
│   ├── icon-box/
│   ├── testimonial/
│   ├── pricing/
│   ├── alert/
│   └── ...
├── templates/
└── fonts/

cats_settings/
├── cats_settings.info.yml
├── cats_settings.module
├── cats_settings.routing.yml
├── cats_settings.links.menu.yml
├── cats_settings.links.task.yml
├── config/
│   ├── install/
│   │   └── cats_settings.settings.yml
│   └── schema/
│       └── cats_settings.schema.yml
└── src/
    └── Form/
        ├── CatsSettingsForm.php
        └── CatsColorsForm.php
```

## Personalización

### CSS Personalizado
Añade estilos personalizados en `css/custom.css`

### Nuevos Componentes
1. Crea una carpeta en `components/`
2. Añade `nombre.component.yml` y `nombre.twig`
3. Limpia cache de Drupal

## Uso con Canvas (Experience Builder)

Los componentes aparecen automáticamente en el Experience Builder de Drupal, organizados en las siguientes secciones:
- **Cats - Layout**: Componentes de estructura
- **Cats - Content**: Componentes de contenido
- **Cats - Widgets**: Widgets prediseñados

## Licencia

MIT
