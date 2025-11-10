<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_petty_cash extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_petty_cash');
		$this->load->model('M_global');
	}
	
	function index()
	{
		$data['judul']      = 'List Data Divisi';
		$data['load_grid']  = 'C_petty_cash';
		$data['load_add']   = 'C_petty_cash/add';
		$data['url_delete'] = 'C_petty_cash/delete';
		$this->load->view("v_petty_cash/grid_petty_cash", $data);
	}
	public function griddata()
	{
		// $start          = $this->input->post('start') ?? 0;
		// $length         = $this->input->post('length') ?? 10;
		// $search_input   = $this->input->post('search');
		// $search         = isset($search_input['value']) ? $search_input['value'] : '';
		// $order_input    = $this->input->post('order');
		// $order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		// $dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		// $columns        = ['code_company', 'company_name', 'code_petty_cash', 'name','action'];
		// $order_by       = $columns[$order_col] ?? 'name';
		$data           = [];
		// $data           = $this->M_petty_cash->get_paginated_petty_cash($length, $start, $search, $order_by, $dir);
		// $total_records  = $this->M_petty_cash->count_all_petty_cash();
		// $total_filtered = $this->M_petty_cash->count_filtered_petty_cash($search);
		$url_edit   = 'C_petty_cash/editform/';
		$url_delete = 'C_petty_cash/hapusdata/';
		$load_grid  = 'C_petty_cash/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_petty_cash . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_petty_cash . '">
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
				$row->code_petty_cash,
				$row->name,
				$row->alias,
				$aksi,
			];
		}
		echo json_encode([
			"draw" => intval($this->input->post('draw')) ?? 1,
			"recordsTotal" => 0,
			"recordsFiltered" => 0,
			"data" => []
		]);
		// echo json_encode([
		// 	"draw" => intval($this->input->post('draw')) ?? 1,
		// 	"recordsTotal" => $total_records,
		// 	"recordsFiltered" => $total_filtered,
		// 	"data" => $result
		// ]);
	}
	function add()
	{
		$data['judul']     = "Add Petty Cash";
		$data['load_back'] = 'C_petty_cash/add';
		$data['load_grid'] = 'C_petty_cash';
		$this->load->view("v_petty_cash/add_petty_cash", $data);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('kode_petty_cash', 'Code Divisi', 'required');
		$this->form_validation->set_rules('nama_petty_cash', 'Nama Divisi', 'required');
		$this->form_validation->set_rules('alias', 'Nama Alias', 'required');
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
		$perusahaan  = $this->input->post('perusahaan');
		$code_petty_cash = $this->input->post('kode_petty_cash');
		$nama_petty_cash = $this->input->post('nama_petty_cash');
		$alias       = $this->input->post('alias');
		// Cek apakah kode Divisi sudah ada
		$param_kode =[
			'code_petty_cash'  => $code_petty_cash
		];
		$exisCode = $this->M_global->getWhere('divisions', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode Divisi sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_alias = ['alias'  => $alias];
		$exisalias = $this->M_global->getWhere('divisions', $param_alias)->num_rows();
		if ($exisalias != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Alias sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_nama = [
			'name'         => $nama_petty_cash,
			'code_company' => $perusahaan,
		];
		$exisName = $this->M_global->getWhere('divisions', $param_nama)->num_rows();
		if ($exisName != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode Divisi sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		} 
		// Data untuk insert ke database
		$datainsert = [
			'uuid'         => $this->uuid->v4(),
			'code_petty_cash'  => $code_petty_cash,
			'code_company' => $perusahaan,
			'name'         => $nama_petty_cash,
			'alias'        => $alias,
			'created_at'   => date('Y-m-d H:i:s'),
			'updated_at'   => date('Y-m-d H:i:s')
		];
		// Melakukan insert data
		$this->db->insert('divisions', $datainsert);
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
		$data =  $this->M_petty_cash->get_where_petty_cash(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$judul = "Form Edit Divisi";
			$load_grid = "C_petty_cash";
			$load_refresh = "C_petty_cash/editform/" . $uuid;
			$this->load->view('v_petty_cash/edit_petty_cash', [
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
	// Fungsi untuk update data Divisi
	public function update()
	{
		// Ambil data dari POST request
		$uuid = $this->input->post('uuid'); 
		$code_petty_cash = $this->input->post('kode_petty_cash');
		$nama_petty_cash = $this->input->post('nama_petty_cash');
		$alias_post       = $this->input->post('alias');
		// Cek apakah UUID Divisi ada di database
		$data =  $this->M_petty_cash->get_where_petty_cash(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$code_company = $data->code_company;
			$cek_cost_center =  $this->M_global->getWhere('cost_centers', ['code_petty_cash' => $data->code_petty_cash])->num_rows();
			if ($cek_cost_center != 0) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Tidak bisa mengubah Data Divisi karena sedang digunakan di cost centers.',
				];
				echo json_encode($jsonmsg);
				exit;
			}
			if($data->code_petty_cash != $code_petty_cash){
				$cekkode =  $this->M_petty_cash->get_where_petty_cash(['a.code_petty_cash' => $code_petty_cash, "a.code_company" => $code_company ])->num_rows();
				if ($cekkode != 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Kode Depo sudah terdaftar',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			if($data->name != $nama_petty_cash){
				$param_nama = ['a.name' => $nama_petty_cash, "a.code_company" => $code_company];
				$ceknama =  $this->M_petty_cash->get_where_petty_cash($param_nama)->num_rows();
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
				$cekalias =  $this->M_petty_cash->get_where_petty_cash(['a.alias' => $alias_post])->num_rows();
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
				'code_petty_cash'  => $code_petty_cash,
				'name'         => $nama_petty_cash,
				'alias'        => $alias_post,
				'updated_at'   => date('Y-m-d H:i:s')
			];
			// Melakukan update data
			$update = $this->M_global->update($dataupdate, 'divisions', ['uuid' => $uuid]);
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
			// Jika UUID Divisi tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID Divisi tidak ditemukan',
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
		$divisi = $this->M_petty_cash->get_where_petty_cash($param_kode)->row();
		// Jika data tidak ditemukan
		if (!$divisi) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan'
			]);
			return;
		}
		$cek_cc = $this->M_global->getWhere('cost_centers', ['code_petty_cash' => $divisi->code_petty_cash])->num_rows();
		if ($cek_cc != 0) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Tidak bisa Menghapus Data, karena sedang digunakan di cost centers.',
			]);
			return;
		}
		$this->db->trans_begin();
		try {
			// Ambil data divisi berdasarkan UUID
			
			// Lakukan penghapusan data di tabel divisions
			$this->db->where('uuid', $uuid)->delete('divisions');
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
	public function Coa_all()
	{
		$get_value = $this->input->get('cari');
		$cari = preg_replace("/[^a-zA-Z0-9]/", '', $get_value);
		$hasil = $this->M_petty_cash->get_coa($cari);
		echo json_encode($hasil);
	}
}
