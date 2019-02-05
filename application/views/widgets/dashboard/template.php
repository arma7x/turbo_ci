<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="turbolinks-cache-control" content="no-cache">
    <title><?php echo $title ? $title : 'Codeigniter' ?></title>
    <link rel="apple-touch-icon" sizes="180x180" href="/static/img/apple-touch-icon.png">
    <link rel="icon" href="/static/img/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/static/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/static/img/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/static/img/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="<?php echo $this->container->app_name ?>">
    <meta name="application-name" content="<?php echo $this->container->app_name ?>">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="theme-color" content="#ffffff">
    <link href="/static/css/bootstrap.min.css" rel="stylesheet">
    <link href="/src/app.css" type="text/css" rel="stylesheet">
    <script src="/static/js/turbolinks.js"></script>
    <script src="/static/js/jquery-3.3.1.min.js"></script>
    <script src="/static/js/popper.min.js"></script>
    <script src="/static/js/bootstrap.min.js"></script>
    <script>
        window.csrf_token_name = "<?php echo $this->security->get_csrf_token_name(); ?>";
        window.csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
        function parse_date(id, unix) {
            var converted = new Date(parseInt(unix+'000')).toLocaleString();
            $('#'+id).text(converted);
        }
    </script>
  </head>
  <body class="p-0">
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-header d-flex flex-row-reverse flex-lg-row">
      <div>
        <a onclick="goHome()" class="navbar-brand" data-turbolinks="false">
           <i class="material-icons text-dark" style="font-size:1.5em;">home</i>
           <?php echo lang('H_HOMEPAGE') ?>
        </a>
      </div>
      <button id="navbar-toggler" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navCollapsed" aria-controls="navCollapsed" aria-expanded="false" aria-label="Toggle navigation" style="border:0;">
        <i id="navmenu_icon" class="material-icons text-dark" style="font-size:1.7em;">menu</i>
      </button>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons">&#xe8e2;</i> <?php echo lang('L_LANGUAGE') ?></a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" onclick="change_language('english')" data-turbolinks="false"><?php echo lang('L_ENGLISH_LANG') ?></a>
              <a class="dropdown-item" onclick="change_language('malay')" data-turbolinks="false"><?php echo lang('L_MALAY_LANG') ?></a>
            </div>
          </li>
          <?php if($this->container->user !== NULL): ?>
          <li class="nav-item">
            <a onclick="logout()" class="nav-link" data-turbolinks="false"><i class="material-icons">&#xe879;</i> <?php echo lang('H_LOGOUT');?></a>
          </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
    <?php if($this->session->__notification): ?>
    <div class="fixed-top text-sm-center alert alert-<?php echo $this->session->__notification['type'] ?> alert-dismissible top-alert-noround fade show" role="alert">
      <?php echo $this->session->__notification['message'] ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php endif; ?>
    <?php if ($this->container->sw_offline_cache !== NULL): ?>
    <div class="fixed-top text-sm-center alert alert-info alert-dismissible top-alert-noround fade show" role="alert">
      <?php echo lang('M_CACHE_CONTENT'); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php endif; ?>
    <div class="fixed-bottom row justify-content-center align-items-center mb-5">
        <div class="toast mb-5 mx-2" role="status" aria-live="polite" aria-atomic="true" data-autohide="false">
            <div id="dangerMessage" class="text-white toast-body bg-danger">
          </div>
        </div>
    </div>
    <main class="col" id="main" role="main">
        <div class="row">
            <div id="dashboard-menu" class="col col-12 col-lg-2 px-0 fixed-top dashboard-menu shadow-sm">
                <div id="navCollapsed" class="collapse navbar-collapse-dashboard">
                    <div id="accordionDashboard" class="accordion bg-light">
                      <div class="dropdown-item py-2<?php echo '/'.$this->uri->uri_string() == '/dashboard/index' ? ' active' : ''?>" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <a>Item #1</a>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionDashboard">
                          <div>
                            Anim pariatur cliche
                          </div>
                        </div>
                      </div>
                      <div class="dropdown-divider"></div>
                      <?php if((int) $this->container->user['role'] <= 1): ?>
                      <a class="dropdown-item py-2<?php echo '/'.$this->uri->uri_string() == '/dashboard/manage_user' ? ' active' : ''?>" onclick="navigate('/dashboard/manage_user')"><i class="material-icons">&#xe7ef;</i> <?php echo lang('H_MANAGE_USERS');?></a>
                      <?php endif; ?>
                      <div class="dropdown-divider"></div>
                      <div class="dropdown-item py-2" data-toggle="collapse" data-target="#collapseLang" aria-expanded="false" aria-controls="collapseLang">
                        <div id="headingLang">
                            <i class="material-icons">&#xe8e2;</i> <?php echo lang('L_LANGUAGE') ?>
                        </div>
                        <div id="collapseLang" class="collapse" aria-labelledby="headingLang" data-parent="#accordionDashboard">
                          <div>
                          <a class="dropdown-item" onclick="change_language('english')" data-turbolinks="false"><?php echo lang('L_ENGLISH_LANG') ?></a>
                          <a class="dropdown-item" onclick="change_language('malay')" data-turbolinks="false"><?php echo lang('L_MALAY_LANG') ?></a>
                          </div>
                        </div>
                      </div>
                      <div class="dropdown-divider"></div>
                      <?php if($this->container->user !== NULL): ?>
                        <a class="dropdown-item py-2" onclick="logout()"data-turbolinks="false"><i class="material-icons">&#xe879;</i> <?php echo lang('H_LOGOUT');?></a>
                      <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col col-12 col-lg-10 offset-lg-2 dashboard-container">
                <?php echo isset($content) ? $content : '' ?>
            </div>
        </div>
    </main>
    <div id="loading_spinner" class="spinner spinner-gritcode spinner-md spinner-fixed">
        <div class="spinner-wrapper">
          <div class="spinner-circle"></div>
          <div class="spinner-text"></div>
        </div>
    </div>
    <footer class="footer bg-light border-top border-primary">
      <div class="container">
        <div class="col col-12 col-lg-10 offset-lg-2">
        <span class="text-dark small"><?php echo str_replace('%s', $this->benchmark->elapsed_time(), lang('L_F_RENDER_ELAPSED')).'|'.$this->benchmark->memory_usage().'|'.strtoupper(ENVIRONMENT) ?></span>
        </div>
      </div>
    </footer>
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
  </body>
</html>
