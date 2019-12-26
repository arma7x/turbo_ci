<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container">
<div class="row justify-content-sm-center align-items-center" style="min-height:60vh;">
  <div class="<?php echo '/'.$this->uri->uri_string() == '/authentication/login' ? 'col-sm-5' : ''?>">
  <form class="form-center">
    <h2 class="mb-3 text-uppercase text-center text-primary"><?php echo isset($page_name) ? $page_name : 'Codeigniter' ;?></h2>
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
    <button id="lgn_btn" onclick="login(<?php echo '/'.$this->uri->uri_string() == '/authentication/login' ? 'true' : 'false'?>)" class="btn btn-primary btn-block mb-1" type="submit"><?php echo lang('BTN_LOGIN');?></button>
  </form>
  </div>
</div>
</div>
