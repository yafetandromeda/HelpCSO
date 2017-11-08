<?php
	$userid = $this->session->userdata('user_id');
	$username = $this->session->userdata('user_name');
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#buttonSave").bind('click', function(){
			txt = CKEDITOR.instances['txt-closing'].getData();
			if (txt != ""){
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/ctr_helpcso_wording/save_wording',
					data: "closing=" + escape(txt) 
						+ "&user_id=" + "<?php echo $this->session->userdata('session_user_id'); ?>"
				}).done(function(message){
					alert("Change saved");			
					window.location.href = '<?php echo base_url(); ?>index.php/ctr_helpcso_wording/general_script/closing';
				}).fail(function(){
					alert("Sorry, an error occcured. Please try again.");
				});
			}
			else alert("Please fill the closing script");
		});
	});	
	window.onload = function(){
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
<div id="cso-cso-header">
 	<h3>Closing  <button class="btn btn-primary" id="buttonSave"><i class="icon-ok icon-white"> </i> Save</button></h3>
</div>
<textarea id="txt-closing"><?php echo $closing; ?></textarea>
