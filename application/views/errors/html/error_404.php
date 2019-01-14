<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta name="turbolinks-cache-control" content="no-cache">
<meta charset="utf-8">
<title>404 Page Not Found</title>
<link href="/asset/css/bootstrap.min.css" rel="stylesheet">
<link href="/src/app.css" type="text/css" rel="stylesheet">
<script src="/asset/js/turbolinks.js"></script>
</head>
<body>
	<div class="container">
		<div class="col col-12">
			<div class="card shadow-sm">
				<div class="card-body">
					<h5 class="card-title"><?php echo $heading; ?></h5>
					<p class="card-text"><?php echo $message; ?></p>
					<a href="<?php echo config_item('base_url') ?>" class="card-link">Homepage</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
