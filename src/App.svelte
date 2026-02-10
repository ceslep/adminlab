<script lang="ts">
    import { onMount } from 'svelte';
    import { isAuthenticated, user, logout, login } from './stores/auth';
    import type { User } from './lib/types';
    import Login from './lib/components/Login.saas.svelte';
    import Dashboard from './lib/components/Dashboard.svelte';
    
    function handleLoginSuccess(event: CustomEvent) {
        console.log('✅ Login exitoso en App.svelte', event.detail);
        const userData = event.detail;
        
        // Actualizar el store de autenticación
        login(userData);
        
        // Verificar que se actualizó correctamente
        setTimeout(() => {
            console.log('📊 Estado después del login - isAuthenticated:', $isAuthenticated, 'user:', $user);
        }, 100);
    }
    
    function handleLogout() {
        console.log('🔴 Logout procesado en App.svelte');
        logout();
        console.log('Estado después del logout - isAuthenticated:', $isAuthenticated);
    }
    
    // Debug al montar el componente
    onMount(() => {
        console.log('App montado - Estado inicial:', { 
            isAuthenticated: $isAuthenticated, 
            user: $user 
        });
    });
</script>

{#if $isAuthenticated}
    <Dashboard on:logout={handleLogout} />
{:else}
    <Login on:loginSuccess={handleLoginSuccess} />
{/if}