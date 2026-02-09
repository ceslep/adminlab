<script lang="ts">
import PatientFilters from './patient/PatientFilters.svelte';
    import PatientList from './patient/PatientList.svelte';
    import DashboardHeader from './DashboardHeader.svelte';
    import PatientExamModal from './patient/PatientExamModal.simple.svelte';
    // @ts-ignore
    import PatientDetailsModal from './patient/PatientDetailsModal.svelte';
    // @ts-ignore
    import { getPatientDetails } from '../../../lib/api';
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
    let showDetailsModal = false;
    let selectedPatient: any = null;
    let patientDetails: any = null;
    let loadingDetails = false;
    
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
    
async function handleViewDetails(event: CustomEvent) {
        const paciente = event.detail.paciente;
        console.log('👤 Ver detalles completos del paciente:', paciente);
        
        loadingDetails = true;
        try {
            const response = await getPatientDetails(paciente.identificacion);
            if (response.success) {
                patientDetails = (response as any).data || (response as any).patient;
                showDetailsModal = true;
                console.log('📋 Detalles del paciente cargados:', patientDetails);
            } else {
                console.error('❌ Error al cargar detalles:', response.message);
                alert('Error al cargar detalles del paciente: ' + response.message);
            }
        } catch (error) {
            console.error('❌ Error en la llamada:', error);
            alert('Error de conexión al obtener detalles del paciente');
        } finally {
            loadingDetails = false;
        }
    }
    
    function handleCloseDetailsModal() {
        showDetailsModal = false;
        patientDetails = null;
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
        

        
{#if showExamModal}
            <PatientExamModal 
                paciente={selectedPatient}
                show={showExamModal}
                onClose={handleCloseExamModal}
            />
        {/if}
        
        {#if showDetailsModal}
            <PatientDetailsModal 
                paciente={patientDetails}
                show={showDetailsModal}
                onClose={handleCloseDetailsModal}
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