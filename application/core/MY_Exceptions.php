<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions {

	public function __construct() {
		parent::__construct();
	}

	public function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
		$message_json = (is_array($message) ? implode('. ', $message) : $message);
		$templates_path = config_item('error_views_path');
		if (empty($templates_path)) {
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		if (is_cli()) {
			$message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
			$template = 'cli'.DIRECTORY_SEPARATOR.$template;
		} else {
			set_status_header($status_code);
			$message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
			$template = 'html'.DIRECTORY_SEPARATOR.$template;
		}

		if (ob_get_level() > $this->ob_level + 1) {
			ob_end_flush();
		}
		ob_start();

		$ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

		if ($ajax) {
			header('Content-Type: application/json');
			set_status_header($status_code);
			echo json_encode(array('message' => $message_json));
		} else {
			include($templates_path.$template.'.php');
		}

		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	public function show_exception($exception) {
		$templates_path = config_item('error_views_path');
		if (empty($templates_path))
		{
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		$message = $exception->getMessage();
		if (empty($message))
		{
			$message = '(null)';
		}

		if (is_cli())
		{
			$templates_path .= 'cli'.DIRECTORY_SEPARATOR;
		}
		else
		{
			$templates_path .= 'html'.DIRECTORY_SEPARATOR;
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}

		ob_start();

		$ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

		if ($ajax) {
			header('Content-Type: application/json');
			set_status_header(500);
			echo json_encode(array(
				'type' => get_class($exception),
				'message' => $message,
				'filename' => $exception->getFile(),
				'line' => $exception->getLine()
			));
		} else {
			include($templates_path.'error_exception.php');
		}

		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
		die;
	}

	public function show_php_error($severity, $message, $filepath, $line) {
		$templates_path = config_item('error_views_path');
		if (empty($templates_path))
		{
			$templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		$severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;

		// For safety reasons we don't show the full file path in non-CLI requests
		if ( ! is_cli())
		{
			$filepath = str_replace('\\', '/', $filepath);
			if (FALSE !== strpos($filepath, '/'))
			{
				$x = explode('/', $filepath);
				$filepath = $x[count($x)-2].'/'.end($x);
			}

			$template = 'html'.DIRECTORY_SEPARATOR.'error_php';
		}
		else
		{
			$template = 'cli'.DIRECTORY_SEPARATOR.'error_php';
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();

		$ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

		if ($ajax) {
			header('Content-Type: application/json');
			set_status_header(500);
			echo json_encode(array(
				'severity' => $severity,
				'message' => $message,
				'filename' => $filepath,
				'line' => $line
			));
		} else {
			include($templates_path.$template.'.php');
		}

		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
		die;
	}

}
