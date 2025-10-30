<?php
require_once 'Controlador/db_connect.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
  $_SESSION['id_usuario'] = 5; // ⚠️ Solo para pruebas locales (eliminar en producción)
}

$id_usuario = $_SESSION['id_usuario'];

// 🔁 Si viene por AJAX → devolver JSON
if (isset($_GET['ajax'])) {
  header('Content-Type: application/json');

  $sql = "
      SELECT 
          m.id_mensaje,
          m.id_remitente,
          m.contenido,
          m.fecha_envio,
          u.nombre,
          u.apellido,
          u.foto_perfil
      FROM mensajes m
      INNER JOIN conversaciones c ON c.id_conversacion = m.id_conversacion
      INNER JOIN participantes_conversacion pc ON pc.id_conversacion = c.id_conversacion
      INNER JOIN usuarios u ON u.id_usuario = m.id_remitente
      WHERE pc.id_usuario = ? 
        AND m.id_remitente != ? 
        AND m.leido = 0
      ORDER BY m.fecha_envio DESC
  ";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ii", $id_usuario, $id_usuario);
  $stmt->execute();
  $result = $stmt->get_result();

  $mensajes = [];
  while ($row = $result->fetch_assoc()) {
      $mensajes[] = [
          'id_mensaje' => $row['id_mensaje'],
          'remitente' => $row['nombre'] . ' ' . $row['apellido'],
          'foto_perfil' => $row['foto_perfil'] ? $row['foto_perfil'] : 'img/default.png',
          'contenido' => $row['contenido'],
          'fecha' => $row['fecha_envio']
      ];
  }

  if (!empty($mensajes)) {
      $ids = array_column($mensajes, 'id_mensaje');
      $ids_str = implode(',', $ids);
      $conn->query("UPDATE mensajes SET leido = 1 WHERE id_mensaje IN ($ids_str)");
  }

  echo json_encode($mensajes);
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notificaciones - LABU</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="favicon.png">
  <style>
    /* Animación sutil de entrada */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
      animation: fadeIn 0.4s ease-out;
    }
  </style>
</head>
<body class="bg-gradient-to-b from-gray-50 to-white min-h-screen flex flex-col">

  <?php include 'header.php'; ?>

  <main class="flex-grow px-4 sm:px-6 lg:px-8 py-8 max-w-4xl mx-auto w-full">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6 border-b border-gray-200 pb-3">
      🔔 Notificaciones
    </h1>

    <div id="notificacionesContainer" class="flex flex-col space-y-4">
      <p class="text-gray-500 text-center py-8" id="noNotif">
        No tienes notificaciones nuevas.
      </p>
    </div>
  </main>

  <?php include 'footer.php'; ?>

  <script>
  document.addEventListener("DOMContentLoaded", () => {
    const contenedor = document.getElementById("notificacionesContainer");
    const noNotif = document.getElementById("noNotif");

    function formatearFecha(fechaStr) {
      const fecha = new Date(fechaStr);
      return fecha.toLocaleString('es-AR', {
        day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit'
      });
    }

    function mostrarNotificacion(nombre, mensaje, foto) {
      if (Notification.permission === "granted") {
        new Notification(nombre, { body: mensaje, icon: foto });
      } else if (Notification.permission !== "denied") {
        Notification.requestPermission();
      }
    }

    async function cargarNotificaciones() {
      try {
        const response = await fetch("notificaciones.php?ajax=1");
        const mensajes = await response.json();

        if (mensajes.length > 0) {
          noNotif.style.display = "none";

          mensajes.forEach(m => {
            const card = document.createElement("div");
            card.className = `
              flex flex-col sm:flex-row items-start sm:items-center
              bg-white border border-gray-200 rounded-2xl shadow-sm p-4 sm:p-5
              hover:shadow-md transition duration-200 fade-in
            `;

            card.innerHTML = `
              <img src="${m.foto_perfil}" 
                   alt="Foto de perfil"
                   class="w-14 h-14 rounded-full object-cover border-2 border-blue-500 sm:mr-4 mb-3 sm:mb-0">
              <div class="flex-1 w-full">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-1">
                  Nuevo mensaje de ${m.remitente}
                </h2>
                <p class="text-sm sm:text-base text-gray-600 leading-snug line-clamp-2">
                  ${m.contenido}
                </p>
                <p class="text-xs text-gray-400 mt-2">${formatearFecha(m.fecha)}</p>
              </div>
            `;

            contenedor.prepend(card);
            mostrarNotificacion(m.remitente, m.contenido, m.foto_perfil);
          });
        }
      } catch (error) {
        console.error("Error al cargar notificaciones:", error);
      }
    }

    if (Notification.permission !== "granted") {
      Notification.requestPermission();
    }

    setInterval(cargarNotificaciones, 5000);
  });

  new Notification(nombre, {
  body: mensaje,
  icon: foto
}).onclick = function () {
  window.open("chat.php?usuario=" + encodeURIComponent(nombre), "_blank");
};

  </script>
</body>
</html>
