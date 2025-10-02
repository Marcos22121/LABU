<?php
session_start();
include 'Controlador/db_connect.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro como Trabajador</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function nextStep(step) {
      document.querySelectorAll('.step').forEach(div => div.classList.add('hidden'));
      document.getElementById('step-' + step).classList.remove('hidden');
    }
  </script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-lg">
  <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Registrate como Trabajador</h2>

  <?php if (isset($_GET['success'])): ?>
    <p class="text-green-600 text-center mb-4">¡Registro exitoso!</p>
  <?php elseif (isset($_GET['error'])): ?>
    <p class="text-red-600 text-center mb-4">Error: <?= htmlspecialchars($_GET['error']) ?></p>
  <?php endif; ?>

  <form method="POST" action="Modelos/trabajar.php">
  <!-- Paso 1: Especialidad -->
<div id="step-1" class="step">
  <h3 class="text-lg font-semibold mb-4">Seleccioná tu especialidad</h3>
  <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    <?php
    $especialidades = $conn->query("SELECT id_especialidad, nombre, foto_url FROM especialidades");
    while ($esp = $especialidades->fetch_assoc()): ?>
      <label for="esp-<?php echo $esp['id_especialidad']; ?>" 
             class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md group cursor-pointer transition-transform transform hover:scale-105">
        <input type="radio" name="id_especialidad" id="esp-<?php echo $esp['id_especialidad']; ?>" value="<?php echo $esp['id_especialidad']; ?>" class="hidden peer" required>
        <img src="<?php echo $esp['foto_url'] ?: 'img/trabajo.webp'; ?>" 
             alt="<?php echo htmlspecialchars($esp['nombre']); ?>" 
             class="absolute inset-0 w-full h-full object-cover peer-checked:brightness-75">
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        <span class="relative text-white text-lg font-semibold"><?php echo htmlspecialchars($esp['nombre']); ?></span>
      </label>
    <?php endwhile; ?>
  </div>
  <button type="button" onclick="nextStep(2)" class="mt-6 w-full bg-blue-600 text-white py-2 rounded-lg">Siguiente</button>
</div>

    <!-- Paso 2 -->
    <div id="step-2" class="step hidden">
      <label class="block mb-2 font-medium">Descripción de tu servicio</label>
      <textarea name="descripcion" required class="w-full border rounded-lg p-2 mb-4"></textarea>
      <div class="flex justify-between">
        <button type="button" onclick="nextStep(1)" class="px-4 py-2 bg-gray-400 text-white rounded-lg">Atrás</button>
        <button type="button" onclick="nextStep(3)" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Siguiente</button>
      </div>
    </div>

    <!-- Paso 3 -->
<!-- Paso 3: Plan -->
<div id="step-3" class="step hidden">
  <h3 class="text-lg font-semibold mb-4">Elegí tu plan</h3>

  <div class="relative">
    <div id="carousel" class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4">
      <!-- Plan Free -->
      <label class="min-w-[200px] bg-gray-100 rounded-xl p-4 text-center snap-center cursor-pointer hover:scale-105 transition">
        <input type="radio" name="plan" value="Free" class="hidden peer" required>
        <h4 class="text-xl font-bold text-blue-600">Free</h4>
        <p class="text-gray-600">Acceso básico</p>
        <p class="text-lg font-semibold mt-2">$0/mes</p>
      </label>

      <!-- Plan Básico -->
      <label class="min-w-[200px] bg-gray-100 rounded-xl p-4 text-center snap-center cursor-pointer hover:scale-105 transition">
        <input type="radio" name="plan" value="Basico" class="hidden peer" required>
        <h4 class="text-xl font-bold text-green-600">Básico</h4>
        <p class="text-gray-600">Más visibilidad</p>
        <p class="text-lg font-semibold mt-2">$500/mes</p>
      </label>

      <!-- Plan Premium -->
      <label class="min-w-[200px] bg-gray-100 rounded-xl p-4 text-center snap-center cursor-pointer hover:scale-105 transition">
        <input type="radio" name="plan" value="Premium" class="hidden peer" required>
        <h4 class="text-xl font-bold text-purple-600">Premium</h4>
        <p class="text-gray-600">Máxima exposición</p>
        <p class="text-lg font-semibold mt-2">$1000/mes</p>
      </label>
    </div>
  </div>

  <div class="flex justify-between mt-6">
    <button type="button" onclick="nextStep(2)" class="px-4 py-2 bg-gray-400 text-white rounded-lg">Atrás</button>
    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Finalizar</button>
  </div>
</div>


  </form>
</div>

</body>
</html>
