<?php
// M027 - YA - Menampilkan Recovering dan Recovered Ticket
// M028 - YA - Admin super akses
// M050	- YA - perbaikan update status pada  ticket dan interaction, ubah berdasarkan ticket
// M60 - YA - filtering , reporting dan export ticket berdasarkan activity code, tanggal, group, user, dan level
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
		CKEDITOR.replace('ticket_info', 
					{
					toolbarGroups: [
							{ name: 'clipboard',   groups: [ 'clipboard', 'undo', 'outdent', 'indent' ] },																	
							{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
							{ name: 'links', groups : [ 'Link','Unlink','Anchor' ] }
						]
					}
		);
}
// M050
function form_detail(customer_name,customer_type,customer_phone,customer_email,customer_event_datetime,creator_name,create_datetime,status,substatus,handled_datetime,solved_datetime,closed_datetime,ticket_info_id,owner_name)
    {
  //   	document.getElementById("ticket_id_detail").value = ticket_id;
		// document.getElementById("activity_code_detail").value = activity_code;
		// document.getElementById("activity_description_detail").value = activity_description;
// M050
		document.getElementById("customer_name_detail").value = customer_name;
		document.getElementById("customer_type_detail").value = customer_type;
        document.getElementById("customer_phone_detail").value = customer_phone;
        document.getElementById("customer_email_detail").value = customer_email;
		var ticket_info = document.getElementById(ticket_info_id).innerHTML;
        CKEDITOR.instances['ticket_info'].setData(ticket_info);
		document.getElementById("customer_event_datetime_detail").value = customer_event_datetime;  
		document.getElementById("creator_name_detail").value = creator_name;
		document.getElementById("create_datetime_detail").value = create_datetime;
		document.getElementById("owner_name_detail").value = owner_name;
		document.getElementById("handled_datetime_detail").value = handled_datetime;
		document.getElementById("solved_datetime_detail").value = solved_datetime;
		document.getElementById("closed_datetime_detail").value = closed_datetime;
		document.getElementById("status_detail").value = status;		
		document.getElementById("substatus_detail").value = substatus;		
    }

// M028
function form_detail2(ticket_id){
		location.href = "<?php echo base_url(); ?>index.php/user/ctr_ticket/form/" + ticket_id;
    }
// M028
// M60
function toexcel(flag_view_detail,startdate,enddate,status,substatus,activity_code,id,creator_id,level){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket_toexcel?flag_view_detail=" + flag_view_detail +"&startdate=" + startdate + "&enddate=" + enddate + "&status=" + status + "&substatus=" + substatus + "&activity_code=" + activity_code + "&id=" + id + "&creator_id=" + creator_id + "&level=" + level;
    }
	
</script>
<div style="font-weight:bold;font-size:18px;">Export To Excel&nbsp;&nbsp;&nbsp;
<?php 
echo "<a title='Export To Excel' onclick='toexcel(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$status."\",\"".$substatus."\",\"".$activity_code."\",\"".$id."\",\"".$creator_id."\",\"".$level."\")' style='cursor:pointer')>
	  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
?>
<!-- M60 -->
</div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_report">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Ticket ID</th>
            	<th style='display:none'>Ticket ID</th>
    	<!-- M050 -->
				<!-- <th style="display:none;">Activity ID</th>
                <th>Activity Code</th>
				<th>Activity Description</th> -->
		<!-- M050 -->
				<th>Status</th>
                <th>Substatus</th>
                <th>Creator Name</th>
                <th>Create Date</th>
<!-- M027 -->
                <th>Recovering Date</th>
                <th>Success / Failed Date</th>
<!-- M027 -->
                <th>Detail</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($list_report_ticket_detail as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td style='display:none'>".$p->ticket_id."</td>";
				 echo "<td>".$p->code_ticket."</td>";
		// M050
				 // echo "<td style='display:none'>".$p->activity_id."</td>";
				 // echo "<td>".$p->activity_code."</td>";
				 // echo "<td>".$p->activity_description."</td>";
		// M050
				 echo "<td>".$p->status."</td>";
				 echo "<td>".$p->substatus."</td>";
				 echo "<td>".$p->creator_name."</td>";
				 echo "<td>".date("Y-m-d H:i:s",strtotime($p->create_datetime))."</td>";
//  M027
				 echo "<td>".$p->recovering_datetime."</td>";
				 echo "<td>".$p->recovered_datetime."</td>";
//  M027
//  M028
				 echo "<td><a title='Test' onClick='form_detail2(\"".$p->ticket_id."\")' style='cursor:pointer'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
// M028
				 echo "</tr>";
			endforeach;
		?>
		</tbody>
	</table>
<button class="btn pull-left" type='button' onClick="window.history.go(-1);">Back</button>   
</div>
</form>	

<div id="modal_view_detail" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Ticket Report Detail</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_interaction_detail" id="form_new_script" method="post" action="">

			<div class="control-group cso-form-row">
					<label for="ticket_id_detail" class="cso-form-label">Ticket ID</label>
					<input type="text" id="ticket_id_detail" name="ticket_id_detail" disabled="disabled">
			</div>
			<div class="control-group cso-form-row">
					<label for="activity_code_detail" class="cso-form-label">Activity Code</label>
					<input type="text" id="activity_code_detail" name="activity_code_detail" disabled="disabled">
			</div>
           <div class="control-group cso-form-row">
					<label for="activity_description_detail" class="cso-form-label">Activity Description</label>
					<input type="text" id="activity_description_detail" name="activity_description_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="customer_name_detail" class="cso-form-label">Customer Name</label>
					<input type="text" id="customer_name_detail" name="customer_name_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="customer_type_detail" class="cso-form-label">Customer Type</label>
					<input type="text" id="customer_type_detail" name="customer_type_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="customer_phone_detail" class="cso-form-label">Customer Phone</label>
					<input type="text" id="customer_phone_detail" name="customer_phone_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="customer_email_detail" class="cso-form-label">Customer Email</label>
					<input type="text" id="customer_email_detail" name="customer_email_detail" disabled="disabled">
			</div>
            <div class"control-group cso-form-row">
						<label for="ticket_info" class="cso-form-label">Tickt Information</label>
						<textarea id="ticket_info" name="ticket_info" cols="20" rows="5" disabled="disabled"></textarea>
			</div>
            <div class="control-group cso-form-row">
					<label for="customer_event_datetime_detail" class="cso-form-label">Customer Event Datetime</label>
					<input type="text" id="customer_event_datetime_detail" name="customer_event_datetime_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="creator_name_detail" class="cso-form-label">Creator Name</label>
					<input type="text" id="creator_name_detail" name="creator_name_detail" disabled="disabled">
			</div>     
            <div class="control-group cso-form-row">
					<label for="create_datetime_detail" class="cso-form-label">Create Datetime</label>
					<input type="text" id="create_datetime_detail" name="create_datetime_detail" disabled="disabled">
			</div>  
            <div class="control-group cso-form-row">
					<label for="owner_name_detail" class="cso-form-label">Owner Name</label>
					<input type="text" id="owner_name_detail" name="owner_name_detail" disabled="disabled">
			</div>  
            <div class="control-group cso-form-row">
					<label for="handled_datetime_detail" class="cso-form-label">Handled Datetime</label>
					<input type="text" id="handled_datetime_detail" name="handled_datetime_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="solved_datetime_detail" class="cso-form-label">Solved Datetime</label>
					<input type="text" id="solved_datetime_detail" name="solved_datetime_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="closed_datetime_detail" class="cso-form-label">Closed Datetime</label>
					<input type="text" id="closed_datetime_detail" name="closed_datetime_detail" disabled="disabled">
			</div>
             <div class="control-group cso-form-row">
					<label for="status_detail" class="cso-form-label">Status</label>
					<input type="text" id="status_detail" name="status_name_detail" disabled="disabled">
			</div>
			<div class="control-group cso-form-row">
					<label for="substatus_detail" class="cso-form-label">Substatus</label>
					<input type="text" id="substatus_detail" name="substatus_detail" disabled="disabled">
			</div>       
			</form>
		</div>	
</body>
</html>
