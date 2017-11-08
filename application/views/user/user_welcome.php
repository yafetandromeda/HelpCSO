<!-- M024 - YA - Notification Recovering Ticket-->
<!-- M64 - YA - Perombakan notification -->
<?php 
	$usergroupid = $this->session->userdata('session_user_group_id');
if ($this->session->userdata('session_level') == 1) { ?>
<input type="button" class="btn btn-danger" value="Back To Admin Page" id="back" onClick="back_to_admin_page()">
<?php } ?>
<div class="alert alert-success alert-block">
    <h4><strong>Notifications</strong></h4>
    <div id="notifications"><hr>
    <?php 
    // M64
	echo "<p style='color:#167433;font-weight:bold;'>My Interactions</p><hr>";
    echo "<div class='notifbox'>";
		if ($num_interaction_open > 0){
			echo "<div class='notif1'> Draft <br><br class='clearfloat'>
			<a class='countnotif1' href='" . base_url() . "index.php/user/ctr_interaction/showlist/" . $this->session->userdata("session_user_id") . "?interaction_status=DRAFT'>" . $num_interaction_open . "</a></div>";
		}else{
			echo "<div class='notif1'> Draft <br><br class='clearfloat'>
			<div class='countnotif1'>0</div></div>";
		}
		if ($num_interaction_scheduled > 0){
			echo "<div class='notif2'> Scheduled <br><br class='clearfloat'>
			<a class='countnotif2' href='" . base_url() . "index.php/user/ctr_interaction/showlist/" . $this->session->userdata("session_user_id") . "?interaction_status=SCHEDULED'>" . $num_interaction_scheduled . "</a></div>";
		}else{
			echo "<div class='notif2'> Scheduled <br><br class='clearfloat'>
			<div class='countnotif2'>0</div></div>";
		}
		if ($num_interaction_over > 0){
			echo "<div class='notif3'> Over<br>Scheduled <br class='clearfloat'>
			<a class='countnotif3' href='" . base_url() . "index.php/user/ctr_interaction/showlist/" . $this->session->userdata("session_user_id") . "?interaction_status=OVER'>" . $num_interaction_over . "</a></div>";
		}else{
			echo "<div class='notif3'> Over<br>Scheduled <br class='clearfloat'>
			<div class='countnotif3'>0</div></div>";
		}
		if ($num_interaction_inprogress > 0){
			echo "<div class='notif4'> In Progress <br><br class='clearfloat'>
			<a class='countnotif4' href='" . base_url() . "index.php/user/ctr_interaction/showlist/" . $this->session->userdata("session_user_id") . "?interaction_status=IN%20PROGRESS'> " . $num_interaction_inprogress . "</a></div>";
		}else{
			echo "<div class='notif4'> In Progress <br><br class='clearfloat'>
			<div class='countnotif4'>0</div></div>";
		}
	echo "</div>";

	echo "<p style='color:#167433;font-weight:bold;'>My Service Requests</p><hr>";

    echo "<div class='notifbox'>";
		
		if ($num_ticket_draft > 0){
			echo "<div class='notif1'> Draft<br><br class='clearfloat'>
			<a class='countnotif1' href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "?ticket_status=DRAFT'>" . $num_ticket_draft . "</a></div>";
		}else{
			echo "<div class='notif1'> Draft<br><br class='clearfloat'>
			<div class='countnotif1'>0</div></div>";
		}
		if ($num_ticket_draftover > 0){
			echo "<div class='notif2'> Draft<br>Over SLA <br class='clearfloat'>
			<a class='countnotif2' href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "?ticket_status=DRAFT&&sla=OVER%20SLA'>" . $num_ticket_draftover . "</a></div>";
		}else{
			echo "<div class='notif2'> Draft<br>Over SLA<br class='clearfloat'>
			<div class='countnotif2'>0</div></div>";
		}
		if ($num_ticket_open > 0){
			echo "<div class='notif3'> Open<br><br class='clearfloat'>
			<a class='countnotif3' href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "?ticket_status=OPEN'>" . $num_ticket_open . "</a></div>";
		}else{
			echo "<div class='notif3'> Open<br><br class='clearfloat'>
			<div class='countnotif3'>0</div></div>";
		}
		if ($num_ticket_openover > 0){
			echo "<div class='notif4'> Open<br>Over SLA<br class='clearfloat'>
			<a class='countnotif4' href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "?ticket_status=OPEN&&sla=OVER%20SLA'>" . $num_ticket_openover . "</a></div>";
		}else{
			echo "<div class='notif4'> Open<br>Over SLA<br class='clearfloat'>
			<div class='countnotif4'>0</div></div>";
		}
		if ($num_ticket_over > 0){
			echo "<div class='notif5'> Over SLA<br><br class='clearfloat'>
			<a class='countnotif5' href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "?ticket_status=OVER%20SLA'>" . $num_ticket_over . "</a></div>";
		}else{
			echo "<div class='notif5'> Over SLA<br><br class='clearfloat'>
			<div class='countnotif5'>0</div></div>";
		}
		if ($num_ticket_group > 0 && $this->session->userdata('session_level') == 3){
			echo "<div class='notif6'> Group Open<br><br class='clearfloat'>
			<a class='countnotif6' href='" . base_url() . "index.php/user/ctr_ticket/showlist?ticket_status=GROUP'>" . $num_ticket_group . "</a></div>";
		}else{
			echo "<div class='notif6'> Group Open<br><br class='clearfloat'>
			<div class='countnotif6'>0</div></div>";
		}
		// M024
		if ($num_ticket_recovering > 0 && $usergroupid==10){
			echo "<div class='notif7'> Recovering<br><br class='clearfloat'>
			<a class='countnotif7' href='" . base_url() . "index.php/user/ctr_ticket/showlist?ticket_status=RECOVERING'>" . $num_ticket_recovering . "</a></div>";
		}else{
			echo "<div class='notif7'> Recovering<br><br class='clearfloat'>
			<div class='countnotif7'>0</div></div>";
		}
		if ($num_ticket_inprogress > 0){
			echo "<div class='notif8'> In Progress<br><br class='clearfloat'>
			<a class='countnotif8' href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "?ticket_status=IN%20PROGRESS'>" . $num_ticket_inprogress . "</a></div>";
		}else{
			echo "<div class='notif8'> In Progress<br><br class='clearfloat'>
			<div class='countnotif8'>0</div></div>";
		}
		if ($num_ticket_inprogressover > 0){
			echo "<div class='notif9'> In Progress<br>Over SLA <br class='clearfloat'>
			<a class='countnotif9' href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "?ticket_status=IN%20PROGRESS&&sla=OVER%SLA'>" . $num_ticket_inprogressover . "</a></div>";
		}else{
			echo "<div class='notif9'> In Progress<br>Over SLA<br class='clearfloat'>
			<div class='countnotif9'>0</div></div>";
		}
		if ($num_ticket_openhigh > 0){
			echo "<div class='notif10'> Open<br>High Priority <br class='clearfloat'>
			<a class='countnotif10' href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "?ticket_status=OPEN&&ticket_priority=HIGH'>" . $num_ticket_openhigh . "</a></div>";
		}else{
			echo "<div class='notif10'> Open<br>High Priority <br class='clearfloat'>
			<div class='countnotif10'>0</div></div>";
		}
		if ($num_ticket_inprogresshigh > 0){
			echo "<div class='notif10'> In Progress<br>High Priority <br class='clearfloat'>
			<a class='countnotif10' href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "?ticket_status=IN%20PROGRESS&&ticket_priority=HIGH'>" . $num_ticket_inprogresshigh . "</a></div>";
		}else{
			echo "<div class='notif10'> In Progress<br>High Priority <br class='clearfloat'>
			<div class='countnotif10'>0</div></div>";
		}
	echo "</div>";
	// M64
		// M024
		/*
		if ($num_interaction > 0)
			echo "<a href='" . base_url() . "index.php/user/ctr_interaction/showlist/" . $this->session->userdata("session_user_id") . "'>Anda memiliki " . $num_interaction . " open interaction.</a>";
		if ($num_ticket_overdue > 0)
			$ext = ", " . $num_ticket . " ticket diantaranya telah lewat dari deadline";
		else $ext = ".";
		if ($num_ticket > 0)
			echo "<a href='" . base_url() . "index.php/user/ctr_ticket/showlist/" . $this->session->userdata("session_user_id") . "'>Anda masih memiliki " . $num_ticket . " ticket" . $ext . "</a><br />";
			*/
	?>
    </div>
</div>
<div class="alert alert-info alert-block">
    <h4><strong>Announcement</strong></h4>
    <div id="announcement"></div>
</div>
    
<script type="text/javascript">
$("#navbarItemHome").attr('class', 'active');
$.ajax({
	type: 'POST',
	url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_wording/announcement'
	}).done(function(message){
		$("#announcement").html(message);
	}).fail(function(){
	});

function back_to_admin_page(){
	location.href = "<?php echo base_url();?>index.php/admin/ctr_home_admin";
	}
</script>
</div>
