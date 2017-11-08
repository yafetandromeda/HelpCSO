<?php
class Ctr_change_password extends CI_Controller {
		function __construct(){
			parent::__construct();
			$this->load->model('admin/mdl_manage_user');
			$this->load->library('cso_template');
		}
		function index()
		{
			$userid = $this->session->userdata('user_id');
			$data['current_password']=$this->mdl_manage_user->get_current_password($userid)->row('password');
			$this->cso_template->view('change_password',$data,$_GET['template']);
		}
		function change_password() 
		{
			$password=$this->input->post('text-password');
			$this->mdl_manage_user->change_password($password);
		}
}
?>