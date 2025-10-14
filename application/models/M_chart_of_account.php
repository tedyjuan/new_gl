<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_chart_of_account extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function get_paginated_chart_of_account($limit, $start, $search, $order_by, $order_dir)
	{
		$this->db->select('a.*, b.name AS company_name');
		$this->db->from('chart_of_accounts as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		// pencarian
		if (!empty($search)) {
			$this->db->group_start()
				->like('a.name', $search)
				->or_like('a.account_number', $search)
				->or_like('b.name', $search)
				->group_end();
		}

		// limit & order
		$this->db->limit($limit, $start);
		$this->db->order_by($order_by, $order_dir);

		$query = $this->db->get();
		return $query->result();
	}

	// Fungsi untuk menghitung total data
	public function count_all_chart_of_account()
	{
		return $this->db->count_all('chart_of_accounts');
	}
	// Fungsi untuk menghitung jumlah data yang difilter berdasarkan pencarian
	public function count_filtered_chart_of_account($search)
	{
		$this->db->like('name', $search);
		$this->db->or_like('account_number', $search); 
		$this->db->or_like('name', $search); 
		$this->db->or_like('code_company', $search); 
		$this->db->or_like('account_type', $search); 
		$query = $this->db->get('chart_of_accounts');
		return $query->num_rows();
	}
	public function get_where_chart_of_account($param)
	{
		$this->db->select('a.*, b.name AS nm_company');
		$this->db->from('chart_of_accounts as a');
		$this->db->join('companies as b', 'b.code_company = a.code_company', 'left');
		$this->db->where($param);
		return $this->db->get();
	}
	public function get_tbag1Bycompany($code_company, $akun_type = null)
	{
		$this->db->select('account_type, code_trialbalance1, description, code_company');
		$this->db->from('trial_balance_account_group_1');
		$this->db->where('code_company', $code_company);
		if($akun_type == null){
			// hanya menampilkan type akun
			$this->db->group_by('account_type');
		}else{
			// hanya menampilkan ke tbg1 by akun spesifix
			$this->db->where('account_type', $akun_type);
			$this->db->group_by('account_type, code_trialbalance1, description, code_company');
		}
		$this->db->order_by('account_type', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_tbag2Bycompany($code_company, $tbag1)
	{
		$this->db->select('code_trialbalance2, description');
		$this->db->from('trial_balance_account_group_2');
		$this->db->where('code_company', $code_company);
		$this->db->where('code_trialbalance1', $tbag1);
		$this->db->order_by('code_trialbalance2', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	public function get_tbag3Bycompany($code_company, $tbag2)
	{
		$this->db->select('code_trialbalance3, description');
		$this->db->from('trial_balance_account_group_3');
		$this->db->where('code_company', $code_company);
		$this->db->where('code_trialbalance2', $tbag2);
		$this->db->order_by('code_trialbalance3', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}
	
}
