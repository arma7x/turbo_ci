<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Error</title>
<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
<link href="/welcome/css" type="text/css" rel="stylesheet">
<script src="/assets/js/turbolinks.js"></script>
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
