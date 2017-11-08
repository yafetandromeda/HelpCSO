<!-- M022 -->
<?php
class Mdl_wording extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_last_id(){
		$sqlquery = "select max(wording_id) as last_id from mst_wording";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function add_wording($type, $content, $user_id){
		$current_id = $this->get_last_id() + 1;
		$data = array(
					'wording_id' => $current_id,
					'wording_type' => $type,
					'wording_content' => $content,
					'user_id' => $user_id,
					'creator_datetime' => gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)
					);
		$this->db->insert('mst_wording',$data);
	}

// M022
	function get_wording($type, $min_date = ''){
		$wherestr = "";
		if ($min_date != ''){
			$wherestr = " and creator_datetime > '" . $min_date . "'";
		}
		$sqlquery = "select wording_id, wording_type, wording_content, mst_wording.user_id
					, DATE_FORMAT(creator_datetime, '%e %M %Y %H:%i') as wording_datetime
					, user_name
					 from mst_wording
					 inner join helpcso_user on helpcso_user.user_id = mst_wording.user_id
					 where wording_type like '" . $type . "'
					 " . $wherestr . " 
					 order by creator_datetime desc limit 15";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
}
?>