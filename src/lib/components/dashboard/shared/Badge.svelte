<script lang="ts">
    export let text: string = '';
    export let variant: 'indigo' | 'emerald' | 'violet' | 'amber' | 'rose' | 'slate' | 'blue' | 'green' | 'purple' | 'yellow' = 'indigo';
    export let size: 'sm' | 'md' = 'md';
    
    const variants = {
        indigo: 'bg-indigo-50 text-indigo-700 border border-indigo-200/60',
        emerald: 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
        violet: 'bg-violet-50 text-violet-700 border border-violet-200/60',
        amber: 'bg-amber-50 text-amber-700 border border-amber-200/60',
        rose: 'bg-rose-50 text-rose-700 border border-rose-200/60',
        slate: 'bg-slate-50 text-slate-700 border border-slate-200/60',
        // Legacy variants mapped to new ones
        blue: 'bg-indigo-50 text-indigo-700 border border-indigo-200/60',
        green: 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
        purple: 'bg-violet-50 text-violet-700 border border-violet-200/60',
        yellow: 'bg-amber-50 text-amber-700 border border-amber-200/60'
    };
    
    const sizes = {
        sm: 'text-xs px-2.5 py-1',
        md: 'text-sm px-3 py-1.5'
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
        font-weight: 600;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }
    
    .badge:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
</style>