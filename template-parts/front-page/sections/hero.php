<?php
/**
 * Fallback hero section (rendered as custom block output).
 *
 * @package EconopapiWP
 */

$default_hero_block = '<!-- wp:econopapi/hero {"tagline":"ECONOMISTA · DESARROLLADOR","title":"Datos, código y economía — desde México.","description":"Escribo sobre el cruce entre tecnología, datos y economía. Construyo herramientas con Python, PHP y todo lo que tenga sentido.","primaryButtonLabel":"Leer el blog","primaryButtonUrl":"/blog","secondaryButtonLabel":"Ver proyectos","secondaryButtonUrl":"/proyectos"} /-->';

echo do_blocks( $default_hero_block );
