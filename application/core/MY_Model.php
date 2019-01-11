<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

	public $table;

	public function __construct() {
		parent::__construct();
	}

	final protected function paginate($base_url, $per_page, $page_num, $num_links, $total_rows) {
		$this->load->library('pagination');
		$config['full_tag_open'] = '<div class="row justify-content-sm-center align-items-center"><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div>';
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
		$config['num_links'] = $num_links === TRUE ? ($total_rows/$per_page) : FALSE;
		$config['total_rows'] = ($total_rows/$per_page);
		$config['per_page'] = 1;
		$config['page_query_string'] = TRUE;
		$config['reuse_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$this->pagination->initialize($config);
		return ($page_num <= 1 ? 0 : ($page_num - 1)) * $per_page;
	}
}
