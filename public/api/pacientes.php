<?php
/**
 * Endpoint para búsqueda y gestión de pacientes
 * Consultas complejas de pacientes con sus exámenes
 */

require_once 'config.php';

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    
    switch ($action) {
        case 'search':
            handle_search_patients();
            break;
            
        case 'get_patient_exams':
            handle_get_patient_exams();
            break;
            
        default:
            json_response(false, null, "Acción no válida", 400);
    }
    
} catch (Exception $e) {
    json_response(false, null, "Error en pacientes: " . $e->getMessage(), 500);
}

/**
 * Busca pacientes con filtros múltiples
 */
function handle_search_patients() {
    global $mysqli;
    
    // Obtener parámetros
    $identificacion = trim($_POST['identificacion'] ?? '');
    $nombres = trim($_POST['nombres'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $ciudad = trim($_POST['ciudad'] ?? '');
    $entidad = trim($_POST['entidad'] ?? '');
    $include_examenes = $_POST['include_examenes'] ?? '1';
    $solo_con_resultados = $_POST['solo_con_resultados'] ?? '0';
    $limit = isset($_POST['limit']) ? (int) $_POST['limit'] : 50;
    
    // Validar que al menos un criterio esté presente
    if (empty($identificacion) && empty($nombres) && empty($telefono) && empty($ciudad) && empty($entidad)) {
        json_response(false, null, "Debe ingresar al menos un criterio de búsqueda", 400);
    }
    
    // Construir SQL base
    $sql = "
        SELECT
            p.identificacion,
            CONCAT_WS(' ', p.apellidos, p.nombres) as nombre_completo,
            p.apellidos,
            p.nombres,
            p.edad,
            p.genero,
            p.fecnac,
            p.telefono,
            p.telefono_movil,
            p.telefono_residencia2,
            p.correo,
            p.ciudad_residencia,
            p.direccion_residencia,
            p.entidad,
            COUNT(DISTINCT e.fecha) as total_visitas,
            MAX(e.fecha) as ultima_visita
        FROM paciente p
        LEFT JOIN examenes e ON p.identificacion = e.identificacion
        WHERE 1=1";
    
    $params = [];
    $param_types = "";
    
    // Filtro por identificación
    if (!empty($identificacion)) {
        $sql .= " AND p.identificacion LIKE ?";
        $params[] = "%" . $identificacion . "%";
        $param_types .= "s";
    }
    
    // Filtro por nombres
    if (!empty($nombres)) {
        $nombres_like = "%" . $nombres . "%";
        $sql .= " AND (p.nombres LIKE ? OR p.apellidos LIKE ? OR p.nombre1 LIKE ? OR p.nombre2 LIKE ? OR p.apellido1 LIKE ? OR p.apellido2 LIKE ?)";
        $params = array_merge($params, [$nombres_like, $nombres_like, $nombres_like, $nombres_like, $nombres_like, $nombres_like]);
        $param_types .= "ssssss";
    }
    
    // Filtro por teléfono
    if (!empty($telefono)) {
        $telefono_like = "%" . $telefono . "%";
        $sql .= " AND (p.telefono LIKE ? OR p.telefono_movil LIKE ? OR p.telefono_residencia2 LIKE ?)";
        $params = array_merge($params, [$telefono_like, $telefono_like, $telefono_like]);
        $param_types .= "sss";
    }
    
    // Filtro por ciudad
    if (!empty($ciudad)) {
        $sql .= " AND p.ciudad_residencia LIKE ?";
        $params[] = "%" . $ciudad . "%";
        $param_types .= "s";
    }
    
    // Filtro por entidad
    if (!empty($entidad)) {
        $sql .= " AND p.entidad LIKE ?";
        $params[] = "%" . $entidad . "%";
        $param_types .= "s";
    }
    
    $sql .= " GROUP BY p.identificacion ORDER BY p.apellidos ASC, p.nombres ASC LIMIT " . $limit;
    
    $stmt = safe_query($mysqli, $sql, $params, $param_types);
    $result = $stmt->get_result();
    
    $pacientes = [];
    $procedimientos_exists = $mysqli->query("SHOW TABLES LIKE 'procedimientos'")->num_rows > 0;
    
    while ($paciente_row = $result->fetch_assoc()) {
        $identificacion_p = $paciente_row['identificacion'];
        
        // Formatear teléfono principal
        $telefono_principal = $paciente_row['telefono'] ?: $paciente_row['telefono_movil'] ?: $paciente_row['telefono_residencia2'] ?: '';
        
        // Calcular edad si no está en la base de datos
        $edad_formateada = $paciente_row['edad'];
        if (empty($edad_formateada) && !empty($paciente_row['fecnac']) && $paciente_row['fecnac'] !== '0000-00-00') {
            $fecha_nac = new DateTime($paciente_row['fecnac']);
            $hoy = new DateTime();
            $edad_formateada = $hoy->diff($fecha_nac)->y;
        }
        
        $paciente_data = [
            'identificacion' => $identificacion_p,
            'nombre_completo' => $paciente_row['nombre_completo'],
            'apellidos' => $paciente_row['apellidos'],
            'nombres' => $paciente_row['nombres'],
            'edad' => $edad_formateada,
            'genero' => $paciente_row['genero'],
            'telefono' => $telefono_principal,
            'telefono_fijo' => $paciente_row['telefono'],
            'telefono_movil' => $paciente_row['telefono_movil'],
            'telefono_residencia2' => $paciente_row['telefono_residencia2'],
            'correo' => $paciente_row['correo'],
            'ciudad_residencia' => $paciente_row['ciudad_residencia'],
            'direccion_residencia' => $paciente_row['direccion_residencia'],
            'entidad' => $paciente_row['entidad'],
            'total_visitas' => $paciente_row['total_visitas'] ?? 0,
            'ultima_visita' => $paciente_row['ultima_visita'] ?? '',
            'examenes' => []
        ];
        
        // Obtener exámenes del paciente si se solicita
        if ($include_examenes == '1') {
            $paciente_data['examenes'] = get_patient_exams($identificacion_p, $procedimientos_exists, $solo_con_resultados);
        }
        
        $paciente_data['total_examenes'] = count($paciente_data['examenes']);
        $paciente_data['examenes_con_resultados'] = count(array_filter($paciente_data['examenes'], function ($ex) {
            return isset($ex['estado']) && $ex['estado'] == 'Completado';
        }));
        
        $pacientes[] = $paciente_data;
    }
    
    json_response(true, [
        'pacientes' => $pacientes,
        'total_pacientes' => count($pacientes)
    ], "Búsqueda completada");
}

/**
 * Obtiene los exámenes de un paciente específico
 */
function handle_get_patient_exams() {
    global $mysqli;
    
    $identificacion = $_POST['identificacion'] ?? '';
    $solo_con_resultados = $_POST['solo_con_resultados'] ?? '0';
    
    if (empty($identificacion)) {
        json_response(false, null, "La identificación del paciente es requerida", 400);
    }
    
    $procedimientos_exists = $mysqli->query("SHOW TABLES LIKE 'procedimientos'")->num_rows > 0;
    $examenes = get_patient_exams($identificacion, $procedimientos_exists, $solo_con_resultados);
    
    json_response(true, [
        'examenes' => $examenes,
        'total_examenes' => count($examenes)
    ], "Exámenes obtenidos correctamente");
}

/**
 * Función auxiliar para obtener exámenes de un paciente
 */
function get_patient_exams($identificacion, $procedimientos_exists, $solo_con_resultados) {
    global $mysqli;
    
    $sql = "SELECT e.fecha, e.codexamen, e.realizado, e.entidad";
    
    if ($procedimientos_exists) {
        $sql .= ", pr.nombre as nombre_examen, pr.codigo as codigo_examen, pr.tabla as examen_tabla, 
                  pr.tipo as tipo_examen, pr.tipoprocedimiento as tipo_procedimiento, pr.abreviatura";
    }
    
    $sql .= " FROM examenes e
              INNER JOIN paciente p ON e.identificacion = p.identificacion";
    
    if ($procedimientos_exists) {
        $sql .= " LEFT JOIN procedimientos pr ON e.codexamen = pr.codigo";
    }
    
    $sql .= " WHERE e.identificacion = ? ORDER BY e.fecha DESC";
    
    $stmt = safe_query($mysqli, $sql, [$identificacion], "s");
    $result = $stmt->get_result();
    
    $examenes = [];
    
    while ($examen_row = $result->fetch_assoc()) {
        $examen_data = [
            'fecha' => $examen_row['fecha'],
            'codexamen' => $examen_row['codexamen'],
            'entidad' => $examen_row['entidad'],
            'realizado' => $examen_row['realizado']
        ];
        
        if ($procedimientos_exists) {
            $examen_data['nombre'] = $examen_row['nombre_examen'] ?? $examen_row['abreviatura'] ?? 'Examen #' . $examen_row['codexamen'];
            $examen_data['tipo'] = $examen_row['tipo_examen'] ?? 'No especificado';
            $examen_data['tabla'] = $examen_row['examen_tabla'] ?? '';
            $examen_data['procedimiento'] = $examen_row['tipo_procedimiento'] ?? '';
            $examen_data['abreviatura'] = $examen_row['abreviatura'] ?? '';
            
            // Obtener resultado real del examen
            if (!empty($examen_row['examen_tabla'])) {
                $examen_data = array_merge($examen_data, get_exam_result($identificacion, $examen_row));
            } else {
                $examen_data['resultado'] = 'Sin tabla definida';
                $examen_data['referencia'] = 'N/A';
                $examen_data['estado'] = 'Pendiente';
            }
        } else {
            $examen_data['nombre'] = 'Examen #' . $examen_row['codexamen'];
            $examen_data['resultado'] = 'No disponible';
            $examen_data['estado'] = 'N/A';
        }
        
        // Filtrar si solo se quieren resultados concretos
        if ($solo_con_resultados == '1' && ($examen_data['estado'] == 'Pendiente' || $examen_data['resultado'] == 'No disponible')) {
            continue;
        }
        
        $examenes[] = $examen_data;
    }
    
    return $examenes;
}

/**
 * Función auxiliar para obtener resultado específico de un examen
 */
function get_exam_result($identificacion, $examen_row) {
    global $mysqli;
    
    $tabla_resultado = $examen_row['examen_tabla'];
    $tabla_existe = $mysqli->query("SHOW TABLES LIKE '$tabla_resultado'")->num_rows > 0;
    
    if (!$tabla_existe) {
        return [
            'resultado' => 'Tabla no encontrada',
            'referencia' => 'N/A',
            'estado' => 'Error'
        ];
    }
    
    // Obtener estructura de la tabla
    $estructura = $mysqli->query("DESCRIBE `$tabla_resultado`");
    $columnas = [];
    while ($col = $estructura->fetch_assoc()) {
        $columnas[] = $col['Field'];
    }
    
    // Construir WHERE dinámico
    $where_clauses = [];
    $where_params = [];
    $where_types = "";
    
    if (in_array('identificacion', $columnas)) {
        $where_clauses[] = "identificacion = ?";
        $where_params[] = $identificacion;
        $where_types .= "s";
    }
    
    if (in_array('codexamen', $columnas)) {
        $where_clauses[] = "codexamen = ?";
        $where_params[] = $examen_row['codexamen'];
        $where_types .= "s";
    } elseif (in_array('examen', $columnas)) {
        $where_clauses[] = "examen = ?";
        $where_params[] = $examen_row['codexamen'];
        $where_types .= "s";
    }
    
    if (in_array('fecha', $columnas)) {
        $where_clauses[] = "fecha = ?";
        $where_params[] = $examen_row['fecha'];
        $where_types .= "s";
    }
    
    if (empty($where_clauses)) {
        return [
            'resultado' => 'Sin columnas clave',
            'referencia' => 'N/A',
            'estado' => 'Error'
        ];
    }
    
    $where_sql = implode(" AND ", $where_clauses);
    $sql_resultado = "SELECT * FROM `$tabla_resultado` WHERE $where_sql LIMIT 1";
    
    try {
        $stmt_resultado = safe_query($mysqli, $sql_resultado, $where_params, $where_types);
        $result_resultado = $stmt_resultado->get_result();
        
        if ($result_resultado->num_rows > 0) {
            $datos_resultado = $result_resultado->fetch_assoc();
            
            // Extraer valor principal según tipo de tabla
            $valor_resultado = extract_main_value($tabla_resultado, $datos_resultado);
            $referencia = extract_reference($datos_resultado);
            
            return [
                'resultado' => $valor_resultado ?: 'Sin valor',
                'referencia' => $referencia ?: 'N/A',
                'estado' => 'Completado',
                'resultado_completo' => $datos_resultado
            ];
        } else {
            return [
                'resultado' => 'Pendiente',
                'referencia' => 'N/A',
                'estado' => 'Pendiente'
            ];
        }
    } catch (Exception $e) {
        return [
            'resultado' => 'Error en consulta',
            'referencia' => 'N/A',
            'estado' => 'Error'
        ];
    }
}

/**
 * Extrae el valor principal según el tipo de tabla
 */
function extract_main_value($tabla, $datos) {
    $valor_resultado = '';
    
    switch ($tabla) {
        case 'examen_tipo_1':
        case 'examen_tipo_2':
            $valor_resultado = $datos['valoracion'] ?? '';
            break;
            
        case 'examen_tipo_3':
            $valores_clave = ['densidad', 'color', 'ph', 'proteinas', 'glucosa', 'bilirrubina', 'nitritos', 'leucocitos'];
            foreach ($valores_clave as $campo) {
                if (!empty($datos[$campo]) && $datos[$campo] !== 'N/A') {
                    $valor_resultado .= ($valor_resultado ? ', ' : '') . $campo . ': ' . $datos[$campo];
                }
            }
            break;
            
        case 'examen_tipo_5':
            $hemograma_clave = ['hemoglobina', 'hematocrito', 'leucocitos', 'WBC', 'RBC', 'PLT'];
            foreach ($hemograma_clave as $campo) {
                if (!empty($datos[$campo]) && $datos[$campo] !== 'N/A') {
                    $valor_resultado .= ($valor_resultado ? ', ' : '') . $campo . ': ' . $datos[$campo];
                }
            }
            break;
            
        case 'perfilLipidico':
            $lipidos_clave = ['colesterol_total', 'colesterol_hdl', 'colesterol_ldl', 'trigliceridos'];
            foreach ($lipidos_clave as $campo) {
                if (!empty($datos[$campo]) && $datos[$campo] !== 'N/A') {
                    $valor_resultado .= ($valor_resultado ? ', ' : '') . $campo . ': ' . $datos[$campo];
                }
            }
            break;
            
        default:
            // Búsqueda genérica
            foreach ($datos as $columna => $valor) {
                if (in_array(strtolower($columna), ['resultado', 'valor', 'result', 'value']) && !empty($valor)) {
                    $valor_resultado = $valor;
                    break;
                }
            }
            
            // Si no se encuentra resultado específico
            if (empty($valor_resultado)) {
                $excluir = ['ind', 'identificacion', 'codexamen', 'examen', 'fecha', 'hora', 'id', 'bacteriologo', 'observaciones', 'fechahora'];
                foreach ($datos as $columna => $valor) {
                    if (!in_array(strtolower($columna), $excluir) && !empty($valor) && $valor !== '0000-00-00' && $valor !== '0' && $valor !== 'N/A') {
                        $valor_resultado = $valor;
                        break;
                    }
                }
            }
    }
    
    return $valor_resultado;
}

/**
 * Extrae el valor de referencia
 */
function extract_reference($datos) {
    $ref_campos = ['referencia', 'rango', 'range', 'normal', 'valor_de_referencia'];
    foreach ($ref_campos as $campo) {
        if (!empty($datos[$campo]) && $datos[$campo] !== 'N/A') {
            return $datos[$campo];
        }
    }
    return '';
}
?>