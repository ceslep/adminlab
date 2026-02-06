<!-- src/lib/components/modals/PatientSearchModal.svelte -->
<script lang="ts">
  import { createEventDispatcher } from 'svelte';
  import { patients as patientsApi } from '$lib/api';
  import type { PatientWithExams, Exam } from '$lib/types';

  const dispatch = createEventDispatcher();

  let form = {
    identificacion: '',
    nombres: '',
    telefono: '',
    ciudad: '',
    entidad: '', // Assuming this could be a text input for now, or a dropdown later
    includeExams: true,
    onlyWithResults: false,
    limit: 50
  };

  let loadingPatients = false;
  let showResultsView = false;
  let patientSearchResults: PatientWithExams[] = [];
  let totalPatientsFound = 0;
  let errorMessage: string | null = null;

  // For expanding/collapsing exam lists within each patient card
  let examenesExpandidos: boolean[] = [];

  function closeModal() {
    dispatch('close');
  }

  async function buscarPacientes() {
    loadingPatients = true;
    errorMessage = null;
    try {
      // Basic validation: at least one field must be filled
      if (
        !form.identificacion &&
        !form.nombres &&
        !form.telefono &&
        !form.ciudad &&
        !form.entidad
      ) {
        errorMessage = 'Debe ingresar al menos un criterio de búsqueda.';
        loadingPatients = false;
        return;
      }

      const response = await patientsApi.searchPatientsWithExams(form);
      if (response.success) {
        patientSearchResults = response.pacientes;
        totalPatientsFound = response.total_pacientes;
        examenesExpandidos = new Array(patientSearchResults.length).fill(false); // Initialize all to collapsed
        showResultsView = true;
      } else {
        errorMessage = response.message || 'Error al buscar pacientes.';
        patientSearchResults = [];
        totalPatientsFound = 0;
      }
    } catch (error: any) {
      console.error('Error searching patients:', error);
      errorMessage = error.message || 'Error de conexión al buscar pacientes.';
    } finally {
      loadingPatients = false;
    }
  }

  function volverFormularioBusqueda() {
    showResultsView = false;
    patientSearchResults = [];
    totalPatientsFound = 0;
    errorMessage = null;
  }

  function exportarPacientesExcel() {
    console.log('Exportar pacientes a Excel:', patientSearchResults);
    alert('Funcionalidad de exportar a Excel no implementada.');
  }

  function toggleExamenExpandido(index: number) {
    examenesExpandidos[index] = !examenesExpandidos[index];
    examenesExpandidos = examenesExpandidos; // Trigger Svelte reactivity
  }

  function verExamenIndividual(examen: Exam) {
    console.log('Ver examen individual (desde modal de búsqueda de pacientes):', examen);
    // You might want to dispatch an event to the main layout to load this report
    alert('Funcionalidad de ver examen individual no implementada (se abriría en el visor principal).');
  }
</script>

<!-- Modal backdrop -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-[60]">
  <!-- Modal container -->
  <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full h-[85vh] flex flex-col border-4 border-green-500" role="dialog" aria-modal="true" aria-labelledby="patient-search-modal-title">
    {#if !showResultsView}
      <!-- Formulario de búsqueda -->
      <div class="flex flex-col h-full">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-6 rounded-t-2xl flex-shrink-0">
          <div class="flex justify-between items-center mb-4">
            <h3 id="patient-search-modal-title" class="text-xl font-bold flex items-center gap-2">
              <i class="bi bi-people-fill" aria-hidden="true"></i>
              Búsqueda de Pacientes
            </h3>
            <button on:click={closeModal} class="text-white hover:bg-white/20 p-2 rounded-lg transition" title="Cerrar">
              <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
          </div>
          <p class="text-green-100 text-sm">
            Busque pacientes por identificación, nombres, teléfono, ciudad o entidad
          </p>
        </div>
        <form on:submit|preventDefault={buscarPacientes} class="p-6 space-y-5 overflow-y-auto flex-1">
          <!-- Fila 1: Identificación y Nombres -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="identificacion-input" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                <i class="bi bi-card-text text-green-600" aria-hidden="true"></i>
                Identificación
              </label>
              <input
                id="identificacion-input"
                type="text"
                bind:value={form.identificacion}
                placeholder="Documento del paciente"
                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
              />
            </div>
            <div>
              <label for="nombres-input" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                <i class="bi bi-person text-green-600" aria-hidden="true"></i>
                Nombres Completos
              </label>
              <input
                id="nombres-input"
                type="text"
                bind:value={form.nombres}
                placeholder="Nombres y apellidos"
                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
              />
            </div>
          </div>
          <!-- Fila 2: Teléfono y Ciudad -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="telefono-input" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                <i class="bi bi-phone text-green-600" aria-hidden="true"></i>
                Teléfono
              </label>
              <input
                id="telefono-input"
                type="text"
                bind:value={form.telefono}
                placeholder="Número de teléfono"
                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
              />
            </div>
            <div>
              <label for="ciudad-input" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                <i class="bi bi-geo-alt text-green-600" aria-hidden="true"></i>
                Ciudad
              </label>
              <input
                id="ciudad-input"
                type="text"
                bind:value={form.ciudad}
                placeholder="Ciudad de residencia"
                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
              />
            </div>
          </div>
          <!-- Fila 3: Entidad y Opciones -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="entidad-search-input" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                <i class="bi bi-building text-green-600" aria-hidden="true"></i>
                Entidad
              </label>
              <input
                id="entidad-search-input"
                type="text"
                bind:value={form.entidad}
                placeholder="Entidad del paciente"
                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all"
              />
            </div>
            <div class="flex flex-col justify-end space-y-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  bind:checked={form.includeExams}
                  class="w-4 h-4 text-green-600 rounded focus:ring-green-500"
                />
                <span class="ml-2 text-sm text-slate-700">Incluir exámenes asociados</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  bind:checked={form.onlyWithResults}
                  class="w-4 h-4 text-green-600 rounded focus:ring-green-500"
                />
                <span class="ml-2 text-sm text-slate-700">Mostrar solo con resultados</span>
              </label>
            </div>
          </div>
          <!-- Error Message -->
          {#if errorMessage}
            <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm flex items-center gap-2" role="alert">
              <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
              {errorMessage}
            </div>
          {/if}
          <!-- Botones de acción -->
          <div class="flex gap-3 pt-4 border-t">
            <button
              type="button"
              on:click={closeModal}
              class="flex-1 px-4 py-3 border-2 border-slate-300 text-slate-700 rounded-xl font-bold hover:bg-slate-50 transition"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-bold transition shadow-lg shadow-green-200 flex items-center justify-center gap-2"
              disabled={loadingPatients}
            >
              {#if loadingPatients}
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Buscando...
              {:else}
                <i class="bi bi-search" aria-hidden="true"></i>
                BUSCAR PACIENTES
              {/if}
            </button>
          </div>
        </form>
      </div>
    {:else}
      <!-- Resultados de la búsqueda -->
      <div class="flex flex-col h-full">
        <div class="bg-gradient-to-r from-emerald-600 to-green-600 text-white p-6 rounded-t-2xl flex-shrink-0">
          <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
              <div class="bg-white/20 p-2 rounded-lg">
                <i class="bi bi-people-fill text-xl" aria-hidden="true"></i>
              </div>
              <div>
                <h3 class="text-xl font-bold">Resultados Encontrados</h3>
                <p class="text-emerald-100 text-sm">
                  {totalPatientsFound} pacientes encontrados
                </p>
              </div>
            </div>
            <div class="flex gap-2">
              <button
                on:click={exportarPacientesExcel}
                class="bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1"
              >
                <i class="bi bi-file-earmark-excel" aria-hidden="true"></i>
                EXCEL
              </button>
              <button
                on:click={volverFormularioBusqueda}
                class="bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1"
              >
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                VOLVER
              </button>
              <button on:click={closeModal} class="bg-white/20 hover:bg-white/30 p-2 rounded-lg transition" title="Cerrar">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>
        <!-- Contenido de resultados -->
        <div class="flex-1 overflow-y-auto p-4 scrollbar-thin">
          {#if loadingPatients}
            <div class="flex items-center justify-center h-full">
              <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-green-600"></div>
                <p class="mt-4 text-slate-600">Buscando pacientes...</p>
              </div>
            </div>
          {:else if patientSearchResults.length > 0}
            <div class="space-y-4 max-w-6xl mx-auto">
              {#each patientSearchResults as paciente, i (paciente.identificacion)}
                <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden patient-card">
                  <!-- Header del paciente -->
                  <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 border-b border-green-100">
                    <div class="flex justify-between items-start">
                      <div class="flex gap-3">
                        <div class="bg-green-100 w-10 h-10 rounded-full flex items-center justify-center">
                          <i class="bi bi-person-fill text-green-600" aria-hidden="true"></i>
                        </div>
                        <div>
                          <h4 class="font-bold text-slate-800 text-lg">{paciente.nombre_completo}</h4>
                          <div class="flex flex-wrap gap-2 mt-1">
                            <span class="chip bg-slate-100 text-slate-700">{paciente.identificacion}</span>
                            <span class="chip {paciente.genero === 'M' ? 'chip-genero-masculino' : 'chip-genero-femenino'}">{paciente.edad} años</span>
                            <span class="chip bg-indigo-100 text-indigo-700">{paciente.genero || 'G: N/A'}</span>
                            <span class="chip chip-telefono">{paciente.telefono || 'Sin teléfono'}</span>
                          </div>
                          <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-1 text-xs text-slate-600">
                            <div class="flex items-center gap-1">
                              <i class="bi bi-envelope text-green-600" aria-hidden="true"></i>
                              <span>{paciente.correo || 'Sin correo'}</span>
                            </div>
                            <div class="flex items-center gap-1">
                              <i class="bi bi-geo-alt text-green-600" aria-hidden="true"></i>
                              <span>{paciente.ciudad_residencia || 'N/A'} - {paciente.direccion_residencia || 'N/A'}</span>
                            </div>
                            <div class="flex items-center gap-1">
                              <i class="bi bi-building text-green-600" aria-hidden="true"></i>
                              <span>{paciente.entidad || 'Sin entidad'}</span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="flex gap-2">
                        <!-- Removed 'VER' button as it's not clear what it would do here -->
                      </div>
                    </div>
                  </div>
                  <!-- Información adicional -->
                  <div class="p-4">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
                      <div class="text-center">
                        <div class="text-lg font-bold text-slate-800">{paciente.total_visitas || 0}</div>
                        <div class="text-xs text-slate-500 uppercase">Visitas</div>
                      </div>
                      <div class="text-center">
                        <div class="text-lg font-bold text-slate-800">{paciente.total_examenes || 0}</div>
                        <div class="text-xs text-slate-500 uppercase">Exámenes</div>
                      </div>
                      <div class="text-center">
                        <div class="text-lg font-bold text-slate-800">{paciente.examenes_con_resultados || 0}</div>
                        <div class="text-xs text-slate-500 uppercase">Con resultados</div>
                      </div>
                      <div class="text-center">
                        <div class="text-sm font-bold text-slate-800">{paciente.ultima_visita || 'N/A'}</div>
                        <div class="text-xs text-slate-500 uppercase">Última visita</div>
                      </div>
                    </div>
                    <!-- Exámenes del paciente -->
                    {#if paciente.examenes && paciente.examenes.length > 0}
                      <button
                        on:click={() => toggleExamenExpandido(i)}
                        class="w-full bg-slate-50 hover:bg-slate-100 px-3 py-2 rounded-lg text-sm font-medium text-slate-700 transition flex items-center justify-between mb-2"
                      >
                        <span>Ver exámenes ({paciente.examenes.length})</span>
                        <i class="bi {examenesExpandidos[i] ? 'bi-chevron-up' : 'bi-chevron-down'}" aria-hidden="true"></i>
                      </button>
                      {#if examenesExpandidos[i]}
                        <div class="space-y-2">
                          {#each paciente.examenes as examen (examen.codexamen + '_' + examen.fecha)}
                            <div class="bg-slate-100 rounded-lg p-3 flex items-center justify-between">
                              <div class="flex-1">
                                <div class="font-medium text-slate-800 text-sm">{examen.nombre || `Examen #${examen.codexamen}`}</div>
                                <div class="text-xs text-slate-500">
                                  {examen.fecha} • {examen.entidad || 'Sin entidad'}
                                </div>
                              </div>
                              <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded-full {examen.estado === 'Completado' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                                  {examen.estado || 'Pendiente'}
                                </span>
                                <button
                                  on:click={() => verExamenIndividual(examen)}
                                  class="bg-indigo-600 hover:bg-indigo-700 text-white p-1 rounded transition-colors"
                                  title="Ver examen"
                                >
                                  <i class="bi bi-eye-fill text-xs" aria-hidden="true"></i>
                                </button>
                              </div>
                            </div>
                          {/each}
                        </div>
                      {/if}
                    {:else}
                      <div class="text-center text-slate-400 py-4">
                        <i class="bi bi-info-circle text-lg mb-1" aria-hidden="true"></i>
                        <p class="text-xs">No hay exámenes asociados o no se solicitaron.</p>
                      </div>
                    {/if}
                  </div>
                </div>
              {/each}
            </div>
          {:else if !loadingPatients && !errorMessage}
            <div class="h-full flex flex-col items-center justify-center p-8 text-center">
              <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-person-x text-3xl text-slate-400" aria-hidden="true"></i>
              </div>
              <h3 class="text-lg font-bold text-slate-700 mb-2">No se encontraron pacientes</h3>
              <p class="text-sm text-slate-500 mb-4">Pruebe con otros criterios de búsqueda.</p>
            </div>
          {/if}
        </div>
      </div>
    {/if}
  </div>
</div>

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
  .chip {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    line-height: 1rem;
    font-weight: 500;
  }
  .chip-genero-masculino {
    background-color: #dbeafe;
    color: #1d4ed8;
  }
  .chip-genero-femenino {
    background-color: #fce7f3;
    color: #be185d;
  }
  .chip-telefono {
    background-color: #dcfce7;
    color: #166534;
  }
</style>
