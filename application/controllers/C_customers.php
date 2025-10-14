<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_customers extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_customers');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'List Customers';
		$data['load_grid']  = 'C_customers';
		$data['load_add']   = 'C_customers/add';
		$data['url_delete'] = 'C_customers/delete';
		$this->load->view("v_customers/grid_customers", $data);
	}
	public function griddata()
	{
		$start  = $this->input->get('start');
		$length = $this->input->get('length');
		$search = $this->input->get('search')['value'];
		$order  = $this->input->get('order')[0]['column'];
		$dir    = $this->input->get('order')[0]['dir'];

		$order_by = ['customer_id', 'company_name', 'name', 'email', 'phone', 'email', 'alamat', 'type', 'status', 'action'][$order];

		$data = $this->M_customers->get_paginated_ord_customer($length, $start, $search, $order_by, $dir);
		$total_records = $this->M_customers->count_all_ord_customer();
		$total_filtered = $this->M_customers->count_filtered_ord_customer($search);

		$url_edit   = 'C_customers/editform/';
		$url_delete = 'C_customers/hapusdata/';
		$load_grid  = 'C_customers/griddata';

		$result = [];
		$no = $start + 1; // biar sesuai pagination

		foreach ($data as $row) {

			// ===== BADGE STATUS =====
			$status_badge = '';
			if (strtolower($row->status) === 'active') {
				$status_badge = '<span class="badge bg-success">Aktif</span>';
			} elseif (strtolower($row->status) === 'inactive') {
				$status_badge = '<span class="badge bg-danger">Nonaktif</span>';
			} else {
				$status_badge = '<span class="badge bg-secondary">' . htmlspecialchars($row->status) . '</span>';
			}

			// ===== BADGE STAGE =====
			$stage_badge = '';
			if (strtolower($row->stage) === 'deal') {
				$stage_badge = '<span class="badge bg-success">Deal</span>';
			} elseif (strtolower($row->stage) === 'negotiation') {
				$stage_badge = '<span class="badge bg-warning">Negosiasi</span>';
			} else {
				$stage_badge = '<span class="badge bg-secondary">' . htmlspecialchars($row->status) . '</span>';
			}

			// ===== AKSI =====
			$aksi = '
		<div class="dropdown">
			<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->customer_id . '" data-bs-toggle="dropdown" aria-expanded="false">
				More <i class="bi-chevron-down ms-1"></i>
			</button>
			<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->customer_id . '">
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
				$no++, // kolom nomor urut
				$row->customer_id,
				$row->company_id . ' - '.htmlspecialchars($row->company_name),
				$row->customer_id . ' - ' . htmlspecialchars($row->name),
				htmlspecialchars($row->phone),
				htmlspecialchars($row->email),
				htmlspecialchars($row->address),
				$stage_badge,
				$status_badge,
				$aksi
			];
		}

		echo json_encode([
			"draw" => intval($this->input->get('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}



	function add()
	{
		$data['judul']     = "Form Tambah Customer";
		$data['load_refresh'] = 'C_customers/add';
		$data['load_back'] = 'C_customers';
		$this->load->view("v_customers/add_customers", $data);
	}
	public function simpandata()
	{
		$this->output->set_content_type('application/json');

		// Validasi input wajib
		$this->form_validation->set_rules('name', 'Nama Customer', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
		$this->form_validation->set_rules('phone', 'Nomor Telepon', 'trim');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => validation_errors()
			]);
			return;
		}

		// Ambil data dari form
		$name    = $this->input->post('name', TRUE);
		$company_id    = $this->input->post('company_id', TRUE);
		$email   = $this->input->post('email', TRUE);
		$phone   = $this->input->post('phone', TRUE);
		$address = $this->input->post('address', TRUE);
		$status  = $this->input->post('status', TRUE) ?: 'active';

		// 🔎 Cek duplikasi berdasarkan salah satu dari nama, email, atau nomor telepon
		$this->db->group_start()
			->where('name', $name)
			->or_where('email', $email)
			->or_where('phone', $phone)
			->group_end();

		$duplikat = $this->db->get('ord_customer')->num_rows();

		if ($duplikat > 0) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Nama, Email, atau Nomor Telepon sudah terdaftar.'
			]);
			return;
		}

		// Generate UUID pakai MySQL
		$uuid = $this->db->query("SELECT UUID() AS uuid")->row()->uuid;

		// Data untuk disimpan
		$dataInsert = [
			'uuid'       => $uuid,
			'name'       => $name,
			'email'      => $email,
			'phone'      => $phone,
			'address'    => $address,
			'status'     => $status,
			'company_id'       => $company_id,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		];

		// Jalankan dalam transaksi
		$this->db->trans_start();

		$this->db->insert('ord_customer', $dataInsert);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan saat menyimpan data.'
			]);
		} else {
			echo json_encode([
				'hasil' => 'true',
				'pesan' => 'Data customer berhasil disimpan.'
			]);
		}
	}
	public function editform($uuid)
{
	// Ambil data ord_customer + nama company
	$sql = "
		SELECT oc.*, c.name AS company_name
		FROM ord_customer oc
		LEFT JOIN companies c ON oc.company_id = c.code_company
		WHERE oc.uuid = ?
	";
	$data = $this->db->query($sql, [$uuid])->row();

	if ($data) {
		$this->load->view('v_customers/edit_customers', [
			'judul'        => 'Form Edit Customer',
			'load_back'    => 'C_customers',
			'load_refresh' => 'C_customers/editform/' . $uuid,
			'data'         => $data,
			'uuid'         => $uuid
		]);
	} else {
		$this->load->view('error');
	}
}


	// Fungsi untuk update data perusahaan
	public function updatedata()
	{
		$this->output->set_content_type('application/json');

		// Ambil data dari POST
		$uuid    = $this->input->post('uuid', TRUE);
		$name    = $this->input->post('name', TRUE);
		$company_id    = $this->input->post('company_id', TRUE);
		$email   = $this->input->post('email', TRUE);
		$phone   = $this->input->post('phone', TRUE);
		$address = $this->input->post('address', TRUE);
		$status  = $this->input->post('status', TRUE) ?: 'active';

		// Pastikan UUID dikirim
		if (empty($uuid)) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'UUID tidak ditemukan.'
			]);
			return;
		}

		// Cek apakah UUID ada di tabel
		$exists = $this->db->get_where('ord_customer', ['uuid' => $uuid])->num_rows() > 0;

		if (!$exists) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data customer tidak ditemukan.'
			]);
			return;
		}

		// 🔎 Cek duplikasi nama, email, atau phone selain dirinya sendiri
		$this->db->group_start()
			->where('name', $name)
			->or_where('email', $email)
			->or_where('phone', $phone)
			->group_end();
		$this->db->where('uuid !=', $uuid); // Jangan cek dirinya sendiri

		$duplikat = $this->db->get('ord_customer')->num_rows();

		if ($duplikat > 0) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Nama, Email, atau Nomor Telepon sudah digunakan oleh customer lain.'
			]);
			return;
		}

		// Siapkan data update
		$dataUpdate = [
			'name'       => $name,
			'email'      => $email,
			'phone'      => $phone,
			'address'    => $address,
			'status'     => $status,
			'company_id'       => $company_id,
			'updated_at' => date('Y-m-d H:i:s')
		];

		// Jalankan dalam transaksi
		$this->db->trans_start();
		$this->db->update('ord_customer', $dataUpdate, ['uuid' => $uuid]);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan saat memperbarui data.'
			]);
		} else {
			echo json_encode([
				'hasil' => 'true',
				'pesan' => 'Data customer berhasil diperbarui.'
			]);
		}
	}
	public function search()
	{
		$get_value = $this->input->get('getCustomers');
		$cari = trim($get_value);
	
		// Ambil semua kolom dari tabel
		$fields = $this->db->list_fields('ord_customer');
	
		// Mulai query
		$this->db->from('ord_customer');
		$this->db->where('status', 'active'); // hanya yang aktif
		$this->db->group_start();
	
		foreach ($fields as $i => $field) {
			if ($i === 0) {
				$this->db->like($field, $cari);
			} else {
				$this->db->or_like($field, $cari);
			}
		}
	
		$this->db->group_end();
	
		$hasil = $this->db->get()->result();
		echo json_encode($hasil);
	}
	

	public function search_deal()
	{
		$get_value = $this->input->get('getCustomers');
		$cari = trim($get_value);
	
		$fields = $this->db->list_fields('ord_customer');
	
		$this->db->from('ord_customer');
		$this->db->where('status', 'active'); // masih aktif
		$this->db->where('stage', 'deal');    // dan stage deal
		$this->db->group_start();
	
		foreach ($fields as $i => $field) {
			if ($i === 0) {
				$this->db->like($field, $cari);
			} else {
				$this->db->or_like($field, $cari);
			}
		}
	
		$this->db->group_end();
	
		$hasil = $this->db->get()->result();
		echo json_encode($hasil);
	}
	

	public function hapusdata()
	{
		$uuid = $this->input->post('uuid');

		// Validasi input
		if (empty($uuid)) {
			echo json_encode([
				'hasil' => false,
				'pesan' => 'UUID tidak boleh kosong'
			]);
			return;
		}

		// Mulai transaksi
		$this->db->trans_begin();

		try {
			// Pastikan data ada
			$customer = $this->db->get_where('ord_customer', ['uuid' => $uuid])->row();
			if (!$customer) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => false,
					'pesan' => 'Data customer tidak ditemukan'
				]);
				return;
			}

			// Hapus data berdasarkan UUID
			$this->db->where('uuid', $uuid)->delete('ord_customer');

			// Tangkap error MySQL (misal karena foreign key constraint)
			$error = $this->db->error();

			if ($error['code'] != 0) {
				// Gagal karena constraint
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => false,
					'pesan' => 'Gagal menghapus data. Kemungkinan masih digunakan di tabel lain.'
					// kalau mau tampilkan debug: ' (' . $error['message'] . ')'
				]);
				return;
			}

			// Jika tidak ada baris terhapus
			if ($this->db->affected_rows() <= 0) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => false,
					'pesan' => 'Data gagal dihapus atau tidak ditemukan'
				]);
				return;
			}

			// Commit transaksi
			$this->db->trans_commit();
			echo json_encode([
				'hasil' => 'true',
				'pesan' => 'Data berhasil dihapus'
			]);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi error: ' . $e->getMessage()
			]);
		}
	}
}
