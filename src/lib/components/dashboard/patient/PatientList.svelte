<script lang="ts">
    import PatientCard from './PatientCard.svelte';
    import LoadingSpinner from '../shared/LoadingSpinner.svelte';
    import EmptyState from '../shared/EmptyState.svelte';
    import { createEventDispatcher } from 'svelte';
    
export let pacientes: any[] = [];
    export let loading: boolean = false;
    export let searchQuery: string = '';
    export const onViewExams: ((event: CustomEvent) => void) | undefined = undefined;
    export const onViewDetails: ((event: CustomEvent) => void) | undefined = undefined;
    
    const dispatch = createEventDispatcher();
    
    function handleRetry() {
        // Este evento se manejará en el componente padre
        const event = new CustomEvent('retry');
        document.dispatchEvent(event);
    }
    
    function handleViewExams(event: CustomEvent) {
        console.log('📋 handleViewExams llamado en PatientList:', event.detail);
        try {
            dispatch('viewExams', event.detail);
        } catch (error) {
            console.error('❌ Error en handleViewExams de PatientList:', error);
        }
    }
    
    function handleViewDetails(event: CustomEvent) {
        dispatch('viewDetails', event.detail);
    }
</script>

<div class="patient-list">
    {#if loading}
        <div class="loading-container">
            <LoadingSpinner text="Cargando pacientes..." size="md" />
        </div>
    {:else if pacientes.length > 0}
        <div class="patients-grid">
            {#each pacientes as paciente (paciente.id_paciente)}
                <PatientCard 
                    {paciente} 
                    onViewExams={handleViewExams}
                    onViewDetails={handleViewDetails}
                />
            {/each}
        </div>
    {:else}
        <div class="empty-container">
            <EmptyState 
                icon="🔍"
                title={searchQuery ? "No se encontraron pacientes" : "No hay pacientes registrados"}
                message={searchQuery 
                    ? `No se encontraron resultados para "${searchQuery}"`
                    : "No hay pacientes con exámenes en la fecha seleccionada"
                }
                action={handleRetry}
                actionText="Reintentar"
                showAction={true}
            />
        </div>
    {/if}
</div>

<style>
    .patient-list {
        min-height: 500px;
        position: relative;
    }
    
    .loading-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 4rem;
    }
    
    .patients-grid {
        display: grid;
        gap: 2rem;
        grid-template-columns: 1fr;
    }
    
    @media (min-width: 640px) {
        .patients-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 1024px) {
        .patients-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
    }
    
    @media (min-width: 1280px) {
        .patients-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    .empty-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 400px;
    }

    /* Bento-style grid for patient cards */
    .patients-grid :global(.patient-card:nth-child(3n+1)) {
        grid-column: span 1;
    }

    .patients-grid :global(.patient-card:nth-child(3n+2)) {
        grid-column: span 1;
    }

    .patients-grid :global(.patient-card:nth-child(3n+3)) {
        grid-column: span 1;
    }

    /* Large screens - create varied card sizes */
    @media (min-width: 1536px) {
        .patients-grid :global(.patient-card:nth-child(4n+1)) {
            grid-row: span 1;
        }
        
        .patients-grid :global(.patient-card:nth-child(4n+2)) {
            grid-row: span 1;
        }
        
        .patients-grid :global(.patient-card:nth-child(4n+3)) {
            grid-row: span 1;
        }
        
        .patients-grid :global(.patient-card:nth-child(4n+4)) {
            grid-row: span 1;
        }
    }
</style>