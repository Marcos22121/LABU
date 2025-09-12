<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mi App</title>
  <!-- Importar Tailwind CSS vía CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 pb-20">

  <header class="flex justify-between items-center px-4 py-3 border-b border-gray-200">
    <!-- Logo izquierda -->
    <div class="logo">
      <img src="img/labu.png" alt="Logo" class="h-12">
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
     <a href="#mensajes" 
   class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100" 
   title="Mensajes">
  <img src="img/chat.png" alt="Mensajes" class="w-6 h-6 object-contain">
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
   <a href="#todos" 
   class="relative flex items-center justify-center h-28 rounded-lg overflow-hidden shadow-md hover:brightness-110 transition">

  <!-- Imagen de fondo -->
  <img src="img/trabajo.webp" 
       alt="Trabajos" 
       class="absolute inset-0 w-full h-full object-cover">

  <!-- Overlay azul -->
  <div class="absolute inset-0 bg-blue-600 bg-opacity-30"></div>

  <!-- Texto -->
  <span class="relative z-10 text-white text-lg font-semibold">Ver todos</span>
</a>

  </div>
</section>

<section class="px-4 py-8 bg-gray-50">
  <h2 class="text-xl font-bold text-gray-800 mb-4">Trabajadores Destacados</h2>

  <!-- Trabajador 1 -->
  <div class="w-full bg-white rounded-xl shadow-md p-4 flex items-start gap-4 mb-4">
    <img src="https://randomuser.me/api/portraits/men/32.jpg" 
         alt="Foto trabajador" 
         class="w-16 h-16 rounded-full object-cover">
    <div class="flex-1">
      <h3 class="text-lg font-semibold text-gray-800">Carlos Ramírez</h3>
      <div class="flex flex-wrap gap-2 my-1">
        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Electricista</span>
        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Plomero</span>
        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Mantenimiento</span>
      </div>
      <div class="flex items-center my-2">
        <!-- 4 llenas, 1 vacía -->
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <span class="ml-2 text-sm text-gray-600">4.0</span>
      </div>
      <p class="text-sm text-gray-600 leading-snug">
        Profesional con más de 8 años de experiencia en instalaciones eléctricas y plomería. Responsable, puntual y orientado a resolver problemas de forma eficiente.
      </p>
    </div>
  </div>

  <!-- Trabajador 2 -->
  <div class="w-full bg-white rounded-xl shadow-md p-4 flex items-start gap-4 mb-4">
    <img src="https://randomuser.me/api/portraits/women/44.jpg" 
         alt="Foto trabajadora" 
         class="w-16 h-16 rounded-full object-cover">
    <div class="flex-1">
      <h3 class="text-lg font-semibold text-gray-800">Mariana López</h3>
      <div class="flex flex-wrap gap-2 my-1">
        <span class="px-2 py-1 text-xs bg-pink-100 text-pink-700 rounded-full">Diseñadora</span>
        <span class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded-full">Ilustradora</span>
      </div>
      <div class="flex items-center my-2">
        <!-- 5 llenas -->
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <span class="ml-2 text-sm text-gray-600">5.0</span>
      </div>
      <p class="text-sm text-gray-600 leading-snug">
        Especialista en diseño gráfico y branding con 5 años de experiencia. Creativa y apasionada por crear soluciones visuales modernas.
      </p>
    </div>
  </div>

  <!-- Trabajador 3 -->
  <div class="w-full bg-white rounded-xl shadow-md p-4 flex items-start gap-4 mb-4">
    <img src="https://randomuser.me/api/portraits/men/65.jpg" 
         alt="Foto trabajador" 
         class="w-16 h-16 rounded-full object-cover">
    <div class="flex-1">
      <h3 class="text-lg font-semibold text-gray-800">Julián Torres</h3>
      <div class="flex flex-wrap gap-2 my-1">
        <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Carpintero</span>
        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Constructor</span>
      </div>
      <div class="flex items-center my-2">
        <!-- 3 llenas, 1 media, 1 vacía -->
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 opacity-50 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <span class="ml-2 text-sm text-gray-600">3.5</span>
      </div>
      <p class="text-sm text-gray-600 leading-snug">
        Carpintero con más de 10 años en el rubro. Experto en muebles a medida y reparaciones para el hogar.
      </p>
    </div>
  </div>

  <!-- Trabajador 4 -->
  <div class="w-full bg-white rounded-xl shadow-md p-4 flex items-start gap-4">
    <img src="https://randomuser.me/api/portraits/women/12.jpg" 
         alt="Foto trabajadora" 
         class="w-16 h-16 rounded-full object-cover">
    <div class="flex-1">
      <h3 class="text-lg font-semibold text-gray-800">Lucía Fernández</h3>
      <div class="flex flex-wrap gap-2 my-1">
        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Jardinera</span>
        <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">Paisajista</span>
      </div>
      <div class="flex items-center my-2">
        <!-- 4 llenas, 1 vacía -->
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.57 8.332 1.151-6.064 5.888 1.528 8.307L12 18.896l-7.464 4.607 1.528-8.307-6.064-5.888 8.332-1.151z"/></svg>
        <span class="ml-2 text-sm text-gray-600">4.2</span>
      </div>
      <p class="text-sm text-gray-600 leading-snug">
        Amante de la naturaleza, con experiencia en jardinería y paisajismo. Crea y mantiene espacios verdes llenos de vida.
      </p>
    </div>
  </div>
</section>
 


<!-- Barra inferior fija -->
<nav class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 flex justify-around items-center py-1.5 shadow-md z-50">
  
  <!-- Botón Inicio -->
  <a href="#inicio" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 w-1/3">
    <!-- Icono Home -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
    </svg>
    <span class="text-xs font-medium">Inicio</span>
  </a>

  <!-- Botón central grande -->
  <a href="#trabajador" 
     class="relative -mt-6 w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg hover:brightness-110 transition">
    <!-- Icono trabajador -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 
           1.79-4 4 1.79 4 4 4zm0 2c-3.31 
           0-6 2.69-6 6h12c0-3.31-2.69-6-6-6z" />
    </svg>
  </a>

  <!-- Botón Cuenta -->
  <a href="#cuenta" class="flex flex-col items-center justify-center text-gray-700 hover:text-blue-600 w-1/3">
    <!-- Icono usuario -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M5.121 17.804A9 9 0 1112 21a9 
           9 0 01-6.879-3.196zM15 11a3 3 0 
           11-6 0 3 3 0 016 0z" />
    </svg>
    <span class="text-xs font-medium">Cuenta</span>
  </a>
</nav>


</body>
</html>
