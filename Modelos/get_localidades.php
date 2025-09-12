<?php
// ¡SOLUCIÓN PARA CORS!
header('Access-Control-Allow-Origin: *');

// Incluir el archivo de conexión a la base de datos
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['id_provincia']) || empty($_GET['id_provincia'])) {
    echo json_encode([]);
    $conn->close();
    exit();
}

$id_provincia = $_GET['id_provincia'];
$localidades = [];

$stmt = $conn->prepare("SELECT id_localidad, nombre_localidad FROM localidades WHERE id_provincia = ? ORDER BY nombre_localidad");
$stmt->bind_param("i", $id_provincia);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $localidades[] = $row;
}

echo json_encode($localidades);

$stmt->close();
$conn->close();
?>
