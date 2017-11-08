<?php
class Ctr_helpcso_wording extends CI_Controller {
	var $current_greetings, $current_reconfirmation, $current_closing;
	function __construct(){
		parent::__construct();
		$this->load->model("mdl_wording");
		$this->load->library('cso_template',array("templateFile"=>"admin/admin_template"));
		$this->current_greetings = "";
		$this->current_reconfirmation = "";
		$this->current_closing = "";
		$this->current_wording = array(
			"greetings" => "",
			"reconfirmation" => "",
			"closing" => ""
			);
	}
	function index(){
		$this->load_wording();
	}
	function announcement(){
		$data['announcement'] = $this->mdl_wording->get_wording('announcement', 3);
		$wording_record = $this->mdl_wording->get_wording('announcement', 1);
		if (isset($wording_record[0])){
			$data['current_announcement'] = $wording_record[0]->wording_content;
		}
		else $data['current_announcement'] = "<i>No Announcement</i>";
		$this->cso_template->view("admin/manage_wording_announcement", $data);
		}
	function general_script($type = ""){
		if ($type == ""){
			$this->cso_template->view("admin/manage_wording_general", array());
			}
		else if ($type == "greetings" || $type == "reconfirmation" || $type == "closing"){
			$wording_record = $this->mdl_wording->get_wording($type, 1);
			if (isset($wording_record[0])){
				$data[$type] = $wording_record[0]->wording_content;
				$this->current_wording[$type] = $wording_record[0]->wording_content;
			}
			else $data[$type] = "<i>No " . $type . "</i>";
			
			$this->cso_template->view("admin/manage_wording_" . $type, $data);
			}
		}
	function load_wording(){
		$data['announcement'] = $this->mdl_wording->get_wording('announcement', 3);
		
		$wording_record = $this->mdl_wording->get_wording('announcement', 1);
		if (isset($wording_record[0])){
			$data['current_announcement'] = $wording_record[0]->wording_content;
		}
		else $data['current_announcement'] = "<i>No Announcement</i>";
		
		$wording_record = $this->mdl_wording->get_wording('greetings', 1);
		if (isset($wording_record[0])){
			$data['greetings'] = $wording_record[0]->wording_content;
			$this->current_greetings = $wording_record[0]->wording_content;
		}
		else $data['greetings'] = "<i>No Greetings</i>";
		
		$wording_record = $this->mdl_wording->get_wording('reconfirmation', 1);
		if (isset($wording_record[0])){
			$data['reconfirmation'] = $wording_record[0]->wording_content;
			$this->current_reconfirmation = $wording_record[0]->wording_content;
		}
		else $data['reconfirmation'] = "<i>No Reconfirmation</i>";
		
		$wording_record = $this->mdl_wording->get_wording('closing', 1);
		if (isset($wording_record[0])){
			$data['closing'] = $wording_record[0]->wording_content;
			$this->current_closing = "<i>No Closing</i>";
		}
		
		$this->cso_template->view("admin/manage_wording", $data);
	}
	function save_wording(){
		$announcement = $_POST['announcement'];
		$greetings = $_POST['greetings'];
		$reconfirmation = $_POST['reconfirmation'];
		$closing = $_POST['closing'];
		
		$user_id = $_POST['user_id'];
		
		// current wording
		$wording_record = $this->mdl_wording->get_wording('announcement', 1);
		if (isset($wording_record[0])){
			$this->current_announcement = $wording_record[0]->wording_content;
		}

		$wording_record = $this->mdl_wording->get_wording('greetings', 1);
		if (isset($wording_record[0])){
			$this->current_greetings = $wording_record[0]->wording_content;
		}

		$wording_record = $this->mdl_wording->get_wording('reconfirmation', 1);
		if (isset($wording_record[0])){
			$this->current_reconfirmation = $wording_record[0]->wording_content;
		}

		$wording_record = $this->mdl_wording->get_wording('closing', 1);
		if (isset($wording_record[0])){
			$this->current_closing = $wording_record[0]->wording_content;
		}
		if ($announcement != NULL && $announcement != "" && $announcement != $this->current_announcement)			
			$this->mdl_wording->add_wording("announcement", $announcement, $user_id);
		if ($greetings != NULL && $greetings != "" && $greetings != $this->current_greetings)			
			$this->mdl_wording->add_wording("greetings", $greetings, $user_id);
		if ($reconfirmation != NULL && $reconfirmation != "" && $reconfirmation != $this->current_reconfirmation)		
			$this->mdl_wording->add_wording("reconfirmation", $reconfirmation, $user_id);
		if ($closing != NULL && $closing != "" && $closing != $this->current_closing)			
			$this->mdl_wording->add_wording("closing", $closing, $user_id);
	}
}
?>