<?php
include 'header.php';

require 'controlador/db_connect.php';

$sql = "SELECT * FROM especialidades WHERE activo = 1 ORDER BY nombre ASC";
$result = $conn->query($sql);

$especialidades = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $especialidades[] = $row;
  }
}
?>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todas las Especialidades - TuSitioWeb</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<section class="px-6 py-10">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl md:text-3xl font-semibold text-gray-800">
      Todas las especialidades disponibles
    </h2>

    <a href="index.php" 
       class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
      </svg>
      Volver
    </a>
  </div>

  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php if (count($especialidades) > 0): ?>
      <?php foreach ($especialidades as $esp): ?>
        <a href="#<?php echo strtolower($esp['nombre']); ?>" 
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
    <?php else: ?>
      <p class="col-span-full text-center text-gray-500">No hay especialidades disponibles en este momento.</p>
    <?php endif; ?>
  </div>
</section>

<?php include 'footer.php'; ?>
