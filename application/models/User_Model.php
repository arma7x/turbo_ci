<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends MY_Model {

	public $table = 'users';

	public function get_user_list($base_url, $per_page, $num_links, $page_num) {
		$skip = $this->skip($page_num, $per_page);
		$filter = array(
			'keyword' => 'arma7x', //id, username, email
			'role' => null,
			'access_level' => null,
			'status' => null,
		);
		$total_rows = $this->db->count_all_results($this->table);
		$select = 'id, username, email, role, access_level, status, avatar,created_at, updated_at, last_logged_in';
		$this->db->select($select);
		foreach($filter as $index => $value) {
			if ($value !== NULL) {
				if ($index === 'keyword') {
					$this->db->group_start();
					$this->db->like('id', $value);
					$this->db->or_like('username', $value);
					$this->db->or_like('email', $value);
					$this->db->group_end();
				} else {
					$this->db->group_start();
					$this->db->where($index, $value);
					$this->db->group_end();
				}
			}
		}
		$this->db->limit($per_page, $skip);
		$this->db->order_by('role', 'ASC');
		$this->db->order_by('access_level', 'ASC');
		$this->db->order_by('status', 'ASC');
		$result = $this->db->get($this->table)->result_array();
		$this->paginate($base_url, $total_rows, $per_page, $num_links);
		return $result;
	}
}
