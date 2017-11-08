<?php
class Mdl_helpcso_login extends CI_Model{
	function __construct(){
			parent::__construct();
	}
	
	function cek_login($username,$password)
	{
		$sqlquery = "SELECT * from helpcso_user where user_name='".$username."' AND password='".md5($password)."'";
		$query = $this->db->query($sqlquery);
		return $query->result();	
	}
}
?>