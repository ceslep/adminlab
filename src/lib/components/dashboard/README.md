# Dashboard de Pacientes - Arquitectura Modular

## Estructura de Componentes

El dashboard de pacientes ha sido refactorizado en componentes independientes y reutilizables para mejorar la mantenibilidad y escalabilidad.

## Jerarquía de Componentes

```
Dashboard.svelte (principal)
└── Dashboard.new.svelte (lógica principal)
    ├── DashboardView.svelte (vista principal)
    │   ├── DashboardHeader.svelte
    │   └── FeatureCard.svelte (x3)
    └── PatientView.svelte (vista de pacientes)
        ├── DashboardHeader.svelte
        ├── PatientFilters.svelte
        └── PatientList.svelte
            └── PatientCard.svelte (por cada paciente)
```

## Componentes Compartidos

### `shared/Avatar.svelte`
- Propósito: Muestra avatar con iniciales
- Props: `name`, `size` ('sm'|'md'|'lg'), `customSize`
- Uso: Perfiles de pacientes, usuarios

### `shared/Badge.svelte`
- Propósito: Etiquetas de estado/información
- Props: `text`, `variant`, `size`
- Variantes: blue, green, purple, yellow, red, gray

### `shared/Button.svelte`
- Propósito: Botones estandarizados
- Props: `variant`, `size`, `disabled`, `loading`, `icon`, `fullWidth`, `onClick`
- Variantes: primary, secondary, success, danger

### `shared/LoadingSpinner.svelte`
- Propósito: Indicador de carga
- Props: `text`, `size`, `showText`

### `shared/EmptyState.svelte`
- Propósito: Estado vacío con opción de acción
- Props: `icon`, `title`, `message`, `action`, `actionText`, `showAction`

## Componentes del Dashboard

### `DashboardHeader.svelte`
- Propósito: Cabecera reutilizable
- Props: `title`, `subtitle`, `showBackButton`, `onBack`, `onLogout`, `userName`

### `DashboardView.svelte`
- Propósito: Vista principal del dashboard
- Props: `userName`, `onLogout`, `onShowPatients`

### `FeatureCard.svelte`
- Propósito: Tarjeta de funcionalidad
- Props: `icon`, `title`, `description`, `buttonText`, `onClick`, `disabled`, `variant`

## Componentes de Pacientes

### `PatientView.svelte`
- Propósito: Vista completa de gestión de pacientes
- Props: `searchQuery`, `selectedDate`, `pacientes`, `loading`, `onBack`, `onLogout`, `onSearch`, `onDateChange`, `onRefresh`

### `PatientFilters.svelte`
- Propósito: Filtros de búsqueda y fecha
- Props: `searchQuery`, `selectedDate`, `totalPatients`, `onSearch`, `onDateChange`, `onRefresh`, `loading`

### `PatientList.svelte`
- Propósito: Lista/grid de pacientes
- Props: `pacientes`, `loading`, `searchQuery`

### `PatientCard.svelte`
- Propósito: Tarjeta individual de paciente
- Props: `paciente` (objeto completo)

## Flujo de Datos

1. **Dashboard.svelte**: Componente raíz, maneja estado global
2. **Dashboard.new.svelte**: Lógica principal y estado compartido
3. **Vistas**: Reciben props y emiten eventos al padre
4. **Componentes**: Son "dumb" - solo renderizan datos recibidos

## Estado Global

El estado principal se mantiene en `Dashboard.new.svelte`:
- `showPatientsView`: Controla la vista activa
- `pacientes`: Array de pacientes cargados
- `loading`: Estado de carga
- `searchQuery`: Texto de búsqueda
- `selectedDate`: Fecha seleccionada

## Eventos y Comunicación

Los componentes utilizan:
- **Props** para datos descendentes
- **Eventos personalizados** (`on:event`) para comunicación ascendente
- **CustomEvent** para componentes más profundos (ej: PatientList → Dashboard)

## Ventajas de esta Arquitectura

1. **Reutilización**: Componentes compartidos en múltiples lugares
2. **Mantenibilidad**: Cada componente tiene una única responsabilidad
3. **Testeabilidad**: Componentes pequeños son más fáciles de probar
4. **Escalabilidad**: Fácil agregar nuevas funcionalidades
5. **Consistencia**: UI consistente en toda la aplicación

## Importación

Para facilitar las importaciones, existe un archivo `index.ts` que exporta todos los componentes:

```typescript
import { 
    Avatar, 
    Badge, 
    Button, 
    PatientCard, 
    DashboardHeader 
} from './dashboard';
```

## Estilos

- Cada componente incluye sus propios estilos scoped
- Uso de Tailwind CSS para clases utilitarias
- Diseño responsivo integrado
- Transiciones y efectos hover consistentes

## TypeScript

- Todos los componentes están tipados
- Interfaces bien definidas para props
- Mejor autocompletado y detección de errores