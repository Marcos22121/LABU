<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<header class="flex justify-between items-center px-4 py-3 border-b border-gray-200 bg-white sticky top-0 z-50">
  <a href="index.php" class="flex items-center">
    <div class="logo">
      <img src="img/labu.png" alt="Logo" class="h-12">
    </div>
  </a>

  <div class="flex items-center gap-4 relative">
    <!-- Botón de notificaciones -->
    <a href="notificaciones.php" id="notif-btn"
       class="relative w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100"
       title="Notificaciones">
      <svg xmlns="http://www.w3.org/2000/svg"
           class="w-6 h-6 text-gray-700"
           fill="none"
           viewBox="0 0 24 24"
           stroke="currentColor">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 17h5l-1.405-1.405A2.032 2.032 
                 0 0118 14.158V11a6.002 6.002 
                 0 00-4-5.659V5a2 2 0 10-4 
                 0v.341C7.67 6.165 6 8.388 6 
                 11v3.159c0 .538-.214 1.055-.595 
                 1.436L4 17h5m6 0v1a3 3 0 
                 11-6 0v-1m6 0H9" />
      </svg>

      <!-- Indicador rojo -->
      <span id="notif-indicador"
            class="hidden absolute top-2 right-2 w-3 h-3 bg-red-500 rounded-full ring-2 ring-white animate-pulse">
      </span>
    </a>

    <!-- Botón de mensajes -->
    <a href="mensajes.php"
       class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100"
       title="Mensajes">
      <img src="img/chat.png" alt="Mensajes" class="w-6 h-6 object-contain">
    </a>
  </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const indicador = document.getElementById("notif-indicador");

  // Verifica periódicamente si hay notificaciones nuevas
  async function verificarNotificaciones() {
    try {
      const response = await fetch("Controlador/verificar_notificaciones.php");
      const data = await response.json();

      // Si hay notificaciones no leídas, mostrar el punto rojo
      if (data.nuevas && data.nuevas > 0) {
        indicador.classList.remove("hidden");
      } else {
        indicador.classList.add("hidden");
      }
    } catch (error) {
      console.error("Error al verificar notificaciones:", error);
    }
  }

  // Ejecutar al cargar y luego cada 5 segundos
  verificarNotificaciones();
  setInterval(verificarNotificaciones, 5000);
});
</script>
