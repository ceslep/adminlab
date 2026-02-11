<script lang="ts">
    export let name: string = '';
    export let size: 'sm' | 'md' | 'lg' = 'md';
    export let customSize: string | undefined = undefined;
    
    const initials = name
        .split(' ')
        .filter(word => word.length > 0)
        .slice(0, 2)
        .map(word => word[0].toUpperCase())
        .join('');
    
    const sizeConfigs = {
        sm: {
            width: 'var(--avatar-size-sm)',
            height: 'var(--avatar-size-sm)',
            fontSize: 'var(--font-size-sm)'
        },
        md: {
            width: 'var(--avatar-size-md)',
            height: 'var(--avatar-size-md)',
            fontSize: 'var(--font-size-base)'
        },
        lg: {
            width: 'var(--avatar-size-lg)',
            height: 'var(--avatar-size-lg)',
            fontSize: 'var(--font-size-lg)'
        }
    };
    
    $: currentSize = customSize ? null : sizeConfigs[size];
    $: finalStyle = customSize 
        ? `background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; border-radius: 50%; box-shadow: var(--shadow-base); border: 2px solid rgba(255, 255, 255, 0.8); ${customSize}`
        : `background: var(--gradient-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; border-radius: 50%; box-shadow: var(--shadow-base); border: 2px solid rgba(255, 255, 255, 0.8); width: ${currentSize?.width}; height: ${currentSize?.height}; font-size: ${currentSize?.fontSize};`;
</script>

<div 
    class="avatar"
    style={finalStyle}
>
    {initials}
</div>

<style>
    .avatar {
        transition: all var(--duration-normal) var(--ease-out);
        font-family: var(--font-family-sans);
    }
    
    .avatar:hover {
        transform: scale(1.1);
        box-shadow: var(--shadow-lg);
    }
</style>