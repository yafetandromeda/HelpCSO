<?php
class Ctr_helpcso_escalation extends CI_Controller {
	var $ticket_status;
	function __construct(){
		parent::__construct();
		$this->load->library("cso_template", array("templateFile" => "escalation/esc_template"));
		$this->load->model("mdl_ticket");
		$this->ticket_status = array(
			"new" => 1,
			"open" => 2,
			"handled" => 3,
			"solved" => 4
		);
	}
	
	/* esc */
	function index($startDate = "", $endDate = "", $status = "", $priority = "", $category = ""){
		if ($startDate == "" && $endDate == "")
			$data['tickets'] = $this->mdl_ticket->get($category, $status, $priority);
		else $data['tickets'] = $this->mdl_ticket->get_by_date($startDate, $endDate, $category, $status, $priority);
		$data['startDate'] = $startDate;
		$data['endDate'] = $endDate;	
		$data['ticketCategory'] = $this->mdl_ticket->category_get_all();	
		$data['ticketPriority'] = $this->mdl_ticket->priority_get_all(false);
		$data['ticketStatus'] = $this->mdl_ticket->status_get_all();
		$this->cso_template->view("escalation/esc_home", $data);
	}
	function detail_ticket($ticket_id){
		$data['ticket'] = $this->mdl_ticket->detail($ticket_id);
		$data['ticket_content'] = $this->mdl_ticket->getFieldData($ticket_id);
		$this->cso_template->view('escalation/esc_ticket', $data);
	}
	function handle_ticket(){
		$ticket_id = $_POST['ticket_id'];
		$data['esc_id'] = $_POST['esc_id'];
		$data['ticket_status'] = $this->ticket_status['handled'];
		$data['handled_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
		$this->mdl_ticket->update($ticket_id, $data);
	}
	function unhandle_ticket(){
		$ticket_id = $_POST['ticket_id'];
		$data['ticket_status'] = $this->ticket_status['new'];
		$this->mdl_ticket->update($ticket_id, $data);
	}
	function solve_ticket(){
		$ticket_id = $_POST['ticket_id'];
		$data['esc_id'] = $_POST['esc_id'];
		$data['ticket_status'] = $this->ticket_status['solved'];
		$data['ticket_response'] = mysql_real_escape_string( $_POST['ticket_response'] );
		$data['solved_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
		$this->mdl_ticket->update($ticket_id, $data);
	}
	/* cso */
	function create_ticket(){
		$data['notifMessage'] = "";
		$data['notifType'] = "";
		if (isset($_GET['notif'])){
			if ($_GET['notif'] == "success"){
				$data['notifMessage'] = "Your ticket has been submitted successfully";
				$data['notifType'] = "success";
				}
		}
		$this->load->view("user/cso_create_ticket", $data);
	}
	function submit_ticket(){
		$data['cat_id'] = $_POST['ticket_category'];
		$data['ticket_content'] = $_POST['txt-ticket'];
		$data['cso_id'] = $this->session->userdata('session_user_id');
		$data['ticket_priority'] = $_POST['ticket_priority'];
		$data['trxIDEmail'] = $_POST['trxIDEmail'];
		if (isset($_POST['btnClosedTicket']) && $_POST['btnClosedTicket'] == "btnClosedTicket"){
			$ticketStatus = 5;
		}
		else $ticketStatus = 1;
		$ticket_id = $this->mdl_ticket->add($data, $ticketStatus);
		
		foreach ($_POST['Field'] as $fieldID => $fieldContent){
			$field_data['ticket_id'] = $ticket_id;
			$field_data['fieldID'] = $fieldID;
			$field_data['fieldContent'] = $fieldContent;
			$this->mdl_ticket->addFieldData($field_data);
		}
		header("Location: " . base_url() . "index.php/ctr_helpcso_escalation/create_ticket?notif=success");
	}
	function view_tickets($userid, $startDate = '', $endDate = ''){
		$data['tickets'] = $this->mdl_ticket->get_for_cso($startDate, $endDate);
		$this->load->view("user/cso_my_tickets", $data);
	}
	function ajax_cso_detail_ticket(){
		$ticket_id = $_POST['ticket_id'];
		$result = $this->mdl_ticket->getFieldData($ticket_id);
		$str = "";
		foreach($result as $record){
			$str .= "<div class='cso-detail-ticket-item'><div class='cso-detail-ticket-field'>" . $record->fieldName . ": </div><div class='cso-detail-ticket-content'>" . $record->fieldContent . "</div></div>";
		}
		echo $str;
	}
	function ajax_fields(){
		$catid = $_POST['cat_id'];
		$str = "";
		$fields = $this->mdl_ticket->fields_get_all($catid);
		$asterisk = "";
		$required = "";
		foreach ($fields as $f){
			if ($f->fieldMandatory == '1'){
				$asterisk = "*";
				$required = "required";
			}
			else {
				$asterisk = "";
				$required = "";
				}
			$str .= "<label for='Field[" . $f->fieldID . "]'>" . $f->fieldName . " " . $asterisk . "</label>";
			$str .= "<input type='text' name='Field[" . $f->fieldID . "]' id='Field[" . $f->fieldID . "]' " . $required . ">";
		}
		echo $str;
	}
	
	//escalation categories
	function add_new_category(){
		$this->mdl_ticket->category_add(array(
			"level" => $_POST['level'],
			"parent_id" => $_POST['par_category'],
			"catname" => $_POST['catname'],
			"category_code" => $_POST['category_code']
		));
	}
	function edit_category(){
		$this->mdl_ticket->category_update($_POST['catid'], array(
			"level" => $_POST['level'],
			"parent_id" => $_POST['par_category'],
			"catname" => $_POST['catname'],
			"category_code" => $_POST['category_code']
		));
	}
	function delete_category(){
		$this->mdl_ticket->category_delete($_POST['cat_id']);
	}
	function manage_categories(){
		$data['list_categories'] = $this->mdl_ticket->category_get_all();
		$data['category_id'] =$this->mdl_ticket->category_last_id() + 1;
		$data['pil_levelcategory'] =$this->mdl_ticket->pil_level_escalation_category();
		$this->cso_template->view("admin/manage_escalation_categories", $data, "admin/admin_template");
	}
	
	function check_child_category(){		
			$child_category_name = $this->mdl_ticket->check_child_category(1,$_GET['catid'])->row('catname');
			$flag_check_child_category = $this->mdl_ticket->check_child_category(2,$_GET['catid']);
			if ($flag_check_child_category > 0) {
				echo "<input type='hidden' id='child_category_name' value='".$child_category_name."'/>";
				echo "<input type='hidden' id='flag_check_child_category' value='".$flag_check_child_category."'/>"; 
			}
			else
				echo "<input type='hidden' id='flag_check_child_category' value='".$flag_check_child_category."'/>"; 
		}
		
	function parent_category(){
			$level = $_GET['level'];
			$cat_id = $_GET['cat_id'];
			if ($level == 2){
				$par_category = $this->mdl_ticket->par_category_get(1,$cat_id);
			}
			else if ($level == 3){
				$par_category = $this->mdl_ticket->par_category_get(2,$cat_id);	
			}
			
			if ($level == 1 or $level == ''){
				echo " ";
				echo "<input type='hidden' id='par_category' value=''/>";
			}
			else {
				echo "<label for='par_activity' class='cso-form-label'>Activity Parent</label>";
				echo "<select id='par_category' name='par_category'>";
				echo "<option value=''>--choose--</option>";
				foreach ($par_category as $p):
						echo "<option value='".$p->cat_id."'>".$p->catname."</option>";
				endforeach;
				echo "</select>";
			}
		}
		
		function parent_category_edit(){
			$level = $_GET['level'];
			$cat_id = $_GET['cat_id'];	
			$flag = $_GET['flag'];	
			
			if ($flag == 0) {
				echo " ";
				echo "<input type='hidden' id='par_category_edit' value=''/>";
			}
			else {
				if ($level == 2){
					$par_category = $this->mdl_ticket->par_category_get(1,$cat_id);
				}
				else if ($level == 3){
					$par_category = $this->mdl_ticket->par_category_get(2,$cat_id);
				}
				
				if ($level == 1 or $level == ''){
					echo " ";
					echo "<input type='hidden' id='par_category_edit' value=''/>";
				}
				else {
					echo "<label for='par_category_edit' class='cso-form-label'>Category Parent</label>";
					echo "<select id='par_category_edit' name='par_category_edit'>";
					echo "<option value=''>--choose--</option>";
					foreach ($par_category as $p):
									echo "<option value='".$p->cat_id."'>".$p->catname."</option>";
					endforeach;
					echo "</select>";	
				}
			}
		}

	function search_categories(){
		$text_search = $_GET['text_search'];
		$data['list_categories'] = $this->mdl_ticket->category_search(1,1,$text_search);
		$data['category_id'] =$this->mdl_ticket->category_last_id() + 1;
		$data['pil_levelcategory'] =$this->mdl_ticket->pil_level_escalation_category();
		$this->cso_template->view("admin/manage_escalation_categories", $data, "admin/admin_template");
	}
	
	 function search_category_suggestion()
	{
			$text_search_suggestion=$_GET['text_search_suggestion'];
			$data_category_suggestion = $this->mdl_ticket->category_search(2,1,$text_search_suggestion);
			$jumlah_data_suggestion = $this->mdl_ticket->category_search(2,2,$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 0;
			 foreach ($data_category_suggestion as $p):
				 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'><div style='cursor:pointer;'>" .$p->catname. "</div></li>";
			  endforeach;
		}
	}
		  
	function view_report(){
		$data['list_report_eskalasi'] = $this->mdl_ticket->get_ticket_report();
		$this->cso_template->view("admin/view_eskalasi_report", $data, "admin/admin_template");
	}
	
	function search_report(){
		$text_search = $_GET['text_search_report'];
		$data['list_report'] = $this->mdl_ticket->report_search(1,1,$text_search);
		$this->cso_template->view("admin/view_eskalasi_report", $data, "admin/admin_template");
	}
	
	 function search_report_suggestion()
	{		
			$text_search_suggestion=$_GET['text_search_suggestion'];
			$data_report_suggestion = $this->mdl_ticket->report_search(2,1,$text_search_suggestion);
			$jumlah_data_suggestion = $this->mdl_ticket->report_search(2,2,$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 0;
			 foreach ($data_report_suggestion as $p):
				 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'><div style='cursor:pointer;'>" .$p->cso_name. "</div></li>";
			  endforeach;
		}
	}
	function search_report_bydate(){
		$text_startDate = $_GET['text_startDate'];
		$text_endDate = $_GET['text_endDate'];
		$data['list_report_eskalasi'] = $this->mdl_ticket->report_search_bydate(1,$text_startDate,$text_endDate);
		$this->cso_template->view("admin/view_eskalasi_report", $data, "admin/admin_template");
	}
	
	//fields
	function manage_categories_fields(){
		$cat_id = $_GET['cat_id'];
		$data['list_fields'] = $this->mdl_ticket->fields_get_all($cat_id);
		$data['fields_id'] =$this->mdl_ticket->fields_last_id() + 1;
		$data['cat_id'] = $cat_id;
		$data['category'] = $this->mdl_ticket->fields_category($cat_id);
		$this->cso_template->view("admin/manage_escalation_categories_fields", $data, "admin/admin_template");
	}
		
	function add_categories_fields(){
		$cat_id = $_POST['cat_id'];
		$this->mdl_ticket->fields_add(array(
			"fieldName" => $_POST['field_name'],
			"fieldMandatory" =>  $_POST['field_mandatory']
		),$cat_id);
	}
	function edit_categories_fields(){
		$this->mdl_ticket->fields_update($_POST['field_id'], array(
			"fieldName" => $_POST['field_name'],
			"fieldMandatory" =>  $_POST['field_mandatory']
		));
	}
	function delete_categories_fields(){
		$cat_id = $_GET['cat_id'];
		$this->mdl_ticket->fields_delete($_GET['field_id']);
		redirect("ctr_helpcso_escalation/manage_categories_fields?cat_id=".$cat_id);
	}
	function search_fields(){
		$cat_id = $_GET['cat_id'];
		$text_search = $_GET['text_search'];
		$data['list_fields'] = $this->mdl_ticket->fields_search(1,1,$cat_id,$text_search);
		$data['fields_id'] =$this->mdl_ticket->fields_last_id() + 1;
		$data['cat_id'] = $cat_id;
		$data['category'] = $this->mdl_ticket->fields_category($cat_id);
		$this->cso_template->view("admin/manage_escalation_categories_fields", $data, "admin/admin_template");
	}
	
	 function search_fields_suggestion()
	{		
			$text_search_suggestion = $_GET['text_search_suggestion'];
			$data_fields_suggestion = $this->mdl_ticket->fields_search(2,1,$_GET['cat_id'],$text_search_suggestion);
			$jumlah_data_suggestion = $this->mdl_ticket->fields_search(2,2,$_GET['cat_id'],$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 0;
			 foreach ($data_fields_suggestion as $p):
				 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'><div style='cursor:pointer;'>" .$p->fieldName. "</div></li>";
			  endforeach;
		}
	}
}
?>