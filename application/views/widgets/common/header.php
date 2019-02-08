<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

    <link rel="apple-touch-icon" sizes="180x180" href="/static/img/apple-touch-icon.png">
    <link rel="icon" href="/static/img/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/static/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/static/img/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/static/img/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="<?php echo $this->container['app_name'] ?>">
    <meta name="application-name" content="<?php echo $this->container['app_name'] ?>">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="theme-color" content="#ffffff">
    <link href="/static/css/animate.min.css" type="text/css" rel="stylesheet">
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
        };
        $(document).on('turbolinks:load', function() {
            $('body').addClass('animated fadeIn');
        });
    </script>
