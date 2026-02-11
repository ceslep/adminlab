<script lang="ts">
    import { fade, fly, scale } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import { 
        Activity, 
        Users, 
        FileText, 
        TrendingUp, 
        Calendar, 
        BarChart3, 
        Heart, 
        Stethoscope, 
        Zap,
        AlertTriangle,
        CheckCircle,
        Clock,
        TrendingDown,
        Eye,
        Download
    } from 'lucide-svelte';
    
    import PremiumCard from './dashboard/shared/PremiumCard.svelte';
    import BentoGrid from './dashboard/shared/BentoGrid.svelte';
    import Button from './dashboard/shared/Button.svelte';
    import Badge from './dashboard/shared/Badge.svelte';
    import Avatar from './dashboard/shared/Avatar.svelte';
    
    export let userName: string = '';
    export let onLogout: () => void;
    export let onShowPatients: () => void;
    
    // Mock data for clinical lab dashboard
    const labStats = {
        totalPatients: 1247,
        todayTests: 89,
        criticalResults: 3,
        averageTAT: 24, // hours
        uptime: 99.9,
        monthlyGrowth: 12.5
    };
    
    const recentActivity = [
        { id: 1, type: 'critical', patient: 'María García', test: 'Hemoglobina Glicosilada', time: '2 min ago', status: 'alert' },
        { id: 2, type: 'result', patient: 'Juan Pérez', test: 'Perfil Lipídico', time: '15 min ago', status: 'completed' },
        { id: 3, type: 'pending', patient: 'Ana Martínez', test: 'Conteo Celular', time: '1 hour ago', status: 'pending' },
        { id: 4, type: 'approved', patient: 'Carlos López', test: 'Función Renal', time: '2 hours ago', status: 'approved' }
    ];
    
    const upcomingTasks = [
        { id: 1, title: 'Validar resultados críticos', priority: 'high', dueTime: '30 min', icon: AlertTriangle },
        { id: 2, title: 'Revisar control de calidad', priority: 'medium', dueTime: '2 hours', icon: CheckCircle },
        { id: 3, title: 'Actualizar inventario', priority: 'low', dueTime: 'EOD', icon: FileText }
    ];
    
    const performanceMetrics = {
        accuracy: 99.7,
        efficiency: 94.2,
        satisfaction: 4.8,
        compliance: 100
    };
    
    // Bento grid data
    $: bentoChildren = [
        {
            size: 'large',
            interactive: true,
            backgroundType: 'gradient',
            title: 'Gestión de Pacientes',
            subtitle: 'Acceso completo al sistema',
            description: 'Administre pacientes, resultados y seguimiento médico',
            onClick: onShowPatients,
            action: { text: 'Ir a Pacientes' },
            stats: [
                { value: labStats.totalPatients, label: 'Pacientes' },
                { value: labStats.todayTests, label: 'Análisis Hoy' }
            ]
        },
        {
            size: 'medium',
            backgroundType: 'gradient',
            title: 'Resultados Críticos',
            subtitle: 'Requieren atención',
            description: 'Resultados que necesitan validación inmediata',
            action: { text: 'Ver Alertas' },
            stats: [
                { value: labStats.criticalResults, label: 'Críticos' },
                { value: '89', label: 'Hoy' }
            ]
        },
        {
            size: 'medium',
            backgroundType: 'gradient',
            title: 'Control de Calidad',
            subtitle: 'Sistema operativo',
            description: 'Todos los sistemas funcionando correctamente',
            action: { text: 'Ver Detalles' }
        },
        {
            size: 'small',
            backgroundType: 'gradient',
            title: 'Tiempo Respuesta',
            stats: [{ value: `${labStats.averageTAT}h`, label: 'Promedio' }]
        },
        {
            size: 'small',
            backgroundType: 'gradient',
            title: 'Uptime',
            stats: [{ value: `${labStats.uptime}%`, label: 'Disponibilidad' }]
        },
        {
            size: 'wide',
            backgroundType: 'gradient',
            title: 'Salud del Sistema',
            subtitle: 'Monitoreo en tiempo real',
            description: 'Estado general: Óptimo'
        }
    ];
</script>

<div class="clinical-lab-dashboard">
    <!-- Header with Clinical Context -->
    <header 
        class="dashboard-header"
        in:fly={{ y: -20, duration: 600, easing: cubicOut }}
    >
        <div class="header-content">
            <div class="lab-info">
                <div class="lab-logo">
                    <Heart class="logo-icon" />
                </div>
                <div class="lab-details">
                    <h1 class="lab-title">AdminLab Clinical</h1>
                    <p class="lab-subtitle">Sistema de Gestión de Laboratorio Clínico</p>
                </div>
            </div>
            
            <div class="header-actions">
                <div class="user-info">
                    <Avatar name={userName} size="md" />
                    <div class="user-details">
                        <span class="user-name">{userName}</span>
                        <Badge text="Lab Manager" variant="emerald" size="sm" />
                    </div>
                </div>
                <Button variant="secondary" size="sm" onClick={onLogout}>
                    Cerrar Sesión
                </Button>
            </div>
        </div>
        
        <!-- Status Bar -->
        <div class="status-bar">
            <div class="status-item">
                <CheckCircle class="status-icon success" />
                <span class="status-text">Sistema Operativo</span>
            </div>
            <div class="status-item">
                <Clock class="status-icon info" />
                <span class="status-text">Última sincronización: 2 min ago</span>
            </div>
            <div class="status-item">
                <TrendingUp class="status-icon success" />
                <span class="status-text">Eficiencia: +12.5%</span>
            </div>
        </div>
    </header>

    <!-- Main Dashboard Content -->
    <main class="dashboard-main">
        <!-- Quick Stats -->
        <div 
            class="quick-stats"
            in:fly={{ y: 20, duration: 500, delay: 100, easing: cubicOut }}
        >
            <PremiumCard variant="glass" interactive hover>
                <div class="stat-card">
                    <div class="stat-icon-container primary">
                        <Users class="stat-icon" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{labStats.totalPatients}</div>
                        <div class="stat-label">Pacientes Totales</div>
                        <div class="stat-change positive">+{labStats.monthlyGrowth}%</div>
                    </div>
                </div>
            </PremiumCard>
            
            <PremiumCard variant="glass" interactive hover>
                <div class="stat-card">
                    <div class="stat-icon-container success">
                        <Activity class="stat-icon" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{labStats.todayTests}</div>
                        <div class="stat-label">Análisis Hoy</div>
                        <div class="stat-change positive">+8% vs ayer</div>
                    </div>
                </div>
            </PremiumCard>
            
            <PremiumCard variant="glass" interactive hover>
                <div class="stat-card">
                    <div class="stat-icon-container error">
                        <AlertTriangle class="stat-icon" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{labStats.criticalResults}</div>
                        <div class="stat-label">Resultados Críticos</div>
                        <div class="stat-change neutral">Requiere acción</div>
                    </div>
                </div>
            </PremiumCard>
            
            <PremiumCard variant="glass" interactive hover>
                <div class="stat-card">
                    <div class="stat-icon-container info">
                        <TrendingUp class="stat-icon" />
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{labStats.averageTAT}h</div>
                        <div class="stat-label">TAT Promedio</div>
                        <div class="stat-change positive">-2h vs mes</div>
                    </div>
                </div>
            </PremiumCard>
        </div>

        <!-- Bento Grid Main Layout -->
        <div 
            class="bento-section"
            in:fly={{ y: 20, duration: 500, delay: 200, easing: cubicOut }}
        >
            <BentoGrid children={bentoChildren} gap="md" />
        </div>

        <!-- Secondary Content Sections -->
        <div class="secondary-content">
            <!-- Recent Activity -->
            <section 
                class="activity-section"
                in:fly={{ x: -20, duration: 500, delay: 300, easing: cubicOut }}
            >
                <PremiumCard variant="glass" size="lg">
                    <div class="section-header">
                        <h3 class="section-title">Actividad Reciente</h3>
                        <Button variant="secondary" size="sm">
                            <Eye class="w-4 h-4" />
                            Ver Todo
                        </Button>
                    </div>
                    
                    <div class="activity-list">
                        {#each recentActivity as activity}
                            <div class="activity-item">
                                <div class="activity-icon-container {activity.status}">
                                    {#if activity.type === 'critical'}
                                        <AlertTriangle class="activity-icon" />
                                    {:else if activity.type === 'result'}
                                        <FileText class="activity-icon" />
                                    {:else if activity.type === 'pending'}
                                        <Clock class="activity-icon" />
                                    {:else}
                                        <CheckCircle class="activity-icon" />
                                    {/if}
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">{activity.patient}</div>
                                    <div class="activity-subtitle">{activity.test}</div>
                                </div>
                                <div class="activity-meta">
                                    <span class="activity-time">{activity.time}</span>
                                    <Badge 
                                        text={activity.status} 
                                        variant={
                                            activity.status === 'alert' ? 'rose' : 
                                            activity.status === 'completed' ? 'emerald' :
                                            activity.status === 'pending' ? 'amber' : 'indigo'
                                        } 
                                        size="sm" 
                                    />
                                </div>
                            </div>
                        {/each}
                    </div>
                </PremiumCard>
            </section>

            <!-- Performance Metrics -->
            <section 
                class="performance-section"
                in:fly={{ x: 20, duration: 500, delay: 400, easing: cubicOut }}
            >
                <PremiumCard variant="glass" size="lg">
                    <div class="section-header">
                        <h3 class="section-title">Métricas de Rendimiento</h3>
                        <Button variant="secondary" size="sm">
                            <Download class="w-4 h-4" />
                            Exportar
                        </Button>
                    </div>
                    
                    <div class="metrics-grid">
                        <div class="metric-card">
                            <div class="metric-header">
                                <span class="metric-label">Precisión</span>
                                <span class="metric-value">{performanceMetrics.accuracy}%</span>
                            </div>
                            <div class="metric-bar">
                                <div class="metric-fill" style="width: {performanceMetrics.accuracy}%"></div>
                            </div>
                        </div>
                        
                        <div class="metric-card">
                            <div class="metric-header">
                                <span class="metric-label">Eficiencia</span>
                                <span class="metric-value">{performanceMetrics.efficiency}%</span>
                            </div>
                            <div class="metric-bar">
                                <div class="metric-fill" style="width: {performanceMetrics.efficiency}%"></div>
                            </div>
                        </div>
                        
                        <div class="metric-card">
                            <div class="metric-header">
                                <span class="metric-label">Satisfacción</span>
                                <span class="metric-value">{performanceMetrics.satisfaction}/5</span>
                            </div>
                            <div class="metric-bar">
                                <div class="metric-fill" style="width: {performanceMetrics.satisfaction * 20}%"></div>
                            </div>
                        </div>
                        
                        <div class="metric-card">
                            <div class="metric-header">
                                <span class="metric-label">Cumplimiento</span>
                                <span class="metric-value">{performanceMetrics.compliance}%</span>
                            </div>
                            <div class="metric-bar">
                                <div class="metric-fill" style="width: {performanceMetrics.compliance}%"></div>
                            </div>
                        </div>
                    </div>
                </PremiumCard>
            </section>
        </div>
    </main>
</div>

<style>
    .clinical-lab-dashboard {
        min-height: 100vh;
        background: var(--gradient-hero);
        position: relative;
        overflow-x: hidden;
    }

    .dashboard-header {
        background: var(--color-surface-glass);
        backdrop-filter: var(--backdrop-blur-2xl);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: var(--spacing-6) 0;
        position: sticky;
        top: 0;
        z-index: var(--z-index-sticky);
    }

    .header-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 var(--spacing-8);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .lab-info {
        display: flex;
        align-items: center;
        gap: var(--spacing-4);
    }

    .lab-logo {
        width: 60px;
        height: 60px;
        background: var(--gradient-primary);
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        box-shadow: var(--shadow-lg);
    }

    .logo-icon {
        width: 32px;
        height: 32px;
    }

    .lab-details {
        display: flex;
        flex-direction: column;
    }

    .lab-title {
        font-size: var(--font-size-3xl);
        font-weight: var(--font-weight-extrabold);
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-1) 0;
        background: var(--gradient-text);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .lab-subtitle {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
        margin: 0;
        font-weight: var(--font-weight-medium);
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: var(--spacing-6);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: var(--spacing-3);
    }

    .user-details {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .user-name {
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-semibold);
        color: var(--color-text-primary);
    }

    .status-bar {
        max-width: 1400px;
        margin: var(--spacing-4) auto 0;
        padding: 0 var(--spacing-8);
        display: flex;
        gap: var(--spacing-8);
    }

    .status-item {
        display: flex;
        align-items: center;
        gap: var(--spacing-2);
        font-size: var(--font-size-xs);
        color: var(--color-text-secondary);
    }

    .status-icon {
        width: 14px;
        height: 14px;
    }

    .status-icon.success {
        color: var(--color-success-500);
    }

    .status-icon.info {
        color: var(--color-primary-500);
    }

    .dashboard-main {
        max-width: 1400px;
        margin: 0 auto;
        padding: var(--spacing-8);
        display: flex;
        flex-direction: column;
        gap: var(--spacing-8);
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: var(--spacing-6);
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: var(--spacing-4);
    }

    .stat-icon-container {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon-container.primary {
        background: var(--gradient-primary);
        color: white;
    }

    .stat-icon-container.success {
        background: var(--gradient-success);
        color: white;
    }

    .stat-icon-container.error {
        background: var(--gradient-error);
        color: white;
    }

    .stat-icon-container.info {
        background: var(--gradient-primary);
        color: white;
    }

    .stat-icon {
        width: 24px;
        height: 24px;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: var(--font-size-2xl);
        font-weight: var(--font-weight-extrabold);
        color: var(--color-text-primary);
        line-height: 1;
        margin-bottom: var(--spacing-1);
    }

    .stat-label {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
        margin-bottom: var(--spacing-1);
    }

    .stat-change {
        font-size: var(--font-size-xs);
        font-weight: var(--font-weight-semibold);
    }

    .stat-change.positive {
        color: var(--color-success-600);
    }

    .stat-change.negative {
        color: var(--color-error-600);
    }

    .stat-change.neutral {
        color: var(--color-text-tertiary);
    }

    .bento-section {
        margin-bottom: var(--spacing-8);
    }

    .secondary-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-8);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: var(--spacing-6);
    }

    .section-title {
        font-size: var(--font-size-xl);
        font-weight: var(--font-weight-bold);
        color: var(--color-text-primary);
        margin: 0;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-4);
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: var(--spacing-3);
        padding: var(--spacing-3);
        background: var(--color-neutral-50);
        border-radius: var(--radius-lg);
        transition: all var(--duration-normal) var(--ease-out);
    }

    .activity-item:hover {
        background: var(--color-neutral-100);
        transform: translateX(4px);
    }

    .activity-icon-container {
        width: 40px;
        height: 40px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .activity-icon-container.alert {
        background: var(--color-error-50);
        color: var(--color-error-600);
    }

    .activity-icon-container.completed {
        background: var(--color-success-50);
        color: var(--color-success-600);
    }

    .activity-icon-container.pending {
        background: var(--color-warning-50);
        color: var(--color-warning-600);
    }

    .activity-icon-container.approved {
        background: var(--color-primary-50);
        color: var(--color-primary-600);
    }

    .activity-icon {
        width: 20px;
        height: 20px;
    }

    .activity-content {
        flex: 1;
        min-width: 0;
    }

    .activity-title {
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-semibold);
        color: var(--color-text-primary);
        margin-bottom: var(--spacing-1);
    }

    .activity-subtitle {
        font-size: var(--font-size-xs);
        color: var(--color-text-secondary);
    }

    .activity-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: var(--spacing-1);
    }

    .activity-time {
        font-size: var(--font-size-xs);
        color: var(--color-text-tertiary);
    }

    .metrics-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-4);
    }

    .metric-card {
        padding: var(--spacing-4);
        background: var(--color-neutral-50);
        border-radius: var(--radius-lg);
    }

    .metric-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--spacing-3);
    }

    .metric-label {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
        font-weight: var(--font-weight-medium);
    }

    .metric-value {
        font-size: var(--font-size-lg);
        font-weight: var(--font-weight-bold);
        color: var(--color-text-primary);
    }

    .metric-bar {
        height: 8px;
        background: var(--color-neutral-200);
        border-radius: var(--radius-full);
        overflow: hidden;
    }

    .metric-fill {
        height: 100%;
        background: var(--gradient-primary);
        border-radius: var(--radius-full);
        transition: width var(--duration-slow) var(--ease-out);
    }

    @media (max-width: 1024px) {
        .secondary-content {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: var(--spacing-4);
            text-align: center;
        }

        .lab-info {
            flex-direction: column;
            text-align: center;
        }

        .user-details {
            align-items: center;
        }

        .status-bar {
            flex-direction: column;
            gap: var(--spacing-2);
        }

        .quick-stats {
            grid-template-columns: 1fr;
        }

        .dashboard-main {
            padding: var(--spacing-4);
        }
    }
</style>