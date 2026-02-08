<script lang="ts">
    import { createEventDispatcher } from 'svelte';
    import { fly } from 'svelte/transition';
    import { getPacientes } from '../api';
    import type { Paciente } from '../types';

    const dispatch = createEventDispatcher<{ logout: undefined }>();

    export let userName: string = 'Usuario';

    let showPatientsView = false;
    let pacientes: Paciente[] = [];
    let loading = false;
    let searchQuery = '';
    let selectedDate = '2026-02-06'; // Fecha de desarrollo

    const dashboardCards = [
        { title: 'Pacientes', description: 'Gestión de información de pacientes.', color: 'indigo' },
        { title: 'Exámenes', description: 'Visualización y gestión de exámenes.', color: 'purple' },
        { title: 'Reportes', description: 'Generación de informes.', color: 'green' }
    ];

    async function loadPacientes() {
        loading = true;
        try {
            const response = await getPacientes(searchQuery, selectedDate);
            if (response.success && response.data) {
                pacientes = response.data;
            }
        } catch (error) {
            console.error('Error cargando pacientes:', error);
        } finally {
            loading = false;
        }
    }

    function handleShowPatients() {
        showPatientsView = true;
        loadPacientes();
    }

    function handleBackToDashboard() {
        showPatientsView = false;
    }

    function handleLogout(): void {
        dispatch('logout');
    }

    function formatDate(dateString: string): string {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', { 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric' 
        });
    }

    function getInitials(nombre: string): string {
        if (!nombre) return 'NA';
        const names = nombre.trim().split(' ');
        return names.map((n: string) => n.charAt(0).toUpperCase()).join('').substring(0, 2);
    }

    function formatAge(age: number): string {
        if (!age) return 'N/A';
        return `${age} años`;
    }

    function getGenderColor(genero: string): string {
        return genero?.toLowerCase() === 'm' || genero?.toLowerCase() === 'masculino' ? 'text-blue-600' : 'text-pink-600';
    }
</script>

{#if !showPatientsView}
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-lg sticky top-0 z-10 border-b">
            <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
                <h2 class="text-xl font-bold text-gray-900">Bienvenido, <span class="text-indigo-600 font-semibold">{userName}</span></h2>
                <button
                    on:click={handleLogout}
                    class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl"
                >
                    Cerrar sesión
                </button>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <div in:fly={{ y: 20, duration: 400, delay: 200 }} class="max-w-7xl mx-auto">
                <h3 class="text-3xl font-bold text-gray-900 mb-2">Panel de Control</h3>
                <p class="text-gray-600 mb-8">Aquí se mostrarán los datos y funcionalidades del laboratorio.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Dashboard Cards -->
                    {#each dashboardCards as card, i}
                        <div
                            in:fly={{ y: 20, duration: 400, delay: 300 + (i * 100) }}
                            class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 cursor-pointer border border-transparent hover:border-indigo-400"
                            on:click={card.title === 'Pacientes' ? handleShowPatients : undefined}
                        >
                            <div class={`bg-${card.color}-100 w-12 h-12 rounded-xl flex items-center justify-center mb-4`}>
                                <i class={`bi bi-${card.title === 'Pacientes' ? 'people' : 'person'} text-2xl text-${card.color}-600`}></i>
                            </div>
                            <h4 class="font-bold text-lg text-gray-900 mb-2">{card.title}</h4>
                            <p class="text-sm text-gray-600">{card.description}</p>
                        </div>
                    {/each}
                </div>
            </div>
        </main>
    </div>
{/if}

{#if showPatientsView}
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-lg sticky top-0 z-10 border-b">
            <div class="max-w-7xl mx-auto flex justify-between items-center p-4">
                <div class="flex items-center space-x-4">
                    <button
                        on:click={handleBackToDashboard}
                        class="flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200"
                    >
                        <i class="bi bi-arrow-left text-xl mr-2"></i>
                        <span class="font-semibold">Volver</span>
                    </button>
                    <h2 class="text-xl font-bold text-gray-900">Gestión de Pacientes</h2>
                </div>
                <button
                    on:click={handleLogout}
                    class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl"
                >
                    Cerrar sesión
                </button>
            </div>
        </header>

        <!-- Main Content - Pacientes -->
        <main class="flex-1 p-8">
            <div in:fly={{ y: 20, duration: 400, delay: 200 }} class="max-w-7xl mx-auto">
                <!-- Search and Filters -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <div class="flex flex-col md:flex-row gap-4 mb-6">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar pacientes</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    bind:value={searchQuery}
                                    placeholder="Buscar por identificación o nombre..."
                                    class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                    on:keyup={loadPacientes}
                                />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="bi bi-search"></i>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                            <input
                                type="date"
                                bind:value={selectedDate}
                                class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                                on:change={loadPacientes}
                            />
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-sm text-gray-600">
                        <span>Total: {pacientes.length} pacientes</span>
                        <button
                            on:click={loadPacientes}
                            class="text-indigo-600 hover:text-indigo-700 font-medium transition-colors duration-200"
                        >
                            <i class="bi bi-arrow-clockwise mr-1"></i>
                            Actualizar
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                {#if loading}
                    <div class="flex justify-center items-center py-12">
                        <div class="text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-r-2 border-indigo-600"></div>
                            <p class="mt-4 text-gray-600">Cargando pacientes...</p>
                        </div>
                    </div>
                {:else}
                    <!-- Patients Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {#each pacientes as paciente, i}
                            <div
                                in:fly={{ y: 20, duration: 400, delay: 300 + (i * 50) }}
                                class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-200 hover:border-indigo-400 p-6 cursor-pointer"
                            >
                                <!-- Patient Avatar -->
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                        {getInitials(paciente.nombre_completo)}
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="font-semibold text-gray-900">{paciente.nombre_completo}</h4>
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <span class="flex items-center">
                                                <i class="bi bi-telephone mr-1"></i>
                                                {paciente.telefono || 'N/A'}
                                            </span>
                                            <span class="flex items-center">
                                                <i class="bi bi-calendar3 mr-1"></i>
                                                {paciente.edad ? formatAge(paciente.edad) : 'N/A'}
                                            </span>
                                             <span class="flex items-center">
                                                 <i class="bi bi-geo-alt mr-1"></i>
                                                 {paciente.estado || 'N/A'}
                                             </span>
                                        </div>
                                        <div class="flex items-center mt-1">
                                             <span class={`inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${paciente.color_genero}`}>
                                                 <i class="bi bi-gender-{paciente.genero?.toLowerCase() === 'm' || paciente.genero?.toLowerCase() === 'masculino' ? 'male' : 'female'} mr-1"></i>
                                                 {paciente.genero || 'N/A'}
                                             </span>
                                             <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {paciente.color_estado} ml-2">
                                                 <i class="bi bi-activity mr-1"></i>
                                                 {paciente.estado}
                                             </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Info -->
                                <div class="grid grid-cols-1 gap-3 text-sm">
                                     <div class="flex items-center text-gray-600">
                                         <i class="bi bi-envelope mr-2 text-gray-400"></i>
                                         <span>{paciente.email || 'N/A'}</span>
                                     </div>
                                     <div class="flex items-center text-gray-600">
                                         <i class="bi bi-telephone mr-2 text-gray-400"></i>
                                         <span>{paciente.telefono || 'N/A'}</span>
                                     </div>
                                     <div class="flex items-center text-gray-600">
                                         <i class="bi bi-person-badge mr-2 text-gray-400"></i>
                                         <span>ID: {paciente.id_paciente}</span>
                                     </div>
                                     <div class="flex items-center text-gray-600">
                                         <i class="bi bi-calendar-check mr-2 text-gray-400"></i>
                                         <span>{formatDate(paciente.fecha_registro)}</span>
                                     </div>
                                     <div class="flex items-center text-gray-600">
                                         <i class="bi bi-file-medical mr-2 text-gray-400"></i>
                                         <span>{paciente.total_examenes || 0} exámenes</span>
                                     </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2 mt-4 pt-4 border-t border-gray-100">
                                    <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 hover:scale-105">
                                        <i class="bi bi-search mr-2"></i>
                                        Ver Detalles
                                    </button>
                                    <button class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 hover:scale-105">
                                        <i class="bi bi-file-medical mr-2"></i>
                                        Exámenes
                                    </button>
                                </div>
                            </div>
                        {/each}
                    </div>

                    <!-- Empty State -->
                    {#if pacientes.length === 0 && !loading}
                        <div class="text-center py-12">
                            <div class="text-gray-400 text-6xl mb-4">
                                <i class="bi bi-people"></i>
                            </div>
                            <p class="text-gray-600 text-lg font-medium">No se encontraron pacientes</p>
                            <p class="text-gray-500 mt-2">Intenta con otros criterios de búsqueda</p>
                        </div>
                    {/if}
                {/if}
            </div>
        </main>
    </div>
{/if}
