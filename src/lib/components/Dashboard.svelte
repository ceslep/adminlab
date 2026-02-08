<script lang="ts">
    import { user } from '../../stores/auth';
    // Importar el nuevo Dashboard modularizado
    import DashboardNew from './Dashboard.new.svelte';
    
    $: userName = $user?.nombre || 'Usuario';
</script>

<!-- Usar el nuevo Dashboard modularizado -->
<DashboardNew {userName} on:logout />