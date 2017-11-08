<!-- MD03 - YA - add information pada ticket, Tambah button interaksi di activity plan untuk create new interaksi -->
<?php

	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Ticket</a>
    <span class='user-page-subtitle'>- interactions</span>    
    <div class='pull-right'>
      <div class='btn-group'>
    	<a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Detail</a>
	    <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/activities/" . $ticket_id; ?>'>Activity Plan</a>
        <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_ticket/interactions/" . $ticket_id; ?>'> Interactions</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/attachments/" . $ticket_id; ?>'>Attachments</a>
        <!-- <a class="btn btn-danger" href='<?php //echo base_url() . "index.php/user/ctr_ticket/notes/" . $ticket_id; ?>'>Notes</a> -->
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/related/" . $ticket_id; ?>'>Related</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/audit_trail/" . $ticket_id; ?>'>Audit Trail</a>
       </div>
	    
    </div>
</div>
<div class='user-page-content'>
	<a class="btn btn-success pull-right" id='btn-new-activity' href='<?php echo base_url(); ?>index.php/user/ctr_interaction/form?ticket_id=<?php echo $ticket_id; ?>'>
    	<span class='glyphicon glyphicon-plus'> </span> New Interaction
    </a>
	<table class='table' id='tbl-interaction-activities'>
    	<tr>
        	<th>Interaction ID</th>
            <th>Created Datetime</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Customer Phone</th>
            <th>Interaction Creator Name</th>
            <th>Interaction Type</th>
            <th>Status</th>            
            <th>View Detail</th>
        </tr>
        	<?php foreach($ticket_interactions as $record){ ?>
        <tr>            
            	<td><?php echo $record->interaction_id; ?></td>
                <td><?php echo $record->creator_datetime; ?></td>
                <td><?php echo $record->customer_name; ?></td>
                <td><?php echo $record->customer_email; ?></td>
                <td><?php echo $record->customer_phone; ?></td>
                <td><?php echo $record->creator_name; ?></td>
                <td><?php echo $record->interaction_type_name; ?></td>
                <td><?php echo $record->status_name; ?></td>
                <td><a href="<?php echo base_url(); ?>index.php/user/ctr_interaction/form/<?php echo $record->interaction_id; ?>" class="btn btn-primary btn-xs"><div class="glyphicon glyphicon-search"> </div></a></td>
        </tr>
            <?php }?>
    </table>
</div>
<script type="text/javascript">
$("#navbarItemTicket").attr('class', 'active');
</script>