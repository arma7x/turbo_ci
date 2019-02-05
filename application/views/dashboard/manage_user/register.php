<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container starter-template">
  <form>
    <h2 class="mb-3 text-center text-primary text-uppercase"><?php echo isset($page_name) ? $page_name : 'Codeigniter' ;?></h2>
    <div class="row">
        <div class="col col-12 col-sm-6">
    <div class="form-group">
      <label for="inputUsername" class="sr-only"><?php echo lang('L_USERNAME');?></label>
      <div id="inputUsernameError" class="input-group border rounded">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="material-icons">&#xe7fd;</i></span>
        </div>
        <input type="text" id="inputUsername" class="form-control" placeholder="<?php echo lang('L_USERNAME');?>" required>
      </div>
      <div id="inputUsernameErrorText" class="form-control-feedback text-danger"></div>
    </div>
    
    <div class="form-group">
      <label for="inputEmail" class="sr-only"><?php echo lang('L_EMAIL');?></label>
      <div id="inputEmailError" class="input-group border rounded">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="material-icons">&#xe0be;</i></span>
        </div>
        <input type="email" id="inputEmail" class="form-control" placeholder="<?php echo lang('L_EMAIL');?>" required>
      </div>
      <div id="inputEmailErrorText" class="form-control-feedback text-danger"></div>
    </div>
    
    <div class="form-group">
      <label for="inputPassword" class="sr-only"><?php echo lang('L_PASSWORD');?></label>
      <div id="inputPasswordError" class="input-group border rounded">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="material-icons">&#xe0da;</i></span>
        </div>
        <input type="password" id="inputPassword" class="form-control" placeholder="<?php echo lang('L_PASSWORD');?>" required>
      </div>
      <div id="inputPasswordErrorText" class="form-control-feedback text-danger"></div>
    </div>
    
    <div class="form-group">
      <label for="inputConfirmPassword" class="sr-only"><?php echo lang('L_CONFIRM_PASSWORD');?></label>
      <div id="inputConfirmPasswordError" class="input-group border rounded">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="material-icons">&#xe0da;</i></span>
        </div>
        <input type="password" id="inputConfirmPassword" class="form-control" placeholder="<?php echo lang('L_CONFIRM_PASSWORD');?>" required>
      </div>
      <div id="inputConfirmPasswordErrorText" class="form-control-feedback text-danger"></div>
    </div>
        </div>
        <div class="col col-12 col-sm-6">
    <div class="form-group">
      <label for="inputRole" class="sr-only"><?php echo lang('L_ROLE');?></label>
      <div id="inputRoleError" class="input-group border rounded">
        <div class="input-group-prepend">
            <div class="input-group-text"><i class="material-icons">&#xe8d3;</i></div>
        </div>
        <select id="inputRole" class="form-control" required>
            <option value="" selected><?php echo lang('L_ROLE');?></option>
            <option value="0"><?php echo lang('L_ADMIN');?></option>
            <option value="1"><?php echo lang('L_MODERATOR');?></option>
            <option value="127"><?php echo lang('L_MEMBER');?></option>
        </select>
      </div>
      <div id="inputRoleErrorText" class="form-control-feedback text-danger"></div>
    </div>

    <div class="form-group">
      <label for="inputAccessLevel" class="sr-only"><?php echo lang('L_ROLE');?></label>
      <div id="inputAccessLevelError" class="input-group border rounded">
        <div class="input-group-prepend">
            <div class="input-group-text"><i class="material-icons">&#xe565;</i></div>
        </div>
        <select id="inputAccessLevel" class="form-control" required>
            <option value="" selected><?php echo lang('L_ACCESS_LEVEL');?></option>
            <option value="0"><?php echo lang('L_READ').'|'.lang('L_WRITE').'|'.lang('L_MODIFY');?></option>
            <option value="1"><?php echo lang('L_READ').'|'.lang('L_WRITE');?></option>
            <option value="127"><?php echo lang('L_LIMITED');?></option>
        </select>
      </div>
      <div id="inputAccessLevelErrorText" class="form-control-feedback text-danger"></div>
    </div>

    <div class="form-group">
      <label for="inputStatus" class="sr-only"><?php echo lang('L_ROLE');?></label>
      <div id="inputStatusError" class="input-group border rounded">
        <div class="input-group-prepend">
            <div class="input-group-text"><i class="material-icons">&#xe8e8;</i></div>
        </div>
        <select id="inputStatus" class="form-control" required>
            <option value="" selected><?php echo lang('L_STATUS');?></option>
            <option value="-1"><?php echo lang('L_BAN');?></option>
            <option value="0"><?php echo lang('L_INACTIVE');?></option>
            <option value="1"><?php echo lang('L_ACTIVE');?></option>
        </select>
      </div>
      <div id="inputStatusErrorText" class="form-control-feedback text-danger"></div>
    </div>

    <div class="form-group">
        <button id="add_user_btn" onclick="addUser()" class="btn btn-primary btn-block" type="submit"><?php echo lang('BTN_REGISTER');?></button>
    </div>
        </div>
    </div>
  </form>
</div>
