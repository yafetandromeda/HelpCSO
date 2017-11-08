<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<title>Interaction Type Management</title>
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
		
		var interaction_type_id = document.getElementById('text-interaction_type_ID').value;
		var interaction_type_name = document.getElementById('text-interaction_type_name').value;
		if(interaction_type_name == '') {
			errtxt = errtxt + '-. Interaction Type Name Still Empty\n';	
			flag = 1; 
		}

		var status_active = document.getElementById('status_active').value;
		if(status_active == '') {
			errtxt = errtxt + '-. Status Active User Group Not Chosen\n';	
			flag = 1; 
		}
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_interaction/add_interaction_type',
						data: "text-interaction_type_ID=" + interaction_type_id + "&text-interaction_type_name=" + encodeURIComponent(interaction_type_name) + "&status_active=" + status_active		
					}).done(function(message){
						alert("New Data Interaction Type has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_interaction/index_interaction_type";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_interaction/index_interaction_type";
					});
		}
	}
	
	function cekData_edit(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		var interaction_type_id_edit = document.getElementById('text-interaction_type_ID_edit').value;
		var interaction_type_name_edit = document.getElementById('text-interaction_type_name_edit').value;
		var status_active_edit = document.getElementById('status_active_edit').value;
		if(interaction_type_name_edit == '') {
			errtxt = errtxt + '-. Interaction Type Name Still Empty\n';	
			flag = 1; 
		}
		
		if(status_active == '') {
			errtxt = errtxt + '-. Status Active Interaction Type Not Chosen\n';	
			flag = 1; 
		}
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_interaction/edit_interaction_type',
						data: "text-interaction_type_ID_edit= " + interaction_type_id_edit + "&text-interaction_type_name_edit=" + encodeURIComponent(interaction_type_name_edit) + "&status_active_edit=" + status_active_edit		
					}).done(function(message){
						alert("Data Interaction Type has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_interaction/index_interaction_type";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_interaction/index_interaction_type";
					});
		}
	}
	
	function form_edit(interaction_type_ID,interaction_type_name,active_id)

        {
            document.getElementById("text-interaction_type_ID_edit").value = interaction_type_ID;
            document.getElementById("text-interaction_type_name_edit").value = interaction_type_name; 
			document.getElementById("status_active_edit").value = active_id;

        }
	
	function search_interaction_type() {
	 		var text_search_interaction_type  = document.getElementById('text-search_interaction_type').value;
			
			if(text_search_interaction_type == ''){
				location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_interaction/index_interaction_type";
			}
			else {	
				location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_interaction/search_interaction_type?text_search_interaction_type=" +encodeURIComponent(text_search_interaction_type)
			}
	}
	
	function search_interaction_type_suggestion(event) {
	  var text_search_interaction_type  = document.getElementById('text-search_interaction_type').value;
	  document.getElementById('search_suggestion').style.visibility="visible";
	  var keyCode = event.keyCode;
	  
	 if(keyCode == 40){
	 		if(text_search_interaction_type == ''){
					document.getElementById('search_suggestion').style.visibility="hidden";
			}
			else{
				k = i + 1;
				if($('#li' + k).length > 0) {
						i = i + 1;
						j = i - 1;
						document.getElementById('li' + i).className = 'hovered';
						//document.getElementById('text-search_user').value = document.getElementById('li' + i).textContent;
						if(j>0){
						document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');
						}
				}
				else { 
					   if (i == 1) j = i - 1;
					   else j = i;
					   i = 1; 
					   document.getElementById('li' + i).className = 'hovered';
					   //document.getElementById('text-search_user').value = document.getElementById('li' + i).textContent;
					   document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
					}
			}
		}
		else if(keyCode == 38){
			if(text_search_interaction_type == ''){
					document.getElementById('search_suggestion').style.visibility="hidden";
			}
			else{
				k = i - 1;
				if($('#li' + k).length > 0) {
						i = i - 1;
						j = i + 1;
						document.getElementById('li' + i).className = 'hovered';
						//document.getElementById('text-search_user').value = document.getElementById('li' + i).textContent;
						document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');
				}
				else {
				for(var data_max=1;data_max<=6;data_max++){
					   if($('#li' + data_max).length == 0) { 
							i = data_max - 1; 
							break;
						}
				}
					   j = 1;
					   document.getElementById('li' + i).className = 'hovered';
					   //document.getElementById('text-search_user').value = document.getElementById('li' + i).textContent;
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
			document.getElementById('text-search_interaction_type').value = document.getElementById('li' + i).textContent;
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

					url: "<?php echo base_url();?>index.php/admin/ctr_manage_interaction/search_interaction_type_suggestion?text_search_suggestion=" +text_search_interaction_type,
				   success: function(data_interaction_type){
						if(data_interaction_type){
							$("#search_suggestion").html(data_interaction_type);
							i = 0;
							j = 0;
							k = 0;
						}
					}   
			   });
		}
	}
	function chosenText1(){
			document.getElementById('text-search_interaction_type').value = document.getElementById('li1').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
			
	}
	function chosenText2(){
			document.getElementById('text-search_interaction_type').value = document.getElementById('li2').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText3(){
			document.getElementById('text-search_interaction_type').value = document.getElementById('li3').textContent;
			$("#search_suggestion").html("");
	}
	function chosenText4(){
			document.getElementById('text-search_interaction_type').value = document.getElementById('li4').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText5(){
			document.getElementById('text-search_interaction_type').value = document.getElementById('li5').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	
</script>
	
</head>

<body>
<div style="font-weight:bold;font-size:18px;" class="form-horizontal">Search Interaction Type&nbsp;&nbsp;&nbsp;
		<input type="text" name="text-search_interaction_type" id="text-search_interaction_type" onKeyUp="search_interaction_type_suggestion(event)" placeholder="Search Interaction Type">
		<input type="button" name="search_interaction_type" id="search_interaction_type"  value="Search" onClick="search_interaction_type()" class="btn btn-primary" />
</div>
<div id="search_suggestion"></div>
<form id="form1" name="form1" method="post" action="">
<div id="search_list_group" style="margin: 1% 0px;">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Interaction Type Number</th>
				<th style="display:none">Interaction Type ID</th>
				<th>Interaction Type Name</th>
				<th>Status Active</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($data_interaction_type as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->interaction_type_id."</td>";
				 echo "<td>".$p->interaction_type_name."</td>";
				 echo "<td>".$p->status_active_name."</td>";
				 echo "<td><a href='#modal_edit_interaction_type' data-toggle='modal'";?> onClick="form_edit('<?php echo $p->interaction_type_id; ?>','<?php echo $p->interaction_type_name; ?>','<?php echo $p->status_active; ?>')" <?php echo "><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
	<a href='#modal_add_interaction_type' data-toggle="modal"><input type='submit' name='add_button' id='add_button' value='add' class="btn btn-primary"/></a>
</form>

		<div id="modal_add_interaction_type" class="modal hide fade" style="width: 375px; height: 500px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Add Interaction Type</h3>
			</div>
			<div class="modal-body">
				<form name="form_add_group" id="form_add_group" method="post" action="">
					<div class"control-group cso-form-row">
							<label for="text-interaction_type_ID" class="cso-form-label">Interaction Type ID</label>
							<input type="text" id="text-interaction_type_ID" name="text-interaction_type_ID" value="<?php echo $interaction_type_id;?>" disabled="disabled">
					</div>
					<div class"control-group cso-form-row">
							<label for="text-interaction_type_name" class="cso-form-label">Interaction Type Name</label>
							<input type="text" id="text-interaction_type_name" name="text-interaction_type_name">
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
            	<button type="button" class="btn btn_primary" id="add_group" data-dismiss="modal" onClick="cekData_add()">Add Interaction Type</button>
            </div>
		</div>

		<div id="modal_edit_interaction_type"class="modal hide fade" style="width: 375px; height: 450px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Interaction Type</h3>
			</div>
			<div class="modal-body">
				<form name="form_edit_group" id="form_edit_group" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="text-interaction_type_ID" class="cso-form-label">Interaction Type ID</label>
						<input type="text" id="text-interaction_type_ID_edit" name="text-interaction_type_ID_edit"  disabled="disabled">
				</div>
				<div class"control-group cso-form-row">
						<label for="text-interaction_type_name" class="cso-form-label">Interaction Type Name</label>
						<input type="text" id="text-interaction_type_name_edit" name="text-interaction_type_name_edit">
				</div>
                <div class"control-group cso-form-row">
							<label for="status_active" class="cso-form-label">Status Active</label>
							<select id="status_active_edit" name="status_active_edit">
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
					<button type="button" class="btn btn_primary" id="update_group" data-dismiss="modal" onClick="cekData_edit()">Update Interaction Type</button>
			</div>
		</div>
</body>
</html>
