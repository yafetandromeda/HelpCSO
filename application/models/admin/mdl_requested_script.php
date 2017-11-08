<?php
class Mdl_requested_script extends CI_Model{	

		function get_requested_script($flag)
		{	$sqlquery = "Select script_request.request_id as request_id, script_request.note as note, user.user_name as user_name,script_request.request_date as request_date
						 from helpcso_script_request script_request
						 inner join helpcso_user user on script_request.user_request_id = user.user_id
						 where status_request <> '3' 
						 order by request_id ASC";
			$query = $this->db->query($sqlquery);
			if($flag == '1') 
				{
				return $query->result();
				}
				elseif ($flag == '2'){
					return $query->num_rows();
				}
		}
		
		function get_last_scriptid()
		{	$sqlquery = "Select * from mst_count_data";
			return $this->db->query($sqlquery);
		}
		
		function get_status_request()
		{	$sqlquery = "Select * from mst_status_request";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
		
		function get_data_request($request_id)
		{	$sqlquery = "Select request_id,user.user_name AS user_name, note, request_date
						 from helpcso_script_request script_request
						 inner join helpcso_user user on user.user_id = script_request.user_request_id
						 where request_id ='".$request_id."' and status_request <> '3'";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
		
		function update_status_request($request_id,$flag)
		{	
			if($flag == 1) $status = 2;
			else if ($flag == 2) $status = 3;
			$data_read = array(
						'status_request'=> $status
						);
			$this->db->where('request_id',$request_id);
			$this->db->update('helpcso_script_request',$data_read);
		}
		
		function search_requested_script($flag,$text_search_script)
		{
			$sqlquery = "Select distinct(script_request.script_id) AS script_id, script.question AS question, COUNT(script_request.script_id) AS count_requested_script
						 from helpcso_script_request script_request
						 inner join helpcso_temp_script_request script on script.script_id = script_request.script_id
						 where script_request.status_request <> '3' and  script.question like '%".$text_search_script."%' 
						 group by script.question 
						 Order by script_request.script_id LIMIT 4";
			$query = $this->db->query($sqlquery);
			if($flag == '1'){
			return $query->result();
			}
			elseif ($flag == '2'){
				return $query->num_rows();
			}
		}
		
		function search_requested_script_bydate($flag,$startDate,$endDate)
		{
			$sqlquery = "Select script_request.request_id as request_id, script_request.note as note, user.user_name as user_name,script_request.request_date as request_date
						 from helpcso_script_request script_request
						 inner join helpcso_user user on script_request.user_request_id = user.user_id
						 where status_request <> '3' 
						 	    and script_request.request_date between '".$startDate." 00:00:00' and '".$endDate." 23:59:59'
						 order by request_id ASC";
			$query = $this->db->query($sqlquery);
			if($flag == '1'){
				return $query->result();
			}
			elseif ($flag == '2'){
				return $query->num_rows();
			}
		}
		
		function save_requested_script($script_id,$question,$answer,$category_id,$tag,$user_id,$visibility,$tracking_category)
		{	
			$data_insert = array(
						'script_id'=>$script_id,
						'question'=>$question,
						'answer'=> $answer,
						'count_view'=> 0,
						'count_reported'=> 0,
						'tag'=> $tag,
						'category_id'=> $category_id,
						'tracking_category'=> $tracking_category,
						'user_create_id'=> $user_id,
						'visibility'=> $visibility,
						'create_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
						'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)
						);
			$this->db->insert('helpcso_script',$data_insert);
			
			$data_temp = array(
						'script_id'=>$script_id,
						'question'=>$question,
						'answer'=> $answer,
						'tag'=> $tag,
						'category_id'=> $category_id,
						'tracking_category'=> $tracking_category,
						'user_create_id'=> $user_id,
						'visibility'=> $visibility,
						'create_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
						'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)
						);
			$this->db->insert('helpcso_script_temp',$data_temp);
			
			$data_jumlah = array(
							'total_script'=>$script_id
							);
			$this->db->update('mst_count_data',$data_jumlah);
		}
	
		function get_pil_subcategory($flag,$parent_id){
			$query = $this->db->query("select code_id, category, level, parent_id
									   from mst_category where parent_id = '".$parent_id."' and record_flag = '1'");
			if ($flag == 1){
				return $query->result();
			}
			else if ($flag == 2){
				return $query;
			}
		}
}
?>