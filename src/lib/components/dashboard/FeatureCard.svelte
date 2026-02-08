<script lang="ts">
    import Button from './shared/Button.svelte';
    
    export let icon: string = '';
    export let title: string = '';
    export let description: string = '';
    export let buttonText: string = '';
    export let onClick: () => void;
    export let disabled: boolean = false;
    export let variant: 'primary' | 'secondary' | 'success' = 'primary';
</script>

<div class="feature-card" class:disabled={disabled}>
    <div class="feature-icon">{icon}</div>
    <h3 class="feature-title">{title}</h3>
    <p class="feature-description">{description}</p>
    <Button 
        {variant} 
        fullWidth 
        {disabled}
        onClick={onClick}
    >
        {buttonText}
    </Button>
</div>

<style>
    .feature-card {
        background: white;
        border-radius: 0.75rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }
    
    .feature-card:hover:not(.disabled) {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #3b82f6;
    }
    
    .feature-card.disabled {
        opacity: 0.7;
    }
    
    .feature-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        line-height: 1;
    }
    
    .feature-title {
        margin-bottom: 0.5rem;
        color: #1f2937;
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .feature-description {
        color: #6b7280;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }
</style>