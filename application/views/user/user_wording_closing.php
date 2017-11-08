<div class="well alert alert-block wording">
    <h4><strong>Closing</strong></h4>
    <div id="closing"></div>
</div>
<script type='text/javascript'>
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_wording/closing'
		}).done(function(message){
			$("#closing").html(message);
		}).fail(function(){
		});
</script>