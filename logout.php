<?php
session_start();
session_unset(); // Limpia todas las variables de sesión
session_destroy(); // Destruye la sesión

header("Location: registro.php"); // Redirige al login o registro
exit;
?>
