<?php
class Mdl_home_admin extends CI_Model{	
		function get_count_unread_report()
		{	$sqlquery = "Select COUNT(distinct(script_id)) AS count_reported_script
						 from helpcso_script_report 
						 where status_report = '1'";
			return $this->db->query($sqlquery);
		}
		
		function get_count_unread_request()
		{	$sqlquery = "Select * from helpcso_script_request where status_request = '1'";
			$query = $this->db->query($sqlquery);
			return $query->num_rows();
		}
}
?>