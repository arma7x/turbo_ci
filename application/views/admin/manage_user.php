<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container starter-template">
	<h3 class="text-center"><?php echo $page_name ? $page_name : 'Codeigniter' ;?></h3>
	<div class="row">
		<form class="form-inline mb-sm-1">
			<div class="input-group input-group-sm mr-sm-1 mb-1">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="material-icons">&#xe312;</i></div>
				</div>
				<input type="text" id="keyword" placeholder="<?php echo lang('L_KEYWORD');?>" class="form-control" value="<?php echo $filter['keyword'] !== NULL ? $filter['keyword'] : '' ?>">
			</div>
				<div class="input-group input-group-sm mr-sm-1 mb-1">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="material-icons">&#xe892;</i></div>
				</div>
				<input type="number" id="role" placeholder="<?php echo lang('L_ROLE');?>" class="form-control" value="<?php echo $filter['role'] !== NULL ? $filter['role'] : '' ?>">
			</div>
			<div class="input-group input-group-sm mr-sm-1 mb-1">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="material-icons">&#xe8d3;</i></div>
				</div>
				<input type="number" id="access_level" placeholder="<?php echo lang('L_ACCESS_LEVEL');?>" class="form-control" value="<?php echo $filter['access_level'] !== NULL ? $filter['access_level'] : '' ?>">
			</div>
			<div class="input-group input-group-sm mr-sm-1 mb-1">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="material-icons">&#xe8e8;</i></div>
				</div>
				<select id="status" class="form-control">
					<option value=""<?php echo $filter['status'] === NULL ? ' selected' : '' ?>><?php echo lang('L_STATUS');?></option>
					<option value="-1"<?php echo $filter['status'] === -1 ? ' selected' : '' ?>><?php echo lang('L_BAN');?></option>
					<option value="0"<?php echo $filter['status'] === 0 ? ' selected' : '' ?>><?php echo lang('L_INACTIVE');?></option>
					<option value="1"<?php echo $filter['status'] === 1 ? ' selected' : '' ?>><?php echo lang('L_ACTIVE');?></option>
				</select>
			</div>
			<div class="input-group mr-sm-1 mb-1">
				<button type="submit "onclick="search_user()" class="btn btn-block btn-outline-primary btn-sm"><i class="material-icons">&#xe8b6;</i> Search</button>
			</div>
		</form>
	</div>
	<div class="row">
		<div class="table-responsive">
			<table id="token_list" class="table table-sm table-bordered">
				<thead>
				<tr>
					<th scope="col">ID</th>
					<th scope="col"><?php echo lang('L_AVATAR');?></th>
					<th scope="col"><?php echo lang('L_USERNAME');?></th>
					<th scope="col"><?php echo lang('L_EMAIL');?></th>
					<th scope="col"><?php echo lang('L_ROLE');?></th>
					<th scope="col"><?php echo lang('L_ACCESS_LEVEL');?></th>
					<th scope="col"><?php echo lang('L_STATUS');?></th>
					<th scope="col"><?php echo lang('L_CREATED_AT');?></th>
					<th scope="col"><?php echo lang('L_UPDATED_AT');?></th>
					<th scope="col"><?php echo lang('L_LAST_LOGGED_IN');?></th>
					<th scope="col"><?php echo lang('L_ACTION');?></th>
				</tr>
				</thead>
				<tbody class="small">
				<?php foreach($user_list as $index => $user): ?>
				<tr<?php echo $this->container->user['id'] === $user['id'] ? ' class="table-primary"' : ''; ?>>
					<th scope="row"><?php echo $user['id'] ?></th>
					<th><img class="rounded-circle avatar" style="width:50px;height:50px;" src="<?php echo $user['avatar'] ?>"/></th>
					<td><?php echo $user['username'] ?></td>
					<td><?php echo $user['email'] ?></td>
					<td><?php echo $user['role'] ?></td>
					<td><?php echo $user['access_level'] ?></td>
					<td><?php echo $user['status'] ?></td>
					<td><parse-date><?php echo $user['created_at'] ?></parse-date></td>
					<td><parse-date><?php echo $user['updated_at'] ?></parse-date></td>
					<td><parse-date><?php echo $user['last_logged_in'] ?></parse-date></td>
					<td>
						<button class="btn btn-sm btn-danger<?php echo $this->container->user['id'] !== $user['id'] ? ' enabled' : ''; ?>"<?php echo $this->container->user['id'] === $user['id'] ? ' disabled' : ''; ?> onclick="deleteToken(<?php echo $this->container->user['id'] === $user['id'] ? 'null' : "'".$user['id']."'" ; ?>)">
							<?php echo lang('BTN_REMOVE');?>
						</button>
					</td>
				</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php echo $this->pagination->create_links(); ?>
	</div>
</div>
