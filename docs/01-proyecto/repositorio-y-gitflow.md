<!-- TODO: Falta definir si se usará GitHub u otro servidor de host para el repositorio. -->
# Repositorio y GitFlow

## Objetivo

Establecer un flujo de control de versiones que permita al equipo trabajar simultáneamente sin afectar la estabilidad del proyecto.

---

## 1. Repositorio

### Plataforma

Se utilizará Github como plataforma para trabajar con el repositorio de manera remota y gestionar versiones, cambios y ramas.

### Repositorio principal

El repositorio principal es:

```text
dae-uaeh
```

---

## 2. Estructura de ramas

Se utilizará una versión simplificada de GitFlow para gestionar la estructura de ramas.

### Rama principal

```text
main
```

- Contiene únicamente versiones estables.
- Nadie desarrolla directamente aquí.

---

### Rama de integración

```text
develop
```

- Aquí se integran todas las funcionalidades terminadas.

---

### Ramas de funcionalidades

Formato:

```text
feature/nombre-funcionalidad
```

Ejemplos:

```text
feature/login
feature/roles
feature/usuarios
feature/constancias
feature/dashboard
feature/validacion-constancias
```

---

### Ramas de correcciones

Formato:

```text
fix/nombre-correccion
```

Ejemplos:

```text
fix/login-session
fix/password-validation
fix/pdf-generation
```

---

### Ramas de versiones candidatas

Formato:

```text
release/x.y.z
```

Ejemplos:

```text
release/1.0.0
release/1.1.0
```

---

### Ramas de emergencias

Formato:

```text
hotfix/x.y.z
```

Ejemplos:

```text
hotfix/1.0.1
hotfix/1.0.2
```

---

## 3. Flujo de trabajo

### Desarrollo normal

```text
develop
    ↓
feature/login
    ↓
Desarrollo
    ↓
Commit
    ↓
Push
    ↓
Pull Request
    ↓
Revisión
    ↓
Merge a develop
```

---

### Liberación

```text
develop
    ↓
release/1.0.0
    ↓
Pruebas
    ↓
main
```

---

### Corrección urgente

```text
main
    ↓
hotfix/1.0.1
    ↓
Corrección
    ↓
main
    ↓
develop
```

---

## 4. Protección de ramas

Configurar en GitHub:

### main

- Require Pull Request
- Require review
- Block force push
- Block direct push

---

### develop

- Require Pull Request
- Block force push

---

## 5. Estrategia de Pull Request

Todo cambio debe pasar por PR.

Nadie hace:

```bash
git push origin main
```

o

```bash
git push origin develop
```

directamente.

Siempre:

```text
feature/*
    ↓
Pull Request
    ↓
Revisión
    ↓
Merge
```

---

## 6. Convención de commits

Utilizaremos Conventional Commits simplificado.

### Nuevas funcionalidades

```text
feat: agregar inicio de sesion
```

---

### Correcciones

```text
fix: corregir validacion de contraseña
```

---

### Refactorización

```text
refactor: separar logica de autenticacion
```

---

### Documentación

```text
docs: actualizar guia de instalacion
```

---

### Estilos

```text
style: ajustar espaciado del formulario
```

---

### Pruebas

```text
test: agregar pruebas de login
```

---

### Configuración

```text
chore: actualizar gitignore
```

---

## 7. Estrategia de Merge

Recomiendo:

### Squash and Merge

Ventajas:

- Historial limpio.
- Un commit por funcionalidad.
- Más fácil de auditar.

Ejemplo:

```text
feature/login
├── commit 1
├── commit 2
├── commit 3
└── commit 4

↓

develop

feat: implementar modulo de login
```

---

## 8. Versionado

Usaremos Semantic Versioning.

Formato:

```text
MAJOR.MINOR.PATCH
```

Ejemplos:

```text
1.0.0
1.1.0
1.1.1
2.0.0
```

---

### MAJOR

Cambios incompatibles.

```text
1.0.0 → 2.0.0
```

---

### MINOR

Nueva funcionalidad compatible.

```text
1.0.0 → 1.1.0
```

---

### PATCH

Correcciones.

```text
1.1.0 → 1.1.1
```

---

## 9. Archivo .gitignore

Debe ignorar al menos:

```gitignore
/vendor/
/node_modules/

.env
.env.local

/logs/
/tmp/

/.idea/
/.vscode/

Thumbs.db
.DS_Store
```

Posteriormente podremos adaptarlo a PHP 7.2 y a la estructura MVC que definamos.

---

## 10. Roles dentro de Git

### Full Stack

- Crear ramas feature.
- Realizar PR.
- Revisar código.

---

### BD

- Scripts SQL versionados.
- Revisar cambios de esquema.

---

### Servidores

- Configuración de despliegue.
- Releases.

---

### Diseñador

- Recursos visuales.
- Mockups.
- Assets.

---
