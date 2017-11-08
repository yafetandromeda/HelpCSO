<?php
// M018 - YA - Menampilkan Solved dan Closed Datetime
// M024 - YA - Notification Recovering Ticket
// M026 - YA - Menampilkan Handled, Recovering dan Recovered Ticket
// M034 - YA - Menampilkan ticket template berdasarkan activity code
// M035 - YA - Menampilkan Hour berisi SLA
// M038 - YA - menyamakan customer name
// M039 - YA - menyamakan email & nomor
// M043 - YA - tambah recovery & resolution notes
// M045 - YA - Ubah feld
// M046 - YA - Ubah tampilan baloon, hyperlink di showlist,  tambah status draft, tambah SLA
// M051 - YA - Ubah report activity, ticket, & interaction
// M55 - YA - penambahan SO number pada interaction dan ticket
// M58 - YA - Fitur cancelled untuk ticket junk/salah
// M65 - YA - Related pada ticket ini by automatic fungsi ini akan mencocokan 3 field pada ticket Customer Name & Customer Phone & Customer Email digantikan dengan kombinasi ID Pesanan*(Prioritas) & SO Number & Customer Phone & Customer Email
// M67 - YA - Ubah filter ticket
class Mdl_ticket extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_last_id(){
		$sqlquery = "select max(ticket_id) as last_id from helpcso_ticket";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	// M045
	function get_last_code_ticket(){
		$today = date("ymd");
		$sqlquery = "select max(code_ticket) as last_code from helpcso_ticket where code_ticket LIKE 'SR$today%'";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_code;
	}
	// M045
	// M038 M039 M046 M55
	function add($ticket_id, $creator_id, $activity_id = NULL, $intact_id = NULL, $customer_name = NULL, $customer_phone = NULL, $customer_email = NULL, $id_pesanan = NULL, $so_number = NULL, $code_ticket = NULL){
		$data = array(
			  "ticket_id" => $ticket_id
			, "creator_id" => $creator_id
			, "creator_datetime" => gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)
			, "activity_id" => $activity_id
			, "interaction_activity_id" => $intact_id
			, "ticket_status" => 13
			, "customer_name" => $customer_name
			, "customer_phone" => $customer_phone
			, "customer_email" => $customer_email
			, "id_pesanan" => $id_pesanan
			, "so_number" => $so_number
			, "code_ticket" => $code_ticket
			// M67
			, "ticket_substatus" => 1
			// M67
			);
		$this->db->insert("helpcso_ticket", $data);
	}
	// M038 M039 M046 M55
	function num_by_owner_id($owner_id, $overdue = false){
		if ($overdue == true){
			$ext = " and due_datetime < '" . gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60) . "'";
		}
		else $ext = "";
		$sqlquery = "select 
			  count(ticket_id) as num_ticket
			from helpcso_ticket
			where ticket_status != 8 and ticket_status != 9 and owner_id = " . $owner_id . " " . $ext;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->num_ticket;
	}
// M018
	// M046
	function get($aColumns, $sLimit, $sOrder, $sWhere, $sEcho){
		/* Table */
		$sTable = "helpcso_ticket
			left join helpcso_activity
			on helpcso_activity.activity_id = helpcso_ticket.activity_id
			left join helpcso_priority
			on helpcso_priority.priority_id = helpcso_ticket.ticket_priority
			left join helpcso_status
			on helpcso_status.status_id = helpcso_ticket.ticket_status
			left join helpcso_user_group
			on helpcso_user_group.id = helpcso_ticket.owner_group_id
			left join helpcso_user creator
			on creator.user_id = helpcso_ticket.creator_id
			left join (select user_id as ownerid, user_name as owner_name from helpcso_user) owner
			on owner.ownerid = helpcso_ticket.owner_id
			";
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			select SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			from " . $sTable . "
			" . $sWhere . "
			" . $sOrder . "
			" . $sLimit;
		$rResult = $this->db->query($sQuery);
		
		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS() as row
		";
		$rResultFilterTotal = $this->db->query($sQuery); 
		$aResultFilterTotal = $rResultFilterTotal->result();
		$iFilteredTotal = $aResultFilterTotal[0]->row;
		
		/* Total data set length */
		$sQuery = "
			SELECT COUNT(*) as cnt
			FROM   " . $sTable;
		$rResultTotal = $this->db->query($sQuery); 
		$aResultTotal = $rResultTotal->result();
		$iTotal = $aResultTotal[0]->cnt;
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => $sEcho,
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		$records = $rResult->result();
		$colLen = count($aColumns);
		foreach ($records as $aRow){
			$row = array();
			/* General output */
			for ($i = 0; $i < $colLen; $i++)
				$row[] = $aRow->$aColumns[$i];
			// M65
			$row[0] = "<a href='" . base_url() . "index.php/user/ctr_ticket/form/" . $aRow->$aColumns[17] . " 'class='p'>".$aRow->$aColumns[0]."</a>";
			// M65
			$row[11] = "<p>".$aRow->$aColumns[11]."</p>";
			$output['aaData'][] = $row;
		}
		
		return json_encode( $output );
	}
	// M045 M046
	function get_ticket_status(){
		$sqlquery = "select
						status_id as ticket_status_id
					  , status_name  as ticket_status_name
					  from helpcso_status
					  where status_active = 1 
					  and status_flag = 't'
					  order by status_order";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_ticket_substatus(){
		$sqlquery = "select
						substatus_id as ticket_substatus_id
					  , substatus_name  as ticket_substatus_name
					  from helpcso_substatus
					  where status_active = 1 
					  and substatus_flag = 't'";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_ticket_priority(){
		$sqlquery = "select
						priority_id as ticket_priority_id
					  , priority_name  as ticket_priority_name
					  , priority_default as ticket_priority_default
					  from helpcso_priority";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_ticket_template(){
		$sqlquery = "select
			  ticket_template_id
			, ticket_template_name
			, (select sum(sla) from helpcso_ticket_activityplan
				where helpcso_ticket_activityplan.ticket_template_id = helpcso_ticket_template.ticket_template_id) 
			  as sla
			from helpcso_ticket_template
			where status_active = 1";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}

	// function get_ticket_template($activity_code){
	// 	$sqlquery = "SELECT template_activity.ticket_template_id, ticket_template.ticket_template_name,
	// 				(select sum(sla) from helpcso_ticket_activityplan where helpcso_ticket_activityplan.ticket_template_id = ticket_template.ticket_template_id) as sla,
	// 				ticket_template.status_active
	// 				FROM helpcso_ticket_template_activity template_activity
	// 				inner join helpcso_ticket_template ticket_template on ticket_template.ticket_template_id=template_activity.ticket_template_id
	// 				where ticket_template.status_active=1 and template_activity.activity_code=".$activity_code;
	// 	$query = $this->db->query($sqlquery);
	// 	$result = $query->result();
	// 	if($query->num_rows() > 0)
	// 	return $result;
	// }

	function get_sla_by_template_id($ticket_template_id){
		$sqlquery = "SELECT sum(sla) as sla from helpcso_ticket_activityplan
				where helpcso_ticket_activityplan.ticket_template_id = " . $ticket_template_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		$record = $result[0];
		return $record->sla;	
	}

	function get_sla_by_activity_code($activity_code){
		$sqlquery = "SELECT sum(sla) as sla FROM helpcso_ticket_activityplan activity_plan
					inner join helpcso_ticket_template_activity template_activity on template_activity.ticket_template_id=activity_plan.ticket_template_id
					where template_activity.activity_code= " . $activity_code;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;	
	}

	function get_ticket_data($ticket_id, $fields = NULL){
		$fieldstr = "";
		if ($fields == NULL) $fieldstr = "*";
		else $fieldstr = implode(", ", $fields);
		$sqlquery = "select " . $fieldstr . "
			from helpcso_ticket 
			left join helpcso_interaction_activity 
				on helpcso_ticket.interaction_activity_id = helpcso_interaction_activity.interaction_activity_id
			where ticket_id = " . $ticket_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_ticket_activityplan($ticket_template_id){
		$sqlquery = "select
						template_activityplan.plan_id,
					    template_activityplan.ticket_template_id,
					    template_activityplan.plan_order,
					    activityplan.action_name,
					    activityplan.function_name,
					    activityplan.sla,
					    template_activityplan.status_active
							from helpcso_ticket_template_activityplan template_activityplan
					    inner join helpcso_ticket_activityplan activityplan
					    	on activityplan.plan_id=template_activityplan.plan_id
						where template_activityplan.ticket_template_id = ".$ticket_template_id."
					    and template_activityplan.status_active=1
						order by template_activityplan.plan_order
			";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_last_ticket_activity_id(){
		$sqlquery = "select
			max(ticket_activity_id) as last_id
			from helpcso_ticket_activity";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function use_ticket_activityplan($ticket_id, $ticket_template_id){
		// get plan_id from template
		// set status = 6 open
		$sqlquery = "select
			template_activityplan.plan_id, activityplan.action_name
			from helpcso_ticket_template_activityplan template_activityplan
      inner join helpcso_ticket_activityplan activityplan
        on activityplan.plan_id = template_activityplan.plan_id
			where template_activityplan.ticket_template_id = ".$ticket_template_id."
      and template_activityplan.status_active = 1
			order by template_activityplan.plan_order
			";
		$query = $this->db->query($sqlquery);
		$plans = $query->result();
		$last_id = $this->get_last_ticket_activity_id();
		
		foreach($plans as $plan){
			$last_id += 1;
			$data = array(
				  "ticket_activity_id" => $last_id
				, "ticket_id" => $ticket_id
				, "plan_id" => $plan->plan_id
				, "action_name" =>$plan->action_name
				, "ticket_activity_status" => 6
			);
			$this->db->insert("helpcso_ticket_activity", $data);
		}	
		
		$this->db->query("update helpcso_ticket 
			set ticket_template_id = " . $ticket_template_id . " 
			where ticket_id = " . $ticket_id);
	}
	// M55
	function get_ticket_activities($ticket_id){
		$sqlquery = "select
			  ticket_activity_id
			, activity.ticket_id
			, ticket.customer_name
			, ticket.customer_phone
			, ticket.customer_email
			, ticket.id_pesanan
			, ticket.so_number
			, plan_order
			, activity.plan_id
			, activity.action_name
			, function_name
			, sla
			, ticket_activity_status
			, status_name
			, activity.start_datetime
			, activity.closed_datetime
			from helpcso_ticket_activity activity
			inner join helpcso_ticket_activityplan activityplan
				on activity.plan_id = activityplan.plan_id
			inner join helpcso_status status
				on activity.ticket_activity_status = status.status_id
			inner join helpcso_ticket ticket
				on ticket.ticket_id = activity.ticket_id	
			where activity.ticket_id = " . $ticket_id . "
			order by activityplan.plan_order";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	// M55
	function update_ticket_activity($ticket_activity_id, $data){
		$this->db->update("helpcso_ticket_activity", $data, array("ticket_activity_id" => $ticket_activity_id));
	}
	function is_ticket_activity_solved($ticket_id){
		$query = $this->db->query("select count(ticket_activity_status) as cnt
from helpcso_ticket_activity where ticket_id = " . $ticket_id . " and interaction_activity_status != 8"); // still open
		$result = $query->result();
		$record = $result[0];
		if ($record->cnt == 0) return 1;
		return 0;
	}
	function get_ticket_notes($ticket_id){
		$sqlquery = "select
			note_id, note_datetime, ticket_id, notes, user_name as author
			from helpcso_ticket_notes
			inner join helpcso_user on helpcso_user.user_id = helpcso_ticket_notes.creator_id
			where ticket_id = " . $ticket_id . "
			order by note_datetime desc
			";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_last_note_id(){
		$sqlquery = "select max(note_id) as last_id from helpcso_ticket_notes";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function add_ticket_notes($ticket_id, $notes, $creator_id){
		$data = array(
			  "note_id" => $this->get_last_note_id() + 1
			, "note_datetime" => gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)
			, "ticket_id" => $ticket_id
			, "notes" => $notes
			, "creator_id" => $creator_id
			);
		$this->db->insert("helpcso_ticket_notes", $data);	
	}
	function get_ticket_fields($activity_id){
		$sqlquery = "select
			field_id, field_name, field_mandatory
			from helpcso_ticket_field
			where status_active = 1 and activity_id = " . $activity_id . "
			order by field_id
			";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_ticket_details($ticket_id){
		$sqlquery = "select
			ticket_detail_id, ticket_id, ticket_detail_content,
			helpcso_ticket_field.field_id, field_name, field_mandatory
			from helpcso_ticket_field
			left join helpcso_ticket_detail on helpcso_ticket_detail.field_id = helpcso_ticket_field.field_id
			where status_active = 1 and ticket_id = " . $ticket_id . "
			order by helpcso_ticket_field.field_id
			";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
// M043 M051 M55 M58
	function get_by_ticket_id($ticket_id, $activity_code){
		$sqlquery = "select ticket_id
			, ticket_template_id
			, helpcso_ticket.interaction_activity_id
			, helpcso_interaction_activity.interaction_id
			, (select code_interaction from helpcso_interaction
			  left join helpcso_interaction_activity on helpcso_interaction_activity.interaction_id=helpcso_interaction.interaction_id
	          left join helpcso_ticket on helpcso_ticket.interaction_activity_id=helpcso_interaction_activity.interaction_activity_id
	          where ticket_id='".$ticket_id."') as code_interaction
			, inact.activity_code as inactivity_code
			, helpcso_ticket.activity_id
			, helpcso_activity.activity_code
			, detail_info
			, customer_name
			, id_pesanan
			, so_number
			, customer_type
			, customer_priority
			, customer_phone
			, customer_alt_number
			, customer_email
			, customer_event_datetime
			, ticket_status
			, helpcso_status.status_name as ticket_status_name
			, ticket_substatus
			, helpcso_substatus.substatus_name as ticket_substatus_name
			, owner_group_id
			, group_name
			, owner_id
			, owner.user_name as owner_name
			, ticket_priority
			, creator_id
			, creator.user_name as creator_name
			, creator_datetime
			, due_datetime
			, code_ticket
			, helpcso_ticket.closed_datetime
			, helpcso_ticket.solved_datetime
			, helpcso_ticket.recovering_datetime
			, helpcso_ticket.recovered_datetime
			, helpcso_ticket.cancelled_datetime
			, (select sum(sla) as sla FROM helpcso_ticket_activityplan activity_plan
					inner join helpcso_ticket_template_activity template_activity on template_activity.ticket_template_id=activity_plan.ticket_template_id
					where template_activity.activity_code= '" . $activity_code ."' group by template_activity.ticket_template_id limit 1) as sla
			, (select log_datetime from helpcso_ticket_log where log_desc_id=1 and ticket_id='".$ticket_id."' order by log_id desc limit 1) as log_datetime
			, (select status_name from helpcso_interaction left join helpcso_status st_int on st_int.status_id = interaction_status_id where interaction_id = helpcso_interaction_activity.interaction_id) as interaction_status
			, resolution_note
			, recovery_note
			from helpcso_ticket
			inner join helpcso_user creator on helpcso_ticket.creator_id = creator.user_id
			left join helpcso_user_group on helpcso_ticket.owner_group_id = helpcso_user_group.id
			left join helpcso_user owner on helpcso_ticket.owner_id = owner.user_id
			left join helpcso_status on helpcso_status.status_id = helpcso_ticket.ticket_status
			left join helpcso_substatus on helpcso_substatus.substatus_id = helpcso_ticket.ticket_substatus
			left join helpcso_activity on helpcso_activity.activity_id = helpcso_ticket.activity_id
			left join helpcso_interaction_activity on helpcso_interaction_activity.interaction_activity_id = helpcso_ticket.interaction_activity_id
			left join helpcso_activity inact on helpcso_interaction_activity.activity_id = inact.activity_id
			where ticket_id = " . $ticket_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
// M043 M051 M55 M58
	function get_activity_code($ticket_id){
		$sqlquery = "select
						helpcso_activity.activity_code
					    from helpcso_ticket
						left join helpcso_activity on helpcso_activity.activity_id = helpcso_ticket.activity_id
						where ticket_id =".$ticket_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
// M038
	function get_interaction_id($inact_id){
		$sqlquery = "select
						interaction_activity_id, interaction_id
					    from helpcso_interaction_activity
						where interaction_activity_id =".$inact_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
// M038
	function get_handled_datetime($ticket_id){
		$sqlquery = "select log_datetime from helpcso_ticket_log where log_desc_id=1 and ticket_id=" .$ticket_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}

	function get_related($aColumns, $sLimit, $sOrder, $sWhere, $sEcho){
		/* Table */
		$sTable = "helpcso_ticket_log inner join helpcso_user on helpcso_user.user_id = helpcso_ticket_log.log_user";
	
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			select SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			from " . $sTable . "
			" . $sWhere . "
			" . $sOrder . "
			" . $sLimit;
		$rResult = $this->db->query($sQuery);
		
		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS() as row
		";
		$rResultFilterTotal = $this->db->query($sQuery); 
		$aResultFilterTotal = $rResultFilterTotal->result();
		$iFilteredTotal = $aResultFilterTotal[0]->row;
		
		/* Total data set length */
		$sQuery = "
			SELECT COUNT(*) as cnt
			FROM   " . $sTable;
		$rResultTotal = $this->db->query($sQuery); 
		$aResultTotal = $rResultTotal->result();
		$iTotal = $aResultTotal[0]->cnt;
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => $sEcho,
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		$records = $rResult->result();
		$colLen = count($aColumns);
		foreach ($records as $aRow){
			$row = array();
			/* General output */
			for ($i = 0; $i < $colLen; $i++)
				$row[] = $aRow->$aColumns[$i];
			$output['aaData'][] = $row;
		}
		
		return json_encode( $output );
	}
	function change_activities($ticket_id, $activity_id){
		$this->db->query("UPDATE helpcso_ticket SET activity_id = " . $activity_id . " WHERE ticket_id = " . $ticket_id);
	}
	function save($ticket_id, $data){
		$this->db->update("helpcso_ticket", $data, array("ticket_id" => $ticket_id));
	}
	function get_last_detail_id(){
		$sql = "SELECT MAX(ticket_detail_id) as last_id from helpcso_ticket_detail";
		$query = $this->db->query($sql);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function save_details($ticket_id, $data){
		$this->delete_details($ticket_id);
		foreach($data as $datum){
			$arr["ticket_detail_id"] = $this->get_last_detail_id() + 1;
			$arr['ticket_id'] = $datum['ticket_id'];
			$arr['field_id'] = $datum['field_id'];
			$arr['ticket_detail_content'] = $datum['ticket_detail_content'];
			$this->db->insert("helpcso_ticket_detail", $arr);
		}
	}
	function delete_details($ticket_id){
		$this->db->query("DELETE FROM helpcso_ticket_detail WHERE ticket_id = " . $ticket_id);
	}
	function is_activity_closed($ticket_id){
		$query = $this->db->query("select count(ticket_activity_id) as cnt from helpcso_ticket_activity
where ticket_id = " . $ticket_id . " and closed_datetime is null"); // still open
		$result = $query->result();
		$record = $result[0];
		if ($record->cnt == 0) return 1;
		return 0;
	}
	function notif($userid, $statusname){
		$where = "";
		if ($statusname == "DRAFT") $where = '(ticket_status = 13)' . " and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "DRAFTOVER") $where = '(ticket_status = 13)' . " and (creator_datetime < DATE_SUB(NOW(), INTERVAL 1 DAY)) and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "OPEN") $where = '(ticket_status = 6)' . " and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "OPENHIGH") $where = '(ticket_status = 6)' . " and (ticket_priority = 1) and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "OPENOVER") $where = '(ticket_status = 6)' . " and (creator_datetime < DATE_SUB(NOW(), INTERVAL 1 DAY)) and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "INPROGRESS") $where = "(ticket_status = 7 and (due_datetime is null or due_datetime >= '" . date("Y-m-d H:i:s") . "'))" . " and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "INPROGRESSHIGH") $where = "(ticket_priority = 1) and (ticket_status = 7 and (due_datetime is null or due_datetime >= '" . date("Y-m-d H:i:s") . "'))" . " and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "INPROGRESSOVER") $where = "(ticket_status = 7 and creator_datetime < DATE_SUB(NOW(), INTERVAL 1 DAY) and (due_datetime is null or due_datetime >= '" . date("Y-m-d H:i:s") . "'))" . " and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "CLOSED") $where = "ticket_status = 8" . " and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "SOLVED") $where = "(ticket_status = 9)" . " and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "OVERSLA") $where = "(ticket_status = 7 and due_datetime < '" . date("Y-m-d H:i:s") . "')" . " and (creator_id = " . $userid . " or owner_id = " . $userid . ")";
		else if ($statusname == "GROUP") $where = "(ticket_status = 6) and owner_group_id = " . $this->session->userdata('session_user_group_id');
		// M024
		else if ($statusname == "RECOVERING") $where = "(ticket_status = 10)";
		// M024
		$sqlquery = "select
			count(ticket_id) as num
			from helpcso_ticket
			where " . $where;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		$record = $result[0];
		return $record->num;
		echo $sqlquery;
	}
}
?>