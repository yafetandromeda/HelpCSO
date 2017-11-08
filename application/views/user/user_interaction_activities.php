<?php
// M041 - YA - menghapus activity info pada ticket template
// M042 - YA - konfirmasi penambahan tiket baru
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_interaction/form/" . $interaction_id; ?>'>Interaction</a>
    <span class='user-page-subtitle'>- activities</span>    
    <div class='pull-right'>
      <div class="btn-group">
    	<a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_interaction/form/" . $interaction_id; ?>'>Detail</a>    
	    <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_interaction/activities/" . $interaction_id; ?>'>Activities</a>
	    <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_interaction/attachments/" . $interaction_id; ?>'>Attachments</a>
       </div>
    </div>
</div>
<div class='user-page-content'>
	<?php if ($interaction_status_id != 2 && $interaction_status_id != 5 && $userid == (isset($creator_id) ? $creator_id : $userid)){ ?>
	<a class="btn btn-success pull-right" id='btn-new-activity' href='#modal-activity' data-toggle='modal'>
    	<span class='glyphicon glyphicon-plus'> </span> New Activity
    </a>
    <br />
    <?php } ?>
	<table class='table' id='tbl-interaction-activities'>
    	<tr>
        	<th>Activity Code</th>
            <th>Issue Type</th>
            <th>Activity Status</th>
            <th>Issue Group</th>
            <th>Issue Sub Group</th>
            <th>Issue Description</th>
            <th>Start Date</th>
            <th>Solved Date</th>
            <th>Delete</th>
            <th>Ticket</th>
        </tr>
        <?php
			foreach($activities as $record){
				$act = array();
				$actlevel = $record->activity_level;
				$act[$record->activity_level]['description'] = $record->activity_description;
				$act[$record->activity_level]['code'] = $record->activity_description;
				$act[$record->activity_level]['id'] = $record->activity_id;
				
				while ($actlevel > 1){
					$parent = get_activity_parent($act[$actlevel]['id']);
					$actlevel -= 1;
					$act[$actlevel]['description'] = $parent['description'];
					$act[$actlevel]['code'] = $parent['code'];
					$act[$actlevel]['id'] = $parent['id'];				
				}
				echo "<tr>"
				   . "<td>" . $record->activity_code . "</td>"
				   . "<td>" . $act[1]['description'] . "</td>"
				   . "<td>" . $record->interaction_activity_status_name . "</td>"
				   . "<td>" . (isset($act[2]) ? $act[2]['description'] : "") . "</td>"
				   . "<td>" . (isset($act[3]) ? $act[3]['description'] : "") . "</td>"				   				   
				   . "<td>" . (isset($act[4]) ? $act[4]['description'] : "") . "</td>"
				   . "<td>" . date("m/d/Y H:i:s", strtotime($record->start_datetime)) . "</td>";
				if ($record->closed_datetime == "" && $userid == (isset($creator_id) ? $creator_id : $userid)){
					echo "<td>" 
							. "<a href='" . base_url() 
							. "index.php/user/ctr_interaction/activity_action/close?interaction_id=" 
							. $interaction_id . "&interaction_activity_id=" 
							. $record->interaction_activity_id 
							. "' class='btn btn-warning'>Solve</a>" 
						. "</td>";
					echo "<td>" 
							. "<a href='" . base_url() 
							. "index.php/user/ctr_interaction/activity_action/delete?interaction_id=" 
							. $interaction_id . "&interaction_activity_id=" 
							. $record->interaction_activity_id 
							. "' class='btn btn-danger'>Delete</a>" 
						. "</td>";	
				}
				else {
					echo "<td>" . date("m/d/Y H:i:s", strtotime($record->closed_datetime)) . "</td><td></td>";
				}
				// M041 M042
				if ($userid == (isset($creator_id) ? $creator_id : $userid) && $act[1]['description'] != "Type|INFO"){
				echo "<td>" 
							. "<a href='" . base_url() 
				   			. "index.php/user/ctr_ticket/form?intact_id=" . $record->interaction_activity_id . "&activity_id=" . $record->activity_id . "' class='btn btn-primary' onclick='return validateTicket()'>New Ticket</a>" 
						. "</td>";
				}
				// M041 M042
			}
		?>
    </table>
</div>
<?php $this->load->view("user/user_modal_activity", array("action" => base_url() . "index.php/user/ctr_interaction/activity_action/add", "activity_type" => $activity_type)); ?>
<script type="text/javascript">
// init
$("#navbarItemInteraction").attr('class', 'active');
function validateTicket(href){
			job=confirm("Are you sure to create new ticket?");
		    if(job!=true)
		    {
		        return false;
		    }
		}
</script>