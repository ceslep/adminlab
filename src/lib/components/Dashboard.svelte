<script lang="ts">
    import { createEventDispatcher } from 'svelte';
    import { fly } from 'svelte/transition';

    const dispatch = createEventDispatcher<{ logout: undefined }>();

    export let userName: string = 'Usuario';

    let showPatientsView = false;
    let pacientes: any[] = [];
    let loading = false;
    let searchQuery = '';
    let selectedDate = '2026-02-06';

    const dashboardCards = [
        { title: 'Pacientes', description: 'Gestión de información de pacientes.' },
        { title: 'Exámenes', description: 'Visualización y gestión de exámenes.' },
        { title: 'Reportes', description: 'Generación de informes.' }
    ];

    async function loadPacientes() {
        console.log('loadPacients() llamado');
        loading = true;
        try {
            await new Promise(resolve => setTimeout(resolve, 1000));
            pacientes = [
                { nombre_completo: 'Test Patient', telefono: '123456789' }
            ];
            console.log('Pacientes cargados:', pacientes.length);
        } catch (error) {
            console.error('Error cargando pacientes:', error);
        } finally {
            loading = false;
        }
    }

    function handleShowPatients() {
        console.log('handleShowPatients llamado');
        showPatientsView = true;
        loadPacientes();
    }

    function handleBackToDashboard() {
        showPatientsView = false;
    }

    function testFunction() {
        console.log('TEST FUNCTION - Svelte funciona!');
        alert('Test Function ejecutada!');
    }

    function handleLogout(): void {
        dispatch('logout');
    }
</script>

{#if !showPatientsView}
    <div style="min-height: 100vh; background: #f9fafb; padding: 2rem;">
        <header style="background: white; padding: 1rem; margin-bottom: 2rem; border-radius: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h2>Bienvenido, <span style="color: #4f46e5;">{userName}</span></h2>
            <button on:click={handleLogout} style="background: #dc2626; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer;">Cerrar sesión</button>
        </header>

        <main>
            <h1 style="margin-bottom: 1rem;">Panel de Control</h1>
            <p style="margin-bottom: 2rem;">Aquí se mostrarán los datos del laboratorio.</p>

            <!-- Botón de prueba -->
            <div style="background: #fef3c7; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem;">
                <h3>🧪 Botón de Prueba</h3>
                <button on:click={testFunction} style="background: #dc2626; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.25rem; cursor: pointer; font-weight: bold;">Click para probar Svelte</button>
            </div>

            <!-- Tarjetas -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
                {#each dashboardCards as card}
                    <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #e5e7eb;">
                        <h3 style="margin-bottom: 0.5rem;">{card.title}</h3>
                        <p style="color: #6b7280; margin-bottom: 1rem;">{card.description}</p>
                        
                        {#if card.title === 'Pacientes'}
                            <div style="background: #f3f4f6; padding: 0.5rem; border-radius: 0.25rem; margin-bottom: 0.5rem; font-size: 0.875rem;">
                                Debug: showPatientsView = {showPatientsView}
                            </div>
                            <button 
                                on:click={handleShowPatients}
                                style="background: #4f46e5; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; width: 100%; font-size: 14px;"
                            >
                                👥 Gestionar Pacientes
                            </button>
                        {:else}
                            <button disabled style="background: #d1d5db; color: #6b7280; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: not-allowed; width: 100%;">
                                ⚙️ Próximamente
                            </button>
                        {/if}
                    </div>
                {/each}
            </div>
        </main>
    </div>
{:else}
    <div style="min-height: 100vh; background: #f9fafb; padding: 2rem;">
        <header style="background: white; padding: 1rem; margin-bottom: 2rem; border-radius: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <button on:click={handleBackToDashboard} style="margin-right: 1rem; background: none; border: none; cursor: pointer; font-size: 1.2rem;">←</button>
                <h2 style="display: inline;">Gestión de Pacientes</h2>
            </div>
            <button on:click={handleLogout} style="background: #dc2626; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer;">Cerrar sesión</button>
        </header>

        <main>
            <h1>✅ Vista de Pacientes Funciona!</h1>
            <p>showPatientsView: {showPatientsView}</p>
            <p>Total pacientes: {pacientes.length}</p>
            <p>✅ El botón de "Gestionar Pacientes" funciona correctamente.</p>

            <div style="margin-top: 2rem;">
                <h3>Pacientes cargados:</h3>
                {#each pacientes as paciente}
                    <div style="background: white; padding: 1rem; margin: 0.5rem 0; border-radius: 0.25rem; border: 1px solid #e5e7eb;">
                        <strong>{paciente.nombre_completo}</strong> - {paciente.telefono}
                    </div>
                {/each}
            </div>

            <button on:click={handleBackToDashboard} style="background: #6b7280; color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.25rem; cursor: pointer; margin-top: 2rem;">Volver al Dashboard</button>
        </main>
    </div>
{/if}