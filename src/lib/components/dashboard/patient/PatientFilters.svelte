<script lang="ts">
    import Button from '../shared/Button.svelte';
    
    export let searchQuery: string = '';
    export let selectedDate: string = '';
    export let totalPatients: number = 0;
    export let onSearch: () => void;
    export let onDateChange: () => void;
    export let onRefresh: () => void;
    export let loading: boolean = false;
</script>

<div class="filters-container">
    <div class="filters-header">
        <div class="search-section">
            <label for="search" class="filter-label">Buscar pacientes</label>
            <input 
                id="search"
                type="text" 
                bind:value={searchQuery}
                placeholder="Buscar por nombre, teléfono, email o identificación..."
                class="search-input"
                on:input={onSearch}
            />
        </div>
        <div class="date-section">
            <label for="date" class="filter-label">Fecha</label>
            <input 
                id="date"
                type="date" 
                bind:value={selectedDate}
                class="date-input"
                on:change={onDateChange}
            />
        </div>
    </div>
    
    <div class="filters-footer">
        <span class="results-count">
            <strong>Total:</strong> {totalPatients} pacientes
        </span>
        <Button 
            variant="success" 
            size="sm" 
            icon="🔄"
            loading={loading}
            onClick={onRefresh}
        >
            Actualizar
        </Button>
    </div>
</div>

<style>
    .filters-container {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
    }
    
    .filters-header {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        margin-bottom: 1rem;
    }
    
    .search-section {
        flex: 1;
    }
    
    .date-section {
        min-width: 200px;
    }
    
    .filter-label {
        display: block;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #374151;
        font-size: 0.875rem;
    }
    
    .search-input, .date-input {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s;
        font-family: inherit;
        width: 100%;
        box-sizing: border-box;
    }
    
    .search-input:focus, .date-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .search-input::placeholder {
        color: #9ca3af;
    }
    
    .filters-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.875rem;
        color: #6b7280;
    }
    
    .results-count {
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .filters-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .date-section {
            min-width: auto;
        }
        
        .filters-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
    }
</style>