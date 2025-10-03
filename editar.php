<?php
session_start();
$host = "localhost";
$db   = "labu";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
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
    $sql = "UPDATE usuarios 
            SET nombre=:nombre, apellido=:apellido, telefono=:telefono, email=:email, bio=:bio";
    if ($fotoPerfilPath) $sql .= ", foto_perfil=:foto_perfil";
    $sql .= " WHERE id_usuario=:id_usuario";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":apellido", $apellido);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":bio", $bio);
    $stmt->bindParam(":id_usuario", $id_usuario);
    if ($fotoPerfilPath) $stmt->bindParam(":foto_perfil", $fotoPerfilPath);
    $stmt->execute();

    header("Location: perfil.php");
    exit();
}

// --- GET: obtener datos ---
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario=?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

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
      <?php if ($usuario['foto_perfil']): ?>
        <img src="<?= $usuario['foto_perfil'] ?>" class="w-20 h-20 rounded-full mt-2 object-cover">
      <?php endif; ?>
    </div>

    <button type="submit" class="w-full py-2 bg-blue-600 text-white font-bold rounded-lg">Guardar Cambios</button>
  </form>

</body>
</html>