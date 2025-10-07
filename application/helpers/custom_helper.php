<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//rupiah 1.000.000,00 
if (!function_exists('rupiah_desimal')) {
	function rupiah_desimal($angka)
	{

		$hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
		return $hasil_rupiah;
	}
}
// rupiah Rp 1.000.000
if (!function_exists('rupiah')) {
	function rupiah($angka)
	{
		$hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
		return $hasil_rupiah;
	}
}

if (!function_exists('currency')) {
	function currency($angka, $param = null)
	{
		if ($param != null) {
			$hasil_rupiah = $param . " " . number_format($angka, 0, ',', '.');
		} else {
			$hasil_rupiah = number_format($angka, 0, ',', '.');
		}
		return $hasil_rupiah;
	}
}
if (!function_exists('txtclear')) {
	function txtclear($txt)
	{
		return  preg_replace('/\s+/', '', $txt);
	}
}
if (!function_exists('only_numbers')) {
	function only_numbers($txt)
	{
		// Hapus semua karakter kecuali angka
		return preg_replace('/[^0-9]/', '', $txt);
	}
}


if (!function_exists('clean_nik')) {
	function clean_nik($txt)
	{
		// Hilangkan spasi di awal/akhir
		$txt = trim($txt);

		// Hanya izinkan huruf, angka, dan titik
		$txt = preg_replace('/[^a-zA-Z0-9.]/', '', $txt);

		return $txt;
	}
}

if (!function_exists('tgl_dmy')) {

	function tgl_dmy($tanggal)
	{
		// Buat DateTime object dari string input
		$date = DateTime::createFromFormat('M d Y h:iA', $tanggal);

		// Jika parsing berhasil, format ulang
		if ($date !== false) {
			return $date->format('d-m-Y');
		}

		// Jika parsing gagal, kembalikan string asli atau nilai default
		return $tanggal;
	}
}
if (!function_exists('tgl_string')) {
	function tgl_string($tanggal)
	{
		if (empty($tanggal)) return '-';

		// coba parse dengan format datetime lengkap
		$date = DateTime::createFromFormat('Y-m-d H:i:s', $tanggal);

		// kalau gagal, coba parse dengan format tanggal saja
		if (!$date) {
			$date = DateTime::createFromFormat('Y-m-d', $tanggal);
		}

		// kalau masih gagal, kembalikan apa adanya
		if ($date === false) return $tanggal;

		return $date->format('d M, Y'); // contoh: 30 Jul, 2025
	}
}


if (!function_exists('clean_string')) {
	function clean_string($string)
	{
		// Trim leading and trailing spaces and tabs
		return trim($string, " \t");
	}
}

function redirect_based_on_role()
{
	$CI = &get_instance(); // Get CodeIgniter instance
	$jabatan = $CI->session->userdata('jabatan');
	if ($CI->session->userdata('logged') === TRUE) {
		if ($jabatan == 'administrator') {
			redirect('Dashboard');
		} else if ($jabatan == 'support') {
			redirect('support/supportAutoGL');
		}
	}
}

if (!function_exists('is_logged_in')) {
	function is_logged_in()
	{
		$CI = &get_instance();
		$is_logged = $CI->session->userdata('logged');
		if ($is_logged != true) {
			$CI->session->sess_destroy();
			// Cek apakah ini request AJAX
			if ($CI->input->is_ajax_request()) {
				// Kirim response JSON khusus
				echo json_encode([
					'session_expired' => true,
					'redirect' => base_url('logout'),
					'message' => 'Session Anda telah habis. Silakan login kembali.',
				]);
				http_response_code(401); // unauthorized
				exit;
			} else {
				// Request biasa
				redirect('login');
				exit;
			}
		}
		
	}
}
if (!function_exists('is_session')) {
	function is_session()
	{
		$CI = &get_instance();
		$is_logged = $CI->session->userdata('logged');
		if ($is_logged == true) {
			redirect('/');
		}
	}
}
if (!function_exists('is_pengajuan')) {
	function is_pengajuan()
	{
		// yang mengajukan nl  all spv = level jabatan 4,5,6
		$CI = &get_instance();
		$level = (int) $CI->session->userdata('sess_level_jabatan');
	
		if (!in_array($level, [4, 5, 6,2])) {
			$CI->session->sess_destroy();
			if ($CI->input->is_ajax_request()) {
				// Kirim response JSON khusus
				echo json_encode([
					'session_expired' => true,
					'redirect' => base_url('logout'),
					'message' => 'Level Jabatan Anda tidak terdaftar',
				]);
				http_response_code(401); // unauthorized
				exit;
			} else {
				// Request biasa
				redirect('login');
				exit;
			}
		}
		return true;
	}
}
if (! function_exists('bulan_romawi')) {
	function bulan_romawi($tanggal)
	{
		// Pastikan format tanggal valid
		$bulan = date('n', strtotime($tanggal));

		$romawi = [
			1 => 'I',
			2 => 'II',
			3 => 'III',
			4 => 'IV',
			5 => 'V',
			6 => 'VI',
			7 => 'VII',
			8 => 'VIII',
			9 => 'IX',
			10 => 'X',
			11 => 'XI',
			12 => 'XII'
		];

		return $romawi[$bulan];
	}
}
if (!function_exists('badge')) {
	function badge($status)
	{
		switch (strtoupper($status)) {
			case 'APPROVED':
				return 'bg-success';
			case 'REJECT':
				return 'bg-danger';
			case 'OPEN':
				return 'bg-primary';
			default:
				return 'bg-secondary'; // waiting / null
		}
	}
}

if (!function_exists('get_status_approval')) {
	function get_status_approval($row)
	{
		// Default semua level WAITING
		$status = [
			'kadept' => ['tgl' => '', 'status' => 'OPEN'],
			'staf'   => ['tgl' => '', 'status' => 'WAITING'],
			'spv'    => ['tgl' => '', 'status' => 'WAITING'],
		];

		// Kalau ada REJECT di salah satu level → semua REJECT
		if ($row->status_dept == 'REJECT' || $row->status_qms_staf == 'REJECT' || $row->status_qms_spv == 'REJECT') {
			return [
				'kadept' => ['tgl' => $row->tgl_approve_dept, 'status' => 'REJECT'],
				'staf'   => ['tgl' => $row->tgl_approved_staf, 'status' => 'REJECT'],
				'spv'    => ['tgl' => $row->tgl_status_spv,   'status' => 'REJECT'],
			];
		}

		// Kadept sudah approve
		if ($row->status_dept != '') {
			$status['kadept'] = ['tgl' => $row->tgl_approve_dept, 'status' => $row->status_dept];
			$status['staf']   = ['tgl' => '', 'status' => 'OPEN']; // giliran staf
		}

		// Staf sudah approve
		if ($row->status_qms_staf != '') {
			$status['staf'] = ['tgl' => $row->tgl_approved_staf, 'status' => $row->status_qms_staf];
			$status['spv']  = ['tgl' => '', 'status' => 'OPEN']; // giliran spv
		}

		// SPV sudah approve
		if ($row->status_qms_spv != '') {
			$status['spv'] = ['tgl' => $row->tgl_status_spv, 'status' => $row->status_qms_spv];
		}

		return $status;
	}
}
