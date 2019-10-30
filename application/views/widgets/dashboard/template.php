<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo isset($description) ? $description : '' ?>">
    <meta name="author" content="">
    <meta name="turbolinks-cache-control" content="no-cache">
    <title><?php echo isset($title) ? $title : 'Codeigniter' ?></title>
    <?php echo $this->load->view('widgets/common/header', $this, TRUE) ?>
  </head>
  <body class="p-0">
    <?php echo $this->load->view('widgets/dashboard/nav', $this, TRUE) ?>
    <main class="col" id="main" role="main">
        <div class="row">
            <div id="dashboard-menu" class="col col-12 col-lg-2 px-0 position-fixed dashboard-menu shadow">
            <?php echo $this->load->view('widgets/dashboard/menu', $this, TRUE) ?>
            </div>
            <div class="col col-12 col-lg-10 offset-lg-2 dashboard-container">
                <?php echo $this->load->view('widgets/common/notification', $this, TRUE) ?>
                <?php echo isset($content) ? $content : null ?>
            </div>
        </div>
    </main>
    <?php echo $this->load->view('widgets/common/spinner', $this, TRUE) ?>
    <footer class="footer bg-light border-top border-primary">
      <div class="container text-sm-right">
        <div class="col col-12 col-lg-10 offset-lg-2">
        <span class="text-dark small"><?php echo str_replace('%s', $this->benchmark->elapsed_time(), lang('L_F_RENDER_ELAPSED')).'|'.$this->benchmark->memory_usage().'|'.strtoupper(ENVIRONMENT) ?></span>
        </div>
      </div>
    </footer>
    <?php echo $this->load->view('widgets/common/footer', $this, TRUE) ?>
  </body>
</html>
