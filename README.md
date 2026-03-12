# Econopapi WP Theme

Astra Child Theme implementation for Econopapi Website by Daniel Limón.

## Cambios recientes (Front Page)

Se implementó una primera versión de la portada con enfoque modular, compatible con Gutenberg y con soporte visual para modo claro/oscuro.

### Estructura creada

- `functions.php`: ahora funciona como entry-point del tema.
- `includes/theme-setup.php`: soportes del tema y carga de assets.
- `includes/blocks.php`: registro de bloques personalizados de Gutenberg.
- `front-page.php`: plantilla principal de portada.
- `template-parts/front-page/content-front-page.php`: contenedor de contenido para portada.
- `template-parts/front-page/sections/hero.php`: fallback para hero usando bloque custom.
- `template-parts/front-page/sections/stack.php`: sección de stack tecnológico.
- `template-parts/front-page/sections/latest-posts.php`: últimas publicaciones (query dinámica de posts).
- `custom-blocks/hero/`: implementación del bloque custom `econopapi/hero`.
- `assets/css/front-page.css`: estilos de portada (responsive + light/dark).

## Compatibilidad con Gutenberg

La portada funciona de dos maneras:

1. **Con contenido en el editor de bloques:**
	- Si la página configurada como front page tiene contenido, se renderiza `the_content()` de Gutenberg.
2. **Sin contenido en el editor:**
	- Se muestran secciones fallback (hero, stack y últimas publicaciones).

### Bloque custom: `econopapi/hero`

El bloque está pensado para maquetar el área principal tipo:
"Datos, código y economía — desde México".

Atributos principales del bloque:

- Tagline
- Título
- Descripción
- Texto/URL de botón primario
- Texto/URL de botón secundario

## Modo oscuro

Se implementó mediante variables CSS en `assets/css/front-page.css`:

- Modo claro por default.
- Modo oscuro automático por `prefers-color-scheme: dark`.
- Soporte adicional por clase `body.theme-dark` o atributo `body[data-theme="dark"]` para activación manual futura.

### Toggle manual en header

Se agregó un switch de tema en el menú principal del header (ubicación `primary`) con persistencia:

- `includes/theme-toggle.php`: integración del toggle en header + carga de assets.
- `assets/js/theme-toggle.js`: control del cambio de tema y persistencia en `localStorage` (`econopapi-theme`).
- `assets/css/theme-toggle.css`: estilos visuales del switch.

Comportamiento:

- Si existe preferencia guardada, se respeta (`light` o `dark`).
- Si no existe, se usa `prefers-color-scheme` del sistema.
- Se aplican atributos en `body` (`data-theme="light|dark"`) para que los estilos respondan inmediatamente.

## Override del header

Se implementó override real del header de Astra para respetar maquetado/comportamiento de los mocks:

- `includes/header-override.php`: reemplaza el markup del header de Astra.
- `template-parts/header/site-header.php`: header personalizado (logo, nombre, handle, navegación y toggle).
- `assets/css/header.css`: estilos del header.

## Logo y modo oscuro

Para que el logo reaccione correctamente a cambios de fondo en dark mode, el tema ahora soporta dos variantes:

- `Apariencia > Personalizar > Identidad del sitio > Logo`: logo normal (light/default).
- `Apariencia > Personalizar > Identidad del sitio > Logo para modo oscuro`: logo alternativo para fondo oscuro.

Si defines ambos, el header cambia automáticamente entre variantes según `data-theme` (`light|dark`).

## Nota para siguientes iteraciones

- Si se desea toggle manual de tema (switch claro/oscuro), se puede agregar en una siguiente etapa con almacenamiento de preferencia en `localStorage` y sincronización de clase en `body`.