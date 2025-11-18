<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_journal_entry extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_journal_entry');
		$this->load->model('M_global');
		$this->name = $this->session->userdata('sess_name');
		$this->company = $this->session->userdata('sess_company');
	}
	function index()
	{
		$data['judul']      = 'Journal Entry';
		$data['load_grid']  = 'C_journal_entry';
		$data['load_add']   = 'C_journal_entry/add';
		$data['url_delete'] = 'C_journal_entry/delete';
		$this->load->view("v_journal_entry/grid_journal_entry", $data);
	}
	public function griddata()
	{
		$start          = $this->input->post('start') ?? 0;
		$length         = $this->input->post('length') ?? 10;
		$search_input   = $this->input->post('search');
		$search         = isset($search_input['value']) ? $search_input['value'] : '';
		$order_input    = $this->input->post('order');
		$order_col      = isset($order_input[0]['column']) ? $order_input[0]['column'] : 0;
		$dir            = isset($order_input[0]['dir']) ? $order_input[0]['dir'] : 'desc';
		$columns        = ['transaction_date','code_journal_source', 'batch_number', 'voucher_number', 'action'];
		$order_by       = $columns[$order_col] ?? 'name';
		$data           = $this->M_journal_entry->get_paginated_journal_entry($length, $start, $search, $order_by, $dir);
		$total_records  = $this->M_journal_entry->count_all_journal_entry();
		$total_filtered = $this->M_journal_entry->count_filtered_journal_entry($search);
		$url_detail       = 'C_journal_entry/detailform';
		$url_edit       = 'C_journal_entry/editform';
		$url_delete     = 'C_journal_entry/hapusdata';
		$load_grid      = 'C_journal_entry/griddata';
		$result = [];
		foreach ($data as $row) {
			$aksi = '<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->batch_number . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->batch_number . '">
					<button class="dropdown-item editbtn" onclick="editform(\'' . $url_detail . '\', \'' . $row->uuid . '\')">
						<i class="bi bi-eye"></i> Detail
					</button>
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
				$row->transaction_date,
				$row->code_journal_source .' - '.$row->journal_source_name,
				$row->batch_number,
				$row->voucher_number,
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
		$data['judul']     = "Add Journal Entry";
		$data['load_back'] = 'C_journal_entry/add';
		$data['load_grid'] = 'C_journal_entry';
		$data['code_company'] = $this->session->userdata('sess_company');

		$param = [
			'code_company' => $this->session->userdata('sess_company'),
		];
		$data['depos'] = $this->M_global->getWhere('depos', $param)->result();
		$data['journal_sources'] = $this->M_global->getWhere('journal_sources', $param)->result();
		$this->load->view("v_journal_entry/add_journal_entry", $data);
	}
	public function Costcenter_all()
	{
		$get_value = $this->input->get('cari');
		$cari = preg_replace("/[^a-zA-Z0-9]/", '', $get_value);
		$hasil = $this->M_journal_entry->get_cc($cari);
		echo json_encode($hasil);
	}
	public function generate_code_journal()
	{
		$batch_code = $this->input->post('batch_type');
		$date       = $this->input->post('batch_date');
		$branch     = $this->input->post('branch');
		$hasil      = $this->M_journal_entry->preview_code_journal($batch_code,  $date, $branch);
		echo json_encode($hasil);
	}

	public function simpandata()
	{
		$post        = json_decode($this->input->raw_input_stream, true);
		$line_items  = $post['lineItems'];
		$branch      = $post['branch'];
		$batch_type  = $post['batch_type'];
		$batch_date  = $post['batch_date'];
		$des_header  = $post['des_header'];

		// generate counter
		$counter      = $this->M_journal_entry->preview_code_journal($batch_type, $batch_date, $branch);
		$current_year = date('Y', strtotime($batch_date));
		$month_year   = date('m', strtotime($batch_date));

		// sequence
		$parts       = explode("/", $counter);
		$seq         = intval($parts[2]);

		// =================================
		// BUILD JOURNAL ITEMS (DETAIL)
		// =================================
		$journal_items = [];
		$total_debit   = 0;
		$total_credit  = 0;

		foreach ($line_items as $key => $item) {

			// amankan debit
			$debit  = isset($item['debit']) && $item['debit'] !== ''
				? (int) str_replace('.', '', $item['debit'])
				: 0;

			// amankan credit
			$credit = isset($item['credit']) && $item['credit'] !== ''
				? (int) str_replace('.', '', $item['credit'])
				: 0;

			// akumulasi total debit/credit
			$total_debit  += $debit;
			$total_credit += $credit;

			// push item
			$journal_items[] = [
				'uuid'             => $this->uuid->v4(),
				'sequence_number'  => $key + 1,
				'batch_number'     => $counter,
				'code_cost_center' => $item['cost_center'],
				'code_coa'         => $item['accountNo'],
				'description'      => $item['description'],
				'debit'            => $debit,
				'credit'           => $credit,
				'transaction_date' => $batch_date,
				'created_at'       => date('Y-m-d H:i:s'),
				'updated_at'       => date('Y-m-d H:i:s'),
			];
		}

		// hitung difference
		$difference = $total_debit - $total_credit;

		// =================================
		// BUILD JOURNAL HEADER
		// =================================
		$journal_header = [
			'uuid'                => $this->uuid->v4(),
			'sequence'            => $seq,
			'batch_number'        => $counter,
			'voucher_number'      => $counter,
			'code_depo'           => $branch,
			'code_journal_source' => $batch_type,
			'description'         => $des_header,
			'status'              => 'unposted',
			'year'                => $current_year,
			'period'              => $month_year,
			'transaction_date'    => $batch_date,
			'total_credit'        => $total_credit,
			'total_debit'         => $total_debit,
			'difference'          => $difference,
			'user_create'         => $this->name,
			'code_company'        => $this->company,
			'created_at'          => date('Y-m-d H:i:s'),
			'updated_at'          => date('Y-m-d H:i:s'),
		];

		// =============================================
		// =============== BEGIN TRANSACTION ============
		// =============================================
		$this->db->trans_begin();

		// insert header
		$this->db->insert('journals', $journal_header);

		// insert detail (batch)
		$this->db->insert_batch('journal_items', $journal_items);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'saving data failed',
			]);
			return;
		}

		$this->db->trans_commit();
		echo json_encode([
			'hasil' => 'true',
			'pesan' => 'Successfully saved data',
		]);
	}
	public function hapusdata()
	{
		$uuid = $this->input->post('uuid');

		if (empty($uuid)) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'UUID cannot be empty'
			]);
			return;
		}

		$param_kode = ['a.uuid' => $uuid];
		$journal_entry = $this->M_journal_entry->get_where_journal_entry($param_kode)->row();

		if (!$journal_entry) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data not found'
			]);
			return;
		}

		if ($journal_entry->status != 'unposted') {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Cannot delete data because status is not unposted.'
			]);
			return;
		}

		$batch = $journal_entry->batch_number;

		// START TRANSACTION
		$this->db->trans_begin();

		// 1. Delete journal items (child first)
		$this->db->where('batch_number', $batch)->delete('journal_items');

		// 2. Delete journal header
		$this->db->where('batch_number', $batch)->delete('journals');

		// Check if transaction ok
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data deletion failed'
			]);
			return;
		}

		// Commit
		$this->db->trans_commit();
		echo json_encode([
			'hasil' => 'true',
			'pesan' => 'Data successfully deleted'
		]);
	}

	public function detailform($uuid)
	{
		$cekdata =  $this->M_journal_entry->get_where_journal_entry(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$param = ['batch_number' => $cekdata->batch_number];
			$data['head'] = $this->M_journal_entry->get_where_journal_entry($param)->row();
			$data['journal_item']   = $this->M_journal_entry->get_journal_entry_item($param)->result();
			$data['judul']          = "Detail Journal Entry";
			$data['load_grid']      = 'C_journal_entry';
			$data['load_refresh']   = "C_journal_entry/detailform/" . $uuid;
			$data['uuid']           = $uuid;
			$data['data']           = $cekdata;
			$this->load->view("v_journal_entry/detail_journal_entry", $data);
		} else {
			$this->load->view('error');
		}
	}
	public function editform($uuid)
	{
		$cekdata = $this->M_journal_entry->get_where_journal_entry(['a.uuid' => $uuid])->row();
		if ($cekdata != null) {
			$param                = ['batch_number' => $cekdata->batch_number];
			$data['head']         = $this->M_journal_entry->get_where_journal_entry($param)->row();
			$data['items'] = $this->M_journal_entry->get_journal_entry_item($param)->result();
			$data['judul']        = "Edit Journal Entry";
			$data['load_grid']    = 'C_journal_entry';
			$data['load_back'] = "C_journal_entry/editform/" . $uuid;
			$data['uuid']         = $uuid;
			$data['data']         = $cekdata;
			$this->load->view("v_journal_entry/edit_journal_entry", $data);
		} else {
			$this->load->view('error');
		}
	}

	 public function updatedata(){
		$post        = json_decode($this->input->raw_input_stream, true);
		var_dump($post); die;
	 }
}
