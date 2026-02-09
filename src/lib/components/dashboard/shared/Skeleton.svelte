<script lang="ts">
    export let lines: number = 3;
    export let height: string = 'h-4';
    export let avatar: boolean = false;
    export let title: boolean = false;
    export let paragraph: boolean = true;
</script>

<div class="skeleton-container">
    {#if avatar}
        <div class="skeleton-avatar"></div>
    {/if}
    
    {#if title}
        <div class="skeleton-title"></div>
    {/if}
    
    {#if paragraph}
        {#each Array(lines) as _, i}
            <div class="skeleton-line" style:--i={i}></div>
        {/each}
    {/if}
</div>

<style>
    .skeleton-container {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        width: 100%;
    }
    
    .skeleton-avatar {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        background: linear-gradient(90deg, 
            rgba(226, 232, 240, 0.3) 25%, 
            rgba(226, 232, 240, 0.6) 50%, 
            rgba(226, 232, 240, 0.3) 75%
        );
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    
    .skeleton-title {
        height: 1.5rem;
        width: 60%;
        border-radius: 0.5rem;
        background: linear-gradient(90deg, 
            rgba(226, 232, 240, 0.3) 25%, 
            rgba(226, 232, 240, 0.6) 50%, 
            rgba(226, 232, 240, 0.3) 75%
        );
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }
    
    .skeleton-line {
        height: 1rem;
        border-radius: 0.375rem;
        background: linear-gradient(90deg, 
            rgba(226, 232, 240, 0.3) 25%, 
            rgba(226, 232, 240, 0.6) 50%, 
            rgba(226, 232, 240, 0.3) 75%
        );
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
        animation-delay: calc(var(--i) * 0.1s);
    }
    
    .skeleton-line:nth-child(1) { width: 90%; }
    .skeleton-line:nth-child(2) { width: 80%; }
    .skeleton-line:nth-child(3) { width: 70%; }
    .skeleton-line:nth-child(4) { width: 85%; }
    .skeleton-line:nth-child(5) { width: 75%; }
    
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .skeleton-avatar,
        .skeleton-title,
        .skeleton-line {
            background: linear-gradient(90deg, 
                rgba(71, 85, 105, 0.3) 25%, 
                rgba(71, 85, 105, 0.6) 50%, 
                rgba(71, 85, 105, 0.3) 75%
            );
        }
    }
</style>