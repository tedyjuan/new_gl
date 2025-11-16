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
		$data['judul']      = 'List Petty Cash';
		$data['load_grid']  = 'C_petty_cash';
		$data['load_add']   = 'C_petty_cash/add';
		$data['url_delete'] = 'C_petty_cash/delete';
		$this->load->view("v_petty_cash/grid_petty_cash", $data);
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
		$columns        = ['voucher_no', 'trans_date', 'proveniance', 'flow', 'status'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_petty_cash->get_paginated_petty_cash($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_petty_cash->count_all_petty_cash();
		$total_filtered = $this->M_petty_cash->count_filtered_petty_cash($search);
		$url_detail   = 'C_petty_cash/detailform';
		$url_delete = 'C_petty_cash/hapusdata';
		$load_grid  = 'C_petty_cash/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->voucher_no . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->voucher_no . '">
					<button class="dropdown-item editbtn" onclick="editform(\'' . $url_detail . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-eye"></i> Detail
					</button>
					<div class="dropdown-divider"></div>
					<button class="dropdown-item text-danger" onclick="hapus(\'' . $row->uuid . '\', \'' . $url_delete . '\', \'' . $load_grid . '\')">
						<i class="bi bi-trash3"></i> Void
					</button>
				</div>
			</div>';
			if ($row->status == 'APPLIED') {
				$class = 'bg-success';
			} else if ($row->status == 'VOID') {
				$class = 'bg-danger';
			}
			$ststus = '<span class="badge ' . $class . '">' . strtolower($row->status) . '</span>';
			$result[] = [
				$row->voucher_no,
				$row->trans_date,
				$row->proveniance,
				$row->flow,
				$ststus,
				$aksi,
			];
		}
		echo json_encode([
			"draw"            => intval($this->input->post('draw')) ?? 1,
			"recordsTotal"    => $total_records,
			"recordsFiltered" => $total_filtered,
			"data"            => $result
		]);
	}
	function add()
	{
		$data['judul']     = "Add Petty Cash";
		$data['load_back'] = 'C_petty_cash/add';
		$data['load_grid'] = 'C_petty_cash';
		$data['counter'] = $this->M_global->preview_code('PC');
		$this->load->view("v_petty_cash/add_petty_cash", $data);
	}
	public function simpandata()
	{
		// Ambil data JSON dari request body
		$data        = json_decode($this->input->raw_input_stream, true);
		$lineItems   = isset($data['lineItems']) ? $data['lineItems'] : [];
		$bankDetails = isset($data['bankDetails']) ? $data['bankDetails'] : [];
		$voucherNo = $this->M_global->generate_code("PC");
		// ====== Siapkan data header ======
		$header = [
			'uuid'        => $this->uuid->v4(),
			'voucher_no'  => $voucherNo,
			'trans_date'  => $data['date'],
			'proveniance' => $data['proveniance'],
			'flow'        => $data['flow'],
			'created_at'  => date('Y-m-d H:i:s'),
			'user_at'     => $this->session->userdata('sess_username'),
		];

		// ====== Siapkan data line items ======
		$data_item = [];
		if (!empty($lineItems)) {
			foreach ($lineItems as $key => $item) {
				$data_item[] = [
					'uuid'        => $this->uuid->v4(),
					'voucher_no'  => $voucherNo,
					'account_no'  => $item['accountNo'],
					'description' => $item['description'],
					'debit'       => $item['debit'],
					'credit'      => $item['credit'],
					'item_number' => $key + 1,
				];
			}
		}

		// ====== Siapkan data bank details ======
		$data_bank = [];
		if (!empty($bankDetails)) {
			foreach ($bankDetails as $keys => $item_bank) {
				$data_bank[] = [
					'uuid'        => $this->uuid->v4(),
					'voucher_no'  => $voucherNo,
					'account_no'  => $item_bank['accountNo'],
					'bank_name'   => $item_bank['bankName'],
					'trans_date'  => $item_bank['dueDate'],
					'item_number' => $keys + 1,
				];
			}
		}

		// ==============================================================
		// MULAI TRANSAKSI
		// ==============================================================
		$this->db->trans_begin();

		try {
			// Insert header
			$this->db->insert('petty_cash_headers', $header);

			// Insert batch line items jika ada
			if (!empty($data_item)) {
				$this->db->insert_batch('petty_cash_itemprices', $data_item);
			}

			// Insert batch bank details jika ada
			if (!empty($data_bank)) {
				$this->db->insert_batch('petty_cash_banks', $data_bank);
			}

			// ==============================================================
			// CEK STATUS TRANSAKSI
			// ==============================================================
			if ($this->db->trans_status() === FALSE) {
				// Rollback jika ada error
				$this->db->trans_rollback();
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Gagal menyimpan data. Transaksi dibatalkan.' ,
					];
				echo json_encode($jsonmsg);
			} else {
				// Commit jika semua sukses
				$this->db->trans_commit();
				$jsonmsg = [
					'hasil' => 'true',
					'pesan' =>  'Data saved successfully.',
				];
				echo json_encode($jsonmsg);
			}
		} catch (Exception $e) {
			// Tangani error tak terduga
			$this->db->trans_rollback();
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan: ' . $e->getMessage(),
			];
			echo json_encode($jsonmsg);
		}

	}

	public function detailform($uuid)
	{
		$cekdata =  $this->M_petty_cash->get_where_petty_cash(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$param                = ['voucher_no' => $cekdata->voucher_no];
			$data['list_bank']    = $this->M_petty_cash->get_item_akun_bank($cekdata->voucher_no);
			$data['list_item']    = $this->M_petty_cash->get_item_debitcredit($cekdata->voucher_no);
			$data['amount']       = $this->M_petty_cash->get_amount($cekdata->voucher_no);
			$data['judul']        = "Detail Petty Cash";
			$data['load_grid']    = 'C_petty_cash';
			$data['load_refresh'] = "C_petty_cash/detailform/" . $uuid;
			$data['uuid']         = $uuid;
			$data['data']         = $cekdata;
			$this->load->view("v_petty_cash/detail_petty_cash", $data);
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
