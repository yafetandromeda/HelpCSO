<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<title>SubStatus Management</title>
<script type="text/javascript">
	var i = 0;
	var j = 0;
	var k = 0;
	$("body").click(function() {
		document.getElementById('search_suggestion').style.visibility="hidden";
	});
	
	$("#search_suggestion").click(function(e) {
		e.stopPropagation();
	});
	
	function cekData_add(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		
		var substatus_id = document.getElementById('text-substatusID').value;
		var substatus_name = document.getElementById('text-substatusname').value;
		
		if(substatus_name == '') {
			errtxt = errtxt + '-. Sub Status Name still empty\n';	
			flag = 1; 
		}
		var substatus_type = document.getElementById('substatus_type').value;
		if(substatus_type == '') {
			errtxt = errtxt + '-. Sub Status Type is required\n';	
			flag = 1; 
		}
		var substatus_active = document.getElementById('substatus_active').value;
		if(substatus_active == '') {
			errtxt = errtxt + '-. Status Active is required\n';	
			flag = 1; 
		}
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_status/add_substatus',
						data: "text-substatusID=" + substatus_id + "&text-substatusname=" + encodeURIComponent(substatus_name) + "&substatus_type=" + substatus_type + "&substatus_active=" + substatus_active	
					}).done(function(message){
						alert("New Data Status has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_status/index_substatus";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_status/index_substatus";
					});
		}
	}
	
	function cekData_edit(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		var substatus_id_edit = document.getElementById('text-substatusID_edit').value;
		var substatus_name_edit = document.getElementById('text-substatusname_edit').value;
		
		if(substatus_name_edit == '') {
			errtxt = errtxt + '-. Sub Status Name still empty\n';	
			flag = 1; 
		}
		
		var substatus_type_edit = document.getElementById('substatus_type_edit').value;
		if(substatus_type_edit == '') {
			errtxt = errtxt + '-. Sub Status Type is required\n';	
			flag = 1; 
		}
		
		var substatus_active_edit = document.getElementById('substatus_active_edit').value;
		if(substatus_active_edit == '') {
			errtxt = errtxt + '-. Status Active Not Chosen\n';	
			flag = 1; 
		}
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_status/edit_substatus',
						data: "text-substatusID_edit= " +substatus_id_edit+ "&text-substatusname_edit=" + encodeURIComponent(substatus_name_edit) + "&substatus_type_edit=" + substatus_type_edit + "&substatus_active_edit=" + substatus_active_edit 
					}).done(function(message){
						alert("Data Sub Status has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_status/index_substatus";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_status/index_substatus";
					});
		}
	}
	
	function form_edit(substatus_id,substatus_name,substatus_type,substatus_active)

        {

            document.getElementById("text-substatusID_edit").value = substatus_id;
            document.getElementById("text-substatusname_edit").value = substatus_name;
			document.getElementById("substatus_type_edit").value = substatus_type;
			document.getElementById("substatus_active_edit").value = substatus_active;
        }
</script>
	
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<div id="search_list_user" style="margin: 1% 0px;">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Sub Status Number</th>
				<th style="display:none">Sub Status ID</th>
				<th>Sub Status Name</th>
				<th>Sub Status Type</th>
				<th>Status Active</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($data_substatus as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->substatus_id."</td>";
				 echo "<td>".$p->substatus_name."</td>";
				 echo "<td>".$p->substatus_type."</td>";
				 echo "<td>".$p->status_active_name."</td>";
				 echo "<td><a href='#modal_edit_substatus' data-toggle='modal'";?> onClick="form_edit('<?php echo $p->substatus_id; ?>','<?php echo $p->substatus_name; ?>','<?php echo $p->substatus_flag; ?>','<?php echo $p->status_active; ?>')" <?php echo "><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
	<a href='#modal_add_substatus' data-toggle="modal" style="display:none;"><input type='submit' name='add_button' id='add_button' value='add' class="btn btn-primary"/></a>
</form>

		<div id="modal_add_substatus" class="modal hide fade" style="width: 375px; height: 500px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Add Sub Status</h3>
			</div>
			<div class="modal-body">
				<form name="form_add_substatus" id="form_add_substatus" method="post" action="">
					<div class"control-group cso-form-row">
							<label for="text-substatusID" class="cso-form-label">Sub Status ID</label>
							<input type="text" id="text-substatusID" name="text-substatusID" value="<?php echo $substatus_id;?>" disabled="disabled">
					</div>
					<div class"control-group cso-form-row">
							<label for="text-substatusname" class="cso-form-label">Sub Status Name</label>
							<input type="text" id="text-substatusname" name="text-substatusname">
					</div>		
                   	
                    <div class"control-group cso-form-row">
							<label for="substatus_type" class="cso-form-label">Sub Status Type</label>
							<select id="substatus_type" name="substatus_type">
								<option value=''>--choose--</option>
								<option value='i'>Interaction</option>
                                <option value='t'>Ticket</option>
							</select>
					</div>
                    
					<div class"control-group cso-form-row">
							<label for="substatus_active" class="cso-form-label">Status Active</label>
							<select id="substatus_active" name="substatus_active">
								<option value=''>--choose--</option>
								<?php foreach ($pil_active as $p):
										echo "<option value='".$p->code_id."'>".$p->status_active."</option>";
									  endforeach;
								?>
								</select>
					</div>
					</form>
			</div>
			<div class="modal-footer">
            	<button type="button" class="btn btn_primary" id="add_substatus" data-dismiss="modal" onClick="cekData_add()">Add Sub Status</button>
            </div>
		</div>

		<div id="modal_edit_substatus"class="modal hide fade" style="width: 375px; height: 450px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Status</h3>
			</div>
			<div class="modal-body">
				<form name="form_edit_substatus" id="form_edit_substatus" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="text-substatusID" class="cso-form-label">Sub Status ID</label>
						<input type="text" id="text-substatusID_edit" name="text-substatusID_edit"  disabled="disabled">
				</div>
				<div class"control-group cso-form-row">
						<label for="text-substatusname" class="cso-form-label">Sub Status Name</label>
						<input type="text" id="text-substatusname_edit" name="text-substatusname_edit">
				</div>
				<div class"control-group cso-form-row" style="display:none;">
							<label for="substatus_type" class="cso-form-label" >Sub Status Type</label>
							<select id="substatus_type_edit" name="substatus_type_edit">
								<option value='i'>Interaction</option>
                                <option value='t'>Ticket</option>
							</select>
				</div>
                <div class"control-group cso-form-row" style="display:none;">
							<label for="substatus_active_edit" class="cso-form-label">Status Active</label>
							<select id="substatus_active_edit" name="substatus_active_edit">
								<?php foreach ($pil_active as $p):
										echo "<option value='".$p->code_id."'>".$p->status_active."</option>";
									  endforeach;
								?>
								</select>
				</div>
			</form>
			</div>
			<div class="modal-footer">
					<button type="button" class="btn btn_primary" id="update_substatus" data-dismiss="modal" onClick="cekData_edit()">Update Sub Status</button>
			</div>
		</div>
</body>
</html>
