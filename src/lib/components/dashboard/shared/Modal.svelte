<script lang="ts">
    import { createEventDispatcher } from 'svelte';
    import { fade, fly, scale } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import { X, ChevronRight, Activity, FileText, Calendar, Phone, Mail, MapPin } from 'lucide-svelte';
    
    export let show: boolean = false;
    export let title: string = '';
    export let subtitle: string = '';
    export let size: 'sm' | 'md' | 'lg' | 'xl' = 'md';
    export let showCloseButton: boolean = true;
    export let closeOnBackdrop: boolean = true;
    export let onClose: () => void = () => {};
    
    const dispatch = createEventDispatcher();
    
    function handleClose() {
        console.log('🔴 Modal handleClose() llamado');
        dispatch('close');
        onClose();
    }
    
    function handleBackdropClick(e: MouseEvent) {
        if (closeOnBackdrop && e.target === e.currentTarget) {
            handleClose();
        }
    }
    
    function handleKeydown(e: KeyboardEvent) {
        if (e.key === 'Escape') {
            handleClose();
        }
    }
    
    $: if (show && typeof window !== 'undefined') {
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', handleKeydown);
    } else if (typeof window !== 'undefined') {
        document.body.style.overflow = '';
        document.removeEventListener('keydown', handleKeydown);
    }
    
    const sizeClasses = {
        sm: 'max-width: var(--modal-max-width-sm)',
        md: 'max-width: var(--modal-max-width-md)',
        lg: 'max-width: var(--modal-max-width-lg)',
        xl: 'max-width: var(--modal-max-width-xl)'
    };
</script>

{#if show}
    <div 
        class="modal-backdrop"
        on:click={handleBackdropClick}
        on:keydown={handleKeydown}
        role="button"
        tabindex="0"
        aria-label="Close modal"
        in:fade={{ duration: 300 }}
        out:fade={{ duration: 200 }}
    >
        <div 
            class="modal-container"
            style={sizeClasses[size]}
            in:fade={{ duration: 300 }}
            out:fade={{ duration: 200 }}
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-title"
        >
            <!-- Modal Header -->
            <div class="modal-header">
                <div class="modal-header-content">
                    {#if title}
                        <h2 id="modal-title" class="modal-title">{title}</h2>
                    {/if}
                    {#if subtitle}
                        <p class="modal-subtitle">{subtitle}</p>
                    {/if}
                </div>
                
                {#if showCloseButton}
                    <button 
                        class="modal-close-button"
                        on:click={handleClose}
                        aria-label="Close modal"
                    >
                        <div class="close-button-content">
                            <X class="close-icon" />
                        </div>
                        <div class="close-button-glow"></div>
                    </button>
                {/if}
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <slot />
            </div>
            
            <!-- Optional Footer -->
            {#if $$slots.footer}
                <div class="modal-footer">
                    <slot name="footer" />
                </div>
            {/if}
        </div>
    </div>
{/if}

<style>
    .modal-backdrop {
        position: fixed;
        inset: 0;
        background: var(--modal-backdrop-bg);
        backdrop-filter: var(--backdrop-blur-md);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: var(--spacing-8);
        z-index: var(--z-index-modal-backdrop);
        overflow-y: auto;
    }
    
    .modal-container {
        background: var(--color-surface-glass);
        backdrop-filter: var(--backdrop-blur-2xl);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--radius-3xl);
        box-shadow: var(--shadow-xl);
        max-height: calc(100vh - var(--spacing-16));
        overflow-y: auto;
        position: relative;
        z-index: var(--z-index-modal);
        width: 100%;
    }
    
    .modal-container::-webkit-scrollbar {
        width: var(--spacing-2);
    }
    
    .modal-container::-webkit-scrollbar-track {
        background: var(--color-neutral-100);
        border-radius: var(--radius-base);
    }
    
    .modal-container::-webkit-scrollbar-thumb {
        background: var(--color-neutral-300);
        border-radius: var(--radius-base);
    }
    
    .modal-container::-webkit-scrollbar-thumb:hover {
        background: var(--color-neutral-400);
    }
    
    .modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: var(--spacing-10) var(--spacing-10) var(--spacing-6);
        border-bottom: 1px solid var(--color-border);
        gap: var(--spacing-4);
    }
    
    .modal-header-content {
        flex: 1;
        min-width: 0;
    }
    
    .modal-title {
        font-size: var(--font-size-3xl);
        font-weight: var(--font-weight-extrabold);
        color: var(--color-neutral-800);
        margin: 0 0 var(--spacing-2) 0;
        line-height: var(--line-height-tight);
        background: linear-gradient(135deg, var(--color-neutral-800), var(--color-primary-500));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-family: var(--font-family-sans);
    }
    
    .modal-subtitle {
        font-size: var(--font-size-base);
        color: var(--color-text-secondary);
        margin: 0;
        line-height: var(--line-height-relaxed);
        font-weight: var(--font-weight-medium);
        font-family: var(--font-family-sans);
    }
    
    .modal-close-button {
        width: 44px;
        height: 44px;
        border-radius: var(--radius-xl);
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.1));
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: var(--color-error-600);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all var(--duration-normal) var(--ease-out);
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.1);
    }
    
    .modal-close-button:hover {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(220, 38, 38, 0.25));
        border-color: rgba(239, 68, 68, 0.5);
        color: var(--color-error-700);
        transform: rotate(180deg) scale(1.1);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        border-width: 2px;
    }
    
    .modal-close-button:active {
        transform: rotate(90deg) scale(0.95);
    }

    .close-button-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-icon {
        width: 20px;
        height: 20px;
        transition: transform var(--duration-normal) var(--ease-out);
    }

    .close-button-glow {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform var(--duration-slow) var(--ease-out);
        pointer-events: none;
    }
    
    .modal-close-button:hover .close-button-glow {
        transform: translateX(0);
    }
    
    .modal-body {
        padding: var(--spacing-10);
    }
    
    .modal-footer {
        padding: var(--spacing-6) var(--spacing-10) var(--spacing-10);
        border-top: 1px solid var(--color-border);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .modal-backdrop {
            padding: var(--spacing-4);
        }
        
        .modal-container {
            max-height: calc(100vh - var(--spacing-8));
        }
        
        .modal-header {
            padding: var(--spacing-8) var(--spacing-6) var(--spacing-4);
        }
        
        .modal-body {
            padding: var(--spacing-6);
        }
        
        .modal-footer {
            padding: var(--spacing-4) var(--spacing-6) var(--spacing-6);
        }
        
        .modal-title {
            font-size: var(--font-size-2xl);
        }
    }
    
    /* Premium animation effects */
    .modal-container {
        animation: modalGlow 3s ease-in-out infinite;
    }
    
    @keyframes modalGlow {
        0%, 100% {
            box-shadow: var(--shadow-xl), 0 0 0 1px rgba(99, 102, 241, 0.1);
        }
        50% {
            box-shadow: var(--shadow-xl), 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
    }
</style>