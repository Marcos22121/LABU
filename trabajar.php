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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script>
    function nextStep(step) {
      document.querySelectorAll('.step').forEach(div => div.classList.add('hidden'));
      document.getElementById('step-' + step).classList.remove('hidden');
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  </script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

  <!-- HEADER -->
  <header class="sticky top-0 z-50 bg-white shadow-md">
    <?php include 'header.php'; ?>
  </header>

  <!-- CONTENIDO -->
  <main class="flex-grow flex items-center justify-center px-4 py-6 sm:py-10">
    <div class="bg-white shadow-lg rounded-xl p-6 sm:p-8 w-full max-w-md sm:max-w-lg mt-4 sm:mt-0">

      <h2 class="text-2xl sm:text-3xl font-bold text-center text-blue-600 mb-6">Registrate como Trabajador</h2>

      <?php if (isset($_GET['success'])): ?>
  <script>
    window.location.href = "perfil.php";
  </script>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
  <p class="text-red-600 text-center mb-4">Error: <?= htmlspecialchars($_GET['error']) ?></p>
<?php endif; ?>


      <form method="POST" action="Modelos/trabajar.php" class="space-y-6">

        <!-- Paso 1: Especialidad -->
        <div id="step-1" class="step">
          <h3 class="text-lg font-semibold mb-4 text-center">Seleccioná tu especialidad</h3>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            <?php
            $especialidades = $conn->query("SELECT id_especialidad, nombre, foto_url FROM especialidades");
            while ($esp = $especialidades->fetch_assoc()): ?>
              <label for="esp-<?php echo $esp['id_especialidad']; ?>" 
                     class="relative flex items-center justify-center h-24 sm:h-28 rounded-lg overflow-hidden shadow-md group cursor-pointer transition-transform transform hover:scale-105">
                <input type="radio" name="id_especialidad" id="esp-<?php echo $esp['id_especialidad']; ?>" value="<?php echo $esp['id_especialidad']; ?>" class="hidden peer" required>
                <img src="<?php echo $esp['foto_url'] ?: 'Img/trabajo.webp'; ?>" 
                     alt="<?php echo htmlspecialchars($esp['nombre']); ?>" 
                     class="absolute inset-0 w-full h-full object-cover peer-checked:brightness-75">
                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                <span class="relative text-white text-sm sm:text-lg font-semibold text-center px-2">
                  <?php echo htmlspecialchars($esp['nombre']); ?>
                </span>
              </label>
            <?php endwhile; ?>
          </div>

          <button type="button" onclick="nextStep(2)" 
                  class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-base sm:text-lg transition">
            Siguiente
          </button>
        </div>

        <!-- Paso 2: Descripción -->
        <div id="step-2" class="step hidden">
          <label class="block mb-2 font-medium text-gray-700">Descripción de tu servicio</label>
          <textarea name="descripcion" required 
                    class="w-full border rounded-lg p-3 text-sm sm:text-base h-32 focus:ring-2 focus:ring-blue-500"></textarea>

          <div class="flex flex-col sm:flex-row justify-between gap-3 mt-4">
            <button type="button" onclick="nextStep(1)" 
                    class="w-full sm:w-auto px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition">
              Atrás
            </button>
            <button type="button" onclick="nextStep(3)" 
                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
              Siguiente
            </button>
          </div>
        </div>

        <!-- Paso 3: Plan -->
        <div id="step-3" class="step hidden">
          <h3 class="text-lg font-semibold mb-4 text-center">Elegí tu plan</h3>

          <div class="relative">
            <div id="carousel" class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 scroll-smooth">
              <!-- Plan Free -->
              <label class="min-w-[180px] sm:min-w-[200px] bg-gray-100 rounded-xl p-4 text-center snap-center cursor-pointer hover:scale-105 transition">
                <input type="radio" name="plan" value="Free" class="hidden peer" required>
                <h4 class="text-lg sm:text-xl font-bold text-blue-600">Free</h4>
                <p class="text-gray-600 text-sm">Acceso básico</p>
                <p class="text-lg font-semibold mt-2">$0/mes</p>
              </label>

              <!-- Plan Básico -->
              <label class="min-w-[180px] sm:min-w-[200px] bg-gray-100 rounded-xl p-4 text-center snap-center cursor-pointer hover:scale-105 transition">
                <input type="radio" name="plan" value="Basico" class="hidden peer" required>
                <h4 class="text-lg sm:text-xl font-bold text-green-600">Básico</h4>
                <p class="text-gray-600 text-sm">Más visibilidad</p>
                <p class="text-lg font-semibold mt-2">$500/mes</p>
              </label>

              <!-- Plan Premium -->
              <label class="min-w-[180px] sm:min-w-[200px] bg-gray-100 rounded-xl p-4 text-center snap-center cursor-pointer hover:scale-105 transition">
                <input type="radio" name="plan" value="Premium" class="hidden peer" required>
                <h4 class="text-lg sm:text-xl font-bold text-purple-600">Premium</h4>
                <p class="text-gray-600 text-sm">Máxima exposición</p>
                <p class="text-lg font-semibold mt-2">$1000/mes</p>
              </label>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row justify-between gap-3 mt-6">
            <button type="button" onclick="nextStep(2)" 
                    class="w-full sm:w-auto px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg transition">
              Atrás
            </button>
            <button type="submit" 
                    class="w-full sm:w-auto px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
              Finalizar
            </button>
          </div>
        </div>

      </form>
    </div>
  </main>
<?php include 'footer.php'; ?>

</body>
</html>

