<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_department extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_department');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'List Data Department';
		$data['load_grid']  = 'C_department';
		$data['load_add']   = 'C_department/add';
		$data['url_delete'] = 'C_department/delete';
		$this->load->view("v_department/grid_department", $data);
	}
	public function griddata()
	{
		$start  = $this->input->post('start') ?? 0;
		$length = $this->input->post('length') ?? 10;
		$search_input = $this->input->post('search');
		$search = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input = $this->input->post('order');
		$order_col = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'asc';
		$columns = ['code_company', 'company_name', 'code_department', 'name','action'];
		$order_by = $columns[$order_col] ?? 'name';
		$data = $this->M_department->get_paginated_department($length, $start, $search, $order_by, $dir);
		$total_records = $this->M_department->count_all_department();
		$total_filtered = $this->M_department->count_filtered_department($search);
		$url_edit   = 'C_department/editform/';
		$url_delete = 'C_department/hapusdata/';
		$load_grid  = 'C_department/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_department . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_department . '">
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
				$row->code_department,
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
		$data['judul']     = "Form Tambah Department";
		$data['load_back'] = 'C_department/add';
		$data['load_grid'] = 'C_department';
		$this->load->view("v_department/add_department", $data);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('kode_department', 'Code Department', 'required');
		$this->form_validation->set_rules('nama_department', 'Nama Department', 'required');
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
		$perusahaan      = $this->input->post('perusahaan');
		$code_department = $this->input->post('kode_department');
		$nama_department = $this->input->post('nama_department');
		$alias           = $this->input->post('alias');
		// Cek apakah kode Department sudah ada
		$param_kode =[
			'code_department'  => $code_department
		];
		$exisCode = $this->M_global->getWhere('departments', $param_kode)->num_rows();
		if ($exisCode != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode Department sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_alias = [
			'alias'  => $alias
		];
		$exisalias = $this->M_global->getWhere('departments', $param_alias)->num_rows();
		if ($exisalias != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Alias sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_nama = [
			'name'         => $nama_department,
			'code_company' => $perusahaan,
		];
		$exisName = $this->M_global->getWhere('departments', $param_nama)->num_rows();
		if ($exisName != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode Department sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		} 
		// Data untuk insert ke database
		$datainsert = [
			'uuid'         => $this->uuid->v4(),
			'code_department'  => $code_department,
			'code_company' => $perusahaan,
			'name'         => $nama_department,
			'alias'        => $alias,
			'created_at'   => date('Y-m-d H:i:s'),
			'updated_at'   => date('Y-m-d H:i:s')
		];
		// Melakukan insert data
		$this->db->insert('departments', $datainsert);
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
		$data =  $this->M_department->get_where_department(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$judul = "Form Edit Department";
			$load_grid = "C_department";
			$load_refresh = "C_department/editform/" . $uuid;
			$this->load->view('v_department/edit_department', [
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
	// Fungsi untuk update data Department
	public function update()
	{
		$uuid            = $this->input->post('uuid');
		$perusahaan      = $this->input->post('perusahaan');
		$code_department = $this->input->post('kode_department');
		$nama_department = $this->input->post('nama_department');
		$alias_post      = $this->input->post('alias');
		// Cek apakah UUID Department ada di database
		$data =  $this->M_department->get_where_department(['a.uuid' => $uuid])->row();
		if ($data != null) {
			if($data->code_department == $code_department){
				$p_kode = 'LOLOS';
			}else{
				$param_kode = ['a.code_department' => $code_department];
				$cekkode =  $this->M_department->get_where_department($param_kode)->num_rows();
				if($cekkode == 0 ){
					$p_kode = 'LOLOS';
				}else{
					$p_kode = 'TTIDAK_LOLOS';
				}
			}
			if($data->name == $nama_department){
				$p_nama = 'LOLOS';
			}else{
				$param_nama = ['a.name' => $nama_department, "a.code_company" => $perusahaan];
				$ceknama =  $this->M_department->get_where_department($param_nama)->num_rows();
				if($ceknama == 0 ){
					$p_nama = 'LOLOS';
				}else{
					$p_nama = 'TTIDAK_LOLOS';
				}
			}
			if($data->alias == $alias_post){
				$p_alias = 'LOLOS';
			}else{
				$param_alias = ['a.alias' => $alias_post];
				$cekalias =  $this->M_department->get_where_department($param_alias)->num_rows();
				if($cekalias == 0 ){
					$p_alias = 'LOLOS';
				}else{
					$p_alias = 'TTIDAK_LOLOS';
				}
			}
			if($p_kode == 'LOLOS' && $p_nama == 'LOLOS' && $p_alias == 'LOLOS'){
				// Siapkan data yang akan diupdate
				$dataupdate = [
					'uuid'         => $this->uuid->v4(),
					'code_department'  => $code_department,
					'name'         => $nama_department,
					'alias'        => $alias_post,
					'code_company' => $perusahaan,
					'updated_at'   => date('Y-m-d H:i:s')
				];
				// Melakukan update data
				$update = $this->M_global->update($dataupdate, 'departments', ['uuid' => $uuid]);
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
			}
		} else {
			// Jika UUID Department tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID Department tidak ditemukan',
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
		// Mulai transaksi
		$this->db->trans_begin();
		try {
			// Ambil data department berdasarkan UUID
			$param_kode = ['a.uuid' => $uuid];
			$department = $this->M_department->get_where_department($param_kode)->row();
			// Jika data tidak ditemukan
			if (!$department) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Data tidak ditemukan'
				]);
				return;
			}
			// Lakukan penghapusan data di tabel departments
			$this->db->where('uuid', $uuid)->delete('departments');
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
