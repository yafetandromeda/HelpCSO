<!--M003-->
<!-- M050 - YA - perbaikan update status pada  ticket dan interaction, ubah berdasarkan ticket -->
<!-- M60 - YA - filtering , reporting dan export ticket berdasarkan activity code, tanggal, group, user, dan level -->
<!-- M62 - YA - Tombol sakti untuk langsung export seluruh status data ticket dengan tanpa harus terlebih dahulu masuk dulu ke masing2 status ticket untuk melakukan export -->
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

	//M003
	function search_tanggal() {
	var id = document.getElementById('user_group').value;
	var creator_id = document.getElementById('creator_id').value;
	var activity_code = document.getElementById('txt-activity-code').value;
	var text_startDate  = document.getElementById('text_startDate').value;
	var text_endDate  = document.getElementById('text_endDate').value; 
	var level = document.getElementById('user_level').value;

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
	//M003

	var flag = 0;
	var flag2 = 0;
	var flag3 = 0;
	// M60
	var flag4 = 0;
	var flag5 = 0;
	var flag6 = 0;
	var flag7 = 0;
	// M60
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

	// if(activity_code == '' && flag3 == 0) {
	// 			errtxt = errtxt + '-. Activity Code still empty\n';	
	// 			flag = 1; 
	// }

	// if(flag2 == 0) {
	//   errtxt = errtxt + '-. Filter Date still empty\n';	
	// }
// M60
	if (activity_code != ''){
		flag6 = 1;
	}
	if (id != ''){
		flag4 = 1;
	}
	if (creator_id != ''){
		flag5 = 1;
	}
	if (level != ''){
		flag7 = 1;
	}
	if(flag == 1 && flag2 == 0 && flag4 == 0 && flag5 == 0) alert(errtxt);
	else if (flag3 == 1) alert(errtxt);
	else {	
		if (flag == 0 && flag2 == 0 && flag4 ==0 && flag5 == 0 && flag7 == 0) {			  
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket?activity_code=" + encodeURIComponent(activity_code)  + "&flag_view=1" 
		}
		else if (flag == 0 && flag2 == 1 && flag4 == 0 && flag5 == 0 && flag6 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_bydate?text_startDate=" + startDate + "&text_endDate=" + endDate
		}
		else if (flag4 == 1 && flag6 == 0 && flag2 == 0 && flag5 == 0 && flag7 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_bygroup?id=" + id + "&flag_view=1"
		}
		else if (flag == 0 && flag2 == 1 && flag4 == 0 && flag5 == 0 && flag7 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_bycode_and_date?activity_code=" + encodeURIComponent(activity_code) + "&text_startDate=" + startDate + "&text_endDate=" + endDate
		}
		else if (flag6 == 1 && flag2 == 0 && flag5 == 0 && flag7 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_byactivity_and_id?activity_code=" + activity_code + "&id=" + id 
		}
		else if (flag2 == 1 && flag4 == 1 && flag5 == 0 && flag6 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_bydate_and_id?text_startDate=" + startDate + "&text_endDate=" + endDate  + "&id=" + id 
		}
		else if (flag6 == 1 && flag2 == 1 && flag4 == 1 && flag5 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_byall?text_startDate=" + startDate + "&text_endDate=" + endDate + "&activity_code=" + activity_code + "&id=" + id
		}
		else if (flag5 == 1 && flag2 == 0 && flag6 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_byuser?creator_id=" + creator_id + "&flag_view=1"
		}
		else if (flag5 == 1 && flag6 == 1){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_byuser_and_activity?creator_id=" + creator_id + "&activity_code=" + activity_code
		}
		else if (flag5 == 1 && flag2 == 1 && flag6 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_byuser_and_date?creator_id=" + creator_id + "&text_startDate=" + startDate + "&text_endDate=" + endDate
		}
		else if (flag5 == 1 && flag2 == 1 && flag6 == 1){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_byuser_all?text_startDate=" + startDate + "&text_endDate=" + endDate + "&activity_code=" + activity_code + "&creator_id=" + creator_id
		}
		else if (flag7 == 1 && flag2 == 0 && flag4 == 0 && flag6 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_bylevel?level=" + level
		}
		else if (flag7 == 1 && flag2 == 1 && flag4 == 0 && flag6 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_bylevel_and_date?level=" + level + "&text_startDate=" + startDate + "&text_endDate=" + endDate
		}
		else if (flag7 == 1 && flag2 == 0 && flag4 == 1 && flag6 == 0){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_bylevel_and_group?level=" + level + "&id=" + id
		}
		else if (flag7 == 1 && flag2 == 0 && flag4 == 0 && flag6 == 1){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_bylevel_and_activity?level=" + level + "&activity_code=" + activity_code
		}
		else if (flag7 == 1 && flag2 == 1 && flag4 == 0 && flag6 == 1){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_report_ticket_bydate_level_and_activity?text_startDate=" + startDate + "&text_endDate=" + endDate + "&level=" + level + "&activity_code=" + activity_code
		}
	}	
	// M60
	}

	function view_report_detail(flag_view_detail,startdate,enddate,status,substatus,activity_code,id,creator_id,level){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket_detail?flag_view_detail=" + flag_view_detail +"&startdate=" + startdate + "&enddate=" + enddate + "&status=" + status + "&substatus=" + substatus + "&activity_code=" + activity_code + "&id=" + id + "&creator_id=" + creator_id + "&level=" + level;
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
// M62
	function toexcel(flag_view_detail,startdate,enddate,activity_code,id,creator_id,level){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_all_ticket_toexcel?flag_view_detail=" + flag_view_detail +"&startdate=" + startdate + "&enddate=" + enddate + "&activity_code=" + activity_code + "&id=" + id + "&creator_id=" + creator_id + "&level=" + level;
    }
// M62
</script>
<body>
<!-- M62 -->
<h2>Ticket Report</h2>
<div style="font-weight:bold;font-size:18px;"><br>
<!-- M62 -->
<!-- M050 -->

<!-- M050 -->
<table>
	<!-- <tr>
		<td>Activity Code</td>
		<td><input type="text" id="activity_code" name="activity_code"><br /></td>
	</tr> -->
	<tr>
		<td>From</td>
			<td><input type="text" id="text_startDate" name="text_startDate"></td>
		<td>To</td>
			<td><input type="text" id="text_endDate" name="text_endDate"></td>
	</tr>
	<tr>
		<td>Level</td>
		<td>
			<select id="user_level" name="user_level">
             	<option value=''>--choose--</option>
				<?php foreach ($user_level as $p):
					echo "<option value='".$p->code_id."'>".$p->level."</option>";
					endforeach;
				?>
			</select>
		<td>
		<td></td>
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
        </td><td></td>
        
		<td align="right">
			<input type="button" class='btn btn-primary' name="search_tanggal" id="search_tanggal"  value="Search" onclick="search_tanggal()" />
		</td>
		<td>&nbsp;&nbsp;&nbsp;Export To Excel&nbsp;&nbsp;
<?php 
echo "<a title='Export To Excel' onclick='toexcel(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$activity_code."\",\"".$id."\",\"".$creator_id."\",\"".$level."\")' style='cursor:pointer')>
	  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
?></td>
	</tr>
</table>
</div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_report">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
				<th>Status</th>
				<th>Assigned</th>
				<th>Un Assigned</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($list_report_ticket as $p):
				 echo "<tr>";
				 echo "<td>".$p->Status."</td>";
		
				 if($p->Assigned > 0)
				 		echo "<td style='cursor:pointer;' onClick='view_report_detail(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$p->Status_id."\",\"2\",\"".$activity_code."\",\"".$id."\",\"".$creator_id."\",\"".$level."\")'}><u>".$p->Assigned."</u></td>";
				 else 
				 		echo "<td>".$p->Assigned."</td>";
				 if($p->Un_Assigned > 0)
				 		echo "<td style='cursor:pointer;' onClick='view_report_detail(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$p->Status_id."\",\"1\",\"".$activity_code."\",\"".$id."\",\"".$creator_id."\",\"".$level."\")'}><u>".$p->Un_Assigned."</u></td>";
				 else 
				 		echo "<td>".$p->Un_Assigned."</td>";
				 echo "</tr>";
			endforeach;
		?>
		</tbody>
	</table>
</div>
</form>		
</body>
</html>
