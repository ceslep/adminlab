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
    
    const dispatch = createEventDispatcher();
    
    function handleClose() {
        dispatch('close');
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
        sm: 'max-w-md',
        md: 'max-w-lg',
        lg: 'max-w-3xl',
        xl: 'max-w-6xl'
    };
</script>

{#if show}
    <div 
        class="modal-backdrop"
        on:click={handleBackdropClick}
        in:fade={{ duration: 300 }}
        out:fade={{ duration: 200 }}
    >
        <div 
            class="modal-container {sizeClasses[size]}"
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
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        z-index: 9999;
        overflow-y: auto;
    }
    
    .modal-container {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        max-height: calc(100vh - 4rem);
        overflow-y: auto;
        position: relative;
        z-index: 1;
    }
    
    .modal-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .modal-container::-webkit-scrollbar-track {
        background: rgba(241, 245, 249, 0.5);
        border-radius: 4px;
    }
    
    .modal-container::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.3);
        border-radius: 4px;
    }
    
    .modal-container::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.5);
    }
    
    .modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 2.5rem 2.5rem 1.5rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        gap: 1rem;
    }
    
    .modal-header-content {
        flex: 1;
        min-width: 0;
    }
    
    .modal-title {
        font-size: 1.875rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 0.5rem 0;
        line-height: 1.2;
        background: linear-gradient(135deg, #1e293b, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .modal-subtitle {
        font-size: 1rem;
        color: #64748b;
        margin: 0;
        line-height: 1.6;
        font-weight: 500;
    }
    
    .modal-close-button {
        width: 44px;
        height: 44px;
        border-radius: 1rem;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.1));
        border: 1px solid rgba(239, 68, 68, 0.25);
        color: #dc2626;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.1);
    }
    
    .modal-close-button:hover {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(220, 38, 38, 0.25));
        border-color: rgba(239, 68, 68, 0.5);
        color: #991b1b;
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
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .close-button-glow {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    
    .modal-close-button:hover {
        background: rgba(239, 68, 68, 0.2);
        transform: scale(1.05);
    }

    .modal-close-button:hover .close-icon {
        transform: rotate(90deg);
    }

    .modal-close-button:hover .close-button-glow {
        transform: translateX(0);
    }
    
    .modal-body {
        padding: 2.5rem;
    }
    
    .modal-footer {
        padding: 1.5rem 2.5rem 2.5rem;
        border-top: 1px solid rgba(226, 232, 240, 0.6);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .modal-backdrop {
            padding: 1rem;
        }
        
        .modal-container {
            max-height: calc(100vh - 2rem);
        }
        
        .modal-header {
            padding: 2rem 1.5rem 1rem;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem 1.5rem;
        }
        
        .modal-title {
            font-size: 1.5rem;
        }
    }
    
    /* Premium animation effects */
    .modal-container {
        animation: modalGlow 3s ease-in-out infinite;
    }
    
    @keyframes modalGlow {
        0%, 100% {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(99, 102, 241, 0.1);
        }
        50% {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
    }
</style>