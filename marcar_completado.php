<?php
include 'Controlador/db_connect.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: registro.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_conversacion = intval($_POST['id_conversacion'] ?? 0);

// Verificar que el usuario sea trabajador participante de esa conversación
$sql = "SELECT c.id_conversacion
        FROM conversaciones c
        JOIN participantes_conversacion p ON c.id_conversacion = p.id_conversacion
        JOIN trabajadores t ON p.id_usuario = t.id_usuario
        WHERE c.id_conversacion = ? AND t.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_conversacion, $id_usuario);
$stmt->execute();
$es_participante_trabajador = $stmt->get_result()->num_rows > 0;
$stmt->close();

if ($es_participante_trabajador) {
    $stmt = $conn->prepare("UPDATE conversaciones SET trabajo_completado = 1 WHERE id_conversacion = ?");
    $stmt->bind_param("i", $id_conversacion);
    $stmt->execute();
    $stmt->close();
}

header("Location: mensajes.php");
exit();
