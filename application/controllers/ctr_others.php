<?php
class Ctr_others extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model("mdl_others");
		}
	function ticket_toexcel(){
		$data['hasil'] = $this->mdl_others->ticket_detail();
		$this->load->view('admin/view_ticket_report_toexcel', $data);
		}
	}
?>