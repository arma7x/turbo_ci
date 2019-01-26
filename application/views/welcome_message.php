<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container starter-template">
	<div class="row justify-content-sm-center align-items-center mb-2">
		<div class="col">
			<div class="card shadow-sm">
				<div class="card-body text-center">
					<h1 class="card-title"><?php echo isset($page_name) ? $page_name : 'Codeigniter' ;?></h1>
					<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>
					<p>If you would like to edit this page you'll find it located at:
						<code>application/views/welcome_message.php</code>
					</p>
					<p>The corresponding controller for this page is found at:
					<code>application/controllers/Welcome.php</code>
					</p>
					<p>Fork <?php echo APP_NAME ?> on <a href="https://github.com/arma7x/turbo_ci" target="_blank" rel="noopener">Github</a> OR if you are exploring CodeIgniter for the very first time, you should start by reading the <a href="https://codeigniter.com/user_guide/" target="_blank" rel="noopener">User Guide</a></p>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-sm-center align-items-center">
		<div class="col col-12 col-sm-6 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<h5 class="card-title">Demo <?php echo lang('L_MODERATOR') ?></h5>
					<code><?php echo lang('L_EMAIL') ?>: ahmadmuhamad101@gmail.com</code></br>
					<code><?php echo lang('L_PASSWORD') ?>: 1111111111 (1x10)</code>
				</div>
			</div>
		</div>
		<div class="col col-12 col-sm-6 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<h5 class="card-title">Demo <?php echo lang('L_MEMBER') ?></h5>
					<code><?php echo lang('L_EMAIL') ?>: arma7x@live.com</code></br>
					<code><?php echo lang('L_PASSWORD') ?>: 1111111111 (1x10)</code>
				</div>
			</div>
		</div>
	</div>
</div>
