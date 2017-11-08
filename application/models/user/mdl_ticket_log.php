<?php
class Mdl_ticket_log extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_last_id(){
		$sql = "select max(log_id) as last_id  from helpcso_ticket_log";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function add($ticket_id, $description, $desc_id){
		$data = array(
			  "log_id" => $this->get_last_id() + 1
			, "ticket_id" => $ticket_id
			, "log_user" => $this->session->userdata('session_user_id')
			, "log_datetime" => gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)
			, "log_description" => $description
			, "log_desc_id" => $desc_id
		);
		$this->db->insert("helpcso_ticket_log", $data);
	}
	function get(){
		
	}
	function get_by_ticket_id(){
		
	}
}
?>