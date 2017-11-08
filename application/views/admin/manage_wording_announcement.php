<?php
	$userid = $this->session->userdata('user_id');
	$username = $this->session->userdata('user_name');
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#buttonSave").bind('click', function(){
			txt = CKEDITOR.instances['txt-announcement'].getData();
			if (txt != ""){
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/ctr_helpcso_wording/save_wording',
					data: "announcement=" + escape(txt) 
						+ "&user_id=" + "<?php echo $this->session->userdata('session_user_id'); ?>"
				}).done(function(message){
					alert("Change saved");			
					window.location.href = '<?php echo base_url(); ?>index.php/ctr_helpcso_wording/announcement';
				}).fail(function(){
					alert("Sorry, an error occcured. Please try again.");
				});
			}
			else alert("Please fill the announcement");
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
			}

	
</script>
<div id="cso-cso-header">
 	<h3>Announcement <button class="btn btn-primary" id="buttonSave"><i class="icon-ok icon-white"> </i> Save</button></h3>
</div>
    <textarea id="txt-announcement" name="txt-announcement"><?php echo $current_announcement; ?></textarea>
    <div id="last_announcement">
    	<div id="search_list_user" style="margin: 1% 0px;">
	<table id="tableannouncement" class="display table table-bordered table-hover">
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
				 echo "<td>".$p->user_name."</td>";
				 echo "<td>".$p->wording_datetime."</td>";
			  endforeach;
		?>
		</tbody>
	</table>
	</div>
  </div>
