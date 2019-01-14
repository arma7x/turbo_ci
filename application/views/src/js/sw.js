const offlinePage = "/offline";
const whiteListCacheFiles = [];
const initialCacheFiles = ["/src/app.css", "/asset/css/bootstrap.min.css", "/asset/font/MaterialIcons-Regular.woff2", "/asset/js/turbolinks.js", "/src/app.js", "/asset/js/bootstrap.min.js", "/asset/js/popper.min.js", "/asset/js/jquery-3.3.1.min.js"
];
const staticCacheName = 'static-<?php echo filemtime(APPPATH.'views/src/js/sw.js').'-'.filemtime(APPPATH.'views/src/js/app.js').'-'.filemtime(APPPATH.'views/src/css/app.css') ?>';
const expectedCaches = [
  staticCacheName
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(staticCacheName)
      .then(cache => cache.addAll(initialCacheFiles))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.map(key => {
        // cache offlinePage first
        const offlinePageReq = new Request(offlinePage);
        fetch(offlinePageReq).then(function(response) {
          return caches.open(staticCacheName).then(function(cache) {
            return cache.put(offlinePageReq, response);
          });
        })
        if (!expectedCaches.includes(key)) {
          return caches.delete(key);
        }
      })
    ))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(fromNetwork(event.request, 30000).then(function(result) {
    return result;
  })
  .catch(function () {
    return fromCache(event.request);
  }));
});

function fromNetwork(request, timeout) {
  return new Promise(function (fulfill, reject) {
    const timeoutId = setTimeout(reject, timeout);
    fetch(request).then(function (response) {;
      // cache whiteListCacheFiles in list
      let target = request.url;
      const origin = self.location.protocol+'//'+self.location.host;
      if (whiteListCacheFiles.indexOf(target.replace(origin, '')) !== -1) {
        const responseToCache = response.clone();
        caches.open(staticCacheName).then(cache => cache.put(request, responseToCache));
      }
      clearTimeout(timeoutId);
      fulfill(response);
    }, reject);
  });
}
 
function fromCache(request) {
  const offline = new Request(offlinePage);
  return caches.open(staticCacheName).then(function (cache) {
    return cache.match(request).then(function (matching) {
      return matching || caches.match(offline);
    });
  });
}
