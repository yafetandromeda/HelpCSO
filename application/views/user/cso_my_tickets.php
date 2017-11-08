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
        	<label for="ticket_categories">Category</label>
        	<select id="ticket_categories" name="ticket_categories">
			<?php
                echo "<option value='-' selected>- ALL -</option>";
                foreach($ticketCategory as $category){
                    echo "<option value='" . $category->cat_id . "'>" 
                        . $category->catname 
                        . "</option>";
                    }
            ?>
            </select><br />
            <label for="ticket_priorities">Priority</label>
            <select id="ticket_priorities" name="ticket_priorities">
            <?php
                echo "<option value='-' selected>- ALL -</option>";
                foreach($ticketPriority as $priority){
                    echo "<option value='" . $priority->priority_id . "'>" 
                        . $priority->priority_name 
                        . "</option>";
                    }
            ?>
            </select><br />
            <label for="ticket_status">Status</label>
            <select id="ticket_status" name="ticket_status">
            <?php
                echo "<option value='-' selected>- ALL -</option>";	
                foreach($ticketStatus as $status){
                    if ($status->status_name == "Open") continue;
                    echo "<option value='" . $status->status_id . "'>" . $status->status_name . "</option>";
                    }
            ?>    
            </select><br />
           <label for="startDate">Date</label> <input type="text" name="startDate" id="startDate" /> To <input type="text" name="endDate" id="endDate" /> 
	    	<button type="submit" id="btnFilterTicket" class="btn btn-primary">Filter</button><br />
		 <table id="tbl-tickets" class="display table table-hover table-bordered">
	          <thead>
    	        <tr>
        	        <th>Ticket ID</th>
            	    <th>Category</th>
                	<th>ID Pesanan / Email</th>
    	            <th>Priority</th>
        	        <th>Status</th>
                    <th>CSO</th>
            	    <th>Staff Eskalasi</th>
                	<th>Tanggal</th>
                    <th></th>
	            </tr>
    	       </thead>
             <tbody>
            <?php
                foreach ($tickets as $record){
                    $datetime = $record->submit_datetime;
                    if ($record->status_name == "Handled") $datetime = $record->handled_datetime;
                    else if ($record->status_name == "Solved") $datetime = $record->solved_datetime;
                    
                    echo "<tr id='ticket_" . $record->ticket_id . "' style=\"color:" . $record->priority_color . ";background-color:" . $record->status_color . "\" class='ticket_row'>" 
                        . "<td>" . $record->ticket_id . "</td>"
                        . "<td>" . $record->catname . "</td>"
                        . "<td>" . $record->trxIDEmail. "</td>"
                        . "<td>" . $record->priority_name . "</td>"
                        . "<td>" . $record->status_name . "</td>"
                        . "<td>" . $record->cso_name . "</td>"
						. "<td>" . (($record->status_name != "New") ? ($record->esc_name) : "") . "</td>"
                        . "<td>" . $datetime . "</td>"
						. "<td><a class='btn detailTicketButton' href='#detailTicketModal' id='detailTicket_" . $record->ticket_id . "' data-toggle='modal' onclick='showDetailTicket(" . $record->ticket_id . ")'>Detail</a></td>"
                        . "</tr>";
                }
            ?>
            </tbody>
        </table>
        </div>
        <div id='ticketContentHidden' class="hide">
        	<?php
              foreach ($tickets as $record){
			  	echo "<div id='ticket_content" . $record->ticket_id . "'>" . $record->ticket_content . "</div>";
				echo "<div id='ticket_response" . $record->ticket_id . "'>" . str_replace("\\n", "", $record->ticket_response) . "</div>";
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
					"bFilter":false,
					"aaSorting": [ [7,'desc'] ]
				});
				CKEDITOR.replace('txt-request', 
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
						$("#ticket_categories").html("<option value='' disabled selected>- Select -</option>" + message);
					}).fail(function(){
					
					});
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_ticketpriorities'
					}).done(function(message){
						$("#ticket_priorities").html(message);
					}).fail(function(){
					
					});
				$("#ticket_categories").bind('change', function(){
					$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_ticketfields',
						data: 'cat_id=' + $(this).val()
					}).done(function(message){
						message = message.split("\n").join(" ");
						CKEDITOR.instances['txt-ticket'].setData(message);
					}).fail(function(){
					
					});
				});
				$("#btnFilterTicket").click(function(){
					var startDate = $("#startDate").datepicker('getDate');
					var endDate = $("#endDate").datepicker('getDate');
					window.location.href = "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/view_tickets/<?php echo $userid; ?>/" + startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getDate() + "/" + endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getDate() + '/' + $("#ticket_status").val() + "/" + $("#ticket_priorities").val() + "/" + $("#ticket_categories").val();
				});
				});
				function showDetailTicket(ticketID){
					$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/ajax_cso_detail_ticket',
						data: "ticket_id=" + ticketID
					}).done(function(message){
						$("#detailTicketModalBody").html(message 
							+ "<br /><div><b>Note:</b><br />" + $("#ticket_content" + ticketID).html() + "</div>"
							+ "<br /><div><b>Response:</b><br />" + $("#ticket_response" + ticketID).html() + "</div>"
							);
					}).fail(function(){
						
					});
					}
        </script>
    </body>
</html>