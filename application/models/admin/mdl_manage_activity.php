<!-- M007 - YA - Export activity to excel -->
<!-- M011 - YA - Import activity -->
<!-- M049 - YA - Tampilan ticket template bila level 4 dan berbentuk count -->
<?php
class Mdl_manage_activity extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	
	function get_act_code()
	{
		$sqlquery = "Select activity_code from helpcso_activity";
		return $this->db->query($sqlquery);
	}

	function get_pil_status_active()
	{
		$sqlquery = "Select * from mst_status_active";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function get_last_id()
		{
			$q = $this->db->query("select MAX(activity_id) as activity_id from helpcso_activity");
	        $code = 0;
	        if($q->num_rows()>0){
	            foreach($q->result() as $cd){
	                $tmp = ((int)$cd->activity_id)+1;
	                $code = $tmp;
	            }
	        }else{
	            $code = "1";
	        }
	        return $code;
		}

// M049
	function activity_get_all(){
		$query = $this->db->query("select
									activity.activity_id as activity_id,
									activity.activity_code as activity_code,
									activity.activity_parent as activity_parent,
									activity.activity_description as activity_description,
									count(ticket_field.activity_id) as total_activityplan,
									count(template_activity.ticket_template_id) as total_ticket_template_id,
									REPLACE(activity.activity_definition, '\n', '<br />') as activity_definition,
									activity.activity_level as activity_level,
									case
										when activity.activity_level = '1' THEN 'Issue Type'
										when activity.activity_level = '2' THEN 'Issue Group'
										when activity.activity_level = '3' THEN 'Issue Sub Group'
										when activity.activity_level = '4' THEN 'Issue Description'
									END as activity_type,
									activity.status_active as status_active,
									msa.status_active as status_active_name
									from helpcso_activity activity
									inner join mst_status_active msa on activity.status_active = msa.code_id
									left join helpcso_ticket_field ticket_field on ticket_field.activity_id=activity.activity_id
									left join helpcso_ticket_template_activity template_activity on template_activity.activity_code=activity.activity_code
									group by activity.activity_id");
		
		return $query->result();
	}
// M049

	function ticket_activity_get_all(){
		$query = $this->db->query("select * from helpcso_activity_plan");
		return $query->result();
	}
	
// M007
	function activity_get_all_toexcel(){
		$query = $this->db->query("select 
									activity.activity_id as activity_id, 
									activity.activity_code as activity_code,
									activity.activity_parent as activity_parent,
									activity.parent_code as parent_code,
									activity.activity_description as activity_description,
									REPLACE(REPLACE(REPLACE(activity.activity_definition, '<p>', ' '), '</p>', ' '), '<br />', ' ') as activity_definition,
									activity.activity_level as activity_level,
									case
										when activity.activity_level = '1' THEN 'Issue Type'
										when activity.activity_level = '2' THEN 'Issue Group'
										when activity.activity_level = '3' THEN 'Issue Sub Group'
										when activity.activity_level = '4' THEN 'Issue Description'
									END as activity_type,
									activity.status_active as status_active,
									msa.status_active as status_active_name
									from helpcso_activity activity
									inner join mst_status_active msa on activity.status_active = msa.code_id");
		
		return $query->result();
	}
// M007
// M011
	function import_activity($dataexcel){
		for($i=0;$i<count($dataexcel);$i++){
	        $data = array(
	            'activity_id'=>$dataexcel[$i]['activity_id'],
	            'activity_code'=>$dataexcel[$i]['activity_code'],
	            // 'activity_parent'=>$dataexcel[$i]['activity_parent'],
	            'parent_code'=>$dataexcel[$i]['parent_code'],
	            'activity_description'=>$dataexcel[$i]['activity_description'],
	            'activity_definition'=>$dataexcel[$i]['activity_definition'],
	            'activity_level'=>$dataexcel[$i]['activity_level'],
	            'status_active'=>$dataexcel[$i]['status_active']
	        );
	        $this->db->insert('helpcso_activity', $data);
        }
	}

	function import_activity_edit($dataexcel){
		for($i=0;$i<count($dataexcel);$i++){
            $data = array(
	            // 'activity_id'=>$dataexcel[$i]['activity_id'],
	            'activity_code'=>$dataexcel[$i]['activity_code'],
	            'activity_parent'=>$dataexcel[$i]['activity_parent'],
	            'parent_code'=>$dataexcel[$i]['parent_code'],
	            'activity_description'=>$dataexcel[$i]['activity_description'],
	            'activity_definition'=>$dataexcel[$i]['activity_definition'],
	            'activity_level'=>$dataexcel[$i]['activity_level'],
	            'status_active'=>$dataexcel[$i]['status_active']
	        );
            $param = array(
               'activity_code'=>$dataexcel[$i]['activity_code']
            );
		$this->db->where($param);
		return $this->db->update('helpcso_activity', $data);
        }
	}

	function cek_activity($dataexcel){
        for($i=0;$i<count($dataexcel);$i++){
            $cek = array(
                'activity_code'=>$dataexcel[$i]['activity_code']
            );
		}
		$data = array();
		$this->db->select('activity_code');
		$this->db->where($cek);
		$this->db->limit(1);
		$Q = $this->db->get('helpcso_activity');
		if($Q->num_rows() > 0){
			$data = $Q->row_array();
		}
		$Q->free_result();
		return $data;
		}

	function update_act_parent(){
		$query = $this->db->query("update helpcso_activity as a inner join helpcso_activity b on a.activity_code=b.parent_code set b.activity_parent=a.activity_id where b.parent_code=a.activity_code");
		return $query->result();

			// $query = $this->db->query("select a.activity_id from helpcso_activity a 
			// 	inner join helpcso_activity b on a.activity_code=b.parent_code where b.parent_code=a.activity_code");
		// return $query->result();
	}
// M011
	
	function get_last_activity_id(){
		$sqlquery = "select max(activity_id) as last_id from helpcso_activity";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	
	function check_child_activity($flag,$activity_id){
			$query = $this->db->query("select activity_description
									   from helpcso_activity 
									   where activity_parent = '".$activity_id."' and status_active = '1' LIMIT 1");
			if ($flag == 1){
				return $query;
			}
			else if ($flag == 2){
				return $query->num_rows();
			}
		}
		
	function add_activity($activity_data){
		$activity_data['activity_id'] = $this->get_last_activity_id() + 1;
		$this->db->insert('helpcso_activity', $activity_data);
	}
	function edit_activity($activity_id, $activity_data){
		$this->db->where('activity_id', $activity_id);
		$this->db->update('helpcso_activity', $activity_data);
	}
	
	function activity_search($flag_query,$flag,$keyword){
		if($flag_query == 1) {
			$query = $this->db->query("select 
											activity.activity_id as activity_id, 
											activity.activity_code as activity_code,
											activity.activity_parent as activity_parent,
											activity.activity_description as activity_description,
											activity.activity_definition as activity_definition,
											count(ticket_field.activity_id) as total_activityplan,
											activity.activity_level as activity_level,
											case
												when activity.activity_level = '1' THEN 'Issue Type'
												when activity.activity_level = '2' THEN 'Issue Group'
												when activity.activity_level = '3' THEN 'Issue Sub Group'
												when activity.activity_level = '4' THEN 'Issue Description'
											END as activity_type,
											activity.status_active as status_active,
											msa.status_active as status_active_name
										from helpcso_activity activity
										left join helpcso_ticket_field ticket_field on ticket_field.activity_id=activity.activity_id
										inner join mst_status_active msa on activity.status_active = msa.code_id
									   where activity.activity_description like '%" . str_replace(" ", "%", $keyword) . "%'");
		}
		else if($flag_query == 2) {
			$query = $this->db->query("
										select 
											activity.activity_id as activity_id, 
											activity.activity_code as activity_code,
											activity.activity_parent as activity_parent,
											activity.activity_description as activity_description,
											activity.activity_definition as activity_definition,
											activity.activity_level as activity_level,
											case
												when activity.activity_level = '1' THEN 'Issue Type'
												when activity.activity_level = '2' THEN 'Issue Group'
												when activity.activity_level = '3' THEN 'Issue Sub Group'
												when activity.activity_level = '4' THEN 'Issue Description'
											END as activity_type,
											activity.status_active as status_active,
											msa.status_active as status_active_name
										from helpcso_activity activity
										inner join mst_status_active msa on activity.status_active = msa.code_id
									   	where activity.activity_description like '%" . str_replace(" ", "%", $keyword) . "%'
										 LIMIT 5");
		}
		if ($flag == 1){
			return $query->result();
		}
		else {
			return $query->num_rows();
		}
	}
	
	function par_activity_get($level,$activity_id)
		{
			$sqlquery = "Select * 
						 from helpcso_activity
						 where activity_level = '" .$level."' 
						 	   and status_active = '1'
							   and activity_id <> '".$activity_id."'";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
	
	//fields

	
	function activity_field_get_all($activity_id){
		$query = $this->db->query("select field.field_id as field_id, 
										  field.field_name as field_name, 
										  field.field_mandatory as field_mandatory,
										  case
												when field.field_mandatory = '1' THEN 'Yes'
												when field.field_mandatory = '0' THEN 'No'
											END as field_mandatory_name,
										  field.status_active as status_active,
										  msa.status_active as status_active_name
								   from helpcso_ticket_field field
								   inner join mst_status_active msa on field.status_active = msa.code_id
								   where field.activity_id = '".$activity_id."'");
		return $query->result();
	}
	
	function get_activity_field_last_id(){
		$sqlquery = "select max(field_id) as last_id from helpcso_ticket_field";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	
	function add_activity_field($activity_field_data,$activity_id){
		$activity_field_data['field_id'] = $this->get_activity_field_last_id() + 1;
		$activity_field_data['activity_id'] = $activity_id;
		$this->db->insert('helpcso_ticket_field', $activity_field_data);
	}
	
	function edit_activity_field($activity_field_id, $activity_field_data){
		$this->db->where('field_id', $activity_field_id);
		$this->db->update('helpcso_ticket_field', $activity_field_data);
	}

	function search_activity_field($flag_query,$flag,$activity_id,$keyword){
		if ($flag_query == 1){
			$query = $this->db->query("select 
											  field.field_id as field_id, 
											  field.field_name as field_name, 
											  field.field_mandatory as field_mandatory,
											  case
													when field.field_mandatory = '1' THEN 'Yes'
													when field.field_mandatory = '0' THEN 'No'
											  END as field_mandatory_name,
											  field.status_active as status_active,
											  msa.status_active as status_active_name
										  from helpcso_ticket_field field
										  inner join mst_status_active msa on field.status_active = msa.code_id
										  where field.activity_id = '".$activity_id."'
										  and field.field_name like '%" . str_replace(" ", "%", $keyword) . "%'");
		} 
		else if ($flag_query == 2){
			$query = $this->db->query("select 
											  field.field_id as field_id, 
											  field.field_name as field_name, 
											  field.field_mandatory as field_mandatory,
											  case
													when field.field_mandatory = '1' THEN 'Yes'
													when field.field_mandatory = '0' THEN 'No'
											  END as field_mandatory_name,
											  field.status_active as status_active,
											  msa.status_active as status_active_name
										  from helpcso_ticket_field field
										  inner join mst_status_active msa on field.status_active = msa.code_id
										  where field.activity_id = '".$activity_id."'
										  and field.field_name like '%" . str_replace(" ", "%", $keyword) . "%' LIMIT 5");
		}
		if ($flag == 1){
			return $query->result();
		}
		else {
			return $query->num_rows();
		}
	}
}
?>