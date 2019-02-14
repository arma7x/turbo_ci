<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

	<div class="container">
		<div class="row justify-content-center align-items-center">
			<div class="toast fade hide" role="status" aria-live="polite" aria-atomic="true" data-autohide="false">
				<div id="dangerMessage" class="text-white toast-body bg-danger"></div>
			</div>
		</div>
    </div>
    <?php if($this->session->__notification): ?>
    <div class="fixed-top text-sm-center alert alert-<?php echo $this->session->__notification['type'] ?> alert-dismissible top-alert-noround fade show" role="alert">
      <?php echo $this->session->__notification['message'] ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php endif; ?>
    <?php if ($this->container['sw_offline_cache'] !== NULL): ?>
    <div class="fixed-top text-sm-center alert alert-info alert-dismissible top-alert-noround fade show" role="alert">
      <?php echo lang('M_CACHE_CONTENT'); ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php endif; ?>
