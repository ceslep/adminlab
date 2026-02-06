<!-- src/lib/components/PatientSidebar.svelte -->
<script lang="ts">
  import { onMount, createEventDispatcher } from 'svelte';
  import { patients as patientsApi } from '$lib/api';
  import type { Patient } from '$lib/types';

  const dispatch = createEventDispatcher();

  let selectedDate: string = new Date().toISOString().slice(0, 10); // Today's date YYYY-MM-DD
  let searchTerm: string = '';
  let searchAllDates: boolean = false;
  let patientList: Patient[] = [];
  let totalPatients: number = 0;
  let loadingPatients: boolean = false;
  let activePatientId: string | null = null; // To highlight selected patient

  $: isToday = selectedDate === new Date().toISOString().slice(0, 10);
  $: hasSearch = searchTerm.length > 0;

  async function fetchPatients() {
    loadingPatients = true;
    try {
      const { patients, totalPatients: fetchedTotal } = await patientsApi.getPatientsList(
        selectedDate,
        searchTerm,
        searchAllDates
      );
      patientList = patients;
      totalPatients = fetchedTotal;
    } catch (error) {
      console.error('Error fetching patients:', error);
      // TODO: Display user-friendly error message
      patientList = [];
      totalPatients = 0;
    } finally {
      loadingPatients = false;
    }
  }

  function toggleFechaInput() {
    // If searching all dates, clear selectedDate
    if (searchAllDates) {
      selectedDate = '';
    } else {
      selectedDate = new Date().toISOString().slice(0, 10); // Reset to today
    }
    fetchPatients();
  }

  function handleSearchSubmit() {
    fetchPatients();
  }

  function clearSearch() {
    searchTerm = '';
    fetchPatients();
  }

  function selectPatient(patient: Patient, examDate: string) {
    activePatientId = `${patient.identificacion}_${examDate}`;
    // Simulate loading report from PHP backend
    const urlTodo = `printphp/imprimirTodo.php?identificacion=${patient.identificacion}&fecha=${examDate}&nombres=${encodeURIComponent(patient.nombre_completo)}&edad=${patient.edad}&entidad=${encodeURIComponent(patient.entidad || '')}&info=Resultados&ver=1`;
    dispatch('loadReport', { url: urlTodo, name: `Reporte de ${patient.nombre_completo} (${examDate})` });
  }

  // Fetch patients on initial load and when dependencies change
  onMount(() => {
    fetchPatients();
  });

  // Re-fetch when selectedDate, searchTerm, or searchAllDates change
  $: selectedDate, searchTerm, searchAllDates, fetchPatients();

</script>

<aside class="w-full md:w-96 bg-white border-r flex flex-col shadow-xl z-10 flex-shrink-0">
  <div class="p-4 border-b bg-white space-y-4">
    <!-- Filters -->
    <form on:submit|preventDefault={handleSearchSubmit} class="space-y-4">
      <div class="grid grid-cols-1 gap-3">
        <!-- Date field (hidden if "search all dates" is active) -->
        <div class="relative" class:hidden={searchAllDates && hasSearch}>
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="bi bi-calendar3 text-slate-400" aria-hidden="true"></i>
          </div>
          <input
            type="date"
            bind:value={selectedDate}
            class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition"
            disabled={searchAllDates && hasSearch}
          />
        </div>
        <!-- Search field -->
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="bi bi-search text-slate-400" aria-hidden="true"></i>
          </div>
          <input
            type="text"
            bind:value={searchTerm}
            placeholder="Buscar por documento, nombres o apellidos..."
            class="w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition"
            autocomplete="off"
          />
          {#if searchTerm}
            <button
              type="button"
              on:click={clearSearch}
              class="absolute inset-y-0 right-0 pr-3 flex items-center text-red-500 hover:text-red-700 transition"
              title="Limpiar búsqueda"
            >
              <i class="bi bi-x-circle-fill" aria-hidden="true"></i>
            </button>
          {/if}
        </div>
        <!-- Checkbox for searching all dates -->
        <div class="flex items-center">
          <input
            type="checkbox"
            id="buscar-todas"
            bind:checked={searchAllDates}
            on:change={toggleFechaInput}
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded"
          />
          <label for="buscar-todas" class="ml-2 text-sm text-slate-700 cursor-pointer">
            Buscar en todas las fechas
          </label>
        </div>
        <button
          type="submit"
          class="w-full bg-indigo-600 text-white py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-md flex items-center justify-center gap-2"
          disabled={loadingPatients}
        >
          {#if loadingPatients}
            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Cargando...
          {:else}
            <i class="bi bi-funnel-fill" aria-hidden="true"></i>FILTRAR
          {/if}
        </button>
      </div>
      {#if hasSearch}
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
          <div class="flex justify-between items-center">
            <div class="text-sm font-medium text-amber-800">
              <i class="bi bi-search mr-2" aria-hidden="true"></i>Búsqueda: "<span class="font-bold">
                {searchTerm}
              </span>"
              {#if searchAllDates}
                <span class="ml-2 text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded">En todas las fechas</span>
              {/if}
            </div>
            <span class="bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
              {totalPatients} {totalPatients === 1 ? 'resultado' : 'resultados'}
            </span>
          </div>
          {#if totalPatients >= 100}
            <div class="mt-2 text-xs text-amber-700">
              <i class="bi bi-info-circle mr-1" aria-hidden="true"></i>
              Mostrando los primeros 100 resultados. Refina tu búsqueda para ver más.
            </div>
          {/if}
        </div>
      {/if}
    </form>
    {#if isToday && !searchAllDates && !hasSearch}
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-center gap-3">
        <div class="bg-blue-100 p-2 rounded-lg">
          <i class="bi bi-info-circle-fill text-blue-600 text-lg" aria-hidden="true"></i>
        </div>
        <div class="text-sm text-blue-800">
          <div class="font-bold">Hoy, {new Date(selectedDate).toLocaleDateString('es-CO')}</div>
          <div class="text-xs">Mostrando resultados del día actual</div>
        </div>
      </div>
    {:else if searchAllDates && hasSearch}
      <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 flex items-center gap-3">
        <div class="bg-purple-100 p-2 rounded-lg">
          <i class="bi bi-database-fill text-purple-600 text-lg" aria-hidden="true"></i>
        </div>
        <div class="text-sm text-purple-800">
          <div class="font-bold">Búsqueda completa</div>
          <div class="text-xs">Buscando en todas las fechas disponibles</div>
        </div>
      </div>
    {/if}
  </div>

  <!-- Patient list -->
  <div class="flex-1 overflow-y-auto p-4 space-y-4 scrollbar-thin">
    {#if loadingPatients}
      <div class="text-center py-8">
        <svg class="animate-spin h-8 w-8 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-2 text-slate-600 text-sm">Cargando pacientes...</p>
      </div>
    {:else if patientList.length > 0}
      {#each patientList as patient (patient.identificacion)}
        <!-- For simplicity, assume each patient entry represents a unique exam date or we group them differently later -->
        <!-- Currently, API mock returns unique patients, so using identif. directly -->
        {@const examDate = selectedDate || 'unknown_date'}
        {@const isActive = activePatientId === `${patient.identificacion}_${examDate}`}
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow" class:ring-2={isActive} class:ring-indigo-500={isActive} class:border-indigo-300={isActive}>
          <!-- Patient header -->
          <button
            on:click={() => selectPatient(patient, examDate)}
            class="w-full p-4 text-left flex justify-between items-center rounded-2xl hover:bg-slate-50/50 transition-colors"
          >
            <div class="flex-1 min-w-0">
              <div class="flex items-center gap-2 mb-1">
                <i class="bi {patient.genero === 'F' ? 'bi-gender-female text-pink-500' : patient.genero === 'M' ? 'bi-gender-male text-blue-500' : 'bi-gender-ambiguous text-gray-500'} text-sm" aria-hidden="true"></i>
                <div class="text-slate-900 font-bold text-sm truncate">
                  {patient.nombre_completo}
                </div>
              </div>
              <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-slate-500 uppercase tracking-wider">
                <span class="font-mono bg-slate-100 px-2 py-0.5 rounded">
                  {patient.identificacion}
                </span>
                {#if patient.edad}
                  <span><i class="bi bi-calendar3 mr-1" aria-hidden="true"></i> {Math.round(patient.edad)} años</span>
                {/if}
                {#if patient.telefono && patient.telefono !== '0'}
                  <span><i class="bi bi-telephone-fill mr-1" aria-hidden="true"></i> {patient.telefono}</span>
                {/if}
                {#if searchAllDates}
                  <span class="text-purple-600 font-medium">
                    <i class="bi bi-calendar-check mr-1" aria-hidden="true"></i>
                    {new Date(examDate).toLocaleDateString('es-CO')}
                  </span>
                {/if}
              </div>
            </div>
          </button>
        </div>
      {/each}
    {:else}
      <div class="h-full flex flex-col items-center justify-center p-8 text-center">
        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-4">
          <i class="bi bi-search text-3xl text-slate-400" aria-hidden="true"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-700 mb-2">No se encontraron resultados</h3>
        {#if hasSearch}
          <p class="text-sm text-slate-500 mb-4">
            para la búsqueda: "<span class="font-mono bg-slate-100 px-2 py-1 rounded">{searchTerm}</span>"
          </p>
          <button
            on:click={clearSearch}
            class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium px-4 py-2 border border-indigo-200 rounded-xl hover:bg-indigo-50 transition-colors"
          >
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            Ver todos los pacientes
          </button>
        {:else}
          <p class="text-sm text-slate-500 mb-4">
            No hay pacientes con exámenes para la fecha <span class="font-bold">
              {new Date(selectedDate).toLocaleDateString('es-CO')}
            </span>
          </p>
          <div class="space-x-3">
            <button
              on:click={() => { selectedDate = new Date().toISOString().slice(0, 10); searchTerm = ''; searchAllDates = false; }}
              class="inline-flex items-center gap-2 text-sm bg-indigo-600 text-white hover:bg-indigo-700 font-medium px-4 py-2 rounded-xl transition-colors"
            >
              <i class="bi bi-calendar3" aria-hidden="true"></i>
              Ver hoy
            </button>
            <button
              on:click={() => { selectedDate = new Date(new Date().setDate(new Date().getDate() - 1)).toISOString().slice(0, 10); searchTerm = ''; searchAllDates = false; }}
              class="inline-flex items-center gap-2 text-sm bg-slate-600 text-white hover:bg-slate-700 font-medium px-4 py-2 rounded-xl transition-colors"
            >
              <i class="bi bi-arrow-left" aria-hidden="true"></i>
              Ver ayer
            </button>
          </div>
        {/if}
      </div>
    {/if}
  </div>
</aside>

<style>
  .scrollbar-thin::-webkit-scrollbar {
    width: 4px;
    height: 4px;
  }
  .scrollbar-thin::-webkit-scrollbar-track {
    background: #f1f5f9;
  }
  .scrollbar-thin::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 2px;
  }
  .scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }
</style>
