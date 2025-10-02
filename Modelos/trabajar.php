<?php
session_start();
include '../Controlador/db_connect.php';

// verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    die("Error: no estás logueado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_especialidad = $_POST['id_especialidad'];
    $descripcion = $_POST['descripcion'];
    $plan = $_POST['plan'];

    // Fechas de plan (por ahora dummy, después lo enganchás con MercadoPago)
    $fecha_inicio = date('Y-m-d');
    $fecha_fin = ($plan === 'Free') ? NULL : date('Y-m-d', strtotime('+1 month'));


    // validar que el usuario existe en la tabla usuarios
$check = $conn->prepare("SELECT id_usuario, foto_perfil FROM usuarios WHERE id_usuario = ?");
$check->bind_param("i", $id_usuario);
$check->execute();
$res = $check->get_result();

if ($res->num_rows === 0) {
    die("Error: el usuario no existe en la tabla usuarios");
}

$user = $res->fetch_assoc();

// validar que tenga foto de perfil
if (empty($user['foto_perfil'])) {
    header("Location: ../trabajar.php?error=Debes subir una foto de perfil antes de registrarte como trabajador");
    exit;
}


    // Insertar en trabajadores
    $sql = "INSERT INTO trabajadores 
        (id_usuario, id_especialidad, descripcion_trabajo, plan_suscripcion, activo, fecha_inicio_plan, fecha_fin_plan, cantidad_reviews, puntaje_promedio) 
        VALUES (?, ?, ?, ?, 1, ?, ?, 0, 0.00)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissss", $id_usuario, $id_especialidad, $descripcion, $plan, $fecha_inicio, $fecha_fin);

    if ($stmt->execute()) {
        // actualizar usuario como trabajador
        $update = $conn->prepare("UPDATE usuarios SET es_trabajador = 1 WHERE id_usuario = ?");
        $update->bind_param("i", $id_usuario);
        $update->execute();

        header("Location: ../trabajar.php?success=1");
        exit;
    } else {
        header("Location: ../trabajar.php?error=" . urlencode($stmt->error));
        exit;
    }
}
?>
