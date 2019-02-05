<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container starter-template">
	<h2 class="text-center text-primary"><?php echo $page_name ? $page_name : 'Codeigniter' ;?></h2>
	<div class="row">
		<form class="form-inline">
			<div class="input-group input-group-sm mr-sm-1 mb-1">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="material-icons">&#xe312;</i></div>
				</div>
				<input type="text" id="keyword" placeholder="<?php echo lang('L_KEYWORD');?>" class="form-control" value="<?php echo $filter['keyword'] !== NULL ? $filter['keyword'] : '' ?>">
			</div>
			<div class="input-group input-group-sm mr-sm-1 mb-1">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="material-icons">&#xe8d3;</i></div>
				</div>
				<select id="role" class="form-control">
					<option value=""<?php echo $filter['role'] === NULL ? ' selected' : '' ?>><?php echo lang('L_ROLE');?></option>
					<option value="0"<?php echo $filter['role'] === 0 ? ' selected' : '' ?>><?php echo lang('L_ADMIN');?></option>
					<option value="1"<?php echo $filter['role'] === 1 ? ' selected' : '' ?>><?php echo lang('L_MODERATOR');?></option>
					<option value="127"<?php echo $filter['role'] === 127 ? ' selected' : '' ?>><?php echo lang('L_MEMBER');?></option>
				</select>
			</div>
			<div class="input-group input-group-sm mr-sm-1 mb-1">
				<div class="input-group-prepend">
					<div class="input-group-text"><i class="material-icons">&#xe565;</i></div>
				</div>
				<select id="access_level" class="form-control">
					<option value=""<?php echo $filter['access_level'] === NULL ? ' selected' : '' ?>><?php echo lang('L_ACCESS_LEVEL');?></option>
					<option value="0"<?php echo $filter['access_level'] === 0 ? ' selected' : '' ?>><?php echo lang('L_READ').'|'.lang('L_WRITE').'|'.lang('L_MODIFY');?></option>
					<option value="1"<?php echo $filter['access_level'] === 1 ? ' selected' : '' ?>><?php echo lang('L_READ').'|'.lang('L_WRITE');?></option>
					<option value="127"<?php echo $filter['access_level'] === 127 ? ' selected' : '' ?>><?php echo lang('L_LIMITED');?></option>
				</select>
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
			<div class="input-group mr-sm-1 mb-2">
				<button type="submit" onclick="searchUser()" class="btn btn-block btn-outline-primary btn-sm"><i class="material-icons">&#xe8b6;</i> <?php echo lang('BTN_SEARCH'); ?></button>
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
						<th scope="col"><?php echo lang('L_CREDENTIAL');?></th>
						<th scope="col"><?php echo lang('L_ROLE');?></th>
						<th scope="col"><?php echo lang('L_ACCESS_LEVEL');?></th>
						<th scope="col"><?php echo lang('L_STATUS');?></th>
						<th scope="col"><?php echo lang('L_INFO');?></th>
						<?php if ((int) $this->container->user['role'] === 0): ?>
						<th scope="col"><?php echo lang('L_ACTION');?></th>
						<?php endif ?>
					</tr>
				</thead>
				<tbody class="small">
				<?php foreach($user_list as $index => $user): ?>
					<tr<?php echo $this->container->user['id'] === $user['id'] ? ' class="table-primary"' : ''; ?>>
						<th scope="row"><?php echo $user['id'] ?></th>
						<td><img class="rounded-circle avatar" style="width:50px;height:50px;" src="<?php echo $user['avatar'] ?>"/></td>
						<td>
							<b><?php echo lang('L_USERNAME');?></b></br>
							<?php echo $user['username']; ?></br>
							<b><?php echo lang('L_EMAIL');?></b></br>
							<?php echo $user['email']; ?>
						</td>
						<td>
							<form class="form">
								<div class="input-group input-group-sm mb-1">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="material-icons">&#xe8d3;</i></div>
									</div>
									<select id="role_<?php echo $user['id'] ?>" class="form-control"<?php echo $this->container->user['id'] === $user['id'] || (int) $this->container->user['role'] !== 0 ? ' disabled' : ''; ?>>
										<option value="0"<?php echo (int) $user['role'] === 0 ? ' selected' : '' ?>><?php echo lang('L_ADMIN');?></option>
										<option value="1"<?php echo (int) $user['role'] === 1 ? ' selected' : '' ?>><?php echo lang('L_MODERATOR');?></option>
										<option value="127"<?php echo (int) $user['role'] === 127 ? ' selected' : '' ?>><?php echo lang('L_MEMBER');?></option>
									</select>
								</div>
								<?php if ((int) $this->container->user['role'] === 0): ?>
								<div class="input-group mb-1">
									<button <?php echo $this->container->user['id'] === $user['id'] ? ' disabled' : ''; ?> type="submit" onclick="updateRole('<?php echo $user['id'] ?>')" class="btn btn-block btn-success btn-sm<?php echo $this->container->user['id'] !== $user['id'] ? ' enabled' : ''; ?>"><?php echo lang('BTN_UPDATE_ROLE'); ?></button>
								</div>
								<?php endif ?>
							</form>
						</td>
						<td>
							<form class="form">
								<div class="input-group input-group-sm mb-1">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="material-icons">&#xe565;</i></div>
									</div>
									<select id="access_level_<?php echo $user['id'] ?>" class="form-control"<?php echo $this->container->user['id'] === $user['id'] || (int) $this->container->user['role'] !== 0 ? ' disabled' : ''; ?>>
										<option value="0"<?php echo (int) $user['access_level'] === 0 ? ' selected' : '' ?>><?php echo lang('L_READ').'|'.lang('L_WRITE').'|'.lang('L_MODIFY');?></option>
										<option value="1"<?php echo (int) $user['access_level'] === 1 ? ' selected' : '' ?>><?php echo lang('L_READ').'|'.lang('L_WRITE');?></option>
										<option value="127"<?php echo (int) $user['access_level'] === 127 ? ' selected' : '' ?>><?php echo lang('L_LIMITED');?></option>
									</select>
								</div>
								<?php if ((int) $this->container->user['role'] === 0): ?>
								<div class="input-group mb-1">
									<button <?php echo $this->container->user['id'] === $user['id'] ? ' disabled' : ''; ?> type="submit" onclick="updateAccessLevel('<?php echo $user['id'] ?>')" class="btn btn-block btn-info btn-sm<?php echo $this->container->user['id'] !== $user['id'] ? ' enabled' : ''; ?>"><?php echo lang('BTN_UPDATE_ACCESS_LEVEL'); ?></button>
								</div>
								<?php endif ?>
							</form>
						</td>
						<td>
							<form class="form">
								<div class="input-group input-group-sm mb-1">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="material-icons">&#xe8e8;</i></div>
									</div>
									<select id="status_<?php echo $user['id'] ?>" class="form-control"<?php echo $this->container->user['id'] === $user['id'] || (int) $this->container->user['role'] !== 0 ? ' disabled' : ''; ?>>
										<option value="-1"<?php echo (int) $user['status'] === -1 ? ' selected' : '' ?>><?php echo lang('L_BAN');?></option>
										<option value="0"<?php echo (int) $user['status'] === 0 ? ' selected' : '' ?>><?php echo lang('L_INACTIVE');?></option>
										<option value="1"<?php echo (int) $user['status'] === 1 ? ' selected' : '' ?>><?php echo lang('L_ACTIVE');?></option>
									</select>
								</div>
								<?php if ((int) $this->container->user['role'] === 0): ?>
								<div class="input-group mb-1">
									<button <?php echo $this->container->user['id'] === $user['id'] ? ' disabled' : ''; ?> type="submit" onclick="updateStatus('<?php echo $user['id'] ?>')" class="btn btn-block btn-warning btn-sm text-white<?php echo $this->container->user['id'] !== $user['id'] ? ' enabled' : ''; ?>"><?php echo lang('BTN_UPDATE_STATUS'); ?></button>
								</div>
								<?php endif ?>
							</form>
						</td>
						<td>
							<b><?php echo lang('L_CREATED_AT');?></b></br>
					<span id="<?php echo 'c_a_'.$user['id'] ?>">
						<script>parse_date('<?php echo "c_a_".$user['id'] ?>', '<?php echo $user['created_at'] ?>')</script>
					</span></br>
							<b><?php echo lang('L_UPDATED_AT');?></b></br>
					<span id="<?php echo 'l_u_'.$user['id'] ?>">
						<script>parse_date('<?php echo "l_u_".$user['id'] ?>', '<?php echo $user['updated_at'] ?>')</script>
					</span></br>
							<b><?php echo lang('L_LAST_LOGGED_IN');?></b></br>
					<span id="<?php echo 'l_l_'.$user['id'] ?>">
						<script>parse_date('<?php echo "l_l_".$user['id'] ?>', '<?php echo $user['last_logged_in'] ?>')</script>
					</span>
						</td>
						<?php if ((int) $this->container->user['role'] === 0): ?>
						<td>
							<button <?php echo $this->container->user['id'] === $user['id'] ? ' disabled' : ''; ?> onclick="deleteUser(<?php echo $this->container->user['id'] === $user['id'] ? 'null' : "'".$user['id']."'" ; ?>)" class="btn btn-block btn-sm btn-danger<?php echo $this->container->user['id'] !== $user['id'] ? ' enabled' : ''; ?>">
								<?php echo lang('BTN_REMOVE');?>
							</button>
						</td>
						<?php endif ?>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row justify-content-sm-center align-items-center" >
	<?php echo $this->pagination->create_links(); ?>
	</div>
</div>
