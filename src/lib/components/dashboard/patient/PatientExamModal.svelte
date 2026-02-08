<script lang="ts">
    import Button from '../shared/Button.svelte';
    import Badge from '../shared/Badge.svelte';
    import Avatar from '../shared/Avatar.svelte';
    import LoadingSpinner from '../shared/LoadingSpinner.svelte';
    
    export let paciente: any;
    export let show: boolean = false;
    export let onClose: () => void;
    
    let loading = false;
    let examenesDetallados: any[] = [];
    let error: string = '';
    
    $: if (show && paciente?.id_paciente) {
        cargarExamenesDetallados();
    }
    
    async function cargarExamenesDetallados() {
        if (!paciente?.identificacion || !paciente?.examenes_codigos) {
            examenesDetallados = [];
            return;
        }
        
        loading = true;
        error = '';
        
        try {
            // Aquí haríamos una llamada al API para obtener los detalles de cada examen
            // Por ahora, usaremos los datos que ya tenemos
            
            examenesDetallados = paciente.examenes_codigos.map((codigo: string, index: number) => ({
                codigo: codigo,
                nombre: paciente.examenes[index] || `Examen ${codigo}`,
                fecha: new Date().toISOString().split('T')[0], // Debería venir del API
                estado: 'Pendiente', // Debería venir del API
                realizado: false // Debería venir del API
            }));
            
        } catch (err) {
            error = 'Error al cargar los detalles de los exámenes';
            console.error('Error cargando exámenes:', err);
        } finally {
            loading = false;
        }
    }
    
    function handleOverlayClick(event: MouseEvent) {
        if (event.target === event.currentTarget) {
            onClose();
        }
    }
    
    function handleVerResultados(examen: any) {
        console.log('Ver resultados del examen:', examen);
        // Aquí podríamos navegar a una vista de resultados
    }
</script>

{#if show}
    <div class="modal-overlay" role="dialog" aria-modal="true" tabindex="-1" on:click={handleOverlayClick} on:keydown={(e) => { if (e.key === 'Escape') onClose(); }}>
        <div class="modal-container">
            <div class="modal-header">
                <div class="patient-info">
                    <Avatar name={paciente.nombre_completo} size="md" />
                    <div>
                        <h3>{paciente.nombre_completo}</h3>
                        <p class="patient-details">
                            ID: {paciente.identificacion} • {paciente.edad} años • {paciente.telefono}
                        </p>
                    </div>
                </div>
                <Button variant="secondary" size="sm" icon="✕" onClick={onClose} />
            </div>
            
            <div class="modal-content">
                <div class="section-header">
                    <h4>Exámenes del paciente</h4>
                    <Badge text={`${paciente.total_examenes} exámenes`} variant="blue" size="sm" />
                </div>
                
                {#if loading}
                    <div class="loading-container">
                        <LoadingSpinner text="Cargando detalles de exámenes..." />
                    </div>
                {:else if error}
                    <div class="error-container">
                        <p class="error-text">{error}</p>
                        <Button variant="primary" size="sm" onClick={cargarExamenesDetallados}>
                            Reintentar
                        </Button>
                    </div>
                {:else}
                    <div class="examenes-list">
                        {#each examenesDetallados as examen (examen.codigo)}
                            <div class="examen-card">
                                <div class="examen-header">
                                    <div class="examen-info">
                                        <h5>{examen.nombre}</h5>
                                        <p class="examen-meta">
                                            Código: {examen.codigo} • Fecha: {examen.fecha}
                                        </p>
                                    </div>
                                    <Badge 
                                        text={examen.estado} 
                                        variant={examen.realizado ? 'green' : 'yellow'} 
                                        size="sm"
                                    />
                                </div>
                                
                                <div class="examen-actions">
                                    <Button 
                                        variant="primary" 
                                        size="sm" 
                                        onClick={() => handleVerResultados(examen)}
                                    >
                                        {examen.realizado ? 'Ver Resultados' : 'Ver Detalles'}
                                    </Button>
                                </div>
                            </div>
                        {/each}
                    </div>
                    
                    {#if examenesDetallados.length === 0}
                        <div class="empty-state">
                            <p>Este paciente no tiene exámenes asignados.</p>
                        </div>
                    {/if}
                {/if}
            </div>
        </div>
    </div>
{/if}

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 1rem;
    }
    
    .modal-container {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .patient-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }
    
    .patient-info h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .patient-details {
        margin: 0.25rem 0 0 0;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .modal-content {
        padding: 1.5rem;
        overflow-y: auto;
        flex: 1;
    }
    
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    
    .section-header h4 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .loading-container, .error-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        text-align: center;
    }
    
    .error-text {
        color: #ef4444;
        margin-bottom: 1rem;
    }
    
    .examenes-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .examen-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem;
        transition: all 0.2s;
    }
    
    .examen-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .examen-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .examen-info h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .examen-meta {
        margin: 0.25rem 0 0 0;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .examen-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }
    
    @media (max-width: 640px) {
        .modal-overlay {
            padding: 0.5rem;
        }
        
        .modal-header {
            padding: 1rem;
        }
        
        .modal-content {
            padding: 1rem;
        }
        
        .examen-header {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .examen-actions {
            width: 100%;
        }
        

    }
</style>