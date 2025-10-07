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
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();
		$this->db->insert($tabel, $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
	public function update($data, $tabel, $where)
	{
		$this->db->trans_start();
		$this->db->trans_strict(FALSE);
		$this->db->where($where);
		$this->db->update("$tabel", $data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
	}
	public function getWhere($table, $param)
	{
		$this->db->where($param);
		return $this->db->get($table);
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
