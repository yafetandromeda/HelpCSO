<?php
// MD01 - YA - Advance user profile, untuk mengatur field yang muncul hanya di user tertentu saja, atau hal – hal yang bisa dijadikan default terhadap user tersebut
class Mdl_manage_user extends CI_Model{	

	function get_last_userid()
	{
		$sqlquery = "Select total_user from mst_count_data";
		return $this->db->query($sqlquery);
	}
	
	function get_last_usergroupid()
	{
		$sqlquery = "Select total_user_group from mst_count_data";
		return $this->db->query($sqlquery);
	}
	
	function get_pil_level()
	{
		$sqlquery = "Select * from mst_level_user";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function get_pil_group()
	{
		$sqlquery = "Select id as group_id, group_name from helpcso_user_group where status_active = '1'";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function get_pil_status_active()
	{
		$sqlquery = "Select * from mst_status_active";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function get_current_password($userid)
	{
		$sqlquery = "Select password from helpcso_user where user_id ='".$userid."'";
		return $this->db->query($sqlquery);
	}
	
	function get_all_user()
	{	$sqlquery = "Select 
						user.user_id,
						user.user_name,
						mst_level.level as level, 
						mst_active.status_active as status_active, 
						user.level as level_id, 
						user.group_id as group_id,
						user_group.group_name as group_name,
						user.status_active as active_id 
				 	 from helpcso_user user
					 left join mst_level_user mst_level on user.level = mst_level.code_id
					 left join helpcso_user_group user_group on user.group_id = user_group.id
					 inner join mst_status_active mst_active on user.status_active = mst_active.code_id
					 order by user.user_id asc";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function search_user($flag_query,$flag_number,$text_search_user)
	{
		if ($flag_query == 1){
		$sqlquery = "Select 
						user_id,
						user_name,
						mst_level.level as level, 
						mst_active.status_active as status_active, 
						user.level as level_id,
						user.group_id as group_id,
						user_group.group_name as group_name,
						user.status_active as active_id 
					 from helpcso_user user
					 left join mst_level_user mst_level on user.level = mst_level.code_id 
					 left join helpcso_user_group user_group on user.group_id = user_group.id
					 inner join mst_status_active mst_active on user.status_active = mst_active.code_id
					 where user_name like '%" . str_replace(" ", "%", $text_search_user) . "%'";
		}
		else if ($flag_query == 2){
		$sqlquery = "Select 
						user_id,
						user_name,mst_level.level as level, 
						mst_active.status_active as status_active, 
						user.level as level_id,
						user.group_id as group_id,
						user_group.group_name as group_name,
						user.status_active as active_id 
					 from helpcso_user user
					 left join mst_level_user mst_level on user.level = mst_level.code_id 
					 left join helpcso_user_group user_group on user.group_id = user_group.id
					 inner join mst_status_active mst_active on user.status_active = mst_active.code_id
					 where user_name like '%" . str_replace(" ", "%", $text_search_user) . "%' 
					 group by user_name LIMIT 5";
		}
		$query = $this->db->query($sqlquery);
		
		if($flag_number == '1'){
			return $query->result();
		}
		elseif ($flag_number == '2'){
			return $query->num_rows();
		}
	}
	
	function add_user($user_id,$username,$password,$level,$user_group,$status_active)
	{
		$userid = $user_id + 1;
		$data = array(
					'user_id'=>$userid,
					'user_name'=>$username,
					'password'=> md5($password),
					'level'=> $level,
					'group_id'=> $user_group,
					'status_active'=> $status_active
					);
		$this->db->insert('helpcso_user',$data);
		
		$data_jumlah = array(
						'total_user'=>$userid
						);
		$this->db->update('mst_count_data',$data_jumlah);
	}
	
	function edit_user($userid,$username,$level,$user_group,$status_active)
	{	
		$data = array(
						'user_name'=>$username,
						'level'=>$level,
						'group_id'=>$user_group,
						'status_active'=> $status_active
						);
		$this->db->where('user_id',$userid);
		$this->db->update('helpcso_user',$data);
	}
	
	function change_password($userid,$new_password)
	{	
		$data_password = array(
						'password'=>md5($new_password)
						);
		$this->db->where('user_id',$userid);
		$this->db->update('helpcso_user',$data_password);
	}
	
	function delete_user($userid)
	{
		$data = array(
						'status_active'=> 2
						);
		$this->db->where('user_id',$userid);
		$this->db->update('helpcso_user',$data);
	}
	// user group
	function get_all_user_group($flag_number)
	{
		$sqlquery = "Select 
						usergroup.id as group_id,
						usergroup.group_name as group_name,
						usergroup.status_active as status_active,
						msa.status_active as status_active_name
					 from helpcso_user_group usergroup
					 inner join mst_status_active msa on usergroup.status_active = msa.code_id ";
		$query = $this->db->query($sqlquery);
		if($flag_number == '1'){
			return $query->result();
		}
		elseif ($flag_number == '2'){
			return $query->num_rows();
		}
	}
	
	function get_user_group($flag_number)
	{
		$sqlquery = "Select 
						id as group_id,
						group_name,
						status_active
					 from helpcso_user_group 
					 where status_active = '1'";
		$query = $this->db->query($sqlquery);
		if($flag_number == '1'){
			return $query->result();
		}
		elseif ($flag_number == '2'){
			return $query->num_rows();
		}
	}
	function add_usergroup($groupid,$groupname,$status_active)
	{
		$groupid = $groupid + 1;
		
		$data = array(
					'id'=>$groupid,
					'group_name'=>$groupname,
					'status_active'=> $status_active
					);
		$this->db->insert('helpcso_user_group',$data);
		
		$data_jumlah = array(
						'total_user_group'=>$groupid
						);
		$this->db->update('mst_count_data',$data_jumlah);
		
	}
	
	function edit_usergroup($groupid,$groupname,$status_active)
	{	
		$data = array(
						'group_name'=>$groupname,
						'status_active'=> $status_active
						);
		$this->db->where('id',$groupid);
		$this->db->update('helpcso_user_group',$data);
	}
	
	function delete_usergroup($groupid)
	{
		$data = array(
						'status_active'=> 2
						);
		$this->db->where('id',$groupid);
		$this->db->update('helpcso_user_group',$data);
	}
	
	function search_usergroup($flag_query,$flag_number,$text_search_group)
	{
		if ($flag_query == 1){
		$sqlquery = "Select 
						id as group_id,
						group_name,
						status_active
					 from helpcso_user_group 
					 where status_active = '1' and group_name like '%" . str_replace(" ", "%", $text_search_group) . "%'";
		}
		else if ($flag_query == 2){
		$sqlquery = "Select 
						id as group_id,
						group_name,
						status_active
					 from helpcso_user_group 
					 where status_active = '1' and group_name like '%" . str_replace(" ", "%", $text_search_group) . "%' 
					 group by group_name LIMIT 5";
		}
		$query = $this->db->query($sqlquery);
		
		if($flag_number == '1'){
			return $query->result();
		}
		elseif ($flag_number == '2'){
			return $query->num_rows();
		}
	}	
	// MD01
	function get_all_user_group_field()
	{
		$sqlquery = "Select 
						user_group_field.usergroup_field_id as group_field_id,
						user_group_field.id as id,
						user_group_field.queue_number as queue_number,
						user_group_field.planned_start_date as planned_start_date,
						user_group.group_name as group_name
					 from helpcso_user_group_field user_group_field
					 inner join helpcso_user_group user_group on user_group.id=user_group_field.id
					 where user_group.status_active = 1";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function count_user_group_field()
	{
		$sqlquery = "Select
           				count(user_group_field.usergroup_field_id) as id
					 from helpcso_user_group_field user_group_field
					 inner join helpcso_user_group user_group on user_group.id=user_group_field.id
					 where user_group.status_active = 1";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
   		return $result[0]->id;
	}
	function update_user_group_field($id,$data_groupfield){
		$this->db->where('id', $id);
		$this->db->update('helpcso_user_group_field', $data_groupfield); 
	}
	// MD01
}
?>