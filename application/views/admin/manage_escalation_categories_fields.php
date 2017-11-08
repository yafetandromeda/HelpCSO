<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>List Escalation Categories Fields</title>
</head>

<script type="text/javascript">
	var i = 0;
	var j = 0;
	var k = 0;
	function cekData_Addfields(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		var cat_id = document.getElementById('cat_id').value;
		var fieldName = document.getElementById('fieldName').value;
		if(fieldName == '') {
			errtxt = errtxt + '-. Fields Name is required\n';	
			flag = 1; 
		}
		
		var fieldMandatory= document.getElementById('fieldMandatory').value;
		if(fieldMandatory == '') {
			errtxt = errtxt + '-. Fields Mandatory is required\n';	
			flag = 1; 
		}
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/add_categories_fields',
						data: "field_name=" + encodeURIComponent(fieldName) + "&cat_id=" + cat_id + "&field_mandatory=" + fieldMandatory
					}).done(function(message){
						alert("New Fields has been created successfully");
						location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories_fields?cat_id=" + cat_id;
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories_fields?cat_id=" + cat_id;
					});
		}
	}
	
	function saveData_Editfields(){
		var flag = 0;
		var errtxt = 'ERROR :\n';
		var cat_id = document.getElementById('cat_id').value;
		var fieldID_edit = document.getElementById('fieldID_edit').value;		
		var fieldName_edit = document.getElementById('fieldName_edit').value;
		var fieldMandatory_edit = document.getElementById('fieldMandatory_edit').value;
		if(fieldName_edit == '') {
			errtxt = errtxt + '-. Fields Name is required\n';	
			flag = 1; 
		}

		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/edit_categories_fields',
						data: "field_id=" + fieldID_edit + "&field_name=" + encodeURIComponent(fieldName_edit) + "&field_mandatory=" + fieldMandatory_edit
					}).done(function(message){
						alert("Fields has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories_fields?cat_id=" + cat_id;
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories_fields?cat_id=" + cat_id;
					});
		}
	}
	function search_fields() {
	  var cat_id = document.getElementById('cat_id').value;
	  var text_search = document.getElementById('text_search').value;
		if(text_search != ''){
			location.href = "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/search_fields?text_search=" + encodeURIComponent(text_search) + "&cat_id=" + cat_id;
		}	
		else {	
				location.href = "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/manage_categories_fields?cat_id=" + cat_id;
		}	
	}
	
	function search_fields_suggestion(event) {
	  var cat_id = document.getElementById('cat_id').value;
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

					url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/search_fields_suggestion?text_search_suggestion=" +encodeURIComponent(text_search_suggestion)
							+ "&cat_id=" + cat_id,
				   success: function(data_fields_suggestion){
						if(data_fields_suggestion){
							$("#search_suggestion").html(data_fields_suggestion);
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
	
	function form_edit(fieldID,fieldName,fieldMandatory){
            document.getElementById("fieldID_edit").value = fieldID;
            document.getElementById("fieldName_edit").value = fieldName;
			document.getElementById("fieldMandatory_edit").value = fieldMandatory;
    }
	
</script>
<body>
	<div style="font-weight:bold;font-size:18px;">Escalation Fields&nbsp;&nbsp;&nbsp;
    	<input type="text" name="text_search" id="text_search" placeholder="Search Fields" onKeyUp="search_fields_suggestion(event)">
		<input type="button" name="search_fields" id="search_fields"  value="Search" onClick="search_fields()" class="btn btn-primary" />
		<div id="search_suggestion" style="margin: 0px 0px 0px 165px;">
		</div>
	</div>
<hr>
<form id="form1" name="form1" method="post" action="" ?>
<h4 align="center">
	<button class="btn pull-left" type='button' onClick="window.history.go(-1);">Back</button>
    <div style="color:#0a476d;"><?php echo "<b>".$category."</b>"; ?></div>
    <br />
</h4>
<input type="hidden" name="cat_id" id="cat_id" value="<?php echo $cat_id; ?>">
<div id="search_list_fields">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Field Number</th>
				<th style="display:none">Field ID</th>
				<th>Field Name</th>
				<th>Edit Field</th>
                <th>Delete Field</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			$find = array("\r\n","\n","&quot;");
			$replace = array(" "," ","&#39;");
			foreach ($list_fields as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->fieldID."</td>";
				 echo "<td>".$p->fieldName."</td>";
				 echo "<td><a href='#modal_edit_fields' data-toggle='modal' onClick='form_edit(\"".$p->fieldID."\",\"".$p->fieldName."\",\"".$p->fieldMandatory."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "<td><a href='".base_url()."index.php/ctr_helpcso_escalation/delete_categories_fields?field_id=".$p->fieldID."&cat_id=".$cat_id."'";?> onClick="return confirm('Are you sure want to delete [<?php echo $p->fieldName; ?>]')" <?php echo "><img src='".base_url()."tools/datatables/media/icon/24x24/Delete.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
<a href='#modal_add_fields' data-toggle="modal" class="btn btn-primary"><i class="icon-plus icon-white"> </i> Add fields</a>


</form>		

	<div id="modal_add_fields" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>New Fields</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_new_fields" id="form_new_fields" method="post" action="">
				<div class="control-group cso-form-row">
					<label for="fieldID" class="cso-form-label">Fields ID</label>
					<input type="text" id="fieldID" name="fieldID" value="<?php echo $fields_id;?>" disabled="disabled">
				</div>
       			<div class="control-group cso-form-row">
					<label for="fieldName" class="cso-form-label">Fields Name</label>
					<input type="text" id="fieldName" name="fieldName">
			 	</div>
				<div class="control-group cso-form-row">
					<label for="fieldMandatory" class="cso-form-label">Fields Mandatory</label>
					<select id="fieldMandatory">
						<option value ="">--choose--</option>
						<option value ="1">Yes</option>
						<option value ="0">No</option>
					</select>
			 	</div>
			</form>
		</div>
			<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="add_fields" data-dismiss="modal" onClick="cekData_Addfields()">Add fields</button>
			</div>
	</div>
		
	<div id="modal_edit_fields" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Fields</h3>
			</div>
			<div class="modal-body-script">
				<form name="form_edit_script" id="form_edit_script" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="fieldID_edit" class="cso-form-label">Fields ID</label>
						<input type="text" id="fieldID_edit" name="fieldID_edit" disabled="disabled">
				</div>
				<div class"control-group cso-form-row">
						<label for="fieldName_edit" class="cso-form-label">Fields Name</label>
						<input type="text" id="fieldName_edit" name="fieldName_edit">
				 </div>
				 <div class="control-group cso-form-row">
					<label for="fieldMandatory_edit" class="cso-form-label">Fields Mandatory</label>
					<select id="fieldMandatory_edit">
						<option value ="1">Yes</option>
						<option value ="0">No</option>
					</select>
			 	</div>
			</form>
		</div>
		<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="edit_fields" data-dismiss="modal" onClick="saveData_Editfields()">Edit fields</button>
		</div>
	</div>
</body>
</html>
