<script lang="ts">
    import Login from './lib/components/Login.svelte';
    import Dashboard from './lib/components/Dashboard.svelte';
    import { isAuthenticated, user, login, logout } from './stores/auth';
    import type { User } from './lib/types'; // Import User from types.ts

    // Function to handle successful login from the Login component
    function handleLoginSuccess(event: CustomEvent<User>): void {
        login(event.detail); // Update the auth store
    }

    // Function to handle logout from the Dashboard component
    function handleLogout(): void {
        logout(); // Clear authentication state
    }
</script>

<main class="relative h-screen w-screen flex flex-col justify-center items-center">
    <ul class="particles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>
    {#if $isAuthenticated}
        <Dashboard userName={($user && $user.nombre) || 'Usuario'} on:logout={handleLogout} />
    {:else}
        <Login on:loginSuccess={handleLoginSuccess} />
    {/if}
</main>

<style lang="postcss">
    /* Global styles mejorados con mejor contraste */
    :global(body) {
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #f8fafc;
    }
    
    /* Mejorar visibilidad de partículas de fondo */
    :global(.particles) {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }
    
    :global(.particles li) {
        position: absolute;
        width: 4px;
        height: 4px;
        background: rgba(6, 182, 212, 0.5);
        border-radius: 50%;
        animation: float 20s infinite linear;
    }
    
    @keyframes float {
        0% {
            transform: translateY(100vh) translateX(0);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            transform: translateY(-100vh) translateX(100px);
            opacity: 0;
        }
    }
    
    :global(.particles li:nth-child(1)) { left: 10%; animation-delay: 0s; animation-duration: 15s; }
    :global(.particles li:nth-child(2)) { left: 20%; animation-delay: 2s; animation-duration: 20s; }
    :global(.particles li:nth-child(3)) { left: 30%; animation-delay: 4s; animation-duration: 18s; }
    :global(.particles li:nth-child(4)) { left: 40%; animation-delay: 6s; animation-duration: 22s; }
    :global(.particles li:nth-child(5)) { left: 50%; animation-delay: 8s; animation-duration: 16s; }
    :global(.particles li:nth-child(6)) { left: 60%; animation-delay: 10s; animation-duration: 19s; }
    :global(.particles li:nth-child(7)) { left: 70%; animation-delay: 12s; animation-duration: 21s; }
    :global(.particles li:nth-child(8)) { left: 80%; animation-delay: 14s; animation-duration: 17s; }
    :global(.particles li:nth-child(9)) { left: 90%; animation-delay: 16s; animation-duration: 23s; }
    :global(.particles li:nth-child(10)) { left: 95%; animation-delay: 18s; animation-duration: 20s; }
</style>
