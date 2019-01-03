<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="turbolinks-cache-control" content="no-cache">
    <link rel="icon" href="/favicon.ico">
    <title><?php echo $title ? $title : 'Codeigniter' ?></title>
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <script src="/assets/js/turbolinks.js"></script>
    <script>
      window.csrf_token_name = "<?php echo $this->security->get_csrf_token_name(); ?>";
      window.csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
    </script>
  </head>

  <body>

    <nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top shadow-header">
      <div>
        <?php if ($this->uri->segment(1) != NULL): ?>
        <a id="back_btn_sm" class="d-sm-none navbar-brand" href="#" data-turbolinks="false"><i class="material-icons" style="font-size:1.5em;">&#xe5c4;</i></a>
        <?php endif; ?>
        <a id="home_btn" class="navbar-brand"  href="#" data-turbolinks="false">TurboCI</a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navCollapsed" aria-controls="navCollapsed" aria-expanded="false" aria-label="Toggle navigation" style="border:0;">
        <i class="material-icons text-dark" style="font-size:1.7em;">&#xe5d2;</i>
      </button>

      <div class="collapse navbar-collapse" id="navCollapsed">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
          <?php if ($this->uri->segment(1) != NULL): ?>
            <a id="back_btn_md" class="nav-link d-none d-md-block" href="#" data-turbolinks="false"><i class="material-icons">&#xe5c4;</i> <?php echo lang('H_BACK');?></a>
          </li>
          <?php endif; ?>
          <?php if($this->session->status == NULL): ?>
          <li class="nav-item<?php echo '/'.$this->uri->uri_string() == '/guest/login' ? ' active' : ''?>">
            <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/guest/login' ? ' text-primary' : ''?>" href="/guest/login"><i class="material-icons">&#xe879;</i> <?php echo lang('H_LOGIN');?></a>
          </li>
          <li class="nav-item<?php echo '/'.$this->uri->uri_string() == '/guest/register' ? ' active' : ''?>">
            <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/guest/register' ? ' text-primary' : ''?>" href="/guest/register"><i class="material-icons">&#xe7fe;</i> <?php echo lang('H_REGISTER');?></a>
          </li>
          <li class="nav-item<?php echo '/'.$this->uri->uri_string() == '/guest/forgot-password' ? ' active' : ''?>">
            <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/guest/forgot-password' ? ' text-primary' : ''?>" href="/guest/forgot-password"><i class="material-icons">&#xe898;</i> <?php echo lang('H_FORGOT_PASSWORD');?></a>
          </li>
          <li class="nav-item<?php echo '/'.$this->uri->uri_string() == '/guest/activate-account' ? ' active' : ''?>">
            <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/guest/activate-account' ? ' text-primary' : ''?>" href="/guest/activate-account"><i class="material-icons">&#xe8e8;</i> <?php echo lang('H_ACTIVATE_ACCOUNT');?></a>
          </li>
          <?php endif; ?>
          <?php if($this->session->status == TRUE): ?>
          <li class="nav-item">
            <a id="avatar_pic" class="nav-link" href="#" data-turbolinks="false">
              <img class="rounded-circle avatar" src="<?php echo $this->session->user['avatar']?>"/>
              <?php echo $this->session->user['username']?>
            </a>
          </li>
          <li class="nav-item<?php echo '/'.$this->uri->uri_string() == '/auth/update-password' ? ' active' : ''?>">
            <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/guest/update-password' ? ' text-primary' : ''?>" href="/auth/update-password"><i class="material-icons">&#xe62f;</i> <?php echo lang('H_UPDATE_PASSWORD');?></a>
          </li>
          <li class="nav-item">
            <a id="logout_btn" class="nav-link" href="#" data-turbolinks="false"><i class="material-icons">&#xe879;</i> <?php echo lang('H_LOGOUT');?></a>
          </li>
          <?php endif; ?>
          <!--
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="#">Action</a>
              <a class="dropdown-item" href="#">Another action</a>
              <a class="dropdown-item" href="#">Something else here</a>
            </div>
          </li>
          -->
        </ul>
      </div>
    </nav>
    <?php if($this->session->__notification): ?>
    <div class="text-sm-center alert alert-<?php echo $this->session->__notification['type'] ?> alert-dismissible fade show" role="alert" style="margin-top:-23px;border-radius:0;">
      <?php echo $this->session->__notification['message'] ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php endif; ?>
    <main role="main" class="container">
