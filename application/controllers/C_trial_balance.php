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
		$this->load->view("v_trial_balance/tbag_2/grid_tbag");
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
		$data['load_grid']  = 'C_trial_balance/tbag_3';
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
		$columns        = ['code_company', 'name',  'code_trialbalance3', 'description', 'code_trialbalance2', 'aksi'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_trial_balance->get_paginated_trial_balance3($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_trial_balance->count_all_trial_balance("trial_balance_account_group_3");
		$total_filtered = $this->M_trial_balance->count_filtered_trial_balance3($search);
		$url_edit   = 'C_trial_balance/editform/';
		$url_delete = 'C_trial_balance/hapusdata/';
		$load_grid  = 'C_trial_balance/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_trialbalance3 . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_trialbalance3 . '">
					<button class="dropdown-item editbtn" onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-pen"></i> Edit
					</button>
					<div class="dropdown-divider"></div>
					<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
						<i class="bi bi-trash3"></i> Delete
					</button>
				</div>
			</div>';
			$result[] = [
				$row->code_company . ' - ' . $row->name,
				$row->code_trialbalance3,
				$row->description,
				$row->code_trialbalance2,
				$aksi,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}

	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('kode_tbg1', 'kode trial balance 1', 'required');
		$this->form_validation->set_rules('kode_tbg2', 'kode trial balance 2', 'required');
		$this->form_validation->set_rules('kode_tbg3', 'kode trial balance ', 'required');
		$this->form_validation->set_rules('des', 'Deskripsi', 'required');
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		// Ambil data dari request
		$perusahaan = $this->input->post('perusahaan');
		$kode_tbg2  = $this->input->post('kode_tbg2');
		$kode_tbg3  = $this->input->post('kode_tbg3');
		$des        = $this->input->post('des');
		$param_kode = [
			'code_trialbalance3'  => $kode_tbg3,
			'code_company'  => $perusahaan,
		];
		$exisCode = $this->M_global->getWhere('trial_balance_account_group_3', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode Divisi sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		
		// Data untuk insert ke database
		$datainsert = [
			'uuid'               => $this->uuid->v4(),
			'code_company'       => $perusahaan,
			'code_trialbalance3' => $kode_tbg3,
			'description'        => $des,
			'code_trialbalance2' => $kode_tbg2,
			'created_at'         => date('Y-m-d H:i:s'),
			'updated_at'         => date('Y-m-d H:i:s')
		];
		// Melakukan insert data
		$this->db->insert('trial_balance_account_group_3', $datainsert);
		if ($this->db->affected_rows() > 0) {
			$jsonmsg = [
				'hasil' => 'true',
				'pesan' => 'Data Berhasil Disimpan',
			];
		} else {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Gagal Menyimpan Data',
			];
		}
		echo json_encode($jsonmsg);
	}
}
