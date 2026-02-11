<script lang="ts">
    export let variant: 'primary' | 'secondary' | 'success' | 'danger' = 'primary';
    export let size: 'sm' | 'md' | 'lg' = 'md';
    export let disabled: boolean = false;
    export let loading: boolean = false;
    export let icon: string = '';
    export let fullWidth: boolean = false;
    export let onClick: (() => void) | undefined = undefined;
    
    const variants = {
        primary: {
            background: 'var(--gradient-primary)',
            hover: 'var(--gradient-primary-hover)',
            color: 'var(--color-neutral-0)',
            shadow: '0 10px 25px -5px rgba(99, 102, 241, 0.35)'
        },
        secondary: {
            background: 'var(--gradient-neutral)',
            hover: 'linear-gradient(135deg, var(--color-primary-100), var(--color-primary-200))',
            color: 'var(--color-primary-700)',
            shadow: '0 4px 6px -1px rgba(99, 102, 241, 0.1)',
            border: '2px solid var(--color-primary-300/50)'
        },
        success: {
            background: 'var(--gradient-success)',
            hover: 'linear-gradient(135deg, var(--color-success-600), var(--color-success-700))',
            color: 'var(--color-neutral-0)',
            shadow: '0 10px 25px -5px rgba(16, 185, 129, 0.35)'
        },
        danger: {
            background: 'var(--gradient-error)',
            hover: 'linear-gradient(135deg, var(--color-error-600), var(--color-error-700))',
            color: 'var(--color-neutral-0)',
            shadow: '0 10px 25px -5px rgba(239, 68, 68, 0.35)'
        }
    };
    
    const sizes = {
        sm: {
            height: 'var(--button-height-sm)',
            padding: '0 var(--button-padding-x-sm)',
            fontSize: 'var(--font-size-xs)'
        },
        md: {
            height: 'var(--button-height-md)',
            padding: '0 var(--button-padding-x-md)',
            fontSize: 'var(--font-size-sm)'
        },
        lg: {
            height: 'var(--button-height-lg)',
            padding: '0 var(--button-padding-x-lg)',
            fontSize: 'var(--font-size-base)'
        }
    };
    
    $: currentVariant = variants[variant];
    $: currentSize = sizes[size];
</script>

<button 
    class="btn-base"
    class:full-width={fullWidth}
    style="
        background: {currentVariant.background};
        color: {currentVariant.color};
        box-shadow: {currentVariant.shadow};
        height: {currentSize.height};
        padding: {currentSize.padding};
        font-size: {currentSize.fontSize};
        {'border' in currentVariant ? `border: ${currentVariant.border};` : ''}
    "
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
    
    <!-- Button overlay for hover effect -->
    <div class="button-overlay"></div>
</button>

<style>
    .full-width {
        width: 100%;
    }
    
    .button-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        opacity: 0;
        transition: opacity var(--duration-normal) var(--ease-out);
        pointer-events: none;
    }
    
    :global(.btn-base:hover .button-overlay) {
        opacity: 1;
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