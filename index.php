<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi App</title>
  <!-- Importar Tailwind CSS vía CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans">

  <header class="flex justify-between items-center px-4 py-3 border-b border-gray-200">
    <!-- Logo izquierda -->
    <div class="logo">
      <img src="logo.png" alt="Logo" class="h-10">
    </div>

    <!-- Iconos derecha -->
    <div class="flex items-center gap-4">
      <!-- Notificaciones -->
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

      <!-- Mensajes -->
      <a href="#mensajes" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100" title="Mensajes">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 10h.01M12 10h.01M16 
               10h.01M21 12c0 4.418-4.03 8-9 
               8a9.77 9.77 0 01-4.255-.938L3 
               20l1.938-4.255A9.77 9.77 0 
               015 12c0-4.418 4.03-8 9-8s9 
               3.582 9 8z" />
        </svg>
      </a>
    </div>
  </header>

    <!-- Hero -->
  <section class="relative h-[70vh] flex items-center justify-center">
    <!-- Imagen de fondo -->
    <img src="https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=1600&q=80" 
         alt="Trabajo" 
         class="absolute inset-0 w-full h-full object-cover">

    <!-- Overlay azul -->
    <div class="absolute inset-0 bg-blue-900 bg-opacity-50"></div>

    <!-- Contenido -->
    <div class="relative text-center text-white max-w-2xl px-6">
      <h1 class="text-3xl md:text-4xl font-bold mb-4">
        Encuentra tus mejores trabajos en nuestra app
      </h1>
      <p class="text-lg md:text-xl mb-6">
        Conecta con oportunidades únicas y haz crecer tu carrera.
      </p>
      <a href="#buscar" 
         class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-full shadow-lg transition">
        Buscar trabajadores
      </a>
    </div>
  </section>
   <!-- Sección de trabajos más buscados -->
<section class="px-6 py-10">
  <!-- Título -->
  <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-6">
    Trabajos más buscados
  </h2>

  <!-- Grid de cajas -->
  <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    
    <!-- Electricista -->
    <a href="#electricista" class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md group">
      <img src="img/elec.webp" 
           alt="Electricista" 
           class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all">
      <div class="absolute inset-0 bg-black bg-opacity-40"></div>
      <span class="relative text-white text-lg font-semibold">Electricista</span>
    </a>

    <!-- Plomero -->
    <a href="#plomero" class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md group">
      <img src="img/plomero.jpg" 
           alt="Plomero" 
           class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all">
      <div class="absolute inset-0 bg-black bg-opacity-40"></div>
      <span class="relative text-white text-lg font-semibold">Plomero</span>
    </a>

    <!-- Carpintero -->
    <a href="#carpintero" class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md group">
      <img src="img/carpin.webp" 
           alt="Carpintero" 
           class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all">
      <div class="absolute inset-0 bg-black bg-opacity-40"></div>
      <span class="relative text-white text-lg font-semibold">Carpintero</span>
    </a>

    <!-- Pintor -->
    <a href="#pintor" class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md group">
      <img src="img/pintor.jpg" 
           alt="Pintor" 
           class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all">
      <div class="absolute inset-0 bg-black bg-opacity-40"></div>
      <span class="relative text-white text-lg font-semibold">Pintor</span>
    </a>

    <!-- Jardinero -->
    <a href="#jardinero" class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md group">
      <img src="img/jardinero.webp" 
           alt="Jardinero" 
           class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-all">
      <div class="absolute inset-0 bg-black bg-opacity-40"></div>
      <span class="relative text-white text-lg font-semibold">Jardinero</span>
    </a>

    <!-- Ver todos -->
    <a href="#todos" class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md bg-blue-600 hover:bg-blue-700 transition">
      <span class="text-white text-lg font-semibold">Ver todos</span>
    </a>
  </div>
</section>

<!-- Barra inferior fija -->
<nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 flex justify-around items-center py-2 shadow-md z-50">
  
  <!-- Botón Inicio -->
  <a href="#inicio" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 w-1/3">
    <!-- Icono Home -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
    </svg>
    <span class="text-sm font-medium">Inicio</span>
  </a>

  <!-- Botón central grande -->
  <a href="#trabajador" 
     class="relative -mt-8 w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg hover:brightness-110 transition">
    <!-- Icono trabajador -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 
           1.79-4 4 1.79 4 4 4zm0 2c-3.31 
           0-6 2.69-6 6h12c0-3.31-2.69-6-6-6z" />
    </svg>
  </a>

  <!-- Botón Cuenta -->
  <a href="#cuenta" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 w-1/3">
    <!-- Icono usuario -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M5.121 17.804A9 9 0 1112 21a9 
           9 0 01-6.879-3.196zM15 11a3 3 0 
           11-6 0 3 3 0 016 0z" />
    </svg>
    <span class="text-sm font-medium">Cuenta</span>
  </a>
</nav>


</body>
</html>
