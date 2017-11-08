<!-- M59 - YA - filtering interaction berdasarkan tanggal, nama team dan dama user -->
<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>View Interaction Report Detail</title>
</head>

<script type="text/javascript">
	window.onload = function(){
		CKEDITOR.replace('interaction_description_detail', 
					{
					toolbarGroups: [
							{ name: 'clipboard',   groups: [ 'clipboard', 'undo', 'outdent', 'indent' ] },																	
							{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
							{ name: 'links', groups : [ 'Link','Unlink','Anchor' ] }
						]
					}
		);
	}
	
	function form_detail(interaction_id,interaction_type_name,customer_name,customer_phone,customer_email,queue_number,creator_name,interaction_description_id,creator_datetime,status_name,planned_start_datetime,actual_start_datetime,actual_end_datetime,actual_cancel_datetime)
    {
			document.getElementById("interaction_id_detail").value = interaction_id;
			document.getElementById("interaction_type_detail").value = interaction_type_name;
			document.getElementById("customer_name_detail").value = customer_name;
            document.getElementById("customer_phone_detail").value = customer_phone;
            document.getElementById("customer_email_detail").value = customer_email;
			document.getElementById("queue_number_detail").value = queue_number;  
			var interaction_description = document.getElementById(interaction_description_id).innerHTML;
            CKEDITOR.instances['interaction_description_detail'].setData(interaction_description);
			document.getElementById("creator_name_detail").value = creator_name;
			document.getElementById("creator_datetime_detail").value = creator_datetime;
			document.getElementById("status_name_detail").value = status_name;		
			document.getElementById("planned_start_datetime_detail").value = planned_start_datetime;
			document.getElementById("actual_start_datetime_detail").value = actual_start_datetime;
			document.getElementById("actual_end_datetime_detail").value = actual_end_datetime;
			document.getElementById("actual_cancel_datetime_detail").value = actual_cancel_datetime;
    }
	
	function toexcel(flag_view_detail,startdate,enddate,id,status,interaction_type_id) {
            location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/interaction_report_toexcel?flag_view_detail=" + flag_view_detail +"&startdate=" + startdate + "&enddate=" + enddate + "&id=" + id + "&status=" + status + "&interaction_type_id=" + interaction_type_id;
    }
    // M59
    function toexcel_creator(flag_view_detail,startdate,enddate,creator_id,status,interaction_type_id,id) {
            location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_interaction/interaction_report_toexcel_bycreator?flag_view_detail=" + flag_view_detail +"&startdate=" + startdate + "&enddate=" + enddate + "&creator_id=" + creator_id + "&status=" + status + "&interaction_type_id=" + interaction_type_id;
    }
    // M59
</script>
<div style="font-weight:bold;font-size:18px;">Export To Excel&nbsp;&nbsp;&nbsp;
<?php 
	if ($creator_id == ''){
		echo "<a title='Export To Excel' onclick='toexcel(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$id."\",\"".$status."\",\"".$interaction_type_id."\")' style='cursor:pointer')>
			  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
	}
	// M59
	else {
		echo "<a title='Export To Excel' onclick='toexcel_creator(\"".$flag_view_detail."\",\"".$startdate."\",\"".$enddate."\",\"".$creator_id."\",\"".$status."\",\"".$interaction_type_id."\")' style='cursor:pointer')>
			  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
	}
	// M59
?>
</div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_user">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Number</th>
				<th style="display:none">Interaction ID</th>
				<th>Customer Name</th>
                <th>Status</th>
                <th>Creator Name</th>
                <th>Create Datetime</th>
				<th>View Detail</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($list_report_interaction_detail as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->interaction_id."</td>";
				 echo "<td>".$p->customer_name."</td>";
				 echo "<td>".$p->status_name."</td>";
				 echo "<td>".$p->creator_name."</td>";
				 echo "<td>".date("Y-m-d H:i:s",strtotime($p->creator_datetime))."</td>";
				 echo "<td><span id='description_".$p->interaction_id."' style='display:none;'>".$p->interaction_description."</span><a href='#modal_view_detail' data-toggle='modal' onClick='form_detail(\"".$p->interaction_id."\",\"".$p->interaction_type_name."\",\"".$p->customer_name."\",\"".$p->customer_phone."\",\"".$p->customer_email."\",\"".$p->queue_number."\",\"".$p->creator_name."\",\"description_".$p->interaction_id."\",\"".($p->creator_datetime ? date("Y-m-d H:i:s", strtotime($p->creator_datetime)) : '')."\",\"".$p->status_name."\",\"".($p->planned_start_datetime ? date("Y-m-d H:i:s", strtotime($p->planned_start_datetime)) : '')."\",\"".($p->actual_start_datetime ? date("Y-m-d H:i:s", strtotime($p->actual_start_datetime)) : '')."\",\"".($p->actual_end_datetime ? date("Y-m-d H:i:s", strtotime($p->actual_end_datetime)) : '')."\",\"".($p->actual_cancel_datetime ? date("Y-m-d H:i:s", strtotime($p->actual_cancel_datetime)) : '')."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
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
				<h3>Interaction Report Detail</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_interaction_detail" id="form_new_script" method="post" action="">
			<div class="control-group cso-form-row">
					<label for="interaction_id_detail" class="cso-form-label">Interaction ID</label>
					<input type="text" id="interaction_id_detail" name="interaction_id_detail" disabled="disabled">
			</div>
           <div class="control-group cso-form-row">
					<label for="interaction_type_detail" class="cso-form-label">Interaction Type Name</label>
					<input type="text" id="interaction_type_detail" name="interaction_type_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="customer_name_detail" class="cso-form-label">Customer Name</label>
					<input type="text" id="customer_name_detail" name="customer_name_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="customer_phone_detail" class="cso-form-label">Customer Phone</label>
					<input type="text" id="customer_phone_detail" name="customer_phone_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="customer_email_detail" class="cso-form-label">Customer Email</label>
					<input type="text" id="customer_email_detail" name="customer_email_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="queue_number_detail" class="cso-form-label">Queue Number</label>
					<input type="text" id="queue_number_detail" name="queue_number_detail" disabled="disabled">
			</div>
            <div class"control-group cso-form-row">
						<label for="interaction_description_detail" class="cso-form-label">Interaction Description</label>
						<textarea id="interaction_description_detail" name="interaction_description_detail" cols="20" rows="5" disabled="disabled"></textarea>
			</div>
            <div class="control-group cso-form-row">
					<label for="status_name_detail" class="cso-form-label">Status</label>
					<input type="text" id="status_name_detail" name="status_name_detail" disabled="disabled">
			</div>
			<div class="control-group cso-form-row">
					<label for="creator_name_detail" class="cso-form-label">Creator Name</label>
					<input type="text" id="creator_name_detail" name="creator_name_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="creator_datetime_detail" class="cso-form-label">Create Datetime</label>
					<input type="text" id="creator_datetime_detail" name="creator_datetime_detail" disabled="disabled">
			</div>   
            <div class="control-group cso-form-row">
					<label for="planned_start_datetime_detail" class="cso-form-label">Planned Start Datetime</label>
					<input type="text" id="planned_start_datetime_detail" name="planned_start_datetime_detail" disabled="disabled">
			</div>        
             <div class="control-group cso-form-row">
					<label for="actual_start_datetime_detail" class="cso-form-label">Actual Start Datetime</label>
					<input type="text" id="actual_start_datetime_detail" name="actual_start_datetime_detail" disabled="disabled">
			</div>   
            
             <div class="control-group cso-form-row">
					<label for="actual_end_datetime_detail" class="cso-form-label">Actual End Datetime</label>
					<input type="text" id="actual_end_datetime_detail" name="actual_end_datetime_detail" disabled="disabled">
			</div>
            <div class="control-group cso-form-row">
					<label for="actual_cancel_datetime_detail" class="cso-form-label">Actual Cancel Datetime</label>
					<input type="text" id="actual_cancel_datetime_detail" name="actual_cancel_datetime_detail" disabled="disabled">
			</div>     
			</form>
		</div>
</body>
</html>
