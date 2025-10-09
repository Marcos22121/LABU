<?php
// Configuración de la base de datos (AlwaysData)
$host = "mysql-labu.alwaysdata.net"; // Servidor remoto
$usuario = "labu";                   // Tu usuario de AlwaysData
$contrasena = "bolas123!";  // Cambiá esto por tu contraseña real
$bd = "labu_bd";                     // El nombre de tu base de datos en AlwaysData

// Crear conexión usando MySQLi
$conn = new mysqli($host, $usuario, $contrasena, $bd, 3306);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Forzar uso de SSL (AlwaysData usa SSL obligatorio)
$conn->ssl_set(NULL, NULL, NULL, NULL, NULL);

// Establecer el juego de caracteres a utf8mb4
if (!$conn->set_charset("utf8mb4")) {
    die("Error configurando utf8mb4: " . $conn->error);
}

?>
