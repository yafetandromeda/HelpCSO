<!-- M007 - YA - Export activity to excel -->
<!-- M011 - YA - Import activity -->
<!-- M049 - YA - Tampilan ticket template bila sudah level 4 dan berbentuk count -->
<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Manage Activity</title>
</head>

<script type="text/javascript">
	var i = 0;
	var j = 0;
	var k = 0;
	window.onload = function(){
		CKEDITOR.replace('text-activity_definition', 
					{
					toolbarGroups: [
							{ name: 'clipboard',   groups: [ 'clipboard', 'undo', 'outdent', 'indent' ] },																	
							{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
							{ name: 'links', groups : [ 'Link','Unlink','Anchor' ] }
						]
					}
		);
		CKEDITOR.replace('text-activity_definition_edit', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo', 'outdent', 'indent' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
						{ name: 'links', groups : [ 'Link','Unlink','Anchor' ] }
					]
					}
		);
	}
	
	function cekData_Add(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		
		var activity_description = document.getElementById('text-activity_description').value;
		activity_description = activity_description.replace(/(?:&nbsp;|<br>)/g,'');
		if(activity_description == '') {
			errtxt = errtxt + '-. Activity Description is required\n';	
			flag = 1; 
		}
		
		var activity_code = document.getElementById('text-activity_code').value;
		if(activity_code == '') {
			errtxt = errtxt + '-. Activity Code is required\n';	
			flag = 1; 
		}
		
		var activity_definition = CKEDITOR.instances['text-activity_definition'].getData();
		activity_definition = activity_definition.replace(/(?:&nbsp;|<br>)/g,'');
		if(activity_definition == '') {
			errtxt = errtxt + '-. Activity Definition still empty\n';	
			flag = 1; 
		}
		
		var activity_level = document.getElementById('activity_level').value;
		if(activity_level == '') {
			errtxt = errtxt + '-. Activity Level is required\n';	
			flag = 1; 
		}
		else if(activity_level == 1){
			var par_activity = 	0;
		}
		else if(activity_level > 1){
			var par_activity = 	document.getElementById('par_activity').value;
			if(par_activity == 0) {
				errtxt = errtxt + '-. Activity\'s parent is required\n';	
				flag = 1; 
				}
		}
		
		var status_active =  document.getElementById('status_active').value;
		if(status_active == '') {
			errtxt = errtxt + '-. Status Active is required\n';	
			flag = 1; 
		}
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/add_activity',
						data: "activity_description=" + encodeURIComponent(activity_description) + "&activity_code=" + activity_code +"&activity_parent=" + par_activity + "&activity_definition=" + encodeURIComponent(activity_definition) + "&activity_level=" + activity_level + "&status_active=" + status_active
					}).done(function(message){
						alert("New Activity has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/index_activity";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/index_activity";
					});
		}
	}
	
	function saveData_Edit(){
		var flag = 0;
		var flag2 = 0;
		var errtxt = 'ERROR :\n';

		var activity_id_edit = document.getElementById('text-activity_id_edit').value;			
		var activity_description_edit = document.getElementById('text-activity_description_edit').value;
		activity_description_edit = activity_description_edit.replace(/(?:&nbsp;|<br>)/g,'');
		if(activity_description_edit == '') {
			errtxt = errtxt + '-. Activity Description is required\n';	
			flag = 1; 
		}
		
		var activity_code_edit = document.getElementById('text-activity_code_edit').value;
		if(activity_code_edit == '') {
			errtxt = errtxt + '-. Activity Code is required\n';	
			flag = 1; 
		}
		
		var activity_definition_edit = CKEDITOR.instances['text-activity_definition_edit'].getData();
		activity_definition_edit = activity_definition_edit.replace(/(?:&nbsp;|<br>)/g,'');
		if(activity_definition_edit == '') {
			errtxt = errtxt + '-. Activity Definition still empty\n';	
			flag = 1; 
		}
		
		var activity_level_edit = document.getElementById('activity_level_edit').value;
		
		if(activity_level_edit == '') {
			errtxt = errtxt + '-. Activity Level is required\n';	
			flag = 1; 
		}
		else if(activity_level_edit == 1){
			var par_activity_edit = 0;
		}
		else if(activity_level_edit > 1){
			var par_activity_edit = document.getElementById('par_activity_edit').value;
			if(par_activity_edit == 0) {
				errtxt = errtxt + '-. Activity\'s parent is required\n';	
				flag = 1; 
				}
		}
		
		var status_active_edit =  document.getElementById('status_active_edit').value;

		var activity_level_first = document.getElementById('activity_level_first').value;
		if(activity_level_edit != activity_level_first){	
				flag2 = 1; 
		}
		
		var status_active_first = document.getElementById('status_active_first').value;
		if(status_active_edit != status_active_first){	
				flag2 = 1; 
		}
		
		if (flag2 == 1) {
			if(flag == 1) alert(errtxt);
			else {
					$.ajax({
						url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/check_child_activity?activity_id=" + activity_id_edit,
						success: function(data_check_child){
						$("#check_child_activity").html(data_check_child);
						var flag_check_child_activity = document.getElementById('flag_check_child_activity').value;
						if (flag_check_child_activity > 0){
								var child_activity_name = document.getElementById('child_activity_name').value;
								alert ("Activity '"+child_activity_name+"' is attached to this activity, Update is aborted");
						}
						else{
							$.ajax({
								type: 'POST',
								url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/edit_activity',
								data: "activity_id=" + activity_id_edit + "&activity_description=" + encodeURIComponent(activity_description_edit) + "&activity_code=" + activity_code_edit +"&activity_parent=" + par_activity_edit + "&activity_definition=" + encodeURIComponent(activity_definition_edit) + "&activity_level=" + activity_level_edit + "&status_active=" + status_active_edit
							}).done(function(message){
								alert("Activity has been edited successfully");
								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/index_activity";
							}).fail(function(){
								alert("Sorry, an error occcured. Please try again.");
								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/index_activity";
							});
						}
					}
				});	
			}
		}
		else {
			if(flag == 1) alert(errtxt);
			else if(flag == 0) {
					$.ajax({
								type: 'POST',
								url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/edit_activity',
								data: "activity_id=" + activity_id_edit + "&activity_description=" + encodeURIComponent(activity_description_edit) + "&activity_code=" + activity_code_edit +"&activity_parent=" + par_activity_edit + "&activity_definition=" + encodeURIComponent(activity_definition_edit) + "&activity_level=" + activity_level_edit + "&status_active=" + status_active_edit
							}).done(function(message){
								alert("Activity has been edited successfully");
								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/index_activity";
							}).fail(function(){
								alert("Sorry, an error occcured. Please try again.");
								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_activity/index_activity";
							});
		}
	}
}
	function search_activity() {
	  var text_search = document.getElementById('text_search').value;
		if(text_search != ''){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/search_activity?text_search=" + encodeURIComponent(text_search);
		}	
		else {	
				location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/index_activity";
		}	
	}
	
	function search_activity_suggestion(event) {
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

					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/search_activity_suggestion?text_search_suggestion=" +text_search_suggestion,
				   success: function(data_activity_suggestion){
						if(data_activity_suggestion){
							$("#search_suggestion").html(data_activity_suggestion);
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
	
	function parent_activity_fields(flag){
					
					if (flag == 1) {
						document.getElementById("subcategory").style.display = 'table-cell';
						var activity_level =  document.getElementById('activity_level').value
						var activity_id = document.getElementById('text-activity_id').value
						$.ajax({	
				
									url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/parent_activity?activity_level=" + activity_level + "&activity_id=" + activity_id,
								   success: function(data_subcategory){
										if(data_subcategory){
											$("#subcategory").html(data_subcategory);
										}
									}   
						});
					}
					else if (flag == 2){
						document.getElementById("subcategory_edit").style.display = 'table-cell';
						var activity_level =  document.getElementById('activity_level_edit').value
						var activity_id = document.getElementById('text-activity_id_edit').value
						$.ajax({	
				
									url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/parent_activity_edit?activity_level=" + activity_level +"&activity_id=" + activity_id + "&flag=1",
								   success: function(data_subcategory){
										if(data_subcategory){
											$("#subcategory_edit").html(data_subcategory);
										}
									}   
						});				
					}		
	}
	
	function form_edit(activity_id,activity_code,activity_parent,activity_description,activity_definition_id,activity_level,status_active){
            document.getElementById("text-activity_id_edit").value = activity_id;
            document.getElementById("text-activity_code_edit").value = activity_code;
			document.getElementById("text-activity_description_edit").value = activity_description;
            var activity_definition = document.getElementById(activity_definition_id).innerHTML;
            CKEDITOR.instances['text-activity_definition_edit'].setData(activity_definition);
			document.getElementById("status_active_edit").value = status_active;
			document.getElementById("status_active_first").value = status_active;
			document.getElementById("activity_level_edit").value = activity_level;
			document.getElementById("activity_level_first").value = activity_level;
			
			if (activity_level > 1){
				document.getElementById("subcategory_edit").style.display = 'table-cell';
				$.ajax({	
					
										url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/parent_activity_edit?activity_level=" + activity_level + "&activity_id=" + activity_id + "&flag=1",
									   success: function(data_subcategory){
											if(data_subcategory){
												$("#subcategory_edit").html(data_subcategory);
												document.getElementById("par_activity_edit").value = activity_parent;
											}
										}   
							});	
				
			}
			else {
			document.getElementById("subcategory_edit").style.display = 'none';
			$.ajax({	
					
										url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/parent_activity_edit?activity_level=" + activity_level + "&activity_id=" + activity_id + "&flag=0",
									   success: function(data_subcategory){
											if(data_subcategory){
												$("#subcategory_edit").html(data_subcategory);
											}
										}   
							});	
			}
    }
    //M007
    function toexcel(){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_activity/activity_toexcel?";
    }
    //M007
	
</script>
<body>
<!-- M007 -->
	<div style="font-weight:bold;font-size:18px;">Export To Excel&nbsp;&nbsp;&nbsp;
	<?php 
	echo "<a title='Export To Excel' onclick='toexcel()' style='cursor:pointer')>
		  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
	?>
	</div>
<!-- M007 -->
<!-- M011 -->
<?php echo form_open_multipart('admin/ctr_manage_activity/do_upload');?>
<input type="file" id="file_upload" name="userfile" size="20" />
<input type="submit" value="Upload" />
<?php echo form_close();?>
<!-- M011 -->
	<div style="font-weight:bold;font-size:18px;">Activity&nbsp;&nbsp;&nbsp;
    	<input type="text" name="text_search" id="text_search" placeholder="Search Activity" onKeyUp="search_activity_suggestion(event)">
		<input type="button" 
        	name="search_activity" id="search_activity"  value="Search" 
            onClick="search_activity()" class="btn btn-primary" />
		<div id="search_suggestion" style="margin: 0px 0px 0px 200px;">
		</div>
	</div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_category">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
				<th>Activity Number</th>
                <th style="display:none">Activity ID</th>
				<th>Activity Code</th>
                <th>Activity Description</th>
                <th>Activity Type</th>
                <th>Status Active</th>
				<th>Fields</th>
				<th>Ticket Template</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			$find = array("\r\n","\n","&quot;");
			$replace = array(" "," ","&#39;");
			foreach ($list_activity as $p):
				$number = $number + 1;
				 echo "<tr>"; 
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->activity_id."</td>";
				 echo "<td>".$p->activity_code."</td>";
				 echo "<td>".$p->activity_description."</td>";
				 echo "<td>".$p->activity_type."</td>";
				 echo "<td>".$p->status_active_name."</td>";
				 echo "<td><a href='".base_url()."index.php/admin/ctr_manage_activity/manage_activity_field?activity_id=".$p->activity_id."&activity_description=".$p->activity_description."'";
				 if ($p->activity_type <> 'Issue Description') echo " style='display:none;' ";
				 echo ">".$p->total_activityplan."</a></td>";

				 echo "<td><a href='".base_url()."index.php/admin/ctr_manage_ticket_template/manage_ticket_template?activity_code=".$p->activity_code."'";
				 if ($p->activity_type <> 'Issue Description') echo " style='display:none;' ";
			// M049
				 echo "><img src='".base_url()."tools/datatables/media/icon/24x24/fields.png'></a></td>";
				 // echo ">".$p->total_ticket_template_id."</a></td>";
			// M049
				 echo "<td><span id='activity_definition_".$p->activity_id."' style='display:none;'>".$p->activity_definition."</span><a href='#modal_edit_activity' data-toggle='modal' onClick='form_edit(\"".$p->activity_id."\",\"".$p->activity_code."\",\"".$p->activity_parent."\",\"".$p->activity_description."\",\"activity_definition_".$p->activity_id."\",\"".$p->activity_level."\",\"".$p->status_active."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
<div id="check_child_activity" class="control-group cso-form-row">
</div>
<a href='#modal_add_activity' data-toggle="modal" class="btn btn-primary"><i class="icon-plus icon-white"> </i> Add Activity</a>
</form>

	<div id="modal_add_activity" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>New Activity</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_new_activity" id="form_new_activity" method="post" action="">
				<div class="control-group cso-form-row">
					<label for="activity_id" class="cso-form-label">Activity ID</label>
					<input type="text" id="text-activity_id" name="text-activity_id" value="<?php echo $last_activity_id;?>" disabled="disabled">
				</div>
                <div class="control-group cso-form-row">
					<label for="activity_code" class="cso-form-label">Activity Code</label>
					<input type="text" id="text-activity_code" name="text-activity_code">
			 	</div>
       			<div class="control-group cso-form-row">
					<label for="activity_description" class="cso-form-label">Activity Description</label>
					<input type="text" id="text-activity_description" name="text-activity_description">
			 	</div>
                <div class="control-group cso-form-row">
					<label for="text-activity_definition" class="cso-form-label">Activity Definition</label>
					<textarea id="text-activity_definition" name="text-activity_definition" cols="20" rows="5"></textarea>
			   </div>
                <div class="control-group cso-form-row">
					<label for="activity_level" class="cso-form-label">Activity Type</label>
					<select id="activity_level" name="activity_level" onChange="parent_activity_fields(1)">
                    	<option value=''>--choose--</option>
						<option value='1'>Issue Type</option>
                        <option value='2'>Issue Group</option>
                        <option value='3'>Issue Sub Group</option>
                        <option value='4'>Issue Description</option>
                    </select>
			 	</div>
                <div id="subcategory" class="control-group cso-form-row">
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
				<button type="button" class="btn btn_primary" id="add_activity" data-dismiss="modal" onClick="cekData_Add()">Add Activity</button>
			</div>
	</div>
		
	<div id="modal_edit_activity" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Activity</h3>
			</div>
			<div class="modal-body-script">
				<form name="form_edit_activity" id="form_edit_activity" method="post" action="">
				<div class="control-group cso-form-row">
					<label for="activity_id_edit" class="cso-form-label">Activity ID</label>
					<input type="text" id="text-activity_id_edit" name="text-activity_id_edit" disabled="disabled">
				</div>
                <div class="control-group cso-form-row">
					<label for="activity_code_edit" class="cso-form-label">Activity Code</label>
					<input type="text" id="text-activity_code_edit" name="text-activity_code_edit">
			 	</div>
       			<div class="control-group cso-form-row">
					<label for="activity_description_edit" class="cso-form-label">Activity Description</label>
					<input type="text" id="text-activity_description_edit" name="text-activity_description_edit">
			 	</div>
                <div class="control-group cso-form-row">
					<label for="text-activity_definition_edit" class="cso-form-label">Activity Definition</label>
					<textarea id="text-activity_definition_edit" name="text-activity_definition_edit" cols="20" rows="5"></textarea>
			   </div>
                <div class="control-group cso-form-row">
					<label for="activity_level_edit" class="cso-form-label">Activity Type</label>
					<select id="activity_level_edit" name="activity_level_edit" onChange="parent_activity_fields(2)">
                    	<option value=''>--choose--</option>
						<option value='1'>Issue Type</option>
                        <option value='2'>Issue Group</option>
                        <option value='3'>Issue Sub Group</option>
                        <option value='4'>Issue Description</option>
                    </select>
                    
                    <input type="hidden" id="activity_level_first" name="activity_level_first">
			 	</div>
                <div id="subcategory_edit" class="control-group cso-form-row">
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
                
               		 <input type="hidden" id="status_active_first" name="status_active_first">
			</form>
		</div>
		<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="edit_activity" data-dismiss="modal" onClick="saveData_Edit()">Edit Activity</button>
		</div>
	</div>
</body>
</html>
<?php /*<select id="category_fields" onChange="form_add_categoryfields()">
					<option value="0">-- choose --</option>
					<option value="1">aa</option>
					<option value="2">bb</option>
				</select> 
				
				function form_add_categoryfields(){
					var flag =  document.getElementById("category_fields").value
					$.ajax({	
			
								url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/category_fields?flag=" + flag,
							   success: function(data_category_fields){
									if(data_category_fields){
										$("#show_category_fields").html(data_category_fields);
									}
								}   
						   });
				}*/ 
			?>