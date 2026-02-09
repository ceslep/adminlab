<script lang="ts">
    import { createEventDispatcher } from 'svelte';
    import { fly, fade } from 'svelte/transition';
    import { cubicOut } from 'svelte/easing';
    import { 
        Home, 
        Users, 
        Activity, 
        FileText, 
        Settings, 
        LogOut, 
        Menu, 
        X,
        TrendingUp,
        Calendar,
        Heart,
        ChevronLeft,
        ChevronRight
    } from 'lucide-svelte';
    import Button from './shared/Button.svelte';
    
    export let isCollapsed: boolean = false;
    export let currentPath: string = '/';
    export let userName: string = '';
    
    const dispatch = createEventDispatcher();
    
    function toggleSidebar() {
        isCollapsed = !isCollapsed;
        dispatch('toggle', { isCollapsed });
    }
    
    const navigation = [
        { icon: Home, label: 'Dashboard', path: '/', active: currentPath === '/' },
        { icon: Users, label: 'Pacientes', path: '/patients', active: currentPath === '/patients' },
        { icon: Activity, label: 'Exámenes', path: '/exams', active: currentPath === '/exams' },
        { icon: FileText, label: 'Reportes', path: '/reports', active: currentPath === '/reports' },
        { icon: Calendar, label: 'Citas', path: '/appointments', active: currentPath === '/appointments' },
        { icon: TrendingUp, label: 'Estadísticas', path: '/analytics', active: currentPath === '/analytics' },
    ];
    
    function handleLogout() {
        dispatch('logout');
    }
    
    function handleNavigation(path: string) {
        currentPath = path;
        dispatch('navigate', { path });
    }
</script>

<div 
    class="sidebar-container"
    class:collapsed={isCollapsed}
>
    <!-- Sidebar Content -->
    <aside 
        class="sidebar"
        in:fly={{ x: -20, duration: 300, easing: cubicOut }}
    >
        <!-- Header -->
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo">
                    <Heart class="w-6 h-6 text-white" />
                </div>
                {#if !isCollapsed}
                    <div 
                        in:fade={{ duration: 300, delay: 200 }}
                        class="logo-text"
                    >
                        AdminLab
                    </div>
                {/if}
            </div>
            
            <button 
                class="toggle-button"
                on:click={toggleSidebar}
                aria-label={isCollapsed ? 'Expand sidebar' : 'Collapse sidebar'}
            >
                <div class="toggle-content">
                    {#if isCollapsed}
                        <ChevronRight class="toggle-icon" />
                    {:else}
                        <ChevronLeft class="toggle-icon" />
                    {/if}
                </div>
                <div class="toggle-glow"></div>
            </button>
        </div>

        <!-- User Info -->
        {#if !isCollapsed}
            <div 
                in:fade={{ duration: 300, delay: 300 }}
                class="user-section"
            >
                <div class="user-avatar">
                    <span class="user-initials">{userName.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)}</span>
                </div>
                <div class="user-info">
                    <div class="user-name">{userName}</div>
                    <div class="user-role">Administrador</div>
                </div>
            </div>
        {/if}

        <!-- Navigation -->
        <nav class="sidebar-nav">
            {#each navigation as item, index}
                <button
                    class="nav-item"
                    class:active={item.active}
                    on:click={() => handleNavigation(item.path)}
                    style:--i={index}
                >
                    <div class="nav-icon">
                        <item.icon class="w-5 h-5" />
                    </div>
                    {#if !isCollapsed}
                        <span 
                            in:fade={{ duration: 200, delay: 400 + (index * 50) }}
                            class="nav-label"
                        >
                            {item.label}
                        </span>
                    {/if}
                    {#if item.active}
                        <div class="nav-indicator"></div>
                    {/if}
                </button>
            {/each}
        </nav>

        <!-- Footer -->
        <div class="sidebar-footer">
            {#if !isCollapsed}
                <button
                    in:fade={{ duration: 300, delay: 600 }}
                    class="footer-item"
                    on:click={() => dispatch('settings')}
                >
                    <Settings class="w-5 h-5" />
                    <span>Configuración</span>
                </button>
                
                <button
                    in:fade={{ duration: 300, delay: 700 }}
                    class="footer-item logout"
                    on:click={handleLogout}
                >
                    <LogOut class="w-5 h-5" />
                    <span>Cerrar Sesión</span>
                </button>
            {:else}
                <button
                    class="footer-item collapsed"
                    on:click={() => dispatch('settings')}
                    title="Configuración"
                >
                    <Settings class="w-5 h-5" />
                </button>
                
                <button
                    class="footer-item collapsed logout"
                    on:click={handleLogout}
                    title="Cerrar Sesión"
                >
                    <LogOut class="w-5 h-5" />
                </button>
            {/if}
        </div>
    </aside>
    
    <!-- Overlay for mobile -->
    {#if !isCollapsed}
        <div 
            class="sidebar-overlay"
            on:click={toggleSidebar}
            in:fade={{ duration: 200 }}
        ></div>
    {/if}
</div>

<style>
    .sidebar-container {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1000;
        display: flex;
        align-items: stretch;
    }
    
    .sidebar {
        width: 280px;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .sidebar-container.collapsed .sidebar {
        width: 80px;
    }
    
    /* Header */
    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 2rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .logo-container {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .logo {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .logo-text {
        font-size: 1.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #1e293b, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .toggle-button {
        width: 40px;
        height: 40px;
        border-radius: 0.75rem;
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.2);
        color: #6366f1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }

    .toggle-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toggle-icon {
        width: 18px;
        height: 18px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .toggle-glow {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    
    .toggle-button:hover {
        background: rgba(99, 102, 241, 0.2);
        transform: scale(1.05);
    }

    .toggle-button:hover .toggle-icon {
        transform: scale(1.1);
    }

    .toggle-button:hover .toggle-glow {
        transform: translateX(0);
    }
    
    /* User Section */
    .user-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 1rem;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .user-initials {
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .user-info {
        flex: 1;
        min-width: 0;
    }
    
    .user-name {
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .user-role {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }
    
    /* Navigation */
    .sidebar-nav {
        flex: 1;
        padding: 1.5rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        overflow-y: auto;
    }
    
    .nav-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 1rem;
        border: 1px solid transparent;
        color: #64748b;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        text-decoration: none;
    }
    
    .nav-item:hover {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        transform: translateX(4px);
    }
    
    .nav-item.active {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.35);
    }
    
    .nav-icon {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .nav-label {
        font-weight: 600;
        white-space: nowrap;
    }
    
    .nav-indicator {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
    }
    
    /* Footer */
    .sidebar-footer {
        padding: 1.5rem 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .footer-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-radius: 1rem;
        color: #64748b;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
    }
    
    .footer-item:hover {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        transform: translateX(2px);
    }
    
    .footer-item.logout:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .footer-item.collapsed {
        justify-content: center;
        padding: 1rem;
    }
    
    /* Overlay */
    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: -1;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            width: 280px;
            transform: translateX(-100%);
        }
        
        .sidebar-container:not(.collapsed) .sidebar {
            transform: translateX(0);
        }
        
        .sidebar-container.collapsed .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar-overlay {
            display: block;
        }
    }
    
    @media (min-width: 769px) {
        .sidebar-overlay {
            display: none;
        }
    }
</style>