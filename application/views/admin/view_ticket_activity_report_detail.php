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

function form_detail(activity_code,activity_description,customer_name,customer_type,customer_phone,customer_email,customer_event_datetime,creator_name,create_datetime,status,substatus,handled_datetime,solved_datetime,closed_datetime,ticket_info_id)
    {
			document.getElementById("activity_code_detail").value = activity_code;
			document.getElementById("activity_description_detail").value = activity_description;
			document.getElementById("customer_name_detail").value = customer_name;
			document.getElementById("customer_type_detail").value = customer_type;
            document.getElementById("customer_phone_detail").value = customer_phone;
            document.getElementById("customer_email_detail").value = customer_email;
			var ticket_info = document.getElementById(ticket_info_id).innerHTML;
            CKEDITOR.instances['ticket_info'].setData(ticket_info);
			document.getElementById("customer_event_datetime_detail").value = customer_event_datetime;  
			document.getElementById("creator_name_detail").value = creator_name;
			document.getElementById("create_datetime_detail").value = create_datetime;
			document.getElementById("handled_datetime_detail").value = handled_datetime;
			document.getElementById("solved_datetime_detail").value = solved_datetime;
			document.getElementById("closed_datetime_detail").value = closed_datetime;
			document.getElementById("status_detail").value = status;		
			document.getElementById("substatus_detail").value = substatus;		
    }

function toexcel(flag_view_detail,date,activity_id){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/view_report_ticket_activity_toexcel?flag_view_detail=" + flag_view_detail +"&date=" + date +  "&activity_id=" + activity_id;
    }
	
</script>
<div style="font-weight:bold;font-size:18px;">Export To Excel&nbsp;&nbsp;&nbsp;
<?php 
echo "<a title='Export To Excel' onclick='toexcel(\"".$flag_view_detail."\",\"".$date."\",\"".$activity_id."\")' style='cursor:pointer')>
	  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
?>
</div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_report">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Number</th>
				<th style="display:none;">Activity ID</th>
                <th>Activity Code</th>
				<th>Activity Description</th>
				<th>Status</th>
                <th>Substatus</th>
                <th>Creator Name</th>
                <th>Create Date</th>
                <th>Detail</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($list_report_ticket_detail as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->activity_id."</td>";
				 echo "<td>".$p->activity_code."</td>";
				 echo "<td>".$p->activity_description."</td>";
				 echo "<td>".$p->status."</td>";
				 echo "<td>".$p->substatus."</td>";
				 echo "<td>".$p->creator_name."</td>";
				 echo "<td>".date("Y-m-d H:i:s",strtotime($p->create_datetime))."</td>";
				 echo "<td><span id='ticket_info_".$p->ticket_id."' style='display:none;'>".$p->ticket_info."</span><a href='#modal_view_detail' data-toggle='modal' onClick='form_detail(\"".$p->activity_code."\",\"".$p->activity_description."\",\"".$p->customer_name."\",\"".$p->customer_type."\",\"".$p->customer_phone."\",\"".$p->customer_email."\",\"".($p->customer_event_datetime ? date("Y-m-d H:i:s", strtotime($p->customer_event_datetime)) : '')."\",\"".$p->creator_name."\",\"".($p->create_datetime ? date("Y-m-d H:i:s", strtotime($p->create_datetime)) : '')."\",\"".$p->status."\",\"".$p->substatus."\",\"".($p->handled_datetime ? date("Y-m-d H:i:s", strtotime($p->handled_datetime)) : '')."\",\"".($p->solved_datetime ? date("Y-m-d H:i:s", strtotime($p->solved_datetime)) : '')."\",\"".($p->closed_datetime ? date("m/d/Y h:i:s", strtotime($p->closed_datetime)) : '')."\",\"ticket_info_".$p->ticket_id."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
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
