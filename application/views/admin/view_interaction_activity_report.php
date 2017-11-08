<!-- M023 - YA - searching by date -->
<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>List Report Interaction Type</title>
</head>

<script type="text/javascript">
	// window.onload = function(){
	// 	$("#text_Date").datepicker({
	// 		dateFormat: "dd M yy",
	// 			defaultDate: 0
	// 	});
	// }
	window.onload = function(){
		$("#text_startDate").datepicker({
			dateFormat: "dd M yy",
				defaultDate: 0
		});
		$("#text_endDate").datepicker({
			dateFormat: "dd M yy",
				defaultDate: 0
		});
	}

	function search_tanggal() {
	  var creator_id = document.getElementById('creator_id').value;
	  var activity_code = document.getElementById('txt-activity-code').value;
	  var text_startDate  = document.getElementById('text_startDate').value;
	  var text_endDate  = document.getElementById('text_endDate').value; 
	  var user_group = document.getElementById('user_group').value;
	  
	  var startDate = new Date(text_startDate);
	  var endDate = new Date(text_endDate);
	  var today = new Date(Date.now());		  
				  
	  var sday = startDate.getDate();
	  var smonth = (startDate.getMonth()+1);
	  var syear = startDate.getFullYear();
	  var eday = endDate.getDate();
	  var emonth = (endDate.getMonth()+1);
	  var eyear = endDate.getFullYear();
	  var tday = today.getDate();
	  var tmonth = (today.getMonth()+1);
	  var tyear = today.getFullYear();
	  
	  if (sday < 10) sday = '0' + sday;
	  if (smonth < 10) smonth = '0' + smonth;
	  if (eday < 10) eday = '0' + eday;
	  if (emonth < 10) emonth = '0' + emonth;
	  if (tday < 10) tday = '0' + tday;
	  if (tmonth < 10) tmonth = '0' + tmonth;
	  
	  var comp_startDate = syear + '-' + smonth + '-' + sday;
	  var comp_endDate = eyear +'-'+ emonth + '-' + eday;
	  var comp_today = tyear+ '-' + tmonth+  '-'+ tday;

	  startDate = syear+'-'+smonth+'-'+sday;
	  endDate = eyear+'-'+emonth+'-'+eday;
	  today = tyear+'-'+tmonth+'-'+tday;

	  var flag = 0;
	  var flag2 = 0;
	  var flag3 = 0;
	  var flag4 = 0;
	  var flag5 = 0;
	  var flag6 = 0;
	  
	 	  var errtxt = 'ERROR :\n';
	 		  	  
		  if(text_startDate != '' && text_endDate != '') {
						flag2 = 1; 
		  }
		  
		  if (flag2 == 1) {
			  if(text_startDate == '' && text_endDate != '') {
							errtxt = errtxt + '-. Start Date still empty\n';	
							flag = 1; 
							flag3 = 1;
			  }
			  
			  if(text_startDate != '' && text_endDate == '') {
							errtxt = errtxt + '-. End Date still empty\n';	
							flag = 1; 
							flag3 = 1;
			  }
						
			  if(comp_startDate  > comp_endDate) {
						errtxt = errtxt + '-. Date not valid\n';	
						flag = 1; 
						flag3 = 1;
			  } 
			  if(comp_today < comp_startDate) {
							errtxt = errtxt + '-. Start Date cannot exceed today\n';	
							flag = 1; 
							flag3 = 1;
			  } 
			  if(comp_today < comp_endDate) {
							errtxt = errtxt + '-. End Date cannot exceed today\n';	
							flag = 1; 
							flag3 = 1;
			  } 
		  }
		  
		  if(activity_code == '' && flag3 == 0) {
						errtxt = errtxt + '-. Activity Code still empty\n';	
						flag = 1; 
		  }
		  
		  if(flag2 == 0) {
			  errtxt = errtxt + '-. Filter Date still empty\n';	
		  }

		  if (user_group != ''){
		  	flag4 = 1;
		  }

		  if (activity_code != ''){
		  	flag5 = 1;
		  }

		  if (creator_id != ''){
		  	flag6 = 1;
		  }

		  if(flag == 1 && flag2 == 0 && flag4 == 0 && flag6 == 0) alert(errtxt);
		  else if (flag3 == 1) alert(errtxt);
		   else {	
		   		 if(flag == 1 && flag2 == 0 && flag4 == 0 && flag6 == 0) {
		   			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?flag_view=0" 
		   		}
		   		else  if (flag == 0 && flag2 == 0 && flag4 == 0) {			  
				 	location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?activity_code=" + encodeURIComponent(activity_code)  + "&flag_view=2" 
				}
				else if (flag == 1 && flag2 == 1 && flag4 == 0 && flag6 == 0){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?text_startDate=" + startDate + "&text_endDate=" + endDate + "&flag_view=1"
				}
				else if (flag == 0 && flag2 == 1 && flag4 == 0 && flag6 == 0){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?activity_code=" + encodeURIComponent(activity_code) + "&text_startDate=" + startDate + "&text_endDate=" + endDate +"&flag_view=3" 
				}
				else if (flag4 == 1 && flag2 == 0){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?user_group=" + user_group + "&flag_view=4"
				}
				else if (flag4 == 1 && flag2 == 1 && flag5 == 0 && flag6 == 0){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?text_startDate=" + startDate + "&text_endDate=" + endDate + "&user_group=" + user_group + "&flag_view=5"
				}
				else if (flag4 == 1 && flag2 == 1 && flag5 == 1 && flag6 == 0){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?text_startDate=" + startDate + "&text_endDate=" + endDate + "&activity_code=" + encodeURIComponent(activity_code) + "&user_group=" + user_group + "&flag_view=6"
				}
				else if (flag6 == 1 && flag2 == 0){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?creator_id=" + creator_id + "&flag_view=7"
				}
				else if (flag6 == 1 && flag2 == 1 && flag5 == 0){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?text_startDate=" + startDate + "&text_endDate=" + endDate + "&creator_id=" + creator_id + "&flag_view=8"
				}
				else if (flag4 == 0 && flag2 == 1 && flag5 == 1 && flag6 == 1){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?text_startDate=" + startDate + "&text_endDate=" + endDate + "&activity_code=" + encodeURIComponent(activity_code) + "&creator_id=" + creator_id + "&flag_view=9"
				}
			}	
	}

	// function search_tanggal() {
	//   var text_Date  = document.getElementById('text_Date').value;
	//   var activity_code = document.getElementById('activity_code').value;
	  
	//   var date = new Date(text_Date);
	//   var today = new Date(Date.now());		  
				  
	//   var day = date.getDate();
	//   var month = (date.getMonth()+1);
	//   var tday = today.getDate();
	//   var tmonth = (today.getMonth()+1);
	  
	//   if (day < 10) day = '0' + day;
	//   if (month < 10) month = '0' + month;
	//   if (tday < 10) tday = '0' + tday;
	//   if (tmonth < 10) tmonth = '0' + tmonth;
	  
	//   var comp_date = date.getFullYear() +'-'+ month + '-' + day;
	//   var comp_today = today.getFullYear()+ '-' + tmonth+  '-'+ tday;
	  
	//   date = month+'/'+day+'/'+date.getFullYear();
	//   today = tmonth+'/'+tday+'/'+today.getFullYear();	  
	  
	//   var flag = 0;
	//   var flag2 = 0;
	//   var flag3 = 0;
	  
	//   var errtxt = 'ERROR :\n';
	  		
	// 	if(activity_code == '') {
	// 			flag = 1; 
	// 	}
		
	// 	if(text_Date != '') {
	// 			flag2 = 1; 
	// 	}
	 	  	
	// 	if (flag2 == 1)	{			
	// 		if(comp_today < comp_date) {
	// 				errtxt = errtxt + '-. Date cannot exceed today\n';	
	// 				flag3 = 1; 
	// 		} 
	// 	}
		
	// 	if(flag3 == 1) alert (errtxt);
	// 	else {
	// 	   if(flag == 1 && flag2 == 0) {
	// 	   			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?flag_view=0" 
	// 	   		 }
	// 	   else  if (flag == 0 && flag2 == 0) {			  
	// 			 	location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?activity_code=" + encodeURIComponent(activity_code)  + "&flag_view=2" 
	// 			 }
	// 	  else if (flag == 1 && flag2 == 1){
	// 				location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?text_Date=" + date + "&flag_view=1" 
	// 			 }
	// 	  else if (flag == 0 && flag2 == 1){
	// 				location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity?activity_code=" + encodeURIComponent(activity_code) + "&text_Date=" + date + "&flag_view=3" 
	// 			 }
	// 		}	
	// }

	function view_report_detail(flag_view_detail,startdate,enddate,activity_id,user_group,creator_id){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity_detail?flag_view_detail=" + flag_view_detail +"&startdate=" + startdate + "&enddate=" + enddate + "&activity_id=" + encodeURIComponent(activity_id) + "&user_group=" + user_group + "&creator_id=" + creator_id;
	}
	
	function toexcel(flag_view_detail,startdate,enddate,activity_code,user_group,creator_id){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_activity_toexcel_summary?flag_view=" + flag_view_detail +"&startdate=" + startdate +"&enddate=" + enddate + "&activity_code=" + activity_code + "&user_group=" + user_group + "&creator_id=" + creator_id;
    }
	
	function selectComboBox(level){
		var $combobox;
		switch (level){
			case 1:
				$combobox = $("#cmb-issue-type");
				break;
			case 2:
				$combobox = $("#cmb-issue-group");
				break;
			case 3:
				$combobox = $("#cmb-issue-sub-group");
				break;
			case 4:
				$combobox = $("#cmb-issue-description");
				break;
		}
		return $combobox;
	}

	function changeActivityCode(level){
		var $combobox = selectComboBox(level);
		var $selected = $combobox.children("option:selected");
		getChildActivity(level + 1, $combobox.val());
		
		$("#hdn-activity-id").val($selected.val());
		$("#txt-activity-code").val($selected.attr('activity-code'));
		$("#txt-activity-name").val($selected.text());
	}

	function getChildActivity(level, activity_code){
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url(); ?>index.php/user/ctr_interaction/ajax_activity',
			data: { 'type': 'children', 'value': activity_code }
		}).done(function(message){
			var $combobox = selectComboBox(level);
			$combobox.html(message);
		}).fail(function(){
		});
	}
</script>
<div style="font-weight:bold;font-size:18px;">Export To Excel&nbsp;&nbsp;&nbsp;
<?php 
echo "<a title='Export To Excel' onclick='toexcel(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$activity_code."\",\"".$user_group."\",\"".$creator_id."\")' style='cursor:pointer')>
	  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
?>
</div>
<div style="font-weight:bold;font-size:18px;">
<table>
	<!-- <tr>
		<td>Activity Code</td>
		<td><input type="text" id="activity_code" name="activity_code"></td>
	</tr> -->
	<!-- Interaction Created In &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="text" id="text_Date" name="text_Date">
							<input type="button" class='btn btn-primary' name="search_tanggal" id="search_tanggal"  value="Search" onclick="search_tanggal()" /><br /> -->
	<tr>
		<td>Interaction Created From</td>
		<td><input type="text" id="text_startDate" name="text_startDate"></td>
		<td>To</td>
	    <td><input type="text" id="text_endDate" name="text_endDate"></td>
	</tr>
	<tr>
		<td>Team</td>
		<td>
			<select id="user_group" name="user_group">
             	<option value=''>--choose--</option>
				<?php foreach ($user_group as $p):
					echo "<option value='".$p->id."'>".$p->group_name."</option>";
					endforeach;
				?>
			</select>
		<td>
		<td></td>
	</tr>
	<tr>
		<td>Name</td>
		<td>
			<select id="creator_id" name="creator_id">
             	<option value=''>--choose--</option>
             	<?php  foreach ($user_name as $user_name) :
             		echo "<option value='".$user_name->user_id."'>".$user_name->user_name."</option>";
             		endforeach;
             	?>
			</select>
		</td>
		<td></td>
	</tr>
	<tr><td><input type='hidden' class="form-control" name="txt-activity-code" id="txt-activity-code" value="" readonly  /></td></tr>
	<tr>
	<td>Issue Type</td>
	<td>
		<select class="form-control" name="cmb-issue-type" id="cmb-issue-type" onchange="changeActivityCode(1);">
        	<option value="" activity-code="" selected="selected">--choose--</option>
        	<?php
            foreach($activity_type as $record){
				echo "<option value='" . $record->activity_id . "' activity-code='" . $record->activity_code . "'>" 
					. $record->activity_description 
					. "</option>";
			}
			?>
        </select>
    </td>
	</tr>
	<tr>
		<td>Issue Group</td>
		<td>
			<select class="form-control" name="cmb-issue-group" id="cmb-issue-group" onchange="changeActivityCode(2);">
            </select>
		</td>
	</tr>
	<tr>
		<td>Issue Sub Group</td>
		<td>
			<select class="form-control" name="cmb-issue-sub-group" id="cmb-issue-sub-group" onchange="changeActivityCode(3);">
            </select>
        </td>
        <td></td>
    </tr>
    <tr>
    	<td>Issue Description</td>
	    <td>
		    <select class="form-control" name="cmb-issue-description" id="cmb-issue-description" onchange="changeActivityCode(4);">
            </select>
        </td>
        <td></td>
		<td align="right">
			<input type="button" class='btn btn-primary' name="search_tanggal" id="search_tanggal"  value="Search" onclick="search_tanggal()" />
		</td>
	</tr>
</table>
</div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_report">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Activity Code</th>
				<th>Activity Description</th>
				<th>Summary</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($list_report_interaction as $p):;
				 echo "<tr>";
				 echo "<td>".$p->activity_code."</td>";
				 echo "<td>".$p->activity_description."</td>";
				 if($p->summary > 0)
				 	echo "<td style='cursor:pointer;' onClick='view_report_detail(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$p->activity_id."\",\"".$user_group."\",\"".$creator_id."\")'}><u>".$p->summary."</u></td>";
				 else 
				 		echo "<td>".$p->summary."</td>";
				 echo "</tr>";
			endforeach;
		?>
		</tbody>
	</table>
</div>
</form>		
</body>
</html>
