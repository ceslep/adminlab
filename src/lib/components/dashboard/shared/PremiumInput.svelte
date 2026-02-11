<script lang="ts">
    import { fade, fly } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    
    export let type: 'text' | 'email' | 'password' | 'tel' | 'number' | 'date' = 'text';
    export let label: string = '';
    export let placeholder: string = '';
    export let value: string = '';
    export let required: boolean = false;
    export let disabled: boolean = false;
    export let error: string = '';
    export let helper: string = '';
    export let icon: any = null;
    export let size: 'sm' | 'md' | 'lg' = 'md';
    export let variant: 'outline' | 'filled' | 'underline' = 'outline';
    export let loading: boolean = false;
    
    let inputElement: HTMLInputElement;
    let focused: boolean = false;
    let hasValue: boolean = false;
    
    $: if (value) {
        hasValue = true;
    } else {
        hasValue = false;
    }
    
    $: labelPosition = (hasValue || focused || placeholder) ? 'floating' : 'default';
    
    function handleFocus() {
        focused = true;
    }
    
    function handleBlur() {
        focused = false;
    }
    
    function handleInput(e: Event) {
        const target = e.target as HTMLInputElement;
        value = target.value;
    }
    
    const sizes = {
        sm: {
            height: 'var(--input-height-sm)',
            fontSize: 'var(--font-size-xs)',
            padding: icon ? '0 var(--spacing-4) 0 3rem' : '0 var(--spacing-4)',
            labelSize: 'var(--font-size-xs)'
        },
        md: {
            height: 'var(--input-height-md)',
            fontSize: 'var(--font-size-sm)',
            padding: icon ? '0 var(--spacing-5) 0 3.5rem' : '0 var(--spacing-5)',
            labelSize: 'var(--font-size-sm)'
        },
        lg: {
            height: 'var(--input-height-lg)',
            fontSize: 'var(--font-size-base)',
            padding: icon ? '0 var(--spacing-6) 0 4rem' : '0 var(--spacing-6)',
            labelSize: 'var(--font-size-base)'
        }
    };
    
    const variants = {
        outline: {
            background: 'rgba(241, 245, 249, 0.6)',
            border: '1px solid var(--color-border)',
            borderRadius: 'var(--radius-xl)',
            focusBorder: '1px solid var(--color-primary-500)',
            focusShadow: '0 0 0 3px rgba(99, 102, 241, 0.1), 0 0 0 1px rgba(99, 102, 241, 0.6)'
        },
        filled: {
            background: 'var(--color-neutral-100)',
            border: '1px solid transparent',
            borderRadius: 'var(--radius-lg)',
            focusBorder: '1px solid var(--color-primary-500)',
            focusShadow: '0 0 0 3px rgba(99, 102, 241, 0.1)'
        },
        underline: {
            background: 'transparent',
            border: 'none',
            borderRadius: '0',
            borderBottom: '2px solid var(--color-border)',
            focusBorder: '2px solid var(--color-primary-500)',
            focusShadow: '0 1px 0 0 var(--color-primary-500)'
        }
    };
    
    $: currentSize = sizes[size];
    $: currentVariant = variants[variant];
    $: inputStyle = `
        height: ${currentSize.height};
        font-size: ${currentSize.fontSize};
        padding: ${currentSize.padding};
        background: ${focused ? 'rgba(255, 255, 255, 0.9)' : currentVariant.background};
        border: ${focused ? currentVariant.focusBorder : currentVariant.border};
        border-radius: ${currentVariant.borderRadius};
        box-shadow: ${focused ? currentVariant.focusShadow : 'none'};
        transition: all var(--duration-normal) var(--ease-out);
    `;
    
    $: labelStyle = `
        font-size: ${labelPosition === 'floating' ? 'var(--font-size-xs)' : currentSize.labelSize};
        top: ${labelPosition === 'floating' ? '-0.5rem' : '50%'};
        left: ${icon ? '3.5rem' : '1rem'};
        transform: ${labelPosition === 'floating' ? 'translateY(0)' : 'translateY(-50%)'};
        color: ${error ? 'var(--color-error-600)' : focused ? 'var(--color-primary-600)' : 'var(--color-text-tertiary)'};
        background: ${labelPosition === 'floating' ? 'var(--color-surface)' : 'transparent'};
        padding: ${labelPosition === 'floating' ? '0 0.5rem' : '0'};
        font-weight: ${labelPosition === 'floating' ? 'var(--font-weight-semibold)' : 'var(--font-weight-medium)'};
    `;
</script>

<div class="premium-input" class:error={error} class:disabled={disabled}>
    <!-- Input Container -->
    <div class="input-container" class:focused={focused}>
        <!-- Icon -->
        {#if icon}
            <div class="input-icon" class:focused={focused}>
                <svelte:component this={icon} />
            </div>
        {/if}
        
        <!-- Input Field -->
        <input
            bind:this={inputElement}
            {type}
            {value}
            {placeholder}
            {required}
            {disabled}
            class="input-field"
            style={inputStyle}
            on:focus={handleFocus}
            on:blur={handleBlur}
            on:input={handleInput}
            aria-describedby={error ? 'error-message' : helper ? 'helper-message' : ''}
            aria-invalid={!!error}
        />
        
        <!-- Floating Label -->
        {#if label}
            <label 
                for="input-{Math.random()}"
            class="input-label"
            style={labelStyle}
            transition:fly={{ y: -10, duration: 200, easing: cubicOut }}
            >
                {label}
                {#if required}
                    <span class="required-star">*</span>
                {/if}
            </label>
        {/if}
        
        <!-- Loading State -->
        {#if loading}
            <div class="input-loading">
                <div class="loading-spinner"></div>
            </div>
        {/if}
        
        <!-- Error Icon -->
        {#if error}
            <div class="input-error">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
        {/if}
    </div>
    
    <!-- Helper Text / Error Message -->
    {#if error}
        <div 
            id="error-message"
            class="input-message error"
            transition:fade={{ duration: 200 }}
        >
            {error}
        </div>
    {:else if helper}
        <div 
            id="helper-message"
            class="input-message helper"
            transition:fade={{ duration: 200 }}
        >
            {helper}
        </div>
    {/if}
</div>

<style>
    .premium-input {
        width: 100%;
        position: relative;
        transition: all var(--duration-normal) var(--ease-out);
    }
    
    .input-container {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .input-field {
        width: 100%;
        border: none;
        outline: none;
        color: var(--color-text-primary);
        font-family: var(--font-family-sans);
        font-weight: var(--font-weight-medium);
        box-sizing: border-box;
        transition: all var(--duration-normal) var(--ease-out);
    }
    
    .input-field::placeholder {
        color: transparent;
    }
    
    .input-field:autofill,
    .input-field:-webkit-autofill,
    .input-field:-webkit-autofill:hover,
    .input-field:-webkit-autofill:focus {
        -webkit-text-fill-color: var(--color-text-primary);
        -webkit-box-shadow: 0 0 0 1000px var(--color-surface) inset;
        transition: background-color 5000s ease-in-out 0s;
    }
    
    .input-icon {
        position: absolute;
        left: var(--spacing-4);
        z-index: 2;
        color: var(--color-text-tertiary);
        transition: all var(--duration-normal) var(--ease-out);
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .input-icon.focused {
        color: var(--color-primary-500);
    }
    
    .input-label {
        position: absolute;
        pointer-events: none;
        transition: all var(--duration-normal) var(--ease-out);
        font-family: var(--font-family-sans);
        z-index: 2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: calc(100% - 2rem);
    }
    
    .required-star {
        color: var(--color-error-500);
        margin-left: 2px;
    }
    
    .input-loading {
        position: absolute;
        right: var(--spacing-4);
        z-index: 2;
    }
    
    .loading-spinner {
        width: 16px;
        height: 16px;
        border: 2px solid var(--color-neutral-300);
        border-top: 2px solid var(--color-primary-500);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    .input-error {
        position: absolute;
        right: var(--spacing-4);
        z-index: 2;
        color: var(--color-error-500);
    }
    
    .input-message {
        margin-top: var(--spacing-2);
        font-size: var(--font-size-xs);
        font-family: var(--font-family-sans);
        line-height: var(--line-height-normal);
    }
    
    .input-message.error {
        color: var(--color-error-600);
        font-weight: var(--font-weight-medium);
    }
    
    .input-message.helper {
        color: var(--color-text-tertiary);
    }
    
    /* States */
    .premium-input.error .input-field {
        border-color: var(--color-error-500) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    }
    
    .premium-input.error .input-label {
        color: var(--color-error-600) !important;
    }
    
    .premium-input.error .input-icon {
        color: var(--color-error-500);
    }
    
    .premium-input.disabled {
        opacity: 0.5;
        pointer-events: none;
    }
    
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    /* Glassmorphism variant specific styles */
    .premium-input:has(.input-container:focus-within) {
        transform: translateY(-1px);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .input-label {
            max-width: calc(100% - 1rem);
        }
        
        .input-icon {
            left: var(--spacing-3);
        }
        
        .input-loading,
        .input-error {
            right: var(--spacing-3);
        }
    }
</style>