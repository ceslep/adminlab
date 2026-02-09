<script lang="ts">
    import { fade, slide } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import Button from './shared/Button.svelte';
    
    export let title: string = '';
    export let subtitle: string = '';
    export let showBackButton: boolean = false;
    export let onBack: () => void;
    export let onLogout: () => void;
    export let userName: string | undefined = undefined;
</script>

<header 
    in:slide={{ duration: 400, easing: cubicOut }}
    class="dashboard-header"
>
    <div class="header-content">
        <div class="header-left">
            {#if showBackButton}
                <div in:fade={{ duration: 300, delay: 100 }}>
                    <Button 
                        variant="secondary" 
                        size="sm" 
                        icon="←"
                        onClick={onBack}
                    >
                        Volver
                    </Button>
                </div>
            {/if}
            <div in:fade={{ duration: 300, delay: 200 }}>
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
        </div>
        <div class="header-right">
            <div in:fade={{ duration: 300, delay: 300 }}>
                <Button 
                    variant="secondary" 
                    size="sm" 
                    onClick={onLogout}
                >
                    Cerrar sesión
                </Button>
            </div>
        </div>
    </div>
</header>

<style>
    .dashboard-header {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }
    
    .header-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.25rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .header-left {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    
    .header-right {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .header-title h1 {
        margin: 0;
        font-size: 1.75rem;
        color: #1e293b;
        font-weight: 700;
        line-height: 1.2;
    }
    
    .subtitle {
        margin: 0.375rem 0 0 0;
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .user-name {
        color: #4f46e5;
        font-weight: 700;
    }
    
    @media (max-width: 768px) {
        .header-content {
            padding: 1rem 1.25rem;
        }
        
        .header-left {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .header-title h1 {
            font-size: 1.375rem;
        }
        
        .subtitle {
            font-size: 0.8125rem;
        }
    }
</style>