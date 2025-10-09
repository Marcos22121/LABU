<?php
session_start();
include('Controlador/db_connect.php');

$id_usuario = $_SESSION['id_usuario'] ?? null;
$email_usuario = $_SESSION['email'] ?? null;

// Obtener especialidades y localidades para los filtros
$especialidades = $conn->query("SELECT nombre FROM especialidades ORDER BY nombre ASC");
$localidades = $conn->query("SELECT id_localidad, nombre_localidad FROM localidades ORDER BY nombre_localidad ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Buscar Trabajadores - LABU</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f3f4f6;
      margin: 0;
      padding-bottom: 5rem;
    }
    .hidden-section {
      display: none;
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="flex justify-between items-center px-4 py-3 border-b border-gray-200 bg-white shadow-sm sticky top-0 z-30">
    <div class="logo">
      <img src="img/labu.png" alt="Logo" class="h-12">
    </div>
    <div class="flex items-center gap-4">
      <a href="#notificaciones" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100" title="Notificaciones">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 17h5l-1.405-1.405A2.032 2.032 
               0 0118 14.158V11a6.002 6.002 
               0 00-4-5.659V5a2 2 0 10-4 
               0v.341C7.67 6.165 6 8.388 6 
               11v3.159c0 .538-.214 1.055-.595 
               1.436L4 17h5m6 0v1a3 3 0 
               11-6 0v-1m6 0H9" />
        </svg>
      </a>

      <a href="#mensajes" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100" title="Mensajes">
        <img src="img/chat.png" alt="Mensajes" class="w-6 h-6 object-contain">
      </a>
    </div>
  </header>

  <!-- CONTENIDO -->
  <main class="max-w-3xl mx-auto px-4 mt-6">
    <form id="buscarForm" class="bg-white shadow-md rounded-xl p-4 mb-6">
      <div class="flex flex-col sm:flex-row gap-3">
        <input type="text" id="q" name="q" placeholder="Buscar trabajadores o servicios..." 
               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg">Buscar</button>
      </div>

      <!-- FILTROS -->
      <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
        <select id="especialidad" name="especialidad" class="border border-gray-300 rounded-lg px-3 py-2">
          <option value="">Todas las especialidades</option>
          <?php while ($esp = $especialidades->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($esp['nombre']) ?>"><?= htmlspecialchars($esp['nombre']) ?></option>
          <?php endwhile; ?>
        </select>

        <select id="orden" name="orden" class="border border-gray-300 rounded-lg px-3 py-2">
          <option value="">Ordenar por...</option>
          <option value="nombre_az">Nombre (A-Z)</option>
          <option value="reviews_cantidad">Cantidad de reviews</option>
          <option value="reviews_promedio">Promedio de reviews</option>
        </select>

        <label class="flex items-center gap-2 text-gray-700">
          <input type="checkbox" id="solo_mi_localidad" class="accent-blue-600">
          Solo mi localidad
        </label>
      </div>

      <!-- BOTÓN LOCALIDADES -->
      <div class="mt-4">
        <button type="button" id="toggleLocalidades" 
                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 rounded-lg flex justify-between items-center">
          <span>Localidades</span>
          <svg id="arrowLoc" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 9l-7 7-7-7" />
          </svg>
        </button>

        <!-- SECCIÓN OCULTA DE LOCALIDADES -->
        <div id="localidadesSection" class="hidden-section mt-3 border border-gray-200 rounded-lg p-3 bg-gray-50">
          <div class="flex justify-between mb-2">
            <button type="button" id="selectAll" class="text-sm text-blue-600 hover:underline">Seleccionar todas</button>
            <button type="button" id="deselectAll" class="text-sm text-blue-600 hover:underline">Deseleccionar todas</button>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-60 overflow-y-auto">
            <?php while ($loc = $localidades->fetch_assoc()): ?>
              <label class="flex items-center text-sm text-gray-700 border border-gray-200 rounded-lg px-2 py-1 bg-white hover:bg-gray-100">
                <input type="checkbox" name="localidades[]" value="<?= $loc['id_localidad'] ?>" class="accent-blue-600 mr-2">
                <?= htmlspecialchars($loc['nombre_localidad']) ?>
              </label>
            <?php endwhile; ?>
          </div>
        </div>
      </div>
    </form>

    <!-- RESULTADOS -->
    <div id="resultados" class="space-y-4"></div>
  </main>

  <!-- NAV -->
  <nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 flex justify-around items-center py-1.5 shadow-md z-50">
    <a href="index.php" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 w-1/3">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
      </svg>
      <span class="text-xs font-medium">Inicio</span>
    </a>

    <a href="buscar.php" 
       class="relative -mt-6 w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg hover:brightness-110 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 
             1.79-4 4 1.79 4 4 4zm0 2c-3.31 
             0-6 2.69-6 6h12c0-3.31-2.69-6-6-6z" />
      </svg>
    </a>

    <a href="perfil.php" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 w-1/3">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M5.121 17.804A9 9 0 1112 21a9 
             9 0 01-6.879-3.196zM15 11a3 3 0 
             11-6 0 3 3 0 016 0z" />
      </svg>
      <span class="text-xs font-medium">Cuenta</span>
    </a>
  </nav>

  <script>
  $(document).ready(function() {

    function buscar() {
      const data = $("#buscarForm").serialize();
      $("#resultados").html("<p class='text-center text-gray-500 py-8'>Buscando...</p>");

      $.ajax({
        url: "buscar_ajax.php",
        type: "GET",
        data: data,
        success: function(res) {
          $("#resultados").html(res);
        },
        error: function() {
          $("#resultados").html("<p class='text-center text-red-500 py-8'>Error al buscar.</p>");
        }
      });
    }

    $("#buscarForm").on("submit", function(e) {
      e.preventDefault();
      buscar();
    });

    $("#buscarForm input, #buscarForm select").on("change keyup", buscar);

    // Toggle Localidades
    $("#toggleLocalidades").click(function() {
      $("#localidadesSection").slideToggle(200);
      $("#arrowLoc").toggleClass("rotate-180");
    });

    // Seleccionar/Deseleccionar todas
    $("#selectAll").click(function() {
      $("#localidadesSection input[type=checkbox]").prop("checked", true).trigger("change");
    });
    $("#deselectAll").click(function() {
      $("#localidadesSection input[type=checkbox]").prop("checked", false).trigger("change");
    });

    // Si marca solo mi localidad, desactiva otras
    $("#solo_mi_localidad").change(function() {
      if ($(this).is(":checked")) {
        $("#localidadesSection input[type=checkbox]").prop("checked", false);
      }
      buscar();
    });

    // Búsqueda inicial
    buscar();
  });
  </script>

</body>
</html>
