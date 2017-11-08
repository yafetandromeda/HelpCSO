<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<title>User Group Management</title>
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
		
		var groupname = document.getElementById('text-groupname').value;
		if(groupname == '') {
			errtxt = errtxt + '-. Group Name Still Empty\n';	
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
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_user/add_usergroup',
						data: "text-groupname=" + groupname + "&status_active=" + status_active		
					}).done(function(message){
						alert("New data user group has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user/index_usergroup";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user/index_usergroup";
					});
		}
	}
	
	function cekData_edit(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		var groupid = document.getElementById('text-groupID_edit').value;
		var groupname = document.getElementById('text-groupname_edit').value;
		var status_active = document.getElementById('status_active_edit').value;
		if(groupname == '') {
			errtxt = errtxt + '-. Group Name Still Empty\n';	
			flag = 1; 
		}
		
		if(status_active == '') {
			errtxt = errtxt + '-. Status Active User Group Not Chosen\n';	
			flag = 1; 
		}
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_user/edit_usergroup',
						data: "text-groupID_edit= " +groupid+ "&text-groupname_edit=" + groupname + "&status_active_edit=" + status_active		
					}).done(function(message){
						alert("Data user group has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user/index_usergroup";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user/index_usergroup";
					});
		}
	}
	
	function form_edit(group_id,group_name,active_id)

        {

            document.getElementById("text-groupID_edit").value = group_id;
            document.getElementById("text-groupname_edit").value = group_name; 
			document.getElementById("status_active_edit").value = active_id;

        }
	
	function search_group() {
	 		var text_search_group  = document.getElementById('text-search_group').value;
			
			if(text_search_group == ''){
				location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user/index_usergroup";
			}
			else {	
				location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user/search_group?text_search_group=" +text_search_group
			}
	}
	
	function search_group_suggestion(event) {
	  var text_search_group  = document.getElementById('text-search_group').value;
	  document.getElementById('search_suggestion').style.visibility="visible";
	  var keyCode = event.keyCode;
	  
	 if(keyCode == 40){
	 		if(text_search_group == ''){
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
			if(text_search_group == ''){
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
			document.getElementById('text-search_group').value = document.getElementById('li' + i).textContent;
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

					url: "<?php echo base_url();?>index.php/admin/ctr_manage_user/search_group_suggestion?text_search_suggestion=" +text_search_group,
				   success: function(data_usergroup){
						if(data_usergroup){
							$("#search_suggestion").html(data_usergroup);
							i = 0;
							j = 0;
							k = 0;
						}
					}   
			   });
		}
	}
	function chosenText1(){
			document.getElementById('text-search_group').value = document.getElementById('li1').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
			
	}
	function chosenText2(){
			document.getElementById('text-search_group').value = document.getElementById('li2').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText3(){
			document.getElementById('text-search_group').value = document.getElementById('li3').textContent;
			$("#search_suggestion").html("");
	}
	function chosenText4(){
			document.getElementById('text-search_group').value = document.getElementById('li4').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText5(){
			document.getElementById('text-search_group').value = document.getElementById('li5').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	
</script>
	
</head>

<body>
<div style="font-weight:bold;font-size:18px;" class="form-horizontal">Search User Group&nbsp;&nbsp;&nbsp;
		<input type="text" name="text-search_group" id="text-search_group" onKeyUp="search_group_suggestion(event)" placeholder="Search User Group">
		<input type="button" name="search_group" id="search_group"  value="Search" onClick="search_group()" class="btn btn-primary" />
</div>
<div id="search_suggestion"></div>
<form id="form1" name="form1" method="post" action="">
<div id="search_list_group" style="margin: 1% 0px;">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
				<th>User Group Number</th>
                <th style="display:none">User Group ID</th>
				<th>User Group Name</th>
				<th>Status Active</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($data_group as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->group_id."</td>";
				 echo "<td>".$p->group_name."</td>";
				 echo "<td>".$p->status_active_name."</td>";
				 echo "<td><a href='#modal_edit_group' data-toggle='modal'";?> onClick="form_edit('<?php echo $p->group_id; ?>','<?php echo $p->group_name; ?>','<?php echo $p->status_active; ?>')" <?php echo "><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				/* echo "<td><a href='".base_url()."index.php/admin/ctr_manage_user/delete_usergroup?groupid=".$p->group_id."'";?> onClick="return confirm('Are you sure want to delete [<?php echo $p->group_name; ?>]')" <?php echo "><img src='".base_url()."tools/datatables/media/icon/24x24/Delete.png'></a></td>";*/
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
	<a href='#modal_add_group' data-toggle="modal"><input type='submit' name='add_button' id='add_button' value='add' class="btn btn-primary"/></a>
</form>

		<div id="modal_add_group" class="modal hide fade" style="width: 375px; height: 500px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Add User Group</h3>
			</div>
			<div class="modal-body">
				<form name="form_add_group" id="form_add_group" method="post" action="">
					<div class"control-group cso-form-row">
							<label for="text-groupID" class="cso-form-label">User Group ID</label>
							<input type="text" id="text-groupID" name="text-groupID" value="<?php echo $group_id;?>" disabled="disabled">
					</div>
					<div class"control-group cso-form-row">
							<label for="text-groupname" class="cso-form-label">User Group Name</label>
							<input type="text" id="text-groupname" name="text-groupname">
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
            	<button type="button" class="btn btn_primary" id="add_group" data-dismiss="modal" onClick="cekData_add()">Add User Group</button>
            </div>
		</div>

		<div id="modal_edit_group"class="modal hide fade" style="width: 375px; height: 450px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit User Group</h3>
			</div>
			<div class="modal-body">
				<form name="form_edit_group" id="form_edit_group" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="text-groupID" class="cso-form-label">User Group ID</label>
						<input type="text" id="text-groupID_edit" name="text-groupID_edit"  disabled="disabled">
				</div>
				<div class"control-group cso-form-row">
						<label for="text-groupname" class="cso-form-label">User Group Name</label>
						<input type="text" id="text-groupname_edit" name="text-groupname_edit">
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
					<button type="button" class="btn btn_primary" id="update_group" data-dismiss="modal" onClick="cekData_edit()">Update User Group</button>
			</div>
		</div>
</body>
</html>
