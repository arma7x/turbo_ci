<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Src extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->AllowGetRequest();
	}

	public function css() {
		$this->_renderCSS(array('src/css/app.css'));
	}

	public function js() {
		$this->_renderJS(array('src/js/app.js'));
	}

	public function sw() {
		$this->_renderJS(array('src/js/sw.js'));
	}

	public function manifest() {
		$this->data = array(
			"name" => $this->container['app_name'],
			"short_name" => $this->container['app_name'],
			"icons" => array(
				array(
					"src" => "/static/img/android-chrome-192x192.png",
					"sizes" => "192x192",
					"type" => "image/png"
				),
				array(
					"src" => "/static/img/android-chrome-512x512.png",
					"sizes" => "512x512",
					"type" => "image/png"
				),
			),
			"theme_color" => "#ffffff",
			"background_color" => "#ffffff",
			"start_url" => "/",
			"display" => "standalone",
			"orientation" => "portrait"
		);
		$this->_renderJSON();
	}

	public function user() {
		$this->_renderJS(array('src/js/user.js'));
	}

}
