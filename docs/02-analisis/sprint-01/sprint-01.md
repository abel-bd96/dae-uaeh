# S1-01 · Análisis de Registro y Configuración de Ciclos

## Objetivo

Definir el comportamiento funcional del proceso de registro y configuración de ciclos que serán utilizados por el módulo de emisión de constancias de estudio.

Este análisis servirá como base para el diseño funcional, diseño de base de datos e implementación del módulo.

---

## Descripción general

El sistema deberá permitir registrar ciclos para la emisión de constancias.

Un ciclo se compone de:

- Nombre del ciclo.
- Periodo del ciclo.
- Periodo vacacional.
- Periodo de solicitud.

Los ciclos serán almacenados en la base de datos de la DAE.

La información del ciclo no será sincronizada con el SIAE.

Únicamente se utilizará el SIAE para validar que el ciclo exista y para obtener el nombre correspondiente.

---

## Flujo general

## Registro de un nuevo ciclo

1. El usuario selecciona la opción para crear un nuevo ciclo.
2. El sistema permite buscar ciclos existentes en SIAE.
3. El usuario selecciona el ciclo deseado.
4. El sistema permite configurar las fechas correspondientes.
5. El usuario guarda la configuración.
6. El sistema registra la información en la base de datos de la DAE.

---

## Configuración general

La primera configuración registrada para un ciclo será considerada la configuración general.

Esta configuración aplicará a todos los programas educativos que no tengan una configuración específica.

Ejemplo:

- ENERO-JUNIO 2026

---

## Configuración específica

Si posteriormente se registra nuevamente el mismo ciclo, el usuario deberá indicar a qué programas educativos aplicará la nueva configuración.

Esta configuración permitirá manejar excepciones para programas educativos que cuenten con calendarios distintos al general.

Ejemplo:

- ENERO-JUNIO 2026
  - Licenciatura en Medicina

- ENERO-JUNIO 2026
  - Licenciatura en Enfermería

---

## Identificación visual

El sistema deberá permitir identificar visualmente:

- Configuraciones generales.
- Configuraciones específicas.

---

## Definición de ciclo

### Características

- Un ciclo representa un periodo académico.
- No necesariamente corresponde a un semestre.
- Puede representar periodos mensuales, semestrales, anuales u otros definidos institucionalmente.
- Puede haber múltiples ciclos activos simultáneamente.

---

## Información a almacenar

Cada configuración deberá almacenar:

- Nombre del ciclo.
- Fecha de inicio del ciclo.
- Fecha de fin del ciclo.
- Fecha de inicio del periodo vacacional.
- Fecha de fin del periodo vacacional.
- Fecha de inicio del periodo de solicitud.
- Fecha de fin del periodo de solicitud.
- Estado.

---

## Estados

### Estado configurable

- Activo
- Inactivo

El estado deberá ser administrado manualmente por el usuario.

---

## Indicadores calculados

Los siguientes indicadores serán calculados utilizando la fecha del servidor y tendrán únicamente una finalidad visual:

- Próximo
- Vigente
- Finalizado

Estos indicadores no sustituyen al estado Activo/Inactivo.

---

## Reglas de negocio

## RN-01

Puede existir más de un ciclo activo simultáneamente.

## RN-02

Los ciclos registrados no podrán eliminarse desde la aplicación.

## RN-03

Solo podrán registrarse ciclos existentes en SIAE.

## RN-04

La información del ciclo se almacenará en la base de datos de la DAE de forma independiente a SIAE.

## RN-05

La primera configuración registrada para un ciclo será asignada como la configuración general.

## RN-06

Toda configuración registrada con posterioridad a la general deberá asociarse a uno o más programas educativos (configuración específica).

## RN-07

Un programa educativo solo podrá estar asignado a una única configuración específica dentro del mismo ciclo.

## RN-08

Todo programa educativo que no pertenezca a una configuración específica adoptará automáticamente la configuración general del ciclo.

## RN-09

En un ciclo activo, las fechas de sus configuraciones se podrán modificar libremente hacia el pasado o hacia el futuro.

## RN-10

En un ciclo activo, se podrán agregar o desvincular programas educativos de una configuración específica en cualquier momento.

## RN-11

Las fechas configuradas en cualquier nivel deberán ser válidas y mantener coherencia cronológica.

---

## Dependencias

Actualmente se identifica la siguiente dependencia:

- Solicitud de constancias.

---

## Roles involucrados

Pendiente de definición.

---

## Casos especiales

No se han identificado casos especiales hasta el momento.

---

## Pendientes por definir

- Roles autorizados para registrar ciclos.
- Roles autorizados para modificar ciclos.
- Roles autorizados para inactivar ciclos.
- Restricciones adicionales solicitadas por el área usuaria.
- Casos extraordinarios de calendario.
- Comportamiento cuando existan múltiples configuraciones específicas para distintos programas educativos.

---

## Observaciones

Esta funcionalidad se implementará inicialmente para apoyar la configuración operativa del módulo de emisión de constancias y para validar procesos de trabajo, arquitectura y configuración general del sistema.

Las reglas y alcances podrán ampliarse en incrementos posteriores conforme se obtenga mayor información del área usuaria.
