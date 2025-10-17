<?php
class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		is_session();
		$this->load->view("login");
	}
	public function logout()
	{
		session_destroy();
		$this->session->set_flashdata('success', "Logout success!");
		redirect(base_url("Auth"));
	}
	public function authProcess()
	{
		$this->load->model('M_global');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required|trim');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('error', validation_errors());
			return redirect(base_url('Auth'));
		}
		$username = trim($this->input->post('username', TRUE));
		$password = $this->input->post('password', TRUE);
		$user = $this->M_global->getWhere('users', ['username' => $username])->row_array();
		if (empty($user)) {
			$this->session->set_flashdata('error', "Login failed! Username not found.");
			return redirect(base_url('Auth'));
		}
		if (md5($password) !== $user['password_hash']) {
			$this->session->set_flashdata('error', "Login failed! Incorrect password.");
			return redirect(base_url('Auth'));
		}
		$roleData = $this->M_global->getWhere('roles', ['id' => $user['role_id']])->row_array();
		if (empty($roleData)) {
			$this->session->set_flashdata('error', "Role not found. Please contact the administrator.");
			return redirect(base_url('Auth'));
		}
		$menus = $this->db
			->select('m.id, m.parent_id, m.name, m.slug, m.	icon')
			->from('menus m')
			->join('role_menu_access rma', 'rma.menu_id = m.id')
			->where('rma.role_id', $user['role_id'])
			->where('m.is_active', 1)
			->order_by('m.sort_order', 'ASC')
			->get()
			->result_array();
		$this->session->set_userdata('sess_menus', $menus);
		$this->session->set_userdata([
			'sess_user_id'   => $user['id'],
			'sess_username'  => $user['username'],
			'sess_name'      => $user['name'],
			'sess_role_id'   => $user['role_id'],
			'sess_role_name' => $roleData['name'],
			'sess_menus'     => $menus,
			'logged'         => TRUE
		]);
		$this->session->set_flashdata('success', "Hi, " . $user['name'] . "<br>Welcome back!");
		redirect(base_url());
	}
}
