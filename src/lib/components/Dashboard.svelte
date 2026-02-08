<script lang="ts">
    import { createEventDispatcher } from 'svelte';
    import { fly } from 'svelte/transition';

    const dispatch = createEventDispatcher<{ logout: undefined }>();

    export let userName: string = 'Usuario';

    const dashboardCards = [
        { title: 'Pacientes', description: 'Gestión de información de pacientes.', color: 'indigo' },
        { title: 'Exámenes', description: 'Visualización y gestión de exámenes.', color: 'purple' },
        { title: 'Reportes', description: 'Generación de informes.', color: 'green' }
    ];

    function handleLogout(): void {
        dispatch('logout');
    }
</script>

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
                <!-- Placeholder Cards -->
                {#each dashboardCards as card, i}
                    <div
                        in:fly={{ y: 20, duration: 400, delay: 300 + (i * 100) }}
                        class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 cursor-pointer border border-transparent hover:border-indigo-400"
                    >
                        <div class={`bg-${card.color}-100 w-12 h-12 rounded-xl flex items-center justify-center mb-4`}>
                            <!-- You can add specific icons here based on card.title -->
                            <i class={`bi bi-person text-2xl text-${card.color}-600`}></i>
                        </div>
                        <h4 class="font-bold text-lg text-gray-900 mb-2">{card.title}</h4>
                        <p class="text-sm text-gray-600">{card.description}</p>
                    </div>
                {/each}
            </div>
        </div>
    </main>
</div>
