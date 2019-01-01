<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="row justify-content-sm-center align-items-center" style="min-height:60vh;">
  <form class="form-center">
    <h1 class="h3 mb-3 font-weight-normal text-uppercase text-center"><?php echo $page_name ? $page_name : 'Codeigniter' ;?></h1>
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
    <button id="frgt_pswd_btn" class="btn btn-primary btn-block" type="submit"><?php echo lang('BTN_FORGOT_PASSWORD');?></button>
    <div id="formMessage" class="alert alert-danger fade show mt-1 sr-only" role="alert"></div>
  </form>
</div>
