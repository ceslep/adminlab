<!-- src/routes/login/+page.svelte -->
<script lang="ts">
  import { login, authStatus, labConfig } from '$lib/stores/auth';
  import { goto } from '$app/navigation';
  import { afterUpdate } from 'svelte';

  let password = '';
  let errorMessage: string | undefined;
  let loading = false;

  // Subscribe to authStatus changes to redirect after successful login
  authStatus.subscribe((status: AuthStatus) => {
    if (status.isAuthenticated) {
      goto('/');
    }
    // Update error message if login failed
    if (status.error) {
      errorMessage = status.error;
    }
  });

  async function handleSubmit() {
    loading = true;
    errorMessage = undefined; // Clear previous errors
    const success = await login(password);
    if (!success) {
      // Error message is already set by the store subscription
    }
    loading = false;
  }

  // Clear error message when password changes
  function handlePasswordChange(event: Event) {
    password = (event.target as HTMLInputElement).value;
    errorMessage = undefined;
  }

  afterUpdate(() => {
    // Optionally, if the login form is rendered on root and not login route
    // ensure we are not already authenticated.
    // This is handled by goto('/') in subscription, but an extra check doesn't hurt.
    if ($authStatus.isAuthenticated) {
      goto('/');
    }
  });
</script>

<svelte:head>
  <title>Acceso al Sistema</title>
  <!-- Bootstrap Icons for styling, matching PHP version's look -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</svelte:head>

<body class="bg-slate-900 h-screen flex items-center justify-center p-4">
  <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md border-t-4 border-indigo-600">
    <div class="text-center mb-8">
      <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="bi bi-shield-lock-fill text-3xl text-indigo-600" aria-hidden="true"></i>
        {#if $labConfig?.urlLogoLaboratorio}
          <img
            src={$labConfig.urlLogoLaboratorio}
            alt="Logo"
            class="w-10 h-10 object-contain absolute"
          />
        {/if}
      </div>
      <h1 class="text-2xl font-bold text-slate-800">{$labConfig?.nombreLaboratorio || 'Laboratorio Clínico'}</h1>
      <p class="text-slate-500 text-sm">Ingrese su clave de configuración</p>
    </div>

    {#if errorMessage}
      <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm flex items-center gap-2" role="alert">
        <i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i>
        {errorMessage}
      </div>
    {/if}

    <form on:submit|preventDefault={handleSubmit} class="space-y-4">
      <div>
        <label for="password-input" class="block text-xs font-bold text-slate-700 uppercase mb-1">
          Usuario / Clave
        </label>
        <div class="relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
            <i class="bi bi-key" aria-hidden="true"></i>
          </span>
          <input
            id="password-input"
            type="password"
            name="password"
            placeholder="Ingrese contraseña"
            required
            bind:value={password}
            on:input={handlePasswordChange}
            class="w-full pl-10 p-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition"
            aria-describedby="password-error"
          />
        </div>
        {#if errorMessage}
          <p id="password-error" class="text-red-500 text-xs mt-1">{errorMessage}</p>
        {/if}
      </div>

      <button
        type="submit"
        name="login"
        class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center justify-center gap-2"
        disabled={loading}
        aria-live="polite"
      >
        {#if loading}
          <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Iniciando sesión...
        {:else}
          INICIAR SESIÓN
        {/if}
      </button>
    </form>
  </div>
</body>
