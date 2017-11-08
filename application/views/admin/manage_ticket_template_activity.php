<!-- M008 - YA - export ticket tamplate toexcel -->
<!-- M012 - YA - Import ticket template -->
<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Manage Ticket Template</title>
</head>

<script type="text/javascript">
	var i = 0;
	var j = 0;
	var k = 0;

	function cekData_Add(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		var activity_code = document.getElementById('activity_code').value;
		var ticket_template_name = document.getElementById('ticket_template_name').value;
		if(ticket_template_name == '') {
			errtxt = errtxt + '-. Ticket Template Name is required\n';	
			flag = 1; 
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
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/add_ticket_template_activity',
						data: "activity_code=" + activity_code + "&ticket_template_id=" + ticket_template_name + "&status_active=" + status_active
					}).done(function(message){
						alert("New Ticket Template has been added");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/manage_ticket_template?activity_code=" + activity_code;
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/manage_ticket_template?activity_code=" + activity_code;
					});
		}
	}

	
	function saveData_Edit(){
		var flag = 0;
		var errtxt = 'ERROR :\n';

		var activity_code_edit = document.getElementById('text-activity_code_edit').value;			
		var ticket_plan_id_edit = document.getElementById('text-ticket_plan_id_edit').value;
		var activity_code = document.getElementById('activity_code').value;
		if(ticket_template_name_edit == '') {
			errtxt = errtxt + '-. Ticket Template Name is required\n';	
			flag = 1; 
		}
		
		var status_active_edit =  document.getElementById('status_active_edit').value;		
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
					$.ajax({
								type: 'POST',
								url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/edit_ticket_template_activity',
								data: "ticket_plan_id=" + ticket_plan_id_edit + "&ticket_template_id=" + ticket_template_name_edit + "&status_active=" + status_active_edit
							}).done(function(message){
								alert("Ticket Template has been edited successfully");
								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/manage_ticket_template?activity_code=" + activity_code;
							}).fail(function(){
								alert("Sorry, an error occcured. Please try again.");
								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/manage_ticket_template?activity_code=" + activity_code;
							});
		}
	}

	function search_ticket_template() {
	  var text_search = document.getElementById('text_search').value;
		if(text_search != ''){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_ticket_template?text_search=" + encodeURIComponent(text_search);
		}	
		else {	
				location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/index_ticket_template";
		}	
	}
	
	function search_ticket_template_suggestion(event) {
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

					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_ticket_template_suggestion?text_search_suggestion=" +encodeURIComponent(text_search_suggestion),
				   success: function(data_script_suggestion){
						if(data_script_suggestion){
							$("#search_suggestion").html(data_script_suggestion);
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

	function form_edit(ticket_plan_id,ticket_template_name,status_active){
            document.getElementById("text-ticket_plan_id_edit").value = ticket_plan_id;
            document.getElementById("ticket_template_name_edit").value = ticket_template_name;
			document.getElementById("status_active_edit").value = status_active;
    }

</script>
<body>
<h4>Ticket Template</h4>
<input type="hidden" name="activity_code" id="activity_code" value="<?php echo $activity_code; ?>">
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_ticket_template">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
				<th>Ticket Plan Number</th>
                <th style="display:none">Ticket Plan ID</th>
				<th>Ticket Template Name</th>
				<th>Status</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php		
			$number = 0;
			foreach ($list_ticket_template_activity as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->ticket_template_id."</td>";
				 echo "<td>".$p->ticket_template_name."</td>";
				 echo "<td>".$p->status_active_name."</td>";
				 echo "<td><a href='#modal_edit_ticket_template' data-toggle='modal' onClick='form_edit(\"".$p->ticket_plan_id."\",\"".$p->ticket_template_name."\",\"".$p->status_active_name."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
<a href='#modal_add_ticket_template' data-toggle="modal" class="btn btn-primary"><i class="icon-plus icon-white"> </i> Add Ticket Template</a>
</form>
	<div id="modal_add_ticket_template" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>New Ticket Template</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_new_ticket_template" id="form_new_ticket_template" method="post" action="">
				<div class="control-group cso-form-row">
					<!-- <label for="ticket_template_activity_id" class="cso-form-label">Ticket Template ID</label> -->
					<input type="hidden" id="text-ticket_template_activity_id" name="text-ticket_template_activity_id" value="<?php echo $last_ticket_template_activity_id;?>" disabled="disabled">
				</div>
				<div class="control-group cso-form-row">
					<label for="activity_code" class="cso-form-label">Activity Code</label>
					<input type="text" id="text-activity_code" name="text-activity_code" value="<?php echo $activity_code;?>" disabled="disabled">
				</div>
                <div class="control-group cso-form-row">
					<label for="ticket_template_name" class="cso-form-label">Ticket Template Name</label>
					<!-- <input type="text" id="text-ticket_template_name" name="text-ticket_template_name"> -->
					<select id="ticket_template_name" name="ticket_template_name">
					<option value=''>-- Choose --</option>
		                	<?php
		                    foreach ($list_ticket_template_all as $ap):
								echo "<option value='" . $ap->ticket_template_id . "'>" 
									. $ap->ticket_template_name
									. "</option>";
									endforeach;
							?>
		                </select>
			 	</div>
                 <div class"control-group cso-form-row">
							<label for="status_active" class="cso-form-label">Status Active</label>
							<select id="status_active" name="status_active">
                            	<option value=''>-- Choose --</option>
								<?php foreach ($pil_active as $p):
										echo "<option value='".$p->code_id."'>".$p->status_active."</option>";
									  endforeach;
								?>
								</select>
				</div>
			</form>
		</div>
			<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="add_activity_plan" data-dismiss="modal" onClick="cekData_Add()">Add Ticket Template</button>
			</div>
	</div>
		
	<div id="modal_edit_ticket_template" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Ticket Template</h3>
			</div>
			<div class="modal-body-script">
				<form name="form_edit_ticket_template" id="form_edit_ticket_template" method="post" action="">
				<div class="control-group cso-form-row">
					<!-- <label for="ticket_template_id_edit" class="cso-form-label">Ticket Template ID</label> -->
					<input type="text" id="text-ticket_plan_id_edit" name="text-ticket_plan_id_edit" disabled="disabled">
				</div>
				<div class="control-group cso-form-row">
					<label for="activity_code" class="cso-form-label">Activity Code</label>
					<input type="text" id="text-activity_code_edit" name="text-activity_code_edit" value="<?php echo $activity_code;?>" disabled="disabled">
				</div>
                <div class="control-group cso-form-row">
					<label for="ticket_template_name_edit" class="cso-form-label">Ticket Template Name</label>
					<input type="text" id="ticket_template_name_edit" name="ticket_template_name_edit" disabled="disabled">
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
				<button type="button" class="btn btn_primary" id="edit_ticket_template" data-dismiss="modal" onClick="saveData_Edit()">Edit Ticket Template</button>
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