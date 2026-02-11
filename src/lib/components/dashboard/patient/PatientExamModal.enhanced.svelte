<script lang="ts">
    import Button from '../shared/Button.svelte';
    import Badge from '../shared/Badge.svelte';
    import Avatar from '../shared/Avatar.svelte';
    import LoadingSpinner from '../shared/LoadingSpinner.svelte';
    import { onMount } from 'svelte';
    import { getEstadoVariant } from './getEstadoVariant';
    import { API_BASE_URL, EXAMENES_PACIENTE_ENDPOINT, RESULTADOS_EXAMEN_ENDPOINT } from '$lib/constants';
    
    export let paciente: any;
    export let show: boolean = false;
    export let onClose: () => void;
    export let selectedDate: string = '';
    
    let loading = false;
    let examenesDetallados: any[] = [];
    let error: string = '';
    let mostrarResultados = false;
    let examenSeleccionado: any = null;
    let resultadosExamen: any = null;
    let loadingResultados = false;
    
    function getDefaultDate(): string {
        return new Date().toISOString().split('T')[0];
    }
    
    // Cargar exámenes cuando se abre el modal
    $: if (show && paciente?.identificacion) {
        cargarExamenesPaciente();
    }
    
    async function cargarExamenesPaciente() {
        if (!paciente?.identificacion) {
            examenesDetallados = [];
            return;
        }
        
        loading = true;
        error = '';
        
        try {
            // Llamar al API para obtener los exámenes del paciente
            const response = await fetch(`${API_BASE_URL}${EXAMENES_PACIENTE_ENDPOINT}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    identificacion: paciente.identificacion,
                    fecha: selectedDate || getDefaultDate()
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.examenes) {
                examenesDetallados = data.examenes;
                console.log('📋 Exámenes cargados:', examenesDetallados);
            } else {
                // Si no hay API específico, usar los datos del paciente
                examenesDetallados = paciente.examenes_codigos?.map((codigo: string, index: number) => ({
                    codigo: codigo,
                    nombre: paciente.examenes[index] || `Examen ${codigo}`,
                fecha: selectedDate || getDefaultDate(),
                    estado: 'Pendiente',
                    realizado: false,
                    entidad: paciente.entidad
                })) || [];
            }
            
        } catch (err) {
            console.error('Error cargando exámenes:', err);
            // Fallback a datos del paciente
            examenesDetallados = paciente.examenes_codigos?.map((codigo: string, index: number) => ({
                codigo: codigo,
                nombre: paciente.examenes[index] || `Examen ${codigo}`,
                fecha: selectedDate || new Date().toISOString().split('T')[0],
                estado: 'Pendiente',
                realizado: false,
                entidad: paciente.entidad
            })) || [];
            
        } finally {
            loading = false;
        }
    }
    
    async function verResultados(examen: any) {
        examenSeleccionado = examen;
        mostrarResultados = true;
        loadingResultados = true;
        resultadosExamen = null;
        
        try {
            // Intentar obtener resultados del examen
            const response = await fetch(`${API_BASE_URL}${RESULTADOS_EXAMEN_ENDPOINT}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    identificacion: paciente.identificacion,
                    codexamen: examen.codigo,
                    fecha: examen.fecha
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    resultadosExamen = data.resultados;
                }
            }
        } catch (err) {
            console.error('Error cargando resultados:', err);
        } finally {
            loadingResultados = false;
        }
    }
    
    function volverALista() {
        mostrarResultados = false;
        examenSeleccionado = null;
        resultadosExamen = null;
    }
    
    function handleOverlayClick(event: MouseEvent) {
        if (event.target === event.currentTarget) {
            onClose();
        }
    }
    
    function handleKeydown(event: KeyboardEvent) {
        if (event.key === 'Escape') {
            onClose();
        }
    }
    

    

    
    function formatearFecha(fecha: string): string {
        if (!fecha) return 'No definida';
        const date = new Date(fecha);
        return date.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }
</script>

{#if show}
    <div class="modal-overlay" role="dialog" aria-modal="true" tabindex="-1" on:click={handleOverlayClick} on:keydown={handleKeydown}>
        <div class="modal-container">
            <!-- Header del modal -->
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
            
            <!-- Contenido principal -->
            <div class="modal-content">
                {#if mostrarResultados && examenSeleccionado}
                    <!-- Vista de resultados -->
                    <div class="resultados-view">
                        <div class="resultados-header">
                            <Button variant="secondary" size="sm" icon="←" onClick={volverALista}>
                                Volver a lista
                            </Button>
                            <div class="examen-titulo">
                                <h4>{examenSeleccionado.nombre}</h4>
                                <Badge text={`Código: ${examenSeleccionado.codigo}`} variant="blue" size="sm" />
                            </div>
                        </div>
                        
                        {#if loadingResultados}
                            <div class="loading-container">
                                <LoadingSpinner text="Cargando resultados..." />
                            </div>
                        {:else if resultadosExamen}
                            <div class="resultados-content">
                                <h5>Resultados del examen:</h5>
                                <div class="resultados-grid">
                                    {#each Object.entries(resultadosExamen) as [key, valor]}
                                        <div class="resultado-item">
                                            <span class="resultado-label">{key}:</span>
                                            <span class="resultado-valor">{valor || 'N/A'}</span>
                                        </div>
                                    {/each}
                                </div>
                            </div>
                        {:else}
                            <div class="empty-resultados">
                                <p>Este examen aún no tiene resultados disponibles.</p>
                                <Badge text="Pendiente" variant="yellow" size="sm" />
                            </div>
                        {/if}
                    </div>
                {:else}
                    <!-- Vista de lista de exámenes -->
                    <div class="examenes-view">
                        <div class="section-header">
                            <h4>Exámenes del paciente</h4>
                            <Badge text={`${examenesDetallados.length} exámenes`} variant="blue" size="sm" />
                        </div>
                        
                        {#if loading}
                            <div class="loading-container">
                                <LoadingSpinner text="Cargando detalles de exámenes..." />
                            </div>
                        {:else if error}
                            <div class="error-container">
                                <p class="error-text">{error}</p>
                                <Button variant="primary" size="sm" onClick={cargarExamenesPaciente}>
                                    Reintentar
                                </Button>
                            </div>
                        {:else if examenesDetallados.length > 0}
                            <div class="examenes-list">
                                {#each examenesDetallados as examen (examen.codigo)}
                                    <div class="examen-card">
                                        <div class="examen-header">
                                            <div class="examen-info">
                                                <h5>{examen.nombre}</h5>
                                                <p class="examen-meta">
                                                    <span class="examen-codigo">Código: {examen.codigo}</span>
                                                    <span class="examen-separator">•</span>
                                                    <span class="examen-fecha">Fecha: {formatearFecha(examen.fecha)}</span>
                                                    {#if examen.entidad}
                                                        <span class="examen-separator">•</span>
                                                        <span class="examen-entidad">{examen.entidad}</span>
                                                    {/if}
                                                </p>
                                            </div>
                                            <div class="examen-estado">
                                                <Badge 
                                                    text={examen.estado || 'Pendiente'} 
                                                    variant={getEstadoVariant(examen.estado)} 
                                                    size="sm"
                                                />
                                            </div>
                                        </div>
                                        
                                        <div class="examen-actions">
                                            <Button 
                                                variant="primary" 
                                                size="sm" 
                                                onClick={() => verResultados(examen)}
                                            >
                                                {examen.realizado ? 'Ver Resultados' : 'Ver Detalles'}
                                            </Button>
                                        </div>
                                    </div>
                                {/each}
                            </div>
                        {:else}
                            <div class="empty-state">
                                <div class="empty-icon">📋</div>
                                <h5>Sin exámenes registrados</h5>
                                <p>Este paciente no tiene exámenes asignados para la fecha seleccionada.</p>
                            </div>
                        {/if}
                    </div>
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
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 1rem;
        backdrop-filter: blur(2px);
    }
    
    .modal-container {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-width: 700px;
        width: 100%;
        max-height: 90vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
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
        padding: 1.25rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .examen-card:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 25px -8px rgba(59, 130, 246, 0.15);
        transform: translateY(-2px);
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
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .examen-meta {
        margin: 0.5rem 0 0 0;
        color: #6b7280;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .examen-codigo {
        font-weight: 600;
        color: #3b82f6;
    }
    
    .examen-separator {
        color: #d1d5db;
    }
    
    .examen-estado {
        flex-shrink: 0;
    }
    
    .examen-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.6;
    }
    
    .empty-state h5 {
        margin: 0 0 0.5rem 0;
        color: #374151;
        font-size: 1.125rem;
        font-weight: 600;
    }
    
    .empty-state p {
        margin: 0;
        color: #9ca3af;
    }
    
    /* Vista de resultados */
    .resultados-view {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .resultados-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .examen-titulo {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .examen-titulo h4 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: #1f2937;
    }
    
    .resultados-content {
        flex: 1;
    }
    
    .resultados-content h5 {
        margin: 0 0 1rem 0;
        color: #1f2937;
        font-weight: 600;
    }
    
    .resultados-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
    
    .resultado-item {
        padding: 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        background: #f9fafb;
    }
    
    .resultado-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
    }
    
    .resultado-valor {
        display: block;
        color: #6b7280;
        word-break: break-word;
    }
    
    .empty-resultados {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }
    
    .empty-resultados p {
        margin: 0 0 1rem 0;
        font-size: 1.125rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .modal-overlay {
            padding: 0.5rem;
        }
        
        .modal-container {
            max-width: 100%;
            border-radius: 0.5rem;
        }
        
        .modal-header, .modal-content {
            padding: 1rem;
        }
        
        .examen-header {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .examen-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }
        
        .resultados-header {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }
        
        .resultados-grid {
            grid-template-columns: 1fr;
        }
    }
</style>