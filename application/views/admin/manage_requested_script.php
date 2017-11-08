<?php
date_default_timezone_set("Asia/Jakarta");
?>
<html>
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
		});
			
		function filter_date(){
				var flag = 0;

				var errtxt = 'ERROR :\n';
				
				var startDate = $("#startDate").datepicker('getDate');
				var text_startDate = document.getElementById('startDate').value;
				if(text_startDate == '') {
					errtxt = errtxt + '-. Start Date still empty\n';	
					flag = 1; 
				}
				var endDate = $("#endDate").datepicker('getDate');
				var text_endDate = document.getElementById('endDate').value;
				if(text_endDate == '') {
					errtxt = errtxt + '-. End Date still empty\n';	
					flag = 1; 
				}
				
				if(startDate > endDate && text_startDate != '' && text_endDate != '' ) {
					errtxt = errtxt + '-. Date not valid\n';	
					flag = 1; 
				}
				
				if(flag == 1) alert(errtxt);
				else if(flag == 0) {
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_requested_script/search_script_bydate?startDate="+startDate.getFullYear() + "-" + (startDate.getMonth() + 1) + "-" + startDate.getDate()+"&endDate="+endDate.getFullYear() + "-" + (endDate.getMonth() + 1) + "-" + endDate.getDate();	
				}
		}
</script>
<body>
<div style="font-weight:bold;font-size:18px;">Script Request</div>
<hr>
<label for="startDate">Date</label>
<input type="text" name="startDate" id="startDate" /> To <input type="text" name="endDate" id="endDate" /> 
<input type="button" class='btn btn-primary' name="filter_date" id="filter_date"  value="Filter Date" onClick="filter_date()" />
<form id="form1" name="form1" method="post">
<div id="search_script_user" style="margin: 1% 0px;">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Request Number</th>
				<th style="display:none">Request ID</th>
				<th>Note</th>
				<th>Request from User</th>
                <th>Request Date</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($requested_script as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'><a href='".base_url()."index.php/admin/ctr_manage_requested_script/form_manage_requested_script?request_id=".$p->request_id."'>".$p->request_id."</a></td>";
				 echo "<td>".$p->note."</td>";
				 echo "<td>".$p->user_name."</td>";
				 echo "<td>".date('m/d/Y',strtotime($p->request_date))."</td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
</form>
</body>
</html>