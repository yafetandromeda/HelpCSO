<?php
class Mdl_manage_status extends CI_Model{	
	
	function get_pil_status_active()
	{
		$sqlquery = "Select * from mst_status_active";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	//status
	function get_all_status()
	{	$sqlquery = "Select 
						status.status_id as status_id,
						status.status_name as status_name,
						status.status_flag as status_flag,
						case
							when status.status_flag = 'i' THEN 'Interaction'
							when status.status_flag = 't' THEN 'Ticket'
						END as status_type,
						status.status_color,
						status.status_active as status_active,
						msa.status_active as status_active_name,
						status.status_primary as status_primary
					from helpcso_status status
					inner join mst_status_active msa on status.status_active = msa.code_id";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function get_last_status_id(){
		$sqlquery = "select max(status_id) as last_id from helpcso_status";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
		
	function add_status($status_id,$status_name,$status_type,$status_color,$status_active,$status_primary)
	{
		$data = array(
					'status_id'=>$status_id,
					'status_name'=>$status_name,
					'status_flag'=> $status_type,
					'status_color'=> $status_color,
					'status_active'=> $status_active,
					'status_primary'=> $status_primary
					);
		$this->db->insert('helpcso_status',$data);
	}
	
	function edit_status($status_id,$status_name,$status_type,$status_color,$status_active,$status_primary)
	{	
		$data = array(
						'status_name'=>$status_name,
						'status_flag'=> $status_type,
						'status_color'=> $status_color,
						'status_active'=> $status_active,
						'status_primary'=> $status_primary
						);
		$this->db->where('status_id',$status_id);
		$this->db->update('helpcso_status',$data);
	}
	
	//substatus
	
	function get_all_substatus()
	{	$sqlquery = "Select 
						substatus.substatus_id as substatus_id,
						substatus.substatus_name as substatus_name,
						substatus.substatus_flag as substatus_flag,
						case
							when substatus.substatus_flag = 'i' THEN 'Interaction'
							when substatus.substatus_flag = 't' THEN 'Ticket'
						END as substatus_type,
						substatus.substatus_color,
						substatus.status_active as status_active,
						msa.status_active as status_active_name
					from helpcso_substatus substatus
					inner join mst_status_active msa on substatus.status_active = msa.code_id";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function get_last_substatus_id(){
		$sqlquery = "select max(substatus_id) as last_id from helpcso_substatus";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	
	function add_substatus($substatus_id,$substatus_name,$substatus_type,$substatus_color,$substatus_active)
	{
		$data = array(
					'substatus_id'=>$substatus_id,
					'substatus_name'=>$substatus_name,
					'substatus_flag'=> $substatus_type,
					'substatus_color'=> $substatus_color,
					'status_active'=> $substatus_active
					);
		$this->db->insert('helpcso_substatus',$data);
	}
	
	function edit_substatus($substatus_id,$substatus_name,$substatus_type,$substatus_color,$substatus_active)
	{	
		$data = array(
						'substatus_name'=>$substatus_name,
						'substatus_flag'=> $substatus_type,
						'substatus_color'=> $substatus_color,
						'status_active'=> $substatus_active
						);
		$this->db->where('substatus_id',$substatus_id);
		$this->db->update('helpcso_substatus',$data);
	}
}
?>