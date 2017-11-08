<!--M003-->
<!-- M012 - YA - Import Ticket Template -->
<!-- M032 - YA - Master activity plan -->
<!-- M040 - YA - menampilkan recovering & success/failed datetime serta recover name -->
<!-- M043 - YA - tambah recovery & resolution notes -->
<!-- B02  - YA - Perbaikan search -->
<!-- M050 - YA - perbaikan update status pada  ticket dan interaction, ubah berdasarkan ticket -->
<!-- B04  - YA - Perbaikan Insert & Update activityplan-->
<!-- M60 - YA filtering  dan reporting ticket berdasarkan activity code, tanggal, group, user, dan level -->
<!-- M62 - YA - Tombol sakti untuk langsung export seluruh status data ticket dengan tanpa harus terlebih dahulu masuk dulu ke masing2 status ticket untuk melakukan export -->
<!-- M67 - YA - Ubah filter ticket -->
<?php
class Mdl_manage_ticket_template extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	function get_pil_status_active()
	{
		$sqlquery = "Select * from mst_status_active";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function get_plan_id(){
		$sqlquery = "Select * from helpcso_ticket_activityplan";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}

	function ticket_template_get_all(){
		$query = $this->db->query("select 
										template.ticket_template_id as ticket_template_id,
										template.ticket_template_name as ticket_template_name,
										template.status_active as status_active,
										msa.status_active as status_active_name
									from helpcso_ticket_template template
									inner join mst_status_active msa on template.status_active = msa.code_id ");
		
		return $query->result();
	}

	function ticket_template_activity_get_all($activity_code){
		$query = $this->db->query("select 
										template_activity.ticket_plan_id as ticket_plan_id,
										template_activity.ticket_template_id as ticket_template_id,
										template.ticket_template_name as ticket_template_name,
										msa.status_active as status_active_name
									from helpcso_ticket_template_activity template_activity 
									inner join helpcso_ticket_template template on template_activity.ticket_template_id=template.ticket_template_id
									inner join helpcso_activity activity on template_activity.activity_code=activity.activity_code
									inner join mst_status_active msa on template_activity.status_active = msa.code_id 
									where template_activity.activity_code='".$activity_code."'");
		
		return $query->result();
	}

	// function import_ticket_template($dataarray)
	//     {
	//         for($i=1;$i<count($dataarray);$i++){
	//             $data1 = array(
	//                 'ticket_template_id'=>$dataarray[$i]['ticket_template_id'],
	//                 'ticket_template_name'=>$dataarray[$i]['ticket_template_name'],
	//                 'status_active'=>$dataarray[$i]['status_active']
	//             );
	//             $this->db->insert('helpcso_ticket_template', $data1);
	//             $data_jumlah = array(
	// 						''=>$script_id
	// 						);
	// 			$this->db->update('mst_count_data',$data_jumlah);
	//         }
	//     }

	function get_last_ticket_template_id(){
		$sqlquery = "select max(ticket_template_id) as last_id from helpcso_ticket_template";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}

	function get_last_ticket_template_activity_id(){
		$sqlquery = "select max(ticket_plan_id) as last_id from helpcso_ticket_template_activity";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
			
	function add_ticket_template($ticket_template_data){
		$ticket_template_data['ticket_template_id'] = $this->get_last_ticket_template_id() + 1;
		$this->db->insert('helpcso_ticket_template', $ticket_template_data);
	}

	function add_ticket_template_activity($ticket_template_data){
		$ticket_template_data['ticket_plan_id'] = $this->get_last_ticket_template_activity_id() + 1;
		$this->db->insert('helpcso_ticket_template_activity', $ticket_template_data);
	}

// M012
	function import_excel($dataexcel){
		for($i=0;$i<count($dataexcel);$i++){
            $data = array(
                'ticket_template_id'=>$dataexcel[$i]['ticket_template_id'],
                'ticket_template_name'=>$dataexcel[$i]['ticket_template_name'],
                'status_active'=>$dataexcel[$i]['status_active']
            );
            $this->db->insert('helpcso_ticket_template', $data);
        }
	}

	function import_excel_edit($dataexcel){
		for($i=0;$i<count($dataexcel);$i++){
            $data = array(
                'ticket_template_id'=>$dataexcel[$i]['ticket_template_id'],
                'ticket_template_name'=>$dataexcel[$i]['ticket_template_name'],
                'status_active'=>$dataexcel[$i]['status_active']
            );
            $param = array(
               'ticket_template_id'=>$dataexcel[$i]['ticket_template_id']
            );
		$this->db->where($param);
		return $this->db->update('helpcso_ticket_template', $data);
        }
	}

	function cek_ticket_template($dataexcel){
        for($i=0;$i<count($dataexcel);$i++){
            $search = array(
                'ticket_template_id'=>$dataexcel[$i]['ticket_template_id']
            );
		}
		$data = array();
		$this->db->where($search);
		$this->db->limit(1);
		$Q = $this->db->get('helpcso_ticket_template');
		if($Q->num_rows() > 0){
		$data = $Q->row_array();
		}
		$Q->free_result();
		return $data;
	}
// M012

	function edit_ticket_template($ticket_template_id, $ticket_template_data){
		$this->db->where('ticket_template_id', $ticket_template_id);
		$this->db->update('helpcso_ticket_template', $ticket_template_data);
	}
	
	function edit_ticket_template_activity($ticket_plan_id, $ticket_template_data){
		$this->db->where('ticket_plan_id', $ticket_plan_id);
		$this->db->update('helpcso_ticket_template_activity', $ticket_template_data);
	}

	function search_ticket_template($flag_query,$flag,$keyword){
		if($flag_query == 1) {
			$query = $this->db->query("select 
											template.ticket_template_id as ticket_template_id,
											template.ticket_template_name as ticket_template_name,
											template.status_active as status_active,
											msa.status_active as status_active_name
										from helpcso_ticket_template template
										inner join mst_status_active msa on template.status_active = msa.code_id
										where template.ticket_template_name like '%" . str_replace(" ", "%", $keyword) . "%'");
		}
		else if($flag_query == 2) {
			$query = $this->db->query("
										select 
											template.ticket_template_id as ticket_template_id,
											template.ticket_template_name as ticket_template_name,
											template.status_active as status_active,
											msa.status_active as status_active_name
										from helpcso_ticket_template template
										inner join mst_status_active msa on template.status_active = msa.code_id
										where template.ticket_template_name like '%" . str_replace(" ", "%", $keyword) . "%'
										LIMIT 5");
		}
		if ($flag == 1){
			return $query->result();
		}
		else {
			return $query->num_rows();
		}
	}
	
	//fields

	
	function activity_plan_get_all($ticket_template_id){
		$query = $this->db->query("select 
										template_activity_plan.ticket_template_id as ticket_template_id,
										template_activity_plan.ticket_activityplan_id as ticket_activityplan_id,
										template_activity_plan.plan_id as plan_id,
										template_activity_plan.plan_order as plan_order,
										activity_plan.action_name as action_name,
										activity_plan.function_name as function_name,
										activity_plan.sla as sla,
										template_activity_plan.status_active as status_active,
										msa.status_active as status_active_name
								   from helpcso_ticket_template_activityplan template_activity_plan
								   inner join helpcso_ticket_activityplan activity_plan on template_activity_plan.plan_id = activity_plan.plan_id
								   inner join mst_status_active msa on template_activity_plan.status_active = msa.code_id
								   where template_activity_plan.ticket_template_id = '".$ticket_template_id."'");
		return $query->result();
	}

// M032
	function activity_plan_get_all2(){
		$query = $this->db->query("select 
										  activity_plan.plan_id as plan_id,
										  activity_plan.plan_order as plan_order,
										  activity_plan.action_name as action_name,
										  activity_plan.function_name as function_name,
										  activity_plan.sla as sla,
										  activity_plan.status_active as status_active,
										  msa.status_active as status_active_name
								   from helpcso_ticket_activityplan activity_plan
								   inner join mst_status_active msa on activity_plan.status_active = msa.code_id
								   where activity_plan.status_active=1");
								   // inner join helpcso_ticket_template_activityplan template_activity_plan on activity_plan.plan_id = template_activity_plan.plan_id");
		return $query->result();
	}

	function get_all_activity_plan(){
		$query = $this->db->query("select 
										  activity_plan.plan_id as plan_id,
										  activity_plan.plan_order as plan_order,
										  activity_plan.action_name as action_name,
										  activity_plan.function_name as function_name,
										  activity_plan.sla as sla,
										  activity_plan.status_active as status_active,
										  msa.status_active as status_active_name
								   from helpcso_ticket_activityplan activity_plan
								   inner join mst_status_active msa on activity_plan.status_active = msa.code_id");
								   // inner join helpcso_ticket_template_activityplan template_activity_plan on activity_plan.plan_id = template_activity_plan.plan_id");
		return $query->result();
	}

	function get_activity_plan_by_id($plan_id){
		$query = $this->db->query("SELECT plan_id, function_name FROM helpcso_ticket_activityplan WHERE plan_id=".$plan_id);
		return $query->result();
	}
// M032
// M009
	function activity_plan_get_all_toexcel(){
		$query = $this->db->query("select 
										  activity_plan.plan_id as plan_id,
										  activity_plan.plan_order as plan_order,
										  activity_plan.action_name as action_name,
										  activity_plan.function_name as function_name,
										  activity_plan.sla as sla,
										  activity_plan.status_active as status_active,
										  msa.status_active as status_active_name,
										  activity_plan.ticket_template_id as ticket_template_id
								   from helpcso_ticket_activityplan activity_plan
								   inner join mst_status_active msa on activity_plan.status_active = msa.code_id");
		return $query->result();
	}
// M009
	function get_activity_plan_last_id(){
		$sqlquery = "select max(plan_id) as last_id from helpcso_ticket_activityplan";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	
	function get_ticket_template_activityplan_last_id(){
		$sqlquery = "select max(ticket_activityplan_id) as last_id from helpcso_ticket_template_activityplan";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}

// M013
	function import_activityplan($dataexcel){
		for($i=0;$i<count($dataexcel);$i++){
            $data = array(
                'plan_id'=>$dataexcel[$i]['plan_id'],
                'plan_order'=>$dataexcel[$i]['plan_order'],
                'action_name'=>$dataexcel[$i]['action_name'],
                'function_name'=>$dataexcel[$i]['function_name'],
                'sla'=>$dataexcel[$i]['sla'],
                'status_active'=>$dataexcel[$i]['status_active'],
                'ticket_template_id'=>$dataexcel[$i]['ticket_template_id'],
            );
            $this->db->insert('helpcso_ticket_activityplan', $data);
        }
	}

	function import_activityplan_edit($dataexcel){
		for($i=0;$i<count($dataexcel);$i++){
            $data = array(
                'plan_id'=>$dataexcel[$i]['plan_id'],
                'plan_order'=>$dataexcel[$i]['plan_order'],
                'action_name'=>$dataexcel[$i]['action_name'],
                'function_name'=>$dataexcel[$i]['function_name'],
                'sla'=>$dataexcel[$i]['sla'],
                'status_active'=>$dataexcel[$i]['status_active'],
                'ticket_template_id'=>$dataexcel[$i]['ticket_template_id'],
            );
            $param = array(
               'plan_id'=>$dataexcel[$i]['plan_id']
            );
		$this->db->where($param);
		return $this->db->update('helpcso_ticket_activityplan', $data);
        }
	}

	function cek_activityplan($dataexcel){
        for($i=0;$i<count($dataexcel);$i++){
            $search = array(
                'plan_id'=>$dataexcel[$i]['plan_id']
            );
		}
		$data = array();
		$this->db->where($search);
		$this->db->limit(1);
		$Q = $this->db->get('helpcso_ticket_activityplan');
		if($Q->num_rows() > 0){
		$data = $Q->row_array();
		}
		$Q->free_result();
		return $data;
	}
// M013
	
	function add_activity_plan($activity_plan_data,$ticket_template_id){
		$activity_plan_data['plan_id'] = $this->get_activity_plan_last_id() + 1;
		$activity_plan_data['ticket_template_id'] = $ticket_template_id;
		$this->db->insert('helpcso_ticket_activityplan', $activity_plan_data);
	}
// B04
	function add_ticket_activity_plan($activity_plan_data,$ticket_template_id){
		$activity_plan_data['ticket_activityplan_id'] = $this->get_ticket_template_activityplan_last_id() + 1;
		// $activity_plan_data['ticket_template_id'] = $ticket_template_id;
		$this->db->insert('helpcso_ticket_template_activityplan', $activity_plan_data);
	}
// B04
	function edit_activity_plan($plan_id, $activity_plan_data){
		$this->db->where('plan_id', $plan_id);
		$this->db->update('helpcso_ticket_activityplan', $activity_plan_data);
	}

	function edit_ticket_activity_plan($ticket_activityplan_id, $activity_plan_data){
		$this->db->where('ticket_activityplan_id', $ticket_activityplan_id);
		$this->db->update('helpcso_ticket_template_activityplan', $activity_plan_data);
	}
// B02
	function search_activity_plan($flag_query,$flag,$keyword){
		if ($flag_query == 1){
			$query = $this->db->query("select 
											  activity_plan.plan_id as plan_id,
											  activity_plan.plan_order as plan_order,
											  activity_plan.action_name as action_name,
											  activity_plan.function_name as function_name,
											  activity_plan.sla as sla,
											  activity_plan.status_active as status_active,
											  msa.status_active as status_active_name,
											  template_activityplan.ticket_activityplan_id,
											  template_activityplan.ticket_template_id
										   from helpcso_ticket_activityplan activity_plan
										   inner join mst_status_active msa on activity_plan.status_active = msa.code_id
										   inner join helpcso_ticket_template_activityplan template_activityplan on template_activityplan.plan_id = activity_plan.plan_id
										   where  activity_plan.action_name like '%" . str_replace(" ", "%", $keyword) . "%' group by plan_id");
		} 
		else if ($flag_query == 2){
			$query = $this->db->query("select 
											  activity_plan.plan_id as plan_id,
											  activity_plan.plan_order as plan_order,
											  activity_plan.action_name as action_name,
											  activity_plan.function_name as function_name,
											  activity_plan.sla as sla,
											  activity_plan.status_active as status_active,
											  msa.status_active as status_active_name,
											  template_activityplan.ticket_activityplan_id,
											  template_activityplan.ticket_template_id
										   from helpcso_ticket_activityplan activity_plan
										   inner join mst_status_active msa on activity_plan.status_active = msa.code_id
										   inner join helpcso_ticket_template_activityplan template_activityplan on template_activityplan.plan_id = activity_plan.plan_id
										   where activity_plan.action_name like '%" . str_replace(" ", "%", $keyword) . "%' group by plan_id LIMIT 5 ");
		}
		if ($flag == 1){
			return $query->result();
		}
		else {
			return $query->num_rows();
		}
	}
// B02 
// M050
// M60
	function get_ticket_report($flag_query,$activity_code){
		if ($flag_query == 1) {
			$sqlquery = "Select
						status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'

						union all
						
						select
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name";
			// $sqlquery = "
			// 		Select
			// 			status_id as Status_id,
			// 			status_name as Status,
			// 			sum(substatus_1) as Assigned,
			// 			sum(substatus_2) as Un_Assigned
			// 		from (
			// 			select 
			// 				ticket.ticket_status as status_id,
			// 				status.status_name as status_name,
			// 				(select 
			// 					count(*) 
			// 				 from helpcso_activity activity
			// 				 inner join helpcso_ticket ticket2 on activity.activity_id = ticket2.activity_id
			// 				 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
			// 				 where
			// 				 	 	ticket2.ticket_id = ticket.ticket_id
			// 				 		and substatus_id = 2
			// 				 ) as substatus_1,
			// 				'0' as substatus_2
			// 			from helpcso_ticket ticket
			// 			right join helpcso_status status on ticket.ticket_status = status.status_id
			// 			where status.status_flag = 't'
							  
			// 			union all
						
			// 			select
			// 				ticket.ticket_status as status_id,
			// 				status.status_name as status_name,
			// 				'0' as substatus_1,
			// 				(select 
			// 					count(*) 
			// 				 from helpcso_activity activity
			// 				 inner join helpcso_ticket ticket2 on activity.activity_id = ticket2.activity_id
			// 				 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
			// 				 where
			// 				 	 	ticket2.ticket_id = ticket.ticket_id
			// 				 		and substatus_id = 1
			// 				 ) as substatus_2
			// 			from helpcso_ticket ticket
			// 			right join helpcso_status status on ticket.ticket_status = status.status_id
			// 			where status.status_flag = 't'
			// 		) as temp
			// 		group by status_id,status_name
			// 		";
		}else{
			$array = explode(';',$activity_code);
			$num_array = count($array);
			$sqlquery = "
					Select
						status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
            inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
						where status.status_flag = 't'
            and activity.activity_code LIKE '".$activity_code."%'
						union all
						
						select
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
            inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
						where status.status_flag = 't'
            and activity.activity_code LIKE '".$activity_code."%'
					) as temp
					group by status_id,status_name
					";
		}
		// 	$sqlquery = "
		// 			Select
		// 				status_id as Status_id,
		// 				status_name as Status,
		// 				sum(substatus_1) as Assigned,
		// 				sum(substatus_2) as Un_Assigned
		// 			from (
		// 				select 
		// 					ticket.ticket_status as status_id,
		// 					status.status_name as status_name,
		// 					(select 
		// 						count(*) 
		// 					 from helpcso_activity activity
		// 					 inner join helpcso_ticket ticket2 on activity.activity_id = ticket2.activity_id
		// 					 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
		// 					 where
		// 					 	 	ticket2.ticket_id = ticket.ticket_id
		// 					 		and substatus_id = 2
		// 							and activity.activity_code in (";
		// 	for ($i=0;$i<$num_array;$i++){ 
		// 		$sqlquery .= "'".$array[$i]."'";	
		// 		if ($i+1 < $num_array) $sqlquery .= ",";	
		// 	};									
		// 	$sqlquery .= ")
		// 					 ) as substatus_1,
		// 					'0' as substatus_2
		// 				from helpcso_ticket ticket
		// 				right join helpcso_status status on ticket.ticket_status = status.status_id
		// 				where status.status_flag = 't'
							  
		// 				union all
						
		// 				select
		// 					ticket.ticket_status as status_id,
		// 					status.status_name as status_name,
		// 					'0' as substatus_1,
		// 					(select 
		// 						count(*) 
		// 					 from helpcso_activity activity
		// 					 inner join helpcso_ticket ticket2 on activity.activity_id = ticket2.activity_id
		// 					 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
		// 					 where
		// 					 	 	ticket2.ticket_id = ticket.ticket_id
		// 					 		and substatus_id = 1
		// 							and activity.activity_code in (";
		// 	for ($i=0;$i<$num_array;$i++){ 
		// 		$sqlquery .= "'".$array[$i]."'";	
		// 		if ($i+1 < $num_array) $sqlquery .= ",";	
		// 	};									
		// 	$sqlquery .= ")
		// 					 ) as substatus_2
		// 				from helpcso_ticket ticket
		// 				right join helpcso_status status on ticket.ticket_status = status.status_id
		// 				where status.status_flag = 't'
		// 			) as temp
		// 			group by status_id,status_name
		// 			";
		// }
		$query = $this->db->query($sqlquery);
	    return $query->result();
	
	}
// M050
	//M003
	function report_ticket_search_bydate($flag_number,$startDate,$endDate){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		// $sqlquery = "
		// 			  Select
		// 			  	status_id as Status_id,
		// 				status_name as Status,
		// 				sum(substatus_1) as Assigned,
		// 				sum(substatus_2) as Un_Assigned
		// 			  from (
		// 				select 
		// 					ticket.ticket_status as status_id,
		// 					status.status_name as status_name,
		// 					(select 
		// 						count(*) 
		// 					 from helpcso_activity activity
		// 					 inner join helpcso_ticket ticket2 on activity.activity_id = ticket2.activity_id
		// 					 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
		// 					 where
		// 					 	 	ticket2.ticket_id = ticket.ticket_id
		// 					 		and substatus_id = 2
		// 							and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
		// 			 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
		// 					 ) as substatus_1,
		// 					'0' as substatus_2
		// 				from helpcso_ticket ticket
		// 				right join helpcso_status status on ticket.ticket_status = status.status_id
		// 				where status.status_flag = 't'
							  
		// 				union all
						
		// 				select 
		// 					ticket.ticket_status as status_id,
		// 					status.status_name as status_name,
		// 					'0' as substatus_1,
		// 					(select 
		// 						count(*) 
		// 					 from helpcso_activity activity
		// 					 inner join helpcso_ticket ticket2 on activity.activity_id = ticket2.activity_id
		// 					 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
		// 					 where
		// 					 	 	ticket2.ticket_id = ticket.ticket_id
		// 					 		and substatus_id = 1
		// 							and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
		// 			 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
		// 					 ) as substatus_2
		// 				from helpcso_ticket ticket
		// 				right join helpcso_status status on ticket.ticket_status = status.status_id
		// 				where status.status_flag = 't'
		// 			) as temp
		// 			group by status_id,status_name
		// 			";
		// $query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}
	
	function report_ticket_search_bycode_and_date($flag_number,$startDate,$endDate,$activity_code){
		$array = explode(';',$activity_code);
		$num_array = count($array);
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and activity.activity_code = '".$activity_code."'
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and activity.activity_code = '".$activity_code."'
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
					
		$query = $this->db->query($sqlquery);
		// $sqlquery = "
		// 			  Select
		// 			  	status_id as Status_id,
		// 				status_name as Status,
		// 				sum(substatus_1) as Assigned,
		// 				sum(substatus_2) as Un_Assigned
		// 			  from (
		// 				select 
		// 					ticket.ticket_status as status_id,
		// 					status.status_name as status_name,
		// 					(select 
		// 						count(*) 
		// 					 from helpcso_activity activity
		// 					 inner join helpcso_ticket ticket2 on activity.activity_id = ticket2.activity_id
		// 					 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
		// 					 where
		// 					 	 	ticket2.ticket_id = ticket.ticket_id
		// 					 		and substatus_id = 2
		// 							and activity.activity_code in (";
		// 	for ($i=0;$i<$num_array;$i++){ 
		// 		$sqlquery .= "'".$array[$i]."'";	
		// 		if ($i+1 < $num_array) $sqlquery .= ",";	
		// 	};									
		// 	$sqlquery .= ")
		// 							and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
		// 			 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
		// 					 ) as substatus_1,
		// 					'0' as substatus_2
		// 				from helpcso_ticket ticket
		// 				right join helpcso_status status on ticket.ticket_status = status.status_id
		// 				where status.status_flag = 't'
							  
		// 				union all
						
		// 				select 
		// 					ticket.ticket_status as status_id,
		// 					status.status_name as status_name,
		// 					'0' as substatus_1,
		// 					(select 
		// 						count(*) 
		// 					 from helpcso_activity activity
		// 					 inner join helpcso_ticket ticket2 on activity.activity_id = ticket2.activity_id
		// 					 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
		// 					 where
		// 					 	 	ticket2.ticket_id = ticket.ticket_id
		// 					 		and substatus_id = 1
		// 							and activity.activity_code in (";
		// 	for ($i=0;$i<$num_array;$i++){ 
		// 		$sqlquery .= "'".$array[$i]."'";	
		// 		if ($i+1 < $num_array) $sqlquery .= ",";	
		// 	};									
		// 	$sqlquery .= ")
		// 							and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
		// 			 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
		// 					 ) as substatus_2
		// 				from helpcso_ticket ticket
		// 				right join helpcso_status status on ticket.ticket_status = status.status_id
		// 				where status.status_flag = 't'
		// 			) as temp
		// 			group by status_id,status_name
		// 			";
					
		// $query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}
	function report_ticket_search_bydate_and_id($flag_number,$startDate,$endDate,$id){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and ticket2.owner_group_id = '".$id."'
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and ticket2.owner_group_id = '".$id."'
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}

	function report_ticket_search_byall($flag_number,$startDate,$endDate,$activity_code,$id){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and ticket2.owner_group_id = '".$id."'
					 	      	 	and activity.activity_code LIKE '".$activity_code."%'
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and ticket2.owner_group_id = '".$id."'
					 	      	 	and activity.activity_code LIKE '".$activity_code."%'
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}

	function report_ticket_search_byuser_all($flag_number,$startDate,$endDate,$activity_code,$creator_id){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and ticket2.creator_id = '".$creator_id."'
					 	      	 	and activity.activity_code LIKE '".$activity_code."%'
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and ticket2.creator_id = '".$creator_id."'
					 	      	 	and activity.activity_code LIKE '".$activity_code."%'
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}
// M027

	function report_ticket_search_bylevel($flag_number,$level){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 		and (level.code_id = '".$level."'
							 			or level2.code_id = '".$level."')
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 		and (level.code_id = '".$level."'
							 			or level2.code_id = '".$level."')
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}		

	}
	function report_ticket_search_bylevel_and_date($flag_number,$startDate,$endDate,$level){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
							 		and (level.code_id = '".$level."'
							 			or level2.code_id = '".$level."')
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
							 		and (level.code_id = '".$level."'
							 			or level2.code_id = '".$level."')
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}
	function report_ticket_search_bylevel_and_group($flag_number,$level,$id){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 		and (level.code_id = '".$level."'
							 			or level2.code_id = '".$level."')
					 	      	 	and ticket2.owner_group_id = '".$id."'
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 		and (level.code_id = '".$level."'
							 			or level2.code_id = '".$level."')
					 	      	 	and ticket2.owner_group_id = '".$id."'
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}		
	}	 	
	function report_ticket_search_bylevel_and_activity($flag_number,$level,$activity_code){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 		and (level.code_id = '".$level."'
							 			or level2.code_id = '".$level."')
					 	      	 	and activity.activity_code LIKE '".$activity_code."%'
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 		and (level.code_id = '".$level."'
							 			or level2.code_id = '".$level."')
					 	      	 	and activity.activity_code LIKE '".$activity_code."%'
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}		
	}	 	
	function report_ticket_search_bydate_level_and_activity($flag_number,$startDate,$endDate,$level,$activity_code){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 		and (level.code_id = '".$level."'
							 			or level2.code_id = '".$level."')
					 	      	 	and activity.activity_code LIKE '".$activity_code."%'
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_user user on user.user_id = ticket2.creator_id
							 inner join mst_level_user level on level.code_id = user.level
							inner join helpcso_user user2 on ticket2.owner_id = user2.user_id
							inner join mst_level_user level2 on level2.code_id = user2.level
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 		and 
							 		)
					 	      	 	and activity.activity_code LIKE '".$activity_code."%'
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}		
	}	 		 	
// M040 M043 M050 M67
	function get_ticket_report_detail($flag_number,$startDate,$endDate,$status,$substatus,$activity_code,$id,$creator_id,$level){
		if($flag_number == 1) {
			$sqlquery = "select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					left join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."";
		// $sqlquery = "
		// 			select 
		// 				activity.activity_id as activity_id,
		// 				activity.activity_code as activity_code,
		// 				activity.activity_description as activity_description,
		// 				ticket.ticket_id as ticket_id,
		// 				ticket.customer_name as customer_name,
		// 				ticket.customer_type as customer_type,
		// 				ticket.customer_phone as customer_phone,
		// 				ticket.customer_email as customer_email,
		// 				ticket.recovering_datetime as recovering_datetime,
		// 				ticket.recovered_datetime as recovered_datetime,
		// 				IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
		// 				user.user_name as creator_name,						
		// 				IFNULL(ticket.creator_datetime,'') as create_datetime,
		// 				status.status_name as status,
		// 				substatus.substatus_name as substatus,
		// 				IFNULL(ticket.solved_datetime,'') as solved_datetime,
		// 				IFNULL(ticket.closed_datetime,'') as closed_datetime,
		// 				IFNULL((
		// 					select MAX(log_datetime) from helpcso_ticket_log 
		// 					where 
		// 						 ticket_id = ticket.ticket_id 
		// 						 and log_desc_id=1
		// 					Order by log_datetime DESC
		// 				),'') as handled_datetime,
		// 				ticket.detail_info as ticket_info,
		// 				IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
		// 				IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
		// 				ticket.resolution_note as resolution_note,
		// 				ticket.recovery_note as recovery_note
		// 			from helpcso_ticket ticket
		// 			inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
		// 			inner join helpcso_status status on status.status_id = ticket.ticket_status
		// 			inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
		// 			left join helpcso_user user on ticket.creator_id = user.user_id
		// 			where  
		// 				   ticket.ticket_status = ".$status."
		// 				   and ticket.ticket_substatus = ".$substatus."
		// 			";	
		}
		else if ($flag_number == 2) {
		$array = explode(';',$activity_code);
		$num_array = count($array);
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					left join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 3) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					left join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
					";
		}
		else if ($flag_number == 4) {
		$array = explode(';',$activity_code);
		$num_array = count($array);
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					left join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and activity.activity_code LIKE '".$activity_code."%'
						   and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
					";
		}
		else if ($flag_number == 5) {
		$array = explode(';',$id);
		$num_array = count($array);
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and ticket.owner_group_id in (";
			for ($i=0;$i<$num_array;$i++){ 
				$sqlquery .= "'".$array[$i]."'";	
				if ($i+1 < $num_array) $sqlquery .= ",";	
			};									
			$sqlquery .= ")";
		}
		else if ($flag_number == 6) {
		$array = explode(';',$creator_id);
		$num_array = count($array);
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and ticket.creator_id in (";
			for ($i=0;$i<$num_array;$i++){ 
				$sqlquery .= "'".$array[$i]."'";	
				if ($i+1 < $num_array) $sqlquery .= ",";	
			};									
			$sqlquery .= ")";
		}
		else if ($flag_number == 7) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and ticket.owner_group_id = '".$id."'
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 8) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						   and ticket.owner_group_id = '".$id."'";
		}
		else if ($flag_number == 9) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						   and ticket.owner_group_id = '".$id."'
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 10) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and ticket.creator_id = '".$creator_id."'
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 11) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						   and ticket.creator_id = '".$creator_id."'";
		}
		else if ($flag_number == 12) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						   and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						   and ticket.creator_id = '".$creator_id."'
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 13) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					inner join mst_level_user level on level.code_id = user.level
					inner join helpcso_user user2 on ticket.owner_id = user2.user_id
					inner join mst_level_user level2 on level2.code_id = user2.level
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
	 				    and (level.code_id = '".$level."'
	 				    or level2.code_id = '".$level."')
			    ";
		}
		else if ($flag_number == 14) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					inner join mst_level_user level on level.code_id = user.level
					inner join helpcso_user user2 on ticket.owner_id = user2.user_id
					inner join mst_level_user level2 on level2.code_id = user2.level
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
						and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					    and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
	 				    and (level.code_id = '".$level."'
	 				    or level2.code_id = '".$level.")
			    ";
		}
		else if ($flag_number == 15) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					inner join mst_level_user level on level.code_id = user.level
					inner join helpcso_user user2 on ticket.owner_id = user2.user_id
					inner join mst_level_user level2 on level2.code_id = user2.level
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
	 				    and (level.code_id = '".$level."'
	 				    or level2.code_id = '".$level."')
	 				    and ticket.owner_group_id = '".$id."'
			    ";
		}
		else if ($flag_number == 16) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					inner join mst_level_user level on level.code_id = user.level
					inner join helpcso_user user2 on ticket.owner_id = user2.user_id
					inner join mst_level_user level2 on level2.code_id = user2.level
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
	 				    and (level.code_id = '".$level."'
	 				    or level2.code_id = '".$level."')
						   and activity.activity_code LIKE '".$activity_code."%'
			    ";
		}
		else if ($flag_number == 17) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					left join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					inner join mst_level_user level on level.code_id = user.level
					inner join helpcso_user user2 on ticket.owner_id = user2.user_id
					inner join mst_level_user level2 on level2.code_id = user2.level
					where
						ticket.ticket_status = ".$status."
	 				    and ticket.ticket_substatus = ".$substatus."
	 				    and (level.code_id = '".$level."'
	 				    or level2.code_id = '".$level."')
						and activity.activity_code LIKE '".$activity_code."%'
						and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					    and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
			    ";
		}
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
// M040 M043 M050 M67
	//M003

	function get_ticket_activity_report($flag_query,$activity_code,$date){
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
						  ticket.activity_id as id,
						  activity.activity_code as code,
						  activity.activity_description as description,
						  (select 
								count(*) 
							 from helpcso_ticket ticket2
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
						  ) as jumlah
						from helpcso_ticket ticket
						inner join helpcso_activity activity on ticket.activity_id = activity.activity_id
						where activity.activity_level = 4
						) as temp
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
						  ticket.activity_id as id,
						  activity.activity_code as code,
						  activity.activity_description as description,
						  (
						  select 
								count(*) 
							 from helpcso_ticket ticket2
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
						  ) as jumlah
						from helpcso_ticket ticket
						inner join helpcso_activity activity on ticket.activity_id = activity.activity_id
						where
							DATE_FORMAT(ticket.creator_datetime, '%m/%d/%Y') = '" . str_replace(" ", "%", $date) . "'
							and activity.activity_level = 4
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
							  and activity_code in (";
						for ($i=0;$i<$num_array;$i++){ 
							$sqlquery .= "'".$array[$i]."'";	
							if ($i+1 < $num_array) $sqlquery .= ",";	
						};									
						$sqlquery .= ")
						
						union all
						
						select
						  ticket.activity_id as id,
						  activity.activity_code as code,
						  activity.activity_description as description,
						  (select 
								count(*) 
							 from helpcso_ticket ticket2
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
						  ) as jumlah
						from helpcso_ticket ticket
						inner join helpcso_activity activity on ticket.activity_id = activity.activity_id
						where activity.activity_level = 4
							  and activity.activity_code in (";
								for ($i=0;$i<$num_array;$i++){ 
									$sqlquery .= "'".$array[$i]."'";	
									if ($i+1 < $num_array) $sqlquery .= ",";	
								};									
								$sqlquery .= ")
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
							  and activity_code in (";
						for ($i=0;$i<$num_array;$i++){ 
							$sqlquery .= "'".$array[$i]."'";	
							if ($i+1 < $num_array) $sqlquery .= ",";	
						};									
						$sqlquery .= ")
						
						union all
						
						select
						  ticket.activity_id as id,
						  activity.activity_code as code,
						  activity.activity_description as description,
						  (select 
								count(*) 
							 from helpcso_ticket ticket2
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
						  ) as jumlah
						from helpcso_ticket ticket
						inner join helpcso_activity activity on ticket.activity_id = activity.activity_id
						where activity.activity_level = 4
							  and activity.activity_code in (";
								for ($i=0;$i<$num_array;$i++){ 
									$sqlquery .= "'".$array[$i]."'";	
									if ($i+1 < $num_array) $sqlquery .= ",";	
								};									
								$sqlquery .= ")
							 and DATE_FORMAT(ticket.creator_datetime, '%m/%d/%Y') = '" . str_replace(" ", "%", $date) . "'
						) as temp
						group by id,code,code,description
					";
		}
		$query = $this->db->query($sqlquery);
	    return $query->result();
	}
	
	function get_ticket_activity_report_detail($flag_query,$date,$activity_id){
		if ($flag_query == 1) {
				$sqlquery = "
							select 
								activity.activity_id as activity_id,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								ticket.ticket_id as ticket_id,
								ticket.customer_name as customer_name,
								ticket.customer_type as customer_type,
								ticket.customer_phone as customer_phone,
								ticket.customer_email as customer_email,
								IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
								user.user_name as creator_name,						
								IFNULL(ticket.creator_datetime,'') as create_datetime,
								status.status_name as status,
								substatus.substatus_name as substatus,
								IFNULL(ticket.solved_datetime,'') as solved_datetime,
								IFNULL(ticket.closed_datetime,'') as closed_datetime,
								IFNULL((
									select MAX(log_datetime) from helpcso_ticket_log 
									where 
										 ticket_id = ticket.ticket_id 
										 and log_description = 'handled the ticket' 
									Order by log_datetime DESC
								),'') as handled_datetime,
								ticket.detail_info as ticket_info
							from helpcso_ticket ticket
							inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
							inner join helpcso_status status on status.status_id = ticket.ticket_status
							inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
							left join helpcso_user user on ticket.creator_id = user.user_id 
							where  
								   ticket.activity_id = ".$activity_id."
							";	
				}
		else if ($flag_query == 2) {
				$sqlquery = "
							select 
								activity.activity_id as activity_id,
								activity.activity_code as activity_code,
								activity.activity_description as activity_description,
								ticket.ticket_id as ticket_id,
								ticket.customer_name as customer_name,
								ticket.customer_type as customer_type,
								ticket.customer_phone as customer_phone,
								ticket.customer_email as customer_email,
								IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
								user.user_name as creator_name,						
								IFNULL(ticket.creator_datetime,'') as create_datetime,
								status.status_name as status,
								substatus.substatus_name as substatus,
								IFNULL(ticket.solved_datetime,'') as solved_datetime,
								IFNULL(ticket.closed_datetime,'') as closed_datetime,
								IFNULL((
									select MAX(log_datetime) from helpcso_ticket_log 
									where 
										 ticket_id = ticket.ticket_id 
										 and log_description = 'handled the ticket' 
									Order by log_datetime DESC
								),'') as handled_datetime,
								ticket.detail_info as ticket_info
							from helpcso_ticket ticket
							inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
							inner join helpcso_status status on status.status_id = ticket.ticket_status
							inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
							left join helpcso_user user on ticket.creator_id = user.user_id
							where  
								  ticket.activity_id = ".$activity_id."
								  and DATE_FORMAT(ticket.creator_datetime, '%m/%d/%Y') = '" . str_replace(" ", "%", $date) . "'  
							";
				}
		$query = $this->db->query($sqlquery);
	    return $query->result();
	}

	function get_user_group(){
		$sqlquery = "Select * from helpcso_user_group where status_active = 1";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}

	function get_user_name(){
		$sqlquery = "Select * from helpcso_user where status_active = 1 order by user_name asc";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function get_user_level(){
		$sqlquery = "Select * from mst_level_user";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}

	function get_ticket_report_bygroup($flag_query,$id){
		if ($flag_query == 1) {
			$sqlquery = "Select
						status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'

						union all
						
						select
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name";
		}else{
			$array = explode(';',$id);
			$num_array = count($array);
			$sqlquery = "
					Select
						status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
            inner join helpcso_user user on user.user_id = ticket.creator_id
						where status.status_flag = 't'
            and user.group_id = '".$id."'
            and ticket.activity_id <> ''
						union all
						
						select
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
            inner join helpcso_user user on user.user_id = ticket.creator_id
						where status.status_flag = 't'
            and user.group_id = '".$id."'
            and ticket.activity_id <> ''
					) as temp
					group by status_id,status_name
					";
		}
		$query = $this->db->query($sqlquery);
	    return $query->result();
	
	}

	
	function search_report_ticket_byactivity_and_id($flag_number,$activity_code,$id){
		$array = explode(';',$activity_code);
		$num_array = count($array);
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
					 	      	 	and activity.activity_code = '".$activity_code."'
					 	      	 	and ticket2.owner_group_id = '".$id."'
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_activity activity on activity.activity_id = ticket2.activity_id
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
					 	      	 	and activity.activity_code = '".$activity_code."'
					 	      	 	and ticket2.owner_group_id = '".$id."'
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
					
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}
	function get_ticket_report_byuser($flag_query,$creator_id){
		if ($flag_query == 1) {
			$sqlquery = "Select
						status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'

						union all
						
						select
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name";
		}else{
			$array = explode(';',$creator_id);
			$num_array = count($array);
			$sqlquery = "
					Select
						status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
            inner join helpcso_user user on user.user_id = ticket.creator_id
						where status.status_flag = 't'
            and ticket.creator_id = '".$creator_id."'
            and ticket.activity_id <> ''
						union all
						
						select
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
            inner join helpcso_user user on user.user_id = ticket.creator_id
						where status.status_flag = 't'
            and ticket.creator_id = '".$creator_id."'
            and ticket.activity_id <> ''
					) as temp
					group by status_id,status_name
					";
		}
		$query = $this->db->query($sqlquery);
	    return $query->result();
	
	}
	function get_ticket_report_byuser_and_activity($flag_query,$creator_id,$activity_code){
			$array = explode(';',$creator_id);
			$num_array = count($array);
			$sqlquery = "
					Select
						status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
            inner join helpcso_user user on user.user_id = ticket.creator_id
						where status.status_flag = 't'
            and ticket.creator_id = '".$creator_id."'
            and activity.activity_code = '".$activity_code."'
						union all
						
						select
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
            inner join helpcso_user user on user.user_id = ticket.creator_id
						where status.status_flag = 't'
            and ticket.creator_id = '".$creator_id."'
            and activity.activity_code = '".$activity_code."'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
	    return $query->result();
	}

	function get_ticket_report_byuser_and_date($flag_number,$startDate,$endDate,$creator_id){
		$sqlquery = "
					  Select
					  	status_id as Status_id,
						status_name as Status,
						sum(substatus_1) as Assigned,
						sum(substatus_2) as Un_Assigned
					  from (
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 2
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and ticket2.creator_id = '".$creator_id."'
						            and ticket2.activity_id <> ''
							 ) as substatus_1,
							'0' as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
							  
						union all
						
						select 
							ticket.ticket_status as status_id,
							status.status_name as status_name,
							'0' as substatus_1,
							(select 
								count(*)
							 from helpcso_ticket ticket2
							 inner join helpcso_substatus substatus on ticket2.ticket_substatus = substatus.substatus_id
							 where
							 	 	ticket2.ticket_id = ticket.ticket_id
							 		and substatus_id = 1
									and ticket2.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	      	 	and ticket2.creator_datetime <= '" . str_replace(" ", "%", $endDate) . "23:59:59'
					 	      	 	and ticket2.creator_id = '".$creator_id."'
						            and ticket2.activity_id <> ''
							 ) as substatus_2
						from helpcso_ticket ticket
						right join helpcso_status status on ticket.ticket_status = status.status_id
						where status.status_flag = 't'
					) as temp
					group by status_id,status_name
					";
		$query = $this->db->query($sqlquery);
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
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
// M60
// M62
	function get_ticket_report_detail2($flag_number,$startDate,$endDate,$activity_code,$id,$creator_id,$level){
		if($flag_number == 1) {
			$sqlquery = "select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					left join helpcso_user user on ticket.creator_id = user.user_id";
		// $sqlquery = "
		// 			select 
		// 				activity.activity_id as activity_id,
		// 				activity.activity_code as activity_code,
		// 				activity.activity_description as activity_description,
		// 				ticket.ticket_id as ticket_id,
		// 				ticket.customer_name as customer_name,
		// 				ticket.customer_type as customer_type,
		// 				ticket.customer_phone as customer_phone,
		// 				ticket.customer_email as customer_email,
		// 				ticket.recovering_datetime as recovering_datetime,
		// 				ticket.recovered_datetime as recovered_datetime,
		// 				IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
		// 				user.user_name as creator_name,						
		// 				IFNULL(ticket.creator_datetime,'') as create_datetime,
		// 				status.status_name as status,
		// 				substatus.substatus_name as substatus,
		// 				IFNULL(ticket.solved_datetime,'') as solved_datetime,
		// 				IFNULL(ticket.closed_datetime,'') as closed_datetime,
		// 				IFNULL((
		// 					select MAX(log_datetime) from helpcso_ticket_log 
		// 					where 
		// 						 ticket_id = ticket.ticket_id 
		// 						 and log_desc_id=1
		// 					Order by log_datetime DESC
		// 				),'') as handled_datetime,
		// 				ticket.detail_info as ticket_info,
		// 				IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
		// 				IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
		// 				ticket.resolution_note as resolution_note,
		// 				ticket.recovery_note as recovery_note
		// 			from helpcso_ticket ticket
		// 			inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
		// 			inner join helpcso_status status on status.status_id = ticket.ticket_status
		// 			inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
		// 			left join helpcso_user user on ticket.creator_id = user.user_id
		// 			where  
		// 				   ticket.ticket_status = ".$status."
		// 				   and ticket.ticket_substatus = ".$substatus."
		// 			";	
		}
		else if ($flag_number == 2) {
		$array = explode(';',$activity_code);
		$num_array = count($array);
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					left join helpcso_user user on ticket.creator_id = user.user_id
					where activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 3) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					left join helpcso_user user on ticket.creator_id = user.user_id
					where ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
					";
		}
		else if ($flag_number == 4) {
		$array = explode(';',$activity_code);
		$num_array = count($array);
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					left join helpcso_user user on ticket.creator_id = user.user_id
					where activity.activity_code LIKE '".$activity_code."%'
						   and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
					";
		}
		else if ($flag_number == 5) {
		$array = explode(';',$id);
		$num_array = count($array);
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where user.group_id in (";
			for ($i=0;$i<$num_array;$i++){ 
				$sqlquery .= "'".$array[$i]."'";	
				if ($i+1 < $num_array) $sqlquery .= ",";	
			};									
			$sqlquery .= ")";
		}
		else if ($flag_number == 6) {
		$array = explode(';',$creator_id);
		$num_array = count($array);
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where ticket.creator_id in (";
			for ($i=0;$i<$num_array;$i++){ 
				$sqlquery .= "'".$array[$i]."'";	
				if ($i+1 < $num_array) $sqlquery .= ",";	
			};									
			$sqlquery .= ")";
		}
		else if ($flag_number == 7) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where ticket.owner_group_id = '".$id."'
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 8) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						   and ticket.owner_group_id = '".$id."'";
		}
		else if ($flag_number == 9) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						   and ticket.owner_group_id = '".$id."'
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 10) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where ticket.creator_id = '".$creator_id."'
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 11) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						   and ticket.creator_id = '".$creator_id."'";
		}
		else if ($flag_number == 12) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					where ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'
						   and ticket.creator_id = '".$creator_id."'
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		else if ($flag_number == 13) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					inner join mst_level_user level on level.code_id = user.level
					inner join helpcso_user user2 on ticket.owner_id = user2.user_id
					inner join mst_level_user level2 on level2.code_id = user2.level
					where level.code_id = '".$level."'
							or level2.code_id = '".$level."'";
		}
		else if ($flag_number == 14) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					inner join mst_level_user level on level.code_id = user.level
					inner join helpcso_user user2 on ticket.owner_id = user2.user_id
					inner join mst_level_user level2 on level2.code_id = user2.level
					where (level.code_id = '".$level."' or level2.code_id = '".$level."')
							and ticket.creator_datetime >= '" . str_replace(" ", "%", $startDate) . "' 
					 	    and ticket.creator_datetime <= '" . str_replace(" ", "%", $endDate) . " 23:59:59'";
		}
		else if ($flag_number == 15) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					inner join mst_level_user level on level.code_id = user.level
					inner join helpcso_user user2 on ticket.owner_id = user2.user_id
					inner join mst_level_user level2 on level2.code_id = user2.level
					where (level.code_id = '".$level."' or level2.code_id = '".$level."')
							and ticket.owner_group_id = '".$id."'";
		}
		else if ($flag_number == 16) {
		$sqlquery = "
					select
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.code_ticket as code_ticket,
						ticket.id_pesanan as id_pesanan,
						ticket.so_number as so_number,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						ticket.recovering_datetime as recovering_datetime,
						ticket.recovered_datetime as recovered_datetime,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log
							where
								 ticket_id = ticket.ticket_id
								 and log_desc_id=1
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name,
						IFNULL((select user_name from helpcso_user where user_id = ticket.recover_id),'') as recover_name,
						ticket.resolution_note as resolution_note,
						ticket.recovery_note as recovery_note
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					inner join helpcso_user user on ticket.creator_id = user.user_id
					inner join mst_level_user level on level.code_id = user.level
					inner join helpcso_user user2 on ticket.owner_id = user2.user_id
					inner join mst_level_user level2 on level2.code_id = user2.level
					where (level.code_id = '".$level."' or level2.code_id = '".$level."')
						   and activity.activity_code LIKE '".$activity_code."%'";
		}
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
// M62
}
?>