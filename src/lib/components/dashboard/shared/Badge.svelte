<script lang="ts">
    export let text: string = '';
    export let variant: 'indigo' | 'emerald' | 'violet' | 'amber' | 'rose' | 'slate' | 'blue' | 'green' | 'purple' | 'yellow' | 'red' | 'gray' = 'indigo';
    export let size: 'sm' | 'md' = 'md';
    
    const variants = {
        indigo: {
            background: 'var(--color-primary-50)',
            color: 'var(--color-primary-700)',
            border: '1px solid var(--color-primary-200/60)'
        },
        emerald: {
            background: 'var(--color-success-50)',
            color: 'var(--color-success-700)',
            border: '1px solid var(--color-success-200/60)'
        },
        violet: {
            background: 'var(--color-secondary-50)',
            color: 'var(--color-secondary-700)',
            border: '1px solid var(--color-secondary-200/60)'
        },
        amber: {
            background: 'var(--color-warning-50)',
            color: 'var(--color-warning-700)',
            border: '1px solid var(--color-warning-200/60)'
        },
        rose: {
            background: 'var(--color-error-50)',
            color: 'var(--color-error-700)',
            border: '1px solid var(--color-error-200/60)'
        },
        slate: {
            background: 'var(--color-neutral-100)',
            color: 'var(--color-neutral-700)',
            border: '1px solid var(--color-neutral-200/60)'
        },
        // Legacy variants mapped to new ones
        blue: {
            background: 'var(--color-primary-50)',
            color: 'var(--color-primary-700)',
            border: '1px solid var(--color-primary-200/60)'
        },
        green: {
            background: 'var(--color-success-50)',
            color: 'var(--color-success-700)',
            border: '1px solid var(--color-success-200/60)'
        },
        purple: {
            background: 'var(--color-secondary-50)',
            color: 'var(--color-secondary-700)',
            border: '1px solid var(--color-secondary-200/60)'
        },
        yellow: {
            background: 'var(--color-warning-50)',
            color: 'var(--color-warning-700)',
            border: '1px solid var(--color-warning-200/60)'
        },
        // Additional variants
        red: {
            background: 'var(--color-error-50)',
            color: 'var(--color-error-700)',
            border: '1px solid var(--color-error-200/60)'
        },
        gray: {
            background: 'var(--color-neutral-100)',
            color: 'var(--color-neutral-700)',
            border: '1px solid var(--color-neutral-200/60)'
        }
    };
    
    const sizes = {
        sm: {
            fontSize: 'var(--font-size-xs)',
            padding: 'var(--spacing-1) var(--spacing-2.5)'
        },
        md: {
            fontSize: 'var(--font-size-sm)',
            padding: 'var(--spacing-1.5) var(--spacing-3)'
        }
    };
    
    $: currentVariant = variants[variant];
    $: currentSize = sizes[size];
</script>

<span 
    class="badge"
    style="
        background: {currentVariant.background};
        color: {currentVariant.color};
        border: {currentVariant.border};
        font-size: {currentSize.fontSize};
        padding: {currentSize.padding};
    "
>
    {text}
</span>

<style>
    .badge {
        display: inline-flex;
        align-items: center;
        border-radius: var(--radius-full);
        font-weight: var(--font-weight-semibold);
        transition: all var(--duration-fast) var(--ease-out);
        white-space: nowrap;
        font-family: var(--font-family-sans);
    }
    
    .badge:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-sm);
    }
</style>