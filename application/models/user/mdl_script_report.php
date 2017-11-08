<?php
class Mdl_script_report extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_all_report()
	{
		$sqlquery = "Select * from helpcso_script_report";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function get_last_id(){
		$sqlquery = "select max(report_id) as last_id from helpcso_script_report";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function add($user_report_id, $note, $script_id)
	{ 
		$current_id = $this->get_last_id() + 1;
		$data = array(
					'report_id' => $current_id,
					'report_date' => gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'user_report_id' => $user_report_id,
					'script_id' => $script_id,
					'note' => $note
					);
		$this->db->insert('helpcso_script_report',$data);
		echo $data;
	}
}
?>