<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_budget extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_budget');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'List Data Budget';
		$data['load_grid']  = 'C_budget';
		$data['load_add']   = 'C_budget/add';
		$data['url_delete'] = 'C_budget/delete';
		$this->load->view("v_budget/grid_budget", $data);
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
		$columns        = ['code_company', 'company_name', 'code_budget', 'name','action'];
		$order_by       = $columns[$order_col] ?? 'name';
		// $data           = $this->M_budget->get_paginated_budget($length, $start, $search, $order_by, $dir);
		// $total_records  = $this->M_budget->count_all_budget();
		// $total_filtered = $this->M_budget->count_filtered_budget($search);
		$data           = [];
		$total_records  = 0;
		$total_filtered = 0;
		$url_edit       = 'C_budget/editform/';
		$url_delete     = 'C_budget/hapusdata/';
		$load_grid      = 'C_budget/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_budget . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_budget . '">
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
				$row->code_budget,
				$row->name,
				$row->alias,
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
	function add()
	{
		$data['judul']     = "Form Budget";
		$data['load_back'] = 'C_budget/add';
		$data['load_grid'] = 'C_budget';
		$data['perusahaanList'] = $this->M_global->getWhereOrder('companies')->result();
		$this->load->view("v_budget/add_budget", $data);
	}
	public function simpandata()
	{
		$data = json_decode(file_get_contents("php://input"), true);

		$code_company = $data['perusahaan'];
		$code_department = $data['department'];
		$opening_balance = $data['saldo_awal'];
		$extend_budget = $data['perpanjang_angaran'];
		$project_amount = $data['jumlah_project'];
		$p_dept = [
			"code_department" => $code_department,
			"code_company" => $code_company,
		];

		$data_department = $this->M_global->getWhere("departments", $p_dept)->row();
		if($data_department == null){
			$jsonmsg = [
			'hasil' => 'false',
			'pesan' => 'Data Departement tidak di temukan',
			];
			echo json_encode($jsonmsg);
			exit;
		}

		$alias_dept        = $data_department->alias;
		$code_budgeting    = $this->M_budget->getcode_budgeting($code_department, $alias_dept,  $code_company);
		$parts             = explode('-', $code_budgeting); // pisahkan berdasarkan "-"
		$counter_budgeting = (int)$parts[count($parts) - 1]; // ambil id terakhir untuk keperluan "counter_budgeting"

		// Header: Mengambil data umum dari JSON
		$budgeting_header = [
			'uuid'               => $this->uuid->v4(),
			'counter_budgeting'  => $counter_budgeting,
			'code_budgeting'     => $code_budgeting,
			'code_company'       => $code_company,
			'code_department'    => $code_department,
			'opening_balance'    => (int)str_replace('.', '', $opening_balance),
			'extend_budget'      => (int)str_replace('.', '', $extend_budget),
			'project_amount'     => (int)str_replace('.', '', $project_amount),
			'years'              => date('Y'),
			'date_budgeting'     => date('Y-m-d'),
		];
		$this->db->insert('budgeting_header', $budgeting_header);
		// Projects: Mengelompokkan data per project
		$projects = [];
		foreach ($data['projects'] as $project) {
			$projectData = [
				'project_name' => $project['project_name'],
				'usulan_anggaran' => $project['usulan_anggaran'],
				'project_desc' => $project['project_desc'],
			];

			// Detail per project: Mengambil counta dan countb
			$countaDetails = [];
			foreach ($project['counta'] as $counta) {
				$countaDetails[] = [
					'account' => $counta['account'],
					'keterangan' => $counta['keterangan'],
					'jumlah' => $counta['jumlah']
				];
			}

			$countbDetails = [];
			foreach ($project['countb'] as $countb) {
				$countbDetails[] = [
					'keterangan' => $countb['keterangan'],
					'jumlah' => $countb['jumlah']
				];
			}

			// Menambahkan counta dan countb ke projectData
			$projectData['counta'] = $countaDetails;
			$projectData['countb'] = $countbDetails;

			// Menambahkan project data ke array projects
			$projects[] = $projectData;
		}

		// Menyiapkan array final untuk dikirim ke view atau diproses lebih lanjut
		$finalData = [
			'header' => $budgeting_header,
			// 'projects' => $projects
		];
		 var_dump($finalData); die; 
		// Debug output: Untuk melihat hasilnya
		
	}

	public function editform($uuid)
	{
		$data =  $this->M_budget->get_where_budget(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$judul = "Form Edit Budget";
			$load_grid = "C_budget";
			$load_refresh = "C_budget/editform/" . $uuid;
			$this->load->view('v_budget/edit_budget', [
				'judul' => $judul,
				'load_grid' => $load_grid,
				'load_refresh' => $load_refresh,
				'data' => $data,
				'uuid' => $uuid
			]);
		} else {
			$this->load->view('error');
		}
	}
	// Fungsi untuk update data Budget
	public function update()
	{
		// Ambil data dari POST request
		$uuid        = $this->input->post('uuid');
		$code_budget = $this->input->post('kode_budget');
		$nama_budget = $this->input->post('nama_budget');
		$alias_post  = $this->input->post('alias');
		// Cek apakah UUID Budget ada di database
		$data =  $this->M_budget->get_where_budget(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$code_company = $data->code_company;
			$cek_cost_center =  $this->M_global->getWhere('cost_centers', ['code_budget' => $data->code_budget])->num_rows();
			if ($cek_cost_center != 0) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Tidak bisa mengubah Data Budget karena sedang digunakan di cost centers.',
				];
				echo json_encode($jsonmsg);
				exit;
			}
			if($data->code_budget != $code_budget){
				$cekkode =  $this->M_budget->get_where_budget(['a.code_budget' => $code_budget, "a.code_company" => $code_company ])->num_rows();
				if ($cekkode != 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Kode Depo sudah terdaftar',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			if($data->name != $nama_budget){
				$param_nama = ['a.name' => $nama_budget, "a.code_company" => $code_company];
				$ceknama =  $this->M_budget->get_where_budget($param_nama)->num_rows();
				if ($ceknama !== 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Nama sudah terdaftar',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			if($data->alias !== $alias_post){
				$cekalias =  $this->M_budget->get_where_budget(['a.alias' => $alias_post])->num_rows();
				if ($cekalias !== 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Alias sudah terdaftar',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			$dataupdate = [
				'uuid'         => $this->uuid->v4(),
				'code_budget'  => $code_budget,
				'name'         => $nama_budget,
				'alias'        => $alias_post,
				'updated_at'   => date('Y-m-d H:i:s')
			];
			// Melakukan update data
			$update = $this->M_global->update($dataupdate, 'budgetons', ['uuid' => $uuid]);
			if ($update) {
				// Jika update berhasil
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' => 'Data Berhasil Diupdate',
				];
				echo json_encode($jsonmsg);
			} else {
				// Jika gagal update
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Gagal Menyimpan Data',
				];
				echo json_encode($jsonmsg);
			}
			
		} else {
			// Jika UUID Budget tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID Budget tidak ditemukan',
			];
			echo json_encode($jsonmsg);
		}
	}
	public function hapusdata()
	{
		$uuid = $this->input->post('uuid');
		// Validasi input
		if (empty($uuid)) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'UUID tidak boleh kosong'
			]);
			return;
		}
		$param_kode = ['a.uuid' => $uuid];
		$budget = $this->M_budget->get_where_budget($param_kode)->row();
		// Jika data tidak ditemukan
		if (!$budget) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan'
			]);
			return;
		}
		$cek_cc = $this->M_global->getWhere('cost_centers', ['code_budget' => $budget->code_budget])->num_rows();
		if ($cek_cc != 0) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Tidak bisa Menghapus Data, karena sedang digunakan di cost centers.',
			]);
			return;
		}
		$this->db->trans_begin();
		try {
			// Ambil data budget berdasarkan UUID
			
			// Lakukan penghapusan data di tabel budgetons
			$this->db->where('uuid', $uuid)->delete('budgetons');
			if ($this->db->affected_rows() <= 0) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Data gagal dihapus atau tidak ditemukan'
				]);
				return;
			}
			// Pastikan semua operasi berhasil
			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Terjadi kesalahan dalam transaksi, rollback dijalankan'
				]);
			} else {
				$this->db->trans_commit();
				echo json_encode([
					'hasil' => 'true',
					'pesan' => 'Data berhasil dihapus'
				]);
			}
		} catch (Exception $e) {
			// Jika ada error di proses apapun → rollback
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi error: ' . $e->getMessage()
			]);
		}
	}

	 public function Coa_expense(){
		$get_value = $this->input->get('cari');
		$code_company = $this->input->get('code_company');
		$cari = preg_replace("/[^a-zA-Z0-9]/", '', $get_value);
		$hasil = $this->M_budget->get_coa_expense($cari, $code_company);
		echo json_encode($hasil);
	 }
}
