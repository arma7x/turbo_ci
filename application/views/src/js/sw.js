const origin = self.location.protocol+'//'+self.location.host;
const offlinePage = "/offline";
const wilcardCacheFiles = [];
const mainCacheFiles = ["/", "/manifest.json", offlinePage];
const staticCacheFiles = ["/src/app.css", "/static/css/bootstrap.min.css",
"/static/font/MaterialIcons-Regular.woff2", "/static/js/turbolinks.js", "/src/app.js",
"/static/js/bootstrap.min.js", "/static/js/popper.min.js", "/static/js/jquery-3.3.1.min.js",
"/static/img/android-chrome-192x192.png", "/static/img/android-chrome-512x512.png",
"/static/img/apple-touch-icon.png", "/static/img/favicon-16x16.png", "/static/img/favicon-32x32.png",
"/static/img/mstile-150x150.png", "/static/img/safari-pinned-tab.svg"];
const cacheName = 'static-<?php echo filemtime(APPPATH.'views/src/js/sw.js').'-'.filemtime(APPPATH.'views/src/js/app.js').'-'.filemtime(APPPATH.'views/src/css/app.css') ?>';
const expectedCaches = [cacheName];

const cacheHeader = { 'sw-offline-cache': cacheName };

self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(cacheName).then(cache => cache.addAll(staticCacheFiles))
    );
});

self.addEventListener('activate', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.map(oldcacheName => {
        mainCacheFiles.forEach(url => {
            let dynamicCacheReq = new Request(url);
            dynamicCacheReq.credentials = 'same-origin';
            let opts = {};
            if (offlinePage == url) {
                opts = { credentials: 'same-origin' };
            } else {
                opts = { credentials: 'same-origin', headers: cacheHeader };
            }
            fetch(dynamicCacheReq, opts).then((response) => {
              return caches.open(cacheName).then((cache) => {
                return cache.put(dynamicCacheReq, response);
              });
            })
        })
        if (!expectedCaches.includes(oldcacheName)) {
            return caches.delete(oldcacheName);
        }
      })
    ))
  );
});

self.addEventListener('fetch', event => {
    const targetRequest = event.request.url.replace(origin, '');
    if (staticCacheFiles.indexOf(targetRequest) !== -1) {
        // console.log("FROM_CACHE_FIRST::"+targetRequest);
        event.respondWith(fromCache(event.request));
    } else {
        event.respondWith(fromNetwork(event.request, 15000).then((result) => {
            return result;
        })
        .catch(() => {
            return fromCache(event.request);
        }));
    }
});

function fromNetwork(request, timeout) {
  return new Promise((fulfill, reject) => {
    const timeoutId = setTimeout(reject, timeout);
    fetch(request).then((response) => {
      const targetRequest = request.url.replace(origin, '');
      if (mainCacheFiles.indexOf(targetRequest) !== -1) {
        if (request.method === 'GET') {
            // console.log("FROM_NETWORK_THEN_CACHE::"+targetRequest);
            let requestWithoutCache = request.clone();
            let opts = {};
            if (offlinePage == targetRequest) {
                opts = { credentials: 'same-origin' };
            } else {
                opts = { credentials: 'same-origin', headers: cacheHeader };
            }
            requestWithoutCache.credentials = 'same-origin';
            fetch(requestWithoutCache, opts).then((responseWithoutCookies) => {
                if (responseWithoutCookies.status === 200) {
                    const responseToCache = responseWithoutCookies.clone();
                    caches.open(cacheName).then(cache => cache.put(requestWithoutCache, responseToCache));
                } else {
                    caches.open(cacheName).then((cache) => cache.delete(requestWithoutCache));
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
    return caches.open(cacheName).then((cache) => {
        return cache.match(request).then((matching) => {
            return matching || caches.match(offline);
        });
    });
}
