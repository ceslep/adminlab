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
        secondary: 'bg-gradient-to-r from-indigo-50 to-indigo-100 hover:from-indigo-100 hover:to-indigo-200 text-indigo-700 border-2 border-indigo-300/50 shadow-indigo-200/50 hover:shadow-indigo-300/50',
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
    /* Enhanced Button Styles v2.0 */
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
    
    .btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
        opacity: 0;
        transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    
    .btn:hover:not(:disabled) {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.25), 0 8px 12px -4px rgba(99, 102, 241, 0.15);
    }
    
    .btn:hover:not(:disabled)::before {
        opacity: 1;
    }
    
    .btn:active:not(:disabled) {
        transform: translateY(-1px) scale(0.98);
        box-shadow: 0 8px 12px -3px rgba(0, 0, 0, 0.15), 0 4px 8px -2px rgba(0, 0, 0, 0.1);
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