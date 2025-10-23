<?php
include 'Controlador/db_connect.php';
session_start();

if (!isset($_SESSION['id_usuario'])) exit;

$id_usuario = $_SESSION['id_usuario'];
$id_conversacion = intval($_GET['id_conversacion']);

$stmt = $conn->prepare("SELECT * FROM mensajes WHERE id_conversacion = ? ORDER BY fecha_envio ASC");
$stmt->bind_param("i", $id_conversacion);
$stmt->execute();
$res = $stmt->get_result();

while ($m = $res->fetch_assoc()):
?>
  <div class="mb-3 <?php echo $m['id_remitente'] == $id_usuario ? 'text-right' : ''; ?>">
    <div class="<?php echo $m['id_remitente'] == $id_usuario ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'; ?> 
                inline-block px-4 py-2 rounded-lg max-w-[80%] text-sm">
      <?php if ($m['tipo'] == 'imagen'): ?>
        <img src="<?php echo htmlspecialchars($m['url_archivo']); ?>" alt="imagen" class="rounded-lg mb-1 max-w-xs max-h-wscreen" style="max-width: 100%;><br>
      <?php elseif ($m['tipo'] == 'archivo'): ?>
        <a href="<?php echo htmlspecialchars($m['url_archivo']); ?>" target="_blank" class="underline">📎 Descargar archivo</a><br>
      <?php endif; ?>
      <?php echo nl2br(htmlspecialchars($m['contenido'])); ?>
    </div>
    <div class="text-xs text-gray-500 mt-1"><?php echo $m['fecha_envio']; ?></div>
  </div>
<?php endwhile; ?>
