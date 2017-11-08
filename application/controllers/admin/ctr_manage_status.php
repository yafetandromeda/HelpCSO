<?php
class Ctr_manage_status extends CI_Controller 
{
		function __construct(){
			parent::__construct();
			$this->load->model('admin/mdl_manage_status');
			$this->load->library("cso_template", array("templateFile" => "admin/admin_template"));
		}
		
		 function index_status() {
		
			$data['data_status']=$this->mdl_manage_status->get_all_status();
			$data['status_id'] =$this->mdl_manage_status->get_last_status_id() + 1;
			$data['pil_active'] =$this->mdl_manage_status->get_pil_status_active();
			$this->cso_template->view('admin/manage_status',$data);
		}
	
		
		function add_status() {
				$status_id=$_POST['text-statusID'];;
				$status_name=$_POST['text-statusname'];
				$status_type=$_POST['status_type'];
				$status_active=$_POST['status_active'];
				$status_primary=$_POST['status_primary'];
				if ($status_type == 'i') $status_color =  '#FCF8E3';
				else $status_color =  '#F0F0F0';
				$this->mdl_manage_status->add_status($status_id,$status_name,$status_type,$status_color,$status_active,$status_primary);
		}
		
		function edit_status() 
		{	
				$status_id=$_POST['text-statusID_edit'];;
				$status_name=$_POST['text-statusname_edit'];
				$status_type=$_POST['status_type_edit'];
				$status_active=$_POST['status_active_edit'];
				$status_primary=$_POST['status_primary_edit'];
				if ($status_type == 'i') $status_color =  '#FCF8E3';
				else $status_color =  '#F0F0F0';
				$this->mdl_manage_status->edit_status($status_id,$status_name,$status_type,$status_color,$status_active,$status_primary);
		}
		
		//substatus	
		function index_substatus() {
		
			$data['data_substatus']=$this->mdl_manage_status->get_all_substatus();
			$data['substatus_id'] =$this->mdl_manage_status->get_last_status_id() + 1;
			$data['pil_active'] =$this->mdl_manage_status->get_pil_status_active();
			$this->cso_template->view('admin/manage_substatus',$data);
		}
	
		
		function add_substatus() {
				$substatus_id=$_POST['text-substatusID'];;
				$substatus_name=$_POST['text-substatusname'];
				$substatus_type=$_POST['substatus_type'];
				$substatus_active=$_POST['substatus_active'];
				if ($substatus_type == 'i') $substatus_color =  '#FCF8E3';
				else $substatus_color =  '#F0F0F0';
				$this->mdl_manage_status->add_substatus($substatus_id,$substatus_name,$substatus_type,$substatus_color,$substatus_active);
		}
		
		function edit_substatus() 
		{	
				$substatus_id=$_POST['text-substatusID_edit'];;
				$substatus_name=$_POST['text-substatusname_edit'];
				$substatus_type=$_POST['substatus_type_edit'];
				$substatus_active=$_POST['substatus_active_edit'];
				if ($substatus_type == 'i') $substatus_color =  '#FCF8E3';
				else $substatus_color =  '#F0F0F0';
				$this->mdl_manage_status->edit_substatus($substatus_id,$substatus_name,$substatus_type,$substatus_color,$substatus_active);
		}
}
?>