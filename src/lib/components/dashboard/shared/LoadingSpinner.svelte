<script lang="ts">
    export let text: string = 'Cargando...';
    export let size: 'sm' | 'md' | 'lg' = 'md';
    export let showText: boolean = true;
    
    const sizes = {
        sm: 'w-4 h-4',
        md: 'w-6 h-6',
        lg: 'w-8 h-8'
    };
</script>

<div class="loading-container">
    <div class="spinner {sizes[size]}"></div>
    {#if showText}
        <p class="loading-text">{text}</p>
    {/if}
</div>

<style>
    .loading-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .spinner {
        border: 3px solid #f3f4f6;
        border-top: 3px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .loading-text {
        color: #6b7280;
        font-size: 0.875rem;
        margin: 0;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>