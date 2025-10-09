<?php
include 'Controlador/db_connect.php';
session_start();

// Si no hay login, redirige
if (!isset($_SESSION['id_usuario'])) {
    header("Location: registro.php");
    exit();
}

// Tomar id del perfil a ver
$id_usuario_logueado = $_SESSION['id_usuario'];
$id_usuario_perfil = isset($_GET['id_usuario']) ? intval($_GET['id_usuario']) : $id_usuario_logueado;

// Traer datos básicos del usuario
$sql = "SELECT u.id_usuario, u.nombre, u.bio, u.apellido, u.id_localidad, u.foto_perfil, t.id_trabajador, e.nombre AS especialidad, t.descripcion_trabajo
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




$sql_localidad = "SELECT nombre_localidad FROM localidades WHERE id_localidad = ?";
$stmt_localidad = $conn->prepare($sql_localidad);
$stmt_localidad->bind_param("i", $perfil['id_localidad']);
$stmt_localidad->execute();
$result_localidad = $stmt_localidad->get_result();
$localidad = $result_localidad->fetch_assoc();
$stmt_localidad->close();

// Si no se encuentra la localidad, usar texto por defecto
$nombre_localidad = $localidad ? $localidad['nombre_localidad'] : 'Localidad desconocida';
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
            <a href="editar.php" class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full shadow">
              Editar
            </a>
          <?php endif; ?>
        </div>
        <p class="text-gray-600 mt-1">
          Vive en <?php echo htmlspecialchars($nombre_localidad); ?></p>
      <br><hr><br>
      </div>
      
      
    </div>

    <?php if ($perfil['id_trabajador']): ?>
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
    <?php elseif ($id_usuario_logueado == $id_usuario_perfil): ?>


                      <p class="text-m text-gray-800 mb-1"><?php echo htmlspecialchars($perfil['bio'])?></p>

      <!-- Card comenzar a trabajar -->
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

</body>
</html>
