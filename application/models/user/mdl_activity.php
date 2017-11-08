<?php 
// M041 - YA - menghapus activity info pada ticket template
class Mdl_activity extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_by_id($id){
		
	}
	function get_by_activity_code($activity_code){
		
	}
	function get_by_keyword($word){
		$sqlquery = "SELECT 
			   activity_id
			 , activity_code
			 , activity_description 
			 FROM helpcso_activity
			 WHERE status_active = 1
			 and (activity_code like '%" . $word . "%' 
			 or activity_description like '%" . $word . "%')
			 and (activity_code not like '%11%')" ;
		$query = $this->db->query($sqlquery);
		return $query->result();
	}

	function get_by_parent($parent_id = 0){
		$sqlquery = "SELECT 
			   activity_id
			 , activity_code
			 , activity_description 
			 FROM helpcso_activity
			 WHERE status_active = 1
			 and activity_parent = " . $parent_id;
		$query = $this->db->query($sqlquery);
		return $query->result();
	}

	function get_by_parent_to_ticket($parent_id = 0){
		$sqlquery = "SELECT 
			   activity_id
			 , activity_code
			 , activity_description 
			 FROM helpcso_activity
			 WHERE status_active = 1
			 and activity_code <> 11
			 and activity_parent = " . $parent_id;
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function get_parent($child_id = 0){
		$sqlquery = "select 
			   activity_id
			 , activity_code
			 , activity_description 
			from helpcso_activity
			where activity_id = (select activity_parent 
				from helpcso_activity 
				where activity_id = '" . $child_id . "')";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function get_by_activity_id($activity_id){
		$sqlquery = "select activity_id
			, activity_code
			, activity_level 
			, activity_description
			from helpcso_activity 
			where activity_id = " . $activity_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_definition($activity_id){
		$sqlquery = "select activity_definition
			from helpcso_activity 
			where activity_id = " . $activity_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
}
?>