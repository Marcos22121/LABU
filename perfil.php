<?php
include 'Controlador/db_connect.php';
session_start();

// --- LOGIN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sqlLogin = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sqlLogin);
    if (!$stmt) die("Error en la consulta: " . $conn->error);

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        if ($usuario['contraseña'] === $password) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            header("Location: perfil.php?id=" . $usuario['id_usuario']);
            exit;
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
}

$id_usuario = $_SESSION['id_usuario'] ?? null;

// --- LOGIN FORM ---
if (!$id_usuario):
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-yellow-400 via-amber-300 to-orange-400">
    <form method="POST" class="bg-white/90 backdrop-blur p-8 rounded-2xl shadow-xl w-11/12 max-w-sm">
        <h2 class="text-3xl font-bold text-gray-800 mb-4 text-center">Bienvenido a <span class="text-yellow-500">LABU</span></h2>
        <?php if (isset($error)): ?>
            <p class="text-red-600 text-center mb-3 font-medium"><?php echo $error; ?></p>
        <?php endif; ?>
        <input type="email" name="email" placeholder="Correo electrónico" required
            class="w-full border border-gray-300 rounded-lg p-3 mb-3 focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <input type="password" name="password" placeholder="Contraseña" required
            class="w-full border border-gray-300 rounded-lg p-3 mb-4 focus:outline-none focus:ring-2 focus:ring-yellow-400">
        <button type="submit" name="login"
            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 rounded-lg transition">
            Entrar
        </button>
    </form>
</body>
</html>
<?php
exit;
endif;

// --- PERFIL ---
$id_trabajador = $_GET['id'] ?? null;
if (!$id_trabajador) {
    echo "<p style='color:red;'>❌ No se especificó ningún perfil.</p>";
    exit;
}

// --- INSERTAR RESEÑA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario'], $_POST['calificacion'])) {
    $comentario = trim($_POST['comentario']);
    $calificacion = intval($_POST['calificacion']);
    if ($calificacion >= 1 && $calificacion <= 5 && !empty($comentario)) {
        $sql = "INSERT INTO reseñas (id_trabajador, id_usuario, calificacion, comentario, fecha)
                VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $id_trabajador, $id_usuario, $calificacion, $comentario);
        $stmt->execute();
    }
}

// --- DATOS DEL PERFIL ---
$sqlPerfil = "SELECT * FROM usuarios WHERE id_usuario = ?";
$stmtPerfil = $conn->prepare($sqlPerfil);
$stmtPerfil->bind_param("i", $id_trabajador);
$stmtPerfil->execute();
$resultadoPerfil = $stmtPerfil->get_result();

if ($resultadoPerfil->num_rows === 0) {
    echo "<p style='color:red;'>❌ No se encontró el perfil solicitado.</p>";
    exit;
}
$perfil = $resultadoPerfil->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($perfil['nombre']); ?> - Perfil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-yellow-50 via-orange-50 to-yellow-100 font-[Poppins] text-gray-800">

    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-10">
        <div class="max-w-5xl mx-auto flex justify-between items-center px-5 py-3">
            <h1 class="text-2xl font-bold text-yellow-500">LABU</h1>
            <a href="logout.php" class="text-sm font-semibold text-gray-600 hover:text-red-500 transition">Cerrar sesión</a>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="max-w-3xl mx-auto mt-8 mb-16 px-4">
        <!-- Perfil -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900"><?php echo htmlspecialchars($perfil['nombre'] . ' ' . $perfil['apellido']); ?></h2>
                <p class="text-gray-600 mt-1"><strong>Email:</strong> <?php echo htmlspecialchars($perfil['email']); ?></p>
                <p class="text-gray-600"><strong>Teléfono:</strong> <?php echo htmlspecialchars($perfil['telefono']); ?></p>
            </div>
            <?php if (!empty($perfil['foto_perfil'])): ?>
                <img src="<?php echo htmlspecialchars($perfil['foto_perfil']); ?>" alt="Foto de perfil"
                     class="w-24 h-24 rounded-full object-cover mt-4 sm:mt-0 border-4 border-yellow-400 shadow-md">
            <?php else: ?>
                <div class="w-24 h-24 rounded-full mt-4 sm:mt-0 bg-gray-200 flex items-center justify-center text-4xl font-bold text-gray-400">
                    <?php echo strtoupper($perfil['nombre'][0]); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Formulario de reseña -->
        <section class="bg-white rounded-2xl shadow-md p-6 mb-6">
            <h3 class="text-xl font-semibold mb-4 text-yellow-600">Dejar una reseña ⭐</h3>
            <form method="POST" id="formReseña">
                <div id="estrellas" class="flex gap-2 text-3xl mb-3 text-gray-300 cursor-pointer"></div>
                <input type="hidden" name="calificacion" id="calificacion">
                <textarea name="comentario" rows="4" placeholder="Escribí tu opinión..." required
                          class="w-full border border-gray-300 rounded-lg p-3 resize-none focus:ring-2 focus:ring-yellow-400 mb-3"></textarea>
                <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 rounded-lg transition">
                    Enviar reseña
                </button>
            </form>
        </section>

        <!-- Listado de reseñas -->
        <section>
            <h3 class="text-xl font-semibold text-gray-800 mb-3">Reseñas recientes</h3>
            <div class="space-y-3">
                <?php
                $sql = "SELECT r.*, u.nombre AS usuario 
                        FROM reseñas r 
                        JOIN usuarios u ON r.id_usuario = u.id_usuario
                        WHERE r.id_trabajador = ? 
                        ORDER BY r.fecha DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id_trabajador);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($r = $result->fetch_assoc()) {
                        echo "<div class='bg-white p-4 rounded-xl shadow-sm border-l-4 border-yellow-400'>";
                        echo "<div class='flex justify-between items-center'>";
                        echo "<strong class='text-gray-800'>" . htmlspecialchars($r['usuario']) . "</strong>";
                        echo "<span class='text-yellow-400 text-lg'>" . str_repeat('★', $r['calificacion']) . "</span>";
                        echo "</div>";
                        echo "<p class='text-gray-700 mt-1 italic'>" . htmlspecialchars($r['comentario']) . "</p>";
                        echo "<small class='text-gray-500 text-xs'>" . $r['fecha'] . "</small>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='text-gray-500 italic text-center'>Todavía no hay reseñas para este usuario.</p>";
                }
                ?>
            </div>
        </section>
    </main>

<script>
// 🌟 Estrellas interactivas Tailwind
const estrellasCont = document.getElementById('estrellas');
const inputCalificacion = document.getElementById('calificacion');
for (let i = 1; i <= 5; i++) {
    const span = document.createElement('span');
    span.textContent = '★';
    span.dataset.value = i;
    span.addEventListener('click', () => {
        inputCalificacion.value = i;
        document.querySelectorAll('#estrellas span').forEach((s, idx) => {
            s.classList.toggle('text-yellow-400', idx < i);
            s.classList.toggle('text-gray-300', idx >= i);
            s.classList.toggle('scale-110', idx === i - 1);
        });
    });
    estrellasCont.appendChild(span);
}
</script>
</body>
</html>
