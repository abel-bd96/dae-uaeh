# Definición del proyecto

## 1. Información general

Sistema web para la Dirección de Administración Escolar (DAE) de la Universidad Autónoma del Estado de Hidalgo (UAEH), cuyo propósito es centralizar, estandarizar y modernizar progresivamente los procesos administrativos de la Dirección. El desarrollo iniciará con el módulo de emisión de constancias de estudio y será un proyecto integral entre las distintas áreas de la Dirección, el área de informática será la encargada de desarrollar dicho sistema utilizando el stack tecnológico dispuesto por la Dirección de Información y Sistemas (DIyS), siendo este: HTML, CSS, JavaScript, PHP en su versión 7.2.9 y Bootstrap opcionalmente.

## 2. Problema identificado

### 2.1 Problema general de la DAE

La DAE no cuenta con un sistema centralizado para realizar sus actividades en sus diferentes áreas. Actualmente existen aplicaciones desarrolladas en Delphi, pero esto genera fragmentación pues en ocasiones hay que utilizar varias de estas aplicaciones para realizar una actividad.

Otra de las problemáticas es la alta dependencia a la DIyS para la generación de proyectos, ya que la base de datos institucional está bajo su responsabilidad y por la alta demanda de trabajo que presentan hay ocasiones en las que no se puede dar seguimiento adecuado a las necesidades de la DAE.

También se ha notado que hay procesos que no están estandarizados dentro de la dirección, adenás de que algunos todavía se realizan de manera manual o sin las herramientas adecaudas.

Tampoco se cuenta con una base de datos propia para almacenar la información que solo es competencia de la Dirección; al depender de la DIyS la generación de estructuras nuevas en la base de datos para almacenar información es bastante lenta.

### 2.2 Problema específico del módulo de emisión de constancias de estudio

Actualmente el proceso de solicitud de constancias académicas presenta diversas limitaciones operativas y administrativas debido a que gran parte del flujo se realiza de manera manual.

El procedimiento vigente requiere que el alumno primero realice el pago correspondiente de la constancia llenando un formulario de pago de manera manual y, posteriormente complete la solicitud en un formulario de Google Forms donde debe registrar sus datos y adjuntar el comprobante de pago. En caso de que el alumno no complete correctamente dicho formulario, la solicitud no puede ser procesada, aún cuando el pago ya haya sido realizado, esto debido a que requiere de correcciones manuales y normalmente implica comunicación directa con el área de Constancias y Certificados la cual tiende a ser lenta debido a que se realiza vía correo electrónico.

Este proceso genera problemáticas como:

- Dependencia de herramientas externas como Google Forms.
- Posibles errores de captura de información por parte del alumno.
- Retrasos en la validación de datos y atención de solicitudes.
- Dificultad para dar seguimiento al trámite.
- Falta de historial centralizado de solicitudes realizadas.
- Riesgo de pérdida o duplicidad de información.
- Comunicación limitada entre el alumno y el área responsable.
- Retraso en emisión de constancias durante el periodo vacacional debido a la necesidad de revisión humana de las solicitudes.
- Fragmentación del proceso, ya que este se realiza en 3 sitios diferentes, lo que puede ocasionar confusión.
- Los organismos que solicitan constancias a los alumnos necesitan llamar para validar la autenticidad del documento.

Además, el proceso actual no ofrece automatización para la emisión de constancias digitales ni una trazabilidad clara del trámite desde su inicio hasta su finalización.

## 3. Justificación

El desarrollar una aplicación web para las actividades realizadas por la DAE podría agilizar diversos procesos que actualmente requieren de mucha intervención humana y por ende es propensa a cometer errores, además de que se emplea tiempo y recursos que podrían utilizarse de manera más optima. Un sistema modernizado con procesos estandarizados permitiría agilizar y aligerar la carga de trabajo de la Dirección, facilitando el grueso de porcesos que podrían realizarse de manera automática y dejando tiempo para revisar aquellas excepciones o casos especiales que si requieran de atención del personal de la Dirección. Al contar con una cierta libertad, se podrían aminorar la dependencia que se tiene con la DIyS para ciertos cambios y lograr agilizarlos.

## 4. Objetivo general

Desarrollar e implementar un sistema web para la DAE que permita la centralización, agilización y estandarización de los procesos llevados por la Dirección. Dicho sistema debe ser funcional tanto para alumnos como para el personal de la Dirección.

## 5. Objetivos específicos

### 5.1 Objetivo del módulo de emisión de constancias

Desarrollar e implementar un módulo web para el departamento de Constancias y Certificados; integrado con el sistema de "Servicios en Línea" pertenecientes a la DIyS que permita al alumnado perteneciente a las distintas escuelas dependientes de la UAEH realizar solicitudes de constancias académicas de manera digital, consultar el estado de sus trámites y obtener constancias académicas de manera digital, consultar el estado de sus trámites y obtener constancias electrónicas de forma más rápida, organizada y segura. Esto también incluirá el desarrollo de un modo administrativo para el departamento de Constancias y Certificados que permita la gestión efectiva de sus procesos y solicitudes.

El módulo tiene como propósito automatizar y centralizar el proceso de solicitud de constancias académicas para alumnos con estatus activo o inscrito, integrandose con el sistema institucional existente denominado "Servicios en Línea". A través de esta integración, el alumnado podrá realizar el trámite en línea, constular el estado de sus solicitudes y obtener sus constancias de forma más rápida y organizada.

La solución contempla la generación de constancias con irma digital y autógrafa, permitiendo gestionar cada flujo de acuerdo con las necesidades operativas del área administrativa.

## 6. Alcance inicial

### 6.1 Alcance del módulo de emisión de constancias de estudio

- Acceso e inicio de sesión para el alumnado desde el sistema institucional “Servicios en Línea” de la DIyS, con identificación automática del alumno y programa educativo.
- Visualización de datos académicos y selección del programa educativo correspondiente.
- Registro de nuevas solicitudes y selección del tipo de firma (autógrafa o digital).
- Conexión con el sistema de finanzas para la generación automática de formatos/referencias de pago y validación de los mismos.
- Generación automática de constancias digitales en formato PDF para su descarga una vez validado el pago, enviando una notificación al alumno.
- Seguimiento de constancias con firma autógrafa, permitiendo registrar a una persona autorizada para su recolección y notificando al alumno cuando esté lista para entrega.
- Consulta del historial y estado de los trámites (Solicitado, Pagado, Elaborado, Emitido, Finalizado y Cancelado).
- Cancelación de solicitudes por parte del alumno (solo en estado “Solicitud”) o por la DAE (en estados específicos).
- Acceso, inicio de sesión y registro de usuarios administrativos desde una pantalla de login usando correo electrónico institucional y contraseña con opción de recuperación de contraseña.
- Generación de solicitudes por parte del departamento de Constancias y Certificados teniendo la opción de generar los certificados de manera manual y opción de omitir pagos requeridos en casos especiales.
- Seguimiento de constancias con monitoreo de estados del proceso y gestión de los mismos por parte del departamento de Constancias y Certificados.
- Planeación de fechas de realización de trámites por periodo escolar y tomando en cuenta los periodos vacacionales.
- Planeación específica por programa educativo de fechas de realización de trámites por periodo escolar y tomando en cuenta los periodos vacacionales.
- Administración de usuarios con asignación de roles y permisos y posibilidad de agregar usuarios nuevos y eliminar usuarios existentes.
- Lista negra de alumnos sin derecho a solicitar trámites. (No en la UI, sólo en la base de datos por el momento).
- Configuración de perfil de usuario, sólo para actualización de contraseña.

## 7. Fuera de alcance

### 7.1 Fuera de alcance del módulo de emisión de constancias de estudio

- Trámites para alumnos egresados o dados de baja (quienes usarán temporalmente el procedimiento tradicional mediante formulario externo).
- Solicitudes de constancias con información personalizada o fuera del formato estándar.
- Procesos administrativos internos del área de certificación posteriores a la recepción de solicitudes.
- Generación de otros documentos escolares distintos a constancias.
- Generación de reportes para el departamento de Constancias y Certificados.

## 8. Usuarios y actores iniciales

El sistema contemplará inicialmente diferentes tipos de usuarios y sistemas externos que participarán en el proceso de emisión de constancias.

### 8.1 Alumnado

Usuarios que realizarán solicitudes de constancias académicas mediante la integración con el sistema institucional “Servicios en Línea”.

Sus principales acciones serán:

- Acceder al módulo mediante su sesión institucional a través de "Servicios en Línea".
- Seleccionar el programa educativo correspondiente.
- Solicitar constancias disponibles.
- Consultar referencias y estatus de pago.
- Consultar el estado de sus solicitudes.
- Cancelar solicitudes cuando el estado del trámite lo permita.
- Descargar constancias digitales cuando hayan sido emitidas.
- Registrar, cuando corresponda, a una persona autorizada para recoger una constancia con firma autógrafa.
- Consultar el historial de solicitudes realizadas.

### 8.2 Personal del departamento de Constancias y Certificados

Usuarios responsables de la gestión operativa de las solicitudes.

Sus principales acciones serán:

- Consultar y administrar las solicitudes recibidas.
- Validar información y documentación relacionada con los trámites.
- Gestionar el estado de las solicitudes.
- Generar constancias de manera automática o manual, según corresponda.
- Registrar y administrar constancias con firma autógrafa.
- Consultar información necesaria para la atención de solicitudes.
- Gestionar periodos de atención de acuerdo con el calendario establecido.
- Programar restricciones o condiciones especiales para determinados periodos o programas educativos.
- Alta y baja de usuarios por parte de la persona responsable del área.
- Asignación y modificación de roles y permisos por parte de la persona responsable del área.

### 8.3 Usuarios administrativos del sistema

Usuarios pertenecientes a la DAE que requieran acceder al sistema para realizar funciones administrativas de acuerdo con los permisos asignados.

Sus capacidades estarán determinadas mediante un esquema de roles y permisos, evitando que todos los usuarios tengan acceso a las mismas funciones.

### 8.4 Administrador del sistema

Usuario con permisos para realizar tareas de administración general, entre ellas:

- Alta y baja de usuarios administrativos.
- Asignación y modificación de roles y permisos.
- Configuración de parámetros del sistema.
- Gestión de restricciones de acceso a determinados trámites.
- Actualización de su propia contraseña.

### 8.5 Sistemas externos

El proyecto dependerá inicialmente de sistemas institucionales externos para determinadas operaciones:

- **Servicios en Línea de la DIyS:** autenticación y obtención de información académica del alumno.
- **Sistema institucional de Finanzas:** generación y validación de referencias o formatos de pago.
- **Servicios institucionales de correo o notificaciones:** envío de avisos relacionados con el estado de los trámites, sujeto a la infraestructura disponible.

Estos sistemas no forman parte del desarrollo directo del módulo, por lo que su disponibilidad, interfaces y modificaciones estarán sujetas a sus respectivas áreas responsables.

### 8.6 Módulo inicial

El primer módulo contemplado dentro del proyecto será:

- **Módulo de emisión de constancias de estudio.**

Este módulo servirá como primera etapa de la plataforma y como base para posteriormente incorporar otros procesos de la Dirección.

---

## 9. Visión futura

El sistema se plantea como una plataforma integral para la Dirección de Administración Escolar, cuyo desarrollo será progresivo y podrá incorporar nuevos módulos y procesos conforme se identifiquen necesidades y se establezcan prioridades.

A largo plazo se contempla:

- Incorporar progresivamente procesos de las distintas áreas de la DAE.
- Centralizar información y operaciones administrativas que actualmente se encuentran fragmentadas entre diferentes aplicaciones.
- Reducir gradualmente la dependencia de aplicaciones desarrolladas en tecnologías obsoletas o aisladas.
- Estandarizar procesos administrativos entre las distintas áreas de la Dirección.
- Automatizar actividades repetitivas y procesos que actualmente requieren intervención manual.
- Implementar un modelo centralizado de usuarios, roles y permisos.
- Mantener un historial y trazabilidad de las operaciones realizadas dentro del sistema.
- Generar herramientas de consulta y análisis para apoyar la toma de decisiones administrativas.
- Incorporar módulos adicionales para otros trámites y servicios dirigidos al alumnado.
- Evaluar progresivamente la creación y administración de estructuras de información propias de la DAE, respetando las políticas y lineamientos institucionales.
- Establecer una arquitectura que permita agregar nuevos módulos sin necesidad de desarrollar aplicaciones independientes para cada proceso.

La visión del proyecto no consiste únicamente en digitalizar los procedimientos actuales, sino en establecer una plataforma que permita a la DAE evolucionar progresivamente hacia procesos más estandarizados, integrados y automatizados.

---

## 10. Restricciones

El desarrollo inicial del proyecto estará condicionado por restricciones institucionales, tecnológicas y operativas.

### 10.1 Restricciones tecnológicas

- El desarrollo deberá utilizar el stack tecnológico definido por la Dirección de Información y Sistemas.
- Para la primera etapa se utilizarán HTML, CSS, JavaScript y PHP 7.2.9.
- Bootstrap podrá utilizarse como herramienta complementaria para la construcción de la interfaz.
- No se contempla inicialmente la adopción de tecnologías o frameworks que no hayan sido autorizados por la DIyS.
- La infraestructura de servidores, bases de datos y servicios institucionales deberá ajustarse a las capacidades disponibles dentro de la Universidad.

### 10.2 Restricciones de integración

- El módulo dependerá de la disponibilidad e infraestructura de datos proporcionadas por los sistemas institucionales de la DIyS.
- La autenticación del alumnado se realizará mediante la integración con “Servicios en Línea”, por lo que el sistema no administrará directamente las credenciales de los alumnos.
- La información académica utilizada por el sistema dependerá de los datos proporcionados por los sistemas institucionales.
- La generación y validación de pagos dependerá de la infraestructura y servicios proporcionados por el sistema de Finanzas.
- Los cambios en sistemas externos podrán requerir modificaciones en el módulo y deberán coordinarse con las áreas responsables.

### 10.3 Restricciones institucionales

<!-- - El sistema deberá cumplir con las políticas, lineamientos y mecanismos de seguridad establecidos por la UAEH. -->
- Los accesos administrativos estarán restringidos al personal autorizado.
<!-- - La información académica y administrativa deberá manejarse de acuerdo con las disposiciones institucionales aplicables.  -->
- Las modificaciones que involucren sistemas administrados por otras áreas estarán sujetas a autorización y coordinación con dichas áreas.

### 10.4 Restricciones de alcance

- El primer desarrollo se limitará al módulo de emisión de constancias de estudio.
- No se contempla inicialmente la migración completa de todos los procesos y aplicaciones existentes de la DAE.
- Los trámites para alumnos egresados o dados de baja permanecerán temporalmente fuera del sistema.
- Las solicitudes de documentos con características especiales o información no contemplada por los formatos definidos permanecerán fuera del alcance inicial.
- Los reportes administrativos especializados no forman parte de la primera versión.

### 10.5 Restricciones operativas

- La automatización estará limitada por las reglas y excepciones definidas por el área responsable.
- Los procesos que requieran firma autógrafa continuarán dependiendo de actividades presenciales o de intervención del personal.
- Durante la etapa inicial podrán coexistir procedimientos digitales y procedimientos tradicionales mientras se completa la transición.

---

## 11. Criterios generales de éxito

El proyecto se considerará exitoso en su primera etapa cuando el módulo de emisión de constancias permita digitalizar y centralizar de manera funcional el proceso definido, cumpliendo como mínimo con los siguientes criterios:

### 11.1 Funcionalidad

- El alumnado podrá iniciar una solicitud desde “Servicios en Línea” sin necesidad de registrar nuevamente sus datos básicos.
- El sistema permitirá gestionar el ciclo de vida de una solicitud desde su creación hasta su finalización o cancelación.
- Será posible consultar el estado de cada solicitud en cualquier momento.
- Las constancias digitales podrán generarse y descargarse correctamente cuando se cumplan las condiciones definidas.
- El personal de Constancias y Certificados podrá administrar las solicitudes desde un módulo administrativo.
- El sistema permitirá gestionar diferentes roles y permisos de usuario.

### 11.2 Reducción de trabajo manual

- Se reducirá la captura repetitiva de información por parte del alumno y del personal administrativo.
- Se disminuirá la necesidad de revisar manualmente información que pueda validarse mediante los sistemas institucionales.
- Se reducirá el intercambio de información mediante correo electrónico para consultar o corregir el estado de los trámites.
- Se automatizarán, en la medida permitida por las integraciones disponibles, las actividades relacionadas con pago, generación y notificación de constancias.

### 11.3 Trazabilidad

- Cada solicitud deberá contar con un registro único.
- El sistema deberá conservar el historial de estados y acciones relevantes realizadas sobre cada solicitud.
- El personal autorizado deberá poder identificar en qué etapa del proceso se encuentra cada trámite.
- Los cambios relevantes deberán quedar asociados al usuario que los realizó cuando sea técnicamente aplicable.

### 11.4 Disponibilidad y operación

- El sistema deberá encontrarse disponible durante los periodos establecidos para la realización de trámites.
- Las operaciones principales deberán poder completarse sin depender de herramientas externas no contempladas en el flujo institucional.
- El sistema deberá permitir la atención de solicitudes sin necesidad de intervención técnica para operaciones administrativas ordinarias.

### 11.5 Calidad

- El módulo deberá cumplir con los requerimientos funcionales definidos por el departamento de Constancias y Certificados y el departamento de Informática.
- Las funciones críticas deberán probarse antes de la puesta en producción.
- No deberán existir errores conocidos de severidad crítica que impidan realizar solicitudes, procesarlas o emitir las constancias.
- La interfaz deberá ser utilizable desde computadoras y dispositivos móviles.

### 11.6 Adopción

- El personal responsable deberá poder realizar las actividades necesarias sin depender constantemente del área de informática.
- El alumnado deberá poder completar una solicitud sin requerir asistencia del personal en los casos considerados como flujo normal.
- La puesta en operación deberá acompañarse de instrucciones o material de apoyo cuando sea necesario.

### 11.7 Evolución

- La arquitectura y estructura del módulo deberán permitir la incorporación posterior de nuevos procesos de la DAE sin tener que desarrollar una aplicación completamente independiente.
- El primer módulo deberá establecer patrones reutilizables para autenticación, usuarios, roles, estados, notificaciones, archivos e integración con servicios institucionales.
