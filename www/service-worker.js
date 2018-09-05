// https://www.ampproject.org/docs/integration/pwa-amp/amp-as-pwa
// https://developers.google.com/web/fundamentals/primers/service-workers/

var CACHE_NAME = 'diis-online-cache-v1';
var urlsToCache = [
  '/',
  '/style.css',
];

// https://developers.google.com/web/fundamentals/app-install-banners/#criteria
self.addEventListener('beforeinstallprompt', function(event) {
  btnAdd.style.display = 'block';
  });


self.addEventListener('install', function(event) {
  // Perform install steps
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Cache hit - return response
        if (response) {
          return response;
        }
        return fetch(event.request);
      }
    )
  );
});


// https://www.netguru.co/codestories/few-tips-that-will-make-your-pwa-on-ios-feel-like-native

// Detects if device is on iOS 
const isIos = () => {
  const userAgent = window.navigator.userAgent.toLowerCase();
  return /iphone|ipad|ipod/.test( userAgent );
}
// Detects if device is in standalone mode
const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

// Checks if should display install popup notification:
if (isIos() && !isInStandaloneMode()) {
  this.setState({ showInstallMessage: true });
}
