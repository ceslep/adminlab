<!-- src/lib/components/Header.svelte -->
<script lang="ts">
  import { authStatus, labConfig, logout } from '$lib/stores/auth';
  import { createEventDispatcher } from 'svelte';

  const dispatch = createEventDispatcher();

  function handleLogout() {
    logout();
  }

  function openEntitiesModal() {
    dispatch('openEntitiesModal');
  }

  function openPatientSearchModal() {
    dispatch('openPatientSearchModal');
  }
</script>

<header class="bg-gradient-to-r from-indigo-800 to-purple-800 text-white p-3 shadow-lg flex-shrink-0">
  <div class="max-w-7xl mx-auto flex justify-between items-center">
    <div class="flex items-center gap-3">
      {#if $labConfig?.urlLogoLaboratorio}
        <img src={$labConfig.urlLogoLaboratorio} alt="Logo" class="h-8 w-8 object-contain" />
      {:else}
        <div class="h-8 w-8 bg-white/20 rounded-lg flex items-center justify-center">
          <i class="bi bi-droplet text-xl" aria-hidden="true"></i>
        </div>
      {/if}
      <div>
        <div class="font-bold text-sm uppercase tracking-wider">
          {$labConfig?.nombreLaboratorio || 'Laboratorio Clínico'}
        </div>
        <div class="text-xs opacity-80">Panel de Resultados</div>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <button
        on:click={openEntitiesModal}
        class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 shadow-md"
        title="Consultar por entidades"
      >
        <i class="bi bi-building" aria-hidden="true"></i>
        <span class="hidden sm:inline">ENTIDADES</span>
      </button>
      <button
        on:click={openPatientSearchModal}
        class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 shadow-md"
        title="Buscar pacientes con resultados"
      >
        <i class="bi bi-people-fill" aria-hidden="true"></i>
        <span class="hidden sm:inline">PACIENTES</span>
      </button>
      <div class="hidden md:flex items-center gap-3 text-xs">
        <div class="bg-white/20 px-3 py-1 rounded-full">
          <i class="bi bi-person-fill mr-1" aria-hidden="true"></i>
          {$authStatus.userName || 'Guest'}
        </div>
        <div class="bg-white/20 px-3 py-1 rounded-full">
          <i class="bi bi-calendar3 mr-1" aria-hidden="true"></i>
          <!-- This would typically show current date or selected filter date. For now, static or from state. -->
          Hoy
        </div>
        <div class="bg-green-500/20 px-3 py-1 rounded-full">
          <i class="bi bi-people-fill mr-1" aria-hidden="true"></i>
          0 pacientes
        </div>
      </div>
      <button
        on:click={handleLogout}
        class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2 shadow-md"
        title="Cerrar sesión"
      >
        <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
        <span class="hidden sm:inline">SALIR</span>
      </button>
    </div>
  </div>
</header>
