<script lang="ts">
    export let variant: 'primary' | 'secondary' | 'success' | 'danger' = 'primary';
    export let size: 'sm' | 'md' | 'lg' = 'md';
    export let disabled: boolean = false;
    export let loading: boolean = false;
    export let icon: string = '';
    export let fullWidth: boolean = false;
    export let onClick: (() => void) | undefined = undefined;
    
    const variants = {
        primary: 'bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white shadow-indigo-500/25',
        secondary: 'bg-slate-100 hover:bg-slate-200 text-slate-700 shadow-slate-200/50',
        success: 'bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white shadow-emerald-500/25',
        danger: 'bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white shadow-red-500/25'
    };
    
    const sizes = {
        sm: 'text-sm px-4 py-2',
        md: 'text-sm px-5 py-2.5',
        lg: 'text-base px-6 py-3'
    };
</script>

<button 
    class="btn {variants[variant]} {sizes[size]} {fullWidth ? 'w-full' : ''}"
    {disabled}
    on:click={onClick}
>
    {#if loading}
        <div class="spinner"></div>
    {:else}
        {#if icon}
            <span class="icon">{icon}</span>
        {/if}
        <slot></slot>
    {/if}
</button>

<style>
    .btn {
        border: none;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .btn:active:not(:disabled) {
        transform: translateY(0);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none;
    }
    
    .spinner {
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.25);
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .icon {
        display: inline-block;
        font-size: 1.1em;
    }
    
    @keyframes spin {
        to { 
            transform: rotate(360deg); 
        }
    }
</style>