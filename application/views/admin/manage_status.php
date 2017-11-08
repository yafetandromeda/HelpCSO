<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<title>Status Management</title>
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
		
		var status_id = document.getElementById('text-statusID').value;
		var status_name = document.getElementById('text-statusname').value;
		
		if(status_name == '') {
			errtxt = errtxt + '-. Status Name still empty\n';	
			flag = 1; 
		}
		var status_type = document.getElementById('status_type').value;
		if(status_type == '') {
			errtxt = errtxt + '-. Status Type is required\n';	
			flag = 1; 
		}
		var status_active = document.getElementById('status_active').value;
		if(status_active == '') {
			errtxt = errtxt + '-. Status Active is required\n';	
			flag = 1; 
		}
		
		var status_primary = document.getElementById('status_primary').value;
		if(status_primary == '') {
			errtxt = errtxt + '-. Status Primary is required\n';	
			flag = 1; 
		}
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_status/add_status',
						data: "text-statusID=" + status_id + "&text-statusname=" + encodeURIComponent(status_name) + "&status_type=" + status_type + "&status_active=" + status_active +"&status_primary=" + status_primary		
					}).done(function(message){
						alert("New data status has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_status/index_status";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_status/index_status";
					});
		}
	}
	
	function cekData_edit(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		var status_id_edit = document.getElementById('text-statusID_edit').value;
		var status_name_edit = document.getElementById('text-statusname_edit').value;
		
		if(status_name_edit == '') {
			errtxt = errtxt + '-. Status Name still empty\n';	
			flag = 1; 
		}
		
		var status_type_edit = document.getElementById('status_type_edit').value;
		if(status_type_edit == '') {
			errtxt = errtxt + '-. Status Type is required\n';	
			flag = 1; 
		}
		
		var status_active_edit = document.getElementById('status_active_edit').value;
		if(status_active_edit == '') {
			errtxt = errtxt + '-. Status Active Not Chosen\n';	
			flag = 1; 
		}
		
		var status_primary_edit = document.getElementById('status_primary_edit').value;
		if(status_primary_edit == '') {
			errtxt = errtxt + '-. Status Primary Not Chosen\n';	
			flag = 1; 
		}
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_status/edit_status',
						data: "text-statusID_edit= " +status_id_edit+ "&text-statusname_edit=" + encodeURIComponent(status_name_edit) + "&status_type_edit=" + status_type_edit + "&status_active_edit=" + status_active_edit + "&status_primary_edit=" + status_primary_edit	
					}).done(function(message){
						alert("Data Status has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_status/index_status";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_status/index_status";
					});
		}
	}
	
	function form_edit(status_id,status_name,status_type,status_active,status_primary)

        {

            document.getElementById("text-statusID_edit").value = status_id;
            document.getElementById("text-statusname_edit").value = status_name;
			document.getElementById("status_type_edit").value = status_type;
			document.getElementById("status_active_edit").value = status_active;
			document.getElementById("status_primary_edit").value = status_primary;
			if (status_primary == 1){
				document.getElementById("div_status_type_edit").style.display = 'none'
				document.getElementById("div_status_active_edit").style.display = 'none'
			}
			else {
            	document.getElementById("div_status_type_edit").style.display = 'table-row'
				document.getElementById("div_status_active_edit").style.display = 'table-row'	
			}
        }
</script>
	
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<div id="search_list_user" style="margin: 1% 0px;">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Status Number</th>
				<th style="display:none">Status ID</th>
				<th>Status Name</th>
				<th>Status Type</th>
				<th>Status Active</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($data_status as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->status_id."</td>";
				 echo "<td>".$p->status_name."</td>";
				 echo "<td>".$p->status_type."</td>";
				 echo "<td>".$p->status_active_name."</td>";
				 echo "<td><a href='#modal_edit_status' data-toggle='modal'";?> onClick="form_edit('<?php echo $p->status_id; ?>','<?php echo $p->status_name; ?>','<?php echo $p->status_flag; ?>','<?php echo $p->status_active; ?>','<?php echo $p->status_primary; ?>')" <?php echo "><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
	<a href='#modal_add_status' data-toggle="modal" style="display:none;"><input type='submit' name='add_button' id='add_button' value='add' class="btn btn-primary"/></a>
</form>

		<div id="modal_add_status" class="modal hide fade" style="width: 375px; height: 500px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Add Status</h3>
			</div>
			<div class="modal-body">
				<form name="form_add_status" id="form_add_status" method="post" action="">
					<div class"control-group cso-form-row">
							<label for="text-statusID" class="cso-form-label">Status ID</label>
							<input type="text" id="text-statusID" name="text-statusID" value="<?php echo $status_id;?>" disabled="disabled">
					</div>
					<div class"control-group cso-form-row">
							<label for="text-statusname" class="cso-form-label">Status Name</label>
							<input type="text" id="text-statusname" name="text-statusname">
					</div>		
                   	
                    <div class"control-group cso-form-row">
							<label for="status_type" class="cso-form-label">Status Type</label>
							<select id="status_type" name="status_type">
								<option value=''>--choose--</option>
								<option value='i'>Interaction</option>
                                <option value='t'>Ticket</option>
							</select>
					</div>
                    
					<div class"control-group cso-form-row">
							<label for="status_active" class="cso-form-label">Status Active</label>
							<select id="status_active" name="status_active">
								<option value=''>--choose--</option>
								<?php foreach ($pil_active as $p):
										echo "<option value='".$p->code_id."'>".$p->status_active."</option>";
									  endforeach;
								?>
								</select>
					</div>
                    <div class"control-group cso-form-row">
							<label for="status_primary" class="cso-form-label">Status Primary</label>
							<select id="status_primary" name="status_primary">
								<option value=''>--choose--</option>
								<option value='1'>Status Primary</option>
                                <option value='2'>Not Status Primary</option>
							</select>
					</div>
					</form>
			</div>
			<div class="modal-footer">
            	<button type="button" class="btn btn_primary" id="add_status" data-dismiss="modal" onClick="cekData_add()">Add Status</button>
            </div>
		</div>

		<div id="modal_edit_status"class="modal hide fade" style="width: 375px; height: 450px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Status</h3>
			</div>
			<div class="modal-body">
				<form name="form_edit_status" id="form_edit_status" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="text-statusID" class="cso-form-label">Status ID</label>
						<input type="text" id="text-statusID_edit" name="text-statusID_edit"  disabled="disabled">
				</div>
				<div class"control-group cso-form-row">
						<label for="text-statusname" class="cso-form-label">Status Name</label>
						<input type="text" id="text-statusname_edit" name="text-statusname_edit">
				</div>
				<div class"control-group cso-form-row" id="div_status_type_edit" style="display:none;">
							<label for="status_type" class="cso-form-label">Status Type</label>
							<select id="status_type_edit" name="status_type_edit">
								<option value='i'>Interaction</option>
                                <option value='t'>Ticket</option>
							</select>
				</div>
                <div class"control-group cso-form-row" style="display:none;">
							<label for="status_active_edit" class="cso-form-label">Status Active</label>
							<select id="status_active_edit" name="status_active_edit">
								<?php foreach ($pil_active as $p):
										echo "<option value='".$p->code_id."'>".$p->status_active."</option>";
									  endforeach;
								?>
								</select>
				</div>
                
                <div class"control-group cso-form-row" style="display:none;">
							<label for="status_primary" class="cso-form-label">Status Primary</label>
							<select id="status_primary_edit" name="status_primary_edit">
								<option value='1'>Status Primary</option>
                                <option value='2'>Not Status Primary</option>
							</select>
					</div>
			</form>
			</div>
			<div class="modal-footer">
					<button type="button" class="btn btn_primary" id="update_status" data-dismiss="modal" onClick="cekData_edit()">Update Status</button>
			</div>
		</div>
</body>
</html>
