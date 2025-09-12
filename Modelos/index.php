<?php
include '../Controlador/db_connect.php';

// Consulta: especialidades más populares por cantidad de trabajadores
$sql = "
    SELECT e.id_especialidad, e.nombre, e.descripcion, e.foto_url, COUNT(t.id_trabajador) AS cantidad
    FROM especialidades e
    LEFT JOIN trabajadores t ON e.id_especialidad = t.id_especialidad
    GROUP BY e.id_especialidad
    ORDER BY cantidad DESC
    LIMIT 5
";

$result = $conn->query($sql);

$especialidades = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $especialidades[] = $row;
    }
} else {
    // Si no hay nada cargado, devolver algunas random
    $sql_random = "SELECT id_especialidad, nombre, descripcion, foto_url FROM especialidades ORDER BY RAND() LIMIT 5";
    $result_random = $conn->query($sql_random);

    if ($result_random && $result_random->num_rows > 0) {
        while ($row = $result_random->fetch_assoc()) {
            $especialidades[] = $row;
        }
    }
}

// Devolver como JSON
header('Content-Type: application/json');
echo json_encode($especialidades);

$conn->close();
?>
