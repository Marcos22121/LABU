<?php
include 'Controlador/db_connect.php';
session_start();

if (!isset($_SESSION['id_usuario'])) exit;

$id_usuario = $_SESSION['id_usuario'];
$id_conversacion = intval($_POST['id_conversacion']);
$contenido = trim($_POST['mensaje']);
$tipo = 'texto';
$url_archivo = null;

// Archivo adjunto
if (!empty($_FILES['archivo']['name'])) {
    $dir = "uploads/mensajes/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $fileName = uniqid("msg_") . "_" . basename($_FILES["archivo"]["name"]);
    $target = $dir . $fileName;

    if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $target)) {
        $url_archivo = $target;
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $tipo = in_array($ext, ['jpg','jpeg','png','webp','gif','jfif']) ? 'imagen' : 'archivo';
    }
}

$stmt = $conn->prepare("INSERT INTO mensajes (id_conversacion, id_remitente, contenido, tipo, url_archivo)
                        VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisss", $id_conversacion, $id_usuario, $contenido, $tipo, $url_archivo);
$stmt->execute();
$stmt->close();

$conn->query("UPDATE conversaciones SET ultimo_mensaje = NOW() WHERE id_conversacion = $id_conversacion");

echo "ok";
