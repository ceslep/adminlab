<script lang="ts">
    import { fade, fly } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import { RefreshCw, Search, Calendar, Users } from 'lucide-svelte';
    import Button from '../shared/Button.svelte';
    
    export let searchQuery: string = '';
    export let selectedDate: string = '';
    export let totalPatients: number = 0;
    export let onSearch: () => void;
    export let onDateChange: () => void;
    export let onRefresh: () => void;
    export let loading: boolean = false;
</script>

<div 
    in:fly={{ y: 20, duration: 500, easing: cubicOut }}
    class="filters-container"
>
    <div class="filters-header">
        <div class="search-section">
            <div class="input-group">
                <div class="input-icon">
                    <Search class="icon" />
                </div>
                <input 
                    id="search"
                    type="text" 
                    bind:value={searchQuery}
                    placeholder="Buscar por nombre, teléfono, email o identificación..."
                    class="search-input"
                    on:input={onSearch}
                />
            </div>
        </div>
        <div class="date-section">
            <div class="input-group">
                <div class="input-icon">
                    <Calendar class="icon" />
                </div>
                <input 
                    id="date"
                    type="date" 
                    bind:value={selectedDate}
                    class="date-input"
                    on:change={onDateChange}
                />
            </div>
        </div>
    </div>
    
    <div class="filters-footer">
        <div class="results-count">
            <Users class="count-icon" />
            <div class="count-text">
                <span class="total-number">{totalPatients}</span>
                <span class="total-label">pacientes encontrados</span>
            </div>
        </div>
        <button 
            class="refresh-button"
            class:loading={loading}
            on:click={onRefresh}
            disabled={loading}
        >
            <div class="button-content">
                {#if loading}
                    <RefreshCw class="loading-spinner" />
                {:else}
                    <RefreshCw class="refresh-icon" />
                {/if}
                <span class="button-text">{loading ? 'Actualizando...' : 'Actualizar'}</span>
            </div>
            <div class="button-glow"></div>
        </button>
    </div>
</div>

<style>
    /* Premium Filters Container */
    .filters-container {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2rem;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .filters-container::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.02), transparent);
        pointer-events: none;
    }
    
    .filters-header {
        display: flex;
        gap: 1.5rem;
        align-items: flex-end;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }
    
    .search-section {
        flex: 1;
    }
    
    .date-section {
        min-width: 280px;
    }
    
    /* Premium Input Groups */
    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        color: var(--slate-400);
        transition: color 300s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }

    .input-icon .icon {
        width: 20px;
        height: 20px;
    }
    
    .search-input, .date-input {
        width: 100%;
        padding: 16px 16px 16px 48px;
        border: 1px solid var(--slate-200);
        border-radius: 1rem;
        font-size: 14px;
        font-weight: 500;
        background: rgba(241, 245, 249, 0.6);
        color: var(--slate-900);
        transition: all 300s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: inherit;
        box-sizing: border-box;
    }

    .search-input::placeholder {
        color: var(--slate-400);
    }
    
    .search-input:focus, .date-input:focus {
        outline: none;
        border-color: var(--indigo-500);
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 0 0 1px rgba(99, 102, 241, 0.6);
    }

    .input-group:focus-within .input-icon {
        color: var(--indigo-500);
    }
    
    /* Premium Footer */
    .filters-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }
    
    /* Results Count */
    .results-count {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .count-icon {
        width: 20px;
        height: 20px;
        color: var(--indigo-500);
    }

    .count-text {
        display: flex;
        flex-direction: column;
    }

    .total-number {
        font-size: 24px;
        font-weight: 800;
        color: var(--slate-900);
        line-height: 1;
        margin-bottom: 2px;
    }

    .total-label {
        font-size: 12px;
        font-weight: 500;
        color: var(--slate-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Premium Refresh Button */
    .refresh-button {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border: none;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 300s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, var(--emerald-500), var(--green-600));
        color: white;
        box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.35);
        overflow: hidden;
    }

    .refresh-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px -5px rgba(16, 185, 129, 0.45);
        background: linear-gradient(135deg, var(--emerald-600), var(--green-700));
    }

    .refresh-button:active:not(:disabled) {
        transform: translateY(-1px);
    }

    .refresh-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .refresh-button.loading {
        cursor: wait;
    }

    .button-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .refresh-icon {
        width: 16px;
        height: 16px;
        transition: transform 300s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .refresh-button:hover:not(:disabled) .refresh-icon {
        transform: rotate(180deg);
    }

    .loading-spinner {
        width: 16px;
        height: 16px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .button-text {
        font-weight: 600;
        font-size: 14px;
    }

    .button-glow {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 500s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }

    .refresh-button:hover .button-glow {
        transform: translateX(0);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .filters-container {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .filters-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .date-section {
            min-width: auto;
        }
        
        .filters-footer {
            flex-direction: column;
            gap: 1.5rem;
            align-items: stretch;
        }

        .results-count {
            justify-content: center;
        }

        .refresh-button {
            justify-content: center;
            width: 100%;
        }

        .total-number {
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .filters-container {
            padding: 1rem;
        }

        .search-input, .date-input {
            padding: 14px 14px 14px 44px;
            font-size: 13px;
        }

        .input-icon {
            left: 14px;
        }

        .input-icon .icon {
            width: 18px;
            height: 18px;
        }
    }
</style>