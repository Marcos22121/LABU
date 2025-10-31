<?php
include 'Controlador/db_connect.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: registro.php");
    exit();   
}

$id_usuario_logueado = $_SESSION['id_usuario'];
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

// Localidad
$sql_localidad = "SELECT nombre_localidad FROM localidades WHERE id_localidad = ?";
$stmt = $conn->prepare($sql_localidad);
$stmt->bind_param("i", $perfil['id_localidad']);
$stmt->execute();
$res_localidad = $stmt->get_result();
$loc = $res_localidad->fetch_assoc();
$stmt->close();

$nombre_localidad = $loc ? $loc['nombre_localidad'] : 'Localidad desconocida';

$mensaje_feedback = "";

// --- Procesar nueva reseña ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calificacion'])) {
    $calificacion = intval($_POST['calificacion']);
    $comentario = trim($_POST['comentario']);

    if ($id_usuario_logueado === $id_usuario_perfil) {
        $mensaje_feedback = "<p class='text-red-500 text-center mt-2'>No podés dejarte una reseña a vos mismo.</p>";
    } elseif ($calificacion < 1 || $calificacion > 5) {
        $mensaje_feedback = "<p class='text-red-500 text-center mt-2'>Calificación inválida.</p>";
    } else {
        // Buscar id_trabajador del perfil
        $sql = "SELECT id_trabajador FROM trabajadores WHERE id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario_perfil);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            $mensaje_feedback = "<p class='text-red-500 text-center mt-2'>Este usuario no es un trabajador válido.</p>";
        } else {
            $id_trabajador = $res->fetch_assoc()['id_trabajador'];
            $stmt->close();

            // Verificar conversación y trabajo completado
            $sql = "SELECT c.trabajo_completado
                    FROM conversaciones c
                    JOIN participantes_conversacion p1 ON c.id_conversacion = p1.id_conversacion AND p1.id_usuario = ?
                    JOIN participantes_conversacion p2 ON c.id_conversacion = p2.id_conversacion AND p2.id_usuario = ?
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $id_usuario_logueado, $id_usuario_perfil);
            $stmt->execute();
            $conv = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$conv) {
                $mensaje_feedback = "<p class='text-red-500 text-center mt-2'>No podés dejar una reseña sin haber tenido una conversación con este trabajador.</p>";
            } elseif ($conv['trabajo_completado'] != 1) {
                $mensaje_feedback = "<p class='text-yellow-600 text-center mt-2'>El trabajador todavía no marcó este trabajo como completado.</p>";
            } else {
                // Verificar si ya existe reseña
                $sql = "SELECT id_review FROM reseñas WHERE id_trabajador = ? AND id_usuario = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $id_trabajador, $id_usuario_logueado);
                $stmt->execute();
                $res = $stmt->get_result();

                if ($res->num_rows > 0) {
                    $sql = "UPDATE reseñas SET calificacion = ?, comentario = ?, fecha = NOW() 
                            WHERE id_trabajador = ? AND id_usuario = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isii", $calificacion, $comentario, $id_trabajador, $id_usuario_logueado);
                    $stmt->execute();
                    $mensaje_feedback = "<p class='text-green-600 text-center mt-2'>Tu reseña fue actualizada correctamente.</p>";
                } else {
                    $sql = "INSERT INTO reseñas (id_trabajador, id_usuario, calificacion, comentario, fecha)
                            VALUES (?, ?, ?, ?, NOW())";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iiis", $id_trabajador, $id_usuario_logueado, $calificacion, $comentario);
                    $stmt->execute();
                    $mensaje_feedback = "<p class='text-green-600 text-center mt-2'>¡Gracias por tu reseña!</p>";
                }
                $stmt->close();
            }
        }
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
        <h2 class="text-xl font-bold text-gray-800">
          <?php echo htmlspecialchars($perfil['nombre'] . " " . $perfil['apellido']); ?>
        </h2>
        <p class="text-gray-600 mt-1">Vive en <?php echo htmlspecialchars($nombre_localidad); ?></p>
        <?php if ($perfil['bio']): ?>
          <p class="text-sm text-gray-700 mt-3"><?php echo nl2br(htmlspecialchars($perfil['bio'])); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="flex justify-center mt-4">
      <?php if ($id_usuario_logueado == $id_usuario_perfil): ?>
        <a href="editar.php" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-full shadow">Editar</a>
        <a href="logout.php" class="ml-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-full shadow">Cerrar sesión</a>
      <?php endif; ?>
    </div>

    <?php if ($perfil['id_trabajador']): ?>
      <div class="mt-6 border-t pt-4">
        <h3 class="text-lg font-semibold text-gray-800">
          <?php echo htmlspecialchars($perfil['nombre']); ?> trabaja de <?php echo htmlspecialchars($perfil['especialidad']); ?>
        </h3>
        <p class="text-sm text-gray-600 mt-2"><?php echo htmlspecialchars($perfil['descripcion_trabajo']); ?></p>
      </div>

      <?php if ($id_usuario_logueado != $id_usuario_perfil): ?>
        <div class="mt-4 text-center">
          <a href="mensaje.php?id=<?php echo $perfil['id_usuario']; ?>"
             class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2 rounded-full shadow">
             Enviar mensaje
          </a>
        </div>

        <!-- Sección reseñas -->
        <div class="mt-8 bg-yellow-50 rounded-xl p-5 border border-yellow-200 shadow-sm">
          <h3 class="text-lg font-semibold text-yellow-700 mb-3">Dejar una valoración ⭐</h3>
          <?php echo $mensaje_feedback; ?>
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

        <!-- Reseñas existentes -->
        <div class="mt-8">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Reseñas de otros usuarios</h3>
          <div class="space-y-3">
            <?php
            $sql = "SELECT r.*, u.nombre, u.apellido 
                    FROM reseñas r 
                    JOIN usuarios u ON r.id_usuario = u.id_usuario 
                    WHERE r.id_trabajador = ? 
                    ORDER BY r.fecha DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $perfil['id_trabajador']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($r = $result->fetch_assoc()) {
                    echo "<div class='bg-white border-l-4 border-yellow-400 rounded-lg p-4 shadow-sm'>";
                    echo "<div class='flex justify-between items-center'>";
                    echo "<span class='font-semibold text-gray-800'>" . htmlspecialchars($r['nombre'] . ' ' . $r['apellido']) . "</span>";
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
            $stmt->close();
            ?>
          </div>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div class="mt-6 border-t pt-4 text-center">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 shadow-sm">
          <h3 class="text-lg font-semibold text-gray-800 mb-2">¿Querés empezar a trabajar?</h3>
          <p class="text-sm text-gray-600 mb-4">Publicá tus servicios y conectá con clientes fácilmente.</p>
          <a href="trabajar.php" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium px-5 py-2 rounded-full shadow">
            Comenzar a trabajar
          </a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include 'footer.php'; ?>

<script>
const estrellasCont = document.getElementById('estrellas');
const inputCalificacion = document.getElementById('calificacion');
for (let i = 1; i <= 5; i++) {
  const s = document.createElement('span');
  s.textContent = '★';
  s.dataset.value = i;
  s.addEventListener('click', () => {
    inputCalificacion.value = i;
    document.querySelectorAll('#estrellas span').forEach((x, idx) => {
      x.classList.toggle('text-yellow-400', idx < i);
      x.classList.toggle('text-gray-300', idx >= i);
      x.classList.toggle('scale-110', idx === i - 1);
    });
  });
  estrellasCont.appendChild(s);
}
</script>

</body>
</html>
