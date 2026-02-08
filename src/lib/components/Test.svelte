<script>
    let count = 0;
    
    function handleClick() {
        console.log('CLICK FUNCIONA!');
        alert('¡El click funciona! Count: ' + count);
        count++;
    }
    
    function handleMouseOver() {
        console.log('MOUSE OVER');
    }
</script>

<div style="padding: 20px; background: white; margin: 20px; border-radius: 8px;">
    <h1>🧪 Test Svelte Básico</h1>
    
    <p>Count: {count}</p>
    
    <button 
        on:click={handleClick}
        on:mouseover={handleMouseOver}
        style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;"
    >
        Click para probar Svelte
    </button>
    
    <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
        <p><strong>Si esto funciona:</strong></p>
        <ul>
            <li>✅ JavaScript básico funciona</li>
            <li>✅ Svelte se compila correctamente</li>
            <li>✅ Event handlers de Svelte funcionan</li>
        </ul>
    </div>
</div>