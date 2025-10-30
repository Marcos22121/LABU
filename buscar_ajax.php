<?php
session_start();
include('Controlador/db_connect.php');

$id_usuario = $_SESSION['id_usuario'] ?? null;

$q = $_GET['q'] ?? '';
$especialidad = $_GET['especialidad'] ?? '';
$solo_mi_localidad = isset($_GET['solo_mi_localidad']);
$localidades = $_GET['localidades'] ?? [];

$sql = "SELECT t.id_trabajador, t.descripcion_trabajo, u.id_usuario, u.nombre, u.apellido, 
               u.foto_perfil, l.nombre_localidad, e.nombre AS especialidad
        FROM trabajadores t
        JOIN usuarios u ON t.id_usuario = u.id_usuario
        LEFT JOIN localidades l ON u.id_localidad = l.id_localidad
        LEFT JOIN especialidades e ON t.id_especialidad = e.id_especialidad
        WHERE 1=1";

$params = [];
$types = "";

// Filtro texto
if ($q !== '') {
    $sql .= " AND (u.nombre LIKE ? OR u.apellido LIKE ? OR e.nombre LIKE ? OR t.descripcion_trabajo LIKE ? OR l.nombre_localidad LIKE ?)";
    $q_like = "%$q%";
    $params = array_merge($params, [$q_like, $q_like, $q_like, $q_like, $q_like]);
    $types .= "sssss";
}

// Filtro especialidad
if (!empty($especialidad)) {
    $sql .= " AND e.nombre = ?";
    $params[] = $especialidad;
    $types .= "s";
}

// Filtro varias localidades
if (!empty($localidades)) {
    $placeholders = implode(',', array_fill(0, count($localidades), '?'));
    $sql .= " AND l.id_localidad IN ($placeholders)";
    foreach ($localidades as $loc) {
        $params[] = (int)$loc;
        $types .= "i";
    }
}

// Solo mi localidad
if ($solo_mi_localidad && $id_usuario) {
    $res = $conn->prepare("SELECT id_localidad FROM usuarios WHERE id_usuario = ?");
    $res->bind_param("i", $id_usuario);
    $res->execute();
    $res_data = $res->get_result()->fetch_assoc();
    if ($res_data && $res_data['id_localidad']) {
        $sql .= " AND u.id_localidad = ?";
        $params[] = $res_data['id_localidad'];
        $types .= "i";
    }
}

$stmt = $conn->prepare($sql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='text-center text-gray-500 py-10 bg-white rounded-xl shadow-sm'>
            <p class='text-lg font-medium mb-2'>No se encontraron trabajadores.</p>
            <p class='text-sm'>Proba con otros filtros o palabras clave.</p>
          </div>";
    exit;
}
?>

<?php while ($row = $result->fetch_assoc()): ?>
  <div class="w-full bg-white rounded-xl shadow-md p-4 flex flex-col sm:flex-row sm:items-start gap-4 hover:shadow-lg transition">
    
    <!-- Imagen -->
    <img src="<?= $row['foto_perfil'] ?: 'img/default-profile.png' ?>" 
         alt="Foto trabajador" 
         class="w-20 h-20 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-blue-500 flex-shrink-0">

    <!-- Contenido -->
    <div class="flex-1">
      <h3 class="text-lg font-semibold text-gray-800 leading-tight">
        <?= htmlspecialchars($row['nombre'] . " " . $row['apellido']) ?>
      </h3>

      <?php if ($row['especialidad']): ?>
        <span class="inline-block px-2 py-1 mt-1 text-xs bg-blue-100 text-blue-700 rounded-full">
          <?= htmlspecialchars($row['especialidad']) ?>
        </span>
      <?php endif; ?>

      <p class="text-sm text-gray-600 mt-2 leading-snug">
        <?= htmlspecialchars($row['descripcion_trabajo'] ?: "Sin descripción") ?>
      </p>

      <p class="text-xs text-gray-500 mt-1">
        <?= htmlspecialchars($row['nombre_localidad'] ?: "Localidad desconocida") ?>
      </p>

      <!-- Botones -->
      <div class="mt-3 flex flex-wrap gap-2">
        <a href="perfil.php?id=<?= $row['id_usuario'] ?>" 
           class="text-sm font-medium text-blue-600 hover:underline">Ver perfil →</a>

        <a href="mensaje.php?id=<?= $row['id_usuario'] ?>" 
           class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white text-xs sm:text-sm font-medium px-3 py-1.5 rounded-lg transition">
          💬 Enviar mensaje
        </a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
