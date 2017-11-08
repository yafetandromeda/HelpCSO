<!-- MD03 - YA - add information pada ticket, Tambah button interaksi di activity plan untuk create new interaksi -->
<?php

	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Ticket</a>
    <span class='user-page-subtitle'>- audit trail</span>    
    <div class='pull-right'>
      <div class='btn-group'>
    	<a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Detail</a>
	    <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/activities/" . $ticket_id; ?>'>Activity Plan</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/interactions/" . $ticket_id; ?>'> Interactions</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/attachments/" . $ticket_id; ?>'>Attachments</a>
        <!-- <a class="btn btn-danger" href='<?php //echo base_url() . "index.php/user/ctr_ticket/notes/" . $ticket_id; ?>'>Notes</a> -->
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/related/" . $ticket_id; ?>'>Related</a>
        <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_ticket/audit_trail/" . $ticket_id; ?>'>Audit Trail</a>
       </div>
	    
    </div>
</div>
<div class='user-page-content'>
   	<div id='hdn_ticket_id' class="hidden"><?php echo $ticket_id; ?></div>        
	<table id='tbl-audit-trail' class="display table table-bordered table-hover">
      <thead>
    	<tr><th>Log No</th>
        	<th>Datetime</th>
            <th>User Name</th>
            <th>Description</th>
        </tr>
       </thead>
       <tbody>
       </tbody>
    </table>
</div>
<script type="text/javascript">
$("#navbarItemTicket").attr('class', 'active');
</script>