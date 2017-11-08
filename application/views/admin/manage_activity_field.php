<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Manage Activity Fields</title>
</head>

<script type="text/javascript">
	var i = 0;
	var j = 0;
	var k = 0;
	function cekData_Add(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		var activity_id = document.getElementById('activity_id').value;
		var activity_description = document.getElementById('activity_description').value;
		var field_name = document.getElementById('text-field_name').value;
		if(field_name == '') {
			errtxt = errtxt + '-. Field Name is required\n';	
			flag = 1; 
		}
		
		var field_mandatory = document.getElementById('field_mandatory').value;
		if(field_mandatory == '') {
			errtxt = errtxt + '-. Field Mandatory is required\n';	
			flag = 1; 
		}
		
		var status_active = document.getElementById('status_active').value;
		if(status_active == '') {
			errtxt = errtxt + '-. Status Active is required\n';	
			flag = 1; 
		}
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/add_activity_field',
						data: "field_name=" + encodeURIComponent(field_name) + "&activity_id=" + activity_id + "&field_mandatory=" + field_mandatory + "&status_active=" + status_active
					}).done(function(message){
						alert("New Field has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/manage_activity_field?activity_id=" + activity_id + "&activity_description=" + activity_description;
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/manage_activity_field?activity_id=" + activity_id + "&activity_description=" + activity_description;
					});
		}
	}
	
	function saveData_Edit(){
		var flag = 0;
		var errtxt = 'ERROR :\n';
		var activity_id = document.getElementById('activity_id').value;
		var activity_description = document.getElementById('activity_description').value;
		var field_id_edit = document.getElementById('text-field_id_edit').value;		
		var field_name_edit = document.getElementById('text-field_name_edit').value;
		var field_mandatory_edit = document.getElementById('field_mandatory_edit').value;
		if(field_name_edit == '') {
			errtxt = errtxt + '-. Field Name is required\n';	
			flag = 1; 
		}
		
		var status_active_edit = document.getElementById('status_active_edit').value;
		if(status_active_edit == '') {
			errtxt = errtxt + '-. Status Active is required\n';	
			flag = 1; 
		}

		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/edit_activity_field',
						data: "field_id=" + field_id_edit + "&field_name=" + encodeURIComponent(field_name_edit) + "&field_mandatory=" + field_mandatory_edit + "&status_active=" + status_active_edit
					}).done(function(message){
						alert("Field has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/manage_activity_field?activity_id=" + activity_id + "&activity_description=" + activity_description;
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/manage_activity_field?activity_id=" + activity_id + "&activity_description=" + activity_description;
					});
		}
	}
	function search_field() {
	  var activity_id = document.getElementById('activity_id').value;
	  var text_search = document.getElementById('text_search').value;
		if(text_search != ''){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/search_activity_field?text_search=" + encodeURIComponent(text_search) + "&activity_id=" + activity_id;
		}	
		else {	
				location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/search_activity_field?activity_id=" + activity_id;
		}	
	}
	
	function search_field_suggestion(event) {
	  var activity_id = document.getElementById('activity_id').value;
	  var text_search_suggestion  = document.getElementById('text_search').value;
	  document.getElementById('search_suggestion').style.visibility="visible";
	  var keyCode = event.keyCode;
	  
	 if(keyCode == 40){
	 		if(text_search_suggestion == ''){
					document.getElementById('search_suggestion').style.visibility="hidden";
				}
			else{
				k = i + 1;
				if($('#li' + k).length > 0) {
						i = i + 1;
						j = i - 1;
						document.getElementById('li' + i).className = 'hovered';
						//document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
						if(j>0){
						document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');
						}
				}
				else { 
					   if (i == 1) j = i - 1;
					   else j = i;
					   i = 1; 
					   document.getElementById('li' + i).className = 'hovered';
					   //document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
					   if (j>0){
					   document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
					}
				}
			}
		}
		else if(keyCode == 38){
			if(text_search_suggestion == ''){
					document.getElementById('search_suggestion').style.visibility="hidden";
				}
			else{
				k = i - 1;
				if($('#li' + k).length > 0) {
						i = i - 1;
						j = i + 1;
						document.getElementById('li' + i).className = 'hovered';
						//document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
						document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');
				}
				else {
				for(var data_max=1;data_max<=5;data_max++){
					   if($('#li' + data_max).length == 0) { 
							i = data_max - 1; 
							break;
						}
				}
					   j = 1;
					   document.getElementById('li' + i).className = 'hovered';
					   //document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
					   document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
					
				}
			}
		}
		else if(keyCode == 37 || keyCode == 39){
			j = i;
			document.getElementById('search_suggestion').style.visibility="hidden";
			if (j > 0){
				document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
			}
			i = 0;
			j = 0;
			k = 0;
		}

		else if (keyCode == 13){
			j = i;
			document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			if (j > 0){
				document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
			}
			$("#search_suggestion").html("");
			i = 0;
			j = 0;
			k = 0;
		 }
	 else{
		$.ajax({	

					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/search_field_suggestion?text_search_suggestion=" +encodeURIComponent(text_search_suggestion)
							+ "&activity_id=" + activity_id,
				   success: function(data_field_suggestion){
						if(data_field_suggestion){
							$("#search_suggestion").html(data_field_suggestion);
							i = 0;
							j = 0;
							k = 0;
						}
					}   
			   });
			}
	}
	
	function chosenText1(){
			document.getElementById('text_search').value = document.getElementById('li1').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
			
	}
	function chosenText2(){
			document.getElementById('text_search').value = document.getElementById('li2').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText3(){
			document.getElementById('text_search').value = document.getElementById('li3').textContent;
			$("#search_suggestion").html("");
	}
	function chosenText4(){
			document.getElementById('text_search').value = document.getElementById('li4').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText5(){
			document.getElementById('text_search').value = document.getElementById('li5').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	
	function form_edit(field_id,field_name,field_mandatory,status_active){
            document.getElementById("text-field_id_edit").value = field_id;
            document.getElementById("text-field_name_edit").value = field_name;
			document.getElementById("field_mandatory_edit").value = field_mandatory;
			document.getElementById("status_active_edit").value = status_active;
    }
	
</script>
<body>
	<div style="font-weight:bold;font-size:18px;">Activity Field&nbsp;&nbsp;&nbsp;
    	<input type="text" name="text_search" id="text_search" placeholder="Search Field" onKeyUp="search_field_suggestion(event)">
		<input type="button" name="search_field" id="search_field"  value="Search" onClick="search_field()" class="btn btn-primary" />
		<div id="search_suggestion" style="margin: 0px 0px 0px 165px;">
		</div>
	</div>
<hr>
<form id="form1" name="form1" method="post" action="" ?>
<h4 align="center">
	<button class="btn pull-left" type='button' onClick="window.history.go(-1);">Back</button>
    <div style="color:#0a476d;"><?php echo "<b>".$activity_description."</b>"; ?></div>
    <br />
</h4>
<input type="hidden" name="activity_id" id="activity_id" value="<?php echo $activity_id; ?>">
<input type="hidden" name="activity_description" id="activity_description" value="<?php echo $activity_description; ?>">
<div id="search_list_fields">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
				<th>Field Number</th>
                <th style="display:none">Field ID</th>
				<th>Field Name</th>
                <th>Field Mandatory</th>
                <th>Status Active</th>
				<th>Edit Field</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($list_activity_field as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->field_id."</td>";
				 echo "<td>".$p->field_name."</td>";
				 echo "<td>".$p->field_mandatory_name."</td>";
				 echo "<td>".$p->status_active_name."</td>";
				 echo "<td><a href='#modal_edit_field' data-toggle='modal' onClick='form_edit(\"".$p->field_id."\",\"".$p->field_name."\",\"".$p->field_mandatory."\",\"".$p->status_active."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
<a href='#modal_add_field' data-toggle="modal" class="btn btn-primary"><i class="icon-plus icon-white"> </i> Add Field</a>

</form>		

	<div id="modal_add_field" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>New Field</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_new_activity_field" id="form_new_activity_field" method="post" action="">
				<div class="control-group cso-form-row">
					<label for="field_id" class="cso-form-label">Field ID</label>
					<input type="text" id="text-field_id" name="text-field_id" value="<?php echo $last_activity_field_id;?>" disabled="disabled">
				</div>
       			<div class="control-group cso-form-row">
					<label for="field_name" class="cso-form-label">Field Name</label>
					<input type="text" id="text-field_name" name="text-field_name">
			 	</div>
				<div class="control-group cso-form-row">
					<label for="field_mandatory" class="cso-form-label">Field Mandatory</label>
					<select id="field_mandatory" name="field_mandatory">
						<option value ="">--choose--</option>
						<option value ="1">Yes</option>
						<option value ="0">No</option>
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
			</form>
		</div>
			<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="add_field" data-dismiss="modal" onClick="cekData_Add()">Add Field</button>
			</div>
	</div>
		
	<div id="modal_edit_field" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Field</h3>
			</div>
			<div class="modal-body-script">
				<form name="form_edit_activity_field" id="form_edit_activity_field" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="field_id_edit" class="cso-form-label">Field ID</label>
						<input type="text" id="text-field_id_edit" name="text-field_id_edit" disabled="disabled">
				</div>
				<div class"control-group cso-form-row">
						<label for="field_name_edit" class="cso-form-label">Field Name</label>
						<input type="text" id="text-field_name_edit" name="text-field_name_edit">
				 </div>
				 <div class="control-group cso-form-row">
					<label for="field_mandatory_edit" class="cso-form-label">Field Mandatory</label>
					<select id="field_mandatory_edit">
						<option value ="1">Yes</option>
						<option value ="0">No</option>
					</select>
			 	</div>
                <div class"control-group cso-form-row">
							<label for="status_active_edit" class="cso-form-label">Status Active</label>
							<select id="status_active_edit" name="status_active_edit">
								<?php foreach ($pil_active as $p):
										echo "<option value='".$p->code_id."'>".$p->status_active."</option>";
									  endforeach;
								?>
								</select>
				</div>
			</form>
		</div>
		<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="edit_field" data-dismiss="modal" onClick="saveData_Edit()">Edit Field</button>
		</div>
	</div>
</body>
</html>
