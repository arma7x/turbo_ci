const origin = self.location.protocol+'//'+self.location.host;
const offlinePage = "/offline";
const wilcardCacheFiles = [];
const mainCacheFiles = ["/", offlinePage];
const staticCacheFiles = ["/manifest.json", "/src/app.css", "/static/css/animate.min.css", "/static/css/bootstrap.min.css",
"/static/font/MaterialIcons-Regular.woff2", "/static/js/turbolinks.js", "/src/app.js",
"/static/js/bootstrap.min.js", "/static/js/popper.min.js", "/static/js/jquery-3.3.1.min.js",
"/static/img/android-chrome-192x192.png", "/static/img/android-chrome-512x512.png",
"/static/img/apple-touch-icon.png", "/static/img/favicon-16x16.png", "/static/img/favicon-32x32.png",
"/static/img/mstile-150x150.png", "/static/img/safari-pinned-tab.svg"];
const cacheName = 'static-<?php echo filemtime(APPPATH.'views/src/js/sw.js').'-'.filemtime(APPPATH.'views/src/js/app.js').'-'.filemtime(APPPATH.'views/src/css/app.css') ?>';
const expectedCaches = [cacheName];

const cacheHeader = { 'Sw-Offline-Cache': cacheName };

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
            opts['credentials'] = 'same-origin';
            if (offlinePage !== url) {
                opts['headers'] = { ...cacheHeader };
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
    const parseURL = new URL(event.request.url);
    if (parseURL.origin !== origin) {
        event.respondWith(fromCache(event.request)
        .then((res) => {
            if (res.url === origin+offlinePage) {
                return fromNetwork(event.request, 15000)
            } else {
                return fromCache(event.request);
            }
        })
        .catch((e) => {
            return fromCache(event.request);
        }));
    } else {
        if (staticCacheFiles.indexOf(targetRequest) !== -1) {
            event.respondWith(fromCache(event.request));
        } else {
            event.respondWith(fromNetwork(event.request, 15000).then((result) => {
                return result;
            })
            .catch(() => {
                if (event.request.method === 'POST') {
                    return 'fail';
                }
                return fromCache(event.request);
            }));
        }
    }
});

function fromNetwork(request, timeout) {
  return new Promise((fulfill, reject) => {
    const timeoutId = setTimeout(reject, timeout);
    fetch(request).then((response) => {
      const targetRequest = request.url.replace(origin, '');
      const regex = new RegExp(/([a-z\-_0-9\/\:\.]*\.(jpg|jpeg|png|gif|webp))/i);
      if (mainCacheFiles.indexOf(targetRequest) !== -1 || response.headers.get('sw-offline-cache') !== null || targetRequest.match(regex) !== null) {
        if (request.method === 'GET') {
            let opts = {};
            opts['credentials'] = 'same-origin';
            let offlineReq = request.clone();
            try {
                var a = new URL(offlineReq.url)
                if (a.origin !== origin) {
                    opts['mode'] = 'no-cors';
                    caches.open(cacheName).then(cache => cache.add(offlineReq.url));
                }
            } catch(e) {
                console.log(e);
            }
            if (offlinePage !== targetRequest) {
                opts['headers'] = { ...cacheHeader };
            }
            fetch(offlineReq, opts).then((offlineRes) => {
                if (offlineRes.status === 200) {
                    const responseToCache = offlineRes.clone();
                    caches.open(cacheName).then(cache => cache.put(offlineReq, responseToCache));
                } else {
                    caches.open(cacheName).then((cache) => cache.delete(offlineReq));
                }
            }).catch((err) => {
                console.log(err);
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
