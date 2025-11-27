<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LABU</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 pb-20">

 <?php include 'header.php'; ?>

  <section class="relative h-[70vh] flex items-center justify-center">
    <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80" 
         alt="Trabajo" 
         class="absolute inset-0 w-full h-full object-cover">

    <div class="absolute inset-0 bg-blue-900 bg-opacity-50"></div>
    
    <div class="relative text-center text-white max-w-2xl px-6">
      <h1 class="text-3xl md:text-4xl font-bold mb-4">
        Encuentra tus mejores trabajos en nuestra app
      </h1>
      <p class="text-lg md:text-xl mb-6">
        Conecta con oportunidades únicas y haz crecer tu carrera.
      </p>
      <a href="buscar.php" 
         class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-full shadow-lg transition">
        Buscar trabajadores
      </a>
    </div>
  </section>
<?php
include 'Controlador/db_connect.php';
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: registro.php");
    exit();
}
$sql = "
    SELECT e.id_especialidad, e.nombre, e.descripcion, e.foto_url, COUNT(t.id_trabajador) AS cantidad
    FROM especialidades e
    LEFT JOIN trabajadores t ON e.id_especialidad = t.id_especialidad
    GROUP BY e.id_especialidad
    ORDER BY cantidad DESC
    LIMIT 5
";
$result = $conn->query($sql);

$especialidades = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $especialidades[] = $row;
    }
} else {
    $sql_random = "SELECT id_especialidad, nombre, descripcion, foto_url FROM especialidades ORDER BY RAND() LIMIT 5";
    $result_random = $conn->query($sql_random);

    if ($result_random && $result_random->num_rows > 0) {
        while ($row = $result_random->fetch_assoc()) {
            $especialidades[] = $row;
        }
    }
}
$conn->close();
?>

<section class="px-6 py-10">
  <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6">
    Trabajos más buscados
  </h2>

  <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    <?php foreach ($especialidades as $esp): ?>
      <a href="buscar.php?especialidad=<?= urlencode($esp['nombre']) ?>" 
   class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md group">
  <img src="<?php echo $esp['foto_url'] ? htmlspecialchars($esp['foto_url']) : 'img/trabajo.webp'; ?>" 
       alt="<?php echo htmlspecialchars($esp['nombre']); ?>" 
       class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all duration-300">
  <div class="absolute inset-0 bg-black bg-opacity-40 group-hover:bg-opacity-50 transition"></div>
  <span class="relative text-white text-lg font-semibold text-center px-2">
    <?php echo htmlspecialchars($esp['nombre']); ?>
  </span>
</a>
    <?php endforeach; ?>
    <a href="alltrabajos.php" 
       class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md hover:brightness-110 transition">
      <img src="img/trabajo.webp" 
           alt="Trabajos" 
           class="absolute inset-0 w-full h-full object-cover">
      <div class="absolute inset-0 bg-blue-600 bg-opacity-30"></div>
      <span class="relative z-10 text-white text-lg font-semibold">Ver todos</span>
    </a>
  </div>
</section>
<section class="px-4 py-8 bg-gray-50">
  <h2 class="text-xl font-bold text-gray-800 mb-4">Trabajadores Destacados</h2>

  <?php
  include 'Controlador/db_connect.php';

  $sql = "SELECT t.id_trabajador, t.descripcion_trabajo, t.puntaje_promedio,
                 u.id_usuario, u.nombre, u.apellido, u.foto_perfil,
                 e.nombre AS especialidad
          FROM trabajadores t
          JOIN usuarios u ON t.id_usuario = u.id_usuario
          LEFT JOIN especialidades e ON t.id_especialidad = e.id_especialidad
          WHERE t.puntaje_promedio IS NOT NULL
          ORDER BY t.puntaje_promedio DESC
          LIMIT 4";
  $result = $conn->query($sql);

  if ($result && $result->num_rows > 0):
    while ($t = $result->fetch_assoc()):
      $foto = $t['foto_perfil'] ?: 'img/default-profile.png';
      $nombre = htmlspecialchars($t['nombre'] . ' ' . $t['apellido']);
      $especialidad = htmlspecialchars($t['especialidad'] ?: 'Sin especialidad');
      $desc = htmlspecialchars($t['descripcion_trabajo']);
      $puntaje = number_format($t['puntaje_promedio'], 1);

      $estrellasLlenas = floor($t['puntaje_promedio']);
      $estrellaMitad = ($t['puntaje_promedio'] - $estrellasLlenas) >= 0.25 && ($t['puntaje_promedio'] - $estrellasLlenas) < 0.75;
      $estrellasVacias = 5 - $estrellasLlenas - ($estrellaMitad ? 1 : 0);
  ?>
  
  <!-- 🔹 Toda la card ahora es clickeable -->
  <a href="perfil.php?id=<?php echo $t['id_usuario']; ?>" 
     class="block w-full bg-white rounded-xl shadow-md p-4 flex items-start gap-4 mb-4 
            transition hover:shadow-lg hover:bg-gray-100 cursor-pointer">

    <img src="<?php echo $foto; ?>" 
         alt="Foto trabajador" 
         class="w-16 h-16 rounded-full object-cover">

    <div class="flex-1">
      <h3 class="text-lg font-semibold text-gray-800">
        <?php echo $nombre; ?>
      </h3>

      <div class="flex flex-wrap gap-2 my-1">
        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">
          <?php echo $especialidad; ?>
        </span>
      </div>

      <div class="flex items-center my-2">
        <?php
          for ($i = 0; $i < $estrellasLlenas; $i++) {
            echo '<svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>';
          }
          if ($estrellaMitad) {
            echo '<svg class="w-4 h-4 text-yellow-400 opacity-50 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>';
          }
          for ($i = 0; $i < $estrellasVacias; $i++) {
            echo '<svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>';
          }
        ?>
        <span class="ml-2 text-sm text-gray-600"><?php echo $puntaje; ?></span>
      </div>

      <p class="text-sm text-gray-600 leading-snug">
        <?php echo $desc ?: 'Sin descripción disponible.'; ?>
      </p>
    </div>
  </a>

  <?php endwhile; else: ?>
    <p class="text-gray-500 italic text-center">No hay trabajadores destacados por el momento.</p>
  <?php endif; ?>
</section>


  <?php include 'footer.php'; ?>
</body>
</html>
