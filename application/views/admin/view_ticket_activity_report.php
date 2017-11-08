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
		$("#text_Date").datepicker({
			dateFormat: "dd M yy",
				defaultDate: 0
		});
	}

	function search_tanggal() {
	  var text_Date  = document.getElementById('text_Date').value;
	  var activity_code = document.getElementById('activity_code').value;
	  
	  var date = new Date(text_Date);
	  var today = new Date(Date.now());		  
				  
	  var day = date.getDate();
	  var month = (date.getMonth()+1);
	  var tday = today.getDate();
	  var tmonth = (today.getMonth()+1);
	  
	  if (day < 10) day = '0' + day;
	  if (month < 10) month = '0' + month;
	  if (tday < 10) tday = '0' + tday;
	  if (tmonth < 10) tmonth = '0' + tmonth;
	  
	  var comp_date = date.getFullYear() +'-'+ month + '-' + day;
	  var comp_today = today.getFullYear()+ '-' + tmonth+  '-'+ tday;
	  
	  date = month+'/'+day+'/'+date.getFullYear();
	  today = tmonth+'/'+tday+'/'+today.getFullYear();
	  
	  var flag = 0;
	  var flag2 = 0;
	  var flag3 = 0;
	  
	  var errtxt = 'ERROR :\n';
	  		
		if(activity_code == '') {
				flag = 1; 
		}
		
		if(text_Date != '') {
				flag2 = 1; 
		}
	 	  	
		if (flag2 == 1)	{			
			if(comp_today < comp_date) {
					errtxt = errtxt + '-. Date cannot exceed today\n';	
					flag3 = 1; 
			} 
		}
		
		if(flag3 == 1) alert (errtxt);
		else {
		   if(flag == 1 && flag2 == 0) {
		   			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket_activity?flag_view=0" 
		   		 }
		   else  if (flag == 0 && flag2 == 0) {			  
				 	location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket_activity?activity_code=" + encodeURIComponent(activity_code)  + "&flag_view=2" 
				 }
		  else if (flag == 1 && flag2 == 1){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket_activity?text_Date=" + date + "&flag_view=1" 
				 }
		  else if (flag == 0 && flag2 == 1){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket_activity?activity_code=" + encodeURIComponent(activity_code) + "&text_Date=" + date + "&flag_view=3" 
				 }
			}	
	}

function view_report_detail(flag_view_detail,date,activity_id){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket_activity_detail?flag_view_detail=" + flag_view_detail +"&date=" + date +  "&activity_id=" + encodeURIComponent(activity_id);
	}
	
function toexcel(flag_view_detail,date,activity_code){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket_activity_toexcel_summary?flag_view=" + flag_view_detail +"&date=" + date + "&activity_code=" + activity_code;
    }
	
</script>
<div style="font-weight:bold;font-size:18px;">Export To Excel&nbsp;&nbsp;&nbsp;
<?php 
echo "<a title='Export To Excel' onclick='toexcel(\"".$flag_view_detail."\",\"".$date."\",\"".$activity_code."\")' style='cursor:pointer')>
	  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
?>
</div>
<div style="font-weight:bold;font-size:18px;">
Activity Code &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="activity_code" name="activity_code"><br />
Activity Report In &nbsp;&nbsp;&nbsp;
						<input type="text" id="text_Date" name="text_Date">
						<input type="button" class='btn btn-primary' name="search_tanggal" id="search_tanggal"  value="Search" onclick="search_tanggal()" />
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
			foreach ($list_report_ticket as $p):;
				 echo "<tr>";
				 echo "<td>".$p->activity_code."</td>";
				 echo "<td>".$p->activity_description."</td>";
				 if($p->summary > 0)
				 		echo "<td style='cursor:pointer;' onClick='view_report_detail(\"".$flag_view_detail."\",\"".$date."\",\"".$p->activity_id."\")'}><u>".$p->summary."</u></td>";
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
