<script lang="ts">
    import { fade, fly, scale } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import { Activity, Users, FileText, TrendingUp, Calendar, BarChart3, Heart, Stethoscope, Zap } from 'lucide-svelte';
    
    // Import dashboard images
    import pacientesImage from '/adminlab/assets/pacientes.png';
    import examenesImage from '/adminlab/assets/examenes.png';
    import reportesImage from '/adminlab/assets/reportes.png';
    import FeatureCard from './FeatureCard.svelte';
    import DashboardHeader from './DashboardHeader.svelte';
    
    export let userName: string = '';
    export let onLogout: () => void;
    export let onShowPatients: () => void;
</script>

<div class="dashboard-view">
    <DashboardHeader 
        title="Panel de Control"
        subtitle="Gestión del Laboratorio"
        {userName}
        {onLogout}
        showBackButton={false}
        onBack={() => {}}
    />

    <main class="dashboard-main">
        <div class="dashboard-content">
            <!-- Hero Section with Welcome Message -->
            <div 
                in:fly={{ y: 30, duration: 800, easing: cubicOut }}
                class="bento-card hero-card"
            >
                <div class="flex flex-col lg:flex-row items-center justify-between">
                    <div class="flex-1 text-center lg:text-left mb-6 lg:mb-0">
                        <div class="flex items-center gap-3 mb-4 justify-center lg:justify-start">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-slate-600">Sistema operativo</span>
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-bold bg-gradient-to-r from-slate-900 via-indigo-900 to-purple-900 bg-clip-text text-transparent mb-4">
                            Bienvenido de nuevo
                        </h1>
                        <p class="text-lg text-slate-600 max-w-2xl">
                            Gestiona tu laboratorio clínico con la plataforma más avanzada del mercado
                        </p>
                    </div>
                    <div class="flex-1 flex justify-center">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl blur-3xl opacity-20"></div>
                            <div class="relative bg-white/70 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-indigo-600">1.2K</div>
                                        <div class="text-sm text-slate-600">Pacientes</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-purple-600">3.4K</div>
                                        <div class="text-sm text-slate-600">Análisis</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-3xl font-bold text-pink-600">98%</div>
                                        <div class="text-sm text-slate-600">Precisión</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento Grid for Features -->
            <div class="bento-grid">
                <!-- Large Card - Patients -->
                <div 
                    in:fly={{ y: 40, duration: 700, delay: 200, easing: cubicOut }}
                    class="bento-card bento-card-large cursor-pointer group"
                    on:click={onShowPatients}
                >
                    <div class="relative h-full flex flex-col overflow-hidden">
                        <!-- Background Image with gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50">
                            <img 
                                src="/adminlab/assets/pacientes.png" 
                                alt="Pacientes" 
                                class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-30 transition-all duration-700 group-hover:scale-105"
                                on:error={(e) => console.log('Error loading pacientes image:', e)}
                                on:load={() => console.log('Pacientes image loaded successfully')}
                            />
                            <!-- Additional gradient for depth -->
                            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-indigo-100/30 to-purple-100/30"></div>
                        </div>
                        
                        <!-- Content Overlay with glassmorphism -->
                        <div class="absolute inset-0 bg-gradient-to-t from-white/95 via-white/70 to-transparent backdrop-blur-sm"></div>
                        
                        <div class="absolute top-4 right-4">
                            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-3 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <Users class="w-6 h-6 text-white" />
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-end p-6 relative z-20">
                            <!-- Floating particles effect -->
                            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                                <div class="particle particle-1"></div>
                                <div class="particle particle-2"></div>
                                <div class="particle particle-3"></div>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-slate-900 mb-2">Pacientes</h3>
                            <p class="text-slate-600 mb-4">Gestión completa de pacientes y sus datos médicos</p>
                            <div class="flex items-center gap-2 text-indigo-600 font-semibold">
                                <span>Gestionar Pacientes</span>
                                <div class="w-5 h-5 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-white text-xs">→</span>
                                </div>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-purple-50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-[2rem]"></div>
                    </div>
                </div>

                <!-- Medium Card - Exams -->
                <div 
                    in:fly={{ y: 40, duration: 700, delay: 300, easing: cubicOut }}
                    class="bento-card bento-card-medium"
                >
                    <div class="relative h-full flex flex-col overflow-hidden">
                        <!-- Background Image with gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50">
                            <img 
                                src="/adminlab/assets/examenes.png" 
                                alt="Exámenes" 
                                class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-30 transition-all duration-700 group-hover:scale-105"
                                on:error={(e) => console.log('Error loading examenes image:', e)}
                                on:load={() => console.log('Examenes image loaded successfully')}
                            />
                            <!-- Additional gradient for depth -->
                            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-emerald-100/30 to-green-100/30"></div>
                        </div>
                        
                        <!-- Content Overlay with glassmorphism -->
                        <div class="absolute inset-0 bg-gradient-to-t from-white/95 via-white/70 to-transparent backdrop-blur-sm"></div>
                        
                        <div class="absolute top-4 right-4">
                            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl p-3 shadow-lg">
                                <Activity class="w-6 h-6 text-white" />
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-end p-6 relative z-20">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Exámenes</h3>
                            <p class="text-slate-600 text-sm mb-4">Visualización y gestión de resultados</p>
                            <div class="flex items-center gap-2 text-slate-400 text-sm font-medium">
                                <span>Próximamente</span>
                                <Zap class="w-4 h-4" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medium Card - Reports -->
                <div 
                    in:fly={{ y: 40, duration: 700, delay: 400, easing: cubicOut }}
                    class="bento-card bento-card-medium"
                >
                    <div class="relative h-full flex flex-col overflow-hidden">
                        <!-- Background Image with gradient overlay -->
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50">
                            <img 
                                src="/adminlab/assets/reportes.png" 
                                alt="Reportes" 
                                class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-30 transition-all duration-700 group-hover:scale-105"
                                on:error={(e) => console.log('Error loading reportes image:', e)}
                                on:load={() => console.log('Reportes image loaded successfully')}
                            />
                            <!-- Additional gradient for depth -->
                            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-amber-100/30 to-orange-100/30"></div>
                        </div>
                        
                        <!-- Content Overlay with glassmorphism -->
                        <div class="absolute inset-0 bg-gradient-to-t from-white/95 via-white/70 to-transparent backdrop-blur-sm"></div>
                        
                        <div class="absolute top-4 right-4">
                            <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-3 shadow-lg">
                                <FileText class="w-6 h-6 text-white" />
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col justify-end p-6 relative z-20">
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Reportes</h3>
                            <p class="text-slate-600 text-sm mb-4">Generación de informes y estadísticas</p>
                            <div class="flex items-center gap-2 text-slate-400 text-sm font-medium">
                                <span>Próximamente</span>
                                <Calendar class="w-4 h-4" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Small Cards -->
                <div 
                    in:fly={{ y: 40, duration: 700, delay: 500, easing: cubicOut }}
                    class="bento-card bento-card-small"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-slate-900">24h</div>
                            <div class="text-sm text-slate-600">Tiempo respuesta</div>
                        </div>
                        <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl p-2">
                            <TrendingUp class="w-5 h-5 text-white" />
                        </div>
                    </div>
                </div>

                <div 
                    in:fly={{ y: 40, duration: 700, delay: 600, easing: cubicOut }}
                    class="bento-card bento-card-small"
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-2xl font-bold text-slate-900">99.9%</div>
                            <div class="text-sm text-slate-600">Uptime</div>
                        </div>
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-2">
                            <BarChart3 class="w-5 h-5 text-white" />
                        </div>
                    </div>
                </div>

                <!-- Wide Bottom Card -->
                <div 
                    in:fly={{ y: 40, duration: 700, delay: 700, easing: cubicOut }}
                    class="bento-card bento-card-wide"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl p-4">
                                <Heart class="w-8 h-8 text-white" />
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-slate-900">Salud del Sistema</h3>
                                <p class="text-slate-600">Todos los sistemas operativos correctamente</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-medium text-green-600">Óptimo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
    .dashboard-view {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
    }
    
    .dashboard-main {
        max-width: 1600px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }
    
    .dashboard-content {
        display: flex;
        flex-direction: column;
        gap: 3rem;
    }
    
    /* Hero Card */
    .hero-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 3rem;
        padding: 3rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        position: relative;
        overflow: hidden;
    }
    
    .hero-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
        pointer-events: none;
    }
    
    /* Bento Grid */
    .bento-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        grid-auto-rows: minmax(200px, auto);
    }
    
    .bento-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 2rem;
        padding: 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .bento-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .bento-card-large {
        grid-column: span 2;
        grid-row: span 2;
        min-height: 400px;
    }
    
    .bento-card-medium {
        grid-row: span 2;
        min-height: 300px;
    }
    
    .bento-card-small {
        min-height: 150px;
    }
    
    .bento-card-wide {
        grid-column: span 2;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .bento-card-large,
        .bento-card-wide {
            grid-column: span 1;
        }
    }
    
    @media (max-width: 768px) {
        .dashboard-main {
            padding: 2rem 1rem;
        }
        
        .hero-card {
            padding: 2rem;
        }
        
        .bento-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .bento-card-medium {
            grid-row: span 1;
        }
    }

    /* Floating Particles Animation */
    .particle {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        opacity: 0;
        animation: float-particle 8s ease-in-out infinite;
    }

    .particle-1 {
        width: 4px;
        height: 4px;
        background: var(--indigo-400);
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }

    .particle-2 {
        width: 3px;
        height: 3px;
        background: var(--violet-400);
        top: 60%;
        left: 80%;
        animation-delay: 2s;
    }

    .particle-3 {
        width: 5px;
        height: 5px;
        background: var(--purple-400);
        top: 80%;
        left: 20%;
        animation-delay: 4s;
    }

    .bento-card:hover .particle {
        opacity: 0.6;
    }

    /* Image Display Fix */
    .bento-card img {
        max-width: 100%;
        height: 100%;
        object-fit: cover;
        pointer-events: none;
        user-select: none;
        -webkit-user-drag: none;
        -khtml-user-drag: none;
        -moz-user-drag: none;
        -o-user-drag: none;
    }

    @keyframes float-particle {
        0%, 100% {
            transform: translateY(0px) translateX(0px);
            opacity: 0;
        }
        25% {
            opacity: 0.4;
            transform: translateY(-10px) translateX(5px);
        }
        50% {
            opacity: 0.6;
            transform: translateY(-20px) translateX(-5px);
        }
        75% {
            opacity: 0.3;
            transform: translateY(-10px) translateX(3px);
        }
    }

    @media (max-width: 768px) {
        .particle {
            display: none;
        }
    }
</style>