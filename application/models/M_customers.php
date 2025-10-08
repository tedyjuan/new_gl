<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_customers extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_ord_customer($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->like('name', $search);
		$this->db->or_like('customer_id', $search);
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir); // Sorting berdasarkan kolom dan arah

		$query = $this->db->get('ord_customer');
		return $query->result();
	}
	// Fungsi untuk menghitung total data
	public function count_all_ord_customer()
	{
		return $this->db->count_all('ord_customer');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_ord_customer($search)
	{
		$this->db->like('name', $search);
		$this->db->or_like('customer_id', $search);
		$query = $this->db->get('ord_customer');
		return $query->num_rows();
	}
}
