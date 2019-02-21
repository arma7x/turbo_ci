<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo isset($description) ? $description : '' ?>">
    <meta name="author" content="">
    <meta name="turbolinks-cache-control" content="no-cache">
    <title><?php echo isset($title) ? $title : 'Codeigniter' ?></title>
    <?php echo isset($header) ? $header : null ?>
  </head>
  <body class="p-0">
    <?php echo isset($nav) ? $nav : null ?>
    <main class="col" id="main" role="main">
        <div class="row">
            <div id="dashboard-menu" class="col col-12 col-lg-2 px-0 position-fixed dashboard-menu shadow">
            <?php echo isset($menu) ? $menu : null ?>
            </div>
            <div class="col col-12 col-lg-10 offset-lg-2 dashboard-container">
                <?php echo isset($notification) ? $notification : null ?>
                <?php echo isset($content) ? $content : null ?>
            </div>
        </div>
    </main>
    <?php echo isset($spinner) ? $spinner : null ?>
    <footer class="footer bg-light border-top border-primary">
      <div class="container text-sm-right">
        <div class="col col-12 col-lg-10 offset-lg-2">
        <span class="text-dark small"><?php echo str_replace('%s', $this->benchmark->elapsed_time(), lang('L_F_RENDER_ELAPSED')).'|'.$this->benchmark->memory_usage().'|'.strtoupper(ENVIRONMENT) ?></span>
        </div>
      </div>
    </footer>
    <?php echo isset($footer) ? $footer : null ?>
  </body>
</html>
