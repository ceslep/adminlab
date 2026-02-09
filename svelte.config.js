import { vitePreprocess } from '@sveltejs/vite-plugin-svelte'

/** @type {import("@sveltejs/vite-plugin-svelte").SvelteConfig} */
export default {
  // Consult https://svelte.dev/docs#compile-time-svelte-preprocess
  // for more information about preprocessors
  preprocess: vitePreprocess(),
  
  // Compiler options for premium UX performance
  compilerOptions: {
    // Enable CSS optimizations for pixel-perfect rendering
    css: 'injected',
    
    // Enable dev source maps for better debugging
    dev: true,
    
    // Hydration optimization for smooth transitions
    hydratable: true
  },
  
  // Kit configuration for enterprise deployment
  kit: {
    // Enable service worker for offline capabilities
    serviceWorker: {
      register: false
    },
    
    // Optimize for performance
    version: {
      pollInterval: 1000
    }
  }
}
