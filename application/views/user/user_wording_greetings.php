<div class="well alert alert-block wording">
    <h4><strong>Greetings</strong></h4>
    <div id="greetings"></div>
</div>
<script type='text/javascript'>
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_wording/greetings'
		}).done(function(message){
			$("#greetings").html(message);
		}).fail(function(){
		});
</script>