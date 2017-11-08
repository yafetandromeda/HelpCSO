<?php
 	class Mdl_others extends CI_Model {
		function ticket_detail(){
			$sqlquery = "select 
						activity.activity_id as activity_id,
						activity.activity_code as activity_code,
						activity.activity_description as activity_description,
						ticket.ticket_id as ticket_id,
						ticket.customer_name as customer_name,
						ticket.customer_type as customer_type,
						ticket.customer_phone as customer_phone,
						ticket.customer_email as customer_email,
						IFNULL(ticket.customer_event_datetime,'') as customer_event_datetime,
						user.user_name as creator_name,						
						IFNULL(ticket.creator_datetime,'') as create_datetime,
						status.status_name as status,
						substatus.substatus_name as substatus,
						IFNULL(ticket.solved_datetime,'') as solved_datetime,
						IFNULL(ticket.closed_datetime,'') as closed_datetime,
						IFNULL((
							select MAX(log_datetime) from helpcso_ticket_log 
							where 
								 ticket_id = ticket.ticket_id 
								 and log_description = 'handled the ticket' 
							Order by log_datetime DESC
						),'') as handled_datetime,
						ticket.detail_info as ticket_info,
						IFNULL((select user_name from helpcso_user where user_id = ticket.owner_id),'') as owner_name
					from helpcso_ticket ticket
					inner join helpcso_activity activity on activity.activity_id = ticket.activity_id
					inner join helpcso_status status on status.status_id = ticket.ticket_status
					inner join helpcso_substatus substatus on substatus.substatus_id = ticket.ticket_substatus
					left join helpcso_user user on ticket.creator_id = user.user_id";
				$query = $this->db->query($sqlquery);
				return $query->result();
			}
		}
?>
                    
                    