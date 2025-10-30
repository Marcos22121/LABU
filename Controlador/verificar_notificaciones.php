<?php
require_once 'db_connect.php';
session_start();

$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    echo json_encode(['nuevas' => 0]);
    exit;
}

$sql = "
    SELECT COUNT(*) AS nuevas
    FROM mensajes m
    INNER JOIN participantes_conversacion pc ON pc.id_conversacion = m.id_conversacion
    WHERE pc.id_usuario = ? 
      AND m.id_remitente != ? 
      AND m.leido = 0
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

echo json_encode(['nuevas' => (int)$row['nuevas']]);
