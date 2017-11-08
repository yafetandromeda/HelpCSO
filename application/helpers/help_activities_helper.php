<?php
function get_activity_parent($child_id){
	$ci = &get_instance();
	$ci->load->model("user/mdl_activity");
	$result = $ci->mdl_activity->get_parent($child_id);
	$record = $result[0];
	$return['id'] = $record->activity_id;
	$return['code'] = $record->activity_code;
	$return['description'] = $record->activity_description;
	return $return;
}

function get_activity_data($activity_id){
	$ci = &get_instance();
	$ci->load->model("user/mdl_activity");
	$result = $ci->mdl_activity->get_by_activity_id($activity_id);
	$record = $result[0];
	$return['id'] = $record->activity_id;
	$return['code'] = $record->activity_code;
	$return['description'] = $record->activity_description;
	$return['level'] = $record->activity_level;
	return $return;
}
?>
