<?php
/**
 * API REST para AdminLab
 * Configuración CORS y headers globales
 */

// Habilitar CORS para el frontend
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Manejo de solicitudes OPTIONS para CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Incluir configuración de base de datos
require_once '../datos_conexion.php';

// Función para enviar respuestas JSON estandarizadas
function json_response($success, $data = null, $message = '', $http_code = 200) {
    http_response_code($http_code);
    $response = ['success' => $success];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    if (!empty($message)) {
        $response['message'] = $message;
    }
    
    echo json_encode($response);
    exit();
}

// Función para validar parámetros requeridos
function validate_required($params, $required) {
    foreach ($required as $field) {
        if (!isset($params[$field]) || empty($params[$field])) {
            json_response(false, null, "El parámetro '$field' es requerido", 400);
        }
    }
}

// Función para ejecutar consultas preparadas de forma segura
function safe_query($mysqli, $sql, $params = [], $types = '') {
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error en preparación de consulta: " . $mysqli->error);
    }
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Error en ejecución de consulta: " . $stmt->error);
    }
    
    return $stmt;
}

// Manejo global de excepciones
try {
    // Obtener datos de entrada (POST o GET)
    $input = array_merge($_POST, $_GET);
    
    // Si es JSON, decodificarlo
    $json_input = file_get_contents('php://input');
    if (!empty($json_input)) {
        $decoded = json_decode($json_input, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $input = array_merge($input, $decoded);
        }
    }
    
} catch (Exception $e) {
    json_response(false, null, "Error del servidor: " . $e->getMessage(), 500);
}
?>