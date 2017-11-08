<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<!DOCTYPE html>
<html>
	<head>
    	<title>HelpCSO - Script Page</title>
        <link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/icon/favicon.ico">
		<link rel="stylesheet" type="text/css" />
        <style type="text/css">
			@import "<?php echo base_url(); ?>tools/datatables/media/css/demo_table_jui.css";
			@import "<?php echo base_url(); ?>tools/datatables/media/themes/smoothness/jquery-ui.css";
			@import "<?php echo base_url(); ?>assets/css/bootstrap.css";
			@import "<?php echo base_url(); ?>assets/css/datepicker.css";
			@import "<?php echo base_url(); ?>tools/admin-style.css";
			@import "<?php echo base_url(); ?>assets/css/cso-style.css";
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
			$('#tablecsodata').dataTable({
				"sPaginationType":"full_numbers",
				"bJQueryUI":true,
				"bFilter":false,
				"bDestroy": false
				});
		});
		
		</script>
    </head>
    <body>
    	<div class="container">
        	<h3>HelpCSO - Script Page</h3>
        </div>
		<?php if($level == '1') { ?>
		<div class="control-group cso-form-row" align="right">
            <input type="button" class="btn btn-danger" value="Back To Admin Page" id="back" onClick="back_to_admin_page()">
		</div>
		<?php } ?>
    	<div id="" class="container">
            <div class="well">
            	<h4>Greetings</h4>
                <div id="greetings"></div>
            </div>
            <div class="well alert-block">
            	<h4>Scripts</h4>
            	<form id="form_search_script" name="form_search_script" 
                	class="form-horizontal" method="get" 
                    action="<?php echo base_url(); ?>index.php/user/ctr_view_list_script/search_script">
                    <div id='div_category'>
                    	<label for="category1" id="category_label">Category</label>
		                <select name="category1" id="category1" class="combobox_category" data-level='1'>
    		            </select>
                    </div>
                    <label for="text_search" id="keyword_label">Keyword</label>
        	        <input type="search" placeholder='' id="text_search" name="text_search" />
					<input type="hidden" name="category_id" id="category_id" value="" />
            	    <button type="submit" class="btn btn-primary" id="btn-search">Search</button>
                </form>
                <div id="cso_search_suggestion"></div>
				<div id='cso-content'><?php $this->load->view($filename, $data); ?></div>
            </div>
            <div class="well">
            	<h4>Reconfirmation</h4>
                <div id="reconfirmation"></div>
            </div>
            <div class="well">
            	<h4>Closing</h4>
                <div id="closing"></div>
            </div>
        </div>
        <script>
			$(document).ready(function(){
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_wording/greetings'
					}).done(function(message){
						$("#greetings").html(message);
					}).fail(function(){
					});
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_wording/reconfirmation'
					}).done(function(message){
						$("#reconfirmation").html(message);
					}).fail(function(){
					});
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_wording/closing'
					}).done(function(message){
						$("#closing").html(message);
					}).fail(function(){
					});
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_category/1'
					}).done(function(message){
						$("#category1").html("<option value=''>-- All Categories --</option>" + message);
					}).fail(function(){
					
					});
				$("#text_search").bind('keyup', function(){
					perform_search();
				});
				$(".combobox_category").live('change', function(){
					category_id = $(this).val();
					level = parseInt($(this).attr('data-level')) + 1;
					level2 = level + 1;
					$("select.combobox_category[data-level=" + level + "]").remove();
					$("select.combobox_category[data-level=" + level2 + "]").remove();
					$("#category_id").val(category_id);
					if (category_id != ''){
						$.ajax({
							type: 'POST',
							url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_category/' 
								+ level + '/' + category_id
						}).done(function(message){
							var str;
							if (message != ''){
								str = "<select class='combobox_category' id='category" + level + "' data-level='" + level + "'><option value=''>-- All Categories --</option>" + message + "</select>";
								$("#div_category").append(str);
							}
						}).fail(function(){
						
						});
					}
				});
				function perform_search(){
					category_id = $("#category_id").val();
					keyword = $("#text_search").val();
					$.ajax({
						type: "GET",
						url: "<?php echo base_url(); ?>index.php/user/ctr_view_list_script/search_script_suggestion",
						data: "category_id=" + category_id + "&text_search_suggestion=" + keyword
					}).done(function(message){
						$("#cso_search_suggestion").html("<div><h5>Search Result: </h5></div>" + message);
						$("#cso-content").addClass('hidden');
						$(".suggestion_item").on('click', function(){
							var id = $(this).attr('id');
							var script_id = id.split('_')[1];
							window.location.href = "<?php echo base_url(); ?>index.php/user/ctr_view_list_script/view_script/" + script_id;
						});
					}).fail(function(message){
						alert("Sorry, an error occured. Please try again.");
					});			
				}			
			});	
			function back_to_admin_page(){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_home_admin";
				}
        </script>
    </body>
</html>