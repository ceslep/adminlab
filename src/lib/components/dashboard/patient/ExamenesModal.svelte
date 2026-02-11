<script lang="ts">
    import { onMount } from 'svelte';
    import { createEventDispatcher } from 'svelte';
    import { fade, fly, scale } from 'svelte/transition';
    import Modal from '../shared/Modal.svelte';
    import Badge from '../shared/Badge.svelte';
    import { Calendar, FileText, Activity, User, Phone, Mail, Clock, CheckCircle, AlertCircle, XCircle, Loader2, Search } from 'lucide-svelte';
    import { API_BASE_URL, EXAMENES_PACIENTE_ENDPOINT } from '$lib/constants';
    
    export let show: boolean = false;
    export let paciente: any = null;
    export let onClose: () => void = () => {};
    
    const dispatch = createEventDispatcher();
    
    function getDefaultDate(): string {
        return new Date().toISOString().split('T')[0];
    }
    
    let loading: boolean = false;
    let examenes: any[] = [];
    let searchTerm: string = '';
    let selectedTipoExamen: string = '';
    let sortBy: string = 'fecha_desc';
    
    $: filteredExamenes = examenes.filter(examen => {
        const matchesSearch = !searchTerm || 
            examen.examen_nombre?.toLowerCase().includes(searchTerm.toLowerCase()) ||
            examen.examen_codigo?.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesTipo = !selectedTipoExamen || examen.tipo_procedimiento === selectedTipoExamen;
        return matchesSearch && matchesTipo;
    }).sort((a, b) => {
        switch(sortBy) {
            case 'fecha_desc':
                return new Date(b.fecha_examen).getTime() - new Date(a.fecha_examen).getTime();
            case 'fecha_asc':
                return new Date(a.fecha_examen).getTime() - new Date(b.fecha_examen).getTime();
            case 'nombre':
                return (a.examen_nombre || '').localeCompare(b.examen_nombre || '');
            default:
                return 0;
        }
    });
    
    $: tiposExamen = [...new Set(examenes.map(e => e.tipo_procedimiento).filter(Boolean))];
    
    $: uniqueDates = [...new Set(examenes.map(e => e.fecha_examen).filter(Boolean))].sort((a, b) => 
        new Date(b).getTime() - new Date(a).getTime()
    );
    
    async function cargarExamenesPaciente() {
        if (!paciente?.identificacion) return;
        
        loading = true;
        try {
            const response = await fetch(`${API_BASE_URL}${EXAMENES_PACIENTE_ENDPOINT}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    identificacion: paciente.identificacion,
                    fecha: getDefaultDate() // Fecha actual como fallback
                })
            });
            
            const data = await response.json();
            console.log('📋 Datos de exámenes recibidos:', data);
            
            if (data.success && data.examenes) {
                examenes = data.examenes;
                console.log(`📋 Se cargaron ${examenes.length} exámenes para ${paciente.nombre_completo}`);
            } else if (data.success && data.pacientes && data.pacientes.length > 0) {
                // Compatibilidad con respuesta anterior
                const pacienteData = data.pacientes[0];
                examenes = pacienteData.examenes || [];
                console.log(`📋 Se cargaron ${examenes.length} exámenes para ${paciente.nombre_completo}`);
            } else {
                examenes = [];
                console.log('📋 No se encontraron exámenes para este paciente');
            }
        } catch (error) {
            console.error('❌ Error al cargar exámenes:', error);
            examenes = [];
        } finally {
            loading = false;
        }
    }
    
    function handleClose() {
        dispatch('close');
    }
    
    function getEstadoIcon(estado: string) {
        switch(estado) {
            case 'Completado':
                return CheckCircle;
            case 'Pendiente':
                return AlertCircle;
            case 'Error':
            case 'Sin datos':
                return XCircle;
            default:
                return Activity;
        }
    }
    
    function getEstadoColor(estado: string) {
        switch(estado) {
            case 'Completado':
                return 'emerald';
            case 'Pendiente':
                return 'amber';
            case 'Error':
            case 'Sin datos':
                return 'red';
            default:
                return 'slate';
        }
    }
    
    function formatDate(dateString: string) {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }
    
    function getResultadoBadgeClass(estado: string) {
        switch(estado) {
            case 'Completado':
                return 'bg-emerald-50 text-emerald-700 border-emerald-200';
            case 'Pendiente':
                return 'bg-amber-50 text-amber-700 border-amber-200';
            case 'Error':
                return 'bg-red-50 text-red-700 border-red-200';
            default:
                return 'bg-slate-50 text-slate-700 border-slate-200';
        }
    }
    
    onMount(() => {
        if (show && paciente) {
            cargarExamenesPaciente();
        }
    });
    
    $: if (show && paciente) {
        cargarExamenesPaciente();
    }
</script>

<Modal 
    show={show} 
    title="Resultados de Exámenes"
    subtitle={paciente ? `${paciente.nombre_completo || paciente.nombre + ' ' + paciente.apellido} - ${paciente.edad || paciente.edad_texto} años` : ''}
    size="xl"
    on:close={handleClose}
>
    <div class="exams-modal-content">
        <!-- Filtros y búsqueda -->
        <div class="filters-section">
            <div class="search-box">
                <Search class="search-icon" />
                <input 
                    type="text" 
                    placeholder="Buscar exámenes..."
                    bind:value={searchTerm}
                    class="search-input"
                />
            </div>
            
            <div class="filter-controls">
                <select 
                    bind:value={selectedTipoExamen}
                    class="filter-select"
                >
                    <option value="">Todos los tipos</option>
                    {#each tiposExamen as tipo}
                        <option value={tipo}>{tipo}</option>
                    {/each}
                </select>
                
                <select 
                    bind:value={sortBy}
                    class="filter-select"
                >
                    <option value="fecha_desc">Más recientes</option>
                    <option value="fecha_asc">Más antiguos</option>
                    <option value="nombre">Por nombre</option>
                </select>
            </div>
        </div>
        
        <!-- Estado de carga -->
        {#if loading}
            <div class="loading-state">
                <Loader2 class="loading-icon" />
                <p>Cargando resultados de exámenes...</p>
            </div>
        {:else if filteredExamenes.length === 0}
            <div class="empty-state">
                <FileText class="empty-icon" />
                <h3>No se encontraron exámenes</h3>
                <p>No hay resultados de exámenes disponibles para este paciente.</p>
            </div>
        {:else}
            <!-- Agrupar por fecha -->
            {#each uniqueDates as fecha}
                {@const examenesFecha = filteredExamenes.filter(e => e.fecha_examen === fecha)}
                <div class="date-section">
                    <div class="date-header">
                        <Calendar class="date-icon" />
                        <h3 class="date-title">{formatDate(fecha)}</h3>
                        <Badge text={`${examenesFecha.length} exámenes`} variant="indigo" size="sm" />
                    </div>
                    
                    <div class="exams-grid">
                        {#each examenesFecha as examen (examen.codigo + examen.fecha_examen)}
                            <div class="exam-card" transition:fly={{ y: 20, duration: 300 }}>
                                <div class="exam-header">
                                    <div class="exam-info">
                                        <h4 class="exam-name">{examen.nombre || 'Examen sin nombre'}</h4>
                                        <p class="exam-code">Código: {examen.codigo}</p>
                                    </div>
                                    <Badge 
                                        text={examen.estado || 'Desconocido'} 
                                        variant={getEstadoColor(examen.estado)} 
                                        size="sm" 
                                    />
                                </div>
                                
                                <div class="exam-details">
                                    <div class="exam-row">
                                        <span class="exam-label">Tipo:</span>
                                        <span class="exam-value">{examen.tipo || 'No especificado'}</span>
                                    </div>
                                    
                                    <div class="exam-row">
                                        <span class="exam-label">Entidad:</span>
                                        <span class="exam-value">{examen.entidad || 'N/A'}</span>
                                    </div>
                                    
                                    <div class="exam-row">
                                        <span class="exam-label">Realizado:</span>
                                        <span class="exam-value">{examen.realizado === 'S' ? 'Sí' : 'No'}</span>
                                    </div>
                                </div>
                                
                                {#if examen.resultado && examen.resultado !== 'Pendiente' && examen.resultado !== 'No disponible'}
                                    <div class="exam-result">
                                        <h5 class="result-title">Resultado:</h5>
                                        <div class="result-content">
                                            <p class="result-text">{examen.resultado}</p>
                                            {#if examen.referencia && examen.referencia !== 'N/A'}
                                                <p class="result-reference">
                                                    <span class="reference-label">Referencia:</span>
                                                    <span class="reference-value">{examen.referencia}</span>
                                                </p>
                                            {/if}
                                        </div>
                                    </div>
                                {/if}
                                
                                {#if examen.resultado_completo}
                                    <details class="detailed-results">
                                        <summary class="details-summary">Ver detalles completos</summary>
                                        <div class="details-content">
                                            {#each Object.entries(examen.resultado_completo) as [key, value]}
                                                {#if !['ind', 'identificacion', 'codexamen', 'examen', 'fecha', 'hora', 'id', 'bacteriologo', 'observaciones', 'fechahora'].includes(key) && value && value !== '0000-00-00' && value !== '0' && value !== 'N/A'}
                                                    <div class="detail-row">
                                                        <span class="detail-key">{key.replace(/_/g, ' ').toUpperCase()}:</span>
                                                        <span class="detail-value">{value}</span>
                                                    </div>
                                                {/if}
                                            {/each}
                                        </div>
                                    </details>
                                {/if}
                            </div>
                        {/each}
                    </div>
                </div>
            {/each}
        {/if}
    </div>
</Modal>

<style>
    .exams-modal-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .filters-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
        background: rgba(241, 245, 249, 0.5);
        border-radius: 0.75rem;
        border: 1px solid rgba(226, 232, 240, 0.6);
    }
    
    .search-box {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .search-icon {
        position: absolute;
        left: 0.75rem;
        width: 1.25rem;
        height: 1.25rem;
        color: var(--slate-400);
        pointer-events: none;
    }
    
    .search-input {
        width: 100%;
        padding: 0.75rem 0.75rem 0.75rem 2.5rem;
        border: 1px solid var(--slate-300);
        border-radius: 0.5rem;
        background: white;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: var(--indigo-500);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .filter-controls {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .filter-select {
        flex: 1;
        min-width: 150px;
        padding: 0.5rem 0.75rem;
        border: 1px solid var(--slate-300);
        border-radius: 0.5rem;
        background: white;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: var(--indigo-500);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .loading-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        gap: 1rem;
        color: var(--slate-600);
    }
    
    .loading-icon {
        width: 2.5rem;
        height: 2.5rem;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1rem;
        gap: 1rem;
        text-align: center;
        color: var(--slate-600);
    }
    
    .empty-icon {
        width: 3rem;
        height: 3rem;
        color: var(--slate-400);
    }
    
    .empty-state h3 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--slate-700);
    }
    
    .date-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .date-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, var(--indigo-50), var(--violet-50));
        border-radius: 0.75rem;
        border: 1px solid rgba(99, 102, 241, 0.2);
    }
    
    .date-icon {
        width: 1.25rem;
        height: 1.25rem;
        color: var(--indigo-600);
    }
    
    .date-title {
        flex: 1;
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--indigo-900);
    }
    
    .exams-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    }
    
    .exam-card {
        padding: 1rem;
        background: white;
        border-radius: 0.75rem;
        border: 1px solid var(--slate-200);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }
    
    .exam-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    
    .exam-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        gap: 0.5rem;
    }
    
    .exam-info {
        flex: 1;
        min-width: 0;
    }
    
    .exam-name {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--slate-800);
        line-height: 1.3;
    }
    
    .exam-code {
        margin: 0;
        font-size: 0.75rem;
        color: var(--slate-500);
        font-family: monospace;
    }
    
    .exam-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    
    .exam-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
    }
    
    .exam-label {
        font-weight: 500;
        color: var(--slate-600);
    }
    
    .exam-value {
        color: var(--slate-800);
        font-weight: 400;
    }
    
    .exam-result {
        margin-top: 0.75rem;
        padding: 0.75rem;
        background: var(--slate-50);
        border-radius: 0.5rem;
        border: 1px solid var(--slate-200);
    }
    
    .result-title {
        margin: 0 0 0.5rem 0;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--slate-700);
    }
    
    .result-content {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .result-text {
        margin: 0;
        font-size: 0.875rem;
        color: var(--slate-800);
        line-height: 1.4;
    }
    
    .result-reference {
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        font-size: 0.75rem;
    }
    
    .reference-label {
        font-weight: 600;
        color: var(--slate-600);
    }
    
    .reference-value {
        color: var(--slate-700);
    }
    
    .detailed-results {
        margin-top: 0.75rem;
    }
    
    .details-summary {
        cursor: pointer;
        padding: 0.5rem;
        background: var(--indigo-50);
        border: 1px solid var(--indigo-200);
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--indigo-700);
        transition: background-color 0.2s ease;
    }
    
    .details-summary:hover {
        background: var(--indigo-100);
    }
    
    .details-content {
        margin-top: 0.5rem;
        padding: 0.75rem;
        background: white;
        border: 1px solid var(--slate-200);
        border-radius: 0.375rem;
        font-size: 0.75rem;
        max-height: 200px;
        overflow-y: auto;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.25rem 0;
        border-bottom: 1px solid var(--slate-100);
    }
    
    .detail-row:last-child {
        border-bottom: none;
    }
    
    .detail-key {
        font-weight: 600;
        color: var(--slate-600);
        flex-shrink: 0;
        margin-right: 0.5rem;
    }
    
    .detail-value {
        color: var(--slate-800);
        text-align: right;
        word-break: break-all;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filters-section {
            padding: 0.75rem;
        }
        
        .filter-controls {
            flex-direction: column;
        }
        
        .filter-select {
            width: 100%;
        }
        
        .exams-grid {
            grid-template-columns: 1fr;
        }
        
        .exam-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .date-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>