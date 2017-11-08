<?php
class Mdl_Categories extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function get_by_level($level, $parent = ''){
		if ($parent == '') $parent_query = "";
		else $parent_query = " and parent_id = " . $parent;
		$sqlquery = "select code_id, parent_id, category_code, category, level from mst_category where level = " . $level . $parent_query . " and record_flag = 1 order by category";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
}
?>