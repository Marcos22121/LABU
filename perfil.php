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
$sql = "SELECT u.id_usuario, u.nombre, u.apellido, u.id_localidad, u.foto_perfil, t.id_trabajador, e.nombre AS especialidad, t.descripcion_trabajo
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

<!-- Header -->
<header class="flex justify-between items-center px-4 py-3 border-b border-gray-200 bg-white shadow-sm">
  <div class="logo">
    <img src="img/labu.png" alt="Logo" class="h-12">
  </div>
  <div class="flex items-center gap-4">
    <a href="#notificaciones" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100" title="Notificaciones">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-lin  ecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 17h5l-1.405-1.405A2.032 2.032 
             0 0118 14.158V11a6.002 6.002 
             0 00-4-5.659V5a2 2 0 10-4 
             0v.341C7.67 6.165 6 8.388 6 
             11v3.159c0 .538-.214 1.055-.595 
             1.436L4 17h5m6 0v1a3 3 0 
             11-6 0v-1m6 0H9" />
      </svg>
    </a>
    <a href="#mensajes" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100" title="Mensajes">
      <img src="img/chat.png" alt="Mensajes" class="w-6 h-6 object-contain">
    </a>
  </div>
</header>

<!-- Perfil -->
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
          Vive en <?php echo htmlspecialchars($nombre_localidad); ?>
        </p>
      </div>
    </div>

    <!-- Card de trabajador -->
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

<!-- Footer -->
<nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 flex justify-around items-center py-1.5 shadow-md z-50">
  <a href="index.php" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 w-1/3">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
    </svg>
    <span class="text-xs font-medium">Inicio</span>
  </a>

  <a href="#trabajador" class="relative -mt-6 w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg hover:brightness-110 transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 
           1.79-4 4 1.79 4 4 4zm0 2c-3.31 
           0-6 2.69-6 6h12c0-3.31-2.69-6-6-6z" />
    </svg>
  </a>

  <a href="perfil.php" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 w-1/3">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M5.121 17.804A9 9 0 1112 21a9 
           9 0 01-6.879-3.196zM15 11a3 3 0 
           11-6 0 3 3 0 016 0z" />
    </svg>
    <span class="text-xs font-medium">Cuenta</span>
  </a>
</nav>

</body>
</html>
