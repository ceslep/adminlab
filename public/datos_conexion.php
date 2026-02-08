<?php
// Database connection configuration
// Based on the database schema from bd.sql

$db_host = 'localhost';
$db_user = 'iedeocci_lab'; // Based on database name and user pattern
$db_pass = ''; // Password should be configured appropriately
$db_name = 'iedeocci_mycar'; // From bd.sql

// Create connection
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($mysqli->connect_error) {
    // In development, you might want to show this error
    // In production, log it instead
    error_log("Database connection failed: " . $mysqli->connect_error);
    // Don't output error details to client for security
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos'
    ]);
    exit();
}

// Set charset to utf8
$mysqli->set_charset("utf8");
?>