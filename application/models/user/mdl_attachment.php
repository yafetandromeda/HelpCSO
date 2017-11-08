<?php
class Mdl_Attachment extends CI_Model {
	function __construct(){
		parent::__construct();
	}
	function get_last_id(){
		$sqlquery = "select max(attachment_id) as last_id from helpcso_attachment";
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result[0]->last_id;
	}
	function get_by_attachment_id($attachment_id){
		$sqlquery = "select 
			  attachment_id
			, attachment_datetime
			, attachment_name
			, attachment_note
			, creator_id
			, user_name as creator_name
			, interaction_id
			, ticket_id
			from helpcso_attachment
			inner join helpcso_user on helpcso_attachment.creator_id = helpcso_user.user_id
			where attachment_id = " . $attachment_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_by_interaction_id($interaction_id){
		$sqlquery = "select
			  attachment_id
			, attachment_datetime
			, attachment_name
			, attachment_note
			, creator_id
			, user_name as creator_name
			, interaction_id
			, ticket_id
			from helpcso_attachment
			inner join helpcso_user on helpcso_attachment.creator_id = helpcso_user.user_id
			where interaction_id = " . $interaction_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function get_by_ticket_id($ticket_id){
		$sqlquery = "select
			  attachment_id
			, attachment_datetime
			, attachment_name
			, attachment_note
			, creator_id
			, user_name as creator_name
			, interaction_id
			, ticket_id
			from helpcso_attachment
			inner join helpcso_user on helpcso_attachment.creator_id = helpcso_user.user_id
			where ticket_id = " . $ticket_id;
		$query = $this->db->query($sqlquery);
		$result = $query->result();
		return $result;
	}
	function add($data = array()){
		$data['attachment_id'] = $this->get_last_id() + 1;
		$data['attachment_datetime'] = gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60);
		$this->db->insert("helpcso_attachment", $data);
	}
	function delete($attachment_id){
		$this->db->query("DELETE FROM helpcso_attachment WHERE attachment_id = " . $attachment_id);
	}
}
?>