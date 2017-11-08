<!--M003-->
<!-- M023 - YA - searching by date -->
<!-- M051 - YA - Ubah report activity, ticket, & interaction -->
<!-- MD01 - YA - Interaction status menggunakan model button, bukan combo box, tidak perlu tombol save &  System Autosave supaya jika pindah tab informasi sebelumnya tidak hilang -->
<!-- M59 - YA - filtering interaction berdasarkan tanggal, nama team dan dama user -->
<!-- M61 - YA - filtering dan reporting summary intercation activity report berdasarkan team, nama dan level -->
<?php
class Mdl_manage_interaction extends CI_Model{	

	function get_pil_status_active()
	{
		$sqlquery = "Select * from mst_status_active";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function get_interaction_type($flag)
	{
		$sqlquery = "Select 
						interaction_type.interaction_type_id as interaction_type_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction_type.status_active as status_active,
						msa.status_active as status_active_name
					 from helpcso_interaction_type interaction_type
					 inner join mst_status_active msa on interaction_type.status_active = msa.code_id";
		$query = $this->db->query($sqlquery);
		
		if($flag == '1'){
			return $query->result();
		}
		elseif ($flag == '2'){
			return $query->num_rows();
		}
	}
	
	function add_interaction_type($interaction_type_id,$interaction_type_name,$status_active)
	{
		$data = array(
					'interaction_type_id'=>$interaction_type_id,
					'interaction_type_name'=>$interaction_type_name,
					'status_active'=> $status_active
					);
		$this->db->insert('helpcso_interaction_type',$data);
	}
	
	function edit_interaction_type($interaction_type_id,$interaction_type_name,$status_active)
	{	
		$data = array(
						'interaction_type_name'=>$interaction_type_name,
						'status_active'=> $status_active
						);
		$this->db->where('interaction_type_id',$interaction_type_id);
		$this->db->update('helpcso_interaction_type',$data);
	}
	
	function search_interaction_type($flag_query,$flag_number,$text_search_interaction_type)
	{
		if ($flag_query == 1){
		$sqlquery = "Select 
						interaction_type.interaction_type_id as interaction_type_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction_type.status_active as status_active,
						msa.status_active as status_active_name
					 from helpcso_interaction_type interaction_type
					 inner join mst_status_active msa on interaction_type.status_active = msa.code_id
					 where interaction_type.interaction_type_name like '%" . str_replace(" ", "%", $text_search_interaction_type) . "%'";
		}
		else if ($flag_query == 2){
		$sqlquery = "Select 
						interaction_type.interaction_type_id as interaction_type_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction_type.status_active as status_active,
						msa.status_active as status_active_name
					 from helpcso_interaction_type interaction_type
					 inner join mst_status_active msa on interaction_type.status_active = msa.code_id
					 where interaction_type.interaction_type_name like '%" . str_replace(" ", "%", $text_search_interaction_type) . "%' 
					 group by interaction_type.interaction_type_name LIMIT 5";
		}
		$query = $this->db->query($sqlquery);
		
		if($flag_number == '1'){
			return $query->result();
		}
		elseif ($flag_number == '2'){
			return $query->num_rows();
		}
	}
	// MD03
	function get_interaction_report(){
		$sqlquery = "
					select 	interaction_type_id as interaction_type_id
							,interaction_type_name as interaction_type_name
							,sum(status_draft) as Draft
							,sum(status_scheduled) as Scheduled
							,sum(status_progress) as Progress
							,sum(status_canceled) as Canceled
							,sum(status_closed) as Closed
					from(
					select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							count(*) as status_draft,
							'0' as status_scheduled,
							'0' as status_progress,
							'0' as status_canceled,
							'0' as status_closed
					from helpcso_interaction_type interaction_type
					inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on status.status_id = interaction.interaction_status_id
					where status.status_id = '1'
					group by interaction_type.interaction_type_id
					union all
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							count(*) as status_scheduled,
							'0' as status_progress,
							'0' as status_canceled,
							'0' as status_closed
				 	 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
					 where status.status_id = '3'
					 group by interaction_type.interaction_type_id
					 union all
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							'0' as status_scheduled,
							count(*) as status_progress,
							'0' as status_canceled,
							'0' as status_closed
				 	 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
					 where status.status_id = '4'
					 group by interaction_type.interaction_type_id
					 union all
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							'0' as status_scheduled,
							'0' as status_progress,
							count(*) as status_canceled,
							'0' as status_closed
					 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
					 where status.status_id = '5'
					 group by interaction_type.interaction_type_id
					 union all
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							'0' as status_scheduled,
							'0' as status_progress,
							'0' as status_canceled,
							count(*)as status_closed
					 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
					 where status.status_id = '2'
					 group by interaction_type.interaction_type_id
					 ) AS temp
					 group by interaction_type_id";

		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	// MD03
	//M003
	function report_interaction_search_bydate($flag_number,$startDate,$endDate,$id){
		//B13
		$group = '';
		if ($id <> ''){
			$group = "and group_id = '".$id."'";
		}
		$query = $this->db->query("
					  select interaction_type_id as interaction_type_id
							,interaction_type_name as interaction_type_name
							,sum(status_draft) as Draft
							,sum(status_scheduled) as Scheduled
							,sum(status_progress) as Progress
							,sum(status_canceled) as Canceled
							,sum(status_closed) as Closed
					from(
					select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							count(*) as status_draft,
							'0' as status_scheduled,
							'0' as status_progress,
							'0' as status_canceled,
							'0' as status_closed
				 	 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '1'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
						   ".$group."
					 group by interaction_type.interaction_type_id
					 union
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							count(*) as status_scheduled,
							'0' as status_progress,
							'0' as status_canceled,
							'0' as status_closed
				 	 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '3'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					       ".$group."
					 group by interaction_type.interaction_type_id
					 union
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							'0' as status_scheduled,
							count(*) as status_progress,
							'0' as status_canceled,
							'0' as status_closed
				 	 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '4'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					       ".$group."
					 group by interaction_type.interaction_type_id
					 union
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							'0' as status_scheduled,
							'0' as status_progress,
							count(*) as status_canceled,
							'0' as status_closed
					 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '5'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					       ".$group."
					 group by interaction_type.interaction_type_id
					 union
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							'0' as status_scheduled,
							'0' as status_progress,
							'0' as status_canceled,
							count(*)as status_closed
					 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '2'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					       ".$group."
					 group by interaction_type.interaction_type_id
					 ) AS temp
					 group by interaction_type_id");
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}
	//B13			 	
	}
// M59
function report_interaction_search_bycreator($flag_number,$startDate,$endDate,$creator_id){
		$query = $this->db->query("
					  select interaction_type_id as interaction_type_id
							,interaction_type_name as interaction_type_name
							,sum(status_draft) as Draft
							,sum(status_scheduled) as Scheduled
							,sum(status_progress) as Progress
							,sum(status_canceled) as Canceled
							,sum(status_closed) as Closed
					from(
					select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							count(*) as status_draft,
							'0' as status_scheduled,
							'0' as status_progress,
							'0' as status_canceled,
							'0' as status_closed
				 	 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '1'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
						   and interaction.creator_id = '".$creator_id."'
					 group by interaction_type.interaction_type_id
					 union
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							count(*) as status_scheduled,
							'0' as status_progress,
							'0' as status_canceled,
							'0' as status_closed
				 	 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '3'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
						   and interaction.creator_id = '".$creator_id."'
					 group by interaction_type.interaction_type_id
					 union
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							'0' as status_scheduled,
							count(*) as status_progress,
							'0' as status_canceled,
							'0' as status_closed
				 	 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '4'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
						   and interaction.creator_id = '".$creator_id."'
					 group by interaction_type.interaction_type_id
					 union
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							'0' as status_scheduled,
							'0' as status_progress,
							count(*) as status_canceled,
							'0' as status_closed
					 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '5'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
						   and interaction.creator_id = '".$creator_id."'
					 group by interaction_type.interaction_type_id
					 union
					 select 
							interaction_type.interaction_type_id as interaction_type_id,
							interaction_type.interaction_type_name as interaction_type_name,
							'0' as status_draft,
							'0' as status_scheduled,
							'0' as status_progress,
							'0' as status_canceled,
							count(*)as status_closed
					 from helpcso_interaction_type interaction_type
					 inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					 inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					 where status.status_id = '2'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
						   and interaction.creator_id = '".$creator_id."'
					 group by interaction_type.interaction_type_id
					 ) AS temp
					 group by interaction_type_id");
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}
// M59
	// M051
	// M59 M61
	function get_interaction_report_detail($flag_number,$startDate,$endDate,$id,$status,$interaction_type_id){
		if ($id <> ''){
			$group = "and group_id = '".$id."'";
		} else {
			$group = '';
		}
		if($flag_number == 1) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction.code_interaction as code_interaction,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						user.user_name as creator_name,
						interaction.interaction_description as interaction_description,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,
						status.status_name as status_name,
						IFNULL(interaction.planned_start_datetime,'') as planned_start_datetime,
						IFNULL(interaction.actual_start_datetime,'') as actual_start_datetime,
						IFNULL(interaction.actual_end_datetime,'') as actual_end_datetime,
						IFNULL(interaction.actual_cancel_datetime,'') as actual_cancel_datetime
					from helpcso_interaction interaction
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					left join helpcso_user user on interaction.creator_id = user.user_id
					where 
						interaction_type.interaction_type_id = '".$interaction_type_id."'
						and interaction.interaction_status_id = '".$status."'
						
					";	
		}
		else if ($flag_number == 2) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction.code_interaction as code_interaction,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						user.user_name as creator_name,
						interaction.interaction_description as interaction_description,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,
						status.status_name as status_name,
						IFNULL(interaction.planned_start_datetime,'') as planned_start_datetime,
						IFNULL(interaction.actual_start_datetime,'') as actual_start_datetime,
						IFNULL(interaction.actual_end_datetime,'') as actual_end_datetime,
						IFNULL(interaction.actual_cancel_datetime,'') as actual_cancel_datetime
					from helpcso_interaction interaction
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					left join helpcso_user user on interaction.creator_id = user.user_id
					where 
						   interaction_type.interaction_type_id = '".$interaction_type_id."'	
						   and interaction.interaction_status_id = '".$status."'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	   ".$group."
					";

		}
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	// M59
	// M051
	//M003
// M023
	function get_interaction_activity_report($flag_query,$activity_code,$startDate,$endDate,$user_group,$creator_id){
		if ($flag_query == 1) {
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4

						union all
						
						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
						where activity.activity_level = 4
						group by activity.activity_id
						)as temp
						group by id,code,code,description
					";
		}
		else if ($flag_query == 2) {
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4
						
						union all
						
						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
						where 
							interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' and 
							interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
							and activity.activity_level = 4
						group by activity.activity_id
						) as temp
						group by id,code,description
					";
		}
		else if ($flag_query == 3) {
			$array = explode(';',$activity_code);
			$num_array = count($array);
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4
							  and activity_code LIKE '".$activity_code."%'
						
						union all
						
						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
						where activity.activity_level = 4
							  and activity_code LIKE '".$activity_code."%'
						group by activity.activity_id
						) as temp
						group by id,code,code,description
					";
		}
		else if ($flag_query == 4) {
			$array = explode(';',$activity_code);
			$num_array = count($array);
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4
							  and activity_code LIKE '".$activity_code."%'
						
						union all
						
						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
						where activity.activity_level = 4
							  and activity_code LIKE '".$activity_code."%'
							  and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' and 
							interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						group by activity.activity_id
						) as temp
						group by id,code,code,description
					";
		}
		else if ($flag_query == 5) {
			$array = explode(';',$user_group);
			$num_array = count($array);
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4
						
						union all

						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
            inner join helpcso_user user on user.user_id = interaction.creator_id
						where activity.activity_level = 4
							  and user.group_id = '".$user_group."'
						group by activity.activity_id
						) as temp
						group by id,code,code,description

					";
		}
		else if ($flag_query == 6) {
			$array = explode(';',$user_group);
			$num_array = count($array);
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4
						
						union all

						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
            inner join helpcso_user user on user.user_id = interaction.creator_id
						where activity.activity_level = 4
							  and user.group_id = '".$user_group."'
							  and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' and 
							interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						group by activity.activity_id
						) as temp
						group by id,code,code,description

					";
		}
		else if ($flag_query == 7) {
			$array = explode(';',$user_group);
			$num_array = count($array);
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4
							  and activity_code LIKE '".$activity_code."%'
						
						union all

						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
            inner join helpcso_user user on user.user_id = interaction.creator_id
						where activity.activity_level = 4
							  and activity_code LIKE '".$activity_code."%'
							  and user.group_id = '".$user_group."'
							  and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' and 
							interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						group by activity.activity_id
						) as temp
						group by id,code,code,description

					";
		}
		else if ($flag_query == 8) {
			$array = explode(';',$creator_id);
			$num_array = count($array);
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4
						
						union all

						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
            inner join helpcso_user user on user.user_id = interaction.creator_id
						where activity.activity_level = 4
						and interaction.creator_id = '".$creator_id."'
						group by activity.activity_id
						) as temp
						group by id,code,code,description

					";
		}
		else if ($flag_query == 9) {
			$array = explode(';',$creator_id);
			$num_array = count($array);
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4
						
						union all

						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
            inner join helpcso_user user on user.user_id = interaction.creator_id
						where activity.activity_level = 4
						and interaction.creator_id = '".$creator_id."'
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' and 
						interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						group by activity.activity_id
						) as temp
						group by id,code,code,description

					";
		}
		else if ($flag_query == 10) {
			$array = explode(';',$creator_id);
			$num_array = count($array);
			$sqlquery = "
						select
						  id as activity_id,
						  code as activity_code,
						  description as activity_description,
						  SUM(jumlah) as summary
						from
						(
						select
						  activity_id as id,
						  activity_code	as code,
						  activity_description as description,
						  '0' as jumlah
						from helpcso_activity
						where activity_level = 4
						
						union all

						select
							activity.activity_id,
							activity.activity_code,
							activity.activity_description,
							count(*)
						from helpcso_interaction_activity interaction_activity
						inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
						inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
            inner join helpcso_user user on user.user_id = interaction.creator_id
						where activity.activity_level = 4
						and activity_code LIKE '".$activity_code."%'
						and interaction.creator_id = '".$creator_id."'
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' and 
						interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						group by activity.activity_id
						) as temp
						group by id,code,code,description

					";
		}
		$query = $this->db->query($sqlquery);
	    return $query->result();
	}
	
	function get_interaction_activity_report_detail($flag_query,$startDate,$endDate,$activity_id,$user_group,$creator_id){
		if ($flag_query == 1) {
				$sqlquery = "
							select 
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,					
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id 
							where  
								   interaction_activity.activity_id = ".$activity_id."
							";	
				}
		else if ($flag_query == 2) {
				$sqlquery = "
							select 
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,					
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id 
							where  
								interaction_activity.activity_id = ".$activity_id."
								and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
							  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
							";
				}
		else if ($flag_query == 3) {
				$sqlquery = "
							select 
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,					
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id 
							where  
								interaction_activity.activity_id = ".$activity_id."
								and user.group_id = '".$user_group."'
							";
				}
		else if ($flag_query == 4) {
				$sqlquery = "
							select 
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,					
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id 
							where  
								interaction_activity.activity_id = ".$activity_id."
								and user.group_id = '".$user_group."'
								and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
							  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
							";
				}
		else if ($flag_query == 5) {
				$sqlquery = "
							select 
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,					
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id 
							where  
								interaction_activity.activity_id = ".$activity_id."
								and user.group_id = '".$user_group."'
								and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
							  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
							";
				}
		else if ($flag_query == 6) {
				$sqlquery = "
							select
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id
							where  
								interaction_activity.activity_id = '".$activity_id."'
								and interaction.creator_id = '".$creator_id."'
							";
				}
		else if ($flag_query == 7) {
				$sqlquery = "
							select
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id
							where  
								interaction_activity.activity_id = '".$activity_id."'
								and interaction.creator_id = '".$creator_id."'
								and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
							  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
							";
				}
		else if ($flag_query == 8) {
				$sqlquery = "
							select
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id
							where  
								interaction_activity.activity_id = '".$activity_id."'
								and interaction.creator_id = '".$creator_id."'
								and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
							  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
							";
				}
		$query = $this->db->query($sqlquery);
	    return $query->result();
	}

	function get_interaction_activity_report_detail2($flag_query,$startDate,$endDate,$activity_code,$user_group,$creator_id){
		if ($flag_query == 1) {
				$sqlquery = "
							select 
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,					
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id
							";	
				}
		else if ($flag_query == 2) {
				$sqlquery = "
							select 
								interaction.interaction_id as interaction_id,
								interaction_type.interaction_type_name as interaction_type_name,
								interaction.customer_name as customer_name,
								interaction.customer_phone as customer_phone,
								interaction.customer_email as customer_email,
								interaction.queue_number as queue_number,
								interaction.interaction_description as interaction_description,
								user.user_name as creator_name,
								IFNULL(interaction.creator_datetime,'') as creator_datetime,					
								status.status_name as status,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								IFNULL(interaction_activity.start_datetime,'') as start_datetime,
								IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
							from helpcso_interaction_activity interaction_activity
							inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
							inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
							inner join helpcso_status status on interaction.interaction_status_id = status.status_id
							inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
							left join helpcso_user user on interaction.creator_id = user.user_id 
							where 
								interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
							  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
							";
				}
		else if ($flag_query == 3) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						interaction.interaction_description as interaction_description,
						user.user_name as creator_name,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,					
						status.status_name as status,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						IFNULL(interaction_activity.start_datetime,'') as start_datetime,
						IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
					from helpcso_interaction_activity interaction_activity
					inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
					left join helpcso_user user on interaction.creator_id = user.user_id 
					where 
						activity.activity_code = '".$activity_code."'
					";
		}
		else if ($flag_query == 4) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						interaction.interaction_description as interaction_description,
						user.user_name as creator_name,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,					
						status.status_name as status,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						IFNULL(interaction_activity.start_datetime,'') as start_datetime,
						IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
					from helpcso_interaction_activity interaction_activity
					inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
					left join helpcso_user user on interaction.creator_id = user.user_id 
					where 
						activity.activity_code = '".$activity_code."' 
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
					";
		}
		else if ($flag_query == 5) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						interaction.interaction_description as interaction_description,
						user.user_name as creator_name,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,					
						status.status_name as status,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						IFNULL(interaction_activity.start_datetime,'') as start_datetime,
						IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
					from helpcso_interaction_activity interaction_activity
					inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
					left join helpcso_user user on interaction.creator_id = user.user_id 
					where 
						activity.activity_code = '".$activity_code."' 
						and user.group_id = '".$user_group."' 
					";
		}
		else if ($flag_query == 6) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						interaction.interaction_description as interaction_description,
						user.user_name as creator_name,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,					
						status.status_name as status,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						IFNULL(interaction_activity.start_datetime,'') as start_datetime,
						IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
					from helpcso_interaction_activity interaction_activity
					inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
					left join helpcso_user user on interaction.creator_id = user.user_id 
					where 
						interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
						and user.group_id = '".$user_group."' 
					";
		}
		else if ($flag_query == 7) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						interaction.interaction_description as interaction_description,
						user.user_name as creator_name,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,					
						status.status_name as status,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						IFNULL(interaction_activity.start_datetime,'') as start_datetime,
						IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
					from helpcso_interaction_activity interaction_activity
					inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
					left join helpcso_user user on interaction.creator_id = user.user_id 
					where 
						interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
						and activity.activity_code LIKE '".$activity_code."%' 
						and user.group_id = '".$user_group."' 
					";
		}
		else if ($flag_query == 8) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						interaction.interaction_description as interaction_description,
						user.user_name as creator_name,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,					
						status.status_name as status,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						IFNULL(interaction_activity.start_datetime,'') as start_datetime,
						IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
					from helpcso_interaction_activity interaction_activity
					inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
					left join helpcso_user user on interaction.creator_id = user.user_id 
					where 
						interaction.creator_id = '".$creator_id."'
					";
		}
		else if ($flag_query == 9) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						interaction.interaction_description as interaction_description,
						user.user_name as creator_name,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,					
						status.status_name as status,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						IFNULL(interaction_activity.start_datetime,'') as start_datetime,
						IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
					from helpcso_interaction_activity interaction_activity
					inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
					left join helpcso_user user on interaction.creator_id = user.user_id 
					where 
						interaction.creator_id = '".$creator_id."'
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
					";
		}
		else if ($flag_query == 10) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						interaction.interaction_description as interaction_description,
						user.user_name as creator_name,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,					
						status.status_name as status,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						IFNULL(interaction_activity.start_datetime,'') as start_datetime,
						IFNULL(interaction_activity.closed_datetime,'') as closed_datetime
					from helpcso_interaction_activity interaction_activity
					inner join helpcso_interaction interaction on interaction_activity.interaction_id = interaction.interaction_id
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					inner join helpcso_activity activity on interaction_activity.activity_id = activity.activity_id
					left join helpcso_user user on interaction.creator_id = user.user_id 
					where 
						interaction.creator_id = '".$creator_id."'
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
						and activity.activity_code LIKE '".$activity_code."%' 
					";
		}
		$query = $this->db->query($sqlquery);
	    return $query->result();
	}
	// M023
	// M61
// M59
	function report_interaction_search_byteam($flag_number,$text_startDate,$text_endDate,$id){
		$query = $this->db->query("
					select interaction_type_id as interaction_type_id
						,interaction_type_name as interaction_type_name
						,sum(status_draft) as Draft
						,sum(status_scheduled) as Scheduled
						,sum(status_progress) as Progress
						,sum(status_canceled) as Canceled
						,sum(status_closed) as Closed
					from(
					select 
						interaction_type.interaction_type_id as interaction_type_id,
						interaction_type.interaction_type_name as interaction_type_name,
						count(*) as status_draft,
						'0' as status_scheduled,
						'0' as status_progress,
						'0' as status_canceled,
						'0' as status_closed
				 	from helpcso_interaction_type interaction_type
					inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on status.status_id = interaction.interaction_status_id
            		inner join helpcso_user user on user.user_id = creator_id
					where status.status_id = '1'
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
						and group_id = '".$id."'
					group by interaction_type.interaction_type_id
					union
					select
						interaction_type.interaction_type_id as interaction_type_id,
						interaction_type.interaction_type_name as interaction_type_name,
						'0' as status_draft,
						count(*) as status_scheduled,
						'0' as status_progress,
						'0' as status_canceled,
						'0' as status_closed
				 	from helpcso_interaction_type interaction_type
					inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on status.status_id = interaction.interaction_status_id
		            inner join helpcso_user user on user.user_id = creator_id
					where status.status_id = '3'
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
						and group_id = '".$id."'
					group by interaction_type.interaction_type_id
					union
					select 
						interaction_type.interaction_type_id as interaction_type_id,
						interaction_type.interaction_type_name as interaction_type_name,
						'0' as status_draft,
						'0' as status_scheduled,
						count(*) as status_progress,
						'0' as status_canceled,
						'0' as status_closed
				 	from helpcso_interaction_type interaction_type
					inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on status.status_id = interaction.interaction_status_id
		            inner join helpcso_user user on user.user_id = creator_id
					where status.status_id = '4'
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
					    and group_id = '".$id."'
					group by interaction_type.interaction_type_id
					union
					select 
						interaction_type.interaction_type_id as interaction_type_id,
						interaction_type.interaction_type_name as interaction_type_name,
						'0' as status_draft,
						'0' as status_scheduled,
						'0' as status_progress,
						count(*) as status_canceled,
						'0' as status_closed
					from helpcso_interaction_type interaction_type
					inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on status.status_id = interaction.interaction_status_id
		            inner join helpcso_user user on user.user_id = creator_id
					where status.status_id = '5'
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
			 	   	    and group_id = '".$id."'
					group by interaction_type.interaction_type_id
					union
					select
						interaction_type.interaction_type_id as interaction_type_id,
						interaction_type.interaction_type_name as interaction_type_name,
						'0' as status_draft,
						'0' as status_scheduled,
						'0' as status_progress,
						'0' as status_canceled,
						count(*)as status_closed
					from helpcso_interaction_type interaction_type
					inner join helpcso_interaction interaction on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on status.status_id = interaction.interaction_status_id
		            inner join helpcso_user user on user.user_id = creator_id
					where status.status_id = '2'
						and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					  	and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59' 
					    and group_id = '".$id."'
					group by interaction_type.interaction_type_id
					) AS temp
					group by interaction_type_id");
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}
// M59
	// function get_user($group_id){
	// 	$this->db->where('group_id', $group_id);
	// 	$result = $this->db->get('helpcso_user');
	// 	if ($result->num_rows() > 0){
	// 		return $result->result_array();
	// 	} else{
	// 		return array();
	// 	}
	// }	

	function get_user_group(){
		$sqlquery = "Select * from helpcso_user_group where status_active = 1";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}

	function get_user_name(){
		$sqlquery = "Select * from helpcso_user where status_active = 1";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
// M59
	function get_interaction_report_detail_bycreator($flag_number,$startDate,$endDate,$creator_id,$status,$interaction_type_id){
		if($flag_number == 1) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction.code_interaction as code_interaction,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						user.user_name as creator_name,
						interaction.interaction_description as interaction_description,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,
						status.status_name as status_name,
						IFNULL(interaction.planned_start_datetime,'') as planned_start_datetime,
						IFNULL(interaction.actual_start_datetime,'') as actual_start_datetime,
						IFNULL(interaction.actual_end_datetime,'') as actual_end_datetime,
						IFNULL(interaction.actual_cancel_datetime,'') as actual_cancel_datetime
					from helpcso_interaction interaction
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					left join helpcso_user user on interaction.creator_id = user.user_id
					where 
						interaction_type.interaction_type_id = '".$interaction_type_id."'
						and interaction.interaction_status_id = '".$status."'
						
					";	
		}
		else if ($flag_number == 2) {
		$sqlquery = "
					select 
						interaction.interaction_id as interaction_id,
						interaction.code_interaction as code_interaction,
						interaction_type.interaction_type_name as interaction_type_name,
						interaction.customer_name as customer_name,
						interaction.customer_phone as customer_phone,
						interaction.customer_email as customer_email,
						interaction.queue_number as queue_number,
						user.user_name as creator_name,
						interaction.interaction_description as interaction_description,
						IFNULL(interaction.creator_datetime,'') as creator_datetime,
						status.status_name as status_name,
						IFNULL(interaction.planned_start_datetime,'') as planned_start_datetime,
						IFNULL(interaction.actual_start_datetime,'') as actual_start_datetime,
						IFNULL(interaction.actual_end_datetime,'') as actual_end_datetime,
						IFNULL(interaction.actual_cancel_datetime,'') as actual_cancel_datetime
					from helpcso_interaction interaction
					inner join helpcso_interaction_type interaction_type on interaction.interaction_type_id = interaction_type.interaction_type_id
					inner join helpcso_status status on interaction.interaction_status_id = status.status_id
					left join helpcso_user user on interaction.creator_id = user.user_id
					where 
						   interaction_type.interaction_type_id = '".$interaction_type_id."'	
						   and interaction.interaction_status_id = '".$status."'
						   and interaction.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and interaction.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	   and creator_id = '".$creator_id."'
					";

		}
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
			 and activity_parent = " . $parent_id;
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
// M59
}
