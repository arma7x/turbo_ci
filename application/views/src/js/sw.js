const origin = self.location.protocol+'//'+self.location.host;
const offlinePage = "/offline";
const staticCacheFiles = ["/src/app.css", "/asset/css/bootstrap.min.css", "/asset/font/MaterialIcons-Regular.woff2", "/asset/js/turbolinks.js", "/src/app.js", "/asset/js/bootstrap.min.js", "/asset/js/popper.min.js", "/asset/js/jquery-3.3.1.min.js"
];
const staticCacheName = 'static-<?php echo filemtime(APPPATH.'views/src/js/sw.js').'-'.filemtime(APPPATH.'views/src/js/app.js').'-'.filemtime(APPPATH.'views/src/css/app.css') ?>';
const expectedCaches = [staticCacheName];
const myHeaders = new Headers({ 'sw-offline-cache': staticCacheName });

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(staticCacheName)
      .then(cache => cache.addAll(staticCacheFiles))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.map(oldStaticCacheName => {
        // cache offlinePage first
        const offlinePageReq = new Request(offlinePage);
        fetch(offlinePageReq).then(function(response) {
          return caches.open(staticCacheName).then(function(cache) {
            return cache.put(offlinePageReq, response);
          });
        })
        if (!expectedCaches.includes(oldStaticCacheName)) {
           // bring forward non-staticCacheFiles from previous cache to current cache
           caches.open(oldStaticCacheName).then(function(cache) {
             cache.keys().then(oldCache => {
               for (i in oldCache) {
                 const oldCacheRequestURL = oldCache[i].url;
                 const trimURL = oldCacheRequestURL.replace(origin, '');
                 if (staticCacheFiles.indexOf(trimURL) === -1 && offlinePage !== trimURL) {
                   let requestWithoutCache = oldCache[i].clone();
                   requestWithoutCache.credentials = 'omit';
                   // heavy task ??
                   fetch(requestWithoutCache, {credentials: 'omit', headers: myHeaders}).then(function (responseWithoutCookies) {
                     if (responseWithoutCookies.status === 200) {
                       const responseToCache = responseWithoutCookies.clone();
                       caches.open(staticCacheName).then(cache => cache.put(requestWithoutCache, responseToCache));
                     }
                   });
                 }
               }
             });
           });
          return caches.delete(oldStaticCacheName);
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
    fetch(request).then(function (response) {
      const targetRequest = request.url;
      if (staticCacheFiles.indexOf(targetRequest.replace(origin, '')) === -1) { // dont re-cache/request staticCacheFiles
        if (request.method === 'GET') { // POST, PUT, PATCH, DELETE IS IGNORE
          let requestWithoutCache = request.clone();
          requestWithoutCache.credentials = 'omit'; // cache page without auth for offline
          fetch(requestWithoutCache, {credentials: 'omit', headers: myHeaders}).then(function (responseWithoutCookies) {
            if (responseWithoutCookies.status === 200) {
              const responseToCache = responseWithoutCookies.clone();
              // cache page with response code 200 only
              caches.open(staticCacheName).then(cache => cache.put(requestWithoutCache, responseToCache));
            } else {
              // offline mode should display 200 content only or return offline page
              caches.open(staticCacheName).then((cache) => cache.delete(requestWithoutCache));
            }
          });
        }
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
