    </main>

    <footer class="footer bg-light border-top border-primary">
      <div class="container">
        <span class="text-dark"><?php echo str_replace('%s', $this->benchmark->elapsed_time(), lang('L_F_RENDER_ELAPSED'));?>/<?php echo $this->benchmark->memory_usage() ?></span>
      </div>
    </footer>

    <script src="/asset/js/jquery-3.3.1.min.js"></script>
    <script src="/asset/js/popper.min.js"></script>
    <script src="/asset/js/bootstrap.min.js"></script>
    <script src="/src/app.js" type="text/javascript"></script>
    <script>
        (function() {
          'use strict';

          var INSTALL_FINISHED = new Event('INSTALL_FINISHED');
          var isLocalhost = Boolean(window.location.hostname === 'localhost' ||
              // [::1] is the IPv6 localhost address.
              window.location.hostname === '[::1]' ||
              // 127.0.0.1/8 is considered localhost for IPv4.
              window.location.hostname.match(
                /^127(?:\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/
              )
            );

          window.addEventListener('load', function() {
              if ('serviceWorker' in navigator &&
                  (window.location.protocol === 'https:' || isLocalhost)) {
                navigator.serviceWorker.register('/sw.js')
                .then(function(registration) {
                  registration.onupdatefound = function() {
                    if (navigator.serviceWorker.controller) {
                      var installingWorker = registration.installing;
                      installingWorker.onstatechange = function() {
                        switch (installingWorker.state) {
                          case 'installed':
                            console.log('New version has been installed');
                            window.dispatchEvent(INSTALL_FINISHED);
                            break;
                          case 'redundant':
                            throw new Error('The installing service worker became redundant.');
                          default:
                            // Ignore
                        }
                      };
                    }
                  };
                }).catch(function(e) {
                  console.error('Error during service worker registration:', e);
                });
              }
          });
        })();
    </script>
  </body>
</html>
