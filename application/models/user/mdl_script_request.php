<?php
class Mdl_script_request extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_all_request()
	{
		$sqlquery = "Select * from helpcso_script_request";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function get_last_id(){
		$sqlquery = "select max(request_id) as last_id from helpcso_script_request";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function add($user_request_id, $note)
	{ 
		$current_id = $this->get_last_id() + 1;
		$data = array(
					'request_id' => $current_id,
					'request_date' => gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'user_request_id' => $user_request_id,
					'note' => $note,
					'status_request' => 1
					);
		$this->db->insert('helpcso_script_request',$data);
		echo $data;
	}
}
?>