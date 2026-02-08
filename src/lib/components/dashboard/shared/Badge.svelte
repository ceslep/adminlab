<script lang="ts">
    export let text: string = '';
    export let variant: 'blue' | 'green' | 'purple' | 'yellow' | 'red' | 'gray' = 'blue';
    export let size: 'sm' | 'md' = 'md';
    
    const variants = {
        blue: 'bg-blue-100 text-blue-800',
        green: 'bg-green-100 text-green-800',
        purple: 'bg-purple-100 text-purple-800',
        yellow: 'bg-yellow-100 text-yellow-800',
        red: 'bg-red-100 text-red-800',
        gray: 'bg-gray-100 text-gray-800'
    };
    
    const sizes = {
        sm: 'text-xs px-2 py-1',
        md: 'text-sm px-3 py-1'
    };
</script>

<span class="badge {variants[variant]} {sizes[size]}">
    {text}
</span>

<style>
    .badge {
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .badge:hover {
        transform: scale(1.05);
    }
</style>