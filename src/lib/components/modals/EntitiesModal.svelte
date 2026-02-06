<!-- src/lib/components/modals/EntitiesModal.svelte -->
<script lang="ts">
  import { createEventDispatcher, onMount } from 'svelte';
  import { entities as entitiesApi } from '$lib/api';
  import type { Entity, EntityReportResponse, Exam } from '$lib/types';

  const dispatch = createEventDispatcher();

  let availableEntities: Entity[] = [];
  let loadingEntities = false;

  let form = {
    entidad: '',
    fechaInicio: '',
    fechaFin: '',
    soloConResultados: false,
    agruparPorFecha: true
  };

  let loadingResults = false;
  let showResultsView = false;
  let results: EntityReportResponse | null = null;
  let errorMessage: string | null = null;

  // For mini result view within the modal
  let expandedExams: { [key: string]: boolean } = {};

  onMount(async () => {
    loadingEntities = true;
    try {
      availableEntities = await entitiesApi.getEntitiesList();
    } catch (error) {
      console.error('Error fetching entities:', error);
      // TODO: Show toast or alert for error
    } finally {
      loadingEntities = false;
    }

    // Set default dates to today and a month ago
    const today = new Date();
    form.fechaFin = today.toISOString().slice(0, 10);
    today.setMonth(today.getMonth() - 1);
    form.fechaInicio = today.toISOString().slice(0, 10);
  });

  async function consultarPorEntidades() {
    loadingResults = true;
    errorMessage = null;
    try {
      results = await entitiesApi.getReportByEntities({
        entity: form.entidad,
        startDate: form.fechaInicio,
        endDate: form.fechaFin,
        onlyWithResults: form.soloConResultados,
        groupByDate: form.agruparPorFecha
      });
      if (!results.success) {
        errorMessage = results.message || 'Error al consultar resultados.';
      } else {
        showResultsView = true;
      }
    } catch (error: any) {
      console.error('Error fetching entity reports:', error);
      errorMessage = error.message || 'Error de conexión al consultar.';
      results = null;
    } finally {
      loadingResults = false;
    }
  }

  function verExamenIndividual(examen: Exam) {
    // This would typically navigate to a report viewer or open another modal
    // For now, let's just log and close the modal
    console.log('Ver examen individual:', examen);
    // You might want to dispatch an event here to the parent to load the report
    // dispatch('loadIndividualExamReport', { exam });
    // For now, we just close the modal.
    closeModal();
  }

  function toggleResultadoMini(examen: Exam) {
    const key = `${examen.identificacion}_${examen.codexamen}_${examen.fecha_examen}`;
    expandedExams = { ...expandedExams, [key]: !expandedExams[key] };
  }

  function closeModal() {
    dispatch('close');
  }

  function exportarExcel() {
    // Logic to export to Excel (using a library like SheetJS/xlsx)
    console.log('Exportar a Excel:', results);
    alert('Funcionalidad de exportar a Excel no implementada.');
  }

  function imprimirResultsModal() {
    // Logic to print the results view directly
    console.log('Imprimir resultados modal:', results);
    alert('Funcionalidad de imprimir no implementada.');
  }
</script>

<!-- Modal backdrop -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
  <!-- Modal container -->
  <div class="bg-white rounded-2xl shadow-2xl max-w-6xl w-full h-[85vh] flex flex-col" role="dialog" aria-modal="true" aria-labelledby="entities-modal-title">
    {#if !showResultsView}
      <!-- Formulario de consulta -->
      <div class="flex flex-col h-full">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-6 rounded-t-2xl flex-shrink-0">
          <div class="flex justify-between items-center mb-4">
            <h3 id="entities-modal-title" class="text-xl font-bold flex items-center gap-2">
              <i class="bi bi-building" aria-hidden="true"></i>
              Consulta por Entidades
            </h3>
            <button on:click={closeModal} class="text-white hover:bg-white/20 p-2 rounded-lg transition" title="Cerrar">
              <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
          </div>
          <p class="text-purple-100 text-sm">
            Consulta resultados de exámenes por entidad (o todas) y rango de fechas
          </p>
        </div>
        <form on:submit|preventDefault={consultarPorEntidades} class="p-6 space-y-5 overflow-y-auto flex-1">
          <!-- Selección de Entidad -->
          <div>
            <label for="entidad-select" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
              <i class="bi bi-building text-purple-600" aria-hidden="true"></i>
              Entidad
            </label>
            <select
              id="entidad-select"
              bind:value={form.entidad}
              class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all"
            >
              <option value="">-- Todas las entidades --</option>
              {#if loadingEntities}
                <option disabled>Cargando entidades...</option>
              {:else}
                {#each availableEntities as entity (entity.id)}
                  <option value={entity.nombre}>{entity.nombre}</option>
                {/each}
              {/if}
            </select>
          </div>
          <!-- Rango de Fechas -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="fecha-inicio" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                <i class="bi bi-calendar-date text-purple-600" aria-hidden="true"></i>
                Fecha Inicio
              </label>
              <input
                id="fecha-inicio"
                type="date"
                bind:value={form.fechaInicio}
                required
                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all"
              />
            </div>
            <div>
              <label for="fecha-fin" class="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
                <i class="bi bi-calendar-date text-purple-600" aria-hidden="true"></i>
                Fecha Fin
              </label>
              <input
                id="fecha-fin"
                type="date"
                bind:value={form.fechaFin}
                required
                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all"
              />
            </div>
          </div>
          <!-- Opciones Adicionales -->
          <div class="bg-purple-50 p-4 rounded-xl border border-purple-100">
            <div class="flex items-center gap-2 mb-3">
              <i class="bi bi-funnel text-purple-600" aria-hidden="true"></i>
              <span class="text-sm font-semibold text-purple-800">Opciones de filtrado</span>
            </div>
            <div class="space-y-2">
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  bind:checked={form.soloConResultados}
                  class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500"
                />
                <span class="ml-2 text-sm text-slate-700">Mostrar solo exámenes con resultados</span>
              </label>
              <label class="flex items-center gap-2 cursor-pointer">
                <input
                  type="checkbox"
                  bind:checked={form.agruparPorFecha}
                  class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500"
                />
                <span class="ml-2 text-sm text-slate-700">Agrupar resultados por fecha</span>
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
          <!-- Botones -->
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
              class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 py-3 rounded-xl font-bold hover:from-purple-700 hover:to-indigo-700 transition shadow-lg flex items-center justify-center gap-2"
              disabled={loadingResults}
            >
              {#if loadingResults}
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Consultando...
              {:else}
                <i class="bi bi-search mr-2" aria-hidden="true"></i>
                Consultar
              {/if}
            </button>
          </div>
        </form>
      </div>
    {:else}
      <!-- Resultados de la consulta -->
      <div class="flex flex-col h-full">
        <!-- Header con botones -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-4 flex-shrink-0">
          <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
              <button on:click={() => (showResultsView = false)} class="bg-white/20 hover:bg-white/30 p-2 rounded-lg transition" title="Volver al formulario">
                <i class="bi bi-arrow-left text-lg" aria-hidden="true"></i>
              </button>
              <div>
                <div class="font-bold text-sm">Resultados por Entidad</div>
                <div class="text-xs opacity-80">
                  {form.fechaInicio} - {form.fechaFin}
                </div>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button
                on:click={exportarExcel}
                class="bg-green-600 hover:bg-green-700 px-3 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1"
              >
                <i class="bi bi-file-earmark-excel" aria-hidden="true"></i>
                EXCEL
              </button>
              <button
                on:click={imprimirResultsModal}
                class="bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg text-xs font-bold transition flex items-center gap-1"
              >
                <i class="bi bi-printer" aria-hidden="true"></i>
                IMPRIMIR
              </button>
              <button on:click={closeModal} class="bg-white/20 hover:bg-white/30 p-2 rounded-lg transition" title="Cerrar">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
              </button>
            </div>
          </div>
        </div>
        <!-- Contenido de resultados -->
        <div class="flex-1 overflow-hidden flex flex-col">
          <div class="max-w-6xl mx-auto w-full p-4 flex flex-col h-full">
            <!-- Resumen -->
            <div class="bg-white rounded-xl shadow-lg p-4 mb-4 border flex-shrink-0">
              <div class="grid grid-cols-4 gap-4">
                <div class="text-center">
                  <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="bi bi-calendar-date text-purple-600 text-sm" aria-hidden="true"></i>
                  </div>
                  <div class="text-lg font-bold text-slate-800">{results?.total_fechas || 0}</div>
                  <div class="text-xs text-slate-500 uppercase">Fechas</div>
                </div>
                <div class="text-center">
                  <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="bi bi-file-earmark-medical text-blue-600 text-sm" aria-hidden="true"></i>
                  </div>
                  <div class="text-lg font-bold text-slate-800">{results?.total_registros || 0}</div>
                  <div class="text-xs text-slate-500 uppercase">Exámenes</div>
                </div>
                <div class="text-center">
                  <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="bi bi-funnel text-green-600 text-sm" aria-hidden="true"></i>
                  </div>
                  <div class="text-lg font-bold text-slate-800 truncate">{form.entidad || 'Todas'}</div>
                  <div class="text-xs text-slate-500 uppercase">Entidad</div>
                </div>
                <div class="text-center">
                  <div class="bg-amber-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2">
                    <i class="bi bi-check-circle text-amber-600 text-sm" aria-hidden="true"></i>
                  </div>
                  <div class="text-lg font-bold text-slate-800">{form.soloConResultados ? 'Sí' : 'No'}</div>
                  <div class="text-xs text-slate-500 uppercase">Solo con resultados</div>
                </div>
              </div>
            </div>
            <!-- List of results -->
            <div class="flex-1 overflow-y-auto space-y-3 scrollbar-thin">
              {#if results && results.resultados && results.resultados.length > 0}
                {#each results.resultados as grupo (grupo.fecha)}
                  <div class="bg-white rounded-xl shadow-lg overflow-hidden border">
                    <!-- Date Header -->
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-3 border-b border-purple-100">
                      <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                          <i class="bi bi-calendar3 text-purple-600" aria-hidden="true"></i>
                          <div>
                            <div class="font-bold text-purple-800 text-sm">
                              {new Date(grupo.fecha + 'T00:00:00').toLocaleDateString('es-CO', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
                            </div>
                            <div class="text-xs text-purple-600">
                              {(grupo as any).examenes.length} exámenes
                            </div>
                          </div>
                        </div>
                        <button
                          on:click={() => alert('Imprimir día no implementado.')}
                          class="bg-purple-600 hover:bg-purple-700 text-white px-2 py-1 rounded-lg text-xs font-bold transition"
                        >
                          <i class="bi bi-printer mr-1" aria-hidden="true"></i>
                          Día
                        </button>
                      </div>
                    </div>
                    <!-- List of exams -->
                    <div class="p-3">
                      <div class="space-y-2">
                        {#each (grupo as any).examenes as examen (examen.identificacion + '_' + examen.codexamen + '_' + examen.fecha_examen)}
                          <div>
                            <div class="flex items-center justify-between p-2 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                              <div class="flex items-center gap-2 flex-1 min-w-0">
                                <div class="w-2 h-2 bg-purple-500 rounded-full flex-shrink-0"></div>
                                <div class="flex-1 min-w-0">
                                  <div class="font-medium text-slate-800 text-sm truncate" title={examen.paciente}>
                                    {examen.paciente}
                                  </div>
                                  <div class="text-xs text-slate-500 truncate">
                                    <span class="font-mono bg-slate-200 px-1 py-0.5 rounded text-xs">{examen.identificacion}</span>
                                    {#if examen.edad}
                                      <span class="ml-1"><i class="bi bi-calendar3" aria-hidden="true"></i> {Math.round(examen.edad)} años</span>
                                    {/if}
                                    {#if examen.genero}
                                      <span class="ml-1"><i class="bi bi-gender-ambiguous" aria-hidden="true"></i> {examen.genero}</span>
                                    {/if}
                                  </div>
                                </div>
                              </div>
                              <div class="flex items-center gap-1 flex-shrink-0 ml-2">
                                <div class="text-right mr-2 min-w-0">
                                  <div class="text-xs font-medium text-slate-700 truncate" title={examen.examen_nombre}>
                                    {examen.examen_nombre}
                                  </div>
                                  <div class="text-xs text-slate-500 truncate" title={examen.tipo_procedimiento || examen.examen_tipo}>
                                    {examen.tipo_procedimiento || examen.examen_tipo || 'N/A'}
                                  </div>
                                </div>
                                <button
                                  on:click={() => toggleResultadoMini(examen)}
                                  class="bg-green-600 hover:bg-green-700 text-white p-1 rounded transition-colors"
                                  class:bg-green-700={expandedExams[`${examen.identificacion}_${examen.examen_codigo}_${examen.fecha_examen}`]}
                                  title={expandedExams[`${examen.identificacion}_${examen.examen_codigo}_${examen.fecha_examen}`] ? 'Ocultar resultado' : 'Ver resultado'}
                                >
                                  <i class="bi bi-file-text text-xs" aria-hidden="true"></i>
                                </button>
                                <button
                                  on:click={() => verExamenIndividual(examen)}
                                  class="bg-indigo-600 hover:bg-indigo-700 text-white p-1 rounded transition-colors"
                                  title="Ver completo"
                                >
                                  <i class="bi bi-eye-fill text-xs" aria-hidden="true"></i>
                                </button>
                              </div>
                            </div>
                            <!-- Mini result view -->
                            {#if expandedExams[`${examen.identificacion}_${examen.examen_codigo}_${examen.fecha_examen}`]}
                              <div
                                class="ml-8 mt-1 p-2 bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg"
                              >
                                <div class="flex items-center gap-1 mb-1">
                                  <i class="bi bi-file-medical text-green-600 text-xs" aria-hidden="true"></i>
                                  <div class="font-semibold text-green-800 text-xs">Resultado:</div>
                                  <button
                                    on:click={() => toggleResultadoMini(examen)}
                                    class="ml-auto text-green-600 hover:text-green-800"
                                    title="Ocultar"
                                  >
                                    <i class="bi bi-x text-xs" aria-hidden="true"></i>
                                  </button>
                                </div>
                                <div class="text-xs space-y-1 text-green-700">
                                  <div class="flex justify-between">
                                    <span class="font-medium">Valor:</span>
                                    <span class="font-bold text-green-900">{examen.resultado || 'No disponible'}</span>
                                  </div>
                                  {#if examen.referencia && examen.referencia !== 'N/A'}
                                    <div class="flex justify-between">
                                      <span class="font-medium">Referencia:</span>
                                      <span>{examen.referencia}</span>
                                    </div>
                                  {/if}
                                  <div class="flex justify-between">
                                    <span class="font-medium">Estado:</span>
                                    <span>{examen.estado || 'Pendiente'}</span>
                                  </div>
                                </div>
                              </div>
                            {/if}
                          </div>
                        {/each}
                      </div>
                    </div>
                  </div>
                {/each}
              {:else if !loadingResults && !errorMessage}
                <div class="text-center py-8 text-slate-400">
                  <i class="bi bi-clipboard-x text-2xl mb-2 opacity-50" aria-hidden="true"></i>
                  <p class="text-sm font-medium">No se encontraron exámenes para los criterios seleccionados.</p>
                </div>
              {/if}
            </div>
          </div>
        </div>
      </div>
    {/if}
  </div>
</div>
