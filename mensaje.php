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
    // Ya existe conversación → simplemente abrila
    $id_conversacion = $conversacion['id_conversacion'];
} else {
    // No existe conversación → verificar que el receptor sea trabajador
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

    // Crear nueva conversación
    $conn->query("INSERT INTO conversaciones (fecha_creacion) VALUES (NOW())");
    $id_conversacion = $conn->insert_id;

    $stmt = $conn->prepare("INSERT INTO participantes_conversacion (id_conversacion, id_usuario) VALUES (?, ?), (?, ?)");
    $stmt->bind_param("iiii", $id_conversacion, $id_usuario_logueado, $id_conversacion, $id_receptor);
    $stmt->execute();
    $stmt->close();
}

// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenido = trim($_POST['mensaje']);
    $tipo = 'texto';
    $url_archivo = null;

    // Manejar archivo adjunto
    if (!empty($_FILES['archivo']['name'])) {
        $dir = "uploads/mensajes/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $fileName = uniqid("msg_") . "_" . basename($_FILES["archivo"]["name"]);
        $target = $dir . $fileName;

        if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $target)) {
            $url_archivo = $target;

            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $tipo = in_array($ext, ['jpg','jpeg','png','webp','gif','jfif']) ? 'imagen' : 'archivo';
        }
    }

    $stmt = $conn->prepare("INSERT INTO mensajes (id_conversacion, id_remitente, contenido, tipo, url_archivo) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $id_conversacion, $id_usuario_logueado, $contenido, $tipo, $url_archivo);
    $stmt->execute();
    $stmt->close();

    // Actualizar último mensaje
    $conn->query("UPDATE conversaciones SET ultimo_mensaje = NOW() WHERE id_conversacion = $id_conversacion");

    header("Location: mensaje.php?id=$id_receptor");
    exit();
}

// Traer mensajes
$stmt = $conn->prepare("SELECT * FROM mensajes WHERE id_conversacion = ? ORDER BY fecha_envio ASC");
$stmt->bind_param("i", $id_conversacion);
$stmt->execute();
$mensajes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<?php include 'header.php'; ?>

<div class="flex-1 overflow-y-auto p-4 max-w-2xl mx-auto w-full">
  <?php foreach ($mensajes as $m): ?>
    <div class="mb-3 <?php echo $m['id_remitente'] == $id_usuario_logueado ? 'text-right' : ''; ?>">
      <div class="<?php echo $m['id_remitente'] == $id_usuario_logueado ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'; ?> 
                  inline-block px-4 py-2 rounded-lg max-w-[80%] text-sm">
        <?php if ($m['tipo'] == 'imagen'): ?>
          <img src="<?php echo htmlspecialchars($m['url_archivo']); ?>" alt="imagen" class="rounded-lg mb-1 max-w-xs">
        <?php elseif ($m['tipo'] == 'archivo'): ?>
          <a href="<?php echo htmlspecialchars($m['url_archivo']); ?>" target="_blank" class="underline">
            Descargar archivo
          </a><br>
        <?php endif; ?>
        <?php echo nl2br(htmlspecialchars($m['contenido'])); ?>
      </div>
      <div class="text-xs text-gray-500 mt-1"><?php echo $m['fecha_envio']; ?></div>
    </div>
  <?php endforeach; ?>
</div>

<form method="POST" enctype="multipart/form-data" class="bg-white p-4 border-t flex gap-2 sticky bottom-0">
  <input type="text" name="mensaje" placeholder="Escribí un mensaje..." required 
         class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
  <input type="file" name="archivo" class="hidden" id="fileInput">
  <label for="fileInput" class="cursor-pointer bg-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-300">📎</label>
  <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Enviar</button>
</form>

<?php include 'footer.php'; ?>

</body>
</html>
