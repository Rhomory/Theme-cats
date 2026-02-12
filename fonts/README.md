# Fuentes Personalizadas - Cats Theme

Esta carpeta es para agregar fuentes personalizadas al tema.

## Formatos recomendados

- **WOFF2** (recomendado) - Mejor compresión, soporte moderno
- **WOFF** - Compatibilidad con navegadores antiguos

## Cómo agregar una fuente

### 1. Coloca los archivos de fuente aquí

Ejemplo de estructura:
```
fonts/
├── inter/
│   ├── inter-regular.woff2
│   ├── inter-medium.woff2
│   ├── inter-semibold.woff2
│   └── inter-bold.woff2
├── poppins/
│   ├── poppins-regular.woff2
│   └── poppins-bold.woff2
└── README.md
```

### 2. Declara la fuente en CSS

Agrega en `css/custom.css`:

```css
/* Declarar la fuente */
@font-face {
  font-family: 'Inter';
  src: url('../fonts/inter/inter-regular.woff2') format('woff2');
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: 'Inter';
  src: url('../fonts/inter/inter-bold.woff2') format('woff2');
  font-weight: 700;
  font-style: normal;
  font-display: swap;
}
```

### 3. Usa la fuente

Opción A - En CSS:
```css
:root {
  --cats-font-family: 'Inter', sans-serif;
}

body {
  font-family: var(--cats-font-family);
}
```

Opción B - Desde el panel de administración:
1. Ve a `/admin/config/cats/settings`
2. En la sección Tipografía, selecciona la fuente

## Fuentes de Google Fonts (alternativa)

Si prefieres usar Google Fonts sin descargar:

1. Agrega en `cats.libraries.yml`:
```yaml
google-fonts:
  css:
    theme:
      //fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap: { type: external }
```

2. Agrega la dependencia en el tema o componente que la use.

## Fuentes incluidas en cats_settings

El módulo cats_settings soporta estas fuentes de sistema:
- System (fuente del sistema)
- Inter
- Roboto  
- Open Sans
- Lato
- Poppins
- Montserrat
- Playfair Display
- Merriweather

Para usarlas, selecciónalas en `/admin/config/cats/settings` > Tipografía.
