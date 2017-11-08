<?php
class Mdl_ticket extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	/* ticket: helpcso_escalation_ticket
		ticket_id, cat_id, ticket_content, ticket_response, cso_id, esc_id, trx_email, ticket_priority, 
		submit_datetime, handled_datetime, solved_datetime, ticket_status */
	function get_last_categoryid()
	{
		$sqlquery = "Select total_escalation_category from mst_count_data";
		return $this->db->query($sqlquery);
	}
	
	function last_id(){
		$sqlquery = "select max(ticket_id) as last_id from helpcso_escalation_ticket";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function add($ticket_data, $ticket_status = 1){
		$ticket_data['ticket_id'] = $this->last_id() + 1;
		$ticket_data['submit_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
		$ticket_data['ticket_status'] = $ticket_status;
		$this->db->insert('helpcso_escalation_ticket', $ticket_data);
		return $ticket_data['ticket_id'];
	}
	function addFieldData($field_data){
		$this->db->insert('helpcso_escalation_ticketcontent', $field_data);
	}
	function getFieldData($ticket_id){
		$sql = "select tcontent.ticket_id, f.fieldName, tcontent.fieldContent
				from helpcso_escalation_ticketcontent tcontent
				inner join helpcso_escalation_fields f on f.fieldid = tcontent.fieldid
				where tcontent.ticket_id = " . $ticket_id . " 
				";
		$query = $this->db->query($sql);
		return $query->result();
	}
	function update($ticket_id, $ticket_data){
		$this->db->update('helpcso_escalation_ticket', $ticket_data, "ticket_id = " . $ticket_id);
	}
	function detail($ticket_id){
		$sqlquery = "select ticket_id
					, ticket.cat_id, cat.catname
					, ticket_content
					, ticket_response
					, ticket.cso_id, cso.user_name as cso_name
					, ticket.esc_id, esc.user_name as esc_name
					, trxIDEmail
					, ticket.ticket_priority, priority.priority_name, priority.priority_color
					, DATE_FORMAT(submit_datetime, '%e %M %Y %H:%i') as submit_datetime
					, DATE_FORMAT(handled_datetime, '%e %M %Y %H:%i') as handled_datetime
					, DATE_FORMAT(solved_datetime, '%e %M %Y %H:%i') as solved_datetime
					, ticket.ticket_status, sts.status_name, sts.status_color
					from helpcso_escalation_ticket ticket
					inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					inner join helpcso_escalation_ticketpriority priority on priority.priority_id = ticket.ticket_priority
					inner join helpcso_escalation_ticketstatus sts on sts.status_id = ticket.ticket_status
					inner join helpcso_user cso on cso.user_id = ticket.cso_id
					left join helpcso_user esc on esc.user_id = ticket.esc_id
					where ticket_id = " . $ticket_id;
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function get($cat_id = "", $status = "", $priority = "", $keyword = ""){
		if ($cat_id != "") $wherestr[] = "cat_id = " . $cat_id;
		if ($status != "") $wherestr[] = "ticket_status = " . $status;
		if ($priority != "") $wherestr[] = "ticket_priority = " . $priority;
		if ($keyword != "") {
			$keyword = str_replace(" ", "%", $keyword);
			$wherestr[] = "(ticket_content like '%" . $keyword . "%' or email like '%" . $keyword . "%')";
			}
		
		$ext = "";
		if (isset($wherestr) && count($wherestr) > 0)
			$ext = "where " . implode(" and ", $wherestr);
			
		$sqlquery = "select ticket_id
					, ticket.cat_id, cat.catname
					, ticket_content
					, ticket_response
					, ticket.cso_id, cso.user_name as cso_name
					, ticket.esc_id, esc.user_name as esc_name
					, trxIDEmail
					, ticket.ticket_priority, priority.priority_name, priority.priority_color
					, DATE_FORMAT(submit_datetime, '%e %M %Y %H:%i') as submit_datetime
					, DATE_FORMAT(handled_datetime, '%e %M %Y %H:%i') as handled_datetime
					, DATE_FORMAT(solved_datetime, '%e %M %Y %H:%i') as solved_datetime
					, ticket.ticket_status, sts.status_name, sts.status_color
					from helpcso_escalation_ticket ticket
					inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					inner join helpcso_escalation_ticketpriority priority on priority.priority_id = ticket.ticket_priority
					inner join helpcso_escalation_ticketstatus sts on sts.status_id = ticket.ticket_status
					inner join helpcso_user cso on cso.user_id = ticket.cso_id
					left join helpcso_user esc on esc.user_id = ticket.esc_id
					" . $ext;
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function get_for_cso($startDate, $endDate){
		$ext = "";
		if ($startDate != '' && $endDate != ""){
			$ext = " WHERE submit_datetime >= '" . $startDate . " 00:00:00' and submit_datetime <= '" . $endDate . " 23:59:59'";
		}
		$sqlquery = "
			select ticket_id
					, ticket.cat_id, cat.catname
					, ticket_content
					, ticket_response
					, ticket.cso_id, cso.user_name as cso_name
					, ticket.esc_id, esc.user_name as esc_name
					, trxIDEmail
					, ticket.ticket_priority, priority.priority_name, priority.priority_color
					, DATE_FORMAT(submit_datetime, '%e %M %Y %H:%i') as submit_datetime
					, DATE_FORMAT(handled_datetime, '%e %M %Y %H:%i') as handled_datetime
					, DATE_FORMAT(solved_datetime, '%e %M %Y %H:%i') as solved_datetime
					, ticket.ticket_status, sts.status_name, sts.status_color
					from helpcso_escalation_ticket ticket
					inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					inner join helpcso_escalation_ticketpriority priority on priority.priority_id = ticket.ticket_priority
					inner join helpcso_escalation_ticketstatus sts on sts.status_id = ticket.ticket_status
					inner join helpcso_user cso on cso.user_id = ticket.cso_id
					left join helpcso_user esc on esc.user_id = ticket.esc_id
					" . $ext . "
		";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function get_by_date($startDate, $endDate, $ticketCategory = "-", $ticketStatus = "-", $ticketPriority = "-"){
		$ext = "";
		if ($ticketCategory != "-")
			$ext .= " and ticket.cat_id = " . $ticketCategory;
		if ($ticketStatus != "-")
			$ext .= " and ticket.ticket_status = " . $ticketStatus;
		if ($ticketPriority != "-")
			$ext .= " and ticket.ticket_priority = " . $ticketPriority;
			
		$sqlquery = "select ticket_id
					, ticket.cat_id, cat.catname
					, ticket_content
					, ticket_response
					, ticket.cso_id, cso.user_name as cso_name
					, ticket.esc_id, esc.user_name as esc_name
					, trxIDEmail
					, ticket.ticket_priority, priority.priority_name, priority.priority_color
					, DATE_FORMAT(submit_datetime, '%e %M %Y %H:%i') as submit_datetime
					, DATE_FORMAT(handled_datetime, '%e %M %Y %H:%i') as handled_datetime
					, DATE_FORMAT(solved_datetime, '%e %M %Y %H:%i') as solved_datetime
					, ticket.ticket_status, sts.status_name, sts.status_color
					from helpcso_escalation_ticket ticket
					inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					inner join helpcso_escalation_ticketpriority priority on priority.priority_id = ticket.ticket_priority
					inner join helpcso_escalation_ticketstatus sts on sts.status_id = ticket.ticket_status
					inner join helpcso_user cso on cso.user_id = ticket.cso_id
					left join helpcso_user esc on esc.user_id = ticket.esc_id
					where submit_datetime >= '" . $startDate . " 00:00:00' and submit_datetime <= '" . $endDate . " 23:59:59' " . $ext . "
					";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	/* category: helpcso_escalation_category
		cat_id, catname, fields, record_flag */
	function category_last_id(){
		$sqlquery = "select max(cat_id) as last_id from helpcso_escalation_category";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function check_child_category($flag,$cat_id){
			$query = $this->db->query("select catname
									   from helpcso_escalation_category where parent_id = '".$cat_id."' and record_flag = '1' LIMIT 1");
			if ($flag == 1){
				return $query;
			}
			else if ($flag == 2){
				return $query->num_rows();
			}
		}
		
	function category_add($cat_data){
		$cat_data['cat_id'] = $this->category_last_id() + 1;
		$cat_data['record_flag'] = "1";
		$this->db->insert('helpcso_escalation_category', $cat_data);
		
		$data_jumlah = array(
						'total_escalation_category'=>$this->category_last_id()
						);
		$this->db->update('mst_count_data',$data_jumlah);
	}
	function category_update($cat_id, $cat_data){
		$this->db->update('helpcso_escalation_category', $cat_data, 'cat_id = ' . $cat_id);
	}
	function category_delete($cat_id){
		$this->db->update('helpcso_escalation_category', array("record_flag" => "0"), 'cat_id = ' . $cat_id);
	}
	function category_detail($cat_id){
		$query = $this->db->query("select cat_id, catname from helpcso_escalation_category where cat_id = " . $cat_id);
		return $query->result();
	}
	
	function category_search($flag_query,$flag,$keyword){
		if($flag_query == 1) {
			$query = $this->db->query("select cat_id, catname,category_code, level, parent_id,
										(select catname from helpcso_escalation_category cat2 where cat2.cat_id = cat1.parent_id) as category_parent 
									   from helpcso_escalation_category cat1
									   where record_flag = '1' and catname like '%" . str_replace(" ", "%", $keyword) . "%'");
		}
		else if($flag_query == 2) {
			$query = $this->db->query("select cat_id, catname,category_code, level, parent_id,
										(select catname from helpcso_escalation_category cat2 where cat2.cat_id = cat1.parent_id) as category_parent 
									   from helpcso_escalation_category cat1
									   where record_flag = '1' and catname like '%" . str_replace(" ", "%", $keyword) . "%' LIMIT 5");
		}
		if ($flag == 1){
			return $query->result();
		}
		else {
			return $query->num_rows();
		}
	}
	
	function category_get_all(){
		$query = $this->db->query("select cat_id, catname,category_code, level, parent_id,
									(select catname from helpcso_escalation_category cat2 where cat2.cat_id = cat1.parent_id) as category_parent 
									from helpcso_escalation_category cat1 where record_flag = '1'");
		return $query->result();
	}
	/* priority: helpcso_escalation_ticketpriority
		priority_id, priority_name, priority_publish, priority_color
	 */
	function priority_get_all($show_hidden = true){
		$wherestr = ""; 
		if ($show_hidden == false){
			$wherestr = "where priority_publish = 1";
		}
		$query = $this->db->query("select priority_id, priority_name, priority_color, priority_default from helpcso_escalation_ticketpriority " 
			. $wherestr 
			. " order by priority_id");
		return $query->result();
	}
	
	function par_category_get($level,$cat_id)
		{
			$sqlquery = "Select * 
						 from helpcso_escalation_category
						 where level = '" .$level."' 
						 	   and record_flag = '1'
							   and cat_id <> '".$cat_id."'";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
		
	function pil_level_escalation_category()
		{
			$sqlquery = "Select * from mst_level_escalation_category";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
	
	function get_ticket_report(){
		$sqlquery = "
					select 	cat_id
							,catname
							,sum(total_new_ticket) as total_new_ticket
							,sum(total_handled_ticket) as total_handled_ticket
							,sum(total_solved_ticket) as total_solved_ticket
							,sum(total_closed_ticket) as total_closed_ticket
					from(
					select 
							ticket.cat_id as cat_id,
							cat.catname as catname,
							count(*) as total_new_ticket,
							'0' as total_handled_ticket,
							'0' as total_solved_ticket,
							'0' as total_closed_ticket
				 	 from helpcso_escalation_ticket ticket
					 inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					 where ticket.ticket_status = '1'
					 group by ticket.cat_id
					 union
					 select 
							ticket.cat_id as cat_id,
							cat.catname as catname,
							'0' as total_new_ticket,
							count(*) as total_handled_ticket,
							'0' as total_solved_ticket,
							'0' as total_closed_ticket
				 	 from helpcso_escalation_ticket ticket
					 inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					 where ticket.ticket_status = '3'
					 group by ticket.cat_id
					 union
					 select 
							ticket.cat_id as cat_id,
							cat.catname as catname,
							'0' as total_handled_ticket,
							'0' as total_new_ticket,
							count(*) as total_solved_ticket,
							'0' as total_closed_ticket
				 	 from helpcso_escalation_ticket ticket
					 inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					 where ticket.ticket_status = '4'
					 group by ticket.cat_id
					 union
					 select 
							ticket.cat_id as cat_id,
							cat.catname as catname,
							'0' as total_handled_ticket,
							'0' as total_solved_ticket,
							'0' as total_new_ticket,
							count(*) as total_closed_ticket
					 from helpcso_escalation_ticket ticket
					 inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					 where ticket.ticket_status = '5'
					 group by ticket.cat_id
					 ) AS temp
					 group by cat_id ";

		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function report_search_bydate($flag_number,$startDate,$endDate){
		$query = $this->db->query("
					  select cat_id
							,catname
							,sum(total_new_ticket) as total_new_ticket
							,sum(total_handled_ticket) as total_handled_ticket
							,sum(total_solved_ticket) as total_solved_ticket
							,sum(total_closed_ticket) as total_closed_ticket
					from(
					select 
							ticket.cat_id as cat_id,
							cat.catname as catname,
							count(*) as total_new_ticket,
							'0' as total_handled_ticket,
							'0' as total_solved_ticket,
							'0' as total_closed_ticket
				 	 from helpcso_escalation_ticket ticket
					 inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					 where ticket.ticket_status = '1'
					 	   and (DATE_FORMAT(ticket.submit_datetime, '%m/%d/%Y') >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   		 and DATE_FORMAT(ticket.submit_datetime, '%m/%d/%Y') <= '" . str_replace(" ", "%", $endDate) . "' )
					 group by ticket.cat_id
					 union
					 select 
							ticket.cat_id as cat_id,
							cat.catname as catname,
							'0' as total_new_ticket,
							count(*) as total_handled_ticket,
							'0' as total_solved_ticket,
							'0' as total_closed_ticket
				 	 from helpcso_escalation_ticket ticket
					 inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					 where ticket.ticket_status = '3'
					 	   and (DATE_FORMAT(ticket.submit_datetime, '%m/%d/%Y') >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   		 and DATE_FORMAT(ticket.submit_datetime, '%m/%d/%Y') <= '" . str_replace(" ", "%", $endDate) . "' )
					 group by ticket.cat_id
					 union
					 select 
							ticket.cat_id as cat_id,
							cat.catname as catname,
							'0' as total_handled_ticket,
							'0' as total_new_ticket,
							count(*) as total_solved_ticket,
							'0' as total_closed_ticket
				 	 from helpcso_escalation_ticket ticket
					 inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					 where ticket.ticket_status = '4'
					 	   and (DATE_FORMAT(ticket.submit_datetime, '%m/%d/%Y') >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   		 and DATE_FORMAT(ticket.submit_datetime, '%m/%d/%Y') <= '" . str_replace(" ", "%", $endDate) . "' )
					 group by ticket.cat_id
					 union
					 select 
							ticket.cat_id as cat_id,
							cat.catname as catname,
							'0' as total_handled_ticket,
							'0' as total_solved_ticket,
							'0' as total_new_ticket,
							count(*) as total_closed_ticket
					 from helpcso_escalation_ticket ticket
					 inner join helpcso_escalation_category cat on cat.cat_id = ticket.cat_id
					 where ticket.ticket_status = '5'
					 	   and (DATE_FORMAT(ticket.submit_datetime, '%m/%d/%Y') >= '" . str_replace(" ", "%", $startDate) . "' 
					 	   		 and DATE_FORMAT(ticket.submit_datetime, '%m/%d/%Y') <= '" . str_replace(" ", "%", $endDate) . "' )
					 group by ticket.cat_id
					 ) AS temp
					 group by cat_id");
		if ($flag_number == 1){
			return $query->result();
		}
		else if ($flag_number == 2){
			return $query->num_rows();
		}			 	
	}
	function status_get_all(){
		$query = $this->db->query("select status_id, status_name from helpcso_escalation_ticketstatus " 
			. " order by status_id");
		return $query->result();
	}
	
	//fields
	
	function fields_category($cat_id){
		$query = $this->db->query("select catname
								   from helpcso_escalation_category
								   where cat_id = '".$cat_id."'");
		return $query->row('catname');
	}
	
	function fields_get_all($cat_id){
		$query = $this->db->query("select fields.fieldID, 
										  fields.fieldName, 
										  fields.fieldMandatory 
								   from helpcso_escalation_fields fields
								   inner join helpcso_escalation_categoryfields cat_fields on cat_fields.fieldID = fields.fieldID
								   where cat_fields.catID = '".$cat_id."'
								         and fields.field_flag ='1'");
		return $query->result();
	}
	
	function fields_last_id(){
		$sqlquery = "select max(fieldID) as last_id from helpcso_escalation_categoryfields";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function fields_add($fields_data,$cat_id){
		$fields_data['fieldID'] = $this->fields_last_id() + 1;
		$fields_data['field_flag'] = "1";
		$this->db->insert('helpcso_escalation_fields', $fields_data);
		
		$data = array(
						'fieldID'=>$this->fields_last_id() + 1,
						'catID'=>$cat_id
						);
		$this->db->insert('helpcso_escalation_categoryfields',$data);
	}
	function fields_update($field_id, $field_data){
		$this->db->update('helpcso_escalation_fields', $field_data, 'fieldID = ' . $field_id);
	}
	function fields_delete($field_id){
		$this->db->update('helpcso_escalation_fields', array("field_flag" => "0"), 'fieldID = ' . $field_id);
	}
	function fields_search($flag_query,$flag,$cat_id,$keyword){
		if ($flag_query == 1){
			$query = $this->db->query("select fields.fieldID, 
											  fields.fieldName,
											  fields.fieldMandatory
									   from helpcso_escalation_fields fields 
									   inner join helpcso_escalation_categoryfields cat_fields on cat_fields.fieldID = fields.fieldID
									   where fields.field_flag = '1' 
											 and cat_fields.catID = '".$cat_id."'
											 and fields.fieldName like '%" . str_replace(" ", "%", $keyword) . "%'");
		} 
		else if ($flag_query == 2){
			$query = $this->db->query("select fields.fieldID, 
											  fields.fieldName,
											  fields.fieldMandatory
									   from helpcso_escalation_fields fields 
									   inner join helpcso_escalation_categoryfields cat_fields on cat_fields.fieldID = fields.fieldID
									   where fields.field_flag = '1' 
											 and cat_fields.catID = '".$cat_id."'
											 and fields.fieldName like '%" . str_replace(" ", "%", $keyword) . "%' LIMIT 4");
		}
		if ($flag == 1){
			return $query->result();
		}
		else {
			return $query->num_rows();
		}
	}
}
?>