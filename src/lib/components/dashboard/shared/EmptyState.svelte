<script lang="ts">
    export let icon: string = '🔍';
    export let title: string = 'No se encontraron resultados';
    export let message: string = 'Intenta con otros criterios de búsqueda';
    export let action: (() => void) | undefined = undefined;
    export let actionText: string = 'Reintentar';
    export let showAction: boolean = false;
</script>

<div class="empty-state">
    <div class="empty-icon">{icon}</div>
    <h3 class="empty-title">{title}</h3>
    <p class="empty-message">{message}</p>
    {#if showAction && action}
        <button class="btn btn-primary" on:click={action}>
            {actionText}
        </button>
    {/if}
</div>

<style>
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.8;
    }
    
    .empty-title {
        color: #6b7280;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .empty-message {
        color: #9ca3af;
        margin: 0 0 1.5rem 0;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .btn {
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
        font-size: 0.875rem;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2563eb;
    }
</style>