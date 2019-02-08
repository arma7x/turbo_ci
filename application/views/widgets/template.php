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
  <body>
    <?php echo isset($nav) ? $nav : null ?>
    <?php echo isset($notification) ? $notification : null ?>
    <main id="main" role="main">
    <?php if($this->container['user'] !== NULL): ?>
    <input id="upload-avatar" class="sr-only" type="file" accept="image/*" onChange="resizePicture('upload-avatar', null, 100, 100, .50, 'image/webp', uploadAvatar, null)"/>
    <?php endif; ?>
    <?php echo isset($content) ? $content : null ?>
    </main>
    <?php echo isset($spinner) ? $spinner : null ?>
    <footer class="footer bg-light border-top border-primary">
      <div class="container text-sm-right">
        <span class="text-dark small"><?php echo str_replace('%s', $this->benchmark->elapsed_time(), lang('L_F_RENDER_ELAPSED')).'|'.$this->benchmark->memory_usage().'|'.strtoupper(ENVIRONMENT) ?></span>
      </div>
    </footer>
    <?php echo isset($footer) ? $footer : null ?>
  </body>
</html>

