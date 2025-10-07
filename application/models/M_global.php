<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_global extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
	}
	public function insert($data, $tabel)
	{
		return $this->db->insert($tabel, $data);

	}
	public function update($data, $tabel, $where)
	{
		$this->db->where($where);
		$this->db->update($tabel, $data);
		if ($this->db->affected_rows() > 0) {
			return true;  
		} else {
			return false; 
		}
	}
	public function getWhereOrder($table, $order = null, $param = null)
	{
		if ($param != null) {
			$this->db->where($param);
		}
		if ($order != null) {
			$this->db->order_by($order);
		}
		return $this->db->get($table);
	}
	
}
