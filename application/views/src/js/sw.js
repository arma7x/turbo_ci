const origin = self.location.protocol+'//'+self.location.host;
const offlinePage = "/offline";
const dynamicCacheFiles = ["/"];
const staticCacheFiles = ["/src/app.css", "/static/css/bootstrap.min.css",
"/static/font/MaterialIcons-Regular.woff2", "/static/js/turbolinks.js", "/src/app.js",
"/static/js/bootstrap.min.js", "/static/js/popper.min.js", "/static/js/jquery-3.3.1.min.js",
"/static/img/android-chrome-192x192.png", "/static/img/android-chrome-512x512.png",
"/static/img/apple-touch-icon.png", "/static/img/favicon-16x16.png", "/static/img/favicon-32x32.png",
"/static/img/mstile-150x150.png", "/static/img/safari-pinned-tab.svg"];
// disable "/static/particlesjs-config.json", "/static/js/particles.min.js"
const staticCacheName = 'static-<?php echo filemtime(APPPATH.'views/src/js/sw.js').'-'.filemtime(APPPATH.'views/src/js/app.js').'-'.filemtime(APPPATH.'views/src/css/app.css') ?>';
const expectedCaches = [staticCacheName];

const cacheHeader = { 'sw-offline-cache': staticCacheName };
//const cacheHeader = new Headers({ 'sw-offline-cache': staticCacheName }); //

self.addEventListener('install', event => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName).then(cache => cache.addAll(staticCacheFiles))
    );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.map(oldStaticCacheName => {
        // dynamic cache
        dynamicCacheFiles.forEach(v => {
            let dynamicCacheReq = new Request(v);
            dynamicCacheReq.credentials = 'same-origin';
            fetch(dynamicCacheReq, {credentials: 'same-origin', headers: cacheHeader}).then((response) => {
              return caches.open(staticCacheName).then((cache) => {
                return cache.put(dynamicCacheReq, response);
              });
            })
        })
        // cache  offlinePage
        let offlinePageReq = new Request(offlinePage);
        offlinePageReq.credentials = 'same-origin';
        fetch(offlinePageReq, {credentials: 'same-origin'}).then((response) => {
          return caches.open(staticCacheName).then((cache) => {
            return cache.put(offlinePageReq, response);
          });
        })
        if (!expectedCaches.includes(oldStaticCacheName)) {
            return caches.delete(oldStaticCacheName);
        }
      })
    ))
  );
});

self.addEventListener('fetch', event => {
    event.respondWith(fromNetwork(event.request, 15000).then((result) => {
        return result;
    })
    .catch(() => {
        return fromCache(event.request);
    }));
});

function fromNetwork(request, timeout) {
  return new Promise((fulfill, reject) => {
    const timeoutId = setTimeout(reject, timeout);
    fetch(request).then((response) => {
      const targetRequest = request.url;
      if (dynamicCacheFiles.indexOf(targetRequest.replace(origin, '')) === 1) { // re-cache dynamicCacheFiles
        if (request.method === 'GET') {
          let requestWithoutCache = request.clone();
          requestWithoutCache.credentials = 'same-origin';
          fetch(requestWithoutCache, {credentials: 'same-origin', headers: cacheHeader}).then((responseWithoutCookies) => {
            if (responseWithoutCookies.status === 200) {
              const responseToCache = responseWithoutCookies.clone();
              // cache page with response code 200 only
              caches.open(staticCacheName).then(cache => cache.put(requestWithoutCache, responseToCache));
            } else {
              // remove non-200 page status 
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
    return caches.open(staticCacheName).then((cache) => {
        return cache.match(request).then((matching) => {
            return matching || caches.match(offline);
        });
    });
}
