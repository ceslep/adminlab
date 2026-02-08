<script lang="ts">
    import Avatar from '../shared/Avatar.svelte';
    import Badge from '../shared/Badge.svelte';
    import Button from '../shared/Button.svelte';
    import { createEventDispatcher } from 'svelte';
    
    export let paciente: any;
    export let onViewExams: ((event: CustomEvent) => void) | undefined = undefined;
    export let onViewDetails: ((event: CustomEvent) => void) | undefined = undefined;
    
    const dispatch = createEventDispatcher();
    
    function handleViewDetails() {
        if (onViewDetails) {
            onViewDetails(new CustomEvent('viewDetails', { detail: { paciente } }));
        } else {
            dispatch('viewDetails', { paciente });
        }
    }
    
    function handleViewExams() {
        console.log('🧪 handleViewExams llamado en PatientCard para:', paciente.nombre_completo);
        console.log('📋 onViewExams prop existe:', !!onViewExams);
        
        try {
            if (onViewExams) {
                console.log('📤 Enviando evento vía onViewExams prop');
                const event = new CustomEvent('viewExams', { detail: { paciente } });
                onViewExams(event);
            } else {
                console.log('📤 Enviando evento vía dispatch');
                dispatch('viewExams', { paciente });
            }
        } catch (error) {
            console.error('❌ Error en handleViewExams:', error);
        }
    }
</script>

<div class="patient-card">
    <!-- Header con avatar -->
    <div class="patient-header">
        <Avatar name={paciente.nombre_completo} size="md" />
        <div class="patient-info">
            <h4 class="patient-name">{paciente.nombre_completo}</h4>
            <p class="patient-contact">{paciente.telefono}</p>
        </div>
    </div>

    <!-- Badges -->
    <div class="patient-badges">
        <Badge text={paciente.genero} variant="blue" size="sm" />
        <Badge text={paciente.estado} variant="green" size="sm" />
        <Badge text={paciente.entidad} variant="purple" size="sm" />
        <Badge text={`${paciente.total_examenes} exámenes`} variant="yellow" size="sm" />
    </div>

    <!-- Información adicional -->
    <div class="patient-details">
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">ID:</span>
                <span class="detail-value">{paciente.identificacion}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{paciente.email}</span>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="patient-actions">
        <Button 
            variant="primary" 
            size="sm" 
            fullWidth 
            icon="👁"
            onClick={handleViewDetails}
        >
            Ver Detalles
        </Button>
        <Button 
            variant="success" 
            size="sm" 
            fullWidth 
            icon="🧪"
            onClick={handleViewExams}
        >
            Exámenes
        </Button>
    </div>
</div>

<style>
    .patient-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }
    
    .patient-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    .patient-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .patient-info {
        margin-left: 1rem;
        flex: 1;
    }
    
    .patient-name {
        margin: 0;
        font-size: 1.1rem;
        color: #1f2937;
        font-weight: 600;
    }
    
    .patient-contact {
        margin: 0.25rem 0;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .patient-badges {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .patient-details {
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 1rem;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
    }
    
    .detail-label {
        font-weight: 600;
        font-size: 0.75rem;
        color: #6b7280;
    }
    
    .detail-value {
        word-break: break-word;
    }
    
    .patient-actions {
        display: flex;
        gap: 0.5rem;
    }
</style>