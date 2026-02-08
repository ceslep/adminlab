<script lang="ts">
    import { createEventDispatcher } from 'svelte';
    
    const dispatch = createEventDispatcher<{ logout: undefined }>();

    export let userName: string = 'Usuario';

    // Estado
    let showPatientsView = false;
    let pacientes: any[] = [];
    let loading = false;
    let searchQuery = '';
    let selectedDate = '2026-02-06';

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
    function loadPacientes() {
        console.log('loadPacientes() llamado');
        loading = true;
        
        // Simular llamada API
        setTimeout(() => {
            pacientes = mockPacientes;
            loading = false;
            console.log('Pacientes cargados:', pacientes.length);
        }, 1000);
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
        console.log('handleLogout() llamado');
        dispatch('logout');
    }

    function handleSearch() {
        console.log('handleSearch() llamado con:', searchQuery);
        // Filtrar pacientes
        if (searchQuery.trim() === '') {
            pacientes = mockPacientes;
        } else {
            const search = searchQuery.toLowerCase();
            pacientes = mockPacientes.filter(p => 
                p.nombre_completo.toLowerCase().includes(search) ||
                p.telefono.includes(search) ||
                p.email.toLowerCase().includes(search) ||
                p.identificacion.includes(search)
            );
        }
    }

    function formatDate(dateString: string): string {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES');
    }
</script>

<style>
    /* Estilos básicos para evitar conflictos */
    main {
        min-height: 100vh;
        background: #f8fafc;
    }
    
    .header {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px);
        border-bottom: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }
    
    .btn {
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2563eb;
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
    }
    
    .grid {
        display: grid;
        gap: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 1024px) {
        .grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    .patient-card {
        transition: all 0.3s;
    }
    
    .patient-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    .input {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    
    .input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .loading {
        display: inline-block;
        width: 1.5rem;
        height: 1.5rem;
        border: 3px solid #f3f4f6;
        border-top: 3px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<!-- Vista Principal: Dashboard -->
{#if !showPatientsView}
    <div>
        <header class="header">
            <div class="container" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0;">
                <h1 style="margin: 0; font-size: 1.5rem; color: #1f2937;">
                    👋 Panel de Control - Bienvenido, <span style="color: #3b82f6; font-weight: 600;">{userName}</span>
                </h1>
                <button class="btn btn-secondary" on:click={handleLogout}>
                    Cerrar sesión
                </button>
            </div>
        </header>

        <main class="container">
            <div style="text-align: center; margin-bottom: 3rem;">
                <h2 style="margin-bottom: 1rem;">Gestión del Laboratorio</h2>
                <p style="color: #6b7280;">Selecciona una opción para gestionar el sistema</p>
            </div>

            <!-- Tarjetas de funcionalidad -->
            <div class="grid">
                <!-- Tarjeta de Pacientes -->
                <div class="card" style="padding: 2rem; text-align: center;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                    <h3 style="margin-bottom: 0.5rem; color: #1f2937;">Pacientes</h3>
                    <p style="color: #6b7280; margin-bottom: 1.5rem;">Gestión completa de pacientes y sus datos</p>
                    <button class="btn btn-primary" style="width: 100%;" on:click={handleShowPatients}>
                        <span style="margin-right: 0.5rem;">👥</span>
                        Gestionar Pacientes
                    </button>
                </div>

                <!-- Tarjeta de Exámenes -->
                <div class="card" style="padding: 2rem; text-align: center; opacity: 0.7;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">🔬</div>
                    <h3 style="margin-bottom: 0.5rem; color: #1f2937;">Exámenes</h3>
                    <p style="color: #6b7280; margin-bottom: 1.5rem;">Visualización y gestión de resultados</p>
                    <button class="btn" disabled style="width: 100%; cursor: not-allowed;">
                        <span style="margin-right: 0.5rem;">🔬</span>
                        Próximamente
                    </button>
                </div>

                <!-- Tarjeta de Reportes -->
                <div class="card" style="padding: 2rem; text-align: center; opacity: 0.7;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📊</div>
                    <h3 style="margin-bottom: 0.5rem; color: #1f2937;">Reportes</h3>
                    <p style="color: #6b7280; margin-bottom: 1.5rem;">Generación de informes y estadísticas</p>
                    <button class="btn" disabled style="width: 100%; cursor: not-allowed;">
                        <span style="margin-right: 0.5rem;">📊</span>
                        Próximamente
                    </button>
                </div>
            </div>
        </main>
    </div>
{/if}

<!-- Vista de Pacientes -->
{#if showPatientsView}
    <div>
        <header class="header">
            <div class="container" style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 0;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="btn btn-secondary" on:click={handleBackToDashboard} style="display: flex; align-items: center;">
                        <span style="margin-right: 0.5rem;">←</span>
                        Volver
                    </button>
                    <h1 style="margin: 0; font-size: 1.5rem; color: #1f2937;">
                        👥 Gestión de Pacientes
                    </h1>
                </div>
                <button class="btn btn-secondary" on:click={handleLogout}>
                    Cerrar sesión
                </button>
            </div>
        </header>

        <main class="container">
            <!-- Filtros -->
            <div class="card" style="margin-bottom: 2rem; padding: 1.5rem;">
                <div style="display: flex; gap: 1rem; align-items: end; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Buscar pacientes</label>
                        <input 
                            type="text" 
                            bind:value={searchQuery}
                            placeholder="Buscar por nombre, teléfono, email o identificación..."
                            class="input"
                            style="width: 100%;"
                            on:input={handleSearch}
                        />
                    </div>
                    <div>
                        <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Fecha</label>
                        <input 
                            type="date" 
                            bind:value={selectedDate}
                            class="input"
                            on:change={loadPacientes}
                        />
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; color: #6b7280;">
                    <span><strong>Total:</strong> {pacientes.length} pacientes</span>
                    <button class="btn btn-success" on:click={loadPacientes}>
                        <span style="margin-right: 0.5rem;">🔄</span>
                        Actualizar
                    </button>
                </div>
            </div>

            <!-- Loading State -->
            {#if loading}
                <div style="display: flex; justify-content: center; align-items: center; padding: 4rem;">
                    <div class="loading"></div>
                    <p style="margin-left: 1rem; color: #6b7280;">Cargando pacientes...</p>
                </div>
            {:else}
                <!-- Grid de Pacientes -->
                <div class="grid">
                    {#each pacientes as paciente (paciente.id_paciente)}
                        <div class="patient-card card" style="padding: 1.5rem; cursor: pointer;">
                            <!-- Header -->
                            <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                                <div style="width: 3rem; height: 3rem; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                                    {paciente.nombre_completo.substring(0, 2).toUpperCase()}
                                </div>
                                <div style="margin-left: 1rem; flex: 1;">
                                    <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937; font-weight: 600;">{paciente.nombre_completo}</h4>
                                    <p style="margin: 0.25rem 0; color: #6b7280; font-size: 0.875rem;">{paciente.telefono}</p>
                                </div>
                            </div>

                            <!-- Badges -->
                            <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">
                                <span class="badge" style="background: #dbeafe; color: #1e40af;">{paciente.genero}</span>
                                <span class="badge" style="background: #10b981; color: white;">{paciente.estado}</span>
                                <span class="badge" style="background: #8b5cf6; color: white;">{paciente.entidad}</span>
                                <span class="badge" style="background: #fef3c7; color: #92400e;">{paciente.total_examenes} exámenes</span>
                            </div>

                            <!-- Info del paciente -->
                            <div style="font-size: 0.875rem; color: #374151; margin-bottom: 1rem;">
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                                    <div><strong>ID:</strong> {paciente.identificacion}</div>
                                    <div><strong>Email:</strong> {paciente.email}</div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div style="display: flex; gap: 0.5rem;">
                                <button class="btn btn-primary" style="flex: 1;">
                                    <span style="margin-right: 0.5rem;">👁</span>
                                    Ver Detalles
                                </button>
                                <button class="btn btn-success" style="flex: 1;">
                                    <span style="margin-right: 0.5rem;">🧪</span>
                                    Exámenes
                                </button>
                            </div>
                        </div>
                    {/each}
                </div>

                <!-- Empty State -->
                {#if pacientes.length === 0}
                    <div style="text-align: center; padding: 4rem;">
                        <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
                        <h3 style="color: #6b7280; font-size: 1.25rem; margin-bottom: 0.5rem;">No se encontraron pacientes</h3>
                        <p style="color: #9ca3af;">Intenta con otros criterios de búsqueda</p>
                    </div>
                {/if}
            {/if}
        </main>
    </div>
{/if}