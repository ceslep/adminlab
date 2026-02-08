<script lang="ts">
    export let paciente: any;
    export let show: boolean = false;
    export let onClose: () => void;
</script>

{#if show}
    <div class="modal-overlay" role="dialog" aria-modal="true" tabindex="-1" on:click={(e) => e.target === e.currentTarget && onClose()}>
        <div class="modal-container">
            <div class="modal-header">
                <h3>Exámenes de {paciente?.nombre_completo}</h3>
                <button on:click={onClose}>✕</button>
            </div>
            <div class="modal-content">
                <h4>Lista de Exámenes</h4>
                {#if paciente?.examenes && paciente.examenes.length > 0}
                    <ul>
                        {#each paciente.examenes as examen}
                            <li>{examen}</li>
                        {/each}
                    </ul>
                {:else}
                    <p>No hay exámenes registrados para este paciente.</p>
                {/if}
            </div>
        </div>
    </div>
{/if}

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 1rem;
    }
    
    .modal-container {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        color: #1f2937;
    }
    
    .modal-header button {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #6b7280;
    }
    
    .modal-content h4 {
        margin: 0 0 1rem 0;
        color: #374151;
    }
    
    .modal-content ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .modal-content li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f3f4f6;
        color: #4b5563;
    }
    
    .modal-content p {
        color: #6b7280;
        text-align: center;
        padding: 2rem 0;
    }
</style>