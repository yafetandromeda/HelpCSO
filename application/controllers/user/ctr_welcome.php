
<?php
// M024 - YA - Notification Recovering Ticket
class Ctr_welcome extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('cso_template', array('templateFile' => 'user/user_template'));
		$this->load->model("user/mdl_interaction");
		$this->load->model("user/mdl_ticket");
	}
	function index(){
		$userid = $this->session->userdata('session_user_id');
		// if (isset($userid) && $userid != "" && $userid != 0){
			$data['num_interaction'] = $this->mdl_interaction->num_by_creator_id($userid);
			$data['num_ticket'] = $this->mdl_ticket->num_by_owner_id($userid);
			$data['num_ticket_overdue'] = $this->mdl_ticket->num_by_owner_id($userid, true);
			
			$data['num_interaction_open'] = $this->mdl_interaction->notif($userid, "DRAFT");
			$data['num_interaction_inprogress'] = $this->mdl_interaction->notif($userid, "INPROGRESS");
			$data['num_interaction_scheduled'] = $this->mdl_interaction->notif($userid, "SCHEDULED");
			$data['num_interaction_over'] = $this->mdl_interaction->notif($userid, "OVER");
			
			$data['num_ticket_draft'] = $this->mdl_ticket->notif($userid, "DRAFT");
			$data['num_ticket_draftover'] = $this->mdl_ticket->notif($userid, "DRAFTOVER");
			$data['num_ticket_open'] = $this->mdl_ticket->notif($userid, "OPEN");
			$data['num_ticket_openhigh'] = $this->mdl_ticket->notif($userid, "OPENHIGH");
			$data['num_ticket_openover'] = $this->mdl_ticket->notif($userid, "OPENOVER");
			$data['num_ticket_inprogress'] = $this->mdl_ticket->notif($userid, "INPROGRESS");
			$data['num_ticket_inprogresshigh'] = $this->mdl_ticket->notif($userid, "INPROGRESSHIGH");
			$data['num_ticket_inprogressover'] = $this->mdl_ticket->notif($userid, "INPROGRESSOVER");
			$data['num_ticket_over'] = $this->mdl_ticket->notif($userid, "OVERSLA");
			
			$data['num_ticket_group'] = $this->mdl_ticket->notif($userid, "GROUP");
			// M024
			$data['num_ticket_recovering'] = $this->mdl_ticket->notif($userid, "RECOVERING");
			// M024

			$this->cso_template->view('user/user_welcome', $data);
		// }
		// else {
		// 	header("Location: " . base_url());
		// }
	}
}
?>