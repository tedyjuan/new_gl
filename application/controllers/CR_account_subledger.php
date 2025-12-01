<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CR_account_subledger extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
	}
	function index()
	{
		$data['judul']      = 'Account Subledger';
		$data['load_grid']  = 'CR_account_subledger';
		$this->load->view("VR_report/VR_account_subledger", $data);
	}
}
