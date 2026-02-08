<script lang="ts">
    import { createEventDispatcher } from 'svelte';
    import { getPacientes } from '../api';
    import DashboardView from './dashboard/DashboardView.svelte';
    import PatientView from './dashboard/PatientView.svelte';
    
    const dispatch = createEventDispatcher<{ logout: undefined }>();

    export let userName: string = 'Usuario';

    // Estado
    let showPatientsView = false;
    let pacientes: any[] = [];
    let loading = false;
    let searchQuery = '';
    let selectedDate = new Date().toISOString().split('T')[0];

    // Datos de prueba (mock)
    const mockPacientes = [
        { 
            id_paciente: 1, 
            nombre_completo: 'Juan Pérez García', 
            telefono: '3001234567',
            email: 'juan.perez@email.com',
            identificacion: '12345678',
            edad: 40,
            genero: 'M',
            estado: 'Activo',
            entidad: 'SURA',
            total_examenes: 2,
            examenes: ['Sangre', 'Orina']
        },
        { 
            id_paciente: 2, 
            nombre_completo: 'María González López', 
            telefono: '3009876543',
            email: 'maria.gonzalez@email.com',
            identificacion: '87654321',
            edad: 33,
            genero: 'F',
            estado: 'Activo',
            entidad: 'COOMEVA',
            total_examenes: 1,
            examenes: ['Radiografía']
        },
        { 
            id_paciente: 3, 
            nombre_completo: 'Carlos Rodríguez Martínez', 
            telefono: '3012345678',
            email: 'carlos.rodriguez@email.com',
            identificacion: '45678912',
            edad: 47,
            genero: 'M',
            estado: 'Inactivo',
            entidad: 'SISBEN',
            total_examenes: 3,
            examenes: ['Sangre', 'Orina', 'ECG']
        }
    ];

    // Funciones
    async function loadPacientes() {
        console.log('loadPacientes() llamado con parámetros:', { searchQuery, selectedDate });
        loading = true;
        
        try {
            console.log('Llamando a API getPacientes...');
            const response = await getPacientes(searchQuery, selectedDate);
            console.log('Respuesta de la API:', response);
            
            if (response.success && response.data) {
                pacientes = response.data;
                console.log('Pacientes cargados desde API:', pacientes.length);
            } else {
                console.log('Error en respuesta de API:', response.message);
                // En caso de error, usar datos de prueba
                pacientes = mockPacientes;
                console.log('Usando datos de prueba como fallback');
            }
        } catch (error) {
            console.error('Error llamando a la API:', error);
            // En caso de error crítico, usar datos de prueba
            pacientes = mockPacientes;
            console.log('Usando datos de prueba como fallback de error');
        } finally {
            loading = false;
        }
    }

    function handleShowPatients() {
        console.log('handleShowPatients() iniciado');
        showPatientsView = true;
        loadPacientes();
    }

    function handleBackToDashboard() {
        console.log('handleBackToDashboard() llamado');
        showPatientsView = false;
    }

    function handleLogout(): void {
        console.log('🔴 handleLogout() llamado');
        console.log('dispatch function:', typeof dispatch);
        console.log('userAgent:', navigator.userAgent);
        
        // Debugging del evento
        try {
            console.log('Intentando dispatch("logout")...');
            dispatch('logout');
            console.log('dispatch("logout") ejecutado');
        } catch (error) {
            console.error('Error en dispatch:', error);
        }
    }

    function handleSearch() {
        console.log('handleSearch() llamado con:', searchQuery);
        loadPacientes();
    }

    function handleDateChange() {
        console.log('handleDateChange() llamado con:', selectedDate);
        loadPacientes();
    }

    function handleRefresh() {
        console.log('handleRefresh() llamado');
        loadPacientes();
    }

    // Manejar eventos del CustomEvent de PatientList
    $: if (typeof window !== 'undefined') {
        window.addEventListener('retry', handleRefresh);
    }
</script>

{#if !showPatientsView}
    <DashboardView 
        {userName}
        onLogout={handleLogout}
        onShowPatients={handleShowPatients}
    />
{:else}
    <PatientView
        bind:searchQuery
        bind:selectedDate
        bind:pacientes
        bind:loading
        onBack={handleBackToDashboard}
        onLogout={handleLogout}
        onSearch={handleSearch}
        onDateChange={handleDateChange}
        onRefresh={handleRefresh}
    />
{/if}

<style>
    /* Estilos base heredados de componentes individuales */
</style>