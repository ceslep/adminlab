export function getEstadoVariant(estado: string): 'indigo' | 'emerald' | 'violet' | 'amber' | 'rose' | 'slate' | 'blue' | 'green' | 'purple' | 'yellow' | 'red' | 'gray' {
        switch (estado?.toLowerCase()) {
            case 'realizado':
            case 'completado':
                return 'green';
            case 'proceso':
            case 'pendiente':
                return 'amber';
            case 'cancelado':
                return 'rose';  // Usar 'rose' en lugar de 'red'
            default:
                return 'slate';  // Usar 'slate' en lugar de 'gray'
        }
    }