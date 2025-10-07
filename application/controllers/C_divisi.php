<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_divisi extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_divisi');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'List Data Divisi';
		$data['load_grid']  = 'C_divisi';
		$data['load_add']   = 'C_divisi/add';
		$data['url_delete'] = 'C_divisi/delete';
		$this->load->view("v_divisi/grid_divisi", $data);
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

		$columns = ['code_company', 'company_name', 'code_divisi', 'name', 'status_data', 'action'];
		$order_by = $columns[$order_col] ?? 'name';

		$data = $this->M_divisi->get_paginated_divisi($length, $start, $search, $order_by, $dir);
		$total_records = $this->M_divisi->count_all_divisi();
		$total_filtered = $this->M_divisi->count_filtered_divisi($search);

		$url_edit   = 'C_divisi/editform/';
		$url_delete = 'C_divisi/hapusdata/';
		$load_grid  = 'C_divisi/griddata';

		$result = [];
		foreach ($data as $row) {
			$status_data = ($row->status_data == 'active')
				? '<span class="badge bg-success">Active</span>'
				: '<span class="badge bg-danger">Inactive</span>';

			$aksi = '<div class="dropdown">
            <button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_divisi . '" data-bs-toggle="dropdown" aria-expanded="false">
                More <i class="bi-chevron-down ms-1"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_divisi . '">
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
				$row->code_divisi,
				$row->name,
				$row->alias,
				$status_data,
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
		$data['judul']     = "Form Tambah Divisi";
		$data['load_back'] = 'C_divisi/add';
		$data['load_grid'] = 'C_divisi';
		$this->load->view("v_divisi/add_divisi", $data);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('kode_divisi', 'Code Divisi', 'required');
		$this->form_validation->set_rules('nama_divisi', 'Nama Divisi', 'required');
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
		$code_divisi = $this->input->post('kode_divisi');
		$nama_divisi = $this->input->post('nama_divisi');
		$alias       = $this->input->post('alias');

		// Cek apakah kode Divisi sudah ada
		$param_kode =[
			'code_divisi'  => $code_divisi,
			'code_company' => $perusahaan,
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
		$param_nama = [
			'name'         => $nama_divisi,
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
			'code_divisi'  => $code_divisi,
			'code_company' => $perusahaan,
			'name'         => $nama_divisi,
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
		$data =  $this->M_divisi->get_where_divisi(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$judul = "Form Edit Divisi";
			$load_grid = "C_divisi";
			$load_refresh = "C_divisi/editform/" . $uuid;
			$this->load->view('v_divisi/edit_divisi', [
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
		$perusahaan  = $this->input->post('perusahaan');
		$code_divisi = $this->input->post('kode_divisi');
		$nama_divisi = $this->input->post('nama_divisi');
		$alias_post       = $this->input->post('alias');

		// Cek apakah UUID Divisi ada di database
		$data =  $this->M_divisi->get_where_divisi(['a.uuid' => $uuid])->row();
		if ($data != null) {
			if($data->code_divisi == $code_divisi){
				$p_kode = 'LOLOS';
			}else{
				$param_kode = ['a.code_divisi' => $code_divisi, "a.code_company" => $perusahaan];
				$cekkode =  $this->M_divisi->get_where_divisi($param_kode)->num_rows();
				if($cekkode == 0 ){
					$p_kode = 'LOLOS';
				}else{
					$p_kode = 'TTIDAK_LOLOS';
				}
			}
			if($data->name == $nama_divisi){
				$p_nama = 'LOLOS';
			}else{
				$param_nama = ['a.name' => $nama_divisi, "a.code_company" => $perusahaan];
				$ceknama =  $this->M_divisi->get_where_divisi($param_nama)->num_rows();
				if($ceknama == 0 ){
					$p_nama = 'LOLOS';
				}else{
					$p_nama = 'TTIDAK_LOLOS';
				}
			}
			if($data->alias == $alias_post){
				$p_alias = 'LOLOS';
			}else{
				$param_alias = ['a.alias' => $alias_post, "a.code_company" => $perusahaan];
				$cekalias =  $this->M_divisi->get_where_divisi($param_alias)->num_rows();
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
					'code_divisi'  => $code_divisi,
					'name'         => $nama_divisi,
					'alias'        => $alias_post,
					'code_company' => $perusahaan,
					'updated_at'   => date('Y-m-d H:i:s')
				];
	
				// Melakukan update data
				$update = $this->M_global->update($dataupdate, 'divisions', ['uuid' => $uuid]);
				if ($update) {
					// Jika update berhasil
					$cek_company = $this->M_global->getWhere('companies', ['code_company' => $perusahaan, 'status_data' => 'inactive'])->num_rows();
					if($cek_company != 0){
						// Melakukan update data
						$update = $this->M_global->update(['status_data' => 'active'], 'companies', ['code_company' => $perusahaan]);
					}
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

		// Mulai transaksi
		$this->db->trans_begin();

		try {
			// Ambil data divisi berdasarkan UUID
			$param_kode = ['a.uuid' => $uuid];
			$divisi = $this->M_divisi->get_where_divisi($param_kode)->row();

			// Jika data tidak ditemukan
			if (!$divisi) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Data tidak ditemukan'
				]);
				return;
			}

			// Jika status aktif → tidak bisa dihapus
			if ($divisi->status_data === 'active') {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Data tidak bisa dihapus karena status masih aktif'
				]);
				return;
			}

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

			// Cek apakah perusahaan masih memiliki entitas lain
			$param_company = ['code_company' => $divisi->code_company];

			$cek_divisi  = $this->M_global->getWhere('divisions', $param_company)->num_rows();
			$cek_dept    = $this->M_global->getWhere('departments', $param_company)->num_rows();
			$cek_segment = $this->M_global->getWhere('segments', $param_company)->num_rows();
			$cek_depo    = $this->M_global->getWhere('depos', $param_company)->num_rows();

			$total_referensi = $cek_divisi + $cek_dept + $cek_segment + $cek_depo;

			// Jika tidak ada entitas lain → set perusahaan menjadi inactive
			if ($total_referensi == 0) {
				$this->M_global->update(['status_data' => 'inactive'], 'companies', $param_company);
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
