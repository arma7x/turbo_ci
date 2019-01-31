<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container starter-template">
	<div class="row mb-3">
		<div class="col col-12 col-sm-8 mb-3">
			<div class="card shadow-sm">
				<div class="card-body">
					<h1 class="card-title text-center text-primary"><?php echo isset($page_name) ? $page_name : 'Codeigniter' ;?></h1>
					<p class="card-text"><span class="text-primary">=></span> The page you are looking at is being generated dynamically by CodeIgniter.</p>
					<p class="card-text"><span class="text-primary">=></span> If you would like to edit this page you'll find it located at: <code>application/views/welcome_message.php</code></p>
					<p class="card-text"><span class="text-primary">=></span> The corresponding controller for this page is found at: <code>application/controllers/Welcome.php</code></p>
					<p class="card-text"><span class="text-primary">=></span> Fork <strong class="text-pink"><?php echo APP_NAME ?></strong> on <a href="https://github.com/arma7x/turbo_ci" target="_blank" rel="noopener">Github</a> or if you are exploring <strong>CodeIgniter</strong> for the very first time, you should start by reading the <a href="https://codeigniter.com/user_guide/" target="_blank" rel="noopener">User Guide</a></p>
				</div>
			</div>
		</div>
		<div class="col col-12 col-sm-4 mb-3">
			<div class="card bg-light shadow-sm">
				<h5 class="card-header text-center text-primary">Try Authentication System</h5>
				<div class="card-body">
					<div>
						<h6 class="text-pink"><strong><?php echo lang('L_MODERATOR') ?></strong></h6>
						<code><?php echo lang('L_EMAIL') ?>:</code></br>
						ahmadmuhamad101@gmail.com</br>
						<code><?php echo lang('L_PASSWORD') ?>:</code> 1111111111 (1x10)
					</div>
					<hr>
					<div>
						<h6 class="text-pink"><strong><?php echo lang('L_MEMBER') ?></strong></h6>
						<code><?php echo lang('L_EMAIL') ?>:</code></br>
						arma7x@live.com</br>
						<code><?php echo lang('L_PASSWORD') ?>:</code> 1111111111 (1x10)
					</div>
				</div>
			</div>
		</div>
	</div>
	<h5 class="text-center text-primary">We make it simple and we built with powerful tools</h5>
	<div class="row justify-content-center">
		<div class="col col-12 col-sm-3 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<blockquote class="blockquote">
					  <p class="mb-0 text-pink"><strong>Written in PHP</strong></p>
					  <footer class="blockquote-footer">CodeIgniter is a powerful PHP framework with a very small footprint.</footer>
					</blockquote>
				</div>
			</div>
		</div>
		<div class="col col-12 col-sm-3 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<blockquote class="blockquote">
					  <p class="mb-0 text-pink"><strong>Utilize Javascript</strong></p>
					  <footer class="blockquote-footer">JQuery to program the behavior of web pages and handle user interaction.</footer>
					</blockquote>
				</div>
			</div>
		</div>
		<div class="col col-12 col-sm-3 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<blockquote class="blockquote">
					  <p class="mb-0 text-pink"><strong>CSS Styling</strong></p>
					  <footer class="blockquote-footer">Bootstrap is the most popular HTML, CSS, and JavaScript framework.</footer>
					</blockquote>
				</div>
			</div>
		</div>
		<div class="col col-12 col-sm-3 mb-2">
			<div class="card shadow-sm">
				<div class="card-body">
					<blockquote class="blockquote">
					  <p class="mb-0 text-pink"><strong>Fast Navigation</strong></p>
					  <footer class="blockquote-footer">Turbolinks® makes navigating your web application faster.</footer>
					</blockquote>
				</div>
			</div>
		</div>
	</div>
</div>
