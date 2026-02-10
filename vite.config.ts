import { defineConfig } from 'vite'
import { svelte } from '@sveltejs/vite-plugin-svelte'
import tailwindcss from '@tailwindcss/vite'
import { resolve } from 'path'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
svelte({
      // Optimize for production
      emitCss: true,
      
      // Enable premium CSS features
      compilerOptions: {
        css: 'injected',
        dev: !process.env.PROD
      }
    }),
    tailwindcss()
  ],
  base: '/adminlab/',
  resolve: {
    alias: {
      $lib: resolve('./src/lib'),
      $components: resolve('./src/lib/components'),
      $styles: resolve('./src/styles'),
      $utils: resolve('./src/lib/utils'),
      $assets: resolve('./src/assets')
    }
  },
  publicDir: 'public',
  
  // Build optimizations for enterprise performance
  build: {
    target: 'es2020',
    minify: 'esbuild',
    cssMinify: true,
    sourcemap: false,
// rollupOptions: {
    //   output: {
    //     manualChunks: {
    //       // Separate vendor chunks for better caching
    //       vendor: ['svelte', 'lucide-svelte', 'framer-motion'],
    //       
    //       // Separate styles
    //       styles: ['./src/styles']
    //     }
    //   }
    // },
    
    // Optimize assets
    assetsInlineLimit: 4096,
    
    // Enable chunk size warnings
    chunkSizeWarningLimit: 1000
  },
  
  // Development server configuration
  server: {
    port: 3000,
    open: true,
    cors: true,
    
    // Enable HMR for smooth development
    hmr: {
      overlay: true
    }
  },
  
  // Preview server configuration
  preview: {
    port: 4173,
    open: true
  },
  
  // CSS optimizations handled by Tailwind
  css: {
    devSourcemap: true
  },
  
  // Define global constants
  define: {
    __APP_VERSION__: JSON.stringify(process.env.npm_package_version),
    __BUILD_DATE__: JSON.stringify(new Date().toISOString())
  }
})
