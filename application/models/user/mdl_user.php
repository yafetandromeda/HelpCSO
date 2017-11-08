<?php
// MD4 - YA - auto distribution
class Mdl_user extends CI_Model{	
	function __construct(){
		parent::__construct();
	}
	function get_group($group_id = ""){
		$ext = "";
		if ($group_id != ""){
			$ext = " and id = " . $group_id;
		}
		$sqlquery = "select id, group_name
				from helpcso_user_group
				where status_active = 1 " . $ext;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
// MD4
	function update_status_login($user_id, $status_login){
		$sqlquery = "update helpcso_user set status_login = ". $status_login . " where user_id=" . $user_id;
		$query = $this->db->query($sqlquery);
	}

	function update_status_distribusi($user_group_id){
		$sqlquery = "update helpcso_user set status_distribusi = true where group_id = '".$user_group_id."' and status_distribusi = false and status_active = 1 and status_login = true limit 1";
		$query = $this->db->query($sqlquery);
	}

	function update_all_status_distribusi($user_group_id){
		$sqlquery = "update helpcso_user set status_distribusi = false where group_id = '".$user_group_id."' and status_active = 1";
		$query = $this->db->query($sqlquery);
	}

	function get_status_distribusi($user_group_id){
		$sqlquery = "select user_id from helpcso_user where group_id = '".$user_group_id."' and status_distribusi = false and status_active = 1 and status_login = true";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}

	function available_user($user_group_id){
		$sqlquery = "select user_id from helpcso_user where group_id = '".$user_group_id."' and status_distribusi = false and status_active = 1 and status_login = true and user_id <> 63 limit 1";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->user_id;
	}
// MD4
}
?>