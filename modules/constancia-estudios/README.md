# Registro y configuracion de ciclos

La funcionalidad usa `data/vta_siae_ciclo.json` y `data/vta_siae_plan.json` como catalogos simulados de SIAE. Las configuraciones propias se separan en `data/ae_ciclo.json` (GENERAL) y `data/ae_Ciclo_Fecha_Plan.json` (ESPECIFICA).

## Migracion a SQL Server

La logica de negocio esta concentrada en `classes/clsConstanciaCiclo.php`. Para migrar a SQL Server se puede conservar el controlador, la vista y el contrato de `models/modConstanciaCiclo.php`, reemplazando los metodos privados de lectura, escritura y busqueda por consultas parametrizadas mediante la conexion institucional:

- `leer` y `escribir` se sustituyen por `SELECT`, `INSERT` y `UPDATE` sobre `ae_ciclo` y `ae_Ciclo_Fecha_Plan`.
- `consultarCiclos` y `consultarPlanes` pasan a consultar vistas o tablas de SIAE.
- `siguienteId` se sustituye por identidad/secuencia de SQL Server.
- Las validaciones permanecen en la clase para conservar el comportamiento mientras la base de datos agrega sus restricciones UNIQUE y FOREIGN KEY.

Los datos de fecha usan formato ISO `YYYY-MM-DD`, compatible con `date` del formulario y con `date` de SQL Server.
