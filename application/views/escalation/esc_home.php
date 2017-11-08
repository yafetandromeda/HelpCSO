<div id="tickets">
	<label for="ticket_categories">Category</label>
	<select id="ticket_categories" name="ticket_categories">
    <?php
		echo "<option value='-' selected>- ALL -</option>";
    	foreach($ticketCategory as $category){
			echo "<option value='" . $category->cat_id . "'>" 
				. $category->catname 
				. "</option>";
			}
	?>
    </select><br />
    <label for="ticket_priorities">Priority</label>
	<select id="ticket_priorities" name="ticket_priorities">
    <?php
		echo "<option value='-' selected>- ALL -</option>";
    	foreach($ticketPriority as $priority){
			echo "<option value='" . $priority->priority_id . "'>" 
				. $priority->priority_name 
				. "</option>";
			}
	?>
    </select><br />
	<label for="ticket_status">Status</label>
	<select id="ticket_status" name="ticket_status">
    <?php
		echo "<option value='-' selected>- ALL -</option>";	
    	foreach($ticketStatus as $status){
			if ($status->status_name == "Open") continue;
			echo "<option value='" . $status->status_id . "'>" . $status->status_name . "</option>";
			}
	?>    
    </select><br />
    <label for="startDate">Date</label>
	<input type="text" name="startDate" id="startDate" /> To <input type="text" name="endDate" id="endDate" /> 
    <button type="submit" id="btnFilterTicket" class="btn btn-primary">Filter</button><br />
	<table id="tbl-tickets" class="display table table-hover table-bordered">
      <thead>
    	<tr>
        	<th>Ticket ID</th>
            <th>Category</th>
            <th>ID Pesanan / Email</th>
            <th>Priority</th>
            <th>Status</th>
            <th>CSO</th>
            <th>Staff Eskalasi</th>
            <th>Tanggal</th>
        </tr>
       </thead>
       <tbody>
        <?php
        	foreach ($tickets as $record){
				$datetime = $record->submit_datetime;
				if ($record->status_name == "Handled") $datetime = $record->handled_datetime;
				else if ($record->status_name == "Solved") $datetime = $record->solved_datetime;
				
				echo "<tr id='ticket_" . $record->ticket_id . "' style=\"color:" . $record->priority_color . ";background-color:" . $record->status_color . "\" class='ticket_row' onclick='showEscalationTicket(" . $record->ticket_id . ")'>" 
					. "<td>" . $record->ticket_id . "</td>"
					. "<td>" . $record->catname . "</td>"
					. "<td>" . $record->trxIDEmail . "</td>"
					. "<td>" . $record->priority_name . "</td>"
					. "<td>" . $record->status_name . "</td>"
					. "<td>" . $record->cso_name . "</td>"
					. "<td>" . (($record->status_name != "New") ? ($record->esc_name) : "") . "</td>"
					. "<td>" . $datetime . "</td>"
					. "</tr>";
			}
		?>
        </tbody>
    </table>
    <script type="text/javascript">
		$(document).ready(function(){
			$("#startDate").datepicker({
				dateFormat: "dd M yy",
				defaultDate: 0
			});
			$("#endDate").datepicker({
				dateFormat: "dd M yy",
				defaultDate: 0				
			});
			$("#btnFilterTicket").click(function(){
				var startDate = $("#startDate").datepicker('getDate');
				var endDate = $("#endDate").datepicker('getDate');
				window.location.href = "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/index/" + startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' + startDate.getDate() + "/" + endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' + endDate.getDate() + "/" + $("#ticket_status").val() + "/" + $("#ticket_priorities").val() + "/" + $("#ticket_categories").val();
			});
		});
		function showEscalationTicket(ticket_id){
			window.location.href = "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/detail_ticket/" + ticket_id;
			}
    </script>
</div>