# Guía visual

## Propósito

Mantener una presentación uniforme entre el acceso, la página de inicio y las pantallas del módulo de constancias. La implementación compartida vive en `assets/css/styles.css`; las vistas deben reutilizar sus clases antes de introducir estilos locales.

## Identidad visual

- Color institucional principal: `#841816` (`--primary-color`). Se usa en encabezados, acentos, botones principales, foco de controles y elementos seleccionados.
- Color institucional de interacción: `#b91116` (`--secondary-color`). Se reserva para hover y foco visible de botones.
- Fondo general: `#dce0e3` (`--light-grey`). Las superficies de trabajo usan blanco.
- Superficie secundaria: `#f8f9fa` (`--subtle-surface`). Se usa en filtros, encabezados de modal y bloques de formularios.
- Bordes: `#e6e6e6` (`--surface-border`). Deben ser discretos; el acento institucional se expresa con una franja superior o un borde lateral, no con varios bordes decorativos.

## Superficies y composición

- Las superficies principales (`.login-card`, `.welcome-panel` y `.ciclos-page`) comparten franja superior de 5 px, radio `--surface-radius` y sombra `--surface-shadow`.
- Los filtros y formularios secundarios usan fondo `--subtle-surface`, borde fino y un acento izquierdo de 4 px.
- El modal de edición conserva la superficie blanca, el acento superior y la misma sombra que las páginas principales. Su encabezado y pie usan la superficie secundaria.
- Mantener una jerarquía sencilla: encabezado de página, contenido y acciones. Evitar tarjetas anidadas y sombras adicionales dentro de una superficie principal.

## Tipografía y controles

- Usar la familia definida por `--main-font` y la escala tipográfica de Bootstrap (`h1`/`h2`, `h3`/`h5`, texto secundario).
- Los títulos de sección y las etiquetas de formulario deben ser legibles y consistentes; las etiquetas se presentan en negrita moderada y gris oscuro.
- Los botones de acción principal usan `.btn-primary-uaeh`. Reservar el rojo alterno para sus estados de interacción.
- Los campos enfocados usan el borde institucional y un halo de bajo contraste. No eliminar los indicadores de foco.
- Preferir clases semánticas y componentes Bootstrap sobre estilos inline o clases de tamaño que contradigan la escala definida para el componente.

## Responsive

- Mantener los contenedores de trabajo alrededor del 80% del ancho disponible en escritorio y ocupar el ancho disponible en móvil.
- En móvil, reducir el padding de las superficies, permitir que las acciones se envuelvan y evitar anchos mínimos que generen desbordamiento de página. Las tablas pueden conservar su desplazamiento horizontal dentro de `.table-responsive`.
- Revisar que encabezados, formularios, modal y botones no se encimen a 576 px o menos.

## Cambios de estilo

Agregar o modificar tokens en `:root` cuando un valor de diseño se repita. Agrupar reglas por componente y alcance (`.login-form`, `.ciclos-form-page`, `.ciclos-modal`) para no alterar componentes Bootstrap ajenos. No duplicar reglas de la hoja compartida dentro de cada vista.
