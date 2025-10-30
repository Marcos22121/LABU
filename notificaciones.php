<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notificaciones - LABU</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex flex-col">

  <?php include 'header.php'; ?>

  <main class="flex-grow px-6 py-10 max-w-4xl mx-auto">
    <h1 class="text-3xl font-semibold text-gray-800 mb-8 border-b pb-3">
      Notificaciones
    </h1>

    <div class="flex items-start bg-gray-50 rounded-xl shadow-md p-4 mb-4 hover:shadow-lg transition">
      <img src="https://randomuser.me/api/portraits/women/45.jpg" 
           alt="Foto de perfil"
           class="w-14 h-14 rounded-full object-cover border-2 border-blue-500 mr-4">
      <div class="flex-1">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">
          Nueva solicitud de contacto
        </h2>
        <p class="text-sm text-gray-600 leading-snug">
          <span class="font-medium text-blue-600">María González</span> quiere conectarse contigo para un proyecto de diseño web.
        </p>
        <p class="text-xs text-gray-400 mt-2">Hace 2 horas</p>
      </div>
    </div>

    <div class="flex items-start bg-gray-50 rounded-xl shadow-md p-4 mb-4 hover:shadow-lg transition">
      <div class="w-14 h-14 flex items-center justify-center rounded-full bg-blue-100 border-2 border-blue-500 mr-4">
        <img src="https://cdn-icons-png.flaticon.com/512/1827/1827504.png" 
             alt="Icono sistema"
             class="w-8 h-8 opacity-80">
      </div>
      <div class="flex-1">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">
          Actualización del sistema
        </h2>
        <p class="text-sm text-gray-600 leading-snug">
          Tu cuenta ha sido actualizada correctamente. Revisa las nuevas funciones disponibles en tu panel.
        </p>
        <p class="text-xs text-gray-400 mt-2">Hace 5 horas</p>
      </div>
    </div>

    <div class="flex items-start bg-gray-50 rounded-xl shadow-md p-4 mb-4 hover:shadow-lg transition">
      <img src="https://randomuser.me/api/portraits/men/34.jpg" 
           alt="Foto de perfil"
           class="w-14 h-14 rounded-full object-cover border-2 border-green-500 mr-4">
      <div class="flex-1">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">
          Nueva oferta laboral
        </h2>
        <p class="text-sm text-gray-600 leading-snug">
          <span class="font-medium text-green-600">Carlos Ruiz</span> publicó una oferta para un trabajo de mantenimiento en tu zona.
        </p>
        <p class="text-xs text-gray-400 mt-2">Ayer</p>
      </div>
    </div>

    <div class="flex items-start bg-gray-50 rounded-xl shadow-md p-4 mb-4 hover:shadow-lg transition">
      <div class="w-14 h-14 flex items-center justify-center rounded-full bg-yellow-100 border-2 border-yellow-400 mr-4">
        <img src="https://cdn-icons-png.flaticon.com/512/893/893257.png" 
             alt="Icono aviso"
             class="w-7 h-7 opacity-80">
      </div>
      <div class="flex-1">
        <h2 class="text-lg font-semibold text-gray-800 mb-1">
          Aviso importante
        </h2>
        <p class="text-sm text-gray-600 leading-snug">
          Se actualizaron los términos y condiciones del servicio. Por favor, revísalos para continuar usando LABU.
        </p>
        <p class="text-xs text-gray-400 mt-2">Hace 3 días</p>
      </div>
    </div>

  </main>

  <?php include 'footer.php'; ?>

</body>
</html>
