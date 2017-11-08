<?php
// M045 - YA - Ubah field
// MD01 - YA - Advance user profile, untuk mengatur field yang muncul hanya di user tertentu saja, atau hal – hal yang bisa dijadikan default terhadap user tersebut.
// MD02 - YA - Interaction status menggunakan model button, bukan combo box, tidak perlu tombol save &  System Autosave supaya jika pindah tab informasi sebelumnya tidak hilang
// MD03 - YA - add information pada ticket, Tambah button interaksi di activity plan untuk create new interaksi
// M55 - YA - penambahan SO number pada interaction dan ticket
class Ctr_interaction extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model('user/mdl_interaction');
		$this->load->model('user/mdl_activity');
		$this->load->model('user/mdl_attachment');		
		$this->load->model('user/mdl_interaction_activity');
		$this->load->library('cso_template', array('templateFile' => 'user/user_template'));
		$this->load->helper('download');
	}
	function index(){
		
	}
	function showlist($user_id = 0){
		$data['interaction_status'] = (isset($_GET['interaction_status'])) ? $_GET['interaction_status'] : "";
		$data['user_id'] = $user_id;
		$this->cso_template->view("user/user_interaction_list", $data);
	}
	// M045
	function ajaxlist($user_id = "0"){
		/* 
		 * Columns
		 */
		$aColumns = array(
			"code_interaction"
			, "creator_datetime"
			, "id_pesanan"
			, "customer_name"
			, "customer_email"
			, "customer_phone"
			, "user_name"
			, "interaction_type_name"
			, "case when status_id = 3 and planned_start_datetime < '" . date("Y-m-d H:i:s") .  "' then 'Over Schedule' else status_name end"
			, "interaction_id"
			);
	
		/* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' ){
			$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
				intval( $_GET['iDisplayLength'] );
		}
		
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) ){
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		/* 
		 * Filtering
		 */
		 $sWhere = "";		
		if ( $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ ){
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' ){
				if ( $sWhere == "" ){
					$sWhere = "WHERE ";
				}
				else{
					$sWhere .= " AND ";
				}
				if (strpos($aColumns[$i], " when ") === false){
					$sWhere .= "`".$aColumns[$i]."`LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				}
				else {
					$sWhere .= "".$aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				}
			}
		}
		if ($user_id != "" && $user_id != '0'){
			if ( $sWhere == "" ){
					$sWhere .= "WHERE ";
				}
			else $sWhere .= " AND ";
			$sWhere .= " creator_id = " . $user_id;
			$sWhere .= " AND ";
			$sWhere .= " interaction_status_id != 2 and interaction_status_id != 5";
		}

		
		$sEcho = $_GET['sEcho'];

		echo $this->mdl_interaction->get($aColumns, $sLimit, $sOrder, $sWhere, $sEcho);
	}
	// MD03
	function new_ticket_interaction($id = 0){
		$userid = $this->session->userdata('session_user_id');
		$ticket_id = isset($_GET['ticket_id']) ? $_GET['ticket_id'] : 0;
		$customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] : 0;
		$customer_phone = isset($_GET['customer_phone']) ? $_GET['customer_phone'] : 0;
		$id_pesanan = isset($_GET['id_pesanan']) ? $_GET['id_pesanan'] : 0;
		// M55
		$so_number = isset($_GET['so_number']) ? $_GET['so_number'] : 0;
		// M55
		$customer_email = isset($_GET['customer_email']) ? $_GET['customer_email'] : 0;
		
		$data = array();
		$data['interaction_type'] = $this->mdl_interaction->get_interaction_type();
		$data['interaction_status'] = $this->mdl_interaction->get_interaction_status();
		$data['interaction_priority'] = $this->mdl_interaction->get_interaction_priority();
		$data['user_group_field'] = $this->mdl_interaction->user_group_field($userid);
		$user_group_field = $data['user_group_field'];
		
		$now = date("ymd");
		$last_code_interaction = substr($this->mdl_interaction->get_last_code_interaction(),8,4);
		$next_code_interaction = $last_code_interaction + 1;
		$code_interaction = "IN".$now.sprintf("%04d", $next_code_interaction);
		if ($id == 0){
			// create new form
			$data['interaction_id'] = $this->mdl_interaction->get_last_id() + 1;
			$data['code_interaction'] = $code_interaction;
			$data['creator_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
			$data['creator_id'] = $this->session->userdata('session_user_id');
			
			$data['interaction_status_id'] = 1;
			$data['interaction_type_id'] = 1;
			$data['interaction_activity_solved'] = $this->mdl_interaction_activity->is_solved($data['interaction_id']);
			// M55			
			$this->mdl_interaction->add_ticket_interaction($data['interaction_id'], $data['creator_id'], $data['creator_datetime'], $ticket_id, $data['code_interaction'], $customer_name, $customer_phone, $customer_email, $id_pesanan, $so_number);
			// M55
		}
		redirect('user/ctr_interaction/form/'.$data['interaction_id']);
		$this->cso_template->view("user/user_interaction", $data);
	}
	// MD03
	// M045
	function form($id = 0){
		$userid = $this->session->userdata('session_user_id');
		$ticket_id = isset($_GET['ticket_id']) ? $_GET['ticket_id'] : 0;
		
		$data = array();
		$data['interaction_type'] = $this->mdl_interaction->get_interaction_type();
		$data['interaction_status'] = $this->mdl_interaction->get_interaction_status();
		$data['interaction_priority'] = $this->mdl_interaction->get_interaction_priority();
		$data['user_group_field'] = $this->mdl_interaction->user_group_field($userid);
		$user_group_field = $data['user_group_field'];
		
		$now = date("ymd");
		$last_code_interaction = substr($this->mdl_interaction->get_last_code_interaction(),8,4);
		$next_code_interaction = $last_code_interaction + 1;
		$code_interaction = "IN".$now.sprintf("%04d", $next_code_interaction);
		if ($id == 0){
			// create new form
			$data['interaction_id'] = $this->mdl_interaction->get_last_id() + 1;
			$data['code_interaction'] = $code_interaction;
			$data['creator_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
			$data['creator_id'] = $this->session->userdata('session_user_id');
			
			$data['interaction_status_id'] = 1;
			$data['interaction_type_id'] = 1;
			$data['interaction_activity_solved'] = $this->mdl_interaction_activity->is_solved($data['interaction_id']);
						
			$this->mdl_interaction->add($data['interaction_id'], $data['creator_id'], $data['creator_datetime'], $ticket_id, $data['code_interaction']);
		}
		else {
			$data['interaction_id'] = $id;
			$data['interaction_activity_solved'] = $this->mdl_interaction_activity->is_solved($data['interaction_id']);
			
			// select data from interaction where interaction_id = $id;
			$savedData = $this->mdl_interaction->get_by_id($id);
			foreach($savedData as $record){
				$data['creator_datetime'] = $record->creator_datetime;
				$data['creator_id'] = $record->creator_id;
				$data['creator_name'] = $record->creator_name;
				$data['interaction_type_id'] = $record->interaction_type_id;
				$data['customer_name'] = $record->customer_name;
				$data['customer_phone'] = $record->customer_phone;
				$data['customer_email'] = $record->customer_email;
				$data['queue_number'] = $record->queue_number;
				$data['id_pesanan'] = $record->id_pesanan;
				// M55
				$data['so_number'] = $record->so_number;
				// M55
				$data['priority_id'] = $record->priority_id;
				$data['interaction_description'] = $record->interaction_description;
				$data['interaction_status_id'] = $record->interaction_status_id;
				$data['planned_start_datetime'] = $record->planned_start_datetime;
				$data['actual_start_datetime'] = $record->actual_start_datetime;
				$data['actual_cancel_datetime'] = $record->actual_cancel_datetime;
				$data['actual_end_datetime'] = $record->actual_end_datetime;
				$data['code_interaction'] = $record->code_interaction;
			}
		}
		// MD03
		$interaction_id = $data['interaction_id'];
		$data['get_ticket_id'] = $this->mdl_interaction->get_ticket_id($interaction_id);
		// MD03
		$this->cso_template->view("user/user_interaction", $data);
	}
	// M045
	// MD02
	function form_action(){
		$interaction_id = $_POST['txt-interaction-id'];
		$code_interaction = $_POST['txt-interaction-code'];
		
		$interaction_status =  $_POST['cmb-interaction-status'];
		$data = array(
			  "interaction_type_id" => $_POST['cmb-interaction-type']
			, "customer_name" => $_POST['txt-customer-name']
			, "customer_phone" => $_POST['txt-phone-no']
			, "customer_email" => $_POST['txt-email']
			, "queue_number" => $_POST['txt-queue-no']
			, "priority_id" => $_POST['cmb-interaction-priority']
			, "interaction_description" => str_replace(array('cke_pastebin','style','absolute', 'px'), '', $_POST['txt-interaction-description'])
			//"interaction_description" => $_POST['txt-interaction-description']
			// , "interaction_status_id" => $interaction_status
			, "id_pesanan" => $_POST['txt-id-pesanan']
			// M55
			, "so_number" => $_POST['txt-so_number']
			// M55
			);
		
		// if ($_POST['planned-datetime'] != "" ){
		// 	$data['planned_start_datetime'] = date("Y-m-d H:i:s", strtotime($_REQUEST['planned-datetime']));			
		// }
// 		if ($interaction_status == 2){
// 			$data['actual_end_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
// 			}
// 		else if ($interaction_status == 4){
// 			$data['actual_start_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);			
// 			}
// 		else if ($interaction_status == 3){
// //			$data['planned_start_datetime'] = $_REQUEST['planned-datetime'];			
// 			}
// 		else if ($interaction_status == 5){
// 			$data['actual_cancel_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);			
// 			}

		if ($_POST['btn-scheduled']){
			$data['interaction_status_id'] = '3';		
		} else if ($_POST['btn-inprogress']){
			$data['interaction_status_id'] = '4';
			$data['actual_start_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);	
		} else if ($_POST['btn-canceled']){
			$data['interaction_status_id'] = '5';
			$data['actual_cancel_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);		
		} else if ($_POST['btn-closed']){
			$data['interaction_status_id'] = '2';
			$data['actual_end_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
		}
		$this->mdl_interaction->update($data, $interaction_id);
		redirect("user/ctr_interaction/form/" . $interaction_id);
	}
	// MD02
	function autosave_interaction_type(){
		$this->mdl_interaction->update_autosave($_POST['interaction_id'], array(
				"interaction_type_id" => $_POST['interaction_type_id']
		));
	}
	function autosave_interaction(){
		$this->mdl_interaction->update_autosave($_POST['interaction_id'], array(
				"interaction_type_id" => $_POST['interaction_type_id'],
				"customer_name" => $_POST['customer_name'],
				"customer_phone" => $_POST['customer_phone'],
				"customer_email" => $_POST['customer_email'],
				"queue_number" => $_POST['queue_number'],
				"id_pesanan" => $_POST['id_pesanan'],
				// M55
				"so_number" => $_POST['so_number'],
				// M55
				"priority_id" => $_POST['priority_id'],
				// "planned_start_datetime" => date("Y-m-d H:i:s", strtotime($_POST['planned_start_datetime'])),
				"interaction_description" => $_POST['interaction_description']
		));
	}
	function autosave_planned(){
		$this->mdl_interaction->update_autosave($_POST['interaction_id'], array(
				"planned_start_datetime" => date("Y-m-d H:i:s", strtotime($_POST['planned_start_datetime']))
		));
	}
	// MD02
	function attachments($interaction_id = 0, $message_type = "", $message = ""){
		$data['interaction_id'] = $interaction_id;
		$resultStatus = $this->mdl_interaction->get_by_id($interaction_id, true);
		$recordStatus = $resultStatus[0];
		$data['interaction_status_id'] = $recordStatus->interaction_status_id;
		$data['creator_id'] = $recordStatus->creator_id;
		$data['attachments'] = $this->mdl_attachment->get_by_interaction_id($interaction_id);
		$data['message_type'] = $message_type;
		$data['message'] = $message;
		if ($message == "" && isset($_REQUEST['message'])){
			$data['message'] = $_REQUEST['message'];
		}
		$this->cso_template->view("user/user_interaction_attachments", $data);	
	}
	function attachment_action($mode, $attachment_id = 0, $interaction_id = 0){
		if ($mode == "add"){
			$config['upload_path'] = "./attachment/interaction/";
			$config['allowed_types'] = "jpg|png|gif|bmp|doc|docx|xls|xlsx|ics|txt|pdf|msg";
			$config['max_size'] = "2048";			
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			
			$ticket_id = $_REQUEST['interaction_id'];			
			if (!$this->upload->do_upload('file-interaction-attachment')){
				header("Location: " . base_url() . "index.php/user/ctr_interaction/attachments/" . $_REQUEST['interaction_id'] . "/error?message=" . $this->upload->display_errors());
			}	
			else {
				$upload_data = $this->upload->data();	
				$data['attachment_name'] = $upload_data['full_path'];
				$data['attachment_note'] = $_REQUEST['txt-attachment-description'];
				$data['creator_id'] = $_REQUEST['creator_id'];
				$data['interaction_id'] = $_REQUEST['interaction_id'];
				$this->mdl_attachment->add($data);
				header("Location: " . base_url() . "index.php/user/ctr_interaction/attachments/" . $_REQUEST['interaction_id']);
			}
		}
		else if ($mode == "delete") {
			$this->mdl_attachment->delete($attachment_id);
			if ($interaction_id != 0){
				header("Location: " . base_url() . "index.php/user/ctr_interaction/attachments/" . $interaction_id);
			}
		}	
		else if ($mode == "download"){
			$result = $this->mdl_attachment->get_by_attachment_id($attachment_id);
			$record = $result[0];
			$data = file_get_contents($record->attachment_name);
			force_download($record->attachment_name, $data);
		}
	}
	function activities($interaction_id = 0){
		$data['interaction_id'] = $interaction_id;
		$data['activity_type'] = $this->mdl_activity->get_by_parent();
		$data['activities'] = $this->mdl_interaction_activity->get_by_interaction_id($interaction_id);
		$data['list_activities'] = $this->mdl_interaction->get_by_id($interaction_id);
		
		$resultStatus = $this->mdl_interaction->get_by_id($interaction_id, true);
		$recordStatus = $resultStatus[0];
		$data['interaction_status_id'] = $recordStatus->interaction_status_id;
		$data['creator_id'] = $recordStatus->creator_id;
		
		$this->cso_template->view("user/user_interaction_activities", $data);
	}
	function ajax_activity(){
		$str = "<option value='' activity-code='' disabled='disabled' selected>- Pilih -</option>";
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "word";
		$value = isset($_REQUEST['value']) ? $_REQUEST['value'] : "";
		if ($type == "word"){
			$result = $this->mdl_activity->get_by_keyword($value);
			$record = $result[0];
			$str = $record->activity_id 
				. "##" . $record->activity_code 
				. "##" . $record->activity_description;
		}
		else if ($type == "children"){
			$result = $this->mdl_activity->get_by_parent($value);
			foreach($result as $record){
				$str .= "<option value='" . $record->activity_id . "' activity-code='" . $record->activity_code . "'>" . $record->activity_description . "</option>";
			}
		}
		else if ($type == "definition"){
			$result = $this->mdl_activity->get_definition($value);
			$record = $result[0];
			$str = $record->activity_definition;
			}
		echo $str;
	}
	function activity_action($mode){	
		if ($mode == 'add'){
			$interaction_id = $_REQUEST['hdn-interaction-id'];
			$data['activity_id'] = $_POST['hdn-activity-id'];
			$data['interaction_id'] = $interaction_id;
			$this->mdl_interaction_activity->add($data);
		}
		else if ($mode == 'close'){
			$interaction_id = $_REQUEST['interaction_id'];		
			$data['interaction_activity_status'] = 2;
			$data['closed_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
			$this->mdl_interaction_activity->update($data, $_REQUEST['interaction_activity_id']);
		}
		else if ($mode == 'delete'){
			$interaction_id = $_REQUEST['interaction_id'];		
			$this->mdl_interaction_activity->delete($_REQUEST['interaction_activity_id']);
		}
		redirect("user/ctr_interaction/activities/" . $interaction_id);
	}
}
?>