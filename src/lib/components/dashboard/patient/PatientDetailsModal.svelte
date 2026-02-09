<script lang="ts">
    export let paciente: any = null;
    export let show: boolean = false;
    export let onClose: () => void;
    
    // @ts-ignore
    import { getEntidades } from '$lib/api';
    
    let entidades: any[] = [];
    let loadingEntidades = false;
    let selectedEntidad: string = '';
    
    // Cargar entidades cuando se muestra el modal
    $: if (show && paciente) {
        loadEntidades();
        selectedEntidad = paciente.entidad || '';
    }
    
    async function loadEntidades() {
        if (entidades.length > 0) return; // Ya cargadas
        
        loadingEntidades = true;
        try {
            // @ts-ignore
            const response = await getEntidades();
            if (response.success) {
                entidades = response.data || [];
            }
        } catch (error) {
            console.error('Error cargando entidades:', error);
        } finally {
            loadingEntidades = false;
        }
    }
    
    function handleEntidadChange(event: Event) {
        const target = event.target as HTMLSelectElement;
        selectedEntidad = target.value;
        
        // Actualizar el campo entidad del paciente
        if (paciente) {
            paciente.entidad = selectedEntidad;
        }
    }
    
    async function handleSaveChanges() {
        if (!paciente) return;
        
        try {
            // Aquí podrías agregar una llamada al API para guardar los cambios
            console.log('Guardando cambios del paciente:', paciente);
            
            // Por ahora solo mostramos un mensaje
            alert('Cambios guardados correctamente (funcionalidad por implementar)');
            
        } catch (error) {
            console.error('Error guardando cambios:', error);
            alert('Error al guardar los cambios');
        }
    }
    
    $: if (!show) {
        paciente = null;
        entidades = [];
        selectedEntidad = '';
    }
    
    // Manejar click fuera del modal
    $: if (show) {
        // Usar un timeout para asegurar que el DOM esté actualizado
        setTimeout(() => {
            const handleOutsideClick = (e: MouseEvent) => {
                const target = e.target as HTMLElement;
                if (target.classList.contains('modal-overlay')) {
                    onClose();
                }
            };
            
            document.addEventListener('click', handleOutsideClick);
            
            // Cleanup cuando el modal se cierra
            return () => {
                document.removeEventListener('click', handleOutsideClick);
            };
        }, 0);
    }
</script>

{#if show && paciente}
    <div class="modal-overlay" role="presentation" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">👤 Detalles del Paciente</h2>
                <button class="close-btn" on:click={onClose} aria-label="Cerrar modal">×</button>
            </div>
            <div class="modal-body">
                <div class="patient-details">
                    <div class="detail-section">
                        <h3>Información Personal</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="field-label">Identificación:</div>
                                <div class="field-value">{paciente.identificacion || 'No especificada'}</div>
                            </div>
                            <div class="detail-item">
                                <div class="field-label">Nombres:</div>
                                <div class="field-value">{paciente.nombres || 'No especificados'}</div>
                            </div>
                            <div class="detail-item">
                                <div class="field-label">Apellidos:</div>
                                <div class="field-value">{paciente.apellidos || 'No especificados'}</div>
                            </div>
                            <div class="detail-item">
                                <div class="field-label">Género:</div>
                                <div class="field-value">{paciente.genero || 'No especificado'}</div>
                            </div>
                            <div class="detail-item">
                                <div class="field-label">Fecha Nacimiento:</div>
                                <div class="field-value">{paciente.fecnac || 'No especificada'}</div>
                            </div>
                            <div class="detail-item">
                                <div class="field-label">Estado:</div>
                                <div class="field-value">
                                    <span class="estado-badge {paciente.estado === 'Activo' ? 'activo' : 'inactivo'}">
                                        {paciente.estado || 'Desconocido'}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
<div class="detail-section">
                                    <h3>Contacto</h3>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <div class="field-label">Teléfono:</div>
                                            <div class="field-value">{paciente.telefono || 'No especificado'}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="field-label">Correo:</div>
                                            <div class="field-value">{paciente.correo || 'No especificado'}</div>
                                        </div>
                                        <div class="detail-item entidad-select-item">
                                            <label for="entidad-select">Entidad:</label>
                                            {#if loadingEntidades}
                                                <div class="loading-select">Cargando entidades...</div>
                                            {:else if entidades.length > 0}
                                                <select 
                                                    id="entidad-select"
                                                    class="entidad-select"
                                                    value={selectedEntidad}
                                                    on:change={handleEntidadChange}
                                                >
                                                    <option value="">Seleccione una entidad</option>
                                                    {#each entidades as entidad}
                                                        <option value={entidad.nombre} selected={paciente.entidad === entidad.nombre}>
                                                            {entidad.nombre}
                                                        </option>
                                                    {/each}
                                                </select>
                                            {:else}
                                                <div class="no-entidades">No hay entidades disponibles</div>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                    
                    {#if paciente.fecnac}
                        <div class="detail-section">
                            <h3>Información Adicional</h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <div class="field-label">Edad aproximada:</div>
                                    <div class="field-value">
                                        {#if paciente.fecnac}
                                            {Math.floor((new Date().getTime() - new Date(paciente.fecnac).getTime()) / (365.25 * 24 * 60 * 60 * 1000))} años
                                        {:else}
                                            No calculable
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/if}
                </div>
            </div>
            <div class="modal-footer">
                <div class="footer-info">
                    {#if paciente}
                        Paciente: {paciente.identificacion} - {paciente.nombres} {paciente.apellidos}
                    {/if}
                </div>
                <div class="footer-actions">
                    <button class="btn-primary" on:click={handleSaveChanges}>
                        💾 Guardar Cambios
                    </button>
                    <button class="btn-secondary" on:click={onClose}>
                        Cerrar
                    </button>
                </div>
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
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
        animation: fadeIn 0.2s ease-out;
    }
    
    .modal-content {
        background: white;
        border-radius: 16px;
        max-width: 800px;
        width: 90%;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: modalSlideIn 0.3s ease-out;
        position: relative;
        z-index: 10000;
        display: flex;
        flex-direction: column;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes shimmer {
        0% {
            left: -100%;
        }
        100% {
            left: 100%;
        }
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem 2.5rem;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .modal-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%, rgba(255,255,255,0.1) 100%);
        pointer-events: none;
    }
    
    .modal-header h2 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .close-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: white;
        padding: 0.75rem;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }
    
    .close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1) rotate(90deg);
    }
    
    .modal-body {
        padding: 2.5rem;
        overflow-y: auto;
        flex: 1;
    }
    
    .patient-details {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .detail-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 2rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .detail-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(0, 0, 0, 0.15);
    }
    
    .detail-section h3 {
        margin: 0 0 1.5rem 0;
        color: #1e293b;
        font-size: 1.3rem;
        font-weight: 700;
        border-bottom: 3px solid #3b82f6;
        padding-bottom: 0.75rem;
        position: relative;
    }
    
    .detail-section h3::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #2563eb);
        border-radius: 2px;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    
    .detail-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #3b82f6, #2563eb);
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .detail-item:hover {
        transform: translateX(4px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .detail-item:hover::before {
        opacity: 1;
    }
    
    .detail-item label {
        font-weight: 700;
        color: #64748b;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.75px;
        margin-bottom: 0.25rem;
    }
    
    .detail-item span {
        color: #1e293b;
        font-size: 1.1rem;
        font-weight: 600;
        line-height: 1.4;
    }
    
    .entidad-select-item {
        grid-column: 1 / -1;
    }
    
    .entidad-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        color: #1e293b;
        background: white;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .entidad-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .entidad-select:hover {
        border-color: #cbd5e1;
    }
    
    .loading-select, .no-entidades {
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        color: #64748b;
        font-size: 0.95rem;
        text-align: center;
    }
    
    .estado-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .estado-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }
    
    .estado-badge.activo {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        color: #166534;
        border: 1px solid #86efac;
    }
    
    .estado-badge.inactivo {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border: 1px solid #fca5a5;
    }
    
    .modal-footer {
        padding: 1.5rem 2.5rem;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    
    .footer-info {
        color: #64748b;
        font-size: 0.9rem;
        font-style: italic;
    }
    
    .footer-actions {
        display: flex;
        gap: 1rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
    }
    
    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(59, 130, 246, 0.4);
    }
    
    .btn-primary:hover::before {
        left: 100%;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .btn-secondary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-secondary:hover {
        background: linear-gradient(135deg, #4b5563, #374151);
        transform: translateY(-2px);
        box-shadow: 0 8px 12px -1px rgba(0, 0, 0, 0.2);
    }
    
    .btn-secondary:hover::before {
        left: 100%;
    }
    
    /* Responsive */
    @media (max-width: 640px) {
        .modal-content {
            width: 95%;
            max-height: 90vh;
        }
        
        .modal-header {
            padding: 1rem 1.5rem;
        }
        
        .modal-body {
            padding: 1rem 1.5rem;
        }
        
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem;
        }
    }
</style>