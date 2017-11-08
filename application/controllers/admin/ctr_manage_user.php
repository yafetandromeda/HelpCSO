<?php
// MD01 - YA - Advance user profile, untuk mengatur field yang muncul hanya di user tertentu saja, atau hal – hal yang bisa dijadikan default terhadap user tersebut
class Ctr_manage_user extends CI_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('admin/mdl_manage_user');
		$this->load->library("cso_template", array("templateFile" => "admin/admin_template"));
	}
	
	function index() {
	
		$data['data_user']=$this->mdl_manage_user->get_all_user();
		$data['user_id'] =$this->mdl_manage_user->get_last_userid()->row('total_user') + 1;
		$data['pil_level'] =$this->mdl_manage_user->get_pil_level();
		$data['pil_group'] =$this->mdl_manage_user->get_pil_group();
		$data['pil_active'] =$this->mdl_manage_user->get_pil_status_active();
		$this->cso_template->view('admin/manage_user',$data);
	}
	
	function add_user() {
			$user_id =$this->mdl_manage_user->get_last_userid()->row('total_user');
			$username=$_POST['text-username'];
			$password=$_POST['text-password'];
			$level=$_POST['level'];
			$user_group=$_POST['user_group'];
			$status_active=$_POST['status_active'];
			$this->mdl_manage_user->add_user($user_id,$username,$password,$level,$user_group,$status_active);
	}
	
	function change_password() 
	{	
		$data['message']='';
		$this->cso_template->view('change_password',$data);
	}
	
	function cek_password() {
			$userid=$this->session->userdata('session_user_id');
			$current_password=$this->mdl_manage_user->get_current_password($userid)->row('password');
            $old_password=md5($this->input->post('old-password'));
         
            if ($old_password != $current_password){
                redirect('/admin/ctr_manage_user/error_change_password?flag=0');
            }
            else {	
				$new_password=$this->input->post('new-password');
				$this->mdl_manage_user->change_password($userid,$new_password);
				redirect('/admin/ctr_manage_user/error_change_password?flag=1');
            }
	}
	
	function error_change_password(){
			$flag = $_GET['flag'];
			if ($flag == 0){
            	$data['message']='Old Password Invalid!';
			}else{
				$data['message']='Change Password Successful!';
           	}
			$user_level = $this->session->userdata("session_level");
			if ($user_level == 1){
				$templateFile = "admin/admin_template";
			    $this->cso_template->view('change_password',$data);
			}
			else if ($user_level == 2 || $user_level == 3){
				echo $data['message'];
			}
    }
	
	function edit_user() 
	{	
			$userid = $_POST['user_id'];
			$username=$_POST['text-username_edit'];
			$level=$_POST['level_edit'];
			$user_group=$_POST['user_group_edit'];
			$status_active=$_POST['status_active_edit'];
			$this->mdl_manage_user->edit_user($userid,$username,$level,$user_group,$status_active);
		}
	
	
	function delete_user() 
	{	
		$userid = $_GET['user_id'];
		$this->mdl_manage_user->delete_user($userid);
		redirect('admin/ctr_manage_user/index');
	}
	
	function search_user()
	{
		$text_search_user=$_GET['text_search_user'];
		$data['data_user'] = $this->mdl_manage_user->search_user(1,1,$text_search_user); 
		$data['user_id'] =$this->mdl_manage_user->get_last_userid()->row('total_user') + 1;
		$data['pil_level'] =$this->mdl_manage_user->get_pil_level();
		$data['pil_active'] =$this->mdl_manage_user->get_pil_status_active();
		$this->cso_template->view('admin/manage_user',$data);
	  }	  
	  
	  function search_user_suggestion()
	{
		$text_search_suggestion=$_GET['text_search_suggestion'];
		$data_user_suggestion = $this->mdl_manage_user->search_user(2,1,$text_search_suggestion); 
		$jumlah_data_suggestion =$this->mdl_manage_user->search_user(2,1,$text_search_suggestion);
		if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
			echo " ";
		}
		else {	
			$i = 0;
		 foreach ($data_user_suggestion as $p):
			 $i++;
			 echo "<li id='li".$i."' onClick='chosenText".$i."()'>" .$p->user_name. "</li>";
		  endforeach;
		 }
	  }
	  function back_to_home(){
	  		$level=$this->session->userdata('session_level');
	  		if ($level=='1'){
				redirect('/admin/ctr_home_admin');
			}
			elseif ($level=='2'){
                    redirect('/user/ctr_home_user/');
            }
	 }
	 
	 
	 // user group
	 
	function index_usergroup() {
	
		$data['data_group']=$this->mdl_manage_user->get_all_user_group(1);
		$data['group_id'] =$this->mdl_manage_user->get_last_usergroupid()->row('total_user_group') + 1;
		$data['pil_active'] =$this->mdl_manage_user->get_pil_status_active();
		$this->cso_template->view('admin/manage_user_group',$data);
	}

	 function usergroup_fields(){
		$level = $_GET['level'];
		$user_group = $this->mdl_manage_user->get_user_group(1);
		if ($level == 3){
			echo "<label for='usergroup' class='cso-form-label'>User Group</label>";
			echo "<select id='usergroup' name='usergroup'>";
			echo "<option value='0'>--choose--</option>";
				foreach ($user_group as $p):
						echo "<option value='".$p->group_id."'>".$p->group_name."</option>";
				endforeach;
			echo "</select>";
		}
		else echo " ";
	 }
	 
	 function usergroup_fields_edit(){
		$level = $_GET['level'];
		$user_group = $this->mdl_manage_user->get_user_group(1);
		if ($level == 3){
			echo "<label for='usergroup_edit' class='cso-form-label'>User Group</label>";
			echo "<select id='usergroup_edit' name='usergroup_edit'>";
			echo "<option value='0'>--choose--</option>";
				foreach ($user_group as $p):
						echo "<option value='".$p->group_id."'>".$p->group_name."</option>";
				endforeach;
			echo "</select>";
		}
		else echo " ";
	 }
	
	function add_usergroup() {
			$groupid=$this->mdl_manage_user->get_last_usergroupid()->row('total_user_group') ;
			$groupname=$_POST['text-groupname'];
			$status_active=$_POST['status_active'];
			$this->mdl_manage_user->add_usergroup($groupid,$groupname,$status_active);
	}
	
	function edit_usergroup() 
	{	
			$groupid = $_POST['text-groupID_edit'];
			$groupname=$_POST['text-groupname_edit'];
			$status_active=$_POST['status_active_edit'];
			$this->mdl_manage_user->edit_usergroup($groupid,$groupname,$status_active);
	}
	
	function delete_usergroup() 
	{	
		$groupid = $_GET['groupid'];
		$this->mdl_manage_user->delete_usergroup($groupid);
		redirect('admin/ctr_manage_user/index_usergroup');
	}
	
	function search_group()
	{
		$text_search_group=$_GET['text_search_group'];
		$data['data_group'] = $this->mdl_manage_user->search_usergroup(1,1,$text_search_group); 
		$data['group_id'] =$this->mdl_manage_user->get_last_usergroupid()->row('total_user_group') + 1;
		$data['pil_active'] =$this->mdl_manage_user->get_pil_status_active();
		$this->cso_template->view('admin/manage_user_group',$data);
	  }	  
	  
	  function search_group_suggestion()
	{
		$text_search_suggestion=$_GET['text_search_suggestion'];
		$data_group_suggestion = $this->mdl_manage_user->search_usergroup(2,1,$text_search_suggestion); 
		$jumlah_data_suggestion =$this->mdl_manage_user->search_usergroup(2,1,$text_search_suggestion);
		if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
			echo " ";
		}
		else {	
			$i = 0;
		 foreach ($data_group_suggestion as $p):
			 $i++;
			 echo "<li id='li".$i."' onClick='chosenText".$i."()'>" .$p->group_name. "</li>";
		  endforeach;
		 }
	  }
// MD01
	function index_usergroup_field() {
	
		$data['data_group']=$this->mdl_manage_user->get_all_user_group_field();
		$this->cso_template->view('admin/manage_usergroup_field',$data);
	}
	function update_user_group_field(){	
		$count_data = $this->mdl_manage_user->count_user_group_field();
		$data_groupfield = Array();
		$idx = 0;
		foreach ($this->mdl_manage_user->get_all_user_group_field() as $p):
			$id = $p->id;
			if (!isset($_POST['queue_number'][$idx])){
				$data_groupfield['queue_number'] = 0;
			} else{
				$data_groupfield['queue_number'] = 1;
			}
			if (!isset($_POST['planned_start_date'][$idx])){
				$data_groupfield['planned_start_date'] = 0;
			} else{
				$data_groupfield['planned_start_date'] = 1;
			}
			$this->mdl_manage_user->update_user_group_field($id,$data_groupfield);
			$idx++;
		endforeach;
		redirect("admin/ctr_manage_user/index_usergroup_field");
	}
// MD01 
}
?>