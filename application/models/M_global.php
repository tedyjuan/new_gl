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
	 public function count_company_integrate($kode_company){
		$this->db->select('
		COUNT(DISTINCT b.code_company) AS depos_count,
		COUNT(DISTINCT c.code_company) AS dept_count,
		COUNT(DISTINCT d.code_company) AS divisi_count,
		COUNT(DISTINCT e.code_company) AS segment_count,
		(COUNT(DISTINCT b.code_company) + COUNT(DISTINCT c.code_company) + COUNT(DISTINCT d.code_company) + COUNT(DISTINCT e.code_company)) AS total_count
	');
		$this->db->from('companies a');
		$this->db->join('depos b', 'b.code_company = a.code_company', 'left');
		$this->db->join('departments c', 'c.code_company = a.code_company', 'left');
		$this->db->join('divisions d', 'd.code_company = a.code_company', 'left');
		$this->db->join('segments e', 'e.code_company = a.code_company', 'left');
		$this->db->where('a.code_company', $kode_company); 
		return $this->db->get()->row();

	}
}
