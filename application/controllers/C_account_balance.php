<?php
defined('BASEPATH') or exit('No direct script access allowed');
class C_account_balance extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model('M_account_balance');
		$this->load->model('M_global');
	}
	function index()
	{
		$data['judul']      = 'Account Balance Setup';
		$data['load_grid']  = 'C_account_balance';
		$data['load_add']   = 'C_account_balance/add';
		$data['url_delete'] = 'C_account_balance/delete';
		$this->load->view("v_account_balance/grid_account_balance", $data);
	}
	
	function add()
	{
		$data['judul']     = "Form Tambah Divisi";
		$data['load_back'] = 'C_account_balance/add';
		$data['load_grid'] = 'C_account_balance';
		$this->load->view("v_account_balance/add_account_balance", $data);
	}
	public function simpandata()
	{
		// Validasi input
		$this->form_validation->set_rules('perusahaan', 'Perusahaan', 'required');
		$this->form_validation->set_rules('kode_account_balance', 'Code Divisi', 'required');
		$this->form_validation->set_rules('nama_account_balance', 'Nama Divisi', 'required');
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
		$code_account_balance = $this->input->post('kode_account_balance');
		$nama_account_balance = $this->input->post('nama_account_balance');
		$alias       = $this->input->post('alias');
		// Cek apakah kode Divisi sudah ada
		$param_kode =[
			'code_account_balance'  => $code_account_balance
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
		$param_alias = ['alias'  => $alias];
		$exisalias = $this->M_global->getWhere('divisions', $param_alias)->num_rows();
		if ($exisalias != null) {
			$jsonmsg = [
				'hasil' => 'false',
				'pesan' => 'Alias sudah digunakan',
			];
			echo json_encode($jsonmsg);
			exit;
		}
		$param_nama = [
			'name'         => $nama_account_balance,
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
			'code_account_balance'  => $code_account_balance,
			'code_company' => $perusahaan,
			'name'         => $nama_account_balance,
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
		$data =  $this->M_account_balance->get_where_account_balance(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$judul = "Form Edit Divisi";
			$load_grid = "C_account_balance";
			$load_refresh = "C_account_balance/editform/" . $uuid;
			$this->load->view('v_account_balance/edit_account_balance', [
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
		$code_account_balance = $this->input->post('kode_account_balance');
		$nama_account_balance = $this->input->post('nama_account_balance');
		$alias_post       = $this->input->post('alias');
		// Cek apakah UUID Divisi ada di database
		$data =  $this->M_account_balance->get_where_account_balance(['a.uuid' => $uuid])->row();
		if ($data != null) {
			$code_company = $data->code_company;
			$cek_cost_center =  $this->M_global->getWhere('cost_centers', ['code_account_balance' => $data->code_account_balance])->num_rows();
			if ($cek_cost_center != 0) {
				$jsonmsg = [
					'hasil' => 'false',
					'pesan' => 'Tidak bisa mengubah Data Divisi karena sedang digunakan di cost centers.',
				];
				echo json_encode($jsonmsg);
				exit;
			}
			if($data->code_account_balance != $code_account_balance){
				$cekkode =  $this->M_account_balance->get_where_account_balance(['a.code_account_balance' => $code_account_balance, "a.code_company" => $code_company ])->num_rows();
				if ($cekkode != 0) {
					$jsonmsg = [
						'hasil' => 'false',
						'pesan' => 'Kode Depo sudah terdaftar',
					];
					echo json_encode($jsonmsg);
					exit;
				}
			}
			if($data->name != $nama_account_balance){
				$param_nama = ['a.name' => $nama_account_balance, "a.code_company" => $code_company];
				$ceknama =  $this->M_account_balance->get_where_account_balance($param_nama)->num_rows();
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
				$cekalias =  $this->M_account_balance->get_where_account_balance(['a.alias' => $alias_post])->num_rows();
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
				'code_account_balance'  => $code_account_balance,
				'name'         => $nama_account_balance,
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
		$divisi = $this->M_account_balance->get_where_account_balance($param_kode)->row();
		// Jika data tidak ditemukan
		if (!$divisi) {
			$this->db->trans_rollback();
			echo json_encode([
				'hasil' => 'false',
				'pesan' => 'Data tidak ditemukan'
			]);
			return;
		}
		$cek_cc = $this->M_global->getWhere('cost_centers', ['code_account_balance' => $divisi->code_account_balance])->num_rows();
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
}
