<?php
/**
 * Endpoint de autenticación
 * Maneja login, logout y verificación de sesión
 */

require_once 'config.php';
session_start();

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    
    switch ($action) {
        case 'login':
            handle_login();
            break;
            
        case 'logout':
            handle_logout();
            break;
            
        case 'check':
            handle_auth_check();
            break;
            
        default:
            json_response(false, null, "Acción no válida", 400);
    }
    
} catch (Exception $e) {
    json_response(false, null, "Error en autenticación: " . $e->getMessage(), 500);
}

/**
 * Maneja el proceso de login
 */
function handle_login() {
    global $mysqli;
    
    $password = $_POST['password'] ?? '';
    
    if (empty($password)) {
        json_response(false, null, "La contraseña es requerida", 400);
    }
    
    // Consulta a la base de datos
    $stmt = safe_query($mysqli, 
        "SELECT nombreLaboratorio, nombreCorto, urlLogoLaboratorio FROM configuracion WHERE tarjetaPlaboratorio = ? LIMIT 1",
        [$password],
        "s"
    );
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        
        // Establecer sesión
        $_SESSION['autenticado'] = true;
        $_SESSION['usuario_nombre'] = $user_data['nombreLaboratorio'];
        $_SESSION['lab_id'] = md5($password);
        $_SESSION['lab_nombre'] = $user_data['nombreCorto'];
        
        // Preparar respuesta con configuración
        $lab_config = [
            'nombreCorto' => $user_data['nombreCorto'],
            'nombreLaboratorio' => $user_data['nombreLaboratorio'],
            'urlLogoLaboratorio' => "data:image/png;base64," . $user_data['urlLogoLaboratorio']
        ];
        
        json_response(true, [
            'userName' => $user_data['nombreLaboratorio'],
            'labId' => $_SESSION['lab_id'],
            'labConfig' => $lab_config
        ], "Login exitoso");
        
    } else {
        json_response(false, null, "Credenciales inválidas", 401);
    }
}

/**
 * Maneja el proceso de logout
 */
function handle_logout() {
    session_destroy();
    json_response(true, null, "Sesión cerrada correctamente");
}

/**
 * Verifica el estado de autenticación
 */
function handle_auth_check() {
    if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true) {
        json_response(true, [
            'isAuthenticated' => true,
            'userName' => $_SESSION['usuario_nombre'] ?? null,
            'labId' => $_SESSION['lab_id'] ?? null
        ], "Usuario autenticado");
    } else {
        json_response(true, [
            'isAuthenticated' => false,
            'userName' => null,
            'labId' => null
        ], "Usuario no autenticado");
    }
}
?>