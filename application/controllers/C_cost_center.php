<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_cost_center extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_cost_center');
		$this->load->model('M_global');
	}
	function index()
	{
		$companies   = $this->M_global->count_all_tabel('companies');
		$depos       = $this->M_global->count_all_tabel('depos');
		$departments = $this->M_global->count_all_tabel('departments');
		$divisions   = $this->M_global->count_all_tabel('divisions');
		$segments    = $this->M_global->count_all_tabel('segments');
		$pesan = '';
		if ($companies == 0) {
			$pesan .= "Master Department belum memiliki data.<br>";
		}
		if ($depos == 0) {
			$pesan .= "Master Department belum memiliki data.<br>";
		}
		if ($departments == 0) {
			$pesan .= "Master Department belum memiliki data.<br>";
		}
		if ($divisions == 0) {
			$pesan .= "Master Division belum memiliki data.<br>";
		}
		if ($segments == 0) {
			$pesan .= "Master Segment belum memiliki data.<br>";
		}
		if ($pesan != '') {
			$data['pesan'] = nl2br($pesan);
			$this->load->view("error", $data);
		}else{
			$data['judul']      = 'Data Cost Center';
			$data['load_grid']  = 'C_cost_center';
			$data['load_add']   = 'C_cost_center/add';
			$data['url_delete'] = 'C_cost_center/delete';
			$this->load->view("v_cost_center/grid_cost_center", $data);
		}
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
		$columns        = ['code_cost_center','group_team','manager','description','action'];
		$order_by       = $columns[$order_col] ?? 'code_cost_center';
		$data           = $this->M_cost_center->get_paginated_cost_center($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_cost_center->count_all_cost_center();
		$total_filtered = $this->M_cost_center->count_filtered_cost_center($search);
		$url_edit       = 'C_cost_center/editform/';
		$url_delete     = 'C_cost_center/hapusdata/';
		$load_grid      = 'C_cost_center/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_cost_center . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_cost_center . '">
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
				$row->code_cost_center,
				$row->group_team,
				$row->manager,
				$row->description,
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
		$data['judul']          = "Form Tambah cost_center";
		$data['load_back']      = 'C_cost_center/add';
		$data['load_grid']      = 'C_cost_center';
		$data['perusahaanList'] = $this->M_global->getWhereOrder('companies')->result();
		$this->load->view("v_cost_center/add_cost_center", $data);
	}
	public function simpandata()
	{
		// Validasi form input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('depo', 'Depo', 'required');
		$this->form_validation->set_rules('Department', 'Department', 'required');
		$this->form_validation->set_rules('divisi', 'Divisi', 'required');
		$this->form_validation->set_rules('segment', 'Segment', 'required');
		$this->form_validation->set_rules('manager', 'Manager', 'required');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => validation_errors(),
			]);
			return;
		}

		// Ambil data dari request
		$perusahaan  = $this->input->post('perusahaan');
		$depo        = $this->input->post('depo');
		$department  = $this->input->post('Department');
		$divisi      = $this->input->post('divisi');
		$segment     = $this->input->post('segment');
		$manager     = $this->input->post('manager');
		$description = $this->input->post('description');
		$cc_groupteam    = $this->M_cost_center->get_cc_And_groupteam($perusahaan, $depo, $department, $divisi, $segment);
		if ($cc_groupteam == null) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalaahn saat simpan data',
				'error' => 'Kode company depo dept divisi segment tidak ditemukan',
			]);
			return;
		}
		// set data untuk di insert
		$costcenter = $cc_groupteam->code_cost_center;
		$group_team = $cc_groupteam->group_team;
		$code_area = $cc_groupteam->code_area;
		// Cek apakah costcenter sudah ada
		$param = ['code_cost_center' => $costcenter, 'code_company' => $perusahaan];
		if ($this->M_global->getWhere("cost_centers", $param)->num_rows() != 0) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Kode cost center sudah digunakan',
			]);
			return;
		}
		// Ambil data untuk cek alias dan status_data
		$this->db->trans_start();
		try {
			$uuid = uniqid('', true);
			$datainsert = [
				'uuid'             => $uuid,
				'code_cost_center' => $costcenter,
				'group_team'       => $group_team,
				'code_depo'        => $depo,
				'code_divisi'      => $divisi,
				'code_department'  => $department,
				'code_segment'     => $segment,
				'code_company'     => $perusahaan,
				'code_area'        => $code_area,
				'manager'          => $manager,
				'description'      => $description,
				'created_at'       => date('Y-m-d H:i:s'),
			];

			// Insert costcenter
			if (!$this->M_global->insert($datainsert, 'cost_centers')) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Gagal Menyimpan Data',
				]);
				return;
			}
			// Commit transaksi jika semua berhasil
			$this->db->trans_complete();
			echo json_encode([
				'hasil' => 'true',
				'pesan' => 'Data Berhasil Disimpan',
			]);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Gagal Menyimpan Data: ' . $e->getMessage(),
			]);
		}
	}
	public function editform($uuid)
	{
		$data =  $this->M_cost_center->get_where_cost_center(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$judul = "Form Edit cost_center";
			$load_grid = "C_cost_center";
			$load_refresh = "C_cost_center/editform/" . $uuid;
			$this->load->view('v_cost_center/edit_cost_center', [
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
	// Fungsi untuk update data cost_center
	public function update()
	{
		// Ambil data dari POST request
		$uuid = $this->input->post('uuid'); 
		$perusahaan  = $this->input->post('perusahaan');
		$code_cost_center = $this->input->post('kode_cost_center');
		$nama_cost_center = $this->input->post('nama_cost_center');
		$alias_post       = $this->input->post('alias');
		// Cek apakah UUID cost_center ada di database
		$data =  $this->M_cost_center->get_where_cost_center(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$code_company_old = $data->code_company;
			if($data->code_cost_center == $code_cost_center){
				$p_kode = 'LOLOS';
			}else{
				$param_kode = ['a.code_cost_center' => $code_cost_center];
				$cekkode =  $this->M_cost_center->get_where_cost_center($param_kode)->num_rows();
				if($cekkode == 0 ){
					$p_kode = 'LOLOS';
				}else{
					$p_kode = 'TTIDAK_LOLOS';
				}
			}
			if($data->name == $nama_cost_center){
				$p_nama = 'LOLOS';
			}else{
				$param_nama = ['a.name' => $nama_cost_center, "a.code_company" => $perusahaan];
				$ceknama =  $this->M_cost_center->get_where_cost_center($param_nama)->num_rows();
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
				$cekalias =  $this->M_cost_center->get_where_cost_center($param_alias)->num_rows();
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
					'code_cost_center'  => $code_cost_center,
					'name'         => $nama_cost_center,
					'alias'        => $alias_post,
					'code_company' => $perusahaan,
					'updated_at'   => date('Y-m-d H:i:s')
				];
				// Melakukan update data
				$update = $this->M_global->update($dataupdate, 'cost_centers', ['uuid' => $uuid]);
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
			// Jika UUID cost_center tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID cost_center tidak ditemukan',
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
			// Ambil data cost_center berdasarkan UUID
			$param_kode = ['a.uuid' => $uuid];
			$cost_center = $this->M_cost_center->get_where_cost_center($param_kode)->row();
			// Jika data tidak ditemukan
			if (!$cost_center) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => 'false',
					'pesan' => 'Data tidak ditemukan'
				]);
				return;
			}
			// Lakukan penghapusan data di tabel cost_centers
			$this->db->where('uuid', $uuid)->delete('cost_centers');
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
