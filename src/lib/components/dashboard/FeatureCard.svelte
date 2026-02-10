<script lang="ts">
    import { fade, scale, fly } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import { Sparkles, ArrowRight, Zap } from 'lucide-svelte';
    import Button from './shared/Button.svelte';
    
    export let icon: string = '';
    export let title: string = '';
    export let description: string = '';
    export let buttonText: string = '';
    export let onClick: () => void;
    export let disabled: boolean = false;
    export const variant: 'primary' | 'secondary' | 'success' = 'primary';
</script>

<div 
    class="feature-card" 
    class:disabled={disabled}
    role="button"
    tabindex={disabled ? -1 : 0}
    on:click={!disabled ? onClick : undefined}
    on:keydown={!disabled ? (e) => e.key === 'Enter' && onClick() : undefined}
>
    <!-- Background decoration -->
    <div class="feature-card-bg"></div>
    
    <!-- Sparkles decoration -->
    <div class="absolute top-4 right-4 opacity-20">
        <Sparkles class="w-6 h-6 text-current" />
    </div>
    
    <!-- Icon with glow effect -->
    <div 
        in:scale={{ duration: 400, delay: 100, easing: cubicOut }}
        class="feature-icon-container"
    >
        <div class="feature-icon">{icon}</div>
        <div class="feature-icon-glow"></div>
    </div>
    
    <!-- Content -->
    <div class="feature-content">
        <div in:fly={{ y: 20, duration: 500, delay: 200, easing: cubicOut }}>
            <h3 class="feature-title">{title}</h3>
            <p class="feature-description">{description}</p>
            
            <div in:fly={{ y: 10, duration: 400, delay: 300, easing: cubicOut }}>
                <div class="feature-action">
                    {#if !disabled}
                        <button 
                            class="feature-button"
                            on:click={onClick}
                        >
                            <span>{buttonText}</span>
                            <ArrowRight class="feature-arrow" />
                        </button>
                    {:else}
                        <div class="feature-button feature-button-disabled">
                            <span>{buttonText}</span>
                            <Zap class="feature-arrow" />
                        </div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hover overlay -->
    <div class="feature-overlay"></div>
</div>

<style>
    .feature-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2.5rem;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    
    .feature-card-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent, rgba(99, 102, 241, 0.02), transparent);
        opacity: 0;
        transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .feature-card:hover:not(.disabled) {
        transform: translateY(-12px) scale(1.03);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .feature-card:hover:not(.disabled) .feature-card-bg {
        opacity: 1;
    }
    
    .feature-card:hover:not(.disabled) .feature-icon {
        transform: scale(1.15) rotate(5deg);
    }
    

    
    .feature-card:active:not(.disabled) {
        transform: translateY(-8px) scale(1.02);
    }
    
    .feature-card.disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .feature-card.disabled:hover {
        transform: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .feature-icon-container {
        position: relative;
        display: inline-block;
        margin-bottom: 2rem;
    }
    
    .feature-icon {
        font-size: 5rem;
        line-height: 1;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 2;
    }
    
    .feature-icon-glow {
        position: absolute;
        inset: -10px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.1), transparent);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1;
    }
    
    .feature-card:hover:not(.disabled) .feature-icon-glow {
        opacity: 1;
    }
    
    .feature-content {
        position: relative;
        z-index: 2;
    }
    
    .feature-title {
        margin-bottom: 1rem;
        color: #1e293b;
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -0.025em;
    }
    
    .feature-description {
        color: #64748b;
        margin-bottom: 2.5rem;
        line-height: 1.7;
        font-size: 1rem;
        font-weight: 500;
    }
    
    .feature-action {
        display: flex;
        justify-content: center;
    }
    
    .feature-button {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        padding: 1rem 2rem;
        border-radius: 1.5rem;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.35);
        position: relative;
        overflow: hidden;
    }
    
    .feature-button::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        transform: translateY(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .feature-button:hover::before {
        transform: translateY(0);
    }
    
    .feature-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px -5px rgba(99, 102, 241, 0.45);
    }
    
    .feature-button:active {
        transform: translateY(0);
    }
    
    .feature-button-disabled {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        background: #e2e8f0;
        color: #94a3b8;
        padding: 1rem 2rem;
        border-radius: 1.5rem;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        cursor: not-allowed;
        opacity: 0.6;
    }
    

    
    .feature-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
        opacity: 0;
        transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        border-radius: 2.5rem;
    }
    
    .feature-card:hover:not(.disabled) .feature-overlay {
        opacity: 1;
    }
</style>