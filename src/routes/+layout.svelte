<!-- src/routes/+layout.svelte -->
<script lang="ts">
  import { onMount } from 'svelte';
  import { authStatus, initializeAuth } from '$lib/stores/auth';
  import { goto } from '$app/navigation';
  import '../app.css'; // Import Tailwind CSS, assuming it's configured in app.css

  // Call initializeAuth when the component mounts
  onMount(() => {
    initializeAuth();
  });

  // Reactive statement to redirect based on authentication status
  $: if (!$authStatus.isAuthenticated && $authStatus.userName === null) {
    goto('/login');
  } else if ($authStatus.isAuthenticated && $authStatus.userName !== null) {
    // If authenticated, ensure we are not on the login page
    // (this is handled by the login page itself, but good as a fallback)
    // If current path is /login, redirect to /
    if (window.location.pathname === '/login') {
      goto('/');
    }
  }
</script>

<!-- The actual page content will be rendered here (children of the layout) -->
{#if $authStatus.isAuthenticated}
  <!-- Main application layout for authenticated users -->
  <div class="h-screen flex flex-col">
    <slot />
  </div>
{:else}
  <!-- Render a minimal wrapper while authentication is being checked or if not authenticated -->
  <!-- The login page will handle its own layout entirely, so this div might not be strictly needed for /login -->
  <!-- But it acts as a fallback or a loading screen for other routes if not authenticated -->
  {#if $authStatus.userName === null && !$authStatus.error}
    <!-- Show a loading indicator while trying to initialize auth and before redirecting -->
    <div class="h-screen w-full flex items-center justify-center bg-slate-100">
      <div class="flex flex-col items-center justify-center">
        <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-slate-600">Cargando...</p>
      </div>
    </div>
  {:else}
    <!-- Render children, which might be the login page -->
    <slot />
  {/if}
{/if}

<style lang="postcss">
  /* Global styles, potentially from Tailwind's base, components, and utilities */
  :global(html, body) {
    @apply h-full overflow-hidden;
  }
</style>
