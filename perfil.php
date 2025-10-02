<?php
include('Controlador/db_connect.php');

// Suponiendo que el usuario está logueado y su ID es 5
$id_usuario = 5;

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT u.*, l.nombre_localidad, t.id_trabajador, e.nombre AS especialidad
                        FROM usuarios u
                        LEFT JOIN localidades l ON u.id_localidad = l.id_localidad
                        LEFT JOIN trabajadores t ON u.id_usuario = t.id_usuario
                        LEFT JOIN especialidades e ON t.id_especialidad = e.id_especialidad
                        WHERE u.id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil - LABU</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 pb-24">

  <!-- Header -->
  <header class="flex justify-between items-center px-4 py-3 border-b border-gray-200 bg-white sticky top-0 z-30">
    <div class="logo">
      <img src="img/labu.png" alt="Logo" class="h-12">
    </div>

    <div class="flex items-center gap-4">
      <a href="#notificaciones" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100" title="Notificaciones">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
  <div class="max-w-md mx-auto mt-6 bg-white rounded-2xl shadow-lg p-6">
    <!-- Imagen de perfil -->
    <div class="flex justify-center mb-6">
      <img src="<?= $usuario['foto_perfil'] ? $usuario['foto_perfil'] : 'img/default-profile.png' ?>" 
           alt="Foto de perfil" class="w-24 h-24 rounded-full object-cover border-4 border-purple-500">
    </div>

    <!-- Nombre y correo -->
    <div class="text-center mb-6">
      <h2 class="text-2xl font-bold text-gray-800"><?= $usuario['nombre'] ?> <?= $usuario['apellido'] ?></h2>
      <p class="text-gray-500"><?= $usuario['email'] ?></p>
    </div>

    <!-- Información adicional -->
    <div class="space-y-4">
      <div>
        <label class="block text-gray-600 font-semibold mb-1">DNI</label>
        <input type="text" value="<?= $usuario['dni'] ?>" readonly 
               class="w-full p-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-800">
      </div>

      <div>
        <label class="block text-gray-600 font-semibold mb-1">Fecha de Nacimiento</label>
        <input type="text" value="<?= $usuario['fecha_nacimiento'] ?>" readonly 
               class="w-full p-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-800">
      </div>

      <div>
        <label class="block text-gray-600 font-semibold mb-1">Género</label>
        <input type="text" value="<?= $usuario['genero'] ?>" readonly 
               class="w-full p-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-800">
      </div>

      <div>
        <label class="block text-gray-600 font-semibold mb-1">Teléfono</label>
        <input type="text" value="<?= $usuario['telefono'] ?>" readonly 
               class="w-full p-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-800">
      </div>

      <div>
        <label class="block text-gray-600 font-semibold mb-1">Localidad</label>
        <input type="text" value="<?= $usuario['nombre_localidad'] ?>" readonly 
               class="w-full p-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-800">
      </div>

      <?php if($usuario['id_trabajador']): ?>
      <div>
        <label class="block text-gray-600 font-semibold mb-1">Especialidad</label>
        <input type="text" value="<?= $usuario['especialidad'] ?>" readonly 
               class="w-full p-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-800">
      </div>
      <?php endif; ?>
    </div>

    <!-- Botón editar perfil -->
    <div class="mt-6">
      <a href="editar_perfil.php" 
         class="block w-full text-center py-3 bg-gradient-to-r from-purple-600 to-indigo-500 text-white font-bold rounded-lg shadow hover:shadow-lg transition-all">
         Editar Perfil
      </a>
    </div>
  </div>

  <!-- Footer / Nav inferior -->
  <nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 flex justify-around items-center py-1.5 shadow-md z-50">
    <a href="index.php" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 w-1/3">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
      </svg>
      <span class="text-xs font-medium">Inicio</span>
    </a>

    <a href="perfil.php" 
       class="relative -mt-6 w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg hover:brightness-110 transition">
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
