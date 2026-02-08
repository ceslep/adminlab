<?php
session_start();
header('Content-Type: application/json');

require_once 'cors.php';
require_once '../datos_conexion.php'; // Adjust path as necessary

try {
    // Get query parameters
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';
    
    // Build query based on actual database schema
    $baseQuery = "SELECT p.id, p.identificacion, p.nombres, p.apellidos, p.telefono, 
                         p.correo, p.genero, p.fecnac, p.estado, p.entidad,
                         GROUP_CONCAT(DISTINCT e.codexamen) as examenes
                  FROM paciente p
                  LEFT JOIN examenes e ON p.identificacion = e.identificacion
                  WHERE 1=1";
    
    $params = [];
    $types = '';
    
    // Add search filter - matching actual field names
    if (!empty($search)) {
        $baseQuery .= " AND (p.nombres LIKE ? OR p.apellidos LIKE ? OR p.telefono LIKE ? OR p.correo LIKE ? OR p.identificacion LIKE ?)";
        $searchParam = "%$search%";
        $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam]);
        $types .= str_repeat('s', 5);
    }
    
    // Group and order
    $baseQuery .= " GROUP BY p.id ORDER BY p.apellidos, p.nombres";
    
    // Prepare and execute query following login.php pattern
    $stmt = $mysqli->prepare($baseQuery);
    if ($stmt) {
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Prepare patients array
        $pacientes = [];
        
        while ($row = $result->fetch_assoc()) {
            // Calculate age from fecnac
            $fecha_nacimiento = new DateTime($row['fecnac']);
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha_nacimiento)->y;
            
            // Process gender
            $genero = '';
            $color_genero = 'bg-gray-100 text-gray-600';
            if (strtolower($row['genero']) === 'masculino') {
                $genero = 'M';
                $color_genero = 'bg-blue-100 text-blue-700';
            } elseif (strtolower($row['genero']) === 'femenino') {
                $genero = 'F';
                $color_genero = 'bg-pink-100 text-pink-700';
            }
            
            // Process exams (codexamen contains exam codes)
            $examenes = [];
            if ($row['examenes']) {
                $examenes = explode(',', $row['examenes']);
            }
            
            // Determine status color
            $estado = $row['estado'] ?: 'Activo'; // Default to Activo if null
            $color_estado = 'bg-gray-100 text-gray-700';
            if (strtolower($estado) === 'activo') {
                $color_estado = 'bg-green-100 text-green-700';
            } elseif (strtolower($estado) === 'inactivo') {
                $color_estado = 'bg-red-100 text-red-700';
            }
            
            // Build full name
            $nombre_completo = trim($row['nombres'] . ' ' . $row['apellidos']);
            
            $paciente = [
                'id_paciente' => (int)$row['id'],
                'identificacion' => $row['identificacion'],
                'nombre' => $row['nombres'] ?: '',
                'apellido' => $row['apellidos'] ?: '',
                'nombre_completo' => $nombre_completo,
                'telefono' => $row['telefono'] ?: '',
                'email' => $row['correo'] ?: '',
                'genero' => $genero,
                'genero_completo' => ucfirst($row['genero'] ?: ''),
                'color_genero' => $color_genero,
                'fecha_nacimiento' => $row['fecnac'],
                'edad' => $edad,
                'edad_texto' => $edad . ' años',
                'estado' => ucfirst($estado),
                'color_estado' => $color_estado,
                'entidad' => $row['entidad'] ?: '',
                'usuario_registro' => 'Sistema', // Default since no registro field exists
                'fecha_registro' => $row['fecnac'], // Using birth date as fallback
                'examenes' => $examenes,
                'total_examenes' => count($examenes)
            ];
            
            $pacientes[] = $paciente;
        }
        
        $stmt->close();
        
        // Return response
        echo json_encode([
            'success' => true,
            'data' => $pacientes,
            'total' => count($pacientes),
            'fecha_consulta' => $fecha ?: date('Y-m-d')
        ], JSON_UNESCAPED_UNICODE);
        
    } else {
        throw new Exception('Error al preparar la consulta: ' . $mysqli->error);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener los pacientes',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

if (isset($mysqli)) {
    $mysqli->close();
}
?>