<?php
class Mdl_interaction_activity extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_last_id(){
		$query = $this->db->query("SELECT 
			max(interaction_activity_id) as last_id
			FROM helpcso_interaction_activity");
		$result = $query->result();
		$record = $result[0];
		return $record->last_id + 1;
	}
	function get_by_interaction_id($interaction_id){
		$sqlquery = "SELECT
			   interaction_activity_id
			,  intact.activity_id
			, activity_code
			, activity_description
			, activity_level
			, interaction_id
			, interaction_activity_status
			, status_name as interaction_activity_status_name
			, start_datetime
			, closed_datetime
			FROM helpcso_interaction_activity intact
			INNER JOIN helpcso_activity act ON intact.activity_id = act.activity_id
			INNER JOIN helpcso_status stat ON stat.status_id = intact.interaction_activity_status
			WHERE interaction_id = " . $interaction_id . "
			ORDER BY start_datetime asc
			";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function add($data){
		$data['interaction_activity_id'] = $this->get_last_id();
		$data['start_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
		$data['interaction_activity_status'] = 1;
		$this->db->insert("helpcso_interaction_activity", $data);
	}
	function update($data, $interaction_activity_id){
		$this->db->update("helpcso_interaction_activity", $data, array("interaction_activity_id" => $interaction_activity_id));
	}
	function delete($interaction_activity_id){
		$this->db->query("DELETE FROM helpcso_interaction_activity WHERE interaction_activity_id = " . $interaction_activity_id);
	}
	function is_solved($interaction_id){
		$query = $this->db->query("select count(interaction_activity_status) as cnt
from helpcso_interaction_activity where interaction_id = " . $interaction_id . " and interaction_activity_status = 1"); // still open
		$result = $query->result();
		$record = $result[0];
		if ($record->cnt == 0) return 1;
		return 0;
	}
}
?>