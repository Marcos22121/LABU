<?php
include 'Controlador/db_connect.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: registro.php");
    exit();
}

$id_usuario_logueado = $_SESSION['id_usuario'];
$id_receptor = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$id_receptor || $id_receptor == $id_usuario_logueado) {
    die("Conversación inválida.");
}

// Buscar si ya existe conversación entre ambos
$sql = "SELECT c.id_conversacion 
        FROM conversaciones c
        JOIN participantes_conversacion p1 ON c.id_conversacion = p1.id_conversacion AND p1.id_usuario = ?
        JOIN participantes_conversacion p2 ON c.id_conversacion = p2.id_conversacion AND p2.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario_logueado, $id_receptor);
$stmt->execute();
$result = $stmt->get_result();
$conversacion = $result->fetch_assoc();
$stmt->close();

if ($conversacion) {
    $id_conversacion = $conversacion['id_conversacion'];
} else {
    // Verificar que el receptor sea trabajador
    $sql = "SELECT id_trabajador FROM trabajadores WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_receptor);
    $stmt->execute();
    $res = $stmt->get_result();
    $es_trabajador = $res->num_rows > 0;
    $stmt->close();

    if (!$es_trabajador) {
        die("Solo podés iniciar conversaciones con trabajadores.");
    }

    // Crear conversación nueva
    $conn->query("INSERT INTO conversaciones (fecha_creacion) VALUES (NOW())");
    $id_conversacion = $conn->insert_id;

    $stmt = $conn->prepare("INSERT INTO participantes_conversacion (id_conversacion, id_usuario) VALUES (?, ?), (?, ?)");
    $stmt->bind_param("iiii", $id_conversacion, $id_usuario_logueado, $id_conversacion, $id_receptor);
    $stmt->execute();
    $stmt->close();
}

// Traer mensajes iniciales
$stmt = $conn->prepare("SELECT * FROM mensajes WHERE id_conversacion = ? ORDER BY fecha_envio ASC");
$stmt->bind_param("i", $id_conversacion);
$stmt->execute();
$mensajes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .bolas {
      height: 500px;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<?php include 'header.php'; ?>

<!-- Contenedor de mensajes -->
<div id="chatContainer" class="flex-1 overflow-y-auto p-4 pb-32 max-w-2xl mx-auto w-full" style="scroll-behavior: smooth; max-width=100%;">
  <!-- Los mensajes se cargarán aquí vía AJAX -->
</div>

<!-- Preview del archivo -->
<div id="previewContainer" class="hidden fixed bottom-[138px] left-1/2 -translate-x-1/2 bg-white shadow-lg rounded-lg p-3 w-11/12 max-w-2xl border">
  <div id="previewContent" class="flex items-center gap-3 z-10">
    <img id="previewImage" src="" alt="" class="max-h-20 rounded hidden">
    <span id="previewFileName" class="text-gray-700 text-sm font-medium"></span>
    <button id="removePreview" class="ml-auto text-red-500 hover:text-red-700 text-sm">✕</button>
  </div>
</div>

<!-- Barra inferior del chat -->
<form id="formMensaje" enctype="multipart/form-data" 
      class="fixed bottom-[51px] left-0 w-full bg-white p-3 border-t flex gap-2 shadow-md" style="padding-bottom: 1.5rem;">

  <input type="text" name="mensaje" placeholder="Escribí un mensaje..." required 
         class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
  
  <input type="file" name="archivo" class="hidden" id="fileInput">
  
  <label for="fileInput" class="cursor-pointer bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">📎</label>
  
  <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
    Enviar
  </button>
</form>

<style>
  /* Para que el scroll del chat no se tape con el form */
  #chatContainer {
    scroll-behavior: smooth;
  }
</style>

<script>
$(document).ready(function() {
  const idConversacion = <?php echo $id_conversacion; ?>;
  const chatContainer = $('#chatContainer');

  // Baja automáticamente al último mensaje
  function scrollToBottom() {
    chatContainer.stop().animate({ 
      scrollTop: chatContainer[0].scrollHeight 
    }, 400);
  }

  // Cargar los mensajes (con AJAX)
  function cargarMensajes(scroll = false) {
    $.get('get_messages.php', { id_conversacion: idConversacion }, function(data) {
      chatContainer.html(data);
      if (scroll) scrollToBottom();
    });
  }

  // Primer carga → baja al final
  cargarMensajes(true);

  // Actualiza cada 3s pero solo baja si estás cerca del final
  setInterval(() => {
    const nearBottom = chatContainer.scrollTop() + chatContainer.height() >= chatContainer[0].scrollHeight - 100;
    cargarMensajes(nearBottom);
  }, 3000);

  // Enviar mensaje con AJAX
  $('#formMensaje').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('id_conversacion', idConversacion);

    $.ajax({
      url: 'send_message.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function() {
        $('#formMensaje')[0].reset();
        $('#previewContainer').addClass('hidden');
        $('#fileInput').val('');
        cargarMensajes(true); // vuelve a bajar al último mensaje
      }
    });
  });
});
</script>


<?php include 'footer.php'; ?>

</body>
</html>
