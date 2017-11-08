<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<!DOCTYPE html>
<html>
	<head>
    	<title>HelpCSO - Customer Service Page</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/cso-style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/esc-style.css" />
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/icon/favicon.ico">
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
        <script type="text/javascript">
		$(document).ready(function() {
			$('#tbl-tickets').dataTable({
				"sPaginationType":"full_numbers",
				"bJQueryUI":true,
				"bFilter":false	,
				"aaSorting": [ [7,'desc'] ]
			});
		});
        </script>
    </head>
    <body>
    	<div id="" class="container">
        	<div id="cso-cso-header">
            	<h3>HelpCSO Escalation Team Page</h3>
            </div>
        	<div class="navbar" id='cso-cso-navbar'>
            	<div class="navbar-inner">
                	<ul class="nav">
                    	<li><a href="<?php echo base_url(); ?>index.php/ctr_helpcso_escalation">Home</a></li>
                        <li class="dropdown">
                        	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="icon-user"> </i> <?php echo $this->session->userdata('session_user_name'); ?>
                            <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                              <li><a href="#changePasswordModal" data-toggle="modal">Change Password</a></li>
                              <li><a href="<?php echo site_url('ctr_helpcso_login/logout'); ?>">Logout</a></li>
                            </ul>
                        </li>
                     </ul>
                </div>
            </div>
            <div><?php $this->load->view($filename, $data); ?></div>
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
    </body>
</html>