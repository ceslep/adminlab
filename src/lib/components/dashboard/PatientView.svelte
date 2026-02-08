<script lang="ts">
    import PatientFilters from './patient/PatientFilters.svelte';
    import PatientList from './patient/PatientList.svelte';
    import DashboardHeader from './DashboardHeader.svelte';
    import PatientExamModal from './patient/PatientExamModal.simple.svelte';
    // import PatientExamModal from './patient/PatientExamModal.svelte';
    // import PatientExamModal from './patient/PatientExamModal.enhanced.svelte';
    
    export let searchQuery: string = '';
    export let selectedDate: string = '';
    export let pacientes: any[] = [];
    export let loading: boolean = false;
    export let onBack: () => void;
    export let onLogout: () => void;
    export let onSearch: () => void;
    export let onDateChange: () => void;
    export let onRefresh: () => void;
    
    let showExamModal = false;
    let selectedPatient: any = null;
    
    function handleViewExams(event: CustomEvent) {
        console.log('📋 handleViewExams llamado en PatientView:', event);
        selectedPatient = event.detail.paciente;
        showExamModal = true;
        console.log('📋 Abriendo modal de exámenes para:', selectedPatient.nombre_completo);
        console.log('📋 Estado del modal:', { showExamModal, selectedPatient });
    }
    
    function handleCloseExamModal() {
        showExamModal = false;
        selectedPatient = null;
    }
    
    function handleViewDetails(event: CustomEvent) {
        const paciente = event.detail.paciente;
        console.log('👤 Ver detalles completos del paciente:', paciente);
        // Aquí podríamos mostrar otro modal o navegar a una vista de detalles
    }
</script>

<div class="patient-view">
    <DashboardHeader 
        title="Gestión de Pacientes"
        showBackButton={true}
        {onBack}
        {onLogout}
    />

    <main class="patient-main">
        <PatientFilters
            bind:searchQuery
            bind:selectedDate
            totalPatients={pacientes.length}
            onSearch={onSearch}
            onDateChange={onDateChange}
            onRefresh={onRefresh}
            loading={loading}
        />

        <PatientList 
            {pacientes}
            {loading}
            {searchQuery}
            onViewExams={handleViewExams}
            onViewDetails={handleViewDetails}
            on:viewExams={handleViewExams}
            on:viewDetails={handleViewDetails}
        />
        </main>
        
        <!-- Botón de prueba -->
        <div style="position: fixed; bottom: 20px; right: 20px; z-index: 100;">
            <button 
                on:click={() => {
                    if (pacientes.length > 0) {
                        selectedPatient = pacientes[0];
                        showExamModal = true;
                        console.log('🧪 Botón de prueba - Abriendo modal para:', selectedPatient.nombre_completo);
                    }
                }}
                style="background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;"
            >
                🧪 Test Modal
            </button>
        </div>
        
        {#if showExamModal}
            <PatientExamModal 
                paciente={selectedPatient}
                show={showExamModal}
                onClose={handleCloseExamModal}
            />
        {/if}
    </div>

<style>
    .patient-view {
        min-height: 100vh;
        background: #f8fafc;
    }
    
    .patient-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
</style>