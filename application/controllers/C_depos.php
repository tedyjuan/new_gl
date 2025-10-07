<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_depos extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		// is_logged_in();
		$this->load->model('M_depos');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'List Data Depos';
		$data['load_grid']  = 'C_depos';
		$data['load_add']   = 'C_depos/add';
		$data['url_delete'] = 'C_depos/delete';
		$this->load->view("v_depos/grid_depos", $data);
	}
	public function griddata()
	{
		// Menangkap parameter dari DataTables
		$start  = $this->input->get('start');               // Indeks baris pertama untuk halaman
		$length = $this->input->get('length');              // Jumlah data per halaman
		$search = $this->input->get('search')['value'];     // Nilai pencarian
		$order  = $this->input->get('order')[0]['column'];  // Kolom yang akan diurutkan
		$dir    = $this->input->get('order')[0]['dir'];     // Urutan 'asc' atau 'desc'

		// Menentukan kolom untuk pengurutan berdasarkan parameter 'order'
		$order_by = ['code_depo', 'name', 'status_data', 'action'][$order];

		// Mengambil data dengan pagination dan pencarian
		$data = $this->M_depos->get_paginated_depos($length, $start, $search, $order_by, $dir);
		// Menghitung jumlah total data
		$total_records = $this->M_depos->count_all_depos();
		$total_filtered = $this->M_depos->count_filtered_depos($search);

		// Format data untuk dikirimkan ke DataTables
		$result = [];
		foreach ($data as $row) {
			$status_data = $row->status_data == 'active' ? '<span class="badge bg-success">active</span>' : '<span class="badge bg-danger">inactive</span>';
			$aksi = '<button class="btn btn-primary" onclick="loadform(\'' . 'C_depos/editform/' . $row->uuid . '\')"> <i class="bi bi-eye"></i> Edit </button>';
			$result[] = [
				$row->code_depo,  // code depos
				$row->name,          // nama depos
				$status_data,        // status data
				$aksi                // Aksi
			];
		}

		// Mengirimkan data dalam format JSON
		echo json_encode([
			"draw" => $_GET['draw'],  // Draw counter (untuk menyinkronkan dengan DataTables)
			"recordsTotal" => $total_records,  // Total data
			"recordsFiltered" => $total_filtered,  // Data yang difilter
			"data" => $result
		]);
	}

	function add()
	{
		$data['judul']     = "Form Tambah Perusahaan";
		$data['load_back'] = 'C_depos/add';
		$data['load_grid'] = 'C_depos';
		$this->load->view("v_depos/add_depos", $data);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('depos_code', 'Kode Perusahaan', 'required');
		$this->form_validation->set_rules('depos_name', 'Nama Perusahaan', 'required');

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
		$depos_code = $this->input->post('depos_code');
		$depos_name = $this->input->post('depos_name');

		// Cek apakah kode perusahaan sudah ada
		$this->load->database();
		$this->db->where('code_depo', $depos_code);
		$existingdepos = $this->db->get('depos')->num_rows() > 0;

		if ($existingdepos) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Kode perusahaan sudah digunakan',
			];
			echo json_encode($jsonmsg);
		} else {

			// Data untuk insert ke database
			$datainsert = [
				'uuid'         => $this->uuid->v4(),
				'code_depo' => $depos_code,
				'name'         => $depos_name,
				'created_at'   => date('Y-m-d H:i:s'),
				'updated_at'   => date('Y-m-d H:i:s')
			];

			// Melakukan insert data
			$this->db->insert('depos', $datainsert);
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
	public function editform($uuid)
	{
		$data = $this->M_depos->get_depos_by_uuid($uuid);
		if ($data) {
			$judul = "Form Edit Perusahaan";
			$load_grid = "C_depos";
			$load_refresh = "C_depos/editform/" . $uuid;
			$this->load->view('v_depos/edit_depos', [
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
	public function updatedata()
	{
		$uuid = $this->input->post('uuid');  // Ambil UUID dari input POST
		$depos_name = $this->input->post('depos_name');  // Ambil nama perusahaan
		// Cek apakah UUID perusahaan ada di database
		$existingdepos =  $this->M_global->getWhereOrder('depos','id', ['uuid' => $uuid])->num_rows() > 0;
		if ($existingdepos) {
			// Siapkan data yang akan diupdate
			$dataupdate = [
				'name' => $depos_name,
				'updated_at' => date('Y-m-d H:i:s')  // Format waktu sekarang
			];

			// Melakukan update data
			$update = $this->M_global->update($dataupdate, 'depos', ['uuid' => $uuid]);
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
			// Jika UUID perusahaan tidak ditemukan
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'UUID perusahaan tidak ditemukan',
			];
		}
		// Mengembalikan hasil dalam format JSON
		echo json_encode($jsonmsg);
	}
}
