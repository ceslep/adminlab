<script lang="ts">
    export let variant: 'primary' | 'secondary' | 'success' | 'danger' = 'primary';
    export let size: 'sm' | 'md' | 'lg' = 'md';
    export let disabled: boolean = false;
    export let loading: boolean = false;
    export let icon: string = '';
    export let fullWidth: boolean = false;
    export let onClick: (() => void) | undefined = undefined;
    
    const variants = {
        primary: 'bg-blue-500 hover:bg-blue-600 text-white',
        secondary: 'bg-gray-500 hover:bg-gray-600 text-white',
        success: 'bg-green-500 hover:bg-green-600 text-white',
        danger: 'bg-red-500 hover:bg-red-600 text-white'
    };
    
    const sizes = {
        sm: 'text-sm px-3 py-1.5',
        md: 'text-sm px-4 py-2',
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
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .spinner {
        width: 1rem;
        height: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .icon {
        display: inline-block;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>