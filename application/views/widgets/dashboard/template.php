<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="turbolinks-cache-control" content="no-cache">
    <title><?php echo isset($title) ? $title : 'Codeigniter' ?></title>
    <?php echo isset($header) ? $header : null ?>
  </head>
  <body class="p-0">
    <?php echo isset($nav) ? $nav : null ?>
    <?php echo isset($notification) ? $notification : null ?>
    <main class="col" id="main" role="main">
        <div class="row">
            <div id="dashboard-menu" class="col col-12 col-lg-2 px-0 fixed-top dashboard-menu shadow">
            <?php echo isset($dashboard_menu) ? $dashboard_menu : null ?>
            </div>
            <div class="col col-12 col-lg-10 offset-lg-2 dashboard-container">
                <?php echo isset($content) ? $content : null ?>
            </div>
        </div>
    </main>
    <?php echo isset($spinner) ? $spinner : null ?>
    <footer class="footer bg-light border-top border-primary">
      <div class="container">
        <div class="col col-12 col-lg-10 offset-lg-2">
        <span class="text-dark small"><?php echo str_replace('%s', $this->benchmark->elapsed_time(), lang('L_F_RENDER_ELAPSED')).'|'.$this->benchmark->memory_usage().'|'.strtoupper(ENVIRONMENT) ?></span>
        </div>
      </div>
    </footer>
    <?php echo isset($footer) ? $footer : null ?>
  </body>
</html>
