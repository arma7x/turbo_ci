<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container starter-template">
	<h3 class="text-center"><?php echo isset($page_name) ? $page_name : 'Codeigniter' ;?></h3>
	<div class="table-responsive">
		<table id="token_list" class="table table-sm table-bordered">
			<thead>
			<tr>
				<th scope="col">ID</th>
				<th scope="col"><?php echo lang('L_USER_AGENT');?></th>
				<th scope="col"><?php echo lang('L_LAST_USED');?></th>
				<th scope="col"><?php echo lang('L_ACTION');?></th>
			</tr>
			</thead>
			<tbody class="small">
			<?php foreach($token_list as $index => $token): ?>
			<tr<?php echo $current_token === $token['id'] ? ' class="table-primary"' : ''; ?>>
				<th scope="row"><?php echo $token['id'] ?></th>
				<td><?php echo $token['user_agent'] ?></td>
				<td>
					<span id="<?php echo $token['id'] ?>">
						<script>parseDate('<?php echo $token['id'] ?>', '<?php echo $token['last_used'] ?>')</script>
					</span>
				<td>
					<button class="btn btn-sm btn-danger<?php echo $current_token !== $token['id'] ? ' enabled' : ''; ?>"<?php echo $current_token === $token['id'] ? ' disabled' : ''; ?> onclick="deleteToken(<?php echo $current_token === $token['id'] ? 'null' : "'".$token['id']."'" ; ?>)">
						<?php echo lang('BTN_REMOVE');?>
					</button>
				</td>
			</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
