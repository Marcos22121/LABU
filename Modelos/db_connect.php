<?php
// Configuración de la base de datos
$host = "localhost";
$usuario = "root";
$contrasena = "";
$bd = "labu"; // El nombre de la base de datos que especificaste

// Crear la conexión
$conn = new mysqli($host, $usuario, $contrasena, $bd);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer el juego de caracteres a utf8mb4
$conn->set_charset("utf8mb4");

// Esta función es opcional, pero útil para encriptar contraseñas

?>
