<script lang="ts">
 import { fade, fly, scale } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
 import PatientFilters from './patient/PatientFilters.svelte';
    import PatientList from './patient/PatientList.svelte';
    import DashboardHeader from './DashboardHeader.svelte';
    import PatientExamModal from './patient/ExamenesModal.svelte';
    // @ts-ignore
    import PatientDetailsModal from './patient/PatientDetailsModal.svelte';
    // @ts-ignore
    import { getPatientDetails } from '../../../lib/api';
    // import PatientExamModal from './patient/PatientExamModal.svelte';
    // import PatientExamModal from './patient/PatientExamModal.enhanced.svelte';
    
    export let searchQuery: string = '';
    export let selectedDate: string = new Date().toISOString().split('T')[0];
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
        console.log('🔴 Cerrando modal de exámenes');
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
        <div in:fly={{ y: 20, duration: 500, easing: cubicOut }}>
            <PatientFilters
                bind:searchQuery
                bind:selectedDate
                totalPatients={pacientes.length}
                onSearch={onSearch}
                onDateChange={onDateChange}
                onRefresh={onRefresh}
                loading={loading}
            />
        </div>

        <div in:fly={{ y: 20, duration: 500, delay: 200, easing: cubicOut }}>
            <PatientList 
                {pacientes}
                {loading}
                {searchQuery}
                onViewExams={handleViewExams}
                onViewDetails={handleViewDetails}
                on:viewExams={handleViewExams}
                on:viewDetails={handleViewDetails}
            />
        </div>
    </main>
        
    {#if showExamModal}
        <div in:fade={{ duration: 300 }}>
            <PatientExamModal 
                paciente={selectedPatient}
                show={showExamModal}
                onClose={handleCloseExamModal}
            />
        </div>
    {/if}
        
    {#if showDetailsModal}
        <div in:fade={{ duration: 300 }}>
            <PatientDetailsModal 
                paciente={patientDetails}
                show={showDetailsModal}
                onClose={handleCloseDetailsModal}
            />
        </div>
    {/if}
</div>

<style>
    .patient-view {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        position: relative;
        overflow-x: hidden;
    }
    
    .patient-view::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.08) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    
    .patient-main {
        max-width: 1600px;
        margin: 0 auto;
        padding: 3rem 2rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        position: relative;
        z-index: 1;
    }
    
    /* Premium card styles for patient list */
    :global(.patient-card-grid) {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 1.5rem;
        padding: 1rem 0;
    }
    
    :global(.patient-card) {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2rem;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    
    :global(.patient-card:hover) {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border-color: rgba(99, 102, 241, 0.3);
    }
    
    :global(.patient-card::before) {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent, rgba(99, 102, 241, 0.02), transparent);
        opacity: 0;
        transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    :global(.patient-card:hover::before) {
        opacity: 1;
    }
    
    @media (max-width: 768px) {
        .patient-main {
            padding: 2rem 1rem;
        }
        
        :global(.patient-card-grid) {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>