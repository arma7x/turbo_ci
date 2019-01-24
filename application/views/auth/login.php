<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="row justify-content-sm-center align-items-center" style="min-height:60vh;">
  <div class="col-sm-4">
  <form class="form-center">
    <h1 class="h3 mb-3 font-weight-normal text-uppercase text-center"><?php echo isset($page_name) ? $page_name : 'Codeigniter' ;?></h1>
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
    <div class="checkbox mb-1">
      <label><input id="inputRememberMe" type="checkbox" value="true"> <?php echo lang('L_REMEMBER_ME');?></label>
    </div>
    <button id="lgn_btn" onclick="login()" class="btn btn-primary btn-block mb-1" type="submit"><?php echo lang('BTN_LOGIN');?></button>
  </form>
  </div>
</div>
