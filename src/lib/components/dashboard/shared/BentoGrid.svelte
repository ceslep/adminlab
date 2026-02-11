<script lang="ts">
    import { fade, fly, scale } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    
    export let columns: 'auto' | '1' | '2' | '3' | '4' = 'auto';
    export let gap: 'sm' | 'md' | 'lg' = 'md';
    export let autoRows: boolean = true;
    export let children: any[] = [];
    
    const columnTemplates = {
        auto: 'repeat(auto-fit, minmax(400px, 1fr))',
        '1': '1fr',
        '2': 'repeat(2, 1fr)',
        '3': 'repeat(3, 1fr)',
        '4': 'repeat(4, 1fr)'
    };
    
    const gapSizes = {
        sm: 'var(--spacing-3)',
        md: 'var(--spacing-6)',
        lg: 'var(--spacing-8)'
    };
    
    $: gridStyle = `
        display: grid;
        gap: ${gapSizes[gap]};
        grid-template-columns: ${columnTemplates[columns]};
        ${autoRows ? 'grid-auto-rows: minmax(200px, auto);' : ''}
    `;
</script>

<div class="bento-grid" style={gridStyle}>
    {#each children as child, i}
        <div 
            class="bento-item {child.size || 'medium'}"
            class:interactive={child.interactive}
            in:fly={{ y: 40, duration: 700, delay: i * 100, easing: cubicOut }}
            style="
                grid-column: ${child.gridColumn || 'auto'};
                grid-row: ${child.gridRow || 'auto'};
            "
            on:click={child.onClick}
            role={child.interactive ? 'button' : 'region'}
            tabindex={child.interactive ? 0 : -1}
        >
            <!-- Background Layer -->
            <div 
                class="bento-background {child.backgroundType || 'gradient'}"
                style="background: {child.background || ''};"
            >
                {#if child.image}
                    <img 
                        src={child.image} 
                        alt={child.imageAlt || ''}
                        class="bento-image"
                    />
                {/if}
                
                <!-- Overlay for depth -->
                <div class="bento-overlay"></div>
            </div>
            
            <!-- Content -->
            <div class="bento-content">
                {#if child.icon}
                    <div class="bento-icon">
                        {child.icon}
                    </div>
                {/if}
                
                {#if child.title}
                    <h3 class="bento-title">{child.title}</h3>
                {/if}
                
                {#if child.subtitle}
                    <p class="bento-subtitle">{child.subtitle}</p>
                {/if}
                
                {#if child.description}
                    <p class="bento-description">{child.description}</p>
                {/if}
                
                {#if child.stats}
                    <div class="bento-stats">
                        {#each child.stats as stat}
                            <div class="stat-item">
                                <div class="stat-number">{stat.value}</div>
                                <div class="stat-label">{stat.label}</div>
                            </div>
                        {/each}
                    </div>
                {/if}
                
                {#if child.action}
                    <div class="bento-action">
                        <span>{child.action.text}</span>
                        <div class="action-arrow">→</div>
                    </div>
                {/if}
            </div>
            
            <!-- Floating Particles for Interactive Cards -->
            {#if child.interactive}
                <div class="bento-particles">
                    <div class="particle particle-1"></div>
                    <div class="particle particle-2"></div>
                    <div class="particle particle-3"></div>
                </div>
            {/if}
        </div>
    {/each}
</div>

<style>
    .bento-grid {
        width: 100%;
        position: relative;
    }
    
    .bento-item {
        position: relative;
        overflow: hidden;
        border-radius: var(--radius-2xl);
        transition: all var(--duration-slow) var(--ease-out);
        transform: translateZ(0); /* Hardware acceleration */
    }
    
    .bento-item.interactive {
        cursor: pointer;
    }
    
    .bento-item.interactive:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-xl);
        z-index: 10;
    }
    
    /* Size Variants */
    .bento-item.small {
        min-height: 150px;
    }
    
    .bento-item.medium {
        grid-row: span 2;
        min-height: 300px;
    }
    
    .bento-item.large {
        grid-column: span 2;
        grid-row: span 2;
        min-height: 400px;
    }
    
    .bento-item.wide {
        grid-column: span 2;
        min-height: 200px;
    }
    
    .bento-item.tall {
        grid-row: span 3;
        min-height: 450px;
    }
    
    /* Background Layer */
    .bento-background {
        position: absolute;
        inset: 0;
        overflow: hidden;
    }
    
    .bento-background.gradient {
        background: var(--gradient-neutral);
    }
    
    .bento-background.image {
        background: var(--color-neutral-100);
    }
    
    .bento-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.6;
        transition: all var(--duration-slow) var(--ease-out);
    }
    
    .bento-item.interactive:hover .bento-image {
        opacity: 0.8;
        transform: scale(1.05);
    }
    
    .bento-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(255, 255, 255, 0.85) 100%);
        pointer-events: none;
    }
    
    /* Content */
    .bento-content {
        position: relative;
        z-index: 2;
        padding: var(--spacing-6);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }
    
    .bento-icon {
        width: 48px;
        height: 48px;
        background: var(--gradient-primary);
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-bottom: var(--spacing-4);
        box-shadow: var(--shadow-lg);
        transition: all var(--duration-normal) var(--ease-out);
    }
    
    .bento-item.interactive:hover .bento-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: var(--shadow-hover);
    }
    
    .bento-title {
        font-size: var(--font-size-2xl);
        font-weight: var(--font-weight-bold);
        color: var(--color-text-primary);
        margin: 0 0 var(--spacing-2) 0;
        line-height: var(--line-height-tight);
    }
    
    .bento-subtitle {
        font-size: var(--font-size-base);
        font-weight: var(--font-weight-semibold);
        color: var(--color-primary-600);
        margin: 0 0 var(--spacing-3) 0;
    }
    
    .bento-description {
        font-size: var(--font-size-sm);
        color: var(--color-text-secondary);
        margin: 0 0 var(--spacing-4) 0;
        line-height: var(--line-height-relaxed);
    }
    
    /* Stats */
    .bento-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: var(--spacing-3);
        margin-top: var(--spacing-4);
    }
    
    .stat-item {
        text-align: center;
    }
    
    .stat-number {
        font-size: var(--font-size-2xl);
        font-weight: var(--font-weight-extrabold);
        color: var(--color-primary-600);
        line-height: 1;
        margin-bottom: var(--spacing-1);
    }
    
    .stat-label {
        font-size: var(--font-size-xs);
        color: var(--color-text-tertiary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: var(--font-weight-semibold);
    }
    
    /* Action */
    .bento-action {
        display: flex;
        align-items: center;
        gap: var(--spacing-2);
        font-weight: var(--font-weight-semibold);
        color: var(--color-primary-600);
        margin-top: var(--spacing-4);
        transition: all var(--duration-normal) var(--ease-out);
    }
    
    .action-arrow {
        width: 20px;
        height: 20px;
        background: var(--color-primary-600);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        transition: all var(--duration-normal) var(--ease-out);
    }
    
    .bento-item.interactive:hover .action-arrow {
        transform: translateX(4px);
        background: var(--color-primary-700);
    }
    
    /* Particles */
    .bento-particles {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 1;
    }
    
    .particle {
        position: absolute;
        border-radius: 50%;
        opacity: 0;
        animation: bentoParticle 8s ease-in-out infinite;
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
        background: var(--color-success-400);
        top: 80%;
        left: 20%;
        animation-delay: 4s;
    }
    
    .bento-item.interactive:hover .particle {
        opacity: 0.6;
    }
    
    @keyframes bentoParticle {
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
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .bento-item.large,
        .bento-item.wide {
            grid-column: span 1;
        }
    }
    
    @media (max-width: 768px) {
        :global(.bento-grid) {
            grid-template-columns: 1fr !important;
            gap: var(--spacing-4) !important;
        }
        
        .bento-item.medium {
            grid-row: span 1;
            min-height: 200px;
        }
        
        .bento-item.tall {
            grid-row: span 1;
            min-height: 250px;
        }
        
        .particle {
            display: none;
        }
    }
</style>