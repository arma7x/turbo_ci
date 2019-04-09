<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container starter-template">
	<h2 class="text-center text-primary text-uppercase">
		<?php echo isset($page_name) ? $page_name : 'Codeigniter' ;?>
	</h2>
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
						<script>parse_date('<?php echo $token['id'] ?>', '<?php echo $token['last_used'] ?>')</script>
					</span>
				<td>
					<button class="btn btn-sm btn-danger<?php echo $current_token !== $token['id'] ? ' enabled' : ''; ?>"<?php echo $current_token === $token['id'] ? ' disabled' : ''; ?> onclick="promptconfirmPasswordToken(<?php echo $current_token === $token['id'] ? 'null' : "'".$token['id']."'" ; ?>)">
						<?php echo lang('BTN_REMOVE');?>
					</button>
				</td>
			</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	</div>
	<div id="confirmPasswordToken" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
			<h5 class="modal-title"><?php echo lang('L_CONFIRM_PASSWORD')?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input type="text" id="inputID" class="form-control sr-only">
					<label for="inputPassword" class="sr-only"><?php echo lang('L_PASSWORD');?></label>
					<div id="inputPasswordError" class="input-group border rounded">
						<div class="input-group-prepend"><span class="input-group-text"><i class="material-icons">&#xe0da;</i></span></div>
						<input type="password" id="inputPassword" class="form-control" placeholder="<?php echo lang('L_PASSWORD');?>">
					</div>
					<div id="inputPasswordErrorText" class="form-control-feedback text-danger"></div>
				</div>
				<button type="button" onClick="deleteToken()" class="btn btn-danger float-right"><?php echo lang('BTN_REMOVE');?></button>
			</div>
		</div>
	  </div>
	</div>
</div>
