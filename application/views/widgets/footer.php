    </main>
    <footer class="footer bg-light border-top border-primary">
      <div class="container">
        <span class="text-dark small"><?php echo str_replace('%s', $this->benchmark->elapsed_time(), lang('L_F_RENDER_ELAPSED')).'|'.$this->benchmark->memory_usage().'|'.strtoupper(ENVIRONMENT) ?></span>
      </div>
    </footer>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                registration.onupdatefound = function() {
                    if (navigator.serviceWorker.controller) {
                        var installingWorker = registration.installing;
                        installingWorker.onstatechange = function() {
                            switch (installingWorker.state) {
                                case 'installed':
                                    console.log('Finish install new patch');
                                    break;
                                case 'activated':
                                    alert("<?php echo lang('M_SUCCESS_INSTALL_CACHE') ?>");
                                    console.log('Service worker was activated');
                                    document.location.reload();
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
    </script>
  </body>
</html>
