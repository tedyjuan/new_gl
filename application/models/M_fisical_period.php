<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_fisical_period extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_fisical_period($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('a.*');
		$this->db->from('fiscal_periods as a');
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.period', $search)
				->or_like('a.start_date', $search)
				->or_like('a.end_date', $search)
				->or_like('a.status', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_fisical_period()
	{
		return $this->db->count_all('fiscal_periods');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_fisical_period($search)
	{
		$this->db->like('period', $search);
		$this->db->or_like('status', $search); 
		$query = $this->db->get('fiscal_periods');
		return $query->num_rows();
	}
	public function get_where_fisical_period($param)
	{
		$this->db->select('a.*');
		$this->db->from('fiscal_periods as a');
		$this->db->where($param);
		return $this->db->get();
	}
}
