<?php
/**
 * Endpoint para gestión de entidades
 * Lista y maneja operaciones con entidades
 */

require_once 'config.php';

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? 'list';
    
    switch ($action) {
        case 'list':
            handle_list_entities();
            break;
            
        case 'update':
            handle_update_entity();
            break;
            
        default:
            json_response(false, null, "Acción no válida", 400);
    }
    
} catch (Exception $e) {
    json_response(false, null, "Error en entidades: " . $e->getMessage(), 500);
}

/**
 * Obtiene la lista de entidades
 */
function handle_list_entities() {
    global $mysqli;
    
    $stmt = safe_query($mysqli, 
        "SELECT id, nombre FROM entidades ORDER BY nombre ASC"
    );
    
    $result = $stmt->get_result();
    $entities = [];
    
    while ($row = $result->fetch_assoc()) {
        $entities[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre']
        ];
    }
    
    json_response(true, $entities, "Entidades obtenidas correctamente");
}

/**
 * Actualiza la entidad de un examen específico
 */
function handle_update_entity() {
    global $mysqli;
    
    // Validar parámetros requeridos
    validate_required($_POST, ['identificacion', 'fecha_examen', 'codexamen', 'entidad']);
    
    $identificacion = $_POST['identificacion'];
    $fecha_examen = $_POST['fecha_examen'];
    $codexamen = $_POST['codexamen'];
    $nueva_entidad = $_POST['entidad'];
    
    // Actualizar en la base de datos
    $stmt = safe_query($mysqli, 
        "UPDATE examenes SET entidad = ? WHERE identificacion = ? AND fecha = ? AND codexamen = ?",
        [$nueva_entidad, $identificacion, $fecha_examen, $codexamen],
        "ssss"
    );
    
    if ($stmt->affected_rows > 0) {
        json_response(true, [
            'entidad' => $nueva_entidad
        ], "Entidad actualizada correctamente");
    } else {
        json_response(false, null, "No se encontró el examen especificado o la entidad es la misma", 404);
    }
}
?>