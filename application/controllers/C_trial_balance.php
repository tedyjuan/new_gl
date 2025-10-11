<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_trial_balance extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_global');
		$this->load->model('M_trial_balance');
	}
	function index()
	{
		$data['judul']      = 'List Data Divisi';
		$data['load_grid']  = 'C_trial_balance';
		$data['load_add']   = 'C_trial_balance/add';
		$data['url_delete'] = 'C_trial_balance/delete';
		$this->load->view("v_trial_balance/tbag_1/grid_tbag", $data);
	}
	public function griddata()
	{
		$start          = $this->input->post('start') ?? 0;
		$length         = $this->input->post('length') ?? 10;
		$search_input   = $this->input->post('search');
		$search         = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		$columns        = ['code_company', 'name',  'code_trialbalance1', 'description', 'account_type'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_trial_balance->get_paginated_trial_balance($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_trial_balance->count_all_trial_balance("trial_balance_account_group_1");
		$total_filtered = $this->M_trial_balance->count_filtered_trial_balance($search);
		$result = [];
		foreach ($data as $row) {
			$result[] = [
				$row->code_company . ' - ' . $row->name,
				$row->code_trialbalance1,
				$row->description,
				$row->account_type,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}
	public function tbag_2(){
		$data['judul']      = 'List Data Divisi';
		$this->load->view("v_trial_balance/tbag_2/grid_tbag", $data);
	}
	public function griddata_tbag_2()
	{
		$start          = $this->input->post('start') ?? 0;
		$length         = $this->input->post('length') ?? 10;
		$search_input   = $this->input->post('search');
		$search         = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		$columns        = ['code_company', 'name',  'code_trialbalance1', 'description', 'code_trialbalance2'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_trial_balance->get_paginated_trial_balance2($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_trial_balance->count_all_trial_balance("trial_balance_account_group_2");
		$total_filtered = $this->M_trial_balance->count_filtered_trial_balance2($search);
		$result = [];
		foreach ($data as $row) {
			$result[] = [
				$row->code_company . ' - ' . $row->name,
				$row->code_trialbalance1,
				$row->description,
				$row->code_trialbalance2,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}
	 public function tbag_3(){
		$data['judul']      = 'List Data Divisi';
		$this->load->view("v_trial_balance/tbag_3/grid_tbag", $data);
	}
	public function griddata_tbag_3()
	{
		$start          = $this->input->post('start') ?? 0;
		$length         = $this->input->post('length') ?? 10;
		$search_input   = $this->input->post('search');
		$search         = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		$columns        = ['code_company', 'name',  'code_trialbalance3', 'description', 'code_trialbalance2'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_trial_balance->get_paginated_trial_balance3($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_trial_balance->count_all_trial_balance("trial_balance_account_group_3");
		$total_filtered = $this->M_trial_balance->count_filtered_trial_balance3($search);
		$result = [];
		foreach ($data as $row) {
			$result[] = [
				$row->code_company . ' - ' . $row->name,
				$row->code_trialbalance3,
				$row->description,
				$row->code_trialbalance2,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}
}
