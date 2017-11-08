<?php

	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Ticket</a>
    <span class='user-page-subtitle'>- notes</span>    
    <div class='pull-right'>
      <div class='btn-group'>
    	<a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Detail</a>
	    <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/activities/" . $ticket_id; ?>'>Activity Plan</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/interactions/" . $ticket_id; ?>'> Interactions</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/attachments/" . $ticket_id; ?>'>Attachments</a>
        <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_ticket/notes/" . $ticket_id; ?>'>Notes</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/related/" . $ticket_id; ?>'>Related</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/audit_trail/" . $ticket_id; ?>'>Audit Trail</a>
       </div>
	    
    </div>
</div>
<div class='user-page-content'>
<form method="post" action="<?php echo base_url(); ?>index.php/user/ctr_ticket/notes_action/add">
<textarea name="notes" id="notes"></textarea>
<br />   
<input type='hidden' name="ticket_id" id="ticket_id" value="<?php echo $ticket_id; ?>" />
<input type='hidden' name="creator_id" id="creator_id" value="<?php echo $this->session->userdata('session_user_id'); ?>" />
<button type='submit' class="btn btn-primary pull-right" name="btn-ticket-notes-add" id="btn-ticket-notes-add" onclick="saveTicketNotes()">Submit</button>
</form>
<br />
<br />
<?php foreach ($ticket_notes as $record){ ?>
	<div class='ticket_note'>
    	<div class='ticket_note_body'><?php echo $record->notes; ?></div>
        <div class="ticket_note_footer">
	        <div class='ticket_note_author'>By <?php echo $record->author; ?></div>
    	    <div class='ticket_note_datetime'>on <?php echo $record->note_datetime; ?></div>
        </div>
    </div>
<?php }?>
</div>
<script type="text/javascript">
$("#navbarItemTicket").attr('class', 'active');
CKEDITOR.replace('notes', 
	{
	toolbarGroups: [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	]
	}
);
</script>