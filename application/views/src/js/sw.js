const cacheFirstFiles = ["/", "/src/app.css", "/asset/css/bootstrap.min.css", "/asset/font/MaterialIcons-Regular.woff2", "/asset/js/turbolinks.js", "/src/app.js", "/asset/js/bootstrap.min.js", "/asset/js/popper.min.js", "/asset/js/jquery-3.3.1.min.js"
];

const staticCacheName = 'static-<?php echo filemtime(APPPATH.'views/src/js/sw.js') ?>';
const expectedCaches = [
  staticCacheName
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(staticCacheName)
      .then(cache => cache.addAll(cacheFirstFiles))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.map(key => {
        if (!expectedCaches.includes(key)) return caches.delete(key);
      })
    ))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(fromNetwork(event.request, 400).then(function(result) {
    return result;
  })
  .catch(function () {
    var result = fromCache(event.request);
    return result;
  }));
});

function fromNetwork(request, timeout) {
  return new Promise(function (fulfill, reject) {
    var timeoutId = setTimeout(reject, timeout);
    fetch(request).then(function (response) {
      const responseToCache = response.clone();
      caches.open(staticCacheName).then(cache => cache.put(request, responseToCache));
      clearTimeout(timeoutId);
      fulfill(response);
    }, reject);
  });
}
 
function fromCache(request) {
  return caches.open(staticCacheName).then(function (cache) {
    return cache.match(request).then(function (matching) {
      return matching || Promise.reject('no-match');
    });
  });
}
