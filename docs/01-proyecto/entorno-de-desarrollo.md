# S0-05 · Preparación del Entorno de Desarrollo

## 1. Objetivo

Definir, documentar y estandarizar el entorno de desarrollo que utilizará el equipo para garantizar consistencia durante la construcción del sistema de la Dirección de Administración Escolar.

---

## 2. Alcance

Incluye:

- Sistema operativo de desarrollo
- Servidor local
- PHP
- SQL Server
- Git
- GitHub
- Editor de código
- Navegadores para pruebas
- Configuración inicial del proyecto

No incluye:

- Servidores de producción
- Configuración de infraestructura institucional
- Despliegue final

---

## 3. Herramientas oficiales del proyecto

<!-- TODO: Definir versión de SQL Server -->
<!-- TODO: Definir en dondes se va a alojar el repositorio ya que GitHub dejó de ser opción por los costos. -->
| Herramienta | Versión                       |
| ----------- | ----------------------------- |
| PHP         | 7.2.9                         |
| Apache      | Incluido en XAMPP             |
| XAMPP       | Compatible con PHP 7.2.9      |
<!-- | SQL Server  | Definir versión institucional | -->
| Git         | Última estable                |
<!-- | GitHub      | Repositorio central           | -->
| Bootstrap   | Versión a definir             |
| JavaScript  | ES6 compatible                |
| HTML        | HTML5                         |
| CSS         | CSS3                          |

---

## 4. Entorno mínimo de cada desarrollador

Cada integrante deberá contar con:

### Software obligatorio

- Git
- XAMPP
- SQL Server Management Studio (SSMS)
- Navegador moderno
  - Chrome
  - Edge
- Editor de código

Recomendado:

- Visual Studio Code

---

## 5. Estructura local propuesta

```text
C:\
│
├── xampp\
│
└── htdocs\
    │
    └── dae-uaeh\
```

---

## 6. Configuración de Git

Verificar instalación:

```bash
git --version
```

Configurar usuario:

```bash
git config --global user.name "Nombre Apellido"
git config --global user.email "correo@ejemplo.com"
git config --global init.defaultBranch main
```

Verificar:

```bash
git config --list
```

---

<!-- ## 7. Configuración de GitHub

Cada integrante debe:

* Tener cuenta GitHub.
* Tener acceso al repositorio.
* Poder:

```bash
git clone
git pull
git push
```

--- -->

<!-- TODO: Definir configuración de DB con Danny -->
<!-- ## 8. Configuración de SQL Server

Aquí hay una pregunta importante que debemos resolver.

### ¿Cuál será la base de datos?

Puede ser:

* SQL Server Express
* SQL Server Developer
* SQL Server Standard
* SQL Server Enterprise

Para desarrollo recomiendo:

### SQL Server Developer

Porque:

* Tiene todas las funcionalidades.
* Es gratuita para desarrollo.
* Facilita pruebas más cercanas al entorno real.

---

## Configuración mínima

Autenticación:

```text
SQL Server Authentication
```

o

```text
Windows Authentication
```

Esto debe definirse para todo el equipo.

---

## Convención inicial

Servidor local:

```text
localhost
```

Base de datos:

```text
DAE_DEV
```

--- -->

## 9. Conexión PHP + SQL Server

Este punto suele causar problemas.

PHP no puede conectarse a SQL Server únicamente con XAMPP.

Se requieren drivers:

### Microsoft Drivers for PHP for SQL Server

Extensiones:

```text
php_sqlsrv
php_pdo_sqlsrv
```

La versión debe ser compatible con:

```text
PHP 7.2
```

---

### Verificación

Crear:

```php
<?php
phpinfo();
```

Confirmar que aparezcan:

```text
sqlsrv
pdo_sqlsrv
```

---

## 10. Editor de código

Estandarizar:

### Visual Studio Code

Extensiones recomendadas:

```text
PHP Intelephense
Bootstrap IntelliSense
SQL Server
EditorConfig
Better Comments
```

---

## 11. Navegadores de prueba

Obligatorios:

- Chrome
- Edge

Mínimo:

```text
Última versión estable
```

---

## 12. Variables de configuración

No debemos subir configuraciones sensibles.

Ejemplo:

```text
config/
│
├── database.example.php
└── database.php
```

Subimos:

```text
database.example.php
```

Ignoramos:

```text
database.php
```

---

## 13. Archivo .gitignore inicial

Ejemplo:

```gitignore
/config/database.php

/vendor/

node_modules/

*.log

.DS_Store

Thumbs.db
```

Más adelante podremos ampliarlo.

---

## 14. Prueba de validación del entorno

Cada integrante deberá poder:

### Paso 1

Clonar:

```bash
git clone URL_DEL_REPOSITORIO
```

### Paso 2

Levantar Apache.

### Paso 3

Abrir:

```text
http://localhost/dae-system
```

### Paso 4

Conectarse a SQL Server.

### Paso 5

Ejecutar una consulta simple.

Ejemplo:

```sql
SELECT 1;
```

### Paso 6

Ver resultado desde PHP.

Si todos pueden hacerlo:

✅ Entorno validado.

---

## 15. Riesgos identificados

### Riesgo 1

Versiones diferentes de PHP.

Mitigación:

- Todos usarán PHP 7.2.9.

---

### Riesgo 2

Drivers SQL Server incompatibles.

Mitigación:

- Documentar instalación paso a paso.

---

### Riesgo 3

Configuraciones locales distintas.

Mitigación:

- Estandarizar estructura de carpetas.

---

### Riesgo 4

Dependencia de configuraciones personales.

Mitigación:

- Todo debe quedar documentado.

---

## Criterios de aceptación de S0-05

```text
[ ] Todos los integrantes tienen Git instalado.
[ ] Todos los integrantes tienen acceso a GitHub.
[ ] Todos los integrantes tienen XAMPP configurado.
[ ] Todos los integrantes tienen PHP 7.2.9.
[ ] Todos los integrantes tienen SQL Server instalado.
[ ] Todos los integrantes tienen SSMS instalado.
[ ] Los drivers SQL Server para PHP funcionan.
[ ] Todos pueden clonar el repositorio.
[ ] Todos pueden ejecutar el proyecto localmente.
[ ] Todos pueden conectarse a la base de datos.
[ ] Existe documentación de instalación.
```

---

### Observación importante

Antes de dar S0-05 por terminado, necesito saber algo que afectará bastante la arquitectura técnica inicial:

**¿La UAEH ya les indicó qué versión de SQL Server utilizan en sus servidores o bases institucionales (2016, 2017, 2019, 2022, etc.)?**

Ese dato nos ayudará a definir exactamente qué drivers, herramientas y configuraciones debe usar el equipo.
