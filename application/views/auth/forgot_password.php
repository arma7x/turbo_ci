<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container">
<div class="row justify-content-sm-center align-items-center" style="min-height:60vh;">
  <div class="col-sm-5">
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
    <button id="frgt_pswd_btn" onclick="forgot_password()" class="btn btn-primary btn-block" type="submit"><?php echo lang('BTN_FORGOT_PASSWORD');?></button>
  </form>
  </div>
</div>
</div>
