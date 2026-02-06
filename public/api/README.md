# API REST - AdminLab

Esta carpeta contiene los endpoints PHP para el sistema AdminLab.

## 📁 Estructura

- **`index.php`** - Router principal que enruta solicitudes a los endpoints específicos
- **`config.php`** - Configuración global, CORS y funciones auxiliares
- **`auth.php`** - Autenticación (login, logout, verificación de sesión)
- **`configuracion.php`** - Obtener configuración del laboratorio
- **`entidades.php`** - Gestión de entidades (listar, actualizar)
- **`pacientes.php`** - Búsqueda y gestión de pacientes con sus exámenes
- **`examenes.php`** - Consulta de exámenes y reportes por entidades

## 🔧 Configuración

### Uso del Router

El router principal se encuentra en `index.php` y redirige las solicitudes según el parámetro `endpoint`:

```
GET /api/index.php?endpoint=auth&action=check
POST /api/index.php?endpoint=auth&action=login
POST /api/index.php?endpoint=pacientes&action=search
POST /api/index.php?endpoint=examenes&action=entity_report
```

### Acceso Directo a Endpoints

También puedes acceder directamente a los archivos específicos:

```
POST /api/auth.php?action=login
POST /api/pacientes.php?action=search
POST /api/examenes.php?action=entity_report
```

## 📋 Endpoints Disponibles

### Autenticación (`auth.php`)
- **`action=login`** - Iniciar sesión
  - Método: POST
  - Parámetros: `password`
  - Respuesta: Token de sesión y configuración del laboratorio

- **`action=logout`** - Cerrar sesión
  - Método: GET/POST
  - Respuesta: Confirmación de logout

- **`action=check`** - Verificar estado de autenticación
  - Método: GET
  - Respuesta: Estado actual de la sesión

### Configuración (`configuracion.php`)
- **`action=get`** - Obtener configuración del laboratorio
  - Método: GET
  - Respuesta: Nombre corto, nombre completo y logo del laboratorio

### Entidades (`entidades.php`)
- **`action=list`** - Listar todas las entidades
  - Método: GET
  - Respuesta: Array de entidades con id y nombre

- **`action=update`** - Actualizar entidad de un examen
  - Método: POST
  - Parámetros: `identificacion`, `fecha_examen`, `codexamen`, `entidad`
  - Respuesta: Confirmación de actualización

### Pacientes (`pacientes.php`)
- **`action=search`** - Buscar pacientes con filtros múltiples
  - Método: POST
  - Parámetros: `identificacion`, `nombres`, `telefono`, `ciudad`, `entidad`, `include_examenes`, `solo_con_resultados`, `limit`
  - Respuesta: Array de pacientes con sus exámenes (si se solicita)

- **`action=get_patient_exams`** - Obtener exámenes de un paciente específico
  - Método: POST
  - Parámetros: `identificacion`, `solo_con_resultados`
  - Respuesta: Array de exámenes del paciente

### Exámenes (`examenes.php`)
- **`action=search`** - Buscar exámenes por fecha o criterios
  - Método: GET
  - Parámetros: `fecha`, `identificacion`, `buscar`, `todos`
  - Respuesta: Array de exámenes filtrados

- **`action=entity_report`** - Generar reporte por entidades
  - Método: POST
  - Parámetros: `entidad`, `fecha_inicio`, `fecha_fin`, `solo_resultados`, `agrupar_fecha`
  - Respuesta: Reporte detallado con opción de agrupación por fecha

## 🔄 Formato de Respuestas

### Respuesta Exitosa
```json
{
    "success": true,
    "data": [...],
    "message": "Operación completada correctamente"
}
```

### Respuesta de Error
```json
{
    "success": false,
    "message": "Descripción del error"
}
```

## 🔐 Seguridad

- Todas las consultas usan sentencias preparadas para prevenir SQL injection
- CORS configurado para permitir acceso desde el frontend
- Validación de parámetros requeridos
- Manejo centralizado de excepciones

## 📊 Tablas de Base de Datos

Los endpoints interactúan con las siguientes tablas:

- **`configuracion`** - Configuración del laboratorio
- **`paciente`** - Datos demográficos de pacientes
- **`examenes`** - Registro de exámenes
- **`procedimientos`** - Catálogo de tipos de exámenes
- **`entidades`** - Lista de entidades/aseguradoras
- **Tablas dinámicas** - Resultados específicos por tipo de examen

## 🚀 Ejemplos de Uso

### Login
```javascript
fetch('/api/index.php?endpoint=auth&action=login', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({password: 'mi_password'})
})
```

### Búsqueda de Pacientes
```javascript
fetch('/api/index.php?endpoint=pacientes&action=search', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        nombres: 'Juan',
        include_examenes: '1',
        limit: 50
    })
})
```

### Reporte por Entidades
```javascript
fetch('/api/index.php?endpoint=examenes&action=entity_report', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({
        entidad: 'EPS001',
        fecha_inicio: '2024-01-01',
        fecha_fin: '2024-12-31',
        agrupar_fecha: '1'
    })
})
```

## 🛠️ Notas de Implementación

- Se requiere el archivo `datos_conexion.php` en el directorio padre
- Todas las funciones auxiliares están centralizadas en `config.php`
- Se manejan tablas dinámicas para resultados de exámenes
- Implementación optimizada para consultas complejas con JOINs múltiples