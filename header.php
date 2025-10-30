  <head>
      <script src="https://cdn.tailwindcss.com"></script>

  </head>
  
  <header class="flex justify-between items-center px-4 py-3 border-b border-gray-200">
    <a href="index.php">
    <div class="logo">
      <img src="img/labu.png" alt="Logo" class="h-12">
    </div>
    </a>
    <?php if (isset($_SESSION['id_usuario'])): ?>
 

    
    <div class="flex items-center gap-4">
  <a href="#" class="text-gray-500 hover:text-gray-700">
    <i class="ri-notification-3-line text-xl"></i>
  </a>
  <a href="#" class="text-gray-500 hover:text-gray-700">
    <i class="ri-chat-3-line text-xl"></i>
  </a>

  <?php if (isset($_SESSION['id_usuario'])): ?>
    <a href="logout.php" 
       class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-full shadow transition">
      Cerrar sesión
    </a>
  <?php endif; ?>
</div>

  </header>