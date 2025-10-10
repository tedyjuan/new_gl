<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_customer_budget extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_customer_budget');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'List Budget Offer';
		$data['load_grid']  = 'C_customer_budget';
		$data['load_add']   = 'C_customer_budget/add';
		$data['url_delete'] = 'C_customer_budget/delete';
		$this->load->view("v_customer_budget/grid_customer_budget", $data);
	}
	public function griddata()
	{
		$start  = $this->input->get('start');
		$length = $this->input->get('length');
		$search = $this->input->get('search')['value'];
		$order  = $this->input->get('order')[0]['column'];
		$dir    = $this->input->get('order')[0]['dir'];

		$order_by = ['budget_id', 'name', 'email', 'phone', 'email', 'alamat', 'type', 'status', 'action'][$order];

		$data = $this->M_customer_budget->get_paginated_ord_customer_budget($length, $start, $search, $order_by, $dir);
		$total_records = $this->M_customer_budget->count_all_ord_customer_budget();
		$total_filtered = $this->M_customer_budget->count_filtered_ord_customer_budget($search);

		$url_edit   = 'C_customer_budget/editform/';
		$url_delete = 'C_customer_budget/hapusdata/';
		$load_grid  = 'C_customer_budget/griddata';

		$result = [];
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
				$stage_badge = '<span class="badge bg-info">Negosiasi</span>';
			} else {
				$stage_badge = '<span class="badge bg-secondary">' . htmlspecialchars($row->status) . '</span>';
			}

			// ===== AKSI =====
			$aksi = '<div class="dropdown">
            <button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->budget_id . '" data-bs-toggle="dropdown" aria-expanded="false">
                More <i class="bi-chevron-down ms-1"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->budget_id . '">
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
				$row->budget_id,
				$row->name,
				$row->phone,
				$row->email,
				$row->address,
				$stage_badge,
				$status_badge,
				$aksi
			];
		}

		echo json_encode([
			"draw" => $_GET['draw'],
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}


	function add()
	{
		$data['judul']     = "Form Tambah Budget Offer";
		$data['load_refresh'] = 'C_customer_budget/add';
		$data['load_back'] = 'C_customer_budget';
		$this->load->view("v_customer_budget/add_customer_budget", $data);
	}
	public function simpandata()
	{
		$this->output->set_content_type('application/json');

		// Validasi minimal
		$this->form_validation->set_rules('customer_id', 'Customer', 'required|trim');
		$this->form_validation->set_rules('project_name', 'Nama Proyek', 'required|trim');
		$this->form_validation->set_rules('total_budget', 'Total Budget', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => validation_errors()
			]);
			return;
		}

		// Ambil data dari form
		$customer_id     = $this->input->post('customer_id', TRUE);
		$project_name    = $this->input->post('project_name', TRUE);
		$description     = $this->input->post('description', TRUE);
		$total_budget    = $this->input->post('total_budget', TRUE);
		$start_timeline  = $this->input->post('start_timeline', TRUE);
		$end_timeline    = $this->input->post('end_timeline', TRUE);
		$notes           = $this->input->post('notes', TRUE);
		$revised_version = $this->input->post('revised_version', TRUE) ?: 0;
		$revised_notes   = $this->input->post('revised_notes', TRUE);

		// Item dikirim via array JSON
		$items = $this->input->post('items'); // [{item_type, item_name, qty, ...}]
		$items = json_decode($items, true);

		if (empty($items) || !is_array($items)) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data item masih kosong.'
			]);
			return;
		}

		// Generate UUID
		$uuid = $this->db->query("SELECT UUID() AS uuid")->row()->uuid;

		// Data Header Budget
		$dataHeader = [
			'uuid'            => $uuid,
			'customer_id'     => $customer_id,
			'project_name'    => $project_name,
			'description'     => $description,
			'total_budget'    => $total_budget,
			'star_timeline'   => $start_timeline,
			'end_timeline'    => $end_timeline,
			'notes'           => $notes,
			'revised_version' => $revised_version,
			'revised_notes'   => $revised_notes,
			'created_at'      => date('Y-m-d H:i:s'),
			'updated_at'      => date('Y-m-d H:i:s'),
		];

		// Transaksi mulai
		$this->db->trans_start();

		// Insert ke Header
		$this->db->insert('ord_customer_budget', $dataHeader);
		$budget_id = $this->db->insert_id();

		// Insert ke Items
		foreach ($items as $row) {
			if (empty($row['item_name'])) continue;

			$dataItem = [
				'budget_id'       => $budget_id,
				'item_type'       => $row['item_type'] ?? '',
				'item_name'       => $row['item_name'] ?? '',
				'item_description' => $row['item_description'] ?? '',
				'qty'             => $row['qty'] ?? 0,
				'unit'            => $row['unit'] ?? '',
				'unit_price'      => $row['unit_price'] ?? 0,
				'notes'           => $row['notes'] ?? '',
				'created_at'      => date('Y-m-d H:i:s'),
				'updated_at'      => date('Y-m-d H:i:s')
			];

			$this->db->insert('ord_customer_budget_item', $dataItem);
		}

		// Transaksi selesai
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan saat menyimpan data.'
			]);
		} else {
			echo json_encode([
				'hasil' => 'true',
				'pesan' => 'Data Budget dan Item berhasil disimpan.'
			]);
		}
	}

	public function editform($uuid)
	{
		// Ambil data header berdasarkan UUID
		$dataHeader = $this->db->get_where('ord_customer_budget', ['uuid' => $uuid])->row();

		if (!$dataHeader) {
			$this->load->view('error');
			return;
		}

		// Ambil item-item terkait budget_id
		$dataItems = $this->db->get_where('ord_customer_budget_item', [
			'budget_id' => $dataHeader->budget_id
		])->result();

		// Load view edit
		$this->load->view('v_customer_budget/edit_customer_budget', [
			'judul'        => 'Edit Budget Offer',
			'load_back'    => 'C_customer_budget',
			'load_refresh' => 'C_customer_budget/editform/' . $uuid,
			'data'         => $dataHeader,
			'items'        => $dataItems,
			'uuid'         => $uuid
		]);
	}


	// Fungsi untuk update data perusahaan
	public function updatedata()
	{
		$this->output->set_content_type('application/json');

		// Validasi minimal
		$this->form_validation->set_rules('uuid', 'UUID', 'required');
		$this->form_validation->set_rules('project_name', 'Nama Proyek', 'required|trim');
		$this->form_validation->set_rules('total_budget', 'Total Budget', 'required|numeric');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => validation_errors()
			]);
			return;
		}

		// Ambil data dari POST
		$uuid            = $this->input->post('uuid', TRUE);
		$customer_id     = $this->input->post('customer_id', TRUE);
		$project_name    = $this->input->post('project_name', TRUE);
		$description     = $this->input->post('description', TRUE);
		$total_budget    = $this->input->post('total_budget', TRUE);
		$start_timeline  = $this->input->post('start_timeline', TRUE);
		$end_timeline    = $this->input->post('end_timeline', TRUE);
		$notes           = $this->input->post('notes', TRUE);
		$revised_version = $this->input->post('revised_version', TRUE) ?: 0;
		$revised_notes   = $this->input->post('revised_notes', TRUE);
		$items           = json_decode($this->input->post('items'), TRUE);

		// Pastikan UUID valid
		$budget = $this->db->get_where('ord_customer_budget', ['uuid' => $uuid])->row();
		if (!$budget) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data budget tidak ditemukan.'
			]);
			return;
		}

		// Siapkan data update untuk header
		$dataUpdate = [
			'customer_id'     => $customer_id,
			'project_name'    => $project_name,
			'description'     => $description,
			'total_budget'    => $total_budget,
			'star_timeline'   => $start_timeline,
			'end_timeline'    => $end_timeline,
			'notes'           => $notes,
			'revised_version' => $revised_version,
			'revised_notes'   => $revised_notes,
			'updated_at'      => date('Y-m-d H:i:s'),
		];

		// Jalankan dalam transaksi
		$this->db->trans_start();

		// Update header
		$this->db->update('ord_customer_budget', $dataUpdate, ['uuid' => $uuid]);

		// Hapus item lama
		$this->db->delete('ord_customer_budget_item', ['budget_id' => $budget->budget_id]);

		// Insert ulang item baru
		if (!empty($items) && is_array($items)) {
			foreach ($items as $row) {
				if (empty($row['item_name'])) continue;

				$dataItem = [
					'budget_id'        => $budget->budget_id,
					'item_type'        => $row['item_type'] ?? '',
					'item_name'        => $row['item_name'] ?? '',
					'item_description' => $row['item_description'] ?? '',
					'qty'              => $row['qty'] ?? 0,
					'unit'             => $row['unit'] ?? '',
					'unit_price'       => $row['unit_price'] ?? 0,
					'notes'            => $row['notes'] ?? '',
					'created_at'       => date('Y-m-d H:i:s'),
					'updated_at'       => date('Y-m-d H:i:s')
				];

				$this->db->insert('ord_customer_budget_item', $dataItem);
			}
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan saat memperbarui data.'
			]);
		} else {
			echo json_encode([
				'hasil' => 'true',
				'pesan' => 'Data Budget Offer berhasil diperbarui.'
			]);
		}
	}

	public function search()
	{
		$get_value = $this->input->get('getcustomer_budget');
		$cari = trim($get_value); // cukup trim biar spasi di ujung hilang

		// Ambil semua kolom dari tabel
		$fields = $this->db->list_fields('ord_customer_budget');

		// Mulai query
		$this->db->from('ord_customer_budget');
		$this->db->group_start(); // buka grup WHERE

		foreach ($fields as $i => $field) {
			if ($i === 0) {
				$this->db->like($field, $cari);
			} else {
				$this->db->or_like($field, $cari);
			}
		}

		$this->db->group_end(); // tutup grup WHERE

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
			$budget = $this->db->get_where('ord_customer_budget', ['uuid' => $uuid])->row();
			if (!$budget) {
				$this->db->trans_rollback();
				echo json_encode([
					'hasil' => false,
					'pesan' => 'Data budget tidak ditemukan'
				]);
				return;
			}

			// Hapus data berdasarkan UUID
			$this->db->where('uuid', $uuid)->delete('ord_customer_budget');

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
				'hasil' => true,
				'pesan' => 'Data berhasil dihapus'
			]);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => false,
				'pesan' => 'Terjadi error: ' . $e->getMessage()
			]);
		}
	}
}
