<?php
// MD01 - YA - Advance user profile, untuk mengatur field yang muncul hanya di user tertentu saja, atau hal – hal yang bisa dijadikan default terhadap user tersebut
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>	
</head>

<body>
<h4>User Group Management Field</h4>
<div id="search_suggestion"></div>
<form method="post" action="<?php echo base_url(); ?>index.php/admin/ctr_manage_user/update_user_group_field" name="form_user_group_field" id="form_user_group_field">
<div id="search_list_group" style="margin: 1% 0px;">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
				<th width="100px">User Group Number</th>
                <th style="display:none">User Group ID</th>
				<th>User Group Name</th>
				<th width="50px">Queue Number</th>
				<th width="50px">Planned Start Date</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($data_group as $p):
				 $number = $number + 1;
				echo "<tr>";
				echo "<td>".$number."</td>";
				echo "<td style='display:none'><input name='id_user' id='id_user' value='".$p->id."'</td>";
				echo "<td>".$p->group_name."</td>";
				if ($p->queue_number == 1){
					echo "<td><input type='checkbox' name='queue_number[" . ($number - 1) ."]' id='queue_number[" . ($number - 1) ."]' checked='checked'/></td>";
				}else{
					echo "<td><input type='checkbox' name='queue_number[" . ($number - 1) ."]' id='queue_number[" . ($number - 1) ."]'/></td>";
				}
				if ($p->planned_start_date == 1){	
					echo "<td ><input type='checkbox' name='planned_start_date[" . ($number - 1) ."]' id='planned_start_date[" . ($number - 1) ."]' checked='checked' /></td>";
				}else{
					echo "<td ><input type='checkbox' name='planned_start_date[" . ($number - 1) ."]' id='planned_start_date[" . ($number - 1) ."]'/></td>";
				}
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
	<button class="btn btn-primary" type="submit" id="btn-save" onclick="return validate_update();"><span class='glyphicon glyphicon-floppy-saved'> </span> Save</button>
</form>
</body>

<script type="text/javascript">
// 	function validate_update(){
// 		var id_user = document.getElementById('id_user').value;	
// 		var queue_number = document.getElementById('queue_number').value;
// 		// var planned_start_date = document.getElementById('planned_start_date').value;
// 							$.ajax({
// 								type: 'POST',
// 								url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_user/update_user_group_field',
// 								data: "id=" + id_user + "queue_number=" + queue_number
// 							}).done(function(message){
// 								alert("Activity has been edited successfully");
// 								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user/";
// 							}).fail(function(){
// 								alert("Sorry, an error occcured. Please try again.");
// 								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user/";
// 							});
// 						}
// 					}
// }
</script>
</html>
