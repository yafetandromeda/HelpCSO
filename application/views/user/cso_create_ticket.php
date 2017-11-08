<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<!DOCTYPE html>
<html>
	<head>
    	<title>HelpCSO - Customer Service Page</title>
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/icon/favicon.ico">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/cso-style.css" />
        <link rel="stylesheet" type="text/css" />
		<style type="text/css">
			@import "<?php echo base_url(); ?>tools/datatables/media/css/demo_table_jui.css";
			@import "<?php echo base_url(); ?>tools/datatables/media/themes/smoothness/jquery-ui.css";
			@import "<?php echo base_url(); ?>tools/admin-style.css";
		</style>
	<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery-1.8.3.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery.dataTables.columnFilter.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.ui.core.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.ui.datepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>
    </head>
    <body>
		<?php if($level == '1') { ?>
		<div class="control-group cso-form-row" align="right">
            <input type="button" class="btn btn-danger" value="Back To Admin Page" id="back" onClick="back_to_admin_page()">
		</div>
		<?php } ?>
    	<div id="" class="container">
        	<div id="cso-cso-header">
            	<h3>HelpCSO Customer Service Page</h3>
            </div>
        	<div class="navbar" id='cso-cso-navbar'>
            	<div class="navbar-inner">
                	<ul class="nav">
                    	<li><a href="<?php echo base_url(); ?>index.php/user/ctr_home_user/index">Home</a></li>
                        <li><a href="#requestScriptModal" <?php if($level == '2') { ?> data-toggle="modal" <?php } ?>>Request Script</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            Escalation Ticket
                            <b class="caret"></b>
                            </a>
							<?php if($level == '2') { ?>
                            <ul class="dropdown-menu">
                              <li><a href="<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/create_ticket">Create Ticket</a></li>
                              <li><a href="<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/view_tickets/<?php echo $userid; ?>">View All Ticket</a></li>
                            </ul>
							<?php } ?>
                        </li>
                        <li class="dropdown">
                        	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"> </i> <?php echo $this->session->userdata('session_user_name'); ?>
                            <b class="caret"></b>
                            </a>
							<?php if($level == '2') { ?>
                            <ul class="dropdown-menu">
                              <li><a href="#changePasswordModal" data-toggle="modal">Change Password</a></li>
                              <li><a href="<?php echo site_url('ctr_helpcso_login/logout'); ?>">Logout</a></li>
                            </ul>
							<?php } ?>
                        </li>
                      </ul>
                </div>
            </div>
		<div id='cso-content'>
        	<h4>New Ticket</h4>
        	<?php 
			if (isset($notifType) && $notifType == "success"){
				echo "<div class='alert alert-success'>" . $notifMessage 
					. "  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button></div>";	
			}
			?>
           <form id="form_create_ticket" name="form_create_ticket" method="post" action="<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/submit_ticket">
            	<div class="control-group cso-form-row">
        			<label for="ticket_category" class="cso-form-label">Category</label>
	            	<select name="ticket_category" id="ticket_category">
    	            </select>
       		 	</div>
				<div class="control-group cso-form-row">
                	<label for="ticket_priority" class="cso-form-label">Priority</label>
	                <select name="ticket_priority" id="ticket_priority">
    	            </select>
                </div>
                <div class="control-group cso-form-row">
                	<label for="ticket_priority" class="cso-form-label">ID Pesanan / Email</label>
	                <input type='text' name='trxIDEmail' id='trxIDEmail' required />
                </div>
                <!-- Custom Fields -->
                <div id="customFieldDiv"></div>
                <label for="txt-ticket" class="cso-form-label">Notes</label>
	            <textarea name="txt-ticket" id="txt-ticket"></textarea>
				<br />                
               	<button class='btn btn-inverse pull-right' id='btnClosedTicket' name="btnClosedTicket" value="btnClosedTicket" onClick="return confirm('Are you sure to save this closed ticket?');">Closed</button>
                <span class="pull-right">&nbsp;</span>
                <button class='btn btn-primary pull-right' id='btnSubmitTicket' name="btnSubmitTicket" value="btnSubmitTicket" onClick="return confirm('Are you sure to submit this ticket?');">Submit</button>
                <br />
            </form>
        </div>
        <div id='ticketContentHidden' class="hide">
        	<?php
              foreach ($tickets as $record){
			  	echo "<div id='ticket_content" . $record->ticket_id . "'>" . $record->ticket_content . "</div>";
			  }
			?>
        </div>
        <div id="detailTicketModal" class="modal hide fade">
        	<div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	        <h3>Detail Ticket</h3>
            </div>
            <div class="modal-body" id='detailTicketModalBody'>
            	
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn_primary" data-dismiss="modal">Close</button>
            </div>
        </div>
        <div id="requestScriptModal" class="modal hide fade">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Request Script</h3>
            </div>
            <div class="modal-body">
            <form id="form_request_script" name="form_request_script" method="post">
	            <textarea placeholder="Write your request here..." name="txt-request" id="txt-request"></textarea>
            </form>
            </div>
            <div class="modal-footer">
            	<button type="button" class="btn btn_primary" id="submit_request_button" data-dismiss="modal">Submit</button>
            </div>
        </div>
        <div id="changePasswordModal" class="modal hide fade">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Change Password</h3>
            </div>
            <div class="modal-body">
				<?php $this->load->view('change_password', array('message' => '')); ?>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <script>
			$(document).ready(function(){
				$("#startDate").datepicker({
					dateFormat: "dd M yy"
				});
				$("#endDate").datepicker({
					dateFormat: "dd M yy"		
				});
				$('#tbl-tickets').dataTable({
					"sPaginationType":"full_numbers",
					"bJQueryUI":true,
					"bFilter":false	
				});
				CKEDITOR.replace('txt-request', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					]
					}
				);
				CKEDITOR.replace('txt-ticket', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					]
					}
				);
				$("#submit_request_button").bind('click', function(){
 				  if (confirm("Are you sure to submit this request?") == true){
					$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/user/ctr_view_list_script/request_script',
						data: "txt-request=" + escape(CKEDITOR.instances['txt-request'].getData()) 
							+ "&user_id=" + "<?php echo $this->session->userdata('session_user_id'); ?>"
					}).done(function(message){
						alert("Your request has been sent successfully");
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
					});
				  }
				});
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_ticketcategories'
					}).done(function(message){
						$("#ticket_category").html("<option value='' disabled selected>- Select -</option>" + message);
					}).fail(function(){
					
					});
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_ticketpriorities'
					}).done(function(message){
						$("#ticket_priority").html(message);
					}).fail(function(){
					
					});
				$("#ticket_category").bind('change', function(){
					$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/ajax_fields',
						data: 'cat_id=' + $(this).val()
					}).done(function(message){
						$("#customFieldDiv").html("<div id='asteriskNote'>* indicates required field</div>" + message);
					}).fail(function(){
					
					});
				});
				$(".detailTicketButton").click(function(){
					var id = $(this).attr('id');
					var ticketID = id.split('_')[1];
					console.log(id);
					console.log(ticketID);
					$("#detailTicketModalBody").html($("#ticket_content" + ticketID).html());
					});
				$("#btnFilterTicket").click(function(){
					var startDate = $("#startDate").datepicker('getDate');
					var endDate = $("#endDate").datepicker('getDate');
					window.location.href = "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/view_tickets/<?php echo $userid; ?>/" + startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getDate() + "/" + endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getDate();
				});
				});
        </script>
    </body>
</html>