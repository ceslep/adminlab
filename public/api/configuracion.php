<?php
/**
 * Endpoint de configuración del laboratorio
 * Obtiene datos de configuración del laboratorio
 */

require_once 'config.php';

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? 'get';
    
    switch ($action) {
        case 'get':
            handle_get_config();
            break;
            
        default:
            json_response(false, null, "Acción no válida", 400);
    }
    
} catch (Exception $e) {
    json_response(false, null, "Error en configuración: " . $e->getMessage(), 500);
}

/**
 * Obtiene la configuración del laboratorio
 */
function handle_get_config() {
    global $mysqli;
    
    $stmt = safe_query($mysqli, 
        "SELECT nombreCorto, nombreLaboratorio, urlLogoLaboratorio FROM configuracion ORDER BY id DESC LIMIT 1"
    );
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $config = $result->fetch_assoc();
        
        // Procesar logo
        $logo_url = $config['urlLogoLaboratorio'] ?? '';
        if (!empty($logo_url) && !str_starts_with(trim($logo_url), 'data:image')) {
            // Si no es base64, asumir que es una ruta
            if (!filter_var($logo_url, FILTER_VALIDATE_URL) && !str_starts_with($logo_url, '/')) {
                $logo_url = "printphp/" . $logo_url;
            }
        } else {
            $logo_url = "data:image/png;base64," . $logo_url;
        }
        
        $lab_config = [
            'nombreCorto' => $config['nombreCorto'] ?? 'LAB',
            'nombreLaboratorio' => $config['nombreLaboratorio'] ?? 'Laboratorio Clínico',
            'urlLogoLaboratorio' => $logo_url
        ];
        
        json_response(true, $lab_config, "Configuración obtenida correctamente");
        
    } else {
        // Configuración por defecto
        $default_config = [
            'nombreCorto' => 'LAB',
            'nombreLaboratorio' => 'Laboratorio Clínico',
            'urlLogoLaboratorio' => ''
        ];
        
        json_response(true, $default_config, "Usando configuración por defecto");
    }
}
?>