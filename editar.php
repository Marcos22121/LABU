<?php
session_start();
// Configuración de la base de datos (AlwaysData)
$host = "mysql-labu.alwaysdata.net";
$usuario = "labu";
$contrasena = "bolas123!";
$bd = "labu_bd";

// Crear conexión usando MySQLi
$conn = new mysqli($host, $usuario, $contrasena, $bd, 3306);

// Verificar conexión
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}

// Forzar uso de SSL (AlwaysData usa SSL obligatorio)
$conn->ssl_set(NULL, NULL, NULL, NULL, NULL);

// Establecer el juego de caracteres a utf8mb4
if (!$conn->set_charset("utf8mb4")) {
  die("Error configurando utf8mb4: " . $conn->error);
}

if (!isset($_SESSION['id_usuario'])) {
  die("Debes iniciar sesión.");
}
$id_usuario = $_SESSION['id_usuario'];

// --- POST: actualizar ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre    = trim($_POST["nombre"]);
  $apellido  = trim($_POST["apellido"]);
  $telefono  = trim($_POST["telefono"]);
  $email     = trim($_POST["email"]);
  $bio       = trim($_POST["bio"]);

  // foto perfil
  $fotoPerfilPath = null;
  if (!empty($_FILES["foto_perfil"]["name"])) {
    $dir = "uploads/perfiles/";
    if (!is_dir($dir)) mkdir($dir, 0777, true);

    $fileName = uniqid("usr_")."_".basename($_FILES["foto_perfil"]["name"]);
    $target = $dir.$fileName;

    if (move_uploaded_file($_FILES["foto_perfil"]["tmp_name"], $target)) {
      $fotoPerfilPath = $target;
    }
  }

  // update usuarios
  if ($fotoPerfilPath) {
    $sql = "UPDATE usuarios SET nombre=?, apellido=?, telefono=?, email=?, bio=?, foto_perfil=? WHERE id_usuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nombre, $apellido, $telefono, $email, $bio, $fotoPerfilPath, $id_usuario);
  } else {
    $sql = "UPDATE usuarios SET nombre=?, apellido=?, telefono=?, email=?, bio=? WHERE id_usuario=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellido, $telefono, $email, $bio, $id_usuario);
  }
  $stmt->execute();
  $stmt->close();

  header("Location: perfil.php");
  exit();
}

// --- GET: obtener datos ---
$sql = "SELECT * FROM usuarios WHERE id_usuario=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
   <?php include 'header.php'; ?>

  

  <form action="editar.php" method="POST" enctype="multipart/form-data"
    class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md space-y-4">
  <h2 class="text-xl font-bold text-gray-800">Editar Perfil</h2>

  <div>
    <label>Nombre</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required class="w-full px-3 py-2 border rounded">
  </div>

  <div>
    <label>Apellido</label>
    <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required class="w-full px-3 py-2 border rounded">
  </div>

  <div>
    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required class="w-full px-3 py-2 border rounded">
  </div>

  <div>
    <label>Teléfono</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" class="w-full px-3 py-2 border rounded">
  </div>

  <div>
    <label>Bio</label>
    <textarea name="bio" class="w-full px-3 py-2 border rounded"><?= htmlspecialchars($usuario['bio']) ?></textarea>
  </div>

  <div>
    <label>Foto de perfil</label>
    <input type="file" name="foto_perfil" accept="image/*" class="w-full">
    <?php if (!empty($usuario['foto_perfil'])): ?>
    <img src="<?= htmlspecialchars($usuario['foto_perfil']) ?>" class="w-20 h-20 rounded-full mt-2 object-cover">
    <?php endif; ?>
  </div>

  <button type="submit" class="w-full py-2 bg-blue-600 text-white font-bold rounded-lg">Guardar Cambios</button>
  </form>
  <?php include 'footer.php'; ?>

</body>

</html>