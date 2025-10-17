<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_contract extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_ord_contract($limit, $start, $search, $order_by, $order_dir)
	{
		// Kolom valid buat sorting (hindari SQL injection)
		$valid_columns = [
			'b.budget_id',
			'b.customer_id',
			'b.project_name',
			'b.total_budget',
			'b.star_timeline',
			'b.end_timeline',
			'b.status',
			'b.created_at',
			'c.name' // nama customer
		];
		if (!in_array($order_by, $valid_columns)) {
			$order_by = 'b.created_at';
		}
		$order_dir = (strtolower($order_dir) === 'asc') ? 'ASC' : 'DESC';

		$search = $this->db->escape_like_str($search);

		// Query utama (JOIN ke item & customer)
		$sql = "
		SELECT 
			b.*,
			c.name AS customer_name,
  			FROM ord_contract b
			LEFT JOIN ord_customer_budget f ON b.budget_id = f.budget_id AND f.customer_id = b.customer_id
 			LEFT JOIN ord_customer c ON b.customer_id = c.customer_id
			WHERE 
				(b.start_date LIKE '%{$search}%'
				OR b.end_date LIKE '%{$search}%'
				OR b.status LIKE '%{$search}%'
				OR f.project_name LIKE '%{$search}%'
				OR c.name LIKE '%{$search}%')
		GROUP BY b.contract_id
		ORDER BY {$order_by} {$order_dir}
		LIMIT {$start}, {$limit}
	";

		$query = $this->db->query($sql);
		return $query->result();
	}

	public function count_all_ord_contract()
	{
		$sql = "SELECT COUNT(*) AS total FROM ord_contract";
		return $this->db->query($sql)->row()->total;
	}

	public function count_filtered_ord_contract($search)
	{
		$search = $this->db->escape_like_str($search);
		$sql = "
		SELECT COUNT(DISTINCT b.budget_id) AS total
		FROM ord_contract b
		LEFT JOIN ord_customer_budget f ON b.budget_id = f.budget_id AND f.customer_id = b.customer_id
 		LEFT JOIN ord_customer c ON b.customer_id = c.customer_id
		WHERE 
			(b.start_date LIKE '%{$search}%'
			OR b.end_date LIKE '%{$search}%'
			OR b.status LIKE '%{$search}%'
			OR f.project_name LIKE '%{$search}%'
			OR c.name LIKE '%{$search}%')
	";
		return $this->db->query($sql)->row()->total;
	}
}
