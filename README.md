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

### Bloque custom: `econopapi/profile-card`

Se agregó un bloque dinámico para construir una tarjeta de perfil editable desde Gutenberg con enfoque premium, accesible y responsive.

Estructura del bloque:

- `custom-blocks/profile-card/block.json`
- `custom-blocks/profile-card/index.js`
- `custom-blocks/profile-card/render.php`
- `custom-blocks/profile-card/style.css`
- `custom-blocks/profile-card/editor.css`

Registro:

- El bloque se registra en `includes/blocks.php` junto al bloque Hero.

Campos disponibles en el bloque:

- Nombre
- Usuario/handle
- Rol o tagline
- Variantes visuales seleccionables (`Minimal`, `Gradient`, `Neon Soft`)
- Avatar por URL + ALT + fallback por iniciales
- Email (opcional y toggleable)
- Enlace GitHub (label + URL, opcional y toggleable)
- Enlace LinkedIn (label + URL, opcional y toggleable)
- Enlace YouTube (label + URL, opcional y toggleable)
- Ubicación (label + valor)
- Toggle para acabado visual tipo glass

Comportamiento:

- Render dinámico en servidor (`render.php`) con sanitización de datos.
- Soporte de modo claro/oscuro por `prefers-color-scheme`, `body.theme-dark` y `body[data-theme="dark"]`.
- Diseño responsive para uso en sidebar o columnas estrechas.
- Los campos de contacto se muestran sólo si su toggle está activo y tienen valor.

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

## Footer Personalizado

Se implementó un footer modular y profesional con soporte completo para dark mode.

### Estructura creada

- `footer.php`: Sobrescribe el footer por defecto de Astra
- `includes/footer.php`: Configuración y setup del footer personalizado
- `assets/css/footer.css`: Estilos completos con soporte para dark mode
- `template-parts/footer/footer.php`: Template part principal del footer
- `template-parts/footer/social-links.php`: Componente de enlaces sociales

### Características del Footer

#### 1. Diseño Modular
- **Áreas de widgets**: `footer-main` (principal) y `footer-copyright` (copyright)
- **Menú de navegación**: Ubicación `footer` para enlaces adicionales
- **Enlaces sociales**: Configurables desde el Customizer (Twitter, LinkedIn, GitHub, Instagram)

#### 2. Soporte para Dark Mode
- Variables CSS para temas claro/oscuro
- Compatible con `theme-dark`, `data-theme="dark"` y `prefers-color-scheme: dark`
- Transiciones suaves entre modos

#### 3. Diseño Responsive
- Grid layout para widgets en desktop
- Layout de una columna en tablet/mobile
- Contenido centrado en dispositivos móviles

#### 4. Accesibilidad
- ARIA labels para navegación y enlaces sociales
- Estados focus-visible para navegación por teclado
- Texto para screen readers en iconos sociales
- Estructura HTML semántica

### Configuración

#### 1. Enlaces Sociales
1. Ir a Apariencia → Personalizar → Enlaces Sociales
2. Ingresar URLs para cada plataforma
3. Los enlaces aparecerán automáticamente en el footer

#### 2. Widgets
1. Ir a Apariencia → Widgets
2. Agregar widgets al área "Footer Principal"
3. Agregar texto de copyright al área "Footer Copyright"

#### 3. Menú del Footer
1. Ir a Apariencia → Menus
2. Crear nuevo menú y asignar a "Menú del Footer"
3. Agregar enlaces para aparecer en la navegación del footer

### Integración con el Tema

El footer reemplaza automáticamente el footer por defecto de Astra cuando se activa el tema. Se integra con:

- Sistema de dark mode existente
- Customizer para configuración
- Widgets de WordPress
- Menús de navegación

### Estilos y Variables CSS

El footer utiliza un sistema de variables CSS similar al resto del tema:

```css
--eco-footer-bg: #f8f9fc;           /* Fondo claro */
--eco-footer-surface: #ffffff;      /* Superficie de widgets */
--eco-footer-border: #e2e6f0;       /* Bordes */
--eco-footer-text: #1e2130;         /* Texto principal */
--eco-footer-muted: #676d82;        /* Texto secundario */
--eco-footer-accent: #5f58d8;       /* Color de acento */
```

En modo oscuro, estas variables cambian automáticamente a valores más oscuros con mejor contraste.
- Se aplican atributos en `body` (`data-theme="light|dark"`) para que los estilos respondan inmediatamente.

## Schema Markup (JSON-LD)

Se implementó un módulo de datos estructurados del tema para reforzar reconocimiento semántico en motores de búsqueda.

### Implementación actual

- `includes/schema.php`: módulo dedicado para datos estructurados.
- `functions.php`: carga modular del archivo de schema como parte del bootstrap del tema.

### Schemas activos

1. `Person` (Schema.org)
- Hook: `wp_head`
- Condición: sólo se imprime en `is_front_page()`
- Datos incluidos: nombre, URL del sitio, imagen de perfil, roles (`jobTitle`) y perfiles sociales (`sameAs`).

2. `Article` (Schema.org)
- Hook: `wp_head`
- Condición: sólo se imprime en `is_singular( 'post' )`
- Datos incluidos: `headline`, `datePublished`, `dateModified`, `author`, `publisher`, `mainEntityOfPage` e `image` (si existe imagen destacada).

3. `BreadcrumbList` (Schema.org)
- Hook: `wp_head`
- Condición: se imprime en contenido singular y en archive de proyectos (`is_post_type_archive( 'project' )`).
- Comportamiento:
	- Post individual: `Inicio > Blog > Post`
	- Página individual: `Inicio > ...ancestros... > Página actual`
	- Proyecto individual: `Inicio > Proyectos > Proyecto`
	- Archive de proyectos: `Inicio > Proyectos`

### Extensibilidad

Los payloads se pueden modificar sin tocar el core del módulo mediante los filtros:

- `econopapi_person_schema_data`
- `econopapi_article_schema_data`
- `econopapi_breadcrumb_schema_data`

Esto permite ajustar o enriquecer el schema en child customizations o módulos futuros sin romper el enfoque modular del tema.

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

## Templates custom para Single Post y Single Page

Se implementaron plantillas personalizadas para contenido singular con soporte visual claro/oscuro y compatibilidad con Gutenberg:

- `single.php` + `template-parts/single/content-single.php`: layout de post individual.
- `template-parts/single/sidebar-single.php`: sidebar de apoyo para post individual.
- `page.php` + `template-parts/page/content-page.php`: layout simplificado para páginas.
- `includes/singular-helpers.php`: utilidades de lectura estimada, outline de headings y URLs de meta.
- `assets/css/singular.css`: estilos de `single post` y `single page`.

### Single Post

- Hero con categoría, fecha y tiempo estimado de lectura.
- Cuerpo principal de contenido con soporte Gutenberg (`the_content()`).
- Sidebar (desktop) con:
	- "En este post" (headings H2 detectados del contenido).
	- Títulos clicables con navegación por ancla.
	- Offset dinámico de anclas para que los headings no queden ocultos debajo del header sticky ni de la barra de lectura.
	- Resaltado automático del título activo conforme al scroll.
	- Enlaces opcionales de Repo y Demo por meta fields.
- Barra sticky de contexto al hacer scroll (título del post) con progreso de lectura como subrayado morado.
- Sección de "Más publicaciones" al final.

Meta fields opcionales para el post:

- `econopapi_repo_url`
- `econopapi_demo_url`

### Single Page

- Hero simple con título y extracto (si existe).
- Contenido en una sola columna para una lectura más limpia.
- Barra sticky de contexto al hacer scroll (título de la página) con progreso de lectura.

### Carga de assets

`assets/css/singular.css` se carga automáticamente en `is_singular()` (excepto front page).

`assets/js/single-outline.js` se carga en `is_single()` para scrollspy del sidebar.

`assets/js/singular-reading-bar.js` se carga en `is_singular()` (excepto front page) para la barra sticky de título + progreso de lectura.

## Blog Archive (listado de publicaciones)

Se agregó una plantilla dedicada para el archivo de blog, siguiendo el diseño de referencia con encabezado editorial, chips de filtro por categoría, grid de tarjetas y paginación tipo "Cargar más publicaciones".

### Estructura agregada

- `home.php`: template principal para índice de publicaciones (`is_home`).
- `template-parts/blog/content-blog.php`: markup del archive (hero, filtros, listado y paginación).
- `includes/blog-archive.php`: helpers de URL/filtro y carga condicional de assets.
- `assets/css/blog-archive.css`: estilos responsivos del archive con soporte light/dark.

### Comportamiento

- Se muestra el heading "Blog / Publicaciones" con descripción.
- Filtros por categorías con estado activo y opción "Todos".
- Grid de posts en 3 columnas (2 en tablet, 1 en mobile).
- Cada card incluye categoría principal, título, extracto y meta de fecha + lectura estimada.
- Botón "Cargar más publicaciones" enlaza a la siguiente página del loop principal cuando existe.

### Carga incremental por AJAX

- El botón "Cargar más publicaciones" ahora usa AJAX para agregar más cards sin recargar la página.
- Se mantiene fallback por enlace normal para escenarios sin JavaScript o ante error de red.
- Endpoint implementado con nonce y sanitización en `admin-ajax.php`.

## Proyectos (CPT)

Se agregó un Custom Post Type para la sección de Proyectos, separado del flujo de publicaciones del blog.

### Estructura agregada

- `includes/projects.php`: registro del CPT, metaboxes, helpers de render y carga condicional de assets.
- `archive-project.php`: plantilla principal para el archivo de proyectos.
- `template-parts/project/content-project.php`: markup del listado de proyectos.
- `single-project.php`: plantilla singular dedicada para el CPT `project`.
- `template-parts/project/content-single-project.php`: layout singular de proyectos con sidebar y relacionados propios.
- `assets/css/projects-archive.css`: estilos responsivos del archive con soporte light/dark.

### CPT: `project`

- Slug de archivo: `/proyectos`
- Slug singular: `/proyecto/{slug}`
- Soportes: título, contenido, extracto, imagen destacada y revisiones.
- Taxonomía habilitada: tags (`post_tag`) para chips de tecnologías o categorías técnicas.

### Fields de Proyecto (meta)

En cada proyecto se agregó un metabox lateral con:

- `Estatus`: opciones predefinidas (`En vivo`, `En desarrollo`, `Activo`, `Pausado`).
- `URL del proyecto`: URL principal pública (demo, app o landing).
- `URL del repositorio`: opcional, pensada para enlazar el código fuente (por ejemplo, GitHub).

### Render del archivo

Cada tarjeta del archive muestra:

- Título del proyecto (enlace a URL externa o permalink si no existe URL).
- Badge de estatus.
- Extracto como subtítulo breve.
- Descripción breve derivada del contenido.
- Chips de tags/técnologías.
- Link visible con dominio/URL del proyecto.

## Single Project

Los proyectos ya no reutilizan el mismo flujo visual de `single post`; ahora cuentan con una experiencia singular propia.

### Comportamiento

- Hero con estatus, fecha y tiempo estimado de lectura.
- Acciones rápidas para abrir el proyecto y, si existe, su repositorio.
- Sidebar contextual en desktop con:
	- Ficha del proyecto (`Estatus`, `URL del proyecto`, `URL del repositorio` si existe).
	- Índice interactivo de headings H2 del contenido, igual que en posts normales.
	- Soporte para índices largos mediante scroll interno en el panel lateral.
- El índice ya no se recorta artificialmente; se listan todos los H2 detectados del contenido.
- Al comenzar la lectura y rebasar el primer H2:
	- La ficha lateral se contrae para priorizar el índice.
	- La barra sticky superior muestra una versión resumida de la ficha del proyecto.
- Los enlaces de la ficha usan iconografía contextual y, para repositorios de GitHub, se formatean como `owner/repo.git`.
- Sección final de relacionados dividida en dos columnas:
	- `Más publicaciones`
	- `Otros proyectos`

### Helpers reutilizables

En `includes/projects.php` se agregaron helpers para:

- Normalizar metadatos del proyecto.
- Formatear hosts/labels de URLs.
- Obtener queries de contenido relacionado para posts y proyectos.

## Nota para siguientes iteraciones

- Si se desea toggle manual de tema (switch claro/oscuro), se puede agregar en una siguiente etapa con almacenamiento de preferencia en `localStorage` y sincronización de clase en `body`.