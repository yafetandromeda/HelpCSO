<div id="detail_ticket">
	<?php $record = $ticket[0];?>
    <h3>Detail Ticket</h3>
	<div class="detail_ticket_row">
    	<div class='detail_ticket_field'>Ticket ID</div>
        <div class='detail_ticket_value'><?php echo $record->ticket_id; ?></div>
    </div>
    <div class="detail_ticket_row">
    	<div class='detail_ticket_field'>Category</div>
        <div class='detail_ticket_value'><?php echo $record->catname; ?></div>
    </div>
    <div class="detail_ticket_row">
    	<div class='detail_ticket_field'>Status</div>
        <div class='detail_ticket_value'><?php echo $record->status_name; ?></div>
    </div>
    <div class="detail_ticket_row">
    	<div class='detail_ticket_field'>Priority</div>
        <div class='detail_ticket_value'><?php echo $record->priority_name; ?></div>
    </div>
    <div class="detail_ticket_row">
    	<div class='detail_ticket_field'>CSO Name</div>
        <div class='detail_ticket_value'><?php echo $record->cso_name . " (" . $record->submit_datetime . ")"; ?></div>
    </div>
    <div class="detail_ticket_row">
    	<div class='detail_ticket_field'>ID Pesanan / Email</div>
        <div class='detail_ticket_value'><?php echo $record->trxIDEmail; ?></div>
    </div>
	<?php 
		foreach($ticket_content as $detailrecord){
			echo "<div class='detail_ticket_row'>
					<div class='detail_ticket_field'>" . $detailrecord->fieldName . "</div>
					<div class='detail_ticket_value'>" . $detailrecord->fieldContent . "</div>
 				  </div>";
		}
	?>
    <div class="detail_ticket_row">
    	<div class='detail_ticket_field'>Ticket Content</div>
        <div class='detail_ticket_value'><?php echo $record->ticket_content; ?></div>
    </div>
    <div class="detail_ticket_row">
    	<div class="detail_ticket_field"></div>
        <div class="detail_ticket_value">
	    	<button class="btn btn-primary" id="btn_handled">Handle</button>
            <button class="btn btn-primary handled_field" id="btn_unhandled">Unhandle</button>
        </div>
    </div>
    <div class="detail_ticket_row handled_field">
    	<div class='detail_ticket_field'>Ticket Response</div>
        <div class='detail_ticket_value' id="saved_response"><textarea name="txt-response" id="txt-response"></textarea></div>
    </div>
    <div class="detail_ticket_row handled_field">
    	<div class="detail_ticket_field"></div>
        <div class="detail_ticket_value">
	    	<button class="btn btn-primary solved_field" id="btn_solved">Solved</button>
        </div>
    </div>
    <script type="text/javascript">
    	CKEDITOR.replace('txt-response', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
					]
					}
				);
		 ticket_status = "<?php echo $record->status_name; ?>";
		 esc_name = "<?php echo $record->esc_name; ?>";
		 logged_user = "<?php echo $this->session->userdata('session_user_name'); ?>";
		 if (ticket_status == "New"){
		 	$("#btn_handled").css('display', '');
			$(".handled_field").css('display', 'none');
		 }
		 else if (esc_name == logged_user){
		 	$("#btn_handled").css('display', 'none');
			$(".handled_field").css('display', '');
			if (ticket_status == "Solved" || ticket_status == "Closed"){
				$("#btn_unhandled").css('display', 'none');
				$("#btn_solved").css('display', 'none');
				$("#saved_response").empty().html("<?php echo $record->ticket_response; ?>");
			}
		 }
		 else {
				$("#btn_handled").css('display', 'none');
				$("#btn_unhandled").css('display', 'none');
				$("#btn_solved").css('display', 'none');
				$("#saved_response").empty().html("<?php echo $record->ticket_response; ?>");		 
		 }
		 $("#btn_handled").click(function(){
			 if (confirm("Are you sure to handle this ticket?") == true){
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/handle_ticket',
					data: 'ticket_id=<?php echo $record->ticket_id; ?>'
						+ '&esc_id=<?php echo $this->session->userdata("session_user_id"); ?>'
				}).done(function(){
					$("#btn_handled").css('display', 'none');
					$(".handled_field").css('display', '');
				}).fail(function(){
					alert("Sorry, an error occured. Please try again later.");
				});
			}
		 });
		 $("#btn_unhandled").click(function(){
			 if (confirm("Are you sure to unhandle this ticket?") == true){
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/unhandle_ticket',
					data: 'ticket_id=<?php echo $record->ticket_id; ?>'
						+ '&esc_id=<?php echo $this->session->userdata("session_user_id"); ?>'				
				}).done(function(){
					$("#btn_handled").css('display', '');
					$(".handled_field").css('display', 'none');
				}).fail(function(){
					alert("Sorry, an error occured. Please try again later.");			
				});
			 }
		 });
		 $("#btn_solved").click(function(){
		 	if (confirm("Are you sure you have solved this ticket?") == true){
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/solve_ticket',
					data: 'ticket_id=<?php echo $record->ticket_id; ?>'
						+ '&esc_id=<?php echo $this->session->userdata("session_user_id"); ?>'
						+ '&ticket_response=' + escape(CKEDITOR.instances['txt-response'].getData()) 
				}).done(function(){
					$("#btn_handled").css('display', 'none');
					$(".handled_field").css('display', 'none');
					$(".solved_field").css('display', 'none');
					window.location.href = '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/detail_ticket/<?php echo $record->ticket_id; ?>';
				}).fail(function(){
					alert("Sorry, an error occured. Please try again later.");			
				});
			}
		 });
    </script>
</div>