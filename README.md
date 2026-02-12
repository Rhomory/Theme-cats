# Cats Theme

Tema para Drupal 11 con Canvas Experience Builder y 33 componentes SDC.

## Instalacion

### 1. Agregar repositorio en composer.json

Agrega esto en la seccion `repositories` de tu `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/Rhomory/Theme-cats"
    }
  ]
}
```

### 2. Instalar con Composer

```bash
ddev composer require rhomory/cats:dev-main
```

### 3. Habilitar modulo y tema

```bash
# Habilitar dependencias
ddev drush en cva -y
ddev drush en cats_settings -y

# Habilitar y establecer tema por defecto
ddev drush theme:enable cats -y
ddev drush config:set system.theme default cats -y
ddev drush cr
```

### 4. Configurar colores (opcional)

Accede al panel de configuracion de colores:

```bash
ddev launch /admin/config/cats/settings
```

Aqui puedes personalizar:
- Colores primarios, secundarios y de acento
- Tipografia
- Espaciados

## Verificar instalacion

```bash
ddev launch /admin/content/xb
```

Los componentes apareceran en el panel lateral de Canvas agrupados como "Cats - Layout", "Cats - Content", etc.

## Requisitos

- Drupal 11 / Drupal CMS
- Canvas Experience Builder
- CVA (se instala automaticamente)

---

## Componentes incluidos (33)

### Layout

| Componente     | Descripcion                         |
| -------------- | ----------------------------------- |
| Section        | Contenedor con grid de 1-4 columnas |
| Container      | Envoltorio con ancho maximo         |
| Grid           | Cuadricula flexible                 |
| Row            | Fila horizontal con flexbox         |
| Column         | Columna individual                  |
| Navbar         | Barra de navegacion                 |
| Hero           | Banner con imagen de fondo          |
| Footer Section | Pie de pagina multi-columna         |
| Spacer         | Espaciador vertical                 |
| Divider        | Linea divisoria                     |

### Contenido

| Componente  | Descripcion           |
| ----------- | --------------------- |
| Heading     | Titulos H1-H6         |
| Text        | Parrafos de texto     |
| Text Editor | Contenido enriquecido |
| Blockquote  | Citas destacadas      |

### Interactivo

| Componente     | Descripcion             |
| -------------- | ----------------------- |
| Button         | Botones con 5 variantes |
| Accordion      | Paneles expandibles     |
| Accordion Item | Item de acordeon        |
| Tabs           | Pestanas                |
| Carousel       | Carrusel de slides      |

### Multimedia

| Componente   | Descripcion              |
| ------------ | ------------------------ |
| Image        | Imagenes con efectos     |
| Video        | YouTube, Vimeo, local    |
| Map          | Google Maps              |
| Social Icons | Iconos de redes sociales |

### Datos

| Componente   | Descripcion       |
| ------------ | ----------------- |
| Counter      | Contador animado  |
| Progress Bar | Barra de progreso |

### Marketing

| Componente  | Descripcion          |
| ----------- | -------------------- |
| Card        | Tarjeta de contenido |
| Icon Box    | Caja con icono       |
| CTA         | Llamada a la accion  |
| Pricing     | Tabla de precios     |
| Testimonial | Testimonios          |
| Alert       | Mensajes de alerta   |
| Badge       | Etiquetas            |

---

## Funcionalidades

- **Drag & Drop**: Todos los componentes funcionan con Canvas Experience Builder
- **Clases CSS personalizadas**: Cada componente acepta `css_class` para estilos adicionales
- **IDs HTML**: Cada componente acepta `html_id` para anclas y enlaces internos
- **Responsive**: Componentes adaptables a movil, tablet y desktop
- **Variantes de estilo**: Multiples opciones de color, tamano y apariencia
- **CVA (Class Variance Authority)**: Sistema de variantes para estilos consistentes
- **TailwindCSS**: Clases utilitarias integradas
- **Colores personalizables**: Variables CSS configurables desde /admin/config/cats/settings

---

## Variables CSS

Los colores se pueden modificar desde `/admin/config/cats/settings` o directamente en CSS:

```css
:root {
  --cats-primary: #3b82f6;
  --cats-secondary: #64748b;
  --cats-accent: #f59e0b;
  --cats-background: #ffffff;
  --cats-foreground: #0f172a;
  --cats-muted: #f1f5f9;
  --cats-border: #e2e8f0;
}
```
