<?php
class Mdl_reported_script extends CI_Model{	

		function get_reported_script($flag)
		{	$sqlquery = "Select 
								distinct(script_report.script_id) AS script_id, 
								script.question AS question, 
								COUNT(script_report.script_id) AS count_reported_script,
								status_report.status_color AS status_color,
								status_report.status_report AS status_report
						 from helpcso_script_report script_report
						 inner join helpcso_script_temp script on script.script_id = script_report.script_id
						 inner join mst_status_report status_report on status_report.code_id = script_report.status_report
						 where script_report.status_report <> '3' 
						 group by script.question
						 order by script_report.script_id,script_report.status_report ASC";
			$query = $this->db->query($sqlquery);
			if($flag == '1') 
				{
				return $query->result();
				}
				elseif ($flag == '2'){
					return $query->num_rows();
				}
		}
		
		function get_data_script($script_id)
		{	$sqlquery = "Select * from helpcso_script_temp where script_id ='".$script_id."'";
			return $this->db->query($sqlquery);
		}
		
		function get_status_report()
		{	$sqlquery = "Select * from mst_status_report";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
		
		function get_data_report($script_id)
		{	$sqlquery = "Select report_id,user.user_name AS user_name, note, report_date
						 from helpcso_script_report script_report
						 inner join helpcso_user user on user.user_id = script_report.user_report_id
						 where script_id ='".$script_id."' and status_report <> '3'";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
		
		function update_status_report($script_id)
		{	
			$data_read = array(
						'status_report'=>2
						);
			$this->db->where('script_id',$script_id);
			$this->db->update('helpcso_script_report',$data_read);
		}
		
		function search_reported_script($flag_query,$flag_number,$text_search_script)
		{
			if($flag_query == 1){
			$sqlquery = "Select script_report.script_id AS script_id, script.question AS question, COUNT(script_report.script_id) AS count_reported_script
						 from helpcso_script_report script_report
						 inner join helpcso_script_temp script on script.script_id = script_report.script_id
						 where script_report.status_report <> '3' and  script.question like '%". str_replace(" ", "%", $text_search_script)."%' 
						 Order by script_report.script_id LIMIT 5";
			}
			else if($flag_query == 2){
			$sqlquery = "Select script_report.script_id AS script_id, script.question AS question, COUNT(script_report.script_id) AS count_reported_script
						 from helpcso_script_report script_report
						 inner join helpcso_script_temp script on script.script_id = script_report.script_id
						 where script_report.status_report <> '3' and  script.question like '%". str_replace(" ", "%", $text_search_script)."%'
						 group by script.question 
						 Order by script_report.script_id LIMIT 5";
			}
			$query = $this->db->query($sqlquery);
			if($flag_number == 1){
			return $query->result();
			}
			elseif ($flag_number == 2){
				return $query->num_rows();
			}
		}
		
		function save_edited_script($script_id,$question,$answer,$category_id,$tag,$user_create_id)
		{	
			$data_edit = array(
						'question'=>$question,
						'answer'=>$answer,
						'category_id'=>$category_id,
						'tag'=>$tag,
						'user_create_id'=> $user_create_id,
						'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)
						);
			$this->db->where('script_id',$script_id);
			$this->db->update('helpcso_script_temp',$data_edit);
		}
		
		function solved_reported_script($script_id)
		{	
			$sqlquery = "Select * from helpcso_script_temp where script_id = ".$script_id;
			$question = $this->db->query($sqlquery)->row('question');
			$answer = $this->db->query($sqlquery)->row('answer');
			$category_id = $this->db->query($sqlquery)->row('category_id');
			$tag = $this->db->query($sqlquery)->row('tag');
			$user_create_id = $this->db->query($sqlquery)->row('user_create_id');
			$last_edited_datetime = $this->db->query($sqlquery)->row('last_edited_datetime');
			
			$data_publish = array(
						'question'=>$question,
						'answer'=>$answer,
						'category_id'=>$category_id,
						'tag'=>$tag,
						'user_create_id'=> $user_create_id,
						'last_edited_datetime'=> $last_edited_datetime
						);
			$this->db->where('script_id',$script_id);
			$this->db->update('helpcso_script',$data_publish);
			
			$status_edit = array(
						'status_report'=>3
						);
			$this->db->where('script_id',$script_id);
			$this->db->where('status_report',2);
			$this->db->update('helpcso_script_report',$status_edit);
		}
	
}
?>