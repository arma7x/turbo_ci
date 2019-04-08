<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public $table;

	public function __construct() {
		parent::__construct();
	}

	final public function skip($per_page, $page_num) {
		return ($page_num <= 1 ? 0 : ($page_num - 1)) * $per_page;
	}

	final public function generate($result, $base_url, $per_page, $page_num, $total_rows, $skip, $num_links) {
		$this->load->library('pagination');
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close'] = '</span></li>';
		$config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close'] = '</span></li>';
		$config['first_link'] = '<i class="material-icons">&#xe5dc;</i>';
		$config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['first_tag_close'] = '</span></li>';
		$config['next_link'] = '<i class="material-icons">&#xe5cc;</i>';
		$config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['next_tag_close'] = '</span></li>';
		$config['prev_link'] = '<i class="material-icons">&#xe5cb;</i>';
		$config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['prev_tag_close'] = '</span></li>';
		$config['last_link'] = '<i class="material-icons">&#xe5dd;</i>';
		$config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
		$config['last_tag_close'] = '</span></li>';
		$config['base_url'] = $base_url;
		$config['num_links'] = $num_links === TRUE ? ($total_rows/$per_page) : $num_links;
		$config['total_rows'] = ($total_rows/$per_page);
		$config['per_page'] = 1;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$this->pagination->initialize($config);

		$last_page = (int) ceil($total_rows / $per_page);
		$next_page = $page_num < $last_page && $last_page > 1 ? ($page_num === 0 ? 2 : ($page_num + 1)) : NULL;
		$previos_page = ($skip / $per_page) >= 1 ? (($skip / $per_page) - 1 === 0 ? 1 : ($skip / $per_page)) : NULL;
		$current_page = $page_num <= 1 ? 1 : $page_num;
		return array(
			'url' => $base_url,
			'current_page' => $current_page,
			'next_page' => $next_page,
			'previos_page' => $previos_page,
			'last_page' => $last_page,
			'per_page' => $per_page,
			'total_result' => $total_rows,
			'result' => $result,
		);
		
	}
}
