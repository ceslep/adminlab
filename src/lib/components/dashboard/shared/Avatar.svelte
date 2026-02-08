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
    
    const sizeClasses = {
        sm: 'w-8 h-8 text-sm',
        md: 'w-12 h-12 text-lg',
        lg: 'w-16 h-16 text-xl'
    };
    
    const finalSizeClass = customSize || sizeClasses[size];
</script>

<div 
    class="avatar {finalSizeClass}" 
    style="background: linear-gradient(135deg, #3b82f6, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; border-radius: 50%;">
    {initials}
</div>

<style>
    .avatar {
        transition: transform 0.2s;
    }
    
    .avatar:hover {
        transform: scale(1.05);
    }
</style>