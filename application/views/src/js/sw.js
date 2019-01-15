const origin = self.location.protocol+'//'+self.location.host;
const homepage = "/";
const offlinePage = "/offline";
const staticCacheFiles = ["/src/app.css", "/asset/css/bootstrap.min.css", "/asset/font/MaterialIcons-Regular.woff2", "/asset/js/turbolinks.js", "/src/app.js", "/asset/js/bootstrap.min.js", "/asset/js/popper.min.js", "/asset/js/jquery-3.3.1.min.js"
];
const staticCacheName = 'static-<?php echo filemtime(APPPATH.'views/src/js/sw.js').'-'.filemtime(APPPATH.'views/src/js/app.js').'-'.filemtime(APPPATH.'views/src/css/app.css') ?>';
const expectedCaches = [staticCacheName];

const cacheHeader = { 'sw-offline-cache': staticCacheName };
//const cacheHeader = new Headers({ 'sw-offline-cache': staticCacheName });

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
        // cache homepage
        let homepageReq = new Request(homepage);
        homepageReq.credentials = 'omit';
        fetch(homepageReq, {credentials: 'omit', headers: cacheHeader}).then((response) => {
          return caches.open(staticCacheName).then((cache) => {
            return cache.put(homepageReq, response);
          });
        })
        // cache offlinePage
        let offlinePageReq = new Request(offlinePage);
        offlinePageReq.credentials = 'omit';
        fetch(offlinePageReq, {credentials: 'omit'}).then((response) => {
          return caches.open(staticCacheName).then((cache) => {
            return cache.put(offlinePageReq, response);
          });
        })
        if (!expectedCaches.includes(oldStaticCacheName)) {
           // bring forward non-staticCacheFiles from previous cache to current cache
           caches.open(oldStaticCacheName).then((cache) => {
             cache.keys().then(oldCache => {
               oldCache.forEach(v => {
                 const oldCacheRequestURL = v.url;
                 const trimURL = oldCacheRequestURL.replace(origin, '');
                 if (staticCacheFiles.indexOf(trimURL) === -1 && trimURL !== offlinePage) {
                   let request = new Request(trimURL);
                   request.credentials = 'omit';
                   // heavy network request if to many old cached pages emm
                   fetch(request, {credentials: 'omit', headers: cacheHeader}).then((response) => {
                     if (response.status === 200) {
                       const freshCopy = response.clone();
                       caches.open(staticCacheName).then(cache => cache.put(request, freshCopy));
                     }
                   });
                 }
               });
             });
           });
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
      if (staticCacheFiles.indexOf(targetRequest.replace(origin, '')) === -1) { // dont re-cache staticCacheFiles
        if (request.method === 'GET') {
          let requestWithoutCache = request.clone();
          requestWithoutCache.credentials = 'omit'; // cache page without auth for offline
          fetch(requestWithoutCache, {credentials: 'omit', headers: cacheHeader}).then((responseWithoutCookies) => {
            if (responseWithoutCookies.status === 200) {
              const responseToCache = responseWithoutCookies.clone();
              // cache page with response code 200 only
              caches.open(staticCacheName).then(cache => cache.put(requestWithoutCache, responseToCache));
            } else {
              // offline mode should display 200 content or return offline page
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
