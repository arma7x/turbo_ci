<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="starter-template">
	<h1>Welcome to <?php echo $page_name ? $page_name : 'Codeigniter' ;?>!</h1>
	<div>
		<?php var_dump($user_list); ?>
		<?php echo $this->pagination->create_links(); ?>
	</div>
</div>
