<?php
// M046 - YA - Ubah tampilan baloon, hyperlink di showlist,  tambah status draft, tambah SLA
// MD01 - YA - Advance user profile, untuk mengatur field yang muncul hanya di user tertentu saja, atau hal – hal yang bisa dijadikan default terhadap user tersebut.
// MD02 - YA - Interaction status menggunakan model button, bukan combo box, tidak perlu tombol save &  System Autosave supaya jika pindah tab informasi sebelumnya tidak hilang
// MD03 - YA - add information pada ticket, Tambah button interaksi di activity plan untuk create new interaksi
// M55 - YA - penambahan SO number pada interaction dan ticket

class Mdl_interaction extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_last_id(){
		$sqlquery = "select max(interaction_id) as last_id from helpcso_interaction";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	// M045
	function get_last_code_interaction(){
		$today = date("ymd");
		$sqlquery = "select max(code_interaction) as last_code from helpcso_interaction where code_interaction LIKE 'IN$today%'";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_code;
	}
	function get_by_id($interaction_id, $just_status = false){
		if ($just_status)
			$sqlquery = "select 
			  interaction_id, interaction_status_id, creator_id
			from helpcso_interaction
			inner join helpcso_user on helpcso_user.user_id = helpcso_interaction.creator_id
			where interaction_id = " . $interaction_id;
		else 
			// M55
		  $sqlquery = "select 
			  interaction_id
			, interaction_type_id
			, customer_name
			, customer_phone
			, customer_email
			, queue_number
			, id_pesanan
			, so_number
			, creator_id
			, creator_datetime
			, user_name as creator_name
			, priority_id
			, interaction_description
			, interaction_status_id
			, planned_start_datetime
			, actual_start_datetime
			, actual_cancel_datetime
			, actual_end_datetime 
			, code_interaction
			from helpcso_interaction
			inner join helpcso_user on helpcso_user.user_id = helpcso_interaction.creator_id
			where interaction_id = " . $interaction_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
		// M55
	}
	// M045
	function get_by_ticket_id($ticket_id){
		 $sqlquery = "select 
			  helpcso_interaction.interaction_id
			, customer_name
	        , customer_phone
            , customer_email
			, creator_datetime
			, creator_datetime
			, user_name as creator_name
			, creator_datetime
      	    , status_name
	        , interaction_type_name
			from helpcso_interaction
			inner join helpcso_ticket_interaction
			on helpcso_interaction.interaction_id = helpcso_ticket_interaction.interaction_id
      		inner join helpcso_user
			on helpcso_interaction.creator_id = helpcso_user.user_id
      		left join helpcso_status
			on helpcso_interaction.interaction_status_id = helpcso_status.status_id
      		left join helpcso_interaction_type
			on helpcso_interaction.interaction_type_id = helpcso_interaction_type.interaction_type_id
			where ticket_id = " . $ticket_id . " order by creator_datetime desc";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_by_creator_id($creator_id){
		$sqlquery = "select 
			  interaction_id
			, interaction_type_id
			, customer_name
			, customer_phone
			, customer_email
			, queue_number
			, creator_id
			, creator_datetime
			, priority_id
			, interaction_description
			, interaction_status_id
			, planned_start_datetime
			, actual_start_datetime
			, actual_cancel_datetime
			, actual_end_datetime 
			from helpcso_interaction
			where creator_id = " . $creator_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function num_by_creator_id($creator_id){
		$sqlquery = "select
			  count(interaction_id) as num_interaction
			from helpcso_interaction
			left join helpcso_interaction_type 
			on helpcso_interaction.interaction_type_id = helpcso_interaction_type.interaction_type_id
			where (interaction_status_id <> 2 or interaction_status_id is null) and creator_id = " . $creator_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->num_interaction;
	}
	// M045
	function get($aColumns, $sLimit, $sOrder, $sWhere, $sEcho){
		/* Table */
		$sTable = "helpcso_interaction
			inner join helpcso_user
			on helpcso_interaction.creator_id = helpcso_user.user_id
			left join helpcso_interaction_type 
			on helpcso_interaction.interaction_type_id = helpcso_interaction_type.interaction_type_id
			left join helpcso_status
			on helpcso_interaction.interaction_status_id = helpcso_status.status_id";
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			select SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			from " . $sTable . "
			left join helpcso_priority on helpcso_interaction.priority_id = helpcso_priority.priority_id
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
			FROM   $sTable
		";
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
			$row[0] = "<a href='" . base_url() . "index.php/user/ctr_interaction/form/" . $aRow->$aColumns[9] . "' class='p'>".$aRow->$aColumns[0]."</a>";
			$output['aaData'][] = $row;
		}
		
		return json_encode( $output );
	}
	// M55
	function add_ticket_interaction($interaction_id, $creator_id, $creator_datetime, $ticket_id = 0, $code_interaction, $customer_name = 0, $customer_phone = 0, $customer_email = 0, $id_pesanan = 0, $so_number = 0){
		$data = array(
			  "interaction_id" => $interaction_id
			, "creator_id" => $creator_id
			, "creator_datetime" => $creator_datetime
			, "interaction_status_id" => 1
			, "code_interaction" => $code_interaction
			, "customer_name" => $customer_name
			, "customer_phone" => $customer_phone
			, "customer_email" => $customer_email
			, "id_pesanan" => $id_pesanan
			, "so_number" => $so_number
			);
		$this->db->insert("helpcso_interaction", $data);	
		
		if ($ticket_id != 0){
			$data = array(
				  "interaction_id" => $interaction_id
				, "ticket_id" => $ticket_id
				, "code_interaction" => $code_interaction
				);
			$this->db->insert("helpcso_ticket_interaction", $data);	
		}
	}
	// M55
	// M046
	function add($interaction_id, $creator_id, $creator_datetime, $ticket_id = 0, $code_interaction){
		$data = array(
			  "interaction_id" => $interaction_id
			, "creator_id" => $creator_id
			, "creator_datetime" => $creator_datetime
			, "interaction_status_id" => 1
			, "code_interaction" => $code_interaction
			);
		$this->db->insert("helpcso_interaction", $data);	
		
		if ($ticket_id != 0){
			$data = array(
				  "interaction_id" => $interaction_id
				, "ticket_id" => $ticket_id
				, "code_interaction" => $code_interaction
				);
			$this->db->insert("helpcso_ticket_interaction", $data);	
		}
	}
	// M046
	// M045
	function update($data, $interaction_id){
		$this->db->update("helpcso_interaction", $data, array("interaction_id" => $interaction_id));
	}
// MD02
	function update_autosave($interaction_id, $interaction_data){
		$this->db->where('interaction_id', $interaction_id);
		$this->db->update('helpcso_interaction', $interaction_data);
	}
// MD02
	function get_interaction_type(){
		$sqlquery = "select 
					  interaction_type_id
					, interaction_type_name 
					from helpcso_interaction_type 
					where status_active = 1";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_interaction_status(){
		$sqlquery = "select
						status_id as interaction_status_id
					  , status_name  as interaction_status_name
					  from helpcso_status
					  where status_active = 1 
					  and status_flag = 'i'
					  order by status_order";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_interaction_priority(){
		$sqlquery = "select
						priority_id as interaction_priority_id
					  , priority_name  as interaction_priority_name
					  , priority_default as interaction_priority_default
					  from helpcso_priority";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function notif($userid, $statusname){
		$where = "";
		if ($statusname == "DRAFT") $where = '(interaction_status_id = 1 or interaction_status_id is null)';
		else if ($statusname == "CLOSED") $where = "interaction_status_id = 2";
		else if ($statusname == "SCHEDULED") $where = "(interaction_status_id = 3 and planned_start_datetime >= '" . date("Y-m-d H:i:s") . "')";
		else if ($statusname == "OVER") $where = "(interaction_status_id = 3 and planned_start_datetime < '" . date("Y-m-d H:i:s") . "')";
		else if ($statusname == "INPROGRESS") $where = "interaction_status_id = 4";
		else if ($statusname == "CANCELLED") $where = "interaction_status_id = 5";
	
		$sqlquery = "select
			count(interaction_id) as num
			from helpcso_interaction
			left join helpcso_interaction_type 
			on helpcso_interaction.interaction_type_id = helpcso_interaction_type.interaction_type_id
			where " . $where . " and creator_id = " . $userid;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		$record = $result[0];
		return $record->num;
	}
// MD01
	function user_group_field($userid){
		$sqlquery = "select
			 user.user_id, field.queue_number, field.planned_start_date
			from helpcso_user user
			inner join helpcso_user_group_field field on field.id = user.group_id
			where user.user_id = " .$userid;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
// MD01
// MD03
	function get_ticket_id($interaction_id){
		$sqlquery = "select ticket_id as ticket_id from helpcso_ticket_interaction where interaction_id = " .$interaction_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result; 
	}
// MD03
}
?>