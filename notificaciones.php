<?php
require_once 'Controlador/db_connect.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
  $_SESSION['id_usuario'] = 5; // ⚠️ Solo para pruebas locales (quitar en producción)
}

$id_usuario = $_SESSION['id_usuario'];

// 🔍 Buscar si el usuario logueado es un trabajador
$sqlTrabajador = "SELECT id_trabajador FROM trabajadores WHERE id_usuario = ? LIMIT 1";
$stmtTrab = $conn->prepare($sqlTrabajador);
$stmtTrab->bind_param("i", $id_usuario);
$stmtTrab->execute();
$resultTrab = $stmtTrab->get_result();
$id_trabajador = null;

if ($row = $resultTrab->fetch_assoc()) {
  $id_trabajador = $row['id_trabajador'];
}

// 🔁 Si viene por AJAX → devolver JSON
if (isset($_GET['ajax'])) {
  header('Content-Type: application/json');

  $notificaciones = [];

  /** 🔹 1. Notificaciones de MENSAJES no leídos **/
  $sqlMensajes = "
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

  $stmt = $conn->prepare($sqlMensajes);
  $stmt->bind_param("ii", $id_usuario, $id_usuario);
  $stmt->execute();
  $resultMensajes = $stmt->get_result();

  while ($row = $resultMensajes->fetch_assoc()) {
    $notificaciones[] = [
      'tipo' => 'mensaje',
      'id' => $row['id_mensaje'],
      'remitente' => $row['nombre'] . ' ' . $row['apellido'],
      'foto' => $row['foto_perfil'] ?: 'img/default.png',
      'contenido' => $row['contenido'],
      'fecha' => $row['fecha_envio']
    ];
  }

  // ✅ no marcamos como leídos automáticamente


  /** 🔹 2. Notificaciones de RESEÑAS nuevas (solo si el usuario es trabajador) **/
  if ($id_trabajador !== null) {
    $sqlReseñas = "
        SELECT 
            r.id_review,
            r.calificacion,
            r.comentario,
            r.fecha,
            r.id_usuario,
            u.nombre,
            u.apellido,
            u.foto_perfil
        FROM reseñas r
        INNER JOIN usuarios u ON u.id_usuario = r.id_usuario
        WHERE r.id_trabajador = ? AND r.recibida = 0
        ORDER BY r.fecha DESC
    ";

    $stmt2 = $conn->prepare($sqlReseñas);
    $stmt2->bind_param("i", $id_trabajador);
    $stmt2->execute();
    $resultReseñas = $stmt2->get_result();

    while ($row = $resultReseñas->fetch_assoc()) {
      $notificaciones[] = [
        'tipo' => 'reseña',
        'id' => $row['id_review'],
        'remitente' => $row['nombre'] . ' ' . $row['apellido'],
        'foto' => $row['foto_perfil'] ?: 'img/default.png',
        'contenido' => $row['comentario'] ?: '(Sin comentario)',
        'calificacion' => $row['calificacion'],
        'fecha' => $row['fecha']
      ];
    }
  }

  echo json_encode($notificaciones);
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
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeIn 0.4s ease-out; }
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

    function mostrarNotificacion(titulo, cuerpo, icono) {
      if (Notification.permission === "granted") {
        const n = new Notification(titulo, { body: cuerpo, icon: icono });
        n.onclick = () => window.focus();
      } else if (Notification.permission !== "denied") {
        Notification.requestPermission();
      }
    }

    function generarEstrellas(num) {
      return '★'.repeat(num) + '☆'.repeat(5 - num);
    }

    async function cargarNotificaciones() {
      try {
        const response = await fetch("notificaciones.php?ajax=1");
        const data = await response.json();

        contenedor.innerHTML = "";
        if (data.length > 0) {
          noNotif.style.display = "none";

          data.forEach(n => {
            const card = document.createElement("div");
            card.className = `
              flex flex-col sm:flex-row items-start sm:items-center
              bg-white border border-gray-200 rounded-2xl shadow-sm p-4 sm:p-5
              hover:shadow-md transition duration-200 fade-in
            `;

            if (n.tipo === 'mensaje') {
              card.innerHTML = `
                <img src="${n.foto}" 
                     alt="Foto de perfil"
                     class="w-14 h-14 rounded-full object-cover border-2 border-blue-500 sm:mr-4 mb-3 sm:mb-0">
                <div class="flex-1 w-full">
                  <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-1">
                    Nuevo mensaje de ${n.remitente}
                  </h2>
                  <p class="text-sm sm:text-base text-gray-600 leading-snug line-clamp-2">
                    ${n.contenido}
                  </p>
                  <p class="text-xs text-gray-400 mt-2">${formatearFecha(n.fecha)}</p>
                </div>
              `;
              mostrarNotificacion("Nuevo mensaje de " + n.remitente, n.contenido, n.foto);

            } else if (n.tipo === 'reseña') {
              card.innerHTML = `
                <div class="flex items-center gap-3 mb-3 sm:mb-0">
                  <div class="flex items-center justify-center w-14 h-14 rounded-full bg-yellow-100 border border-yellow-400">
                    ⭐
                  </div>
                  <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-800 mb-1">
                      Te han escrito una reseña
                    </h2>
                    <p class="text-yellow-500 font-semibold text-sm">
                      ${generarEstrellas(n.calificacion)}
                    </p>
                  </div>
                </div>
                <div class="flex-1 w-full mt-2 sm:mt-0">
                  <p class="text-sm sm:text-base text-gray-700 leading-snug italic">
                    “${n.contenido}”
                  </p>
                  <p class="text-xs text-gray-400 mt-2">${formatearFecha(n.fecha)}</p>
                </div>
              `;
              mostrarNotificacion("Te han escrito una reseña", n.contenido, "img/star.png");
            }

            contenedor.prepend(card);
          });
        } else {
          noNotif.style.display = "block";
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
  </script>
</body>
</html>
