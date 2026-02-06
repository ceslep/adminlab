<script lang="ts">
  import { onMount } from "svelte";
  import { fly, slide } from "svelte/transition";
  import { crossfade } from "svelte/transition";
  import "./app.css";

  // Importar el componente principal de la aplicación
  import MainPage from "./routes/+page.svelte";

  let isInitialized = $state(false);
  let globalError = $state<Error | null>(null);
  let resetGlobal = $state<(() => void) | null>(null);

  // Enhanced crossfade para transiciones suaves
  const [send, receive] = crossfade({
    duration: 400,
    easing: (t) => t * (2 - t), // ease-out-quart
  });

  // Inicialización con loading moderno
  onMount(async () => {
    try {
      // Simular inicialización sofisticada
      await new Promise((resolve) => setTimeout(resolve, 2000));
      isInitialized = true;
    } catch (error) {
      globalError = error as Error;
      console.error("App initialization error:", error);
    }
  });

  // Manejador global de errores
  function handleError(error: unknown, reset: () => void) {
    globalError = error as Error;
    resetGlobal = reset;

    // Log a servicio de monitoreo (placeholder)
    if (typeof window !== "undefined" && "gtag" in window) {
      (window as any).gtag("event", "exception", {
        description: (error as Error).message,
        fatal: false,
      });
    }
  }
</script>

<svelte:boundary onerror={handleError}>
  {#if !isInitialized}
    <div in:fly={{ duration: 600 }} out:slide={{ duration: 400 }}>
      {#if globalError}
        <!-- Error fallback moderno -->
        <div
          class="min-h-screen bg-gradient-to-br from-rose-50 to-red-50 flex items-center justify-center p-6"
        >
          <div
            class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 space-y-6"
          >
            <!-- Icono de error -->
            <div class="flex justify-center">
              <div
                class="w-16 h-16 bg-gradient-to-r from-rose-500 to-red-500 rounded-full flex items-center justify-center shadow-lg"
              >
                <svg
                  class="w-8 h-8 text-white"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                  ></path>
                </svg>
              </div>
            </div>

            <!-- Contenido del error -->
            <div class="text-center space-y-2">
              <h2 class="text-xl font-bold text-slate-900">
                Oops, algo salió mal
              </h2>
              <p class="text-slate-600 text-sm">
                {globalError?.message || "Error inesperado en la aplicación"}
              </p>
            </div>

            <!-- Botones de acción -->
            <div class="space-y-3">
              <button
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                onclick={() => {
                  globalError = null;
                  resetGlobal?.();
                }}
              >
                Intentar nuevamente
              </button>
              <button
                class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-3 px-4 rounded-xl transition-all duration-200"
                onclick={() => window.location.reload()}
              >
                Recargar página
              </button>
            </div>
          </div>
        </div>
      {:else}
        <!-- Skeleton loader moderno -->
        <div
          class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-6"
        >
          <div class="w-full max-w-md space-y-6">
            <!-- Logo skeleton -->
            <div class="flex justify-center">
              <div
                class="w-20 h-20 bg-gradient-to-r from-indigo-300 to-purple-300 rounded-2xl animate-pulse shadow-lg"
              ></div>
            </div>

            <!-- Content skeleton -->
            <div class="space-y-4">
              <div
                class="h-4 bg-gradient-to-r from-slate-200 to-slate-300 rounded-full animate-pulse"
              ></div>
              <div
                class="h-4 bg-gradient-to-r from-slate-200 to-slate-300 rounded-full w-3/4 animate-pulse"
              ></div>
              <div
                class="h-4 bg-gradient-to-r from-slate-200 to-slate-300 rounded-full w-1/2 animate-pulse"
              ></div>
            </div>

            <!-- Texto de carga con tipografía moderna -->
            <div class="text-center">
              <p class="text-slate-600 font-medium mb-2">AdminLab</p>
              <p class="text-slate-500 text-sm">Iniciando sistema...</p>
            </div>

            <!-- Indicador de progreso -->
            <div class="relative overflow-hidden h-2 bg-slate-200 rounded-full">
              <div
                class="absolute left-0 top-0 h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full animate-pulse"
                style="width: 70%;"
              ></div>
            </div>
          </div>
        </div>
      {/if}
    </div>
  {:else}
    <main
      class="min-h-screen bg-slate-50"
      in:fly={{ duration: 800 }}
      out:slide={{ duration: 600 }}
    >
      <MainPage />
    </main>
  {/if}
</svelte:boundary>a

<style>
  /* Estilos globales modernos */
  :global(html) {
    scroll-behavior: smooth;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  :global(body) {
    font-family:
      "Inter",
      system-ui,
      -apple-system,
      BlinkMacSystemFont,
      "Segoe UI",
      Roboto,
      sans-serif;
    background-color: #f8fafc;
    color: #0f172a;
  }

  /* Enhanced focus styles para accesibilidad */
  :global(*:focus) {
    outline: none;
    box-shadow: 0 0 0 2px #6366f1;
  }

  /* Scrollbar moderno */
  :global(::-webkit-scrollbar) {
    width: 8px;
    height: 8px;
  }

  :global(::-webkit-scrollbar-track) {
    background-color: #f1f5f9;
  }

  :global(::-webkit-scrollbar-thumb) {
    background-color: #cbd5e1;
    border-radius: 9999px;
  }

  :global(::-webkit-scrollbar-thumb:hover) {
    background-color: #94a3b8;
  }

  /* Transiciones suaves para elementos interactivos */
  :global(button, a, input, select, textarea) {
    transition: all 200ms;
  }

  /* Estilos de selección modernos */
  :global(::selection) {
    background-color: #e0e7ff;
    color: #312e81;
  }

  /* Animaciones de loading mejoradas */
  @keyframes shimmer {
    0% {
      background-position: -200% 0;
    }
    100% {
      background-position: 200% 0;
    }
  }

  :global(.animate-shimmer) {
    background: linear-gradient(
      90deg,
      transparent,
      rgba(255, 255, 255, 0.4),
      transparent
    );
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
  }
</style>
