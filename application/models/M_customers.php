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
		// Hindari SQL injection lewat escape
		$search   = $this->db->escape_like_str($search);
		$order_by = preg_replace('/[^a-zA-Z0-9_]/', '', $order_by); // sanitize kolom order
		$order_dir = strtoupper($order_dir) === 'DESC' ? 'DESC' : 'ASC'; // hanya ASC/DESC

		$sql = "
			SELECT oc.*, c.name AS company_name,
			FROM ord_customer oc
			LEFT JOIN companies c ON oc.company_id = c.code_company
			WHERE oc.name LIKE '%{$search}%' 
			   OR oc.customer_id LIKE '%{$search}%'
			ORDER BY oc.{$order_by} {$order_dir}
			LIMIT {$start}, {$limit}
		";

		$query = $this->db->query($sql);
		return $query->result();
	}

	public function count_all_ord_customer()
	{
		return $this->db->count_all('ord_customer');
	}

	public function count_filtered_ord_customer($search)
	{
		$search = $this->db->escape_like_str($search);

		$sql = "
			SELECT COUNT(*) AS total
			FROM ord_customer oc
			LEFT JOIN companies c ON oc.company_id = c.code_company
			WHERE oc.name LIKE '%{$search}%' 
			   OR oc.customer_id LIKE '%{$search}%'
		";

		$query = $this->db->query($sql);
		return $query->row()->total;
	}
}
