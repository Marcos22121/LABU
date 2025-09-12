<?php
include 'db_connect.php'; // ajustá el path según tu estructura

if (!isset($_POST['action'])) {
    die("Acción no especificada.");
}

$action = $_POST['action'];

// Funciones de seguridad
function encriptar_contrasena($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verificar_contrasena($password, $hash) {
    return password_verify($password, $hash);
}

if ($action == 'register') {
    // Campos del form
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $contrasena = $_POST['password'];
    $id_provincia = $_POST['id_provincia'];
    $id_localidad = $_POST['id_localidad'];
    
    // Validación de contraseña
    if (strlen($contrasena) < 6) {
        die("La contraseña debe tener al menos 6 caracteres.");
    }
    $contrasena_encriptada = encriptar_contrasena($contrasena);

    // Insertar usuario
    $stmt = $conn->prepare("INSERT INTO usuarios 
        (nombre, apellido, dni, fecha_nacimiento, genero, telefono, email, contraseña, id_localidad, fecha_creacion) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssssssi", 
        $nombre, $apellido, $dni, $fecha_nacimiento, $genero, $telefono, 
        $email, $contrasena_encriptada, $id_localidad
    );

    if ($stmt->execute()) {
        echo "Registro exitoso. ¡Bienvenido a Labu!";
        // podés hacer un redirect acá si querés
        // header("Location: ../Vista/dashboard.php");
        // exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();

} else if ($action == 'login') {
    $email = $_POST['email'];
    $contrasena = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_usuario, contraseña FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id_usuario, $contrasena_hash);
        $stmt->fetch();

        if (verificar_contrasena($contrasena, $contrasena_hash)) {
            session_start();
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['email'] = $email;

            echo "Inicio de sesión exitoso. ¡Bienvenido de nuevo!";
            // header("Location: ../Vista/dashboard.php");
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
