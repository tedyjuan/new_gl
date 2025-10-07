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
		$data['judul']      = 'List data divisi';
		$data['load_grid']  = 'C_divisi';
		$data['load_add']   = 'C_divisi/add';
		$data['url_delete'] = 'C_divisi/delete';
		$this->load->view("v_divisi/grid_divisi", $data);
	}
	public function griddata()
	{
		// Parameter dari DataTables
		$start  = $this->input->get('start');
		$length = $this->input->get('length');
		$search = $this->input->get('search')['value'];
		$order  = $this->input->get('order')[0]['column'];
		$dir    = $this->input->get('order')[0]['dir'];
		
		$order_by = ['code_company', 'company_name', 'code_divisi', 'name', 'status_data', 'action'][$order];
		$data = $this->M_divisi->get_paginated_divisi($length, $start, $search, $order_by, $dir);
		$total_records = $this->M_divisi->count_all_divisi();
		$total_filtered = $this->M_divisi->count_filtered_divisi($search);

		// URL edit/delete bisa didefinisikan di controller atau dikirim dari view
		$url_edit   = 'C_divisi/editform/';
		$url_delete = 'C_divisi/hapusdata/';
		$load_grid  = 'C_divisi/griddata'; // misal: nama function reload datatable

		// Format data untuk DataTables
		$result = [];
		foreach ($data as $row) {

			$status_data = ($row->status_data == 'active')
				? '<span class="badge bg-success">Active</span>'
				: '<span class="badge bg-danger">Inactive</span>';

			// 🔹 Dropdown tombol aksi (HTML)
			$aksi = '
			<div class="dropdown">
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
				$row->code_company .' - '. $row->company_name,
				$row->code_divisi,
				$row->name,
				$row->alias,
				$status_data,
				$aksi,
			];
		}

		// Output JSON ke DataTables
		echo json_encode([
			"draw" => intval($this->input->get('draw')),
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
		$data = $this->M_divisi->get_divisi_by_uuid($uuid);
		if ($data) {
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
	public function updatedata()
	{
		// Ambil data dari POST request
		$uuid = $this->input->post('uuid');  // Ambil UUID dari input POST
		$divisi_name = $this->input->post('divisi_name');  // Ambil nama Divisi

		// Cek apakah UUID Divisi ada di database
		$existingdivisi =  $this->M_global->getWhereOrder('divisions','id', ['uuid' => $uuid])->num_rows() > 0;
		if ($existingdivisi) {
			// Siapkan data yang akan diupdate
			$dataupdate = [
				'name' => $divisi_name,
				'updated_at' => date('Y-m-d H:i:s')  // Format waktu sekarang
			];

			// Melakukan update data
			$update = $this->M_global->update($dataupdate, 'divisions', ['uuid' => $uuid]);
			if ($update) {
				// Jika update berhasil
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' => 'Data Berhasil Diupdate',
				];
			} else {
				// Jika gagal update
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Gagal Menyimpan Data',
				];
			}
		} else {
			// Jika UUID Divisi tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID Divisi tidak ditemukan',
			];
		}

		// Mengembalikan hasil dalam format JSON
		echo json_encode($jsonmsg);
	}
	public function search()
	{
		$get_value = $this->input->get('getdivisi');
		$cari = preg_replace("/[^a-zA-Z0-9]/", '', $get_value);
		$this->db->like('code_divisi', $cari);
		$this->db->or_like('name', $cari);
		$hasil = $this->db->get('divisions')->result();
		echo json_encode($hasil);
	}
}
