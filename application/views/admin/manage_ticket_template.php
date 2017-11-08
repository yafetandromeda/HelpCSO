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
		
		var ticket_template_name = document.getElementById('text-ticket_template_name').value;
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
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/add_ticket_template',
						data: "ticket_template_name=" + encodeURIComponent(ticket_template_name) + "&status_active=" + status_active
					}).done(function(message){
						alert("New Ticket Template has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/index_ticket_template";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/index_ticket_template";
					});
		}
	}

	
	function saveData_Edit(){
		var flag = 0;
		var errtxt = 'ERROR :\n';

		var ticket_template_id_edit = document.getElementById('text-ticket_template_id_edit').value;			
		var ticket_template_name_edit = document.getElementById('text-ticket_template_name_edit').value;
		if(ticket_template_name_edit == '') {
			errtxt = errtxt + '-. Ticket Template Name is required\n';	
			flag = 1; 
		}
		
		var status_active_edit =  document.getElementById('status_active_edit').value;		
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
					$.ajax({
								type: 'POST',
								url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/edit_ticket_template',
								data: "ticket_template_id=" + ticket_template_id_edit + "&ticket_template_name=" + encodeURIComponent(ticket_template_name_edit) + "&status_active=" + status_active_edit
							}).done(function(message){
								alert("Ticket Template has been edited successfully");
								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/index_ticket_template";
							}).fail(function(){
								alert("Sorry, an error occcured. Please try again.");
								location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/index_ticket_template";
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

	function form_edit(ticket_template_id,ticket_template_name,status_active){
            document.getElementById("text-ticket_template_id_edit").value = ticket_template_id;
            document.getElementById("text-ticket_template_name_edit").value = ticket_template_name;
			document.getElementById("status_active_edit").value = status_active;
    }
	//M008
    function toexcel(){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/ticket_template_toexcel?";
    }

    function toexcel2(){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/ticket_activityplan_toexcel?";
    }
    //M008

</script>
<body>
<!-- M008 -->
	<div style="font-weight:bold;font-size:18px;">Export Ticket Template To Excel&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
	echo "<a title='Export To Excel' onclick='toexcel()' style='cursor:pointer')>
		  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
	?>
	</div>
<!-- 	<div style="font-weight:bold;font-size:18px;">Export All Activity Plan To Excel&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
	//echo "<a title='Export To Excel' onclick='toexcel2()' style='cursor:pointer')>
		  //<img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
	?>
	</div> -->
<!-- M008 -->
	<div style="font-weight:bold;font-size:18px;">Ticket Template&nbsp;&nbsp;&nbsp;
    	<input type="text" name="text_search" id="text_search" placeholder="Search Ticket Template" onKeyUp="search_ticket_template_suggestion(event)">
		<input type="button" 
        	name="search_ticket_template" id="search_ticket_template"  value="Search" 
            onClick="search_ticket_template()" class="btn btn-primary" />
		<div id="search_suggestion" style="margin: 0px 0px 0px 200px;">
		</div>
	</div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_ticket_template">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
				<th>Ticket Template Number</th>
                <th style="display:none">Ticket Template ID</th>
				<th>Ticket Template Name</th>
                <th>Status Active</th>
				<th>Activity Plan</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php		
			$number = 0;
			foreach ($list_ticket_template as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->ticket_template_id."</td>";
				 echo "<td>".$p->ticket_template_name."</td>";
				 echo "<td>".$p->status_active_name."</td>";
				 echo "<td><a href='".base_url()."index.php/admin/ctr_manage_ticket_template/index_activity_plan?ticket_template_id=".$p->ticket_template_id."&ticket_template_name=".$p->ticket_template_name."'><img src='".base_url()."tools/datatables/media/icon/24x24/fields.png'></a></td>";
				 echo "<td><a href='#modal_edit_ticket_template' data-toggle='modal' onClick='form_edit(\"".$p->ticket_template_id."\",\"".$p->ticket_template_name."\",\"".$p->status_active."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
<a href='#modal_add_ticket_template' data-toggle="modal" class="btn btn-primary"><i class="icon-plus icon-white"> </i> Add Ticket Template</a>
</form>
<!-- M012 -->
<?php echo form_open_multipart('admin/ctr_manage_ticket_template/do_upload');?>
<input type="file" id="file_upload" name="userfile" size="20" />
<input type="submit" value="Upload" />
<?php echo form_close();?>
<!-- M012 -->
	<div id="modal_add_ticket_template" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>New Ticket Template</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_new_ticket_template" id="form_new_ticket_template" method="post" action="">
				<div class="control-group cso-form-row">
					<label for="ticket_template_id" class="cso-form-label">Ticket Template ID</label>
					<input type="text" id="text-ticket_template_id" name="text-ticket_template_id" value="<?php echo $last_ticket_template_id;?>" disabled="disabled">
				</div>
                <div class="control-group cso-form-row">
					<label for="ticket_template_name" class="cso-form-label">Ticket Template Name</label>
					<input type="text" id="text-ticket_template_name" name="text-ticket_template_name">
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
				<button type="button" class="btn btn_primary" id="add_ticket_template" data-dismiss="modal" onClick="cekData_Add()">Add Ticket Template</button>
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
					<label for="ticket_template_id_edit" class="cso-form-label">Ticket Template ID</label>
					<input type="text" id="text-ticket_template_id_edit" name="text-ticket_template_id_edit" disabled="disabled">
				</div>
                <div class="control-group cso-form-row">
					<label for="ticket_template_name_edit" class="cso-form-label">Ticket Template Code</label>
					<input type="text" id="text-ticket_template_name_edit" name="text-ticket_template_name_edit">
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