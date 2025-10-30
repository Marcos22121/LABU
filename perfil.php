<?php
include 'Controlador/db_connect.php';
session_start();

// Si no hay login, redirige
if (!isset($_SESSION['id_usuario'])) {
    header("Location: registro.php");
    exit();
}

// ID del usuario logueado
$id_usuario_logueado = $_SESSION['id_usuario'];

// el btn solo aparece cuando el usuario esta registrado
<?php if (isset($_SESSION['id_usuario'])): ?>
  <a href="logout.php" class="...">Cerrar sesión</a>
<?php endif; ?>


// Tomar el ID del perfil que se quiere ver (?id= en la URL)
$id_usuario_perfil = isset($_GET['id']) ? intval($_GET['id']) : $id_usuario_logueado;

// Traer datos del usuario
$sql = "SELECT u.id_usuario, u.nombre, u.apellido, u.bio, u.id_localidad, u.foto_perfil, 
               t.id_trabajador, e.nombre AS especialidad, t.descripcion_trabajo
        FROM usuarios u
        LEFT JOIN trabajadores t ON u.id_usuario = t.id_usuario
        LEFT JOIN especialidades e ON t.id_especialidad = e.id_especialidad
        WHERE u.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario_perfil);
$stmt->execute();
$result = $stmt->get_result();
$perfil = $result->fetch_assoc();
$stmt->close();

if (!$perfil) {
    die("Perfil no encontrado.");
}

// Obtener nombre de la localidad
$sql_localidad = "SELECT nombre_localidad FROM localidades WHERE id_localidad = ?";
$stmt_localidad = $conn->prepare($sql_localidad);
$stmt_localidad->bind_param("i", $perfil['id_localidad']);
$stmt_localidad->execute();
$result_localidad = $stmt_localidad->get_result();
$localidad = $result_localidad->fetch_assoc();
$stmt_localidad->close();

$nombre_localidad = $localidad ? $localidad['nombre_localidad'] : 'Localidad desconocida';

// --- Procesar nueva reseña ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calificacion'])) {
    $calificacion = intval($_POST['calificacion']);
    $comentario = trim($_POST['comentario']);

    if ($calificacion >= 1 && $calificacion <= 5) {
        $sql_reseña = "INSERT INTO reseñas (id_trabajador, id_usuario, calificacion, comentario, fecha)
                       VALUES (?, ?, ?, ?, NOW())";
        $stmt_reseña = $conn->prepare($sql_reseña);
        $stmt_reseña->bind_param("iiis", $id_usuario_perfil, $id_usuario_logueado, $calificacion, $comentario);
        $stmt_reseña->execute();
        $stmt_reseña->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 pb-20">

<?php include 'header.php'; ?>

<section class="px-6 py-6 max-w-2xl mx-auto">
  <div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex items-start gap-4">
      <img src="<?php echo $perfil['foto_perfil'] ?: 'img/default-profile.png'; ?>" alt="Foto perfil"
          class="w-20 h-20 rounded-full object-cover shadow-sm">
      <div class="flex-1">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-bold text-gray-800">
            <?php echo htmlspecialchars($perfil['nombre'] . " " . $perfil['apellido']); ?>
          </h2>

          <?php if ($id_usuario_logueado == $id_usuario_perfil): ?>
            <a href="editar.php" 
               class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full shadow">
              Editar
            </a>
          <?php endif; ?>
        </div>

        <p class="text-gray-600 mt-1">Vive en <?php echo htmlspecialchars($nombre_localidad); ?></p>
        <?php if ($perfil['bio']): ?>
          <p class="text-sm text-gray-700 mt-3"><?php echo nl2br(htmlspecialchars($perfil['bio'])); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <?php if ($perfil['id_trabajador']): ?>
      <!-- Info de trabajador -->
      <div class="mt-6 border-t pt-4">
        <div class="w-full bg-gray-50 rounded-xl p-4 shadow-sm flex items-start gap-4">
          <div class="flex-1">
            <h3 class="text-lg font-semibold text-gray-800">
              <?php echo htmlspecialchars($perfil['nombre']); ?> trabaja de <?php echo htmlspecialchars($perfil['especialidad']); ?>
            </h3>
            <p class="text-sm text-gray-600 mt-2">
              <?php echo htmlspecialchars($perfil['descripcion_trabajo']); ?>
            </p>
          </div>
        </div>
      </div>

      <?php if ($perfil['id_trabajador'] && $id_usuario_logueado != $id_usuario_perfil): ?>
      <!-- Botón de mensaje -->
      <div class="mt-4 text-center">
        <a href="mensaje.php?id=<?php echo $perfil['id_usuario']; ?>" 
           class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2 rounded-full shadow">
          Enviar mensaje
        </a>
      </div>

      <!-- 🟡 NUEVA SECCIÓN: Dejar reseña -->
      <div class="mt-8 bg-yellow-50 rounded-xl p-5 border border-yellow-200 shadow-sm">
        <h3 class="text-lg font-semibold text-yellow-700 mb-3">Dejar una valoración ⭐</h3>
        <form method="POST" class="space-y-4">
          <div id="estrellas" class="flex gap-2 text-3xl text-gray-300 cursor-pointer justify-center sm:justify-start"></div>
          <input type="hidden" name="calificacion" id="calificacion" required>

          <textarea name="comentario" rows="3" placeholder="Escribí una reseña (opcional)"
            class="w-full border border-gray-300 rounded-lg p-3 resize-none focus:ring-2 focus:ring-yellow-400"></textarea>

          <button type="submit"
            class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-5 rounded-lg shadow transition">
            Enviar reseña
          </button>
        </form>
      </div>

      <!-- 🟢 Reseñas existentes -->
      <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Reseñas de otros usuarios</h3>
        <div class="space-y-3">
          <?php
          $sql = "SELECT r.*, u.nombre AS usuario
                  FROM reseñas r
                  JOIN usuarios u ON r.id_usuario = u.id_usuario
                  WHERE r.id_trabajador = ?
                  ORDER BY r.fecha DESC";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $id_usuario_perfil);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              while ($r = $result->fetch_assoc()) {
                  echo "<div class='bg-white border-l-4 border-yellow-400 rounded-lg p-4 shadow-sm'>";
                  echo "<div class='flex justify-between items-center'>";
                  echo "<span class='font-semibold text-gray-800'>" . htmlspecialchars($r['usuario']) . "</span>";
                  echo "<span class='text-yellow-400 text-lg'>" . str_repeat('★', $r['calificacion']) . "</span>";
                  echo "</div>";
                  if (!empty($r['comentario'])) {
                      echo "<p class='text-gray-700 italic mt-1'>" . htmlspecialchars($r['comentario']) . "</p>";
                  }
                  echo "<small class='text-gray-500 text-xs'>" . $r['fecha'] . "</small>";
                  echo "</div>";
              }
          } else {
              echo "<p class='text-gray-500 italic text-center'>Todavía no hay reseñas para este trabajador.</p>";
          }
          ?>
        </div>
      </div>
      <?php endif; ?>

    <?php elseif ($id_usuario_logueado == $id_usuario_perfil): ?>
      <!-- Card para comenzar a trabajar -->
      <div class="mt-6 border-t pt-4">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 shadow-sm text-center">
          <img src="img/trabajo.webp" alt="Trabajo" class="w-24 h-24 mx-auto mb-3 rounded-lg object-cover">
          <h3 class="text-lg font-semibold text-gray-800 mb-2">¿Querés empezar a trabajar?</h3>
          <p class="text-sm text-gray-600 mb-4">
            Publicá tus servicios en la app, conectá con clientes y hacé crecer tu trabajo fácilmente.
          </p>
          <a href="trabajar.php" 
            class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2 rounded-full shadow">
            Comenzar a trabajar
          </a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include 'footer.php'; ?>

<script>
// 🌟 Interactividad de estrellas
const estrellasCont = document.getElementById('estrellas');
const inputCalificacion = document.getElementById('calificacion');
for (let i = 1; i <= 5; i++) {
    const span = document.createElement('span');
    span.textContent = '★';
    span.dataset.value = i;
    span.addEventListener('click', () => {
        inputCalificacion.value = i;
        document.querySelectorAll('#estrellas span').forEach((s, idx) => {
            s.classList.toggle('text-yellow-400', idx < i);
            s.classList.toggle('text-gray-300', idx >= i);
            s.classList.toggle('scale-110', idx === i - 1);
        });
    });
    estrellasCont.appendChild(span);
}
</script>

</body>
</html>
