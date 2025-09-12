<?php
// Incluir el archivo de conexión a la base de datos
include 'db_connect.php';

// Verificar si se ha enviado una acción (login o register)
if (!isset($_POST['action'])) {
    die("Acción no especificada.");
}

$action = $_POST['action'];

if ($action == 'register') {
    // Lógica para el registro
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $contrasena = $_POST['password'];
    $id_localidad = $_POST['id_localidad'];
    $es_trabajador = isset($_POST['es_trabajador']) ? 1 : 0;
    
    // Validar y encriptar la contraseña
    if (strlen($contrasena) < 6) {
        die("La contraseña debe tener al menos 6 caracteres.");
    }
    $contrasena_encriptada = encriptar_contrasena($contrasena);

    // Preparar y ejecutar la consulta de inserción
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, email, contraseña, id_localidad, es_trabajador) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $nombre, $apellido, $email, $contrasena_encriptada, $id_localidad, $es_trabajador);

    if ($stmt->execute()) {
        $id_usuario = $stmt->insert_id;
        if ($es_trabajador) {
            // Si el usuario es trabajador, crear un registro en la tabla 'trabajadores'
            $stmt_trabajador = $conn->prepare("INSERT INTO trabajadores (id_usuario, plan_suscripcion, activo) VALUES (?, ?, ?)");
            $plan_suscripcion = 'free';
            $activo = 1;
            $stmt_trabajador->bind_param("isi", $id_usuario, $plan_suscripcion, $activo);
            if (!$stmt_trabajador->execute()) {
                die("Error al registrar trabajador: " . $stmt_trabajador->error);
            }
            $stmt_trabajador->close();
        }
        echo "Registro exitoso. ¡Bienvenido a Labu!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    
} else if ($action == 'login') {
    // Lógica para el login
    $email = $_POST['email'];
    $contrasena = $_POST['password'];

    // Buscar el usuario por email
    $stmt = $conn->prepare("SELECT id_usuario, contrasena FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id_usuario, $contrasena_hash);
        $stmt->fetch();

        // Verificar la contraseña encriptada
        if (verificar_contrasena($contrasena, $contrasena_hash)) {
            // Contraseña correcta, iniciar sesión
            // Aquí podrías iniciar una sesión PHP con session_start() y guardar datos del usuario
            echo "Inicio de sesión exitoso. ¡Bienvenido de nuevo!";
            
            // Redireccionar al usuario a otra página
            // header("Location: dashboard.php");
            // exit();
        } else {
            echo "Email o contraseña incorrectos.";
        }
    } else {
        echo "Email o contraseña incorrectos.";
    }

    $stmt->close();
}

$conn->close();
?>
