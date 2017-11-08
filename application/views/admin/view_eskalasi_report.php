<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>List Report Eskalasi</title>
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
	  var text_startDate  = document.getElementById('text_startDate').value;
	  var text_endDate  = document.getElementById('text_endDate').value; 
	  
	  var startDate = new Date(text_startDate);
	  var endDate = new Date(text_endDate);
	  var today = new Date(Date.now());		  
				  
	  var sday = startDate.getDate();
	  var smonth = (startDate.getMonth()+1);
	  var eday = endDate.getDate();
	  var emonth = (endDate.getMonth()+1);
	  var tday = today.getDate();
	  var tmonth = (today.getMonth()+1);
	  
	  if (sday < 10) sday = '0' + sday;
	  if (smonth < 10) smonth = '0' + smonth;
	  if (eday < 10) eday = '0' + eday;
	  if (emonth < 10) emonth = '0' + emonth;
	  if (tday < 10) tday = '0' + tday;
	  if (tmonth < 10) tmonth = '0' + tmonth;
	  
	  var comp_startDate = startDate.getFullYear() + '-' + smonth + '-' + sday;
	  var comp_endDate = endDate.getFullYear() +'-'+ emonth + '-' + eday;
	  var comp_today = today.getFullYear()+ '-' + tmonth+  '-'+ tday;
	  
	  startDate = smonth+'/'+sday+'/'+startDate.getFullYear();
	  endDate = emonth+'/'+eday+'/'+endDate.getFullYear();
	  today = tmonth+'/'+tday+'/'+today.getFullYear();
	  
	  var flag = 0;

	 	  var errtxt = 'ERROR :\n';
	  
		  if(text_startDate == '') {
						errtxt = errtxt + '-. Start Date still empty\n';	
						flag = 1; 
		  }
		  
		  if(text_endDate == '') {
						errtxt = errtxt + '-. End Date still empty\n';	
						flag = 1; 
		  }
					
		  if(comp_startDate  > comp_endDate) {
						errtxt = errtxt + '-. Date not valid\n';	
						flag = 1; 
		  } 
		  if(comp_today < comp_startDate) {
						errtxt = errtxt + '-. Start Date cannot exceed today\n';	
						flag = 1; 
		  } 
		  if(comp_today < comp_endDate) {
						errtxt = errtxt + '-. End Date cannot exceed today\n';	
						flag = 1; 
		  } 
		  if(flag == 1) alert(errtxt);
		   else {	
		   		  			  
				 location.href = "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/search_report_bydate?text_startDate=" + startDate + "&text_endDate=" + endDate 
			}	
	}

</script>
<body>
<div style="font-weight:bold;font-size:18px;">
Escalation Report &nbsp;&nbsp;&nbsp; From
						<input type="text" id="text_startDate" name="text_startDate">
                        To
                        <input type="text" id="text_endDate" name="text_endDate">
						<input type="button" class='btn btn-primary' name="search_tanggal" id="search_tanggal"  value="Search" onclick="search_tanggal()" />
</div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_report">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Category Number</th>
				<th style="display:none">Category ID</th>
				<th>Ticket Category</th>
				<th>Total New Ticket</th>
				<th>Total Handled Ticket</th>
				<th>Total Solved Ticket</th>
                <th>Total Closed Ticket</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($list_report_eskalasi as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->cat_id."</td>";
				 echo "<td>".$p->catname."</td>";
				 echo "<td>".$p->total_new_ticket."</td>";
				 echo "<td>".$p->total_handled_ticket."</td>";
				 echo "<td>".$p->total_solved_ticket."</td>";
				 echo "<td>".$p->total_closed_ticket."</td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
</form>		
</body>
</html>
