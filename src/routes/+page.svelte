<!-- src/routes/+page.svelte -->
<script lang="ts">
  import Header from '$lib/components/Header.svelte';
  import PatientSidebar from '$lib/components/PatientSidebar.svelte';
  import ReportViewer from '$lib/components/ReportViewer.svelte';
  import EntitiesModal from '$lib/components/modals/EntitiesModal.svelte';
  import PatientSearchModal from '$lib/components/modals/PatientSearchModal.svelte';

  // State for controlling modals
  let showEntitiesModal = false;
  let showPatientSearchModal = false;

  // State for ReportViewer
  let currentReportUrl: string | null = null;
  let currentReportName: string | null = null;

  // Function to load a report (passed to PatientSidebar)
  function loadReport(url: string, name: string) {
    currentReportUrl = url;
    currentReportName = name;
  }
</script>

<svelte:head>
  <title>AdminLab - Panel de Resultados</title>
</svelte:head>

<Header on:openEntitiesModal={() => (showEntitiesModal = true)} on:openPatientSearchModal={() => (showPatientSearchModal = true)} />

<div class="flex flex-1 overflow-hidden">
  <PatientSidebar on:loadReport={(e: CustomEvent<{ url: string; name: string }>) => loadReport(e.detail.url, e.detail.name)} />
  <ReportViewer reportUrl={currentReportUrl} reportName={currentReportName} on:closeReport={() => (currentReportUrl = null)} />
</div>

{#if showEntitiesModal}
  <EntitiesModal on:close={() => (showEntitiesModal = false)} />
{/if}

{#if showPatientSearchModal}
  <PatientSearchModal on:close={() => (showPatientSearchModal = false)} />
{/if}

<style>
  /* Any page-specific styles if necessary */
</style>
