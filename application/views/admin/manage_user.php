<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<title>User Management</title>
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
		
		var username = document.getElementById('text-username').value;
		if(username == '') {
			errtxt = errtxt + '-. UserName still empty\n';	
			flag = 1; 
		}
		var password = document.getElementById('text-password').value;
		if(password== '') {
			errtxt = errtxt + '-. Password still empty\n';	
			flag = 1; 
		}
		var password_confirmation = document.getElementById('text-password_confirmation').value;
		if(password_confirmation == '' || password_confirmation != password) {
			errtxt = errtxt + '-. Password Confirmation not same \n';	
			flag = 1; 
		}
		var level = document.getElementById('level').value;
		if(level == 0) {
			errtxt = errtxt + '-. Level is required\n';	
			flag = 1; 
		}
		else if (level != 3){
			var user_group = 0;
		}
		else if(level == 3) {
			var user_group = document.getElementById('usergroup').value;
			if(user_group == 0) {
				errtxt = errtxt + '-. User Group is required\n';	
				flag = 1; 
				}
		}
		
		var status_active = document.getElementById('status_active').value;
		if(status_active == '') {
			errtxt = errtxt + '-. Status Active User Not Chosen\n';	
			flag = 1; 
		}
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_user/add_user',
						data: "text-username=" + encodeURIComponent(username) + "&text-password=" + password + "&level=" + level + "&user_group=" + user_group +"&status_active=" + status_active		
					}).done(function(message){
						alert("New data user has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user";
					});
		}
	}
	
	function cekData_edit(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		var userid = document.getElementById('text-userID_edit').value;
		var username = document.getElementById('text-username_edit').value;
		var level = document.getElementById('level_edit').value;
		
		if(username == '') {
			errtxt = errtxt + '-. UserName still empty\n';	
			flag = 1; 
		}
		
		if(level == 0) {
			errtxt = errtxt + '-. Level is required\n';	
			flag = 1; 
		}
		else if (level != 3){
			var user_group_edit = 0;
		}
		else if(level == 3) {
			var user_group_edit = document.getElementById('usergroup_edit').value;
			if(user_group_edit == 0) {
				errtxt = errtxt + '-. User Group is required\n';	
				flag = 1; 
				}
		}
			
		var status_active = document.getElementById('status_active_edit').value;	
		if(status_active == '') {
			errtxt = errtxt + '-. Status Active User Not Chosen\n';	
			flag = 1; 
		}
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url();?>index.php/admin/ctr_manage_user/edit_user',
						data: "user_id= " +userid+ "&text-username_edit=" + encodeURIComponent(username) + "&level_edit=" + level + "&user_group_edit=" + user_group_edit + "&status_active_edit=" + status_active		
					}).done(function(message){
						alert("Data user has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user";
					});
		}
	}
	
	function form_edit(user_id,user_name,level_id,group_id,active_id)
        {

            document.getElementById("text-userID_edit").value = user_id;
            document.getElementById("text-username_edit").value = user_name;
            document.getElementById("level_edit").value = level_id;
			document.getElementById("status_active_edit").value = active_id;
			if (level_id == 3){
			document.getElementById("user_group_edit").style.display = 'table-cell';
			document.getElementById("usergroup_edit").value = group_id;	
			}
			else {
			document.getElementById("user_group_edit").style.display = 'none';
			document.getElementById("usergroup_edit").value = '0';
			}

        }
	
		function search_user() {
	 		var text_search_user  = document.getElementById('text-search_user').value;
			
			if(text_search_user == ''){
				location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user";
			}
			else {	
				location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_user/search_user?text_search_user=" +encodeURIComponent(text_search_user)
			}
	}
	
	function search_user_suggestion(event) {
	  var text_search_user  = document.getElementById('text-search_user').value;
	  document.getElementById('search_suggestion').style.visibility="visible";
	  var keyCode = event.keyCode;
	  
	 if(keyCode == 40){
	 		if(text_search_user == ''){
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
			if(text_search_user == ''){
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
			document.getElementById('text-search_user').value = document.getElementById('li' + i).textContent;
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

					url: "<?php echo base_url();?>index.php/admin/ctr_manage_user/search_user_suggestion?text_search_suggestion=" +encodeURIComponent(text_search_user),
				   success: function(data_user){
						if(data_user){
							$("#search_suggestion").html(data_user);
							i = 0;
							j = 0;
							k = 0;
						}
					}   
			   });
		}
	}
	function chosenText1(){
			document.getElementById('text-search_user').value = document.getElementById('li1').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
			
	}
	function chosenText2(){
			document.getElementById('text-search_user').value = document.getElementById('li2').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText3(){
			document.getElementById('text-search_user').value = document.getElementById('li3').textContent;
			$("#search_suggestion").html("");
	}
	function chosenText4(){
			document.getElementById('text-search_user').value = document.getElementById('li4').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText5(){
			document.getElementById('text-search_user').value = document.getElementById('li5').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	
	function usergroup_fields(flag){
					if (flag == 1) {
						var level =  document.getElementById('level').value
							document.getElementById("user_group").style.visibility = 'visible';
							$.ajax({	
					
										url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_user/usergroup_fields?level=" + level,
									   success: function(data_usergroup){
											if(data_usergroup){
												$("#user_group").html(data_usergroup);
											}
										}   
							});
					}
					else if (flag == 2){
						var level =  document.getElementById('level_edit').value
							document.getElementById("user_group_edit").style.display = 'table-cell';
							document.getElementById("user_group_edit").style.visibility = 'visible';
							$.ajax({	
					
										url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_user/usergroup_fields_edit?level=" + level,
									   success: function(data_usergroup_edit){
											if(data_usergroup_edit){
												$("#user_group_edit").html(data_usergroup_edit);
											}
										}   
							});		
					}		
	}
</script>
	
</head>

<body>
<div style="font-weight:bold;font-size:18px;" class="form-horizontal">Search User&nbsp;&nbsp;&nbsp;
		<input type="text" name="text-search_user" id="text-search_user" onKeyUp="search_user_suggestion(event)" placeholder="Search User">
		<input type="button" name="search_user" id="search_user"  value="Search" onClick="search_user()" class="btn btn-primary" />
</div>
<div id="search_suggestion"></div>
<form id="form1" name="form1" method="post" action="">
<div id="search_list_user" style="margin: 1% 0px;">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>User Number</th>
				<th style="display:none">User ID</th>
				<th>User Name</th>
				<th>Level</th>
				<th>Status Active</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($data_user as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->user_id."</td>";
				 echo "<td>".$p->user_name."</td>";
				 echo "<td>".$p->level."</td>";
				 echo "<td>".$p->status_active."</td>";
				 echo "<td><a href='#modal_edit_user' data-toggle='modal'";?> onClick="form_edit('<?php echo $p->user_id; ?>','<?php echo $p->user_name; ?>','<?php echo $p->level_id; ?>','<?php echo $p->group_id; ?>','<?php echo $p->active_id; ?>')" <?php echo "><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "<td><a href='".base_url()."index.php/admin/ctr_manage_user/delete_user?user_id=".$p->user_id."'";?> onClick="return confirm('Are you sure want to delete [<?php echo $p->user_name; ?>]')" <?php echo "><img src='".base_url()."tools/datatables/media/icon/24x24/Delete.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
	<a href='#modal_add_user' data-toggle="modal"><input type='submit' name='add_button' id='add_button' value='add' class="btn btn-primary"/></a>
</form>

		<div id="modal_add_user" class="modal hide fade" style="width: 375px; height: 500px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Add User</h3>
			</div>
			<div class="modal-body">
				<form name="form_add_user" id="form_add_user" method="post" action="">
					<div class"control-group cso-form-row">
							<label for="text-userID" class="cso-form-label">User ID</label>
							<input type="text" id="text-userID" name="text-userID" value="<?php echo $user_id;?>" disabled="disabled">
					</div>
					<div class"control-group cso-form-row">
							<label for="text-username" class="cso-form-label">User Name</label>
							<input type="text" id="text-username" name="text-username">
					</div>
					<div class"control-group cso-form-row">
							<label for="text-password" class="cso-form-label">Password</label>
							<input type="password" id="text-password" name="text-password">
					</div>
					<div class"control-group cso-form-row">
							<label for="text-password2" class="cso-form-label">Password Confirmation</label>
							<input type="password" id="text-password_confirmation" name="text-password_confirmation">
					</div>
					<div class"control-group cso-form-row">
							<label for="level" class="cso-form-label">Level</label>
							<select id="level" name="level"  onChange="usergroup_fields(1)">
								<option value='0'>--choose--</option>
								<?php foreach ($pil_level as $p):
										echo "<option value='".$p->code_id."'>".$p->level."</option>";
									  endforeach;
								?>
								</select>
					</div>
                    <div id="user_group" class="control-group cso-form-row">
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
            	<button type="button" class="btn btn_primary" id="add_user" data-dismiss="modal" onClick="cekData_add()">Add User</button>
            </div>
		</div>

		<div id="modal_edit_user"class="modal hide fade" style="width: 375px; height: 450px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit User</h3>
			</div>
			<div class="modal-body">
				<form name="form_edit_user" id="form_edit_user" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="text-userID" class="cso-form-label">User ID</label>
						<input type="text" id="text-userID_edit" name="text-userID_edit"  disabled="disabled">
				</div>
				<div class"control-group cso-form-row">
						<label for="text-username" class="cso-form-label">User Name</label>
						<input type="text" id="text-username_edit" name="text-username_edit">
				</div>
				<div class"control-group cso-form-row">
						<label for="level_edit" class="cso-form-label">Level</label>
						<select id="level_edit" name="level_edit" onChange="usergroup_fields(2)">
						<option value='0'>--choose--</option>
							<?php			
								 foreach ($pil_level as $p):
									echo "<option value='".$p->code_id."'>".$p->level."</option>";
								  endforeach;
							?>
							</select>
				</div>
                <div id="user_group_edit" class="control-group cso-form-row">
                	<?php 
							echo "<label for='usergroup_edit' class='cso-form-label'>User Group</label>";
							echo "<select id='usergroup_edit' name='usergroup_edit'>";
							echo "<option value='0'>--choose--</option>";
							foreach ($pil_group as $p):
											echo "<option value='".$p->group_id."'>".$p->group_name."</option>";
							endforeach;
							echo "</select>";
						
					?>
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
					<button type="button" class="btn btn_primary" id="update_user" data-dismiss="modal" onClick="cekData_edit()">Update User</button>
			</div>
		</div>
</body>
</html>
