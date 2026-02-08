# Componente PatientExamModal Enhanced

## 📋 Descripción
Componente modal mejorado para visualizar los exámenes de un paciente con capacidad de ver resultados detallados.

## 🚀 Características

### 1. **Vista Dual**
- **Lista de exámenes**: Muestra todos los exámenes del paciente para una fecha específica
- **Vista de resultados**: Muestra los resultados detallados de un examen específico

### 2. **Integración con API**
- Llama a `examenes_paciente.php` para obtener la lista de exámenes
- Llama a `resultados_examen.php` para obtener resultados específicos
- Fallback a datos del paciente si el API no está disponible

### 3. **Visualización Rica**
- **Badges de estado**: Colores diferentes según el estado del examen
- **Información completa**: Código, nombre, fecha, entidad
- **Resultados formateados**: Grid con los resultados del examen

## 📦 Props

```typescript
interface Props {
    paciente: any;           // Objeto paciente completo
    show: boolean;           // Controla la visibilidad del modal
    onClose: () => void;    // Función para cerrar el modal
    selectedDate: string;     // Fecha seleccionada para filtrar exámenes
}
```

## 🎯 Uso Básico

```svelte
<script>
    import PatientExamModal from './PatientExamModal.enhanced.svelte';
    
    let showExamModal = false;
    let selectedPatient = null;
    let selectedDate = '2024-01-15';
    
    function handleViewExams(paciente) {
        selectedPatient = paciente;
        showExamModal = true;
    }
</script>

{#if showExamModal}
    <PatientExamModal 
        paciente={selectedPatient}
        show={showExamModal}
        selectedDate={selectedDate}
        onClose={() => showExamModal = false}
    />
{/if}
```

## 🔧 Endpoints API Requeridos

### 1. examenes_paciente.php
```php
// POST Parameters:
// - identificacion: string (ID del paciente)
// - fecha: string (YYYY-MM-DD)

// Response:
{
    "success": true,
    "examenes": [
        {
            "codigo": "1",
            "nombre": "Hemograma",
            "fecha": "2024-01-15",
            "estado": "Realizado",
            "realizado": true,
            "entidad": "SURA",
            "tipo": "Sanguíneo",
            "tabla": "examen_tipo_5"
        }
    ],
    "total": 1
}
```

### 2. resultados_examen.php
```php
// POST Parameters:
// - identificacion: string (ID del paciente)
// - codexamen: string (Código del examen)
// - fecha: string (YYYY-MM-DD)

// Response:
{
    "success": true,
    "resultados": {
        "Hemoglobina": "14.5 g/dL",
        "Hematocrito": "42%",
        "Leucocitos": "7,500/mm³"
    },
    "tabla": "examen_tipo_5"
}
```

## 🎨 Estados Visuales

### Badges de Estado
- 🟢 **Verde**: Realizado/Completado
- 🟡 **Amarillo**: Pendiente/En proceso
- 🔴 **Rojo**: Cancelado/Error
- ⚪ **Gris**: Sin estado definido

### Animaciones
- **Slide-in**: El modal aparece con animación suave
- **Hover effects**: Las tarjetas tienen efectos al pasar el mouse
- **Loading states**: Indicadores de carga con spinner

## 📱 Responsive Design

- **Desktop**: Grid de 2 columnas para resultados
- **Mobile**: Una sola columna, headers apilados
- **Tablet**: Layout adaptable con espacios óptimos

## 🔄 Flujo de Navegación

1. **Abre modal**: Click en "Exámenes" en tarjeta de paciente
2. **Lista de exámenes**: Muestra todos los exámenes para la fecha
3. **Ver detalles**: Click en "Ver Detalles" o "Ver Resultados"
4. **Vista de resultados**: Muestra resultados específicos del examen
5. **Volver**: Click en "Volver a lista" regresa a la lista

## 🎯 Interacciones del Usuario

### Lista de Exámenes
- ✅ **Click en examen**: Navega a vista de resultados
- ✅ **Hover**: Efecto visual con sombra y elevación
- ✅ **Badge estado**: Color codificado para fácil identificación

### Vista de Resultados
- ✅ **Grid de resultados**: Diseño en tabla para fácil lectura
- ✅ **Navegación**: Botón para volver a la lista
- ✅ **Empty state**: Mensaje si no hay resultados

## 🛠️ Personalización

### Tema y Colores
Los colores están definidos con CSS variables y pueden ser modificados:

```css
:root {
    --modal-overlay-bg: rgba(0, 0, 0, 0.6);
    --modal-bg: white;
    --primary-color: #3b82f6;
    --success-color: #10b981;
    --warning-color: #f59e0b;
}
```

### Tamaños y Espaciado
Todos los valores usan unidades relativas (rem) para consistencia.

## 🔍 Debugging

El componente incluye logs detallados en la consola:
- `📋 Abriendo modal de exámenes para: [nombre]`
- `📋 Exámenes cargados: [array]`
- `🔍 Error cargando exámenes: [error]`

## 📋 Checklist de Implementación

- [x] Componente modal con animaciones
- [x] Integración con API de exámenes
- [x] Vista dual (lista/resultados)
- [x] Estados de carga y error
- [x] Diseño responsivo
- [x] Accesibilidad (ARIA, keyboard)
- [x] Manejo de eventos
- [x] Fallback graceful degradation

## 🚀 Mejoras Futuras

1. **Filtros avanzados**: Por tipo de examen, estado, rango de fechas
2. **Exportación**: Descargar resultados en PDF/Excel
3. **Historial**: Ver exámenes históricos del paciente
4. **Notificaciones**: Alertas cuando resultados estén listos
5. **Comparación**: Comparar resultados entre fechas