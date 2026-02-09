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
        md: 'w-10 h-10 text-base',
        lg: 'w-12 h-12 text-lg'
    };
    
    const finalSizeClass = customSize || sizeClasses[size];
</script>

<div 
    class="avatar {finalSizeClass}" 
    style="background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; border-radius: 50%; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
    {initials}
</div>

<style>
    .avatar {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid rgba(255, 255, 255, 0.8);
    }
    
    .avatar:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>