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
    <meta name="apple-mobile-web-app-title" content="<?php echo APP_NAME ?>">
    <meta name="application-name" content="<?php echo APP_NAME ?>">
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
  <body>
    <nav class="navbar navbar-expand-sm navbar-light bg-light fixed-top shadow-header">
      <div>
        <?php if ($this->uri->segment(1) != NULL): ?>
        <a onclick="goBack()" class="d-sm-none navbar-brand" data-turbolinks="false"><i class="material-icons" style="font-size:1.5em;">arrow_back</i></a>
        <?php endif; ?>
        <a onclick="goHome()" class="navbar-brand" data-turbolinks="false">
            <img style="margin-top:-4px;" src="/static/img/favicon-32x32.png" alt="logo"/>
            <?php echo APP_NAME ?>
        </a>
      </div>
      <button id="navbar-toggler" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navCollapsed" aria-controls="navCollapsed" aria-expanded="false" aria-label="Toggle navigation" style="border:0;">
        <i id="navmenu_icon" class="material-icons text-dark" style="font-size:1.7em;">menu</i>
      </button>
      <div class="collapse navbar-collapse" id="navCollapsed">
        <ul class="navbar-nav ml-auto">
          <?php if ($this->uri->segment(1) != NULL): ?>
          <li class="nav-item">
            <a onclick="goBack()" class="nav-link d-none d-md-block" data-turbolinks="false"><i class="material-icons">arrow_back</i> <?php echo lang('H_BACK');?></a>
          </li>
          <?php endif; ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons">&#xe8e2;</i> <?php echo lang('L_LANGUAGE') ?></a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" onclick="change_language('english')" data-turbolinks="false"><?php echo lang('L_ENGLISH_LANG') ?></a>
              <a class="dropdown-item" onclick="change_language('malay')" data-turbolinks="false"><?php echo lang('L_MALAY_LANG') ?></a>
            </div>
          </li>
          <?php if($this->container->user === NULL): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons">&#xe887;</i> <?php echo lang('L_HELP');?></a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
                <a class="dropdown-item" onclick="navigate('/authentication/ui_activate_account')"><i class="material-icons">&#xe8e8;</i> <?php echo lang('H_ACTIVATE_ACCOUNT');?></a>
                <a class="dropdown-item" onclick="navigate('/authentication/ui_forgot_password')"><i class="material-icons">&#xe898;</i> <?php echo lang('H_FORGOT_PASSWORD');?></a>
            </div>
          </li>
          <li class="nav-item<?php echo '/'.$this->uri->uri_string() == '/authentication/ui_login' ? ' active' : ''?>">
            <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/authentication/ui_login' ? ' text-primary' : ''?>" onclick="navigate('/authentication/ui_login')"><i class="material-icons">&#xe879;</i> <?php echo lang('H_LOGIN');?></a>
          </li>
          <li class="nav-item<?php echo '/'.$this->uri->uri_string() == '/authentication/ui_register' ? ' active' : ''?>">
            <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/authentication/ui_register' ? ' text-primary' : ''?>" onclick="navigate('/authentication/ui_register')"><i class="material-icons">&#xe7fe;</i> <?php echo lang('H_REGISTER');?></a>
          </li>
          <?php endif; ?>
          <?php if($this->container->user !== NULL): ?>
          <li class="nav-item" onclick="selectPic()">
            <a id="avatar_pic" class="nav-link" data-turbolinks="false">
              <img class="rounded-circle avatar" src="<?php echo $this->container->user['avatar'] ?>"/>
              <?php echo $this->container->user['username']?>
            </a>
          </li>
          <li class="nav-item<?php echo '/'.$this->uri->uri_string() == '/authentication/ui_update_password' ? ' active' : ''?>">
            <a class="nav-link<?php echo '/'.$this->uri->uri_string() == '/authentication/ui_update_password' ? ' text-primary' : ''?>" onclick="navigate('/authentication/ui_update_password')"><i class="material-icons">&#xe62f;</i> <?php echo lang('H_UPDATE_PASSWORD');?></a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons">&#xe871;</i> <?php echo lang('H_DASHBOARD');?></a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <?php if((int) $this->container->user['role'] <= 1): ?>
              <a class="dropdown-item" onclick="navigate('/manage_user/user_list')"><i class="material-icons">&#xe7ef;</i> <?php echo lang('H_MANAGE_USERS');?></a>
              <?php endif; ?>
              <a class="dropdown-item" onclick="navigate('/authentication/manage_token')"><i class="material-icons">&#xe1b1;</i> <?php echo lang('H_LOG_IN_DEVICES');?></a>
            </div>
          </li>
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
    <div id="dangerMessage" class="fixed-top text-sm-center alert alert-danger top-alert-noround sr-only" role="alert">
    </div>
    <main id="main" role="main" class="container">
    <?php if($this->container->user !== NULL): ?>
    <input id="upload-avatar" class="sr-only" type="file" accept="image/*" onChange="processPic('upload-avatar')"/>
    <?php endif; ?>
