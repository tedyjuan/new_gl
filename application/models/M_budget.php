<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_budget extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_budget($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('budgetons.*, companies.name AS company_name');
		$this->db->from('budgetons');
		$this->db->join('companies', 'companies.code_company = budgetons.code_company', 'left');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('budgetons.name', $search)
				->or_like('budgetons.code_budget', $search)
				->or_like('companies.name', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_budget()
	{
		return $this->db->count_all('budgetons');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_budget($search)
	{
		$this->db->like('name', $search);
		$this->db->or_like('code_budget', $search); 
		$query = $this->db->get('budgetons');
		return $query->num_rows();
	}
	public function get_where_budget($param)
	{
		$this->db->select('a.*, b.name AS nm_company');
		$this->db->from('budgetons as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		$this->db->where($param);
		return $this->db->get();
	}
}
