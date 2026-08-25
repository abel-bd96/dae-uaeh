# Definición del proyecto

## 1. Información general

Sistema web para la Dirección de Administración Escolar (DAE) de la Universidad Autónoma del Estado de Hidalgo (UAEH), cuyo propósito es centralizar, estandarizar y modernizar progresivamente los procesos administrativos de la Dirección. El desarrollo iniciará con el módulo de emisión de constancias de estudio y será un proyecto integral entre las distintas áreas de la Dirección, así como con algunas otras Direcciones y Dependencias de la UAEH. El área de Informática será la encargada de desarrollar dicho sistema, en colaboración con estas áreas, Direcciones y Dependencias, utilizando el stack tecnológico dispuesto por la Dirección de Información y Sistemas (DIyS): HTML, CSS, JavaScript, PHP en su versión 7.2.9, Bootstrap de manera opcional, Git para el control de versiones y Microsoft SQL Server para la base de datos.

## 2. Problema identificado

### 2.1 Problema general de la DAE

La DAE no cuenta con un sistema centralizado para realizar sus actividades en sus diferentes áreas. Actualmente existen aplicaciones cliente-servidor desarrolladas en Delphi, pero esto genera fragmentación en los distintos procesos que realiza el personal de la DAE, pues en ocasiones es necesario utilizar varias de estas aplicaciones para realizar un solo proceso.

Otra de las problemáticas es la alta dependencia de la DIyS, tanto para la generación de nuevos proyectos como para la actualización y mantenimiento de proyectos existentes, ya que la base de datos institucional está bajo su responsabilidad. Debido a la alta demanda de trabajo que presenta dicha área, en ocasiones no es posible dar un seguimiento adecuado a las necesidades de la DAE y algunas solicitudes pueden quedar pendientes o no ser atendidas oportunamente.

También se ha notado que hay procesos que no están estandarizados dentro de la DAE, además de que algunos todavía se realizan de manera manual o sin las herramientas adecuadas.

Tampoco se cuenta con una base de datos propia para almacenar la información que es competencia exclusiva de la DAE. Al depender de la DIyS para la generación de nuevas estructuras en la base de datos destinadas a almacenar información, estos procesos pueden resultar lentos.

### 2.2 Problema específico del módulo de emisión de constancias de estudio <!-- Nombre provisional -->

Actualmente, el proceso de solicitud de constancias de estudio presenta diversas limitaciones operativas y administrativas debido a que gran parte del proceso se realiza de manera manual.

El procedimiento vigente requiere que el alumno primero realice el pago correspondiente de la constancia, llenando manualmente un formulario de pago en un sistema externo (Sistema de cobros en línea de la Coordinación de Administración y Finanzas). Esto puede causar confusión, ya que no se especifica claramente cómo debe llenarse dicho formulario para generar la orden de pago y, en ocasiones, los alumnos terminan realizando los pagos por un concepto erróneo. Posteriormente, el alumno debe completar la solicitud en un formulario de Google Forms, donde debe registrar sus datos y adjuntar el comprobante de pago. En caso de que el alumno no llene correctamente dicho formulario, la solicitud no puede ser procesada, aun cuando el pago ya haya sido realizado, debido a que requiere correcciones manuales y normalmente implica comunicación directa con el área de Constancias y Certificados, la cual puede presentar demoras debido a que esta comunicación se realiza vía correo electrónico.

Aunado a esto, actualmente existen tres tipos de constancias, las cuales contienen distintos datos académicos de los alumnos. Esto puede causar confusión entre los solicitantes y ocasionar que, en ocasiones, soliciten un tipo de constancia que no contenga los datos que necesitan.

Este proceso genera problemáticas como:

- Dependencia de herramientas externas como Google Forms.
- Posibles errores de captura de información por parte del alumno.
- Retrasos en la validación de datos y atención de solicitudes.
- Dificultad para dar seguimiento al trámite.
- Falta de historial centralizado de solicitudes realizadas.
- Riesgo de pérdida o duplicidad de información.
- Comunicación limitada entre el alumno y el área responsable.
- Retraso en la emisión de constancias durante el periodo vacacional debido a la necesidad de revisión humana de las solicitudes.
- Fragmentación del proceso, ya que este se realiza en tres sitios diferentes, lo que puede ocasionar confusión.
- No existe vinculación entre la solicitud de constancia de estudio y el pago realizado, lo cual impide verificar correctamente si un pago corresponde a la solicitud realizada por el alumno.

Además, el proceso actual no ofrece automatización para la emisión de constancias digitales ni una trazabilidad clara del trámite desde su inicio hasta su finalización.

## 3. Justificación

El desarrollo de una aplicación web para las actividades realizadas por la DAE podría agilizar diversos procesos que actualmente requieren de mucha intervención humana y, por ende, son propensos a contener errores, además de que emplean tiempo y recursos que podrían utilizarse de manera óptima. Un sistema modernizado con procesos estandarizados permitiría agilizar y aligerar la carga de trabajo del personal de la Dirección, facilitando la mayoría de los procesos que podrían realizarse de manera sistematizada y dejando tiempo para revisar aquellas excepciones o casos especiales que sí requieran de atención del personal de la Dirección. Al contar con cierta libertad operativa, se podría aminorar la dependencia que se tiene con la DIyS para ciertos cambios y agilizar su implementación.

## 4. Objetivo general

Desarrollar e implementar un sistema web para la DAE que permita la definición, estandarización, centralización y agilización de los procesos llevados a cabo por la DAE. Dicho sistema debe ser funcional tanto para los alumnos como para el personal de la DAE.

## 5. Objetivos específicos

### 5.1 Objetivo del módulo de emisión de constancias de estudio

Desarrollar e implementar un módulo web para el área de Constancias y Certificados, integrado con el sistema de “Servicios en Línea” perteneciente a la DIyS, que permita a todo el alumnado, tanto activo como inactivo, de todos los niveles académicos de la Universidad Autónoma del Estado de Hidalgo (UAEH) y sus Escuelas incorporadas, realizar solicitudes de constancias académicas de manera digital y consultar el estado de sus trámites de forma más rápida, organizada y segura. El módulo también incluirá un apartado para el área de Constancias y Certificados que permita la gestión efectiva de sus procesos y solicitudes.

La solución contempla la generación de constancias con firma digital y autógrafa, permitiendo gestionar cada flujo de acuerdo con las necesidades operativas del área administrativa.

Entiéndase como constancia de estudio el documento administrativo que detalla datos clave del alumno, como el estado de inscripción, el semestre cursado, los créditos acumulados y el promedio general, y que sirve como prueba fehaciente de dichos hechos ante terceros hasta el momento de su emisión.

Usos principales de una constancia de estudio:

- **Trámites de becas**: Demostrar ante instituciones gubernamentales o privadas que el alumno cuenta con un promedio determinado y mantiene un registro activo.
- **Seguridad social**: Facilitar el alta o la vigencia de derechos en servicios médicos como el IMSS.
- **Empleo y prácticas**: Comprobar el estatus de estudiante para la realización de servicio social, prácticas profesionales o para postularse a ofertas laborales.
- **Descuentos y movilidad**: Tramitar tarifas preferenciales en transporte público, visas de estudiante u otros beneficios institucionales y legales.

## 6. Alcance

### 6.1 Alcance inicial del módulo de emisión de constancias de estudio (primer incremento)

- Solicitud en línea de constancias de estudio para alumnos actualmente inscritos y con estatus activo de las escuelas dependientes de la UAEH, que cuenten con su expediente validado y no estén imposibilitados para solicitar dicho documento según el registro existente en la lista que administre el área de Constancias y Certificados.
- Acceso e inicio de sesión para el alumnado desde el sistema institucional “Servicios en Línea” de la DIyS, con identificación automática del alumno y programa educativo.
- Selección del programa educativo correspondiente.
- Registro de nuevas solicitudes y selección del tipo de firma (autógrafa o digital).
- Conexión con el sistema de Finanzas para la generación automática de formatos o referencias de pago y validación de los mismos.
- Generación automática de constancias digitales en formato PDF para su descarga una vez validado el pago.
- Seguimiento de constancias con firma autógrafa, permitiendo registrar a una persona autorizada para su recolección.
- Seguimiento por parte del área de Constancias y Certificados de la solicitud y estado de los trámites: Solicitada, Pagada, En Elaboración, Emitida, Finalizada y Cancelada.
- Seguimiento por parte del alumno de la constancia y estado de la constancia: Elaborada, Enviada a Firma, Emitida, Entregada y Cancelada.
- Cancelación de solicitudes por parte del alumno o de la DAE.
- Acceso, inicio de sesión y registro de usuarios administrativos desde una pantalla de inicio de sesión utilizando correo electrónico institucional y contraseña, con opción de recuperación de contraseña.
- Planeación de fechas por periodo escolar, tomando en cuenta los periodos vacacionales.
- Administración de usuarios con asignación de roles y permisos, así como la posibilidad de agregar usuarios nuevos y eliminar usuarios existentes.
- Consulta de la lista de alumnos sin derecho a solicitar trámites. La lista será administrada por el personal del área de Constancias y Certificados.
- Validador en el sistema de las constancias emitidas, a través de un código QR incluido en la constancia.<!-- (¿El historial de constancias emitidas tendrá vigencia dentro de la BD, o es perpetuo?) -->
- Configuración del usuario para la actualización de contraseña.

## 7. Fuera de alcance

### 7.1 Fuera de alcance del módulo de emisión de constancias de estudio (primer incremento)

- Emisión de constancias para alumnos egresados, con baja y activos que no cuenten con su expediente de documentos de manera digital (quienes utilizarán temporalmente el procedimiento tradicional mediante formulario externo).
- Emisión de constancias para alumnos de escuelas incorporadas.
- Emisión de constancias que no se ajusten al formato estándar definido por el área de Constancias y Certificados.
- Generación de otros documentos escolares distintos a constancias de estudio.
- Generación de reportes para el área de Constancias y Certificados (se requiere que el área de Constancias y Certificados defina sus indicadores).
- Registro de usuarios en la base de datos de alumnos que, por antigüedad, actualmente no se encuentren en la base de datos del SIAE (Sistema Integral de Administración Escolar) y existan únicamente en el archivo físico de la DAE.

## 8. Usuarios y actores

### 8.1 Usuarios y actores del módulo de emisión de constancias de estudio

El sistema contemplará inicialmente diferentes tipos de usuarios y sistemas externos que participarán en el proceso de emisión de constancias.

#### 8.1.1 Alumnado

Usuarios que realizarán solicitudes de constancias académicas mediante la integración con el sistema institucional “Servicios en Línea”.

Entre sus principales acciones se encuentran:

- Ingresar al módulo mediante su sesión institucional a través de “Servicios en Línea”.
- Seleccionar el programa educativo correspondiente.
- Iniciar solicitudes de constancias de estudio, ya sean digitales o con firma autógrafa.
- Consultar referencias y estatus de pago asociados a sus solicitudes.
- Dar seguimiento al estado de sus trámites.
- Cancelar solicitudes, cuando corresponda.
- Descargar las constancias digitales una vez que hayan sido emitidas.
- Registrar, cuando corresponda, a una persona autorizada para recoger una constancia con firma autógrafa.
- Consultar el historial de solicitudes realizadas.

#### 8.1.2 Personal del área de Constancias y Certificados

Usuarios responsables de la gestión operativa de las solicitudes.

Sus principales acciones serán:

- Consultar y administrar las solicitudes recibidas.
- Gestionar el estado de las solicitudes.
- Generar solicitudes de constancias, según corresponda.
- Registrar y administrar constancias con firma autógrafa.
- Consultar información necesaria para la atención de solicitudes.
- Programar restricciones o condiciones especiales para determinados periodos o programas educativos.

#### 8.1.3 Usuarios administrativos del sistema

Usuarios pertenecientes a la DAE que requieran acceder al sistema para realizar funciones administrativas de acuerdo con los permisos asignados.

Sus capacidades estarán determinadas mediante un esquema de roles y permisos, evitando que todos los usuarios tengan acceso a las mismas funciones.

#### 8.1.4 Administrador del módulo (responsable del área de Constancias y Certificados)

Usuario con permisos para realizar tareas de administración general, entre ellas:

- Alta y baja de usuarios administrativos.
- Asignación y modificación de roles y permisos.
- Gestión de restricciones de acceso a determinados trámites.
- Actualización de su propia contraseña.

#### 8.1.5 Sistemas externos

El proyecto dependerá inicialmente de sistemas institucionales externos para determinadas operaciones:

- **Servicios en Línea de la DIyS:** autenticación y obtención de información académica del alumno.
- **Sistema institucional de Finanzas:** generación y validación de referencias o formatos de pago.
- **Servicios institucionales de correo o notificaciones:** envío de avisos relacionados con el estado de los trámites, sujeto a la infraestructura disponible.

Estos sistemas no forman parte del desarrollo directo del módulo, por lo que su disponibilidad, interfaces y modificaciones estarán sujetas a sus respectivas áreas responsables.

#### 8.1.6 Módulo inicial

El primer módulo contemplado dentro del proyecto será:

- **Módulo de emisión de constancias de estudio.**

Este módulo servirá como primer incremento de la plataforma y como base para posteriormente incorporar otros procesos de la Dirección.

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
- Para el primer incremento se utilizarán HTML, CSS, JavaScript y PHP 7.2.9, Git y Microsoft SQL Server.
- Bootstrap podrá utilizarse como herramienta complementaria para la construcción de la interfaz.
- No se contempla inicialmente la adopción de tecnologías o frameworks que no hayan sido autorizados por la DIyS.
- La infraestructura de servidores, bases de datos y servicios institucionales deberá ajustarse a las capacidades disponibles en el momento dentro de la Universidad.

### 10.2 Restricciones de integración <!-- con las áreas involucradas (pendiente de definir el título) -->

- El módulo dependerá de la disponibilidad e infraestructura de datos proporcionadas por los sistemas institucionales de la DIyS.
- La autenticación del alumnado se realizará mediante la integración con “Servicios en Línea”, por lo que el sistema no administrará directamente las credenciales de los alumnos.
- La información académica utilizada por el sistema dependerá de los datos existentes y que serán proporcionados por los sistemas institucionales.
- La generación y validación de pagos dependerá de la infraestructura y servicios proporcionados por la Coordinación de Administración y Finanzas.
- Los cambios en sistemas externos podrán requerir modificaciones en el módulo y deberán coordinarse con las áreas responsables.

### 10.3 Restricciones institucionales

<!-- - El sistema deberá cumplir con las políticas, lineamientos y mecanismos de seguridad establecidos por la UAEH. -->

- Los accesos a los sistemas estarán restringidos al personal de la UAEH autorizado.
- La información académica y administrativa deberá manejarse de acuerdo con las disposiciones institucionales aplicables.
- Las modificaciones que involucren sistemas administrados por otras áreas estarán sujetas a autorización y coordinación con dichas áreas.

### 10.4 Restricciones de alcance

- El alcance del primer incremento estará limitado a las funcionalidades y procesos definidos para el módulo de emisión de constancias de estudio.
- No se contempla la migración completa de todos los procesos y aplicaciones existentes de la DAE durante este incremento.
- La incorporación de nuevos trámites, formatos o reportes requerirá una definición y priorización posterior.

### 10.5 Restricciones operativas

- La automatización estará limitada por las reglas y excepciones definidas por el área responsable.
- Los procesos que requieran firma autógrafa o que requieran una atención especial continuarán dependiendo de actividades presenciales o de la intervención del personal.
- Durante la etapa inicial podrán coexistir procedimientos digitales y procedimientos tradicionales mientras se completa la transición.

---

## 11. Criterios generales de éxito

El proyecto se considerará exitoso en su primer incremento cuando el módulo de emisión de constancias permita digitalizar y centralizar de manera funcional el proceso definido, cumpliendo como mínimo con los siguientes criterios:

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

- El módulo deberá cumplir con los requerimientos funcionales definidos por el área de Constancias y Certificados y el área de Informática.
- Las funciones críticas deberán probarse antes de la puesta en producción.
- No deberán existir errores conocidos de severidad crítica que impidan realizar solicitudes, procesarlas o emitir las constancias.
- La interfaz deberá ser utilizable desde computadoras y dispositivos móviles.

### 11.6 Adopción

- El personal responsable deberá poder realizar las actividades necesarias sin depender constantemente del área de Informática.
- El alumnado deberá poder completar una solicitud sin requerir asistencia del personal en los casos considerados como flujo normal.
- La puesta en operación deberá acompañarse de instrucciones o material de apoyo cuando sea necesario.

### 11.7 Evolución

- La arquitectura y estructura del módulo deberán permitir la incorporación posterior de nuevos procesos de la DAE sin tener que desarrollar una aplicación completamente independiente.
- El primer módulo deberá establecer patrones reutilizables para autenticación, usuarios, roles, estados, notificaciones, archivos e integración con servicios institucionales.
