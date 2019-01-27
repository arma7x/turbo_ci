<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container starter-template">
	<div class="row">
		<div class="col col-12 col-sm-8">
			<div class="card shadow-sm mb-2">
				<div class="card-body">
					<h1 class="card-title text-center text-primary"><?php echo isset($page_name) ? $page_name : 'Codeigniter' ;?></h1>
					<p><span class="text-primary">=></span> The page you are looking at is being generated dynamically by CodeIgniter.</p>
					<p><span class="text-primary">=></span> If you would like to edit this page you'll find it located at: <code>application/views/welcome_message.php</code></p>
					<p><span class="text-primary">=></span> The corresponding controller for this page is found at: <code>application/controllers/Welcome.php</code></p>
					<p><span class="text-primary">=></span> Fork <?php echo APP_NAME ?> on <a href="https://github.com/arma7x/turbo_ci" target="_blank" rel="noopener">Github</a> OR if you are exploring CodeIgniter for the very first time, you should start by reading the <a href="https://codeigniter.com/user_guide/" target="_blank" rel="noopener">User Guide</a></p>
				</div>
			</div>
		</div>
		<div class="col col-12 col-sm-4">
			<div class="card shadow-sm mb-2">
				<div class="card-body">
					<div class="mb-3">
						<h5 class="text-primary">Demo <?php echo lang('L_MODERATOR') ?></h5>
						<code><?php echo lang('L_EMAIL') ?>:</code></br>
						ahmadmuhamad101@gmail.com</br>
						<code><?php echo lang('L_PASSWORD') ?>:</code></br>
						1111111111 (1x10)
					</div>
					<hr>
					<div>
						<h5 class="text-primary">Demo <?php echo lang('L_MEMBER') ?></h5>
						<code><?php echo lang('L_EMAIL') ?>:</code></br>
						arma7x@live.com</br>
						<code><?php echo lang('L_PASSWORD') ?>:</code></br>
						1111111111 (1x10)
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col col-12 col-sm-3 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<blockquote class="blockquote">
					  <p class="mb-0 text-primary">Written in PHP</p>
					  <footer class="blockquote-footer">CodeIgniter is a powerful PHP framework with a very small footprint.</footer>
					</blockquote>
				</div>
			</div>
		</div>
		<div class="col col-12 col-sm-3 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<blockquote class="blockquote">
					  <p class="mb-0 text-primary">Utilize Javascript</p>
					  <footer class="blockquote-footer">JavaScript to program the behavior of web pages and handle user interaction.</footer>
					</blockquote>
				</div>
			</div>
		</div>
		<div class="col col-12 col-sm-3 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<blockquote class="blockquote">
					  <p class="mb-0 text-primary">CSS Styling</p>
					  <footer class="blockquote-footer">Bootstrap is the most popular HTML, CSS, and JavaScript framework.</footer>
					</blockquote>
				</div>
			</div>
		</div>
		<div class="col col-12 col-sm-3 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<blockquote class="blockquote">
					  <p class="mb-0 text-primary">Fast Navigation</p>
					  <footer class="blockquote-footer">Turbolinks® makes navigating your web application faster.</footer>
					</blockquote>
				</div>
			</div>
		</div>
	</div>
</div>
