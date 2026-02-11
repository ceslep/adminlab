<script lang="ts">
    import { fade, fly, scale } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import { 
        FileText, 
        Calendar, 
        Download, 
        Eye, 
        AlertTriangle,
        CheckCircle,
        Clock,
        TrendingUp,
        Activity,
        Stethoscope,
        TestTube,
        Heart
    } from 'lucide-svelte';
    import Button from '../shared/Button.svelte';
    import Badge from '../shared/Badge.svelte';
    import Avatar from '../shared/Avatar.svelte';
    import Modal from '../shared/Modal.svelte';
    import PremiumInput from '../shared/PremiumInput.svelte';
    import { Search } from 'lucide-svelte';
    import { slide } from 'svelte/transition';
    import { API_BASE_URL, EXAMENES_PACIENTE_ENDPOINT, RESULTADOS_EXAMEN_ENDPOINT } from '$lib/constants';
    import { securityManager } from '$lib/security/data-security';

    
    export let paciente: any;
    export let show: boolean = false;
    export let onClose: () => void;
    
    function handleClosePrincipal() {
        console.log('🔴 Cerrando modal principal de exámenes');
        if (onClose) {
            onClose();
        }
    }
    export let selectedDate: string = '';
    
    let loading = false;
    let error: string = '';
    let examenes: any[] = [];
    let selectedExamen: any = null;
    let showResultadosModal = false;
    let loadingResultados = false;
    let resultadosExamen: any = null;
    let searchQuery = '';
    let filterDate = '';
    let showFilters = false;
    let filterType = 'all'; // all, completed, pending, critical
    
    // Cargar exámenes cuando se abre el modal
    $: if (show && paciente?.identificacion) {
        cargarExamenesPaciente();
    }
    
    async function cargarExamenesPaciente() {
        if (!paciente?.identificacion) {
            examenes = [];
            return;
        }
        
        loading = true;
        error = '';
        
        try {
            // Consultar a la API para obtener exámenes del paciente
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
            console.log('📊 Respuesta del API de exámenes:', data);
            
            if (data.success && data.examenes) {
                console.log('📋 Exámenes recibidos del API:', data.examenes);
                // Mapear los exámenes según la estructura de la base de datos
                examenes = data.examenes.map((examen: any) => {
                    console.log('🔄 Procesando examen:', examen);
                    return {
                        ...examen,
                        codigo: examen.codexamen || examen.codigo,
                        nombre: obtenerNombreExamen(examen),
                        estado: determinarEstado(examen),
                        realizado: examen.realizado === 'S' || examen.realizado === true,
                        resultado: null,
                        tipoExamen: obtenerTipoExamen(examen)
                    };
                console.log('✅ Exámenes procesados:', examenes);
            } else {
                // Fallback: usar datos del paciente
                examenes = paciente.examenes_codigos?.map((codigo: string, index: number) => ({
                    codigo: codigo,
                    nombre: paciente.examenes?.[index] || `Examen ${codigo}`,
                    estado: 'Pendiente',
                    realizado: false,
                    entidad: paciente.entidad,
                    fecha: selectedDate || getDefaultDate(),
                    tipoExamen: 'generic'
                })) || [];
            }
            
            // Log de acceso para auditoría
            securityManager.logDataAccess('VIEW_PATIENT_EXAMS', { 
                pacienteId: paciente.identificacion, 
                totalExamenes: examenes.length 
            });
            
        } catch (err) {
            console.error('Error cargando exámenes:', err);
            error = 'Error al cargar los exámenes. Por favor, inténtelo de nuevo.';
            
            // Fallback a datos básicos
            examenes = paciente.examenes_codigos?.map((codigo: string, index: number) => ({
                codigo: codigo,
                nombre: paciente.examenes?.[index] || `Examen ${codigo}`,
                estado: 'Pendiente',
                realizado: false,
                entidad: paciente.entidad
            })) || [];
        } finally {
            loading = false;
        }
    }
    
    function obtenerNombreExamen(examen: any): string {
        // Determinar el nombre del examen basado en los datos de la tabla
        if (examen.nombre) return examen.nombre;
        if (examen.abreviatura) return examen.abreviatura;
        
        // Mapeo de códigos comunes a nombres descriptivos
        const mapeoExamenes: { [key: string]: string } = {
            'HEMO': 'Hemograma Completo',
            'HEMOG': 'Hemograma',
            'QUIM': 'Química Sanguínea',
            'PERFIL': 'Perfil Lipídico',
            'GLUC': 'Glucosa',
            'UREA': 'Urea',
            'CREAT': 'Creatinina',
            'COLESTEROL': 'Colesterol',
            'TRIG': 'Triglicéridos',
            'HDL': 'Colesterol HDL',
            'LDL': 'Colesterol LDL',
            'ORINA': 'Análisis de Orina',
            'CULTIVO': 'Cultivo',
            'HECES': 'Heces',
            'PSA': 'Antígeno Prostático',
            'TSH': 'Hormona Estimulante de Tiroides',
            'T4': 'Tiroxina T4',
            'ECG': 'Electrocardiograma',
            'RX': 'Radiografía',
            'USG': 'Ultrasonografía',
            'RMN': 'Resonancia Magnética',
            'TAC': 'Tomografía Axial Computarizada',
            'PAP': 'Papanicolaou',
            'BIOPSIA': 'Biopsia',
            'PCR': 'Reacción en Cadena de la Polimerasa',
            'VIH': 'Prueba VIH',
            'VDRL': 'Prueba Sífilis VDRL',
            'RH': 'Factor Rh',
            'GROUP': 'Grupo Sanguíneo'
        };
        
        return mapeoExamenes[examen.codigo] || `Examen ${examen.codigo}`;
    }
    
    function obtenerTipoExamen(examen: any): string {
        // Determinar el tipo de examen basado en la tabla
        const tabla = examen.tabla || examen.examen_tabla;
        
        if (!tabla) return 'generic';
        
        // Mapeo de tablas a tipos de examen
        const tipoTabla: { [key: string]: string } = {
            'examen_tipo_1': 'Bacteriológico',
            'examen_tipo_2': 'Inmunológico',
            'examen_tipo_3': 'Urinálisis',
            'examen_tipo_4': 'Parasitológico',
            'examen_tipo_5': 'Hematológico',
            'examen_tipo_7': 'Coagulación',
            'hemogramaRayto': 'Hematología Automatizada',
            'perfilLipidico': 'Perfil Lipídico',
            'coprologico': 'Análisis Coprológico',
            'frotisVaginal': 'Citología Vaginal',
            'parcialOrina': 'Análisis Parcial de Orina'
        };
        
        return tipoTabla[tabla] || 'General';
    }
    
    function formatFecha(fecha: string): string {
        if (!fecha) return 'N/A';
        
        try {
            const date = new Date(fecha);
            return date.toLocaleDateString('es-CO', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        } catch (error) {
            return fecha;
        }
    }
    
    function getDefaultDate(): string {
        return new Date().toISOString().split('T')[0];
    }
    
    function determinarEstado(examen: any): string {
        // Debug logging para diagnóstico
        console.log('🔍 Determinando estado del examen:', {
            codigo: examen.codigo,
            realizado: examen.realizado,
            tipoRealizado: typeof examen.realizado,
            resultado: examen.resultado,
            tieneResultados: tieneResultados(examen)
        });
        
        // Determinar el estado basado en si está realizado y otros factores
        if (examen.realizado === 'S' || examen.realizado === true) {
            // Verificar si tiene resultados
            if (examen.resultado || tieneResultados(examen)) {
                console.log('✅ Examen marcado como Completado');
                return 'Completado';
            }
            console.log('⏳ Examen marcado como Procesando');
            return 'Procesando';
        }
        console.log('⭕ Examen marcado como Pendiente');
        return 'Pendiente';
    }
    
    function tieneResultados(examen: any): boolean {
        // Verificar si el examen tiene resultados en su tabla específica
        return !!(examen.resultado && 
                   examen.resultado !== 'No disponible' && 
                   examen.resultado !== 'Sin datos' && 
                   examen.resultado !== 'Pendiente' &&
                   examen.resultado !== '');
    }
    
    async function verResultados(examen: any) {
        selectedExamen = examen;
        showResultadosModal = true;
        loadingResultados = true;
        resultadosExamen = null;
        
        try {
            // Log de acceso para auditoría
            securityManager.logDataAccess('VIEW_EXAM_RESULTS', { 
                pacienteId: paciente.identificacion,
                examenCodigo: examen.codigo,
                examenTabla: examen.tabla
            });
            
            // Obtener resultados específicos del examen
            const response = await fetch(`${API_BASE_URL}${RESULTADOS_EXAMEN_ENDPOINT}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    identificacion: paciente.identificacion,
                    codexamen: examen.codigo,
                    fecha: examen.fecha,
                    tabla: examen.tabla || ''
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.resultados) {
                    resultadosExamen = data.resultados;
                }
            }
            
            // Si no se encuentran resultados, mostrar mensaje apropiado
            if (!resultadosExamen) {
                resultadosExamen = {
                    mensaje: 'Los resultados aún no están disponibles',
                    estado: 'Pendiente'
                };
            }
            
        } catch (err) {
            console.error('Error cargando resultados:', err);
            resultadosExamen = {
                mensaje: 'Error al cargar los resultados',
                estado: 'Error'
            };
        } finally {
            loadingResultados = false;
        }
    }
    
    function cerrarResultados() {
        console.log('🔴 Cerrando modal de resultados');
        showResultadosModal = false;
        selectedExamen = null;
        resultadosExamen = null;
    }
    
    function formatarResultado(resultado: any, tipo: string): string {
        if (!resultado) return 'N/A';
        
        // Formatear según el tipo de examen
        if (typeof resultado === 'string') {
            return resultado;
        }
        
        // Para resultados objeto, formatear los campos principales
        if (tipo === 'examen_tipo_5' || tipo === 'hemogramaRayto') {
            // Hemograma
            const campos = ['hemoglobina', 'hematocrito', 'leucocitos', 'WBC', 'RBC', 'PLT'];
            const valores = campos
                .filter(campo => resultado[campo] && resultado[campo] !== 'N/A' && resultado[campo] !== '')
                .map(campo => `${campo}: ${resultado[campo]}`);
            return valores.join(', ') || 'Sin datos';
        }
        
        if (tipo === 'perfilLipidico') {
            // Perfil lipídico
            const campos = ['colesterol_total', 'colesterol_hdl', 'colesterol_ldl', 'trigliceridos'];
            const valores = campos
                .filter(campo => resultado[campo] && resultado[campo] !== 'N/A' && resultado[campo] !== '')
                .map(campo => `${campo}: ${resultado[campo]}`);
            return valores.join(', ') || 'Sin datos';
        }
        
        // Para otros tipos, buscar campos comunes
        const camposComunes = ['resultado', 'valor', 'valoracion', 'diagnostico'];
        for (const campo of camposComunes) {
            if (resultado[campo] && resultado[campo] !== 'N/A' && resultado[campo] !== '') {
                return resultado[campo];
            }
        }
        
        return JSON.stringify(resultado);
    }
    
    function getEstadoVariant(estado: string): 'indigo' | 'emerald' | 'violet' | 'amber' | 'rose' | 'slate' | 'blue' | 'green' | 'purple' | 'yellow' | 'red' | 'gray' {
        const variants: { [key: string]: 'indigo' | 'emerald' | 'violet' | 'amber' | 'rose' | 'slate' | 'blue' | 'green' | 'purple' | 'yellow' | 'red' | 'gray' } = {
            'Completado': 'emerald',
            'Procesando': 'amber',
            'Pendiente': 'slate',
            'Error': 'rose',
            'Crítico': 'rose'
        };
        return variants[estado] || 'slate';
    }
    
    function getTipoExamenIcon(tipo: string) {
        const icons: { [key: string]: any } = {
            'Hematológico': Heart,
            'Bacteriológico': TestTube,
            'Inmunológico': Activity,
            'Urinálisis': FileText,
            'Parasitológico': AlertTriangle,
            'Coagulación': Clock,
            'Hematología Automatizada': TrendingUp,
            'Perfil Lipídico': Stethoscope,
            'General': FileText
        };
        return icons[tipo] || FileText;
    }
    
    // Filtrar exámenes
    $: examenesFiltrados = examenes.filter(examen => {
        const coincideBusqueda = !searchQuery || 
            examen.nombre.toLowerCase().includes(searchQuery.toLowerCase()) ||
            examen.codigo.toLowerCase().includes(searchQuery.toLowerCase());
        
        const coincideFecha = !filterDate || examen.fecha === filterDate;
        
        let coincideEstado = true;
        if (filterType === 'completed') {
            coincideEstado = examen.estado === 'Completado';
        } else if (filterType === 'pending') {
            coincideEstado = examen.estado === 'Pendiente';
        } else if (filterType === 'critical') {
            coincideEstado = examen.estado === 'Error' || examen.estado === 'Crítico';
        }
        
        return coincideBusqueda && coincideFecha && coincideEstado;
    });
    
    // Estadísticas
    $: totalExamenes = examenes.length;
    $: examenesCompletados = examenes.filter(e => e.estado === 'Completado').length;
    $: examenesPendientes = examenes.filter(e => e.estado === 'Pendiente').length;
    $: examenesCriticos = examenes.filter(e => e.estado === 'Error' || e.estado === 'Crítico').length;
</script>

<!-- Modal principal de exámenes -->
<Modal
    {show}
    size="xl"
    title={`Exámenes de ${paciente?.nombre_completo || 'Paciente'}`}
    subtitle={`${totalExamenes} exámenes registrados`}
    onClose={handleClosePrincipal}
>
    <!-- Header con búsqueda y filtros -->
    <div class="examenes-header">
        <div class="examenes-search">
            <PremiumInput
                type="text"
                placeholder="Buscar por nombre o código de examen..."
                bind:value={searchQuery}
                icon={Search}
                size="md"
            />
        </div>
        
        <div class="examenes-filters">
            <Button 
                variant="secondary" 
                size="sm" 
                onClick={() => showFilters = !showFilters}
            >
                <FileText class="w-4 h-4" />
                Filtros
            </Button>
        </div>
    </div>
    
    <!-- Panel de filtros -->
    {#if showFilters}
        <div class="filters-panel" transition:slide={{ duration: 300 }}>
            <div class="filter-group">
                <label class="filter-label">Fecha</label>
                <input 
                    type="date" 
                    bind:value={filterDate}
                    class="filter-input"
                />
            </div>
            
            <div class="filter-group">
                <label class="filter-label">Estado</label>
                <select bind:value={filterType} class="filter-select">
                    <option value="all">Todos</option>
                    <option value="completed">Completados</option>
                    <option value="pending">Pendientes</option>
                    <option value="critical">Críticos</option>
                </select>
            </div>
        </div>
    {/if}
    
    <!-- Estadísticas -->
    <div class="examenes-stats">
        <div class="stat-card">
            <div class="stat-icon-container primary">
                <FileText class="w-5 h-5" />
            </div>
            <div class="stat-content">
                <div class="stat-number">{totalExamenes}</div>
                <div class="stat-label">Total</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-container success">
                <CheckCircle class="w-5 h-5" />
            </div>
            <div class="stat-content">
                <div class="stat-number">{examenesCompletados}</div>
                <div class="stat-label">Completados</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-container warning">
                <Clock class="w-5 h-5" />
            </div>
            <div class="stat-content">
                <div class="stat-number">{examenesPendientes}</div>
                <div class="stat-label">Pendientes</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon-container error">
                <AlertTriangle class="w-5 h-5" />
            </div>
            <div class="stat-content">
                <div class="stat-number">{examenesCriticos}</div>
                <div class="stat-label">Críticos</div>
            </div>
        </div>
    </div>
    
    <!-- Loading state -->
    {#if loading}
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <p class="loading-text">Cargando exámenes...</p>
        </div>
    {:else if error}
        <div class="error-container">
            <AlertTriangle class="error-icon" />
            <div class="error-content">
                <h3>Error al cargar exámenes</h3>
                <p>{error}</p>
            </div>
        </div>
    {:else if examenesFiltrados.length === 0}
        <div class="empty-state">
            <FileText class="empty-icon" />
            <h3>No se encontraron exámenes</h3>
            <p>Este paciente no tiene exámenes registrados para los filtros seleccionados.</p>
        </div>
    {:else}
        <!-- Lista de exámenes -->
        <div class="examenes-list">
            {#each examenesFiltrados as examen}
                <div class="examen-card" in:fly={{ y: 20, duration: 300, delay: 50 }}>
                    <!-- Header del examen -->
                    <div class="examen-header">
                        <div class="examen-icon-container">
                            <svelte:component this={getTipoExamenIcon(examen.tipoExamen)} />
                        </div>
                        <div class="examen-info">
                            <h4 class="examen-nombre">{examen.nombre}</h4>
                            <div class="examen-meta">
                                <Badge text={`Código: ${examen.codigo}`} variant="indigo" size="sm" />
                                <span class="examen-separator">•</span>
                                <span class="examen-tipo">{examen.tipoExamen}</span>
                            </div>
                        </div>
                        <div class="examen-actions">
                            <Badge 
                                text={examen.estado} 
                                variant={getEstadoVariant(examen.estado)} 
                                size="sm" 
                            />
                        </div>
                    </div>
                    
                    <!-- Información adicional -->
                    <div class="examen-details">
                        <div class="detail-item">
                            <Calendar class="detail-icon" />
                            <span class="detail-text">
                                <strong>Fecha:</strong> {formatFecha(examen.fecha)}
                            </span>
                        </div>
                        {#if examen.entidad}
                            <div class="detail-item">
                                <Activity class="detail-icon" />
                                <span class="detail-text">
                                    <strong>Entidad:</strong> {examen.entidad}
                                </span>
                            </div>
                        {/if}
                    </div>
                    
                    <!-- Acciones -->
                    <div class="examen-actions-footer">
                        <Button 
                            variant="primary" 
                            size="sm"
                            onClick={() => verResultados(examen)}
                            disabled={!(examen.realizado && (examen.resultado || tieneResultados(examen)))}
                        >
                            <Eye class="w-4 h-4" />
                            {examen.realizado && (examen.resultado || tieneResultados(examen)) ? 'Ver Resultados' : 'Ver Detalles'}
                        </Button>
                        
                        {#if !examen.realizado}
                            <Button 
                                variant="secondary" 
                                size="sm"
                            >
                                <Clock class="w-4 h-4" />
                                Programar
                            </Button>
                        {/if}
                    </div>
                </div>
            {/each}
        </div>
    {/if}
</Modal>

<!-- Modal de resultados detallados -->
<Modal
    show={showResultadosModal}
    size="lg"
    title={`Resultados: ${selectedExamen?.nombre || 'Examen'}`}
    subtitle={`Código: ${selectedExamen?.codigo || 'N/A'}`}
    onClose={cerrarResultados}
>
    {#if loadingResultados}
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <p class="loading-text">Cargando resultados...</p>
        </div>
    {:else if resultadosExamen?.mensaje}
        <div class="empty-state">
            <AlertTriangle class="empty-icon" />
            <h3>{resultadosExamen.mensaje}</h3>
            <p>Los resultados estarán disponibles pronto.</p>
        </div>
    {:else}
        <div class="resultados-container">
            <!-- Aquí se mostrarían los resultados formateados -->
            {#if typeof resultadosExamen === 'string'}
                <div class="resultado-text">
                    <pre>{resultadosExamen}</pre>
                </div>
            {:else}
                <div class="resultado-grid">
                    {#each Object.entries(resultadosExamen || {}) as [key, value]}
                        {#if value && value !== 'N/A' && value !== ''}
                            <div class="resultado-item">
                                <div class="resultado-key">{key}:</div>
                                <div class="resultado-value">{value}</div>
                            </div>
                        {/if}
                    {/each}
                </div>
            {/if}
            
            <!-- Botones de acción -->
            <div class="resultados-actions">
                <Button variant="primary" size="md" onClick={cerrarResultados}>
                    Cerrar Resultados
                </Button>
                <Button variant="secondary" size="md">
                    <Download class="w-4 h-4" />
                    Exportar PDF
                </Button>
                <Button variant="secondary" size="md">
                    <Download class="w-4 h-4" />
                    Exportar Excel
                </Button>
            </div>
        </div>
    {/if}
</Modal>

<style>
    .examenes-header {
        display: flex;
        gap: var(--spacing-4);
        margin-bottom: var(--spacing-6);
        align-items: center;
    }
    
    .examenes-search {
        flex: 1;
        max-width: 400px;
    }
    
    .examenes-filters {
        display: flex;
        gap: var(--spacing-2);
    }
    
    .filters-panel {
        background: var(--color-neutral-100);
        border-radius: var(--radius-lg);
        padding: var(--spacing-4);
        margin-bottom: var(--spacing-6);
        display: flex;
        gap: var(--spacing-4);
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-2);
    }
    
    .filter-label {
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-semibold);
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-1);
    }
    
    .filter-input,
    .filter-select {
        padding: var(--spacing-3) var(--spacing-4);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        background: var(--color-surface);
        color: var(--color-text-primary);
        font-family: var(--font-family-sans);
        font-size: var(--font-size-sm);
        transition: all var(--duration-normal) var(--ease-out);
    }
    
    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: var(--color-primary-500);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .examenes-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: var(--spacing-4);
        margin-bottom: var(--spacing-6);
    }
    
    .stat-card {
        display: flex;
        align-items: center;
        gap: var(--spacing-3);
        padding: var(--spacing-3) var(--spacing-4);
        background: var(--color-surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
    }
    
    .stat-icon-container {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon-container.primary {
        background: var(--gradient-primary);
        color: white;
    }
    
    .stat-icon-container.success {
        background: var(--gradient-success);
        color: white;
    }
    
    .stat-icon-container.warning {
        background: var(--gradient-warning);
        color: white;
    }
    
    .stat-icon-container.error {
        background: var(--gradient-error);
        color: white;
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-number {
        font-size: var(--font-size-lg);
        font-weight: var(--font-weight-bold);
        color: var(--color-text-primary);
        line-height: 1;
    }
    
    .stat-label {
        font-size: var(--font-size-xs);
        color: var(--color-text-tertiary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .loading-container,
    .error-container,
    .empty-state {
        text-align: center;
        padding: var(--spacing-8);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--spacing-4);
    }
    
    .loading-spinner {
        width: 48px;
        height: 48px;
        border: 4px solid var(--color-neutral-200);
        border-top: 4px solid var(--color-primary-500);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .loading-text {
        color: var(--color-text-secondary);
        font-size: var(--font-size-sm);
    }
    
    .error-icon,
    .empty-icon {
        width: 64px;
        height: 64px;
        color: var(--color-neutral-400);
    }
    
    .error-content h3,
    .empty-state h3 {
        font-size: var(--font-size-lg);
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-2) 0;
    }
    
    .error-content p,
    .empty-state p {
        color: var(--color-text-secondary);
        margin: 0;
        text-align: center;
    }
    
    .examenes-list {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-4);
        max-height: 500px;
        overflow-y: auto;
    }
    
    .examen-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-xl);
        padding: var(--spacing-5);
        transition: all var(--duration-normal) var(--ease-out);
    }
    
    .examen-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--color-primary-200);
    }
    
    .examen-header {
        display: flex;
        align-items: flex-start;
        gap: var(--spacing-4);
        margin-bottom: var(--spacing-4);
    }
    
    .examen-icon-container {
        width: 48px;
        height: 48px;
        background: var(--color-neutral-100);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--color-primary-600);
        flex-shrink: 0;
    }
    
    .examen-info {
        flex: 1;
        min-width: 0;
    }
    
    .examen-nombre {
        font-size: var(--font-size-base);
        font-weight: var(--font-weight-semibold);
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-1) 0;
        line-height: var(--line-height-tight);
    }
    
    .examen-meta {
        display: flex;
        align-items: center;
        gap: var(--spacing-2);
        flex-wrap: wrap;
    }
    
    .examen-separator {
        color: var(--color-neutral-400);
        font-size: var(--font-size-xs);
    }
    
    .examen-tipo {
        font-size: var(--font-size-xs);
        color: var(--color-text-secondary);
        background: var(--color-neutral-100);
        padding: var(--spacing-1) var(--spacing-2);
        border-radius: var(--radius-sm);
    }
    
    .examen-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: var(--spacing-2);
    }
    
    .examen-details {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-2);
        margin-bottom: var(--spacing-4);
        padding-left: var(--spacing-8);
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: var(--spacing-2);
    }
    
    .detail-icon {
        width: 16px;
        height: 16px;
        color: var(--color-text-tertiary);
        flex-shrink: 0;
    }
    
    .detail-text {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
    }
    
    .examen-actions-footer {
        display: flex;
        gap: var(--spacing-3);
        justify-content: flex-end;
    }
    
    .resultados-container {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-6);
    }
    
    .resultado-text {
        background: var(--color-neutral-100);
        border-radius: var(--radius-lg);
        padding: var(--spacing-4);
        border: 1px solid var(--color-border);
        font-family: var(--font-family-mono);
        font-size: var(--font-size-sm);
        color: var(--color-text-primary);
        white-space: pre-wrap;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .resultado-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: var(--spacing-4);
    }
    
    .resultado-item {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-1);
    }
    
    .resultado-key {
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-semibold);
        color: var(--color-text-secondary);
        text-transform: capitalize;
    }
    
    .resultado-value {
        font-size: var(--font-size-base);
        color: var(--color-text-primary);
        padding: var(--spacing-2);
        background: var(--color-surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border);
        word-break: break-all;
    }
    
    .resultados-actions {
        display: flex;
        gap: var(--spacing-3);
        justify-content: flex-end;
        margin-top: var(--spacing-6);
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .examenes-header {
            flex-direction: column;
            align-items: stretch;
            gap: var(--spacing-3);
        }
        
        .examenes-search {
            max-width: 100%;
        }
        
        .filters-panel {
            flex-direction: column;
        }
        
        .examenes-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .examen-header {
            flex-direction: column;
            gap: var(--spacing-3);
            align-items: stretch;
        }
        
        .examen-details {
            padding-left: 0;
        }
        
        .examen-actions {
            align-items: stretch;
        }
        
        .examen-actions-footer {
            flex-direction: column;
            gap: var(--spacing-2);
        }
        
        .resultados-actions {
            flex-direction: column;
            gap: var(--spacing-2);
        }
    }
</style>