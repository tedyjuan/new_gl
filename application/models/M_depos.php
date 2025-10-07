<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_depos extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_depos($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->like('name', $search);
		$this->db->or_like('code_depo', $search);
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir); // Sorting berdasarkan kolom dan arah

		$query = $this->db->get('depos');
		return $query->result();
	}
	// Fungsi untuk menghitung total data
	public function count_all_depos()
	{
		return $this->db->count_all('depos');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_depos($search)
	{
		$this->db->like('name', $search);
		$this->db->or_like('code_depo', $search);
		$query = $this->db->get('depos');
		return $query->num_rows();
	}
	public function get_depos_by_uuid($uuid)
	{
		$this->db->select('name, code_depo');  // Menentukan kolom yang diambil
		$this->db->where('uuid', $uuid);         // Menentukan kondisi berdasarkan UUID
		$query = $this->db->get('depos');    // Melakukan query pada tabel 'depos'

		if ($query->num_rows() > 0) {
			return $query->row();  // Mengembalikan satu baris data jika ditemukan
		} else {
			return null;  // Mengembalikan null jika data tidak ditemukan
		}
	}
}
