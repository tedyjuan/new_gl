<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class C_contract extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_contract');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'List Budget Offer';
		$data['load_grid']  = 'C_contract';
		$data['load_add']   = 'C_contract/add';
		$data['url_delete'] = 'C_contract/delete';
		$this->load->view("v_contract/grid_contract", $data);
	}
	public function griddata()
	{
		$start  = $this->input->get('start');
		$length = $this->input->get('length');
		$search = $this->input->get('search')['value'];
		$order  = $this->input->get('order')[0]['column'];
		$dir    = $this->input->get('order')[0]['dir'];

		// Urutan kolom sesuai tabel di view
		$columns = [
			'budget_id', 'project_name', 'description',
			'total_budget', 'start_timeline', 'end_timeline',
			'status', 'path_pdf', 'path_archive', 'created_at'
		];
		$order_by = $columns[$order] ?? 'created_at';

		// Ambil data dari model
		$data = $this->M_contract->get_paginated_ord_customer_budget($length, $start, $search, $order_by, $dir);
		$total_records = $this->M_contract->count_all_ord_customer_budget();
		$total_filtered = $this->M_contract->count_filtered_ord_customer_budget($search);

		// URL helper
		$url_edit   = 'C_contract/editform/';
		$url_delete = 'C_contract/hapusdata/';
		$url_status = 'C_contract/editform_status/';
		$url_detail = 'C_contract/editform_detail/';
		$load_grid  = 'C_contract/griddata';

		$result = [];
		$no = $start + 1;

		foreach ($data as $row) {

			// ===== BADGE STATUS =====
			if (strtolower($row->status ?? '') === 'approved') {
				$status_badge = '<span class="badge bg-success">Approved</span>';
			} elseif (strtolower($row->status ?? '') === 'pending') {
				$status_badge = '<span class="badge bg-warning text-dark">Pending</span>';
			} elseif (strtolower($row->status ?? '') === 'rejected') {
				$status_badge = '<span class="badge bg-danger">Rejected</span>';
			} else {
				$status_badge = '<span class="badge bg-secondary">' . htmlspecialchars($row->status ?? '-') . '</span>';
			}

			// ===== FORMAT UANG =====
			$total_budget = 'Rp ' . number_format((float)$row->total_budget, 0, ',', '.');

			// ===== FORMAT TANGGAL =====
			$timeline = '-';
			if (!empty($row->start_timeline) && !empty($row->end_timeline)) {
				$timeline = date('d/m/Y', strtotime($row->start_timeline)) . ' - ' . date('d/m/Y', strtotime($row->end_timeline));
			}

			// ===== FILE DOWNLOAD BUTTONS =====
			$btn_pdf = $row->path_pdf
				? '<a href="' . base_url($row->path_pdf) . '" class="btn btn-sm btn-outline-primary m-1" target="_blank" title="Download PDF"><i class="bi bi-file-earmark-pdf"></i></a>'
				: '<button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-file-earmark-pdf"></i></button>';

			$btn_archive = $row->path_archive
				? '<a href="' . base_url($row->path_archive) . '" class="btn btn-sm btn-outline-secondary m-1" target="_blank" title="Download Arsip"><i class="bi bi-archive"></i></a>'
				: '<button class="btn btn-sm btn-outline-secondary" disabled><i class="bi bi-archive"></i></button>';

			// ===== CEK STATUS APPROVED =====
			$isApproved = strtolower($row->status ?? '') === 'approved';

			// ===== AKSI BUTTON =====
			$aksi = '
			<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm dropdown-toggle" data-bs-toggle="dropdown">
					More
				</button>
				<div class="dropdown-menu dropdown-menu-end">';

			if ($isApproved) {
				// kalau udah approved -> cuma boleh lihat detail
				$aksi .= '
					<button class="dropdown-item" onclick="editform(\'' . $url_detail . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-eye"></i> Detail
					</button>
				';
			} else {
				// kalau belum approved -> semua opsi aktif
				$aksi .= '
					<button class="dropdown-item" onclick="editform(\'' . $url_status . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-clipboard-check"></i> Edit Status
					</button>
					<button class="dropdown-item" onclick="editform(\'' . $url_edit . '\', \'' . $row->uuid . '\')">
					<i class="bi bi-pencil"></i> Edit
					</button>
					<button class="dropdown-item" onclick="editform(\'' . $url_detail . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-eye"></i> Detail
					</button>
					<div class="dropdown-divider"></div>
					<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
						<i class="bi bi-trash3"></i> Hapus
					</button>
				';
			}

			$aksi .= '</div></div>';

			// ===== SUSUN DATA PER ROW =====
			$result[] = [
				$no++,
				htmlspecialchars($row->budget_id),
				htmlspecialchars($row->project_name),
				htmlspecialchars(($row->customer_id ?? '') . ' - ' . ($row->customer_name ?? '-')),
				$total_budget,
				$timeline,
				$status_badge,
				$btn_pdf . ' ' . $btn_archive,
				$aksi
			];
		}

		// === OUTPUT JSON UNTUK DATATABLE ===
		echo json_encode([
			"draw" => intval($this->input->get('draw')),
			"recordsTotal" => $total_records,
			"recordsFiltered" => $total_filtered,
			"data" => $result
		]);
	}




	function add()
	{
		$data['judul']     = "Form Tambah Budget Offer";
		$data['load_refresh'] = 'C_contract/add';
		$data['load_back'] = 'C_contract';
		$this->load->view("v_contract/add_contract", $data);
	}
	public function simpandata()
	{
		$this->output->set_content_type('application/json');

		// Validasi form basic
		$this->form_validation->set_rules('customer_id', 'Customer', 'required|trim');
		$this->form_validation->set_rules('project_name', 'Nama Proyek', 'required|trim');
		$this->form_validation->set_rules('total_budget', 'Total Budget', 'required|trim');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => validation_errors()
			]);
			return;
		}

		// Ambil data form header
		$customer_id     = $this->input->post('customer_id', TRUE);
		$project_name    = $this->input->post('project_name', TRUE);
		$description     = $this->input->post('description', TRUE);
		$total_budget    = str_replace('.', '', $this->input->post('total_budget', TRUE));
		$start_timeline_post = $this->input->post('start_timeline', TRUE);
		$end_timeline_post   = $this->input->post('end_timeline', TRUE);

		$start_timeline = !empty($start_timeline_post)
			? date('Y-m-d', strtotime(str_replace('/', '-', $start_timeline_post)))
			: null;

		$end_timeline = !empty($end_timeline_post)
			? date('Y-m-d', strtotime(str_replace('/', '-', $end_timeline_post)))
			: null;

		$notes           = $this->input->post('notes', TRUE);
		$revised_version = $this->input->post('revised_version', TRUE) ?: 0;
		$revised_notes   = $this->input->post('revised_notes', TRUE);

		$item_type  = $this->input->post('item_type');
		$item_name  = $this->input->post('item_name');
		$qty        = $this->input->post('qty');
		$unit       = $this->input->post('unit');
		$unit_price = $this->input->post('unit_price');
		$notes_item = $this->input->post('notes_item');

		if (empty($item_type) || !is_array($item_type)) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data item masih kosong.'
			]);
			return;
		}

		// Generate UUID untuk header
		$uuid = $this->db->query("SELECT UUID() AS uuid")->row()->uuid;

		$dataHeader = [
			'uuid'            => $uuid,
			'customer_id'     => $customer_id,
			'project_name'    => $project_name,
			'description'     => $description,
			'total_budget'    => $total_budget,
			'start_timeline'  => $start_timeline,
			'end_timeline'    => $end_timeline,
			'notes'           => $notes,
			'revised_version' => $revised_version,
			'revised_notes'   => $revised_notes,
			'created_at'      => date('Y-m-d H:i:s'),
			'updated_at'      => date('Y-m-d H:i:s'),
		];

		$this->db->trans_begin();
		$this->db->insert('ord_customer_budget', $dataHeader);
		$budget_id = $this->db->insert_id();

		// Insert detail items
		for ($i = 0; $i < count($item_type); $i++) {
			if (empty($item_type[$i])) continue;

			$dataItem = [
				'budget_id'  => $budget_id,
				'item_type'  => $item_type[$i] ?? '',
				'item_name'  => $item_name[$i] ?? '',
				'qty'        => str_replace('.', '', $qty[$i]) ?? 0,
				'unit'       => $unit[$i] ?? '',
				'unit_price' => str_replace('.', '', $unit_price[$i]) ?? 0,
				'notes_item' => $notes_item[$i] ?? '',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			];

			$this->db->insert('ord_customer_budget_item', $dataItem);
		}

		// Cek transaksi
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan saat menyimpan data.'
			]);
			return;
		}

		// Commit dulu baru generate PDF
		$this->db->trans_commit();

		// === 🔥 Auto Generate Proposal PDF ===
		$dataHeader = $this->db->select('
			b.*, 
			c.name AS customer_name, 
			c.address, 
			c.phone, 
			c.email, 
			comp.name AS company_name,
			comp.phone AS company_phone,
			comp.email AS company_email,
			comp.address AS company_address,
			comp.owner_name AS company_owner

			')
			->from('ord_customer_budget b')
			->join('ord_customer c', 'b.customer_id = c.customer_id', 'left')
			->join('companies comp', 'c.company_id = comp.code_company', 'left')
			->where('b.uuid', $uuid)
			->get()
			->row();


		$dataItem = $this->db->get_where('ord_customer_budget_item', ['budget_id' => $budget_id])->result();

		$html = $this->load->view('v_contract/pdf_contract', [
			'dataHeader' => $dataHeader,
			'dataItem'   => $dataItem
		], true);

		$this->load->library('pdf');
		$filename = 'proposal_' . $uuid . '.pdf';
		$path = FCPATH . 'generate_pdf/proposals/' . $filename;

		if (!is_dir(FCPATH . 'generate_pdf/proposals')) {
			mkdir(FCPATH . 'generate_pdf/proposals', 0777, true);
		}

		// Simpan ke server
		$options = new Options();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isRemoteEnabled', true);
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		file_put_contents($path, $dompdf->output());

		// Update path ke DB
		$this->db->where('budget_id', $budget_id)->update('ord_customer_budget', [
			'path_pdf' => 'generate_pdf/proposals/' . $filename
		]);

		echo json_encode([
			'hasil' => 'true',
			'pesan' => 'Data Budget dan Item berhasil disimpan, proposal berhasil digenerate.',
			'proposal_url' => base_url('generate_pdf/proposals/' . $filename)
		]);
	}


	public function editform($uuid)
	{
		// Ambil data header berdasarkan UUID
		$dataHeader = $this->db
			->select('b.*, c.name AS customer_name') // ambil semua kolom dari budget + nama customer
			->from('ord_customer_budget b')
			->join('ord_customer c', 'b.customer_id = c.customer_id', 'left')
			->where('b.uuid', $uuid)
			->get()
			->row();

		if (!$dataHeader) {
			$this->load->view('error');
			return;
		}


		// Ambil item-item terkait budget_id
		$dataItems = $this->db->get_where('ord_customer_budget_item', [
			'budget_id' => $dataHeader->budget_id
		])->result();

		// Load view edit
		$this->load->view('v_contract/edit_contract', [
			'judul'        => 'Edit Budget Offer',
			'load_back'    => 'C_contract',
			'load_refresh' => 'C_contract/editform/' . $uuid,
			'data'         => $dataHeader,
			'items'        => $dataItems,
			'uuid'         => $uuid
		]);
	}

	public function editform_detail($uuid)
	{
		// Ambil data header berdasarkan UUID
		$dataHeader = $this->db
			->select('b.*, c.name AS customer_name') // ambil semua kolom dari budget + nama customer
			->from('ord_customer_budget b')
			->join('ord_customer c', 'b.customer_id = c.customer_id', 'left')
			->where('b.uuid', $uuid)
			->get()
			->row();

		if (!$dataHeader) {
			$this->load->view('error');
			return;
		}


		// Ambil item-item terkait budget_id
		$dataItems = $this->db->get_where('ord_customer_budget_item', [
			'budget_id' => $dataHeader->budget_id
		])->result();

		// Load view edit
		$this->load->view('v_contract/detail_contract', [
			'judul'        => 'Edit Budget Offer',
			'load_back'    => 'C_contract',
			'load_refresh' => 'C_contract/editform_detail/' . $uuid,
			'data'         => $dataHeader,
			'items'        => $dataItems,
			'uuid'         => $uuid
		]);
	}

	public function editform_status($uuid)
	{
		// Ambil data header berdasarkan UUID
		$dataHeader = $this->db
			->select('b.*, c.name AS customer_name') // ambil semua kolom dari budget + nama customer
			->from('ord_customer_budget b')
			->join('ord_customer c', 'b.customer_id = c.customer_id', 'left')
			->where('b.uuid', $uuid)
			->get()
			->row();

		if (!$dataHeader) {
			$this->load->view('error');
			return;
		}



		// Load view edit
		$this->load->view('v_contract/edit_status_contract', [
			'judul'        => 'Edit Status Budget Offer',
			'load_back'    => 'C_contract',
			'load_refresh' => 'C_contract/editform_status/' . $uuid,
			'data'         => $dataHeader,
			'uuid'         => $uuid
		]);
	}




	public function updatedata()
	{
		$this->output->set_content_type('application/json');

		// === Validasi form basic ===
		$this->form_validation->set_rules('customer_id', 'Customer', 'required|trim');
		$this->form_validation->set_rules('project_name', 'Nama Proyek', 'required|trim');
		$this->form_validation->set_rules('total_budget', 'Total Budget', 'required|trim');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => validation_errors()
			]);
			return;
		}

		// === Ambil data form header ===
		$uuid            = $this->input->post('uuid', TRUE);
		$customer_id     = $this->input->post('customer_id', TRUE);
		$project_name    = $this->input->post('project_name', TRUE);
		$description     = $this->input->post('description', TRUE);
		$total_budget    = str_replace('.', '', $this->input->post('total_budget', TRUE));
		$notes           = $this->input->post('notes', TRUE);
		$revised_notes   = $this->input->post('revised_notes', TRUE);

		// === Ambil array item ===
		$item_type  = $this->input->post('item_type');
		$item_name  = $this->input->post('item_name');
		$qty        = $this->input->post('qty');
		$unit       = $this->input->post('unit');
		$unit_price = $this->input->post('unit_price');
		$notes_item = $this->input->post('notes_item');

		if (empty($item_type) || !is_array($item_type)) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data item masih kosong.'
			]);
			return;
		}

		// === Konversi tanggal ===
		$start_timeline_post = $this->input->post('start_timeline', TRUE);
		$end_timeline_post   = $this->input->post('end_timeline', TRUE);

		$start_timeline = !empty($start_timeline_post)
			? date('Y-m-d', strtotime(str_replace('/', '-', $start_timeline_post)))
			: null;

		$end_timeline = !empty($end_timeline_post)
			? date('Y-m-d', strtotime(str_replace('/', '-', $end_timeline_post)))
			: null;

		// === Ambil data lama ===
		$budget = $this->db->get_where('ord_customer_budget', ['uuid' => $uuid])->row();
		if (!$budget) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan.'
			]);
			return;
		}

		$budget_id = $budget->budget_id;
		$old_path  = !empty($budget->path_pdf) ? FCPATH . $budget->path_pdf : null;

		// === Hitung versi revisi baru ===
		$new_revised_version = ($budget->revised_version ?? 0) + 1;

		// === Mulai transaksi ===
		$this->db->trans_begin();

		// === Update header ===
		$dataHeader = [
			'customer_id'     => $customer_id,
			'project_name'    => $project_name,
			'description'     => $description,
			'total_budget'    => $total_budget,
			'start_timeline'  => $start_timeline,
			'end_timeline'    => $end_timeline,
			'notes'           => $notes,
			'revised_version' => $new_revised_version,
			'revised_notes'   => $revised_notes,
			'updated_at'      => date('Y-m-d H:i:s'),
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('ord_customer_budget', $dataHeader);

		// === Hapus detail lama ===
		$this->db->delete('ord_customer_budget_item', ['budget_id' => $budget_id]);

		// === Insert ulang detail item ===
		for ($i = 0; $i < count($item_type); $i++) {
			if (empty($item_type[$i])) continue;

			$dataItem = [
				'budget_id'  => $budget_id,
				'item_type'  => $item_type[$i] ?? '',
				'item_name'  => $item_name[$i] ?? '',
				'qty'        => str_replace('.', '', $qty[$i]) ?? 0,
				'unit'       => $unit[$i] ?? '',
				'unit_price' => str_replace('.', '', $unit_price[$i]) ?? 0,
				'notes_item' => $notes_item[$i] ?? '',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			];

			$this->db->insert('ord_customer_budget_item', $dataItem);
		}

		// === Commit sebelum PDF ===
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan saat update data.'
			]);
			return;
		}

		$this->db->trans_commit();

		// === Hapus file lama ===
		if ($old_path && file_exists($old_path)) {
			unlink($old_path);
		}

		// === Generate PDF baru ===
		$dataHeader = $this->db->select('
		b.*, 
		c.name AS customer_name, 
		c.address, 
		c.phone, 
		c.email, 
		comp.name AS company_name,
		comp.phone AS company_phone,
		comp.email AS company_email,
		comp.address AS company_address,
		comp.owner_name AS company_owner

		')
			->from('ord_customer_budget b')
			->join('ord_customer c', 'b.customer_id = c.customer_id', 'left')
			->join('companies comp', 'c.company_id = comp.code_company', 'left')
			->where('b.uuid', $uuid)
			->get()
			->row();

		$dataItem = $this->db->get_where('ord_customer_budget_item', ['budget_id' => $budget_id])->result();

		$html = $this->load->view('v_contract/pdf_contract', [
			'dataHeader' => $dataHeader,
			'dataItem'   => $dataItem
		], true);

		$this->load->library('pdf');

		// Path PDF baru
		$filename = 'proposal_' . $uuid . '.pdf';
		$pathDir  = FCPATH . 'generate_pdf/proposals/';
		$path     = $pathDir . $filename;

		if (!is_dir($pathDir)) {
			mkdir($pathDir, 0777, true);
		}

		$options = new Options();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isRemoteEnabled', true);
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		file_put_contents($path, $dompdf->output());

		// === Update path PDF + versi revisi ke DB ===
		$this->db->where('budget_id', $budget_id)->update('ord_customer_budget', [
			'path_pdf' => 'generate_pdf/proposals/' . $filename,
			'updated_at' => date('Y-m-d H:i:s')
		]);

		echo json_encode([
			'hasil' => 'true',
			'pesan' => 'Data berhasil diupdate. Proposal versi ' . $new_revised_version . ' berhasil digenerate.',
			'proposal_url' => base_url('generate_pdf/proposals/' . $filename),
			'revised_version' => $new_revised_version,
			'revised_notes' => $revised_notes
		]);
	}

	public function updatedata_status()
	{
		$this->output->set_content_type('application/json');

		// === Validasi basic form ===
		$this->form_validation->set_rules('uuid', 'UUID', 'required|trim');
		$this->form_validation->set_rules('status', 'Status', 'required|trim');

		if ($this->form_validation->run() === FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => validation_errors()
			]);
			return;
		}

		// === Ambil data dari POST ===
		$uuid         = $this->input->post('uuid', TRUE);
		$status       = $this->input->post('status', TRUE);
		$status_notes = $this->input->post('status_notes', TRUE);

		// === Ambil data lama ===
		$budget = $this->db->get_where('ord_customer_budget', ['uuid' => $uuid])->row();
		if (!$budget) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan.'
			]);
			return;
		}

		$old_path  = !empty($budget->path_archive) ? FCPATH . $budget->path_archive : null;

		// === Folder tujuan upload ===
		$pathDir = FCPATH . 'generate_pdf/proposals_archive/';
		if (!is_dir($pathDir)) {
			mkdir($pathDir, 0777, true);
		}

		$path_archive = $budget->path_archive; // default path lama
		$uploadError  = '';

		// === Cek & proses upload file baru ===
		if (!empty($_FILES['file_archive']['name'])) {

			$config['upload_path']   = $pathDir;
			$config['allowed_types'] = 'jpg|jpeg|png|pdf';
			$config['max_size']      = 5120; // 5MB
			$config['file_name']     = 'archive_' . $uuid . '_' . time();

			$this->load->library('upload', $config);

			if ($this->upload->do_upload('file_archive')) {
				$uploadData   = $this->upload->data();
				$filename     = $uploadData['file_name'];
				$path_archive = 'generate_pdf/proposals_archive/' . $filename;

				// hapus file lama kalau ada
				if ($old_path && file_exists($old_path)) {
					unlink($old_path);
				}
			} else {
				$uploadError = $this->upload->display_errors('', '');
			}
		}

		if ($uploadError !== '') {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Gagal upload file: ' . $uploadError
			]);
			return;
		}

		// === Mulai transaksi ===
		$this->db->trans_begin();

		// === Update header ===
		$dataHeader = [
			'status'        => $status,
			'status_notes'  => $status_notes,
			'path_archive'  => $path_archive,
			'updated_at'    => date('Y-m-d H:i:s'),
			'status_at'    => date('Y-m-d H:i:s'),

		];

		$this->db->where('uuid', $uuid);
		$this->db->update('ord_customer_budget', $dataHeader);

		if ($status == 'approved') {
			$dataCustomer = [
				'stage'        => 'deal',
				'updated_at'    => date('Y-m-d H:i:s'),

			];

			$this->db->where('customer_id', $budget->customer_id);
			$this->db->update('ord_customer', $dataCustomer);
		} else {
			if ($old_path && file_exists($old_path)) {
				unlink($old_path);
			}

			$dataHeader = [
				'path_archive'  => '',
			];

			$this->db->where('uuid', $uuid);
			$this->db->update('ord_customer_budget', $dataHeader);
		}
		// === Commit / rollback ===
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan saat update status.'
			]);
			return;
		}

		$this->db->trans_commit();

		echo json_encode([
			'hasil' => 'true',
			'pesan' => 'Status proposal berhasil diperbarui.',
			'status' => $status,
			'status_notes' => $status_notes,
			'path_archive' => base_url($path_archive)
		]);
	}





	public function search()
	{
		$get_value = $this->input->get('getcontract');
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

	/**
	 * Hapus file proposal (PDF) dari server kalau ada
	 *
	 * @param object $budget Data budget dari database
	 * @return void
	 */
	private function _hapus_file_proposal($budget)
	{
		// Pastikan kolom path_pdf ada dan tidak kosong
		if (!empty($budget->path_pdf)) {
			$file_path = FCPATH . $budget->path_pdf;

			if (file_exists($file_path)) {
				if (@unlink($file_path)) {
					log_message('info', 'File proposal dihapus: ' . $file_path);
				} else {
					log_message('error', 'Gagal menghapus file proposal: ' . $file_path);
				}
			} else {
				log_message('debug', 'File proposal tidak ditemukan: ' . $file_path);
			}
		}
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

			//  Panggil private function buat hapus file PDF proposal-nya
			$this->_hapus_file_proposal($budget);

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
