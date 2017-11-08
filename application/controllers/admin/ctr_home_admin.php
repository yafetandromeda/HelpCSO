<!--M002-->
<?php ob_start(); ?>
<?php
class Ctr_home_admin extends CI_Controller {
		function __construct(){
			parent::__construct();
			$this->load->model('admin/mdl_home_admin');
			$this->load->library('cso_template', array("templateFile" => "admin/admin_template"));
		}
		function index() {
			$data['count_unread_script']=$this->mdl_home_admin->get_count_unread_report()->row('count_reported_script');
			$data['count_unread_request']=$this->mdl_home_admin->get_count_unread_request();
			$this->cso_template->view('admin/home_admin',$data);
		}
		//M002
		function script(){
			$this->cso_template->view("admin/manage_wording_script");
		}
		//M002
		
		function setting(){
			$this->cso_template->view("admin/menu_setting");
		}
		function reports(){
			$this->cso_template->view("admin/menu_reports");
		}
		function confirm_logout()
		{
			$this->cso_template->view("admin/home_admin");
		}
}
?>		
