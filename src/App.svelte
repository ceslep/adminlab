<script>
    import Test from './lib/components/Test.svelte';
</script>

<main style="padding: 20px; background: #f5f5f5; min-height: 100vh;">
    <h1>🧪 App de Diagnóstico</h1>
    <Test />
    
    <div style="margin-top: 20px; padding: 20px; background: white; border-radius: 8px;">
        <h2>📝 Instrucciones:</h2>
        <ol>
            <li>1. Abre la consola del navegador (F12)</li>
            <li>2. Haz click en el botón azul</li>
            <li>3. Deberías ver logs en la consola</li>
            <li>4. Deberías ver una alerta</li>
        </ol>
        
        <div style="background: #fff3cd; padding: 15px; border-radius: 4px; margin-top: 20px;">
            <h3>🎯 ¿Qué probar?</h3>
            <ul>
                <li>✅ Si el Test component funciona → El problema está en el Dashboard.svelte</li>
                <li>❌ Si el Test component NO funciona → Problema fundamental de Svelte/JS</li>
            </ul>
        </div>
    </div>
</main>