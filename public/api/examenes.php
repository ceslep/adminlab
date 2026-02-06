<?php
/**
 * Endpoint para consultas de exámenes y reportes por entidades
 * Maneja consultas complejas con agrupación y filtrado
 */

require_once 'config.php';

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? 'search';
    
    switch ($action) {
        case 'search':
            handle_search_exams();
            break;
            
        case 'entity_report':
            handle_entity_report();
            break;
            
        default:
            json_response(false, null, "Acción no válida", 400);
    }
    
} catch (Exception $e) {
    json_response(false, null, "Error en exámenes: " . $e->getMessage(), 500);
}

/**
 * Busca exámenes con filtros múltiples
 */
function handle_search_exams() {
    global $mysqli;
    
    $fecha = $_GET['fecha'] ?? date("Y-m-d");
    $identificacion = $_GET['identificacion'] ?? '';
    $busqueda = $_GET['buscar'] ?? '';
    $todos = isset($_GET['todos']) ? true : false;
    
    // Construir consulta SQL
    $params = [];
    $param_types = "";
    $where_conditions = "1=1";
    
    // Filtro de fecha
    if (!$todos || empty($busqueda)) {
        $where_conditions .= " AND e.fecha = ?";
        $params[] = $fecha;
        $param_types .= "s";
    }
    
    // Filtro de búsqueda
    if (!empty($busqueda)) {
        $busqueda_like = "%" . $busqueda . "%";
        $where_conditions .= " AND (e.identificacion LIKE ? OR p.nombres LIKE ? OR p.apellidos LIKE ?)";
        $params = array_merge($params, [$busqueda_like, $busqueda_like, $busqueda_like]);
        $param_types .= "sss";
    }
    
    $sql = "SELECT e.identificacion, e.fecha as fecha_examen, MAX(e.entidad) as entidad,
            CONCAT_WS(' ', p.apellidos, p.nombres) as nombres,
            p.edad, p.correo, p.telefono, p.genero, p.fecnac
            FROM examenes e
            INNER JOIN paciente p ON e.identificacion = p.identificacion
            WHERE $where_conditions
            GROUP BY e.identificacion, e.fecha
            ORDER BY e.fecha DESC, p.apellidos ASC, p.nombres ASC
            LIMIT 100";
    
    $stmt = safe_query($mysqli, $sql, $params, $param_types);
    $result = $stmt->get_result();
    
    $resultados = [];
    while ($row = $result->fetch_assoc()) {
        $resultados[] = [
            'identificacion' => $row['identificacion'],
            'paciente' => $row['nombres'],
            'edad' => $row['edad'],
            'genero' => $row['genero'],
            'telefono' => $row['telefono'],
            'fecha_examen' => $row['fecha_examen'],
            'entidad' => $row['entidad']
        ];
    }
    
    json_response(true, [
        'resultados' => $resultados,
        'total_pacientes' => count($resultados),
        'fecha' => $fecha,
        'busqueda' => $busqueda
    ], "Búsqueda completada");
}

/**
 * Genera reporte por entidades
 */
function handle_entity_report() {
    global $mysqli;
    
    // Validar parámetros requeridos
    validate_required($_POST, ['fecha_inicio', 'fecha_fin']);
    
    $entidad = $_POST['entidad'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $solo_resultados = $_POST['solo_resultados'] ?? '0';
    $agrupar_fecha = $_POST['agrupar_fecha'] ?? '1';
    
    // Verificar si existe la tabla procedimientos
    $procedimientos_exists = $mysqli->query("SHOW TABLES LIKE 'procedimientos'")->num_rows > 0;
    
    // Construir consulta SQL
    if ($procedimientos_exists) {
        $sql = "SELECT
            e.identificacion,
            e.fecha as fecha_examen,
            e.entidad,
            e.codexamen,
            e.realizado,
            p.nombres,
            p.apellidos,
            p.edad,
            p.telefono,
            p.genero,
            pr.nombre as nombre_examen,
            pr.codigo as codigo_examen,
            pr.tabla as examen_tabla,
            pr.tipo as tipo_examen,
            pr.tipoprocedimiento as tipo_procedimiento,
            pr.abreviatura
            FROM examenes e
            INNER JOIN paciente p ON e.identificacion = p.identificacion
            LEFT JOIN procedimientos pr ON e.codexamen = pr.codigo
            WHERE e.fecha BETWEEN ? AND ?";
        
        $params = [$fecha_inicio, $fecha_fin];
        $param_types = "ss";
        
        // Agregar filtro de entidad si se especificó
        if (!empty($entidad)) {
            $sql .= " AND TRIM(e.entidad) = TRIM(?)";
            $params[] = $entidad;
            $param_types .= "s";
        }
    } else {
        // Consulta sin procedimientos
        $sql = "SELECT
            e.identificacion,
            e.fecha as fecha_examen,
            e.entidad,
            e.codexamen,
            e.realizado,
            p.nombres,
            p.apellidos,
            p.edad,
            p.telefono,
            p.genero
            FROM examenes e
            INNER JOIN paciente p ON e.identificacion = p.identificacion
            WHERE e.fecha BETWEEN ? AND ?";
        
        $params = [$fecha_inicio, $fecha_fin];
        $param_types = "ss";
        
        if (!empty($entidad)) {
            $sql .= " AND TRIM(e.entidad) = TRIM(?)";
            $params[] = $entidad;
            $param_types .= "s";
        }
    }
    
    // Filtro adicional para exámenes realizados
    if ($solo_resultados == '1') {
        $sql .= " AND e.realizado = 'S'";
    }
    
    $sql .= " ORDER BY e.fecha DESC, p.apellidos ASC, p.nombres ASC";
    
    $stmt = safe_query($mysqli, $sql, $params, $param_types);
    $result = $stmt->get_result();
    
    $resultados = [];
    
    while ($row = $result->fetch_assoc()) {
        // Construir nombre completo del paciente
        $nombres_completos = trim(($row['apellidos'] ?? '') . ' ' . ($row['nombres'] ?? ''));
        
        $examen_data = [
            'identificacion' => $row['identificacion'],
            'paciente' => $nombres_completos,
            'edad' => $row['edad'],
            'genero' => $row['genero'],
            'telefono' => $row['telefono'],
            'fecha_examen' => $row['fecha_examen'],
            'entidad' => $row['entidad'],
            'codexamen' => $row['codexamen'],
            'realizado' => $row['realizado']
        ];
        
        // Agregar datos de procedimientos si existen
        if ($procedimientos_exists) {
            $examen_data['nombre'] = $row['nombre_examen'] ?? $row['abreviatura'] ?? 'Examen #' . $row['codexamen'];
            $examen_data['tipo'] = $row['tipo_examen'] ?? 'No especificado';
            $examen_data['tabla'] = $row['examen_tabla'] ?? '';
            $examen_data['procedimiento'] = $row['tipo_procedimiento'] ?? '';
            $examen_data['abreviatura'] = $row['abreviatura'] ?? '';
            
            // Obtener resultado real del examen
            if (!empty($row['examen_tabla'])) {
                $examen_data = array_merge($examen_data, get_exam_result_from_table(
                    $row['identificacion'],
                    $row['codexamen'],
                    $row['fecha_examen'],
                    $row['examen_tabla']
                ));
            } else {
                $examen_data['resultado'] = 'Sin tabla definida';
                $examen_data['referencia'] = 'N/A';
                $examen_data['estado'] = 'Pendiente';
            }
        } else {
            $examen_data['nombre'] = 'Examen #' . $row['codexamen'];
            $examen_data['resultado'] = 'No disponible';
            $examen_data['estado'] = 'N/A';
        }
        
        $resultados[] = $examen_data;
    }
    
    // Agrupar por fecha si se solicita
    if ($agrupar_fecha == '1') {
        $resultados_agrupados = [];
        foreach ($resultados as $resultado) {
            $fecha = $resultado['fecha_examen'];
            if (!isset($resultados_agrupados[$fecha])) {
                $resultados_agrupados[$fecha] = [];
            }
            $resultados_agrupados[$fecha][] = $resultado;
        }
        
        // Convertir a array numérico para JSON
        $resultado_final = [];
        foreach ($resultados_agrupados as $fecha => $items) {
            $resultado_final[] = [
                'fecha' => $fecha,
                'cantidad' => count($items),
                'examenes' => $items
            ];
        }
        
        json_response(true, [
            'resultados' => $resultado_final,
            'total_registros' => count($resultados),
            'total_fechas' => count($resultado_final)
        ], "Reporte agrupado generado correctamente");
    } else {
        json_response(true, [
            'resultados' => $resultados,
            'total_registros' => count($resultados)
        ], "Reporte generado correctamente");
    }
}

/**
 * Función auxiliar para obtener resultado de examen desde tabla dinámica
 */
function get_exam_result_from_table($identificacion, $codexamen, $fecha_examen, $tabla_resultado) {
    global $mysqli;
    
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
        $where_params[] = $codexamen;
        $where_types .= "s";
    } elseif (in_array('examen', $columnas)) {
        $where_clauses[] = "examen = ?";
        $where_params[] = $codexamen;
        $where_types .= "s";
    }
    
    if (in_array('fecha', $columnas)) {
        $where_clauses[] = "fecha = ?";
        $where_params[] = $fecha_examen;
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
            
            // Usar las mismas funciones auxiliares del archivo pacientes.php
            // Incluimos las funciones aquí para evitar duplicación
            $valor_resultado = extract_main_value_from_data($tabla_resultado, $datos_resultado);
            $referencia = extract_reference_from_data($datos_resultado);
            
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
 * Extrae el valor principal según el tipo de tabla (versión local)
 */
function extract_main_value_from_data($tabla, $datos) {
    $valor_resultado = '';
    
    switch ($tabla) {
        case 'examen_tipo_1':
        case 'examen_tipo_2':
            $valor_resultado = $datos['valoracion'] ?? '';
            break;
            
        case 'examen_tipo_3':
            $valores_clave = ['densidad', 'color', 'ph', 'proteinas', 'glucosa'];
            foreach ($valores_clave as $campo) {
                if (!empty($datos[$campo]) && $datos[$campo] !== 'N/A') {
                    $valor_resultado .= ($valor_resultado ? ', ' : '') . $campo . ': ' . $datos[$campo];
                }
            }
            break;
            
        case 'examen_tipo_5':
            $hemograma_clave = ['hemoglobina', 'hematocrito', 'leucocitos'];
            foreach ($hemograma_clave as $campo) {
                if (!empty($datos[$campo]) && $datos[$campo] !== 'N/A') {
                    $valor_resultado .= ($valor_resultado ? ', ' : '') . $campo . ': ' . $datos[$campo];
                }
            }
            break;
            
        case 'perfilLipidico':
            $lipidos_clave = ['colesterol_total', 'colesterol_hdl', 'trigliceridos'];
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
            
            if (empty($valor_resultado)) {
                $excluir = ['ind', 'identificacion', 'codexamen', 'examen', 'fecha', 'hora', 'id', 'bacteriologo', 'observaciones'];
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
 * Extrae el valor de referencia (versión local)
 */
function extract_reference_from_data($datos) {
    $ref_campos = ['referencia', 'rango', 'range', 'normal', 'valor_de_referencia'];
    foreach ($ref_campos as $campo) {
        if (!empty($datos[$campo]) && $datos[$campo] !== 'N/A') {
            return $datos[$campo];
        }
    }
    return '';
}
?>