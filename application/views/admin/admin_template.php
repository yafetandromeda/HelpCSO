<!--M002-->
<!DOCTYPE html>
<html>
	<head>
    	<title>HelpCSO Administrator Page</title>
    </head>
    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/icon/favicon.ico">
	<link rel="stylesheet" type="text/css" />
	<style type="text/css">
			@import "<?php echo base_url(); ?>tools/datatables/media/css/demo_table_jui.css";
			@import "<?php echo base_url(); ?>tools/datatables/media/themes/smoothness/jquery-ui.css";
			@import "<?php echo base_url(); ?>assets/css/bootstrap.css";
			@import "<?php echo base_url(); ?>assets/css/datepicker.css";
			@import "<?php echo base_url(); ?>tools/admin-style.css";
	</style>
	<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery-1.8.3.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery.dataTables.columnFilter.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.ui.datepicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		var level_user = document.getElementById('level_user').value;
		if (level_user != 1)  location.href = "<?php echo site_url('ctr_helpcso_login/logout');?>"; 
		
		$('#tabledata').dataTable({
			"sPaginationType":"full_numbers",
			"bJQueryUI":true,
			"bFilter":false	
		});
		$('#modal_add_script').on('show', function () {
			$("#text-question").val("");
			$("#text-tag").val("");
			CKEDITOR.instances['text-answer'].setData("");
		});
		$('#modal_add_user').on('show', function () {
			$("#text-username").val("");
			$("#text-password").val("");
			$("#text-password_confirmation").val("");										
		});
		$('#modal_add_category').on('show', function () {
			$("#catname").val("");
		});
		$('#form_new_fields').on('show', function () {
			$("#fieldID").val("");
			$("#fieldName").val("");
		});
	});
	
	</script>
    <body id='cso-admin-body'>
    	 <input type="hidden" id="level_user" name="level_user" value="<?php echo $this->session->userdata('session_level'); ?>">
        <div id="cso-admin-header" class='navbar-fixed-top'>HelpCSO Admin Page</div>
    	<div class="container-fluid" id='cso-admin-content'>
        	<div class="row-fluid">
            	<div class="span3">
                	<ul class="nav nav-tabs nav-stacked" id='cso-admin-nav'>
	                    <li><a href="<?php echo base_url() . "index.php/admin/ctr_home_admin"; ?>">Home</a></li>
                        <li><a href="<?php echo site_url('ctr_helpcso_wording/announcement'); ?>">Announcement</a></li>
						<li><a href="<?php echo site_url('ctr_helpcso_wording/general_script'); ?>">General Script</a></li>
						<!--M002-->
     	                <li><a href="<?php echo site_url('admin/ctr_home_admin/script'); ?>">Script</a></li>
     	                <!--M002-->
                        <li><a href="<?php echo base_url().'index.php/admin/ctr_manage_activity/index_activity' ?>">Activity</a></li>
                        <li><a href="<?php echo base_url().'index.php/admin/ctr_manage_ticket_template/index_ticket_template' ?>">Ticket Template</a></li>
                        <li><a href="<?php echo base_url().'index.php/admin/ctr_manage_ticket_template/get_activity_plan' ?>">Activity Plan</a></li>
						<li><a href="<?php echo site_url('ctr_helpcso_escalation/view_report'); ?>">View Escalation Report</a></li>
                        <li><a href="<?php echo site_url('admin/ctr_home_admin/reports'); ?>">Reports</a></li>
                        <li><a href="<?php echo site_url('user/ctr_welcome'); ?>" target="_blank">View CSO Page</a></li>
                        <li><a href="<?php echo site_url('admin/ctr_home_admin/setting'); ?>">Settings</a></li>
						<li><a href="<?php echo site_url('ctr_helpcso_login/logout'); ?>">Logout</a></li>
                    </ul>
                </div>
                <div class="span8">
                	<?php  $this->load->view($filename, $data); ?>
                </div>
            </div>
        </div>

    </body>
   
</html>