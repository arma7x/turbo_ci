<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="row justify-content-sm-center align-items-center" style="min-height:60vh;">
  <form class="form-center">
    <h1 class="h3 mb-3 font-weight-normal text-uppercase text-center"><?php echo $page_name ? $page_name : 'Codeigniter' ;?></h1>
    <input id="username" name="username" type="text" class="sr-only" value="<?php echo $user['email'] ?>">
    <div class="form-group">
      <label for="inputNewPassword" class="sr-only"><?php echo lang('L_NEW_PASSWORD');?></label>
      <div id="inputNewPasswordError" class="input-group border rounded">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="material-icons">&#xe0da;</i></span>
        </div>
        <input type="password" id="inputNewPassword" class="form-control" placeholder="<?php echo lang('L_NEW_PASSWORD');?>" required>
      </div>
      <div id="inputNewPasswordErrorText" class="form-control-feedback text-danger"></div>
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
    <button id="rst_btn" class="btn btn-primary btn-block" type="submit"><?php echo lang('BTN_RESET_PASSWORD');?></button>
    <div id="formMessage" class="alert alert-danger fade show mt-1 sr-only" role="alert"></div>
  </form>
</div>
