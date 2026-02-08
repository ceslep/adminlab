<script lang="ts">
    import FeatureCard from './FeatureCard.svelte';
    import DashboardHeader from './DashboardHeader.svelte';
    
    export let userName: string = '';
    export let onLogout: () => void;
    export let onShowPatients: () => void;
</script>

<div class="dashboard-view">
    <DashboardHeader 
        title="Panel de Control"
        subtitle="Gestión del Laboratorio"
        {userName}
        {onLogout}
        showBackButton={false}
        onBack={() => {}}
    />

    <main class="dashboard-main">
        <div class="dashboard-content">
            <div class="dashboard-grid">
                <FeatureCard
                    icon="👥"
                    title="Pacientes"
                    description="Gestión completa de pacientes y sus datos"
                    buttonText="Gestionar Pacientes"
                    onClick={onShowPatients}
                    variant="primary"
                />

                <FeatureCard
                    icon="🔬"
                    title="Exámenes"
                    description="Visualización y gestión de resultados"
                    buttonText="Próximamente"
                    disabled={true}
                    variant="secondary"
                    onClick={() => {}}
                />

                <FeatureCard
                    icon="📊"
                    title="Reportes"
                    description="Generación de informes y estadísticas"
                    buttonText="Próximamente"
                    disabled={true}
                    variant="secondary"
                    onClick={() => {}}
                />
            </div>
        </div>
    </main>
</div>

<style>
    .dashboard-view {
        min-height: 100vh;
        background: #f8fafc;
    }
    
    .dashboard-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .dashboard-content {
        text-align: center;
        margin-bottom: 3rem;
    }
    

    
    .dashboard-grid {
        display: grid;
        gap: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>