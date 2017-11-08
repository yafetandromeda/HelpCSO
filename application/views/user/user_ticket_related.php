<!-- M65 - YA - Related pada ticket ini by automatic fungsi ini akan mencocokan 3 field pada ticket Customer Name & Customer Phone & Customer Email digantikan dengan kombinasi ID Pesanan*(Prioritas) & SO Number & Customer Phone & Customer Email -->
<?php

	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Ticket</a>
    <span class='user-page-subtitle'>- related</span>    
    <div class='pull-right'>
      <div class='btn-group'>
    	<a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Detail</a>
	    <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/activities/" . $ticket_id; ?>'>Activity Plan</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/interactions/" . $ticket_id; ?>'> Interactions</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/attachments/" . $ticket_id; ?>'>Attachments</a>
        <!-- <a class="btn btn-danger" href='<?php //echo base_url() . "index.php/user/ctr_ticket/notes/" . $ticket_id; ?>'>Notes</a> -->
        <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_ticket/related/" . $ticket_id; ?>'>Related</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/audit_trail/" . $ticket_id; ?>'>Audit Trail</a>
       </div>
	    
    </div>
</div>
<div class='user-page-content'>
	<div id='hdn_activity_id' class="hidden"><?php echo $activity_id; ?></div>
	<div id='hdn_ticket_template_id' class="hidden">0</div>
	<div id='hdn_interaction_id' class="hidden">0</div>        
   	<div id='hdn_ticket_id' class="hidden"><?php echo $ticket_id; ?></div>     
    <!-- <div id='hdn_customer_name' class="hidden"><?php //echo $customer_name; ?></div>      -->
    <div id='hdn_id_pesanan' class="hidden"><?php echo $id_pesanan; ?></div>   
    <div id='hdn_so_number' class="hidden"><?php echo $so_number; ?></div>       
    <div id='hdn_customer_phone' class="hidden"><?php echo $customer_phone; ?></div>     
    <div id='hdn_customer_email' class="hidden"><?php echo $customer_email; ?></div>        
	<table id='tbl-ticket-related' class="display table table-bordered table-hover">
      <thead>
    	<tr>
        	<th>No</th>
            <th>Created Datetime</th>
        <!-- M046 -->
            <th>ID Pesanan</th>
        <!-- M046 -->
        <!-- M65 -->
            <th>SO Number</th>
        <!-- M65 -->
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Customer Phone</th>
            <th>Activity Code</th>            
            <th>Issue Description</th>
            <th>Ticket Creator Name</th>
            <th>Ticket Owner Group</th>
            <th>Ticket Owner Name</th>
            <th>SLA</th>
            <th>Priority</th>
            <th>Status</th>
<!-- M018 -->
            <th>Solved Ticket</th>
            <th>Closed Ticket</th>
        </tr>
       </thead>
       <tbody>
       </tbody>
    </table>
</div>
<script type="text/javascript">
$("#navbarItemTicket").attr('class', 'active');
</script>