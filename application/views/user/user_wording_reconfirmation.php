<div class="well alert alert-block wording">
    <h4><strong>Reconfirmation</strong></h4>
    <div id="reconfirmation"></div>
</div>
<script type='text/javascript'>
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_wording/reconfirmation'
		}).done(function(message){
			$("#reconfirmation").html(message);
		}).fail(function(){
		});
</script>