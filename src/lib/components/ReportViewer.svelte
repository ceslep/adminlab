<!-- src/lib/components/ReportViewer.svelte -->
<script lang="ts">
  import { createEventDispatcher } from 'svelte';

  export let reportUrl: string | null = null;
  export let reportName: string | null = null;

  const dispatch = createEventDispatcher();

  function closeReport() {
    reportUrl = null;
    reportName = null;
    dispatch('closeReport');
  }

  function printFrame() {
    const iframe = document.getElementById('report-iframe') as HTMLIFrameElement;
    if (iframe && iframe.contentWindow) {
      iframe.contentWindow.focus();
      iframe.contentWindow.print();
    }
  }
</script>

<main class="flex-1 relative flex flex-col bg-slate-200">
  {#if !reportUrl}
    <!-- Default view when no report is selected -->
    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center">
      <!-- Logo and instructions matching PHP original -->
      <div class="w-32 h-32 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center mb-6 shadow-inner">
        <i class="bi bi-file-earmark-pdf text-6xl text-slate-400" aria-hidden="true"></i>
      </div>
      <h3 class="text-xl font-bold text-slate-700 mb-2">Visor de Resultados</h3>
      <p class="text-slate-500 max-w-md mx-auto mb-6">
        Seleccione un examen de la lista para visualizar e imprimir los resultados.
        También puede enviar los resultados por WhatsApp directamente al paciente.
      </p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-2xl mx-auto">
        <div class="bg-white p-4 rounded-xl border border-slate-200 text-center">
          <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="bi bi-eye-fill text-indigo-600" aria-hidden="true"></i>
          </div>
          <div class="text-sm font-bold text-slate-800 mb-1">Visualizar</div>
          <div class="text-xs text-slate-500">Ver resultados completos</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 text-center">
          <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="bi bi-printer-fill text-green-600" aria-hidden="true"></i>
          </div>
          <div class="text-sm font-bold text-slate-800 mb-1">Imprimir</div>
          <div class="text-xs text-slate-500">Generar copia física</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 text-center">
          <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mx-auto mb-3">
            <i class="bi bi-whatsapp text-amber-600" aria-hidden="true"></i>
          </div>
          <div class="text-sm font-bold text-slate-800 mb-1">Compartir</div>
          <div class="text-xs text-slate-500">Enviar por WhatsApp</div>
        </div>
      </div>
    </div>
  {:else}
    <!-- Report view -->
    <div class="h-full flex flex-col">
      <div class="bg-white p-3 border-b shadow-sm flex justify-between items-center flex-shrink-0">
        <button
          on:click={closeReport}
          class="md:hidden text-slate-600 hover:text-slate-800 hover:bg-slate-100 p-2 rounded-lg transition-colors"
          title="Cerrar reporte"
        >
          <i class="bi bi-arrow-left text-lg" aria-hidden="true"></i>
        </button>
        <div class="flex items-center gap-3">
          <div class="bg-indigo-100 p-2 rounded-lg">
            <i class="bi bi-file-earmark-text-fill text-indigo-600" aria-hidden="true"></i>
          </div>
          <div>
            <div class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Reporte de Exámenes</div>
            <div class="text-[10px] text-slate-500">{reportName || 'Cargando...'}</div>
          </div>
        </div>
        <div class="flex gap-2">
          <button
            on:click={printFrame}
            class="bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-md transition flex items-center gap-2"
          >
            <i class="bi bi-printer" aria-hidden="true"></i> IMPRIMIR
          </button>
        </div>
      </div>
      <iframe src={reportUrl} id="report-iframe" class="w-full flex-1 border-none bg-white" title="Vista previa del reporte"></iframe>
    </div>
  {/if}
</main>
