<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_chart_of_account extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_chart_of_account');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'Chart Of Account';
		$data['load_grid']  = 'C_chart_of_account';
		$data['load_add']   = 'C_chart_of_account/add';
		$data['url_delete'] = 'C_chart_of_account/delete';
		$this->load->view("v_chart_of_account/grid_coa", $data);
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
		$columns        = ['code_company', 'account_number', 'name', 'account_type','action'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_chart_of_account->get_paginated_chart_of_account($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_chart_of_account->count_all_chart_of_account();
		$total_filtered = $this->M_chart_of_account->count_filtered_chart_of_account($search);
		$url_edit       = 'C_chart_of_account/editform/';
		$url_delete     = 'C_chart_of_account/hapusdata/';
		$load_grid      = 'C_chart_of_account/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->account_number . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->account_number . '">
					<button class="dropdown-item editbtn" onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-pen"></i> Edit
					</button>
					<div class="dropdown-divider"></div>
					<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
						<i class="bi bi-trash3"></i> Delete
					</button>
				</div>
			</div>';
			$spacing = '';
			if (strlen($row->account_number) == 6) {
				$spacing = '&nbsp;&nbsp;&nbsp;&nbsp;';  // 1 tab untuk 6 digit
			} elseif (strlen($row->account_number) == 8) {
				$spacing = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';  // 2 tab untuk 8 digit
			}
			$result[] = [
				$row->code_company,
				$spacing.$row->account_number,
				$row->name,
				$row->account_type,
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
		$data['judul']     = "Form Tambah Header COA";
		$data['load_back'] = 'C_chart_of_account/add';
		$data['load_grid'] = 'C_chart_of_account';
		$this->load->view("v_chart_of_account/add_header_coa", $data);
	}
	
	 public function get_tbag1(){
		$code_company = $this->input->post('code_company');
		$akun_type = $this->input->post('akun_type');
		$tbag1 = $this->M_chart_of_account->get_tbag1Bycompany($code_company, $akun_type);
		echo json_encode($tbag1);
	}
	 public function get_tbag2(){
		$code_company = $this->input->post('code_company');
		$tbag1 = $this->input->post('tbag1');
		$tbag2 = $this->M_chart_of_account->get_tbag2Bycompany($code_company, $tbag1);
		echo json_encode($tbag2);
	}
	 public function get_tbag3(){
		$code_company = $this->input->post('code_company');
		$tbag2 = $this->input->post('tbag2');
		$tbag3 = $this->M_chart_of_account->get_tbag3Bycompany($code_company, $tbag2);
		echo json_encode($tbag3);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('no_akun', 'No Chart Of Account', 'required');
		$this->form_validation->set_rules('nama_akun', 'Nama Chart Of Account', 'required');
		$this->form_validation->set_rules('akun_dc', 'Metode coa', 'required');
		$this->form_validation->set_rules('akun_group', 'Akun group', 'required');
		$this->form_validation->set_rules('akun_type', 'Akun Type', 'required');
		if ($this->form_validation->run() == FALSE) {
			// Jika validasi gagal
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => validation_errors(),
			];
			echo json_encode($jsonmsg);
			return;
		}
		$perusahaan = $this->input->post('perusahaan');
		$no_akun    = $this->input->post('no_akun');
		$nama_akun  = $this->input->post('nama_akun');
		$akun_dc    = $this->input->post('akun_dc');
		$akun_group = $this->input->post('akun_group');
		$akun_type  = $this->input->post('akun_type');
		$tbag1      = $this->input->post('tbag1');
		$tbag2      = $this->input->post('tbag2');
		$tbag3      = $this->input->post('tbag3');
		$deskripsi  = $this->input->post('deskripsi');
		// Cek apakah kode Chart Of Account sudah ada
		$param_kode =[
			'account_number'  => $no_akun,
			'code_company'  => $perusahaan,
		];
		$exisCode = $this->M_global->getWhere('chart_of_accounts', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode Chart Of Account sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_nama = [
			'name'         => $nama_akun,
			'code_company' => $perusahaan,
		];
		$exisName = $this->M_global->getWhere('chart_of_accounts', $param_nama)->num_rows();
		if ($exisName != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Nama Chart Of Account sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		} 
		// Data untuk insert ke database
		// kalau 4 = unit 
		$datainsert = [
			'uuid'               => $this->uuid->v4(),
			'account_number'     => $no_akun,
			'code_company'       => $perusahaan,
			'name'               => $nama_akun,
			'description'        => $deskripsi,
			'account_type'       => $akun_type,
			'account_method'     => $akun_dc,
			'account_group'      => $akun_group,
			'cost_center_type'   => 'unit',
			'code_trialbalance1' => $tbag1,
			'code_trialbalance2' => $tbag2,
			'code_trialbalance3' => $tbag3,
			'created_at'         => date('Y-m-d H:i:s'),
			'updated_at'         => date('Y-m-d H:i:s')
		];
		// Melakukan insert data
		$this->db->insert('chart_of_accounts', $datainsert);
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
	public function editform($uuid)
	{
		$cekdata =  $this->M_chart_of_account->get_where_chart_of_account(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$code_company = $cekdata->code_company;
			$data['data']         = $cekdata;
			$data['uuid']         = $uuid;
			$data['judul']        = "Form Edit Header COA";
			$data['load_grid']    = "C_chart_of_account";
			$data['load_refresh'] = "C_chart_of_account/editform/" . $uuid;
			$data['companys'] = $this->M_global->getWhere("companies")->result();
			$data['type_akun'] = $this->M_chart_of_account->get_tbag1Bycompany($code_company);
			$data['tbag1List'] = $this->M_global->getWhere("trial_balance_account_group_1", ['code_company' => $code_company])->result();
			$data['tbag2List'] = $this->M_global->getWhere("trial_balance_account_group_2", ['code_company' => $code_company])->result();
			$data['tbag3List'] = $this->M_global->getWhere("trial_balance_account_group_3", ['code_company' => $code_company])->result();
		
			$this->load->view('v_chart_of_account/edit_header_coa', $data);
		} else {
			$this->load->view('error');
		}
	}
	public function update()
	{
		// Ambil data dari POST request
		$uuid       = $this->input->post('uuid');
		$perusahaan = $this->input->post('perusahaan');
		$no_akun    = $this->input->post('no_akun');
		$nama_akun  = $this->input->post('nama_akun');
		$akun_dc    = $this->input->post('akun_dc');
		$akun_group = $this->input->post('akun_group');
		$akun_type  = $this->input->post('akun_type');
		$tbag1      = $this->input->post('tbag1');
		$tbag2      = $this->input->post('tbag2');
		$tbag3      = $this->input->post('tbag3');
		$deskripsi  = $this->input->post('deskripsi');
		// Cek apakah kode Chart Of Account sudah ada
		$param_kode = [
			'account_number'  => $no_akun,
			'code_company'  => $perusahaan,
		];
		$exisCode = $this->M_global->getWhere('chart_of_accounts', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode Chart Of Account sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_nama = [
			'name'         => $nama_akun,
			'code_company' => $perusahaan,
		];
		$exisName = $this->M_global->getWhere('chart_of_accounts', $param_nama)->num_rows();
		if ($exisName != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Nama Chart Of Account sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		// Data untuk insert ke database
		// kalau 4 = unit 
		$datainsert = [
			'uuid'               => $this->uuid->v4(),
			'account_number'     => $no_akun,
			'code_company'       => $perusahaan,
			'name'               => $nama_akun,
			'description'        => $deskripsi,
			'account_type'       => $akun_type,
			'account_method'     => $akun_dc,
			'account_group'      => $akun_group,
			'cost_center_type'   => 'unit',
			'code_trialbalance1' => $tbag1,
			'code_trialbalance2' => $tbag2,
			'code_trialbalance3' => $tbag3,
			'created_at'         => date('Y-m-d H:i:s'),
			'updated_at'         => date('Y-m-d H:i:s')
		];
		// Melakukan insert data
		$this->db->insert('chart_of_accounts', $datainsert);
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
		$Cek_coa = $this->M_chart_of_account->get_where_chart_of_account($param_kode)->row();
		// Jika data tidak ditemukan
		if (!$Cek_coa) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan'
			]);
			return;
		}
		// $cek_cc = $this->M_global->getWhere('chart_of_accounts', ['account_number' => $Cek_coa->account_number])->num_rows();
		// if ($cek_cc != 0) {
		// 	echo json_encode([
		// 		'hasil' => 'false',
		// 		'pesan' => 'Tidak bisa Menghapus Data, karena sedang digunakan di cost centers.',
		// 	]);
		// 	return;
		// }
		$this->db->trans_begin();
		try {
			
			// Lakukan penghapusan data di tabel C_chart_of_accountons
			$this->db->where('uuid', $uuid)->delete('chart_of_accounts');
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
}
