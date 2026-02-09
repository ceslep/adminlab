<script lang="ts">
    import { fade, fly, scale } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import { User, FileText, Activity, Phone, Mail, Calendar, ChevronRight, Eye, Stethoscope } from 'lucide-svelte';
    import Avatar from '../shared/Avatar.svelte';
    import Badge from '../shared/Badge.svelte';
    import Button from '../shared/Button.svelte';
    import { createEventDispatcher } from 'svelte';
    
    export let paciente: any;
    export let onViewExams: ((event: CustomEvent) => void) | undefined = undefined;
    export let onViewDetails: ((event: CustomEvent) => void) | undefined = undefined;
    
    const dispatch = createEventDispatcher();
    
    function handleViewDetails() {
        if (onViewDetails) {
            onViewDetails(new CustomEvent('viewDetails', { detail: { paciente } }));
        } else {
            dispatch('viewDetails', { paciente });
        }
    }
    
    function handleViewExams() {
        console.log('🧪 handleViewExams llamado en PatientCard para:', paciente.nombre_completo);
        console.log('📋 onViewExams prop existe:', !!onViewExams);
        
        try {
            if (onViewExams) {
                console.log('📤 Enviando evento vía onViewExams prop');
                const event = new CustomEvent('viewExams', { detail: { paciente } });
                onViewExams(event);
            } else {
                console.log('📤 Enviando evento vía dispatch');
                dispatch('viewExams', { paciente });
            }
        } catch (error) {
            console.error('❌ Error en handleViewExams:', error);
        }
    }
</script>

<div 
    in:fly={{ y: 20, duration: 500, delay: 100, easing: cubicOut }}
    class="patient-card"
>
    <!-- Background decoration -->
    <div class="card-bg-decoration"></div>
    
    <!-- Header con avatar -->
    <div class="patient-header">
        <div class="avatar-wrapper">
            <Avatar name={paciente.nombre_completo} size="md" />
            <div class="avatar-glow"></div>
        </div>
        <div class="patient-info">
            <h4 class="patient-name">{paciente.nombre_completo}</h4>
            <div class="patient-contact">
                <div class="contact-item">
                    <Phone class="contact-icon" />
                    <span>{paciente.telefono}</span>
                </div>
            </div>
        </div>
        <div class="patient-age">
            <span class="age-number">{paciente.edad}</span>
            <span class="age-text">años</span>
        </div>
    </div>
    
    <!-- Badges -->
    <div class="patient-badges">
        <Badge text={paciente.genero} variant="indigo" size="sm" />
        <Badge text={paciente.estado} variant="emerald" size="sm" />
        <Badge text={paciente.entidad} variant="violet" size="sm" />
        <Badge text={`${paciente.total_examenes} exámenes`} variant="amber" size="sm" />
    </div>
    
    <!-- Información adicional -->
    <div class="patient-details">
        <div class="detail-item">
            <div class="detail-icon">
                <User class="icon" />
            </div>
            <div class="detail-content">
                <span class="detail-label">Identificación</span>
                <span class="detail-value">{paciente.identificacion}</span>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-icon">
                <Mail class="icon" />
            </div>
            <div class="detail-content">
                <span class="detail-label">Email</span>
                <span class="detail-value">{paciente.email}</span>
            </div>
        </div>
    </div>
    
    <!-- Botones de acción -->
    <div class="patient-actions">
        <button 
            class="action-button primary"
            on:click={handleViewDetails}
        >
            <div class="button-content">
                <Eye class="button-icon" />
                <span class="button-text">Ver Detalles</span>
                <ChevronRight class="button-arrow" />
            </div>
            <div class="button-glow"></div>
        </button>
        
        <button 
            class="action-button secondary"
            on:click={handleViewExams}
        >
            <div class="button-content">
                <Stethoscope class="button-icon" />
                <span class="button-text">Exámenes</span>
                <ChevronRight class="button-arrow" />
            </div>
            <div class="button-glow"></div>
        </button>
    </div>
</div>

<style>
    /* Premium Patient Card */
    .patient-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2rem;
        padding: 2rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .card-bg-decoration {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.02), transparent);
        opacity: 0;
        transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    
    .patient-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .patient-card:hover .card-bg-decoration {
        opacity: 1;
    }

    .patient-card:hover .avatar-glow {
        opacity: 1;
        transform: scale(1.2);
    }

    .patient-card:hover .action-button .button-arrow {
        transform: translateX(4px);
    }
    
    /* Premium Header */
    .patient-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .avatar-wrapper {
        position: relative;
        margin-right: 1rem;
    }

    .avatar-glow {
        position: absolute;
        inset: -8px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.1), transparent);
        border-radius: 50%;
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    
    .patient-info {
        flex: 1;
        min-width: 0;
    }
    
    .patient-name {
        margin: 0 0 0.5rem 0;
        font-size: 1.25rem;
        color: var(--slate-900);
        font-weight: 700;
        line-height: 1.2;
        word-break: break-word;
    }

    .patient-contact {
        margin: 0;
        color: var(--slate-600);
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .contact-icon {
        width: 16px;
        height: 16px;
        color: var(--slate-400);
    }

    .patient-age {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: linear-gradient(135deg, var(--indigo-50), var(--violet-50));
        border-radius: 1rem;
        padding: 8px 12px;
        min-width: 60px;
    }

    .age-number {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--indigo-600);
        line-height: 1;
    }

    .age-text {
        font-size: 0.75rem;
        color: var(--indigo-500);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Premium Badges */
    .patient-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
    }
    
    /* Premium Details */
    .patient-details {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.75rem;
        background: rgba(241, 245, 249, 0.5);
        border-radius: 0.75rem;
        border: 1px solid rgba(226, 232, 240, 0.6);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .detail-item:hover {
        background: rgba(241, 245, 249, 0.8);
        transform: translateX(4px);
    }

    .detail-icon {
        width: 16px;
        height: 16px;
        color: var(--slate-400);
        flex-shrink: 0;
    }

    .detail-content {
        flex: 1;
        min-width: 0;
    }

    .detail-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--slate-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .detail-value {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--slate-700);
        word-break: break-word;
        line-height: 1.4;
    }
    
    /* Premium Action Buttons */
    .patient-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        position: relative;
        z-index: 2;
    }

    .action-button {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 16px;
        border: none;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .action-button.primary {
        background: linear-gradient(135deg, var(--indigo-500), var(--violet-600));
        color: white;
        box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.35);
    }

    .action-button.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px -5px rgba(99, 102, 241, 0.45);
        background: linear-gradient(135deg, var(--indigo-600), var(--violet-700));
    }

    .action-button.secondary {
        background: linear-gradient(135deg, var(--emerald-500), var(--green-600));
        color: white;
        box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.35);
    }

    .action-button.secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px -5px rgba(16, 185, 129, 0.45);
        background: linear-gradient(135deg, var(--emerald-600), var(--green-700));
    }

    .action-button:active {
        transform: translateY(-1px);
    }

    .button-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .button-icon {
        width: 16px;
        height: 16px;
    }

    .button-text {
        font-weight: 600;
    }

    .button-arrow {
        width: 14px;
        height: 14px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .button-glow {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }

    .action-button:hover .button-glow {
        transform: translateX(0);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .patient-card {
            padding: 1.5rem;
        }

        .patient-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .avatar-wrapper {
            margin-right: 0;
        }

        .patient-info {
            width: 100%;
        }

        .patient-age {
            position: absolute;
            top: 0;
            right: 0;
        }

        .patient-actions {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .action-button {
            padding: 14px 20px;
        }
    }

    @media (max-width: 480px) {
        .patient-card {
            padding: 1rem;
            border-radius: 1.5rem;
        }

        .patient-name {
            font-size: 1.125rem;
        }

        .detail-item {
            padding: 0.625rem;
        }
    }
</style>