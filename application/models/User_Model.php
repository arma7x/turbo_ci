<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Model extends MY_Model {

	public $table = 'users';

	public function get_user_list($filter, $base_url, $per_page, $page_num, $num_links) {
		$total_rows = $this->get_total_row($filter);
		$skip = $this->skip($per_page, $page_num);
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

		return $this->generate($this->db->get($this->table)->result_array(), $base_url, $per_page, $page_num, $total_rows, $skip, $num_links);
	}

	public function get_total_row($filter) {
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
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
}
