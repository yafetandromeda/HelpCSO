<!--M004 - YA - admin super akses-->
<!-- M034 - YA - Menampilkan ticket template berdasarkan activity code -->
<!-- MD03 - YA - add information pada ticket, Tambah button interaksi di activity plan untuk create new interaksi -->
<!-- M55 - YA - penambahan SO number pada interaction dan ticket -->
<?php

	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Ticket</a>
    <span class='user-page-subtitle'>- activities</span>    
    <div class='pull-right'>
      <div class='btn-group'>
    	<a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Detail</a>
	    <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_ticket/activities/" . $ticket_id; ?>'>Activity Plan</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/interactions/" . $ticket_id; ?>'> Interactions</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/attachments/" . $ticket_id; ?>'>Attachments</a>
        <!-- <a class="btn btn-danger" href='<?php //echo base_url() . "index.php/user/ctr_ticket/notes/" . $ticket_id; ?>'>Notes</a> -->
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/related/" . $ticket_id; ?>'>Related</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/audit_trail/" . $ticket_id; ?>'>Audit Trail</a>
       </div>
	    
    </div>
</div>
<div class='user-page-content'>
	<form method="post" action="<?php echo base_url(); ?>index.php/user/ctr_ticket/apply_plan">
	<!--M004-->
    	<?php if ($ticket_template_id == "" && $ticket_status == 7 && $level == 3 || $level == 1){ ?>
    <!--M004-->
        <button class="btn btn-success pull-right" id='btn-use-template' onclick="return validateTicketActivities();">
            <div class="glyphicon glyphicon-ok"> </div> Use Template
        </button>
        <?php }?>
        <!-- <br /><br /> -->
        <div class='user-input-group'>
            <label for='cmb-ticket-template'>Ticket Template</label>
        <!--M004-->
            <select name="cmb-ticket-template" id='cmb-ticket-template' class='form-control' <?php if ($ticket_template_id != "" || $level == 2) echo "disabled"; ?>>
        <!--M004-->
                <option value='' disabled="disabled" selected="selected">- Select -</option>
            <?php foreach($ticket_template as $record){
                    echo "<option value='" . $record->ticket_template_id . "' " 
                        . (($ticket_template_id == $record->ticket_template_id) ? "selected" : "") . ">" 
                        . $record->ticket_template_name 
                        . "</option>";
            }?>
            </select>
        </div>
        <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>" id="ticket_id" />
       
        
    </form>
    <br />
	<table class='table bordered' id='tbl-ticket-activities'>
    	<tr>
        	<th>Activity Plan</th>
            <th>Action</th>
            <th>Function</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>Due Date</th>
            <th>Solved Date</th>
            <th>ByPass</th>
            <th>New Interaction</th>
        </tr>
       <tbody id="tbl-ticket-activities-body">
       <?php
       if (isset($ticket_activities)){
	   		foreach ($ticket_activities as $ticket_activity){
	   			// MD03 M55
	   			$interaction = "<a class='btn btn-warning' href='" . base_url() . "index.php/user/ctr_interaction/new_ticket_interaction?ticket_id=".$ticket_id."&customer_name=".$ticket_activity->customer_name."&customer_phone=".$ticket_activity->customer_phone."&customer_email=".$ticket_activity->customer_email."&id_pesanan=".$ticket_activity->id_pesanan."&so_number=".$ticket_activity->so_number."'>Interaction</a>";
	   			// MD03 M55
					if ($ticket_activity->start_datetime == ""){
						$bypass = "";
					//M004
						if ($level == 1 || $level == 3 && $userid == (isset($owner_id) ? $owner_id : "")){
					//M004
							$start = "<a class='btn btn-success' href='" . base_url() . "index.php/user/ctr_ticket/activities_action/start/" . $ticket_activity->ticket_activity_id . "/" . $ticket_id . "'>Start</a>";
							$bypass = "<a class='btn btn-primary' href='" . base_url() . "index.php/user/ctr_ticket/activities_action/bypass/" . $ticket_activity->ticket_activity_id . "/" . $ticket_id . "'>Bypass</a>";
							}
						else $start = "";
						$end = "";
						$due = "";
					}
					else if ($ticket_activity->start_datetime != ""){
						$start = date("m/d/Y H:i:s", strtotime($ticket_activity->start_datetime));
						$due = date("m/d/Y H:i:s", strtotime($ticket_activity->start_datetime) + $ticket_activity->sla * 60 * 60);
						// $bypass = "";
						if ($ticket_activity->closed_datetime == ""){
						//M004
							if ($level == 1 || $level == 3 && $userid == (isset($owner_id) ? $owner_id : "")){
						//M004
								$end = "<a class='btn btn-warning' href='" . base_url() . "index.php/user/ctr_ticket/activities_action/close/" . $ticket_activity->ticket_activity_id . "/" . $ticket_id . "'>Solve</a>";
							}
							else $end = "";
						}
						else $end = date("m/d/Y H:i:s", strtotime($ticket_activity->closed_datetime));
						if ($start == $end){
							$bypass = $start;
						} else{
						$bypass = "-";
						}
					}
					else if ($ticket_activity->start_datetime != "" || $ticket_activity->closed_datetime != ""){
						$start = date("m/d/Y H:i:s", strtotime($ticket_activity->start_datetime));
						$due = date("m/d/Y H:i:s", strtotime($ticket_activity->due_datetime));
						$end = date("m/d/Y H:i:s", strtotime($ticket_activity->end_datetime));
					}
					
				echo "<tr>" 
					. "<td>Activity Plan " . $ticket_activity->plan_order . "</td>"
					. "<td>" . $ticket_activity->action_name . "</td>"
					. "<td>" . $ticket_activity->function_name . "</td>"
					. "<td>" . $ticket_activity->status_name . "</td>"
					. "<td>" . $start . "</td>"
					. "<td>" . $due . "</td>"
					. "<td>" . $end . "</td>"
					. "<td>" . $bypass . "</td>"
					// MD03
					. "<td>" . $interaction . "</td>"
					// MD03
					. "</tr>";
					// echo "<form id='form_note' method='post' action=" . base_url() ."'index.php/user/ctr_ticket/activities_action/close/'" . $ticket_activity->ticket_activity_id . "/" . $ticket_id . ">";
						// if ($ticket_activity->start_datetime != ""){
						// 	if ($ticket_activity->closed_datetime == ""){
						// 		echo "<div class='alert' style='border: 0px none; clear:both;'>
						// 		<tr><th colspan='8'><textarea placeholder='Detail Note' name='txt-ticket-note' id='txt-ticket-note' required></textarea></th></tr>
						// 		</div>";
						// 	}
						// }
					// echo "</form>";
			}
	   }
	   ?>
       </tbody>
    </table>
</div>
<script type="text/javascript">
$("#navbarItemTicket").attr('class', 'active');
$("#cmb-ticket-template").bind("change", function(){
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url(); ?>index.php/user/ctr_ticket/ajax_activity_plans/' + $(this).val()
	}).done(function(message){
		$("#tbl-ticket-activities-body").html(message);
	}).fail(function(){
	
	});
});
function validateTicketActivities(){
	if (document.getElementById('cmb-ticket-template').selectedIndex == 0){
		alert("Please select a template first.");
		return false;
		}
	else return confirm("Are you sure to use this template?");
}

CKEDITOR.replace('txt-ticket-note', 
	{
	toolbarGroups: [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	]
	}
);

CKEDITOR.instances['txt-ticket-note'].setData(document.getElementById('hdn-ticket-note').innerHTML);

</script>