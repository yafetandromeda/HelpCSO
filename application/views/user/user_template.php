<!-- M004 - YA - admin super akses -->
<!-- M042 - YA - konfirmasi tiket baru -->
<!-- M65 - YA - Related pada ticket ini by automatic fungsi ini akan mencocokan 3 field pada ticket Customer Name & Customer Phone & Customer Email digantikan dengan kombinasi ID Pesanan*(Prioritas) & SO Number & Customer Phone & Customer Email -->
<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<!DOCTYPE html>
<html>
	<head>
    	<title>HelpCSO</title>
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/icon/favicon.ico">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap3.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.theme.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>tools/datatables/media/css/DT_bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/datepicker.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>tools/datatables/media/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/user-style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/cso-style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" />
        
		<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery-1.8.3.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery.dataTables.columnFilter.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap3.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.ui.core.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.ui.datepicker.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/helper.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js"></script>
        
		<script type='text/javascript'>
		$(document).ready(function(){
			CKEDITOR.replace('txt-request', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					]
					}
				);
			$("#submit_request_button").bind('click', function(){
				 var txt_request = escape(CKEDITOR.instances['txt-request'].getData());
				 if (txt_request == ""){
				 	alert("Please enter the request");
					return false;	
				 }
				 else if (confirm("Are you sure to submit this request?") == true){	
					$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/user/ctr_view_list_script/request_script',
						data: "txt-request=" + txt_request
							+ "&user_id=" + "<?php echo $this->session->userdata('session_user_id'); ?>"
					}).done(function(message){
						alert("Your request has been sent successfully");
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						return false;
					});
				  }
				  else return false;
				});
			
			// interaction table
			var interaction_user_id = $("#tbl-interaction-userid").html();
			var interaction_table = $("#tbl-interaction-list").dataTable({
				'bProcessing': true,
				'bServerSide': true,
				'sAjaxSource': '<?php echo base_url(); ?>index.php/user/ctr_interaction/ajaxlist/' + interaction_user_id,
				'aaSorting': [[1,'desc']]
			});
			
			var interaction_status = $("#tbl-interaction-status").html();
			if (interaction_status != null && interaction_status != ""){
				interaction_table.fnFilter( interaction_status, 8);
			}
			$("#tbl-interaction-list tfoot input").keyup( function (e) {
				interaction_table.fnFilter( this.value, $("#tbl-interaction-list tfoot input").index(this));
			});
			
			// ticket table
			var ticket_user_id = $("#tbl-ticket-userid").html();
			var ticket_user_type = $("#tbl-ticket-usertype").html();
			var ticket_status = $.trim($("#tbl-ticket-status").html());
			var sla = $.trim($("#tbl-ticket-sla").html());
			var ticket_priority = $.trim($("#tbl-ticket-priority").html());
			if (ticket_status != null && ticket_status == "GROUP"){
				ticket_user_id = "";
			}
			var ticket_table = $("#tbl-ticket-list").dataTable({
				'bProcessing': true,
				'bServerSide': true,
				'sAjaxSource': '<?php echo base_url(); ?>index.php/user/ctr_ticket/ajaxlist/' + ticket_user_id,
				'aaSorting': [[1,'desc']]
			});
			var ticket_status = $.trim($("#tbl-ticket-status").html());
			if (ticket_status != null && ticket_status != ""){
				if (ticket_status == "GROUP"){
					ticket_table.fnFilter('<?php echo get_group_name($this->session->userdata('session_user_group_id')); ?>', 10);
					ticket_status = "OPEN";
					}
				// M65
				ticket_table.fnFilter( ticket_status, 14);
				ticket_table.fnFilter(sla, 12);
				ticket_table.fnFilter(ticket_priority, 13);
				// M65
			}	
			$("#tbl-ticket-list tfoot input").keyup( function (e) {
				ticket_table.fnFilter( this.value, $("#tbl-ticket-list tfoot input").index(this));
			});
			
			// related ticket table
			var activity_id = $("#hdn_activity_id").html();
			// var ticket_template_id = $("#hdn_ticket_template_id").html();
			// var interaction_id = $("#hdn_interaction_id").html();
			var ticket_id = $("#hdn_ticket_id").html();
			// M65
			var id_pesanan = $("#hdn_id_pesanan").html();
			var so_number = $("#hdn_so_number").html();
			// M65
			var customer_name = $("#hdn_customer_name").html();
			var customer_phone = $("#hdn_customer_phone").html();
			var customer_email = $("#hdn_customer_email").html();						
			var related_ticket_table = $("#tbl-ticket-related").dataTable({
				'bProcessing': true,
				'bServerSide': true,
				// M65
				'sAjaxSource': '<?php echo base_url(); ?>index.php/user/ctr_ticket/ajaxlist?listtype=related&ticket_id=' + ticket_id + '&id_pesanan=' + id_pesanan + '&so_number=' + so_number + '&activity_id=' + activity_id + '&customer_phone=' + customer_phone + '&customer_email=' + customer_email,
				// M65
				'aaSorting': [[1,'desc']]
			});
			
			// audit trail table
			ticket_id = $("#hdn_ticket_id").html();
			var audit_trail_table = $("#tbl-audit-trail").dataTable({
				'bProcessing': true,
				'bServerSide': true,
				'sAjaxSource': '<?php echo base_url(); ?>index.php/user/ctr_ticket/ajaxauditlist?ticket_id=' + ticket_id,
				'aaSorting': [[1,'desc']]
			});
			
			// search script result
			$('#tablecsodata').dataTable({
				"sPaginationType":"full_numbers",
				"bFilter":false	
			});
		});
// M042
		function validateTicket(href){
			job=confirm("Are you sure to create new ticket?");
		    if(job!=true)
		    {
		        return false;
		    }
		}
// M042
        </script>
    </head>
    <body>
    	 <!-- Header --> 
         <div id='header' class="navbar navbar-default navbar-fixed-top">
      		<div class="container">
		        <div class="navbar-header">
        		  <a class="navbar-brand" href="<?php echo base_url(); ?>index.php/user/ctr_welcome"><strong>HelpCSO</strong></a>
		        </div>
        		<div class="navbar-collapse collapse">
		          <ul class="nav navbar-nav">
        		    <li id='navbarItemHome'><a href="<?php echo base_url(); ?>index.php/user/ctr_welcome">Home</a></li>
			        <li id='navbarItemInteraction' class="dropdown">
            		  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Interaction <b class="caret"></b></a>
					<!--M004-->
		              <ul class="dropdown-menu">
        		        <li><a href="<?php echo base_url(); ?>index.php/user/ctr_interaction/form">New Interaction</a></li>
                        <li><a href="<?php echo base_url(); ?>index.php/user/ctr_interaction/showlist/<?php echo $userid; ?>">My Active Interactions</a></li>
		                <li><a href="<?php echo base_url(); ?>index.php/user/ctr_interaction/showlist">All Interactions</a></li>
        		      </ul>
		            </li>
                    <?php if ($level == 2 || $level == 1){ // cso || admin?>
        		    <li id='navbarItemScript' class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Script <b class="caret"></b></a>
        		      <ul class="dropdown-menu">
                		<li><a href="<?php echo base_url(); ?>index.php/user/ctr_home_user">Browse Scripts</a></li>
		                <li><a href="#requestScriptModal" data-toggle="modal">Request Script</a></li>
        		      </ul>
		            </li>
		            <?php } ?>
                    <li id='navbarItemTicket' class="dropdown">
        		      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Ticket <b class="caret"></b></a>
		              <ul class="dropdown-menu">
					<!-- M042 -->
		                <li><a href="<?php echo base_url(); ?>index.php/user/ctr_ticket/form" id="ticket" onclick="return validateTicket()">New Ticket</a></li>
                    <!-- M042 -->
                        <li><a href="<?php echo base_url(); ?>index.php/user/ctr_ticket/showlist/<?php echo $userid; ?>">My Active Tickets</a></li>
        		        <li><a href="<?php echo base_url(); ?>index.php/user/ctr_ticket/showlist">All Tickets</a></li>
		              </ul>
					<!--M004-->
		            </li>
        		  </ul>
		          <ul class="nav navbar-nav navbar-right">
					  <li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      	<span class="glyphicon glyphicon-user"> </span>
                        <?php echo $username; ?>
                        <b class="caret"></b></a>
        		      <ul class="dropdown-menu">
                		<li><a href="#changePasswordModal" data-toggle="modal">Change Password</a></li>
		                <li><a href="<?php echo site_url('ctr_helpcso_login/logout'); ?>">Logout</a></li>
        		      </ul>
		            </li>
        		  </ul>
		        </div>
	       	</div>
        </div>
        <!-- Body -->
        <div id='content' class="container"><?php if ($filename != '') $this->load->view($filename, $data); ?></div>
        
		<!-- Hidden -->
        <!-- Modal -->
         <div id="changePasswordModal" class="modal fade">
         	<div class="modal-dialog">
              <div class="modal-content">
            	<div class="modal-header">
	            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    	        <h3 class="modal-title">Change Password</h3>
        	    </div>
            	<div class="modal-body">
					<?php $this->load->view('change_password', array('message' => '')); ?>
        	    </div>
              </div>
            </div>
        </div>
        <div id="requestScriptModal" class="modal fade">
        	<div class="modal-dialog">
            	<div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Request Script</h3>
                    </div>
                    <div class="modal-body">
                    <form id="form_request_script" name="form_request_script" method="post">
                        <textarea placeholder="Write your request here..." name="txt-request" id="txt-request"></textarea>
                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="submit_request_button" data-dismiss="modal">Submit</button>
                    </div>                
                </div>
            </div>
        </div>
    </body>
</html>