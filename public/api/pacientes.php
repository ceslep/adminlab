<?php
session_start();
header('Content-Type: application/json');

require_once 'cors.php';
require_once '../datos_conexion.php'; // Adjust path as necessary

try {
    // Get query parameters
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $fecha = isset($_GET['fecha']) ? trim($_GET['fecha']) : '';
    
    // Development mode - return sample data
    if (!isset($mysqli) || $mysqli->connect_error) {
        $pacientes = [
            [
                'id_paciente' => 1,
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'nombre_completo' => 'Juan Pérez',
                'telefono' => '3001234567',
                'email' => 'juan.perez@email.com',
                'genero' => 'M',
                'genero_completo' => 'Masculino',
                'color_genero' => 'bg-blue-100 text-blue-700',
                'fecha_nacimiento' => '1985-05-15',
                'edad' => 40,
                'edad_texto' => '40 años',
                'estado' => 'Activo',
                'color_estado' => 'bg-green-100 text-green-700',
                'usuario_registro' => 'admin',
                'fecha_registro' => '2024-01-15',
                'examenes' => ['Sangre', 'Orina'],
                'total_examenes' => 2,
                'identificacion' => '12345678',
                'entidad' => 'SURA'
            ],
            [
                'id_paciente' => 2,
                'nombre' => 'María',
                'apellido' => 'González',
                'nombre_completo' => 'María González',
                'telefono' => '3009876543',
                'email' => 'maria.gonzalez@email.com',
                'genero' => 'F',
                'genero_completo' => 'Femenino',
                'color_genero' => 'bg-pink-100 text-pink-700',
                'fecha_nacimiento' => '1992-08-22',
                'edad' => 33,
                'edad_texto' => '33 años',
                'estado' => 'Activo',
                'color_estado' => 'bg-green-100 text-green-700',
                'usuario_registro' => 'admin',
                'fecha_registro' => '2024-02-20',
                'examenes' => ['Radiografía'],
                'total_examenes' => 1,
                'identificacion' => '87654321',
                'entidad' => 'COOMEVA'
            ],
            [
                'id_paciente' => 3,
                'nombre' => 'Carlos',
                'apellido' => 'Rodríguez',
                'nombre_completo' => 'Carlos Rodríguez',
                'telefono' => '3012345678',
                'email' => 'carlos.rodriguez@email.com',
                'genero' => 'M',
                'genero_completo' => 'Masculino',
                'color_genero' => 'bg-blue-100 text-blue-700',
                'fecha_nacimiento' => '1978-12-10',
                'edad' => 47,
                'edad_texto' => '47 años',
                'estado' => 'Inactivo',
                'color_estado' => 'bg-red-100 text-red-700',
                'usuario_registro' => 'admin',
                'fecha_registro' => '2023-11-05',
                'examenes' => ['Sangre', 'Orina', 'ECG'],
                'total_examenes' => 3,
                'identificacion' => '45678912',
                'entidad' => 'SISBEN'
            ]
        ];
        
        // Apply search filter if provided
        if (!empty($search)) {
            $search_lower = strtolower($search);
            $pacientes = array_filter($pacientes, function($paciente) use ($search_lower) {
                return strpos(strtolower($paciente['nombre_completo']), $search_lower) !== false ||
                       strpos(strtolower($paciente['telefono']), $search_lower) !== false ||
                       strpos(strtolower($paciente['email']), $search_lower) !== false ||
                       strpos(strtolower($paciente['identificacion']), $search_lower) !== false;
            });
        }
        
        echo json_encode([
            'success' => true,
            'data' => array_values($pacientes),
            'total' => count($pacientes),
            'fecha_consulta' => $fecha ?: '2026-02-06',
            'mode' => 'development'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Production mode - Corrected query based on actual database schema
    $baseQuery = "SELECT p.id, p.identificacion, p.nombres, p.apellidos, p.telefono, 
                         p.correo, p.genero, p.fecnac, p.estado, p.entidad,
                         GROUP_CONCAT(DISTINCT e.codexamen) as examenes
                  FROM paciente p
                  LEFT JOIN examenes e ON p.identificacion = e.identificacion
                  WHERE 1=1";
    
    $params = [];
    $types = '';
    
    // Add search filter - matching the actual field names
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
            'fecha_consulta' => $fecha ?: date('Y-m-d'),
            'mode' => 'production'
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