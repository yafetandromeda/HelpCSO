<?php
function get_group_name($group_id){
	$ci = &get_instance();
	$ci->load->model("user/mdl_user");
	$result = $ci->mdl_user->get_group($group_id);
	if (count($result) > 0){
		$record = $result[0];
		$return = $record->group_name;
	}
	else $return = "";
	return $return;	
}
?>