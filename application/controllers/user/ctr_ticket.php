<?php
// M018 - YA - Menampilkan Solved dan Closed Datetime
// M019 - YA - Recover ticket
// M034 - YA - Menampilkan ticket template berdasarkan activity code
// M035 - YA - menampilkan hour berisi SLA
// M038 - YA - menyamakan customer name
// M039 - YA - menyamakan email & nomor
// M040 - YA - menampilkan recovering & success/failed datetime serta recover name
// M043 - YA - tambah note untuk solved dan closed ticket
// M045 - YA - Ubah field
// M046 - YA - Ubah tampilan baloon, hyperlink di showlist,  tambah status draft, tambah SLA
// MD03 - YA - add information pada ticket, Tambah button interaksi di activity plan untuk create new interaksi
// MD4 - YA - auto distribution ticket
// M55 - YA - penambahan SO number pada interaction dan ticket
// M58 - YA - Fitur cancelled untuk ticket junk/salah
// M63 - YA - Perubahan access & flow cancelled ticket
// M65 - YA - Related pada ticket ini by automatic fungsi ini akan mencocokan 3 field pada ticket Customer Name & Customer Phone & Customer Email digantikan dengan kombinasi ID Pesanan*(Prioritas) & SO Number & Customer Phone & Customer Email
class Ctr_ticket extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->model("user/mdl_ticket");
		$this->load->model("user/mdl_attachment");
		$this->load->model("user/mdl_user");
		$this->load->model("user/mdl_interaction");
		$this->load->model("user/mdl_activity");
		$this->load->model("user/mdl_ticket_log", 'ticketlog');
		$this->load->library('cso_template', array('templateFile' => 'user/user_template'));
		$this->load->helper('download');
	}
	function index(){
		
	}
	function showlist($userid = 0){
		$data['ticket_status'] = (isset($_GET['ticket_status'])) ? $_GET['ticket_status'] : "";
		$data['sla'] = (isset($_GET['sla'])) ? $_GET['sla'] : "";
		$data['ticket_priority'] = (isset($_GET['ticket_priority'])) ? $_GET['ticket_priority'] : "";
		$data['user_id'] = $userid;
		$data['user_type'] = "";
		$this->cso_template->view("user/user_ticket_list", $data);
	}

// M018 M045 M046
	function ajaxlist($user_id = "0"){
		$listtype = (isset($_GET['listtype'])) ? $_GET['listtype'] : "";
		$interaction_id = (isset($_GET['interaction_id'])) ? $_GET['interaction_id'] : "0";
		$activity_id = (isset($_GET['activity_id'])) ? $_GET['activity_id'] : "0";
		$ticket_template_id = (isset($_GET['ticket_template_id'])) ? $_GET['ticket_template_id'] : "0";
		$ticket_id = (isset($_GET['ticket_id'])) ? $_GET['ticket_id'] : "0";
		// M65
		$id_pesanan = (isset($_GET['id_pesanan'])) ? $_GET['id_pesanan'] : "0";
		$so_number = (isset($_GET['so_number'])) ? $_GET['so_number'] : "0";
		// M65
		$customer_name = (isset($_GET['customer_name'])) ? $_GET['customer_name'] : "";
		$customer_phone = (isset($_GET['customer_phone'])) ? $_GET['customer_phone'] : "";
		$customer_email = (isset($_GET['customer_email'])) ? $_GET['customer_email'] : "";
		
		/* 
		 * Columns
		 */
		$aColumns = array(
			"code_ticket"
			, "creator_datetime"
			, "id_pesanan"
			, "so_number"
			, "customer_name"
			, "customer_email"
			, "customer_phone"
			, "activity_code"
			, "activity_description"
			, "user_name"
			, "group_name"
			, "owner_name"
			, "case when creator_datetime < DATE_SUB(NOW(), INTERVAL 1 DAY) then 'Over SLA' else 'Within SLA (24)' end"
			, "priority_name"
			, "case when status_id = 7 and due_datetime < '" . date("Y-m-d H:i:s") .  "' then 'Over SLA' else status_name end"
			, "solved_datetime"
			, "closed_datetime"
			, "ticket_id"
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
			$sWhere .= " (creator_id = " . $user_id . " OR owner_id = " . $user_id . ")";
			$sWhere .= " AND ";
			$sWhere .= " (ticket_status != 8 and ticket_status != 9)";
		}
		
		if ($listtype != ""){
			if ($listtype == "related"){
				if ( $sWhere == "" ){
					$sWhere .= "WHERE ";
				}
				else $sWhere .= " AND ";
				$sWhere .= " `ticket_id` != " . $ticket_id;	
				
				$sExt = "";
				/*
				if ($activity_id != "0" && $activity_id != "") {
					$sExt .= "`helpcso_ticket`.`activity_id` = " . $activity_id;
				}
				*/
				// if ($customer_name != "" && $customer_name != "-" && $customer_name != "DUMMY" && $customer_name != " ") {
				// 	if ($sExt != "") $sExt .= " OR ";
				// 	$sExt .= "`customer_name` like '%" . $customer_name . "%'";
				// }
				// else {
				// 	if ($sExt != "") $sExt .= " OR ";
				// 	$sExt .= "(`customer_name` is null or `customer_name` = '')";
				// }
				// M65
				if ($id_pesanan != "" && $id_pesanan != "-") {
					if ($sExt != "") $sExt .= " OR ";
					$sExt .= "`id_pesanan` = '" . $id_pesanan . "'";
				}
				// else {
				// 	if ($sExt != "") $sExt .= " OR ";
				// 	$sExt .= "(`customer_name` is null or `id_pesanan` = '')";
				// }
				if ($so_number != "" && $so_number != "-") {
					if ($sExt != "") $sExt .= " OR ";
					$sExt .= "`so_number` = '" . $so_number . "'";
				}
				// else {
				// 	if ($sExt != "") $sExt .= " OR ";
				// 	$sExt .= "(`customer_name` is null or `so_number` = '')";
				// }
				// M65
				if ($customer_phone != "" && $customer_phone != "-"){
					if ($sExt != "") $sExt .= " OR ";
					$sExt .= "`customer_phone` like '" . $customer_phone . "'";
				}
				// else {
				// 	if ($sExt != "") $sExt .= " OR ";
				// 	$sExt .= "(`customer_name` is null or `customer_phone` = '')";
				// }
				if ($customer_email != "" && $customer_email != "-"){
					if ($sExt != "") $sExt .= " OR ";
					$sExt .= "`customer_email` like '" . $customer_email . "'";
				}
				// else {
				// 	if ($sExt != "") $sExt .= " OR ";
				// 	$sExt .= "(`customer_name` is null or `customer_email` = '')";
				// }
				if ($sExt != "") $sWhere .= " AND " . $sExt;
				/*
				if ($interaction_id != "0") {
					$sWhere .= " `interaction_id` = " . $interaction_id;
				}
				if ($ticket_template_id != "0") {
					$sWhere .= " `ticket_template_id` = " . $ticket_template_id;
				}
				*/	
			}
		}
		$sEcho = $_GET['sEcho'];

		echo $this->mdl_ticket->get($aColumns, $sLimit, $sOrder, $sWhere, $sEcho);
	}
	// M038 M039 M041 M045 M046
	function form($ticket_id = 0, $message_type = "", $message = ""){
// Detail Ticket
		// $interaction_id = isset($_GET['interaction_id']) ? $_GET['interaction_id'] : 0;
		$activity_id = isset($_GET['activity_id']) ? $_GET['activity_id'] : NULL;
		$intact_id = isset($_GET['intact_id']) ? $_GET['intact_id'] : NULL;
		$code_ticket = isset($_GET['code_ticket']) ? $_GET['code_ticket'] : NULL;
		// $customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] : NULL;
		
		$data = array();
		$activity_code_arr = $this->mdl_ticket->get_activity_code($ticket_id);
		if (count($activity_code_arr) > 0)
			$activity_code = $activity_code_arr[0]->activity_code;
		else $activity_code = "0";

		$data['ticket_status'] = $this->mdl_ticket->get_ticket_status();
		$data['ticket_substatus'] = $this->mdl_ticket->get_ticket_substatus();
		$data['ticket_priority'] = $this->mdl_ticket->get_ticket_priority();
		$data['user_group'] = $this->mdl_user->get_group();
		$data['activity_type'] = $this->mdl_activity->get_by_parent_to_ticket();

		$now = date("ymd");
		$last_code_ticket = substr($this->mdl_ticket->get_last_code_ticket(),8,4);
		$next_code_ticket = $last_code_ticket + 1;
		$no_ticket = "SR".$now.sprintf("%04d", $next_code_ticket);

		if ($ticket_id == 0){
			$ticket_id = $this->mdl_ticket->get_last_id() + 1;
			$code_ticket = $no_ticket;
			if ($intact_id != ""){
				$interaction_id_arr = $this->mdl_ticket->get_interaction_id($intact_id);
				if (count($interaction_id_arr) > 0)
					$interaction_id = $interaction_id_arr[0]->interaction_id;
				else $interaction_id = "0";

				$customer_name_arr = $this->mdl_interaction->get_by_id($interaction_id);
				if (count($customer_name_arr) > 0)
					$customer_name = $customer_name_arr[0]->customer_name;
				else $customer_name = "0";

				$customer_phone_arr = $this->mdl_interaction->get_by_id($interaction_id);
				if (count($customer_phone_arr) > 0)
					$customer_phone = $customer_phone_arr[0]->customer_phone;
				else $customer_phone = "0";

				$customer_email_arr = $this->mdl_interaction->get_by_id($interaction_id);
				if (count($customer_email_arr) > 0)
					$customer_email = $customer_email_arr[0]->customer_email;
				else $customer_email = "0";

				$id_pesanan_arr = $this->mdl_interaction->get_by_id($interaction_id);
				if (count($id_pesanan_arr) > 0)
					$id_pesanan = $id_pesanan_arr[0]->id_pesanan;
				else $id_pesanan = "0";
				// M55
				$so_number_arr = $this->mdl_interaction->get_by_id($interaction_id);
				if (count($so_number_arr) > 0)
					$so_number = $so_number_arr[0]->so_number;
				else $so_number = "0";

				$this->mdl_ticket->add($ticket_id, $this->session->userdata("session_user_id"), $activity_id, $intact_id, $customer_name, $customer_phone, $customer_email, $id_pesanan, $so_number, $code_ticket);	
				// M55
			}else{
				$interaction_id = isset($_GET['interaction_id']) ? $_GET['interaction_id'] : 0;
				$customer_name = isset($_GET['customer_name']) ? $_GET['customer_name'] : NULL;
				$customer_phone = isset($_GET['customer_phone']) ? $_GET['customer_phone'] : NULL;
				$customer_email = isset($_GET['customer_email']) ? $_GET['customer_email'] : NULL;
				$id_pesanan = isset($_GET['id_pesanan']) ? $_GET['id_pesanan'] : NULL;
				// M55
				$so_number = isset($_GET['so_number']) ? $_GET['so_number'] : NULL;

				$this->mdl_ticket->add($ticket_id, $this->session->userdata("session_user_id"), $activity_id, $intact_id, $customer_name, $customer_phone, $customer_email, $id_pesanan, $so_number, $code_ticket);	
				// M55

			}
			// get ticket fields from template
			if (isset($activity_id)){
				$data['ticket_fields'] = $this->mdl_ticket->get_ticket_fields($activity_id);
				}
			else $data['ticket_fields'] = array();
			$data['ticket_details'] = array();
			$result = $this->mdl_ticket->get_by_ticket_id($ticket_id, $activity_code, $code_ticket);
			$data['ticket_activity_closed'] = $this->mdl_ticket->is_activity_closed($ticket_id);
			$record = $result[0];
			$data['ticket_data'] = $record;
			$this->ticketlog->add($ticket_id, "Ticket created", 0);
			}
		else {
			$result = $this->mdl_ticket->get_by_ticket_id($ticket_id, $activity_code, $code_ticket);
			$record = $result[0];
			$data['ticket_data'] = $record;
			
			// get ticket data and fields
			if (isset($record->activity_id)){
				$data['ticket_fields'] = $this->mdl_ticket->get_ticket_fields($record->activity_id);				
			}
			else $data['ticket_fields'] = array();
			$data['ticket_details'] = $this->mdl_ticket->get_ticket_details($ticket_id);
			$data['ticket_activity_closed'] = $this->mdl_ticket->is_activity_closed($ticket_id);
			$this->ticketlog->add($ticket_id, "view ticket details", 0);
		}
		$data['ticket_id'] = $ticket_id;
		$data['code_ticket'] = $code_ticket;
		// MD03
		$data['ticket_notes'] = $this->mdl_ticket->get_ticket_notes($ticket_id);
		// MD03
		// $sla = $this->mdl_ticket->get_sla_by_activity_code($activity_code);
		// $data['sla'] = $sla[0]->sla;
		// $test = $this->ticketlog->get_handled_datetime();
		// print_r($test);
// Detail Ticket

		$this->cso_template->view("user/user_ticket", $data);
		// print_r($customer_name) ;
	}
	// M038 M039 M041 M045
	function form_action($mode, $ticket_id = 0){
		if ($mode == "changeActivityCode"){
			$this->mdl_ticket->change_activities($ticket_id, $_REQUEST['hdn-activity-id']);
			$this->mdl_ticket->delete_details($ticket_id);
			$activityCode = ""; // load activity code
			$this->ticketlog->add($ticket_id, "Ticket activity code changed to " . $activityCode);		
		}	
		else if ($mode == "save"){
			// MD4
			// MD4
			$user_group_id = $_REQUEST['cmb-owner-group'];
			$user_arr = $this->mdl_user->get_status_distribusi($user_group_id);
			if (count($user_arr) == 0){
				$this->mdl_user->update_all_status_distribusi($user_group_id);
			}
			// MD4
            $user_group_id=$_REQUEST['cmb-owner-group'];
			$owner_id = $this->mdl_user->available_user($user_group_id);
			// MD4
			$data['customer_name'] = $_REQUEST['txt-customer-name'];
			$data['customer_type'] = $_REQUEST['txt-customer-type'];
			$data['customer_priority'] = $_REQUEST['txt-customer-priority'];
			$data['customer_event_datetime'] = date("Y-m-d H:i:s", strtotime($_REQUEST['txt-customer-event-datetime']));
			$data['customer_phone'] = $_REQUEST['txt-customer-phone'];
			$data['customer_alt_number'] = $_REQUEST['txt-customer-alt-number'];
			$data['customer_email'] = $_REQUEST['txt-customer-email'];
			$data['ticket_status'] = 6; //$_REQUEST['cmb-ticket-status'];
			$data['ticket_substatus'] = $_REQUEST['cmb-ticket-substatus'];
			$data['owner_group_id'] = $_REQUEST['cmb-owner-group'];
			$data['ticket_priority'] = $_REQUEST['cmb-ticket-priority'];
			$data['detail_info'] = str_replace(array('cke_pastebin','style','absolute', 'px') , '', $_REQUEST['txt-ticket-description']);
			$data['id_pesanan'] = $_REQUEST['txt-id-pesanan'];
			// M55
			$data['so_number'] = $_REQUEST['txt-so-number'];
			// M55
			$data['owner_id'] = $owner_id;
			if ($data['ticket_status'] == 8){ // closed ticket
				$data['closed_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
			}
			
			$this->mdl_ticket->save($ticket_id, $data);
			$details = array();
			if (isset($_REQUEST['txt-field'])){
				foreach($_REQUEST['txt-field'] as $field_id => $ticket_detail_content){
					$details[] = array(
						"ticket_id" => $ticket_id,
						"field_id" => $field_id,
						"ticket_detail_content" => $ticket_detail_content
					);
				}
			}
			// MD4			
			$this->mdl_user->update_status_distribusi($user_group_id);
			// MD4
			$this->mdl_ticket->save_details($ticket_id, $details);
			$this->ticketlog->add($ticket_id, "Ticket saved successfully", 0);			
		}
		else if ($mode == "handle"){
			$data['owner_id'] = $this->session->userdata("session_user_id");
			$data['ticket_substatus'] = 2;
			$data['ticket_status'] = 7;
			$this->mdl_ticket->save($ticket_id, $data);
			$this->ticketlog->add($ticket_id, "handled the ticket", 1);
			}
		else if ($mode == "unhandle"){
			$data['owner_id'] = NULL;		
			$data['ticket_substatus'] = 1;				
			$data['ticket_status'] = 6;			
			$this->mdl_ticket->save($ticket_id, $data);
			$this->ticketlog->add($ticket_id, "unhandled the ticket", 2);						
			}
		else if ($mode == "change_owner"){
			$data['owner_group_id'] = $_REQUEST['owner_group_id'];		
			$data['owner_id'] = NULL;		
			$data['ticket_substatus'] = 1;				
			$data['ticket_status'] = 6;			
			$this->mdl_ticket->save($ticket_id, $data);
			$this->ticketlog->add($ticket_id, "change the ticket owner group", 3);						
		}

// M043
		else if ($mode == "solve"){
			$data['ticket_status'] = 9;
			$data['solved_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
			$data['resolution_note'] = $_POST['resolution_note'];
			$this->mdl_ticket->save($ticket_id, $data);
			$this->ticketlog->add($ticket_id, "solved the ticket", 4);			
		}	
		else if ($mode == "close"){
			$data['ticket_status'] = 8;
			$data['closed_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);		
			$data['resolution_note'] = $_POST['resolution_note'];	
			$this->mdl_ticket->save($ticket_id, $data);
			$this->ticketlog->add($ticket_id, "closed the ticket", 5);	
// M019
// M040
		}else if ($mode == "recover"){
			$data['recover_id'] = $this->session->userdata("session_user_id");
			$data['ticket_status'] = 10;
			$data['recovering_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);	
			$this->mdl_ticket->save($ticket_id, $data);
			$this->ticketlog->add($ticket_id, "recover ticket", 6);		
		}else if ($mode == "success"){
			$data['ticket_status'] = 11;
			$data['recovered_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
			$data['recovery_note'] = $_POST['recovery_note'];	
			$this->mdl_ticket->save($ticket_id, $data);
			$this->ticketlog->add($ticket_id, "recover ticket success", 7);		
		}else if ($mode == "failed"){
			$data['ticket_status'] = 12;
			$data['recovered_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
			$data['recovery_note'] = $_POST['recovery_note'];	
			$this->mdl_ticket->save($ticket_id, $data);
			$this->ticketlog->add($ticket_id, "recover ticket failed", 8);		
		}
// M040
// M019
// M043
// M58
		else if ($mode == "cancelled"){
			$data['ticket_status'] = 14;
			$data['cancelled_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);	
			// M63
			$data['owner_group_id'] = 7;
			$data['ticket_substatus'] = 2;
			// M63
			$this->mdl_ticket->save($ticket_id, $data);
			$this->ticketlog->add($ticket_id, "Cancelled the ticket", 9);
		}
// M58
		header("Location: " . base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id);
	}
	
	/* Activities */
	function activities($ticket_id = 0){
		$data['ticket_id'] = $ticket_id;
		$activities = $this->mdl_ticket->get_ticket_activities($ticket_id);
		$activity_code_arr = $this->mdl_ticket->get_activity_code($ticket_id);
		$activity_code = $activity_code_arr[0]->activity_code;
		// $data['activity_code'] = $activity_code;
		$data['ticket_template'] = $this->mdl_ticket->get_ticket_template($activity_code);
		$result = $this->mdl_ticket->get_ticket_data($ticket_id, array('ticket_status', 'owner_id'));		
		$record = $result[0];
		$data['ticket_status'] = $record->ticket_status;
		$data['owner_id'] = $record->owner_id;
		if (count($activities) == 0){
			$data['ticket_template_id'] = "";
		}		
		else {
			$ticket_template_id = $this->mdl_ticket->get_ticket_data($ticket_id, array("ticket_template_id"));
			$data['ticket_template_id'] = $ticket_template_id[0]->ticket_template_id;
			$data['ticket_activities'] = $activities;
		}			
		$this->cso_template->view("user/user_ticket_activities", $data);
		//echo $activity_code;
		//print_r($activity_code);

	}
	function ajax_activity_plans($ticket_template_id){
		$plans = $this->mdl_ticket->get_ticket_activityplan($ticket_template_id);
		$str = "";
		foreach($plans as $record){
			$str .= "<tr>";
			$str .= 	"<td>Activity Plan " . $record->plan_order . "</td>";
			$str .= 	"<td>" . $record->action_name . "</td>";
			$str .= 	"<td>" . $record->function_name . "</td>";
			$str .= 	"<td></td>";
			$str .= 	"<td></td>";
			$str .= 	"<td></td>";
			$str .= 	"<td></td>";
			$str .= "</tr>";
		}
		echo $str;
	}
	function activities_action($mode, $ticket_activity_id, $ticket_id){
		if ($mode == "start"){
			$this->mdl_ticket->update_ticket_activity($ticket_activity_id, array("ticket_activity_status" => 7, "start_datetime" =>gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)));
			$this->ticketlog->add($ticket_id, "started ticket activity", 0);		
		}
		else if ($mode == "close"){
			$this->mdl_ticket->update_ticket_activity($ticket_activity_id, array("ticket_activity_status" => 8, "closed_datetime" =>gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)));
			$this->ticketlog->add($ticket_id, "closed ticket activity", 0);					
		}
		else if ($mode == "bypass"){
			$this->mdl_ticket->update_ticket_activity($ticket_activity_id, array("ticket_activity_status" => 8, "start_datetime" =>gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60), "closed_datetime" =>gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)));
			$this->ticketlog->add($ticket_id, "closed ticket activity", 0);
		}
		// echo $note;
		header("Location: " . base_url() . "index.php/user/ctr_ticket/activities/" . $ticket_id);
	}
	function apply_plan(){
		$ticket_template_id = $_REQUEST['cmb-ticket-template'];
		$ticket_id = $_REQUEST['ticket_id'];
		$this->mdl_ticket->use_ticket_activityplan($ticket_id, $ticket_template_id);
		$this->ticketlog->add($ticket_id, "applied ticket template");
		$sla = $this->mdl_ticket->get_sla_by_template_id($ticket_template_id);
		
		$days = $sla / 24;
		$hours = $sla % 24;
		
		$duration = $days * 24 * 60 * 60 + $hours * 60 * 60;
		$dueHH = date("H", time() + $duration);
		if ($dueHH >= 17 || $dueHH < 8){
			if ($dueHH < 8) $dueHH += 24;
			$dueHH -= 17;
			$duration += 15 * 60 * 60 + $days * 24 * 60 * 60; // next day + over time
		}
			
		$data['due_datetime'] = date("Y-m-d H:i:s", time() + $duration);
		$this->mdl_ticket->save($ticket_id, $data);
		header("Location: " . base_url() . "index.php/user/ctr_ticket/activities/" . $ticket_id);
	}
	
	/* Interactions */
	function interactions($ticket_id = 0){
		$data['ticket_id'] = $ticket_id;
		$data['ticket_interactions'] = $this->mdl_interaction->get_by_ticket_id($ticket_id);
		$this->cso_template->view("user/user_ticket_interactions", $data);	
	}
	
	/* Attachments */
	function attachments($ticket_id = 0, $message_type = "", $message = ""){
		$data['ticket_id'] = $ticket_id;
		$data['attachments'] = $this->mdl_attachment->get_by_ticket_id($ticket_id);
		$data['message_type'] = $message_type;
		$data['message'] = $message;
		if ($message == "" && isset($_REQUEST['message'])){
			$data['message'] = $_REQUEST['message'];
		}
		$this->cso_template->view("user/user_ticket_attachments", $data);				
	}
	function attachment_action($mode, $attachment_id = 0, $ticket_id = 0){
		if ($mode == "add"){
			$config['upload_path'] = "./attachment/ticket/";
			$config['allowed_types'] = "jpg|png|gif|bmp|doc|docx|xls|xlsx|ics|txt|pdf|msg";
			$config['max_size'] = "2048";
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			
			$ticket_id = $_REQUEST['ticket_id'];
			if (!$this->upload->do_upload('file-ticket-attachment')){
				$this->ticketlog->add($ticket_id, "failed to upload attachment");									
				header("Location: " . base_url() . "index.php/user/ctr_ticket/attachments/" . $_REQUEST['ticket_id'] . "/error?message=" . ($this->upload->display_errors()));
			}	
			else {
				$upload_data = $this->upload->data();	
				$data['attachment_name'] = $upload_data['full_path'];
				$data['attachment_note'] = $_REQUEST['txt-attachment-description'];
				$data['creator_id'] = $_REQUEST['creator_id'];
				$data['ticket_id'] = $_REQUEST['ticket_id'];
				$this->ticketlog->add($data['ticket_id'], "added attachments");									
				$this->mdl_attachment->add($data);
				header("Location: " . base_url() . "index.php/user/ctr_ticket/attachments/" . $_REQUEST['ticket_id']);
			}

		}
		else if ($mode == "delete") {
			$this->mdl_attachment->delete($attachment_id);
			$this->ticketlog->add($ticket_id, "deleted attachment");					
			if ($ticket_id != 0){
				header("Location: " . base_url() . "index.php/user/ctr_ticket/attachments/" . $ticket_id);
			}
		}	
		else if ($mode == "download"){
			$result = $this->mdl_attachment->get_by_attachment_id($attachment_id);
			$record = $result[0];
			$data = file_get_contents($record->attachment_name);
			force_download($record->attachment_name, $data);
			$this->ticketlog->add($ticket_id, "downloaded attachment");					
		}
	}
	
	/* Notes */
	function notes($ticket_id = 0){
		$data['ticket_id'] = $ticket_id;
		$data['ticket_notes'] = $this->mdl_ticket->get_ticket_notes($ticket_id);
		header("Location: " . base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id);	
	}
	// MD03
	function notes_action($mode, $note_id = 0){
		if ($mode == "add"){
			$this->mdl_ticket->add_ticket_notes($_REQUEST['ticket_id'], $_POST['notes'], $_REQUEST['creator_id']);	
			$this->ticketlog->add($_REQUEST['ticket_id'], "added ticket notes");	
		}
		header("Location: " . base_url() . "index.php/user/ctr_ticket/notes/" . $_REQUEST['ticket_id']);
	}
	// MD03
	/* Related */
	function related($ticket_id = 0){
		$data['ticket_id'] = $ticket_id;
		$result = $this->mdl_ticket->get_ticket_data($ticket_id
			, array("id_pesanan", "so_number", "customer_phone", "customer_email", "helpcso_ticket.activity_id"));
		$record = $result[0];
		$data['activity_id'] = $record->activity_id;
		// $data['customer_name'] = $record->customer_name;
		$data['id_pesanan'] = $record->id_pesanan;
		$data['so_number'] = $record->so_number;
		$data['customer_email'] = $record->customer_email;
		$data['customer_phone'] = $record->customer_phone;
		$this->cso_template->view("user/user_ticket_related", $data);
	}
	
	/* Audit Trail */
	function audit_trail($ticket_id = 0){
		$data['ticket_id'] = $ticket_id;	
		$this->cso_template->view("user/user_ticket_audit_trail", $data);
	}
	function ajaxauditlist(){
		$ticket_id = isset($_REQUEST['ticket_id']) ? $_REQUEST['ticket_id'] : 0;
		/* 
		 * Columns
		 */
		$aColumns = array(
			"log_id"
			, "log_datetime"
			, "user_name"
			, "log_description"
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
		
		/* filter just for ticket log */
			if ( $sWhere == "" ){
					$sWhere .= "WHERE ";
				}
			else $sWhere .= " AND ";
			$sWhere .= " ticket_id = " . $ticket_id;
		
		
		$sEcho = $_GET['sEcho'];

		echo $this->mdl_ticket->get_related($aColumns, $sLimit, $sOrder, $sWhere, $sEcho);
	}
}
?>