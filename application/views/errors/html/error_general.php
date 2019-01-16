<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta name="turbolinks-cache-control" content="no-cache">
<meta charset="utf-8">
<title>Error</title>
<link href="/static/css/bootstrap.min.css" rel="stylesheet">
<link href="/src/app.css" type="text/css" rel="stylesheet">
<script src="/static/js/turbolinks.js"></script>
</head>
<body>
	<div class="container">
	  <div class="row align-items-center">
		<div class="col col-sm-8 offset-sm-2">
			<div class="card shadow-sm">
				<div class="card-body text-center">
					<h1><i class="material-icons text-danger" style="font-size:5em;">&#xe002;</i></h1>
					<h5 class="card-title"><?php echo $heading; ?></h5>
					<p class="card-text"><?php echo $message; ?></p>
					<a href="<?php echo config_item('base_url') ?>" class="card-link">Homepage</a>
				</div>
			</div>
		</div>
	  </div>
	</div>
</body>
</html>
