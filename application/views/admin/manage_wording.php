<?php
	$userid = $this->session->userdata('user_id');
	$username = $this->session->userdata('user_name');
?>
<!DOCTYPE html>
<html>
<head>
<title>User Management</title>
<link rel="stylesheet" type="text/css" />
<script src="<?php echo base_url(); ?>tools/jq/jquery-1.8.1.js"></script>    
<script type="text/javascript" src="<?php echo base_url(); ?>assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#buttonSave").bind('click', function(){
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>index.php/ctr_helpcso_wording/save_wording',
				data: "announcement=" + escape(CKEDITOR.instances['txt-announcement'].getData()) 
					+ "&greetings=" + escape(CKEDITOR.instances['txt-greetings'].getData()) 
					+ "&reconfirmation=" + escape(CKEDITOR.instances['txt-reconfirmation'].getData()) 
					+ "&closing=" + escape(CKEDITOR.instances['txt-closing'].getData()) 
					+ "&user_id=" + "<?php echo $this->session->userdata('session_user_id'); ?>"
			}).done(function(message){
				alert("Changes saved");
			}).fail(function(){
				alert("Sorry, an error occcured. Please try again.");
			});
		});
	});	
	window.onload = function(){
				CKEDITOR.replace('txt-announcement', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] }
					]
					}
				);
				CKEDITOR.replace('txt-greetings', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] }
					]
					}
				);
				CKEDITOR.replace('txt-reconfirmation', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] }
					]
					}
				);
				CKEDITOR.replace('txt-closing', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] }
					]
					}
				);
			}

	
</script>
</head>
<body>
<div id="cso-cso-header">
 	<h3>Others  <button class="btn btn-primary" id="buttonSave"><i class="icon-ok icon-white"> </i> Save</button></h3>
</div>
<div class="alert alert-info alert-block">
    <h4>Announcement</h4>
    <textarea id="txt-announcement" name="txt-announcement"><?php echo $current_announcement; ?></textarea>
    <div id="last_announcement">
    	<div id="search_list_user" style="margin: 1% 0px;">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
				<th>Wording</th>
				<th>Created By</th>
                <th>Date Time</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($announcement as $p):
				 echo "<tr>";
				 echo "<td>".$p->wording_content."</td>";
				 echo "<td>".$p->username."</td>";
				 echo "<td>".$p->creator_datetime."</td>";
			  endforeach;
		?>
		</tbody>
	</table>
    
</div>

    </div>
</div>
<div class="well">
    <h4>Greetings</h4>
    <textarea id="txt-greetings"><?php echo $greetings; ?></textarea>
</div>
<div class="well">
    <h4>Reconfirmation</h4>
    <textarea id="txt-reconfirmation"><?php echo $reconfirmation; ?></textarea>
</div>
<div class="well">
    <h4>Closing</h4>
    <textarea id="txt-closing"><?php echo $closing; ?></textarea>
</div>
</body>
</html>
