const CACHE_NAME = "trabajopro-v1";
const urlsToCache = [
  "/",
  "/index.php",
  "/css/tailwind.css",
  "/js/main.js",
  "/img/icons/icon-192x192.png",
  "/img/icons/icon-512x512.png"
];

// Instalar y cachear
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(urlsToCache))
  );
});

// Interceptar peticiones
self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});

// Actualizar caché
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((name) => {
          if (name !== CACHE_NAME) {
            return caches.delete(name);
          }
        })
      );
    })
  );
});
