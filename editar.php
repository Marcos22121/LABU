<?php
// editar.php
// Conexión a la base de datos
$host = "localhost";
$db   = "Labu";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Verificamos que vengan datos por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id          = $_POST["id"] ?? null;
    $nombre      = trim($_POST["nombre"] ?? "");
    $email       = trim($_POST["email"] ?? "");
    $telefono    = trim($_POST["telefono"] ?? "");
    $fechaInicio = $_POST["fechaInicio"] ?? null;
    $puesto      = trim($_POST["puesto"] ?? "");
    $departamento= $_POST["departamento"] ?? "";
    $direccion   = trim($_POST["direccion"] ?? "");
    $rol         = $_POST["rol"] ?? "Empleado";
    $estado      = $_POST["estado"] ?? "Activo";
    $salario     = $_POST["salario"] !== "" ? floatval($_POST["salario"]) : null;
    $legajo      = trim($_POST["legajo"] ?? "");

    if (!$id || !$nombre || !$email || !$puesto) {
        die("Faltan datos obligatorios");
    }

    // Manejo de la imagen (si se sube)
    $avatarPath = null;
    if (!empty($_FILES["avatar"]["name"])) {
        $dir = "uploads/";
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $fileName = uniqid("emp_") . "_" . basename($_FILES["avatar"]["name"]);
        $target   = $dir . $fileName;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target)) {
            $avatarPath = $target;
        }
    }

    // Armamos query dinámica (si hay avatar, lo actualizamos)
    $sql = "UPDATE empleados SET 
                nombre=:nombre, email=:email, telefono=:telefono, fecha_inicio=:fechaInicio,
                puesto=:puesto, departamento=:departamento, direccion=:direccion, 
                rol=:rol, estado=:estado, salario=:salario, legajo=:legajo";

    if ($avatarPath) {
        $sql .= ", avatar=:avatar";
    }

    $sql .= " WHERE id=:id";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":fechaInicio", $fechaInicio);
    $stmt->bindParam(":puesto", $puesto);
    $stmt->bindParam(":departamento", $departamento);
    $stmt->bindParam(":direccion", $direccion);
    $stmt->bindParam(":rol", $rol);
    $stmt->bindParam(":estado", $estado);
    $stmt->bindParam(":salario", $salario);
    $stmt->bindParam(":legajo", $legajo);
    $stmt->bindParam(":id", $id);

    if ($avatarPath) {
        $stmt->bindParam(":avatar", $avatarPath);
    }

    if ($stmt->execute()) {
        echo "Perfil actualizado correctamente.";
    } else {
        echo "Error al actualizar perfil.";
    }
} else {
    echo "Acceso inválido";
}
