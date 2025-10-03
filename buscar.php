<?php
// buscar.php
$conexion = new mysqli("localhost", "root", "", "labu");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$termino = isset($_GET['q']) ? $conexion->real_escape_string($_GET['q']) : '';

$sql = "
    SELECT u.id_usuario, u.nombre, u.apellido, u.telefono, u.email,
           e.nombre AS especialidad, t.descripcion_trabajo, t.puntaje_promedio
    FROM trabajadores t
    INNER JOIN usuarios u ON t.id_usuario = u.id_usuario
    INNER JOIN especialidades e ON t.id_especialidad = e.id_especialidad
    WHERE (u.nombre LIKE '%$termino%' 
           OR u.apellido LIKE '%$termino%' 
           OR e.nombre LIKE '%$termino%')
      AND t.activo = 1
";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Trabajadores</title>
</head>
<body>
    <h2>Buscar trabajadores / especialidades</h2>
    <form method="get" action="buscar.php">
        <input type="text" name="q" placeholder="Ej: Pedro, Plomero, Electricista" value="<?= htmlspecialchars($termino) ?>">
        <button type="submit">Buscar</button>
    </form>

    <hr>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Especialidad</th>
                <th>Descripción</th>
                <th>Puntaje</th>
            </tr>
            <?php while($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['apellido']) ?></td>
                    <td><?= htmlspecialchars($row['telefono']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['especialidad']) ?></td>
                    <td><?= htmlspecialchars($row['descripcion_trabajo']) ?></td>
                    <td><?= htmlspecialchars($row['puntaje_promedio']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No se encontraron resultados.</p>
    <?php endif; ?>

</body>
</html>

<?php
$conexion->close();
?>
