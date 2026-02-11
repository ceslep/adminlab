<script lang="ts">
    import { fade, fly, scale } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    
    export let variant: 'glass' | 'solid' | 'gradient' = 'glass';
    export let interactive: boolean = false;
    export let elevated: boolean = false;
    export let size: 'sm' | 'md' | 'lg' | 'xl' = 'md';
    export let hover: boolean = false;
    
    const variants = {
        glass: {
            background: 'rgba(255, 255, 255, 0.7)',
            backdropFilter: 'blur(20px)',
            border: '1px solid rgba(255, 255, 255, 0.2)',
            shadow: 'var(--shadow-glass)'
        },
        solid: {
            background: 'var(--color-surface)',
            backdropFilter: 'none',
            border: '1px solid var(--color-border)',
            shadow: 'var(--shadow-md)'
        },
        gradient: {
            background: 'var(--gradient-primary)',
            backdropFilter: 'none',
            border: '1px solid rgba(255, 255, 255, 0.3)',
            shadow: 'var(--shadow-hover)'
        }
    };
    
    const sizes = {
        sm: { padding: 'var(--spacing-3)', borderRadius: 'var(--radius-lg)' },
        md: { padding: 'var(--spacing-6)', borderRadius: 'var(--radius-2xl)' },
        lg: { padding: 'var(--spacing-8)', borderRadius: 'var(--radius-3xl)' },
        xl: { padding: 'var(--spacing-10)', borderRadius: 'var(--radius-3xl)' }
    };
    
    $: currentVariant = variants[variant];
    $: currentSize = sizes[size];
</script>

<div 
    class="premium-card"
    class:interactive={interactive}
    class:elevated={elevated}
    style="
        background: {currentVariant.background};
        backdrop-filter: {currentVariant.backdropFilter};
        border: {currentVariant.border};
        box-shadow: {elevated ? 'var(--shadow-xl)' : currentVariant.shadow};
        padding: {currentSize.padding};
        border-radius: {currentSize.borderRadius};
        transition: all var(--duration-slow) var(--ease-out);
    "
    role="region"
>
    <!-- Glass Overlay Layer -->
    {#if variant === 'glass'}
        <div class="glass-overlay"></div>
    {/if}
    
    <!-- Gradient Overlay for Interactive Cards -->
    {#if interactive}
        <div class="gradient-overlay"></div>
    {/if}
    
    <!-- Content Slot -->
    <div class="card-content">
        <slot />
    </div>
    
    <!-- Hover Effect Particles -->
    {#if interactive && hover}
        <div class="particles">
            <div class="particle particle-1"></div>
            <div class="particle particle-2"></div>
            <div class="particle particle-3"></div>
        </div>
    {/if}
</div>

<style>
    .premium-card {
        position: relative;
        overflow: hidden;
        transform: translateZ(0); /* Hardware acceleration */
    }
    
    .premium-card.interactive {
        cursor: pointer;
    }
    
    .premium-card.interactive:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-xl);
    }
    
    .glass-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        pointer-events: none;
        opacity: 0.7;
    }
    
    .gradient-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent, rgba(99, 102, 241, 0.05), transparent);
        opacity: 0;
        transition: opacity var(--duration-slow) var(--ease-out);
        pointer-events: none;
    }
    
    .premium-card.interactive:hover .gradient-overlay {
        opacity: 1;
    }
    
    .card-content {
        position: relative;
        z-index: 2;
    }
    
    /* Particle Effects for Premium Interactive Cards */
    .particles {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
    }
    
    .particle {
        position: absolute;
        border-radius: 50%;
        opacity: 0;
        animation: floatParticle 8s ease-in-out infinite;
    }
    
    .particle-1 {
        width: 4px;
        height: 4px;
        background: var(--color-primary-400);
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .particle-2 {
        width: 3px;
        height: 3px;
        background: var(--color-secondary-400);
        top: 60%;
        left: 80%;
        animation-delay: 2s;
    }
    
    .particle-3 {
        width: 5px;
        height: 5px;
        background: var(--color-error-400);
        top: 80%;
        left: 20%;
        animation-delay: 4s;
    }
    
    .premium-card.interactive:hover .particle {
        opacity: 0.6;
    }
    
    @keyframes floatParticle {
        0%, 100% {
            transform: translateY(0px) translateX(0px);
            opacity: 0;
        }
        25% {
            opacity: 0.4;
            transform: translateY(-10px) translateX(5px);
        }
        50% {
            opacity: 0.6;
            transform: translateY(-20px) translateX(-5px);
        }
        75% {
            opacity: 0.3;
            transform: translateY(-10px) translateX(3px);
        }
    }
    
    /* Premium Reflection Effect */
    .premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transition: left 0.6s var(--ease-out);
        pointer-events: none;
        z-index: 3;
    }
    
    .premium-card.interactive:hover::before {
        left: 100%;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .premium-card.interactive:hover {
            transform: translateY(-4px) scale(1.01);
        }
        
        .particle {
            display: none;
        }
    }
</style>