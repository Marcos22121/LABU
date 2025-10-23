<?php
include 'Controlador/db_connect.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: registro.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT c.id_conversacion, c.ultimo_mensaje, 
               u.id_usuario AS otro_id, u.nombre, u.apellido, u.foto_perfil,
               m.contenido, m.tipo
        FROM conversaciones c
        JOIN participantes_conversacion p ON c.id_conversacion = p.id_conversacion
        JOIN participantes_conversacion p2 ON c.id_conversacion = p2.id_conversacion AND p2.id_usuario != p.id_usuario
        JOIN usuarios u ON u.id_usuario = p2.id_usuario
        LEFT JOIN mensajes m ON m.id_mensaje = (
            SELECT id_mensaje FROM mensajes 
            WHERE id_conversacion = c.id_conversacion 
            ORDER BY fecha_envio DESC LIMIT 1
        )
        WHERE p.id_usuario = ?
        ORDER BY c.ultimo_mensaje DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$convs = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mensajes</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<?php include 'header.php'; ?>

<div class="max-w-2xl mx-auto p-4">
  <h2 class="text-xl font-bold text-gray-800 mb-4">Mensajes</h2>
  <?php while ($c = $convs->fetch_assoc()): ?>
    <a href="mensaje.php?id=<?php echo $c['otro_id']; ?>" class="block bg-white p-4 rounded-lg shadow-sm mb-3 hover:bg-gray-50">
      <div class="flex items-center gap-3">
        <img src="<?php echo $c['foto_perfil'] ?: 'img/default-profile.png'; ?>" 
             class="w-12 h-12 rounded-full object-cover">
        <div class="flex-1">
          <h3 class="text-sm font-semibold text-gray-800">
            <?php echo htmlspecialchars($c['nombre'] . " " . $c['apellido']); ?>
          </h3>
          <p class="text-xs text-gray-600 truncate">
            <?php 
              if ($c['tipo'] == 'imagen') echo "📷 Imagen";
              elseif ($c['tipo'] == 'archivo') echo "📎 Archivo";
              else echo htmlspecialchars($c['contenido']);
            ?>
          </p>
        </div>
        <span class="text-xs text-gray-400"><?php echo date('d/m H:i', strtotime($c['ultimo_mensaje'])); ?></span>
      </div>
    </a>
  <?php endwhile; ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
