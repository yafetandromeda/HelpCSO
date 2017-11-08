<!--M003-->
<!-- MD01 - YA - Interaction status menggunakan model button, bukan combo box, tidak perlu tombol save &  System Autosave supaya jika pindah tab informasi sebelumnya tidak hilang -->
<!-- M59 - YA - filtering interaction berdasarkan tanggal, nama team dan dama user -->
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

		function search_tanggal() {
		var id = document.getElementById('user_group').value;
		var creator_id = document.getElementById('creator_id').value;
		var text_startDate  = document.getElementById('text_startDate').value;
		var text_endDate  = document.getElementById('text_endDate').value; 

		var startDate = new Date(text_startDate);
		var endDate = new Date(text_endDate);
		var today = new Date(Date.now());		  

		//M003
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
		if (text_startDate == ''){
			startDate = '';
			endDate = '';
			today = '';
		} else {
		startDate = syear+'-'+smonth+'-'+sday;
		endDate = eyear+'-'+emonth+'-'+eday;
		today = tyear+'-'+tmonth+'-'+tday;
	}
	  //M003

		var flag = 0;
		var flag2 = 0;

			  var errtxt = 'ERROR :\n';

		  if(text_startDate == '' && id == '' && creator_id == '') {
						errtxt = errtxt + '-. Invalid search\n';	
						flag = 1; 
		  }
		//   if(text_startDate == '') {
		// 				errtxt = errtxt + '-. Start Date still empty\n';	
		// 				flag = 1; 
		//   }
		  
		  if(text_startDate != '' && text_endDate == '') {
						errtxt = errtxt + '-. End Date still empty\n';	
						flag = 1; 
		  }
		
		if (flag == 0){		
		  if(comp_startDate  > comp_endDate) {
						errtxt = errtxt + '-. Date not valid\n';	
						flag2 = 1; 
		  } 
		  // if(comp_today < comp_startDate) {
				// 		errtxt = errtxt + '-. Start Date cannot exceed today\n';	
				// 		flag2 = 1; 
		  // } 
		  // if(comp_today < comp_endDate) {
				// 		errtxt = errtxt + '-. End Date cannot exceed today\n';	
				// 		flag2 = 1; 
		  // } 
		}
		if(flag == 1 || flag2 == 1) alert(errtxt);
		else {	
			// M59
	   		if (creator_id == '')	{		  
				location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/search_report_interaction_bydate?text_startDate=" + startDate + "&text_endDate=" + endDate + "&id=" + id
			}	else {
				location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/search_report_interaction_bycreator?text_startDate=" + startDate + "&text_endDate=" + endDate + "&creator_id=" + creator_id + "&id=" + id
			}
			// M59
		}
	}
	// M69
	function view_report_detail(flag_view_detail,startdate,enddate,id,status,interaction_type_id){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_detail?flag_view_detail=" + flag_view_detail +"&startdate=" + startdate + "&enddate=" + enddate + "&id=" + id + "&status=" + status + "&interaction_type_id=" + interaction_type_id;
	}
	function view_report_detail_bycreator(flag_view_detail,startdate,enddate,creator_id,status,interaction_type_id,id){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/view_report_interaction_detail_bycreator?flag_view_detail=" + flag_view_detail +"&startdate=" + startdate + "&enddate=" + enddate + "&creator_id=" + creator_id + "&status=" + status + "&interaction_type_id=" + interaction_type_id;
	}
	// M59
	// function get_user(){
	// $(document).ready(function(){
	// 	$("#user_group").change(function(){
	// 		var group_id = $("#user_group").val();
	// 		$.ajax({
	// 			type: "POST",
	// 			url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/get_user",
	// 			data: "group_id=" + group_id, 
	// 			success: function(data){
	// 				$("#user_id").html(data);
	// 			} 
	// 		});
	// 	});
	// )};
	// }
</script>
<body>
<div style="font-weight:bold;font-size:18px;">
Interaction Type Report 
<!-- M59 -->
<table>
	<tr>
		<td>From</td>
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
		<td align="right">
			<input type="button" class='btn btn-primary' name="search_tanggal" id="search_tanggal"  value="Search" onclick="search_tanggal()" />
		</td>
	</tr>
	<tr>
		<td></td><td></td><td></td>
	</tr>
</table>
<!-- M59 -->
</div>

<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_report">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Interaction Type Number</th>
				<th style="display:none">Interaction Type ID</th>
				<th>Interaction Type Name</th>
				<th>Draft</th>
				<th>Scheduled</th>
				<th>In Progress</th>
                <th>Canceled</th>
                <th>Closed</th>
			</tr>
		</thead>
		<tbody>
		<?php
			if ($creator_id == ''){
				$number = 0;
				foreach ($list_report_interaction as $p):
					 $number = $number + 1;
					 echo "<tr>";
					 echo "<td>".$number."</td>";
					 echo "<td style='display:none'>".$p->interaction_type_id."</td>";
					 echo "<td>".$p->interaction_type_name."</td>";
					 if($p->Draft > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$id."\",\"1\",\"".$p->interaction_type_id."\")'}><u>".$p->Draft."</u></td>";
					 else
					 		echo "<td>".$p->Draft."</td>";
					 if($p->Scheduled > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$id."\",\"3\",\"".$p->interaction_type_id."\")'}><u>".$p->Scheduled."</u></td>";
					 else 
					 		echo "<td>".$p->Scheduled."</td>";
					 if($p->Progress > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$id."\",\"4\",\"".$p->interaction_type_id."\")'}><u>".$p->Progress."</u></td>";
					 else 
					 		echo "<td>".$p->Progress."</td>";
					 if($p->Canceled > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$id."\",\"5\",\"".$p->interaction_type_id."\")'}><u>".$p->Canceled."</u></td>";
					 else 
					 		echo "<td>".$p->Canceled."</td>";
					 if($p->Closed > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$id."\",\"2\",\"".$p->interaction_type_id."\")'}><u>".$p->Closed."</u></td>";
					 else 
					 		echo "<td>".$p->Closed."</td>";
					 echo "</tr>";
				 endforeach;
			} else {
				// M59
				$number = 0;
				foreach ($list_report_interaction as $p):
					 $number = $number + 1;
					 echo "<tr>";
					 echo "<td>".$number."</td>";
					 echo "<td style='display:none'>".$p->interaction_type_id."</td>";
					 echo "<td>".$p->interaction_type_name."</td>";
					 if($p->Draft > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail_bycreator(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$creator_id."\",\"1\",\"".$p->interaction_type_id."\")'}><u>".$p->Draft."</u></td>";
					 else
					 		echo "<td>".$p->Draft."</td>";
					 if($p->Scheduled > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail_bycreator(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$creator_id."\",\"3\",\"".$p->interaction_type_id."\")'}><u>".$p->Scheduled."</u></td>";
					 else 
					 		echo "<td>".$p->Scheduled."</td>";
					 if($p->Progress > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail_bycreator(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$creator_id."\",\"4\",\"".$p->interaction_type_id."\")'}><u>".$p->Progress."</u></td>";
					 else 
					 		echo "<td>".$p->Progress."</td>";
					 if($p->Canceled > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail_bycreator(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$creator_id."\",\"5\",\"".$p->interaction_type_id."\")'}><u>".$p->Canceled."</u></td>";
					 else 
					 		echo "<td>".$p->Canceled."</td>";
					 if($p->Closed > 0)
					 		echo "<td style='cursor:pointer;' onClick='view_report_detail_bycreator(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$creator_id."\",\"2\",\"".$p->interaction_type_id."\")'}><u>".$p->Closed."</u></td>";
					 else 
					 		echo "<td>".$p->Closed."</td>";
					 echo "</tr>";
				 endforeach;
				 // M59
			}
		?>
		</tbody>
	</table>
</div>
</form>		
</body>
</html>
