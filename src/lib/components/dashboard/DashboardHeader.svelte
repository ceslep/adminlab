<script lang="ts">
    import Button from './shared/Button.svelte';
    
    export let title: string = '';
    export let subtitle: string = '';
    export let showBackButton: boolean = false;
    export let onBack: () => void;
    export let onLogout: () => void;
    export let userName: string | undefined = undefined;
</script>

<header class="dashboard-header">
    <div class="header-content">
        <div class="header-left">
            {#if showBackButton}
                <Button 
                    variant="secondary" 
                    size="sm" 
                    icon="←"
                    onClick={onBack}
                >
                    Volver
                </Button>
            {/if}
            <div class="header-title">
                <h1>{title}</h1>
                {#if subtitle}
                    <p class="subtitle">{subtitle}</p>
                {/if}
                {#if userName && !showBackButton}
                    <p class="subtitle">
                        Bienvenido, <span class="user-name">{userName}</span>
                    </p>
                {/if}
            </div>
        </div>
        <div class="header-right">
            <Button 
                variant="secondary" 
                size="sm" 
                onClick={onLogout}
            >
                Cerrar sesión
            </Button>
        </div>
    </div>
</header>

<style>
    .dashboard-header {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px);
        border-bottom: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .header-title h1 {
        margin: 0;
        font-size: 1.5rem;
        color: #1f2937;
        font-weight: 600;
    }
    
    .subtitle {
        margin: 0.25rem 0 0 0;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .user-name {
        color: #3b82f6;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .header-content {
            padding: 1rem;
        }
        
        .header-left {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .header-title h1 {
            font-size: 1.25rem;
        }
    }
</style>