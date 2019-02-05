<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

    <script src="/src/app.js" type="text/javascript"></script>
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
