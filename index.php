<?php
include 'Controlador/db_connect.php';
// Incluimos el archivo de conexión para poder usarlo en la página
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labu - Encontrá a tu trabajador</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="Estilos/style.css">
</head>
<body>

    <div class="header">
        <h1>Encontrá a tu profesional ideal</h1>
        <p>Busca por especialidad, localidad o nombre para encontrar al mejor trabajador cerca tuyo.</p>
        <div class="search-bar-container">
            <form class="search-bar" action="index.php" method="GET">
                <input type="text" name="query" placeholder="Ej: Plomero, Electricista...">
                <select name="especialidad">
                    <option value="">Todas las especialidades</option>
                    <?php
                        include 'db_connect.php';
                        $sql = "SELECT DISTINCT especialidad FROM trabajadores ORDER BY especialidad";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row['especialidad']) . "'>" . htmlspecialchars($row['especialidad']) . "</option>";
                            }
                        }
                        $conn->close();
                    ?>
                </select>
                <select id="provincia-select" name="provincia">
                    <option value="">Todas las provincias</option>
                    <?php
                        include 'db_connect.php';
                        $sql = "SELECT id_provincia, nombre_provincia FROM provincias ORDER BY nombre_provincia";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($row['id_provincia']) . "'>" . htmlspecialchars($row['nombre_provincia']) . "</option>";
                            }
                        }
                        $conn->close();
                    ?>
                </select>
                <button type="submit">Buscar</button>
            </form>
        </div>
    </div>

    <div class="results-container">
        <?php
        include 'db_connect.php';

        $where_clauses = [];
        $params = [];
        $param_types = "";

        if (isset($_GET['query']) && !empty($_GET['query'])) {
            $query_term = "%" . $_GET['query'] . "%";
            $where_clauses[] = "(t.descripcion_trabajo LIKE ? OR t.especialidad LIKE ?)";
            $params[] = $query_term;
            $params[] = $query_term;
            $param_types .= "ss";
        }

        if (isset($_GET['especialidad']) && !empty($_GET['especialidad'])) {
            $where_clauses[] = "t.especialidad = ?";
            $params[] = $_GET['especialidad'];
            $param_types .= "s";
        }

        if (isset($_GET['provincia']) && !empty($_GET['provincia'])) {
            $where_clauses[] = "l.id_provincia = ?";
            $params[] = $_GET['provincia'];
            $param_types .= "i";
        }

        $sql = "SELECT u.nombre, u.apellido, l.nombre_localidad, t.especialidad, t.descripcion_trabajo, t.puntaje_promedio
                FROM trabajadores AS t
                JOIN usuarios AS u ON t.id_usuario = u.id_usuario
                JOIN localidades AS l ON u.id_localidad = l.id_localidad";

        if (!empty($where_clauses)) {
            $sql .= " WHERE " . implode(" AND ", $where_clauses);
        }

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($param_types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='worker-card'>";
                // Aquí podrías agregar una imagen de perfil si la tuvieras
                echo "<h3>" . htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellido']) . "</h3>";
                echo "<p>Especialidad: " . htmlspecialchars($row['especialidad']) . "</p>";
                echo "<p>Localidad: " . htmlspecialchars($row['nombre_localidad']) . "</p>";
                echo "<p>Puntaje: " . htmlspecialchars($row['puntaje_promedio']) . "/5</p>";
                echo "<p>Descripción: " . htmlspecialchars($row['descripcion_trabajo']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No se encontraron trabajadores que coincidan con la búsqueda.</p>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>

</body>
</html>
