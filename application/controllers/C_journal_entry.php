<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_journal_entry extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_journal_entry');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'Journal Entry';
		$data['load_grid']  = 'C_journal_entry';
		$data['load_add']   = 'C_journal_entry/add';
		$data['url_delete'] = 'C_journal_entry/delete';
		$this->load->view("v_journal_entry/grid_journal_entry", $data);
	}
	public function griddata()
	{
		// $start  = $this->input->post('start') ?? 0;
		// $length = $this->input->post('length') ?? 10;
		// $search_input = $this->input->post('search');
		// $search = isset($search_input['value']) ? $search_input['value'] : '';
		// $order_input = $this->input->post('order');
		// $order_col = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		// $dir = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		// $columns = ['code_company', 'company_name', 'code_journal_entry', 'name','action'];
		// $order_by = $columns[$order_col] ?? 'name';
		// $data = $this->M_journal_entry->get_paginated_journal_entry($length, $start, $search, $order_by, $dir);
		// $total_records = $this->M_journal_entry->count_all_journal_entry();
		// $total_filtered = $this->M_journal_entry->count_filtered_journal_entry($search);
		$url_edit   = 'C_journal_entry/editform/';
		$url_delete = 'C_journal_entry/hapusdata/';
		$load_grid  = 'C_journal_entry/griddata';
		$result = [];
		$data = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_journal_entry . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_journal_entry . '">
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
				$row->code_company . ' - ' . $row->company_name,
				$row->code_journal_entry,
				$row->name,
				$row->alias,
				$aksi,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => 0,
			"recordsFiltered" => 0,
			// "recordsTotal" => $total_records,
			// "recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}
	function add()
	{
		$data['judul']     = "Add Journal Entry";
		$data['load_back'] = 'C_journal_entry/add';
		$data['load_grid'] = 'C_journal_entry';
		$data['code_company'] = $this->session->userdata('sess_company');

		$param = [
			'code_company' => $this->session->userdata('sess_company'),
		];
		$data['depos'] = $this->M_global->getWhere('depos', $param)->result();
		$data['journal_sources'] = $this->M_global->getWhere('journal_sources', $param)->result();
		$this->load->view("v_journal_entry/add_journal_entry", $data);
	}
	public function Costcenter_all()
	{
		$get_value = $this->input->get('cari');
		$cari = preg_replace("/[^a-zA-Z0-9]/", '', $get_value);
		$hasil = $this->M_journal_entry->get_cc($cari);
		echo json_encode($hasil);
	}
	
}
