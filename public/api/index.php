<?php
/**
 * Endpoint principal del API - Router
 * Enruta las solicitudes a los endpoints correspondientes
 */

require_once 'config.php';

try {
    // Obtener el endpoint solicitado
    $endpoint = $_GET['endpoint'] ?? '';
    
    if (empty($endpoint)) {
        json_response(false, null, "Endpoint no especificado", 400);
    }
    
    switch ($endpoint) {
        case 'auth':
            require_once 'auth.php';
            break;
            
        case 'configuracion':
            require_once 'configuracion.php';
            break;
            
        case 'entidades':
            require_once 'entidades.php';
            break;
            
        case 'pacientes':
            require_once 'pacientes.php';
            break;
            
        case 'examenes':
            require_once 'examenes.php';
            break;
            
        default:
            json_response(false, null, "Endpoint '$endpoint' no encontrado", 404);
    }
    
} catch (Exception $e) {
    json_response(false, null, "Error del servidor: " . $e->getMessage(), 500);
}
?>