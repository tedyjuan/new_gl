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
		// Parameter dari DataTables
		$start  = $this->input->get('start');
		$length = $this->input->get('length');
		$search = $this->input->get('search')['value'];
		$order  = $this->input->get('order')[0]['column'];
		$dir    = $this->input->get('order')[0]['dir'];

		// Mapping kolom untuk pengurutan
		$order_by = ['code_depo', 'name', 'city', 'phone_no', 'status_data', 'action'][$order];

		// Ambil data dari model
		$data = $this->M_depos->get_paginated_depos($length, $start, $search, $order_by, $dir);
		$total_records = $this->M_depos->count_all_depos();
		$total_filtered = $this->M_depos->count_filtered_depos($search);

		// URL edit/delete bisa didefinisikan di controller atau dikirim dari view
		$url_edit   = 'C_depos/editform/';
		$url_delete = 'C_depos/hapusdata/';
		$load_grid  = 'C_depos/griddata'; // misal: nama function reload datatable

		// Format data untuk DataTables
		$result = [];
		foreach ($data as $row) {

			$status_data = ($row->status_data == 'active')
				? '<span class="badge bg-success">Active</span>'
				: '<span class="badge bg-danger">Inactive</span>';

			// 🔹 Dropdown tombol aksi (HTML)
			$aksi = '
			<div class="dropdown">
				<button type="button" class="btn btn-white btn-sm" id="aksi-dropdown-' . $row->code_depo . '" data-bs-toggle="dropdown" aria-expanded="false">
					More <i class="bi-chevron-down ms-1"></i>
				</button>
				<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->code_depo . '">
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
				$row->code_depo,
				$row->name,
				$row->city,
				$row->phone_no,
				$status_data,
				$aksi // tombol aksi dropdown
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
		$data['judul']     = "Form Tambah Perusahaan";
		$data['load_back'] = 'C_depos/add';
		$data['load_grid'] = 'C_depos';
		$this->load->view("v_depos/add_depos", $data);
	}
	public function simpandata()
	{
		// Aturan validasi
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('kode_depo', 'Kode Depo', 'required');
		$this->form_validation->set_rules('nama_depo', 'Nama Depo', 'required');
		$this->form_validation->set_rules('kd_depo_cost_center', 'Kode Depo Cost Center', 'required');
		$this->form_validation->set_rules('singkatan_cost_center', 'Singkatan Cost Center', 'required');
		$this->form_validation->set_rules('npwp', 'NPWP', 'required');
		$this->form_validation->set_rules('kota', 'Kota', 'required');
		$this->form_validation->set_rules('kode_pos', 'Kode Pos', 'required');
		$this->form_validation->set_rules('nomor_hp', 'Nomor HP', 'required');
		$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		$this->form_validation->set_rules('status_depo', 'Status Depo', 'required');

		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'hasil' => 'false',
				'pesan' => validation_errors()
			]);
			return;
		}

		// Ambil input dari form
		$kode_depo           = $this->input->post('kode_depo');
		$kd_depo_cost_center = $this->input->post('kd_depo_cost_center');
		$singkatan_cost_center = $this->input->post('singkatan_cost_center');
		$perusahaan          = $this->input->post('perusahaan');

		// Cek data duplikat
		if ($this->db->where('code_depo', $kode_depo)->count_all_results('depos') > 0) {
			echo json_encode(['hasil' => 'false', 'pesan' => 'Kode depo sudah digunakan']);
			return;
		}

		if ($this->db->where('code_area', $kd_depo_cost_center)->count_all_results('depos') > 0) {
			echo json_encode(['hasil' => 'false', 'pesan' => 'Kode Depo Cost Center sudah digunakan']);
			return;
		}

		if ($this->db->where('alias', $singkatan_cost_center)->count_all_results('depos') > 0) {
			echo json_encode(['hasil' => 'false', 'pesan' => 'Singkatan cost center sudah digunakan']);
			return;
		}

		// Mulai transaksi
		$this->db->trans_begin();

		try {

			$datainsert = [
				'uuid'         => $this->uuid->v4(),
				'code_depo'    => $kode_depo,
				'name'         => $this->input->post('nama_depo'),
				'alias'        => $singkatan_cost_center,
				'code_company' => $perusahaan,
				'npwp'         => $this->input->post('npwp'),
				'address'      => $this->input->post('alamat'),
				'city'         => $this->input->post('kota'),
				'postal_code'  => $this->input->post('kode_pos'),
				'phone_no'     => $this->input->post('nomor_hp'),
				'code_area'    => $kd_depo_cost_center,
				'fiscal_year'  => date('Y'),
				'status_depo'  => $this->input->post('status_depo'),
				'created_at'   => date('Y-m-d H:i:s'),
				'updated_at'   => date('Y-m-d H:i:s')
			];

			$this->db->insert('depos', $datainsert);

			if ($this->db->affected_rows() <= 0) {
				$this->db->trans_rollback();
				echo json_encode(['hasil' => 'false', 'pesan' => 'Gagal Menyimpan Data']);
				return;
			}

			// Cek status company
			$cek_status_company = $this->db->where('code_company', $perusahaan)
				->where('status_data', 'inactive')
				->get('companies')->num_rows();

			if ($cek_status_company > 0) {
				$this->db->where('code_company', $perusahaan)
					->update('companies', ['status_data' => 'active']);

				if ($this->db->affected_rows() <= 0) {
					$this->db->trans_rollback();
					echo json_encode(['hasil' => 'false', 'pesan' => 'Data berhasil disimpan, namun gagal update tabel companies']);
					return;
				}
			}

			// Commit transaksi
			$this->db->trans_commit();

			echo json_encode(['hasil' => 'true', 'pesan' => 'Data Berhasil Disimpan']);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo json_encode(['hasil' => 'false', 'pesan' => 'Gagal Menyimpan Data: ' . $e->getMessage()]);
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
		// Mulai transaksi database
		$this->db->trans_begin();

		try {
			$uuid = $this->input->post('uuid');

			// Cek apakah data dengan UUID tersebut ada
			$cek_Data_uuid = $this->db->where('uuid', $uuid)->get('depos')->row();

			if ($cek_Data_uuid) {
				$cekkode      = $cek_Data_uuid->code_depo;
				$cek_code_area = $cek_Data_uuid->code_area;
				$cekalias     = $cek_Data_uuid->alias;

				// Ambil input
				$kode_depo             = $this->input->post('kode_depo');
				$kd_depo_cost_center   = $this->input->post('kd_depo_cost_center');
				$singkatan_cost_center = $this->input->post('singkatan_cost_center');
				$perusahaan            = $this->input->post('perusahaan');
				$npwp                  = $this->input->post('npwp');
				$alamat                = $this->input->post('alamat');
				$kota                  = $this->input->post('kota');
				$kode_pos              = $this->input->post('kode_pos');
				$nomor_hp              = $this->input->post('nomor_hp');

				// ====== CEK DUPLIKASI KODE ======
				// Cek code_depo
				if ($cekkode == $kode_depo) {
					$param_kode = "LOLOS";
				} else {
					$cekKodeDepoBaru = $this->db->where('code_depo', $kode_depo)->count_all_results('depos') > 0;
					$param_kode = $cekKodeDepoBaru ? "TIDAK_LOLOS" : "LOLOS";
				}

				// Cek code_area
				if ($cek_code_area == $kd_depo_cost_center) {
					$param_area = "LOLOS";
				} else {
					$cekAreaCodeBaru = $this->db->where('code_area', $kd_depo_cost_center)->count_all_results('depos') > 0;
					$param_area = $cekAreaCodeBaru ? "TIDAK_LOLOS" : "LOLOS";
				}

				// Cek alias
				if ($cekalias == $singkatan_cost_center) {
					$param_alias = "LOLOS";
				} else {
					$cekAlias = $this->db->where('alias', $singkatan_cost_center)->count_all_results('depos') > 0;
					$param_alias = $cekAlias ? "TIDAK_LOLOS" : "LOLOS";
				}

				// ====== PROSES UPDATE JIKA LOLOS ======
				if ($param_kode == "LOLOS" && $param_area == "LOLOS" && $param_alias == "LOLOS") {
					$dataupdate = [
						'code_depo'    => $kode_depo,
						'name'         => $this->input->post('nama_depo'),
						'alias'        => $singkatan_cost_center,
						'code_company' => $perusahaan,
						'npwp'         => $npwp,
						'address'      => $alamat,
						'city'         => $kota,
						'postal_code'  => $kode_pos,
						'phone_no'     => $nomor_hp,
						'code_area'    => $kd_depo_cost_center,
						'fiscal_year'  => date('Y'),
						'status_depo'  => 'depo',
						'updated_at'   => date('Y-m-d H:i:s')
					];

					$this->db->where('uuid', $uuid)->update('depos', $dataupdate);

					if ($this->db->affected_rows() > 0) {
						$this->db->trans_commit();
						$jsonmsg = [
							'hasil' => 'true',
							'pesan' => 'Data Berhasil Diupdate'
						];
					} else {
						$this->db->trans_rollback();
						$jsonmsg = [
							'hasil' => 'false',
							'pesan' => 'Gagal Menyimpan Data'
						];
					}
				} else {
					// ====== TANGANI KESALAHAN DUPLIKASI ======
					$this->db->trans_rollback();

					if ($param_kode == 'TIDAK_LOLOS') {
						$jsonmsg = ['hasil' => 'false', 'pesan' => 'Kode Depo sudah terdaftar'];
					} elseif ($param_area == 'TIDAK_LOLOS') {
						$jsonmsg = ['hasil' => 'false', 'pesan' => 'Area Code sudah terdaftar'];
					} elseif ($param_alias == 'TIDAK_LOLOS') {
						$jsonmsg = ['hasil' => 'false', 'pesan' => 'Singkatan cost center sudah terdaftar'];
					}
				}
			} else {
				// UUID tidak ditemukan
				$this->db->trans_rollback();
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'UUID perusahaan tidak ditemukan'
				];
			}

			echo json_encode($jsonmsg);
		} catch (Exception $e) {
			// Tangani jika terjadi exception
			$this->db->trans_rollback();
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Terjadi kesalahan: ' . $e->getMessage()
			];
			echo json_encode($jsonmsg);
		}
	}
	public function hapusdata()
	{
		// Mulai transaksi
		$this->db->trans_begin();

		try {
			$uuid = $this->input->post('uuid');

			// Ambil data company berdasarkan UUID
			$company = $this->db->where('uuid', $uuid)->get('depos')->row();
			if ($company) {
				if ($company->status_data === 'active') {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Data tidak bisa dihapus, status : active'
					];
					echo json_encode($jsonmsg);
				} else {
					// Jika status bukan active, hapus data
					$this->db->where('uuid', $uuid)->delete('depos');

					if ($this->db->affected_rows() > 0) {
						$this->db->trans_commit();
						$jsonmsg = [
							'hasil' => 'true',
							'pesan' => 'Data berhasil dihapus'
						];
						echo json_encode($jsonmsg);
					} else {
						$this->db->trans_rollback();
						$jsonmsg = [
							'hasil' => 'false',
							'pesan' => 'Data tidak ditemukan atau gagal dihapus'
						];
						echo json_encode($jsonmsg);
					}
				}
			} else {
				// Jika data tidak ditemukan
				$this->db->trans_rollback();
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Data tidak ditemukan'
				];
				echo json_encode($jsonmsg);
			}
		} catch (Exception $e) {
			// Rollback transaksi jika terjadi error
			$this->db->trans_rollback();
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => $e->getMessage()
			];
			echo json_encode($jsonmsg);
		}
	}
}
