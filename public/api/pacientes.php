<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../config.php';

try {
    // Get query parameters
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';
    
    // Build base query
    $baseQuery = "SELECT p.id_paciente, p.nombre_paciente, p.apellido_paciente, 
                         p.telefono_paciente, p.email_paciente, p.sexo_paciente, 
                         p.fecha_nacimiento, p.estado_paciente, p.usuario_registro, 
                         p.fecha_registro,
                         GROUP_CONCAT(DISTINCT e.tipo_examen ORDER BY e.id_examen) as examenes
                  FROM paciente p
                  LEFT JOIN examenes e ON p.id_paciente = e.id_paciente
                  WHERE 1=1";
    
    $params = [];
    $types = '';
    
    // Add search filter
    if (!empty($search)) {
        $baseQuery .= " AND (p.nombre_paciente LIKE ? OR p.apellido_paciente LIKE ? OR p.telefono_paciente LIKE ? OR p.email_paciente LIKE ?)";
        $searchParam = "%$search%";
        $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
        $types .= str_repeat('s', 4);
    }
    
    // Group and order
    $baseQuery .= " GROUP BY p.id_paciente ORDER BY p.apellido_paciente, p.nombre_paciente";
    
    // Prepare and execute query
    $stmt = $conn->prepare($baseQuery);
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Prepare patients array
    $pacientes = [];
    
    while ($row = $result->fetch_assoc()) {
        // Calculate age
        $fecha_nacimiento = new DateTime($row['fecha_nacimiento']);
        $hoy = new DateTime();
        $edad = $hoy->diff($fecha_nacimiento)->y;
        
        // Process gender
        $genero = '';
        $color_genero = 'bg-gray-100 text-gray-600';
        if (strtolower($row['sexo_paciente']) === 'masculino') {
            $genero = 'M';
            $color_genero = 'bg-blue-100 text-blue-700';
        } elseif (strtolower($row['sexo_paciente']) === 'femenino') {
            $genero = 'F';
            $color_genero = 'bg-pink-100 text-pink-700';
        }
        
        // Process exams
        $examenes = [];
        if ($row['examenes']) {
            $examenes = explode(',', $row['examenes']);
        }
        
        // Determine status
        $estado = $row['estado_paciente'];
        $color_estado = 'bg-gray-100 text-gray-700';
        if (strtolower($estado) === 'activo') {
            $color_estado = 'bg-green-100 text-green-700';
        } elseif (strtolower($estado) === 'inactivo') {
            $color_estado = 'bg-red-100 text-red-700';
        }
        
        $paciente = [
            'id_paciente' => (int)$row['id_paciente'],
            'nombre' => $row['nombre_paciente'],
            'apellido' => $row['apellido_paciente'],
            'nombre_completo' => $row['nombre_paciente'] . ' ' . $row['apellido_paciente'],
            'telefono' => $row['telefono_paciente'],
            'email' => $row['email_paciente'],
            'genero' => $genero,
            'genero_completo' => ucfirst($row['sexo_paciente']),
            'color_genero' => $color_genero,
            'fecha_nacimiento' => $row['fecha_nacimiento'],
            'edad' => $edad,
            'edad_texto' => $edad . ' años',
            'estado' => ucfirst($row['estado_paciente']),
            'color_estado' => $color_estado,
            'usuario_registro' => $row['usuario_registro'],
            'fecha_registro' => $row['fecha_registro'],
            'examenes' => $examenes,
            'total_examenes' => count($examenes)
        ];
        
        $pacientes[] = $paciente;
    }
    
    // Return response
    echo json_encode([
        'success' => true,
        'data' => $pacientes,
        'total' => count($pacientes),
        'fecha_consulta' => $fecha ?: date('Y-m-d')
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener los pacientes',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

$conn->close();
?>