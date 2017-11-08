<!-- M009 - YA - Export ticket activityplan toexcel -->
<!-- M013 - YA - Import activityplan -->
<!-- M033 - YA - Add Edit activityplan -->
<!-- B02 -->
<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Manage Activity Plan</title>
</head>

<script type="text/javascript">
	var i = 0;
	var j = 0;
	var k = 0;
	function cekData_Add(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		var ticket_template_id = 1;
		// var ticket_template_name = document.getElementById('ticket_template_name').value;
		
		var plan_order = 0;
		// if(plan_order == '') {
		// 	errtxt = errtxt + '-. Plan Order is required\n';	
		// 	flag = 1; 
		// }
		
		var action_name = document.getElementById('text-action_name').value;
		if(action_name == '') {
			errtxt = errtxt + '-. Action Name is required\n';	
			flag = 1; 
		}
		
		var function_name = document.getElementById('text-function_name').value;
		if(function_name == '') {
			errtxt = errtxt + '-. Function Nam is required\n';	
			flag = 1; 
		}
		
		var sla = document.getElementById('text-sla').value;
		if(sla == '') {
			errtxt = errtxt + '-. SLA is required\n';	
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
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/add_activity_plan',
						data: "action_name=" + encodeURIComponent(action_name) + "&function_name=" + encodeURIComponent(function_name) + "&sla=" + sla + "&ticket_template_id=" + ticket_template_id + "&status_active=" + status_active
					}).done(function(message){
						alert("New Activity Plan has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/get_activity_plan";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						// location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/get_activity_plan";
					});
		}
	}
	
	function saveData_Edit(){
		var flag = 0;
		var errtxt = 'ERROR :\n';
		// var ticket_template_id = document.getElementById('ticket_template_id').value;
		// var ticket_template_name = document.getElementById('ticket_template_name').value;
		var plan_id_edit = document.getElementById('text-plan_id_edit').value;		
		
		var plan_order_edit = 0;
		// if(plan_order_edit == '') {
		// 	errtxt = errtxt + '-. Plan Order is required\n';	
		// 	flag = 1; 
		// }
		
		var action_name_edit = document.getElementById('text-action_name_edit').value;
		if(action_name_edit == '') {
			errtxt = errtxt + '-. Action Name is required\n';	
			flag = 1; 
		}
		
		var function_name_edit = document.getElementById('text-function_name_edit').value;
		if(function_name_edit == '') {
			errtxt = errtxt + '-. Function Name is required\n';	
			flag = 1; 
		}
		
		var sla_edit = document.getElementById('text-sla_edit').value;
		if(sla_edit == '') {
			errtxt = errtxt + '-. SLA is required\n';	
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
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/edit_activity_plan',
						data: "plan_id=" + plan_id_edit + "&action_name=" + encodeURIComponent(action_name_edit) + "&function_name=" + encodeURIComponent(function_name_edit) + "&sla=" + sla_edit + "&status_active=" + status_active_edit
					}).done(function(message){
						alert("Activity Plan has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/get_activity_plan/";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_ticket_template/get_activity_plan/";
					});
		}
	}
	
	function search_activity_plan() {
	  var text_search = document.getElementById('text_search').value;
		if(text_search != ''){
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_activity_plan?text_search=" + encodeURIComponent(text_search);
		}	
		else {	
			location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/get_activity_plan";
		}	
	}
	
	function search_activity_plan_suggestion(event) {
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

					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/search_activity_plan_suggestion?text_search_suggestion=" +encodeURIComponent(text_search_suggestion),
				   success: function(data_activity_plan_suggestion){
						if(data_activity_plan_suggestion){
							$("#search_suggestion").html(data_activity_plan_suggestion);
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
	
	function form_edit(plan_id,action_name,function_name,sla,status_active){
            document.getElementById("text-plan_id_edit").value = plan_id;
            // document.getElementById("text-plan_order_edit").value = plan_order;
			document.getElementById("text-action_name_edit").value = action_name;
			document.getElementById("text-function_name_edit").value = function_name;
			document.getElementById("text-sla_edit").value = sla;
			document.getElementById("status_active_edit").value = status_active;
    }
	
	function check_numberkey(e)
      {
         var charCode = (e.which) ? e.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
// M009
    function toexcel(){
    	var ticket_template_id = document.getElementById('ticket_template_id').value;
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_ticket_template/ticket_activityplan_byid_toexcel?ticket_template_id=" + ticket_template_id;
    }
// M009
</script>
<body>
<!-- M009 -->
<div style="font-weight:bold;font-size:18px;">Export Activity Plan To Excel&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
	echo "<a title='Export To Excel' onclick='toexcel()' style='cursor:pointer')>
		  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
	?>
<!-- 009 -->
	</div>
	<div style="font-weight:bold;font-size:18px;">Activity Plan&nbsp;&nbsp;&nbsp;
    	<input type="text" name="text_search" id="text_search" placeholder="Search Field" onKeyUp="search_activity_plan_suggestion(event)">
		<input type="button" 
			name="search_activity_plan" id="search_activity_plan"  value="Search" 
			onClick="search_activity_plan()" class="btn btn-primary" />
		<div id="search_suggestion" style="margin: 0px 0px 0px 165px;">
		</div>
	</div>
<hr>
<form id="form1" name="form1" method="post" action="" ?>
<h4 align="center">
	<button class="btn pull-left" type='button' onClick="window.history.go(-1);">Back</button>
    <!-- <div style="color:#0a476d;"><?php //echo "<b>".$ticket_template_name."</b>"; ?></div> -->
    <br />
</h4>
<!-- <input type="hidden" name="ticket_template_id" id="ticket_template_id" value="<?php //echo $ticket_template_id; ?>"> -->
<!-- <input type="hidden" name="ticket_template_name" id="ticket_template_name" value="<?php //echo $ticket_template_name; ?>"> -->
<div id="search_list_activity_plan">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
				<th>Plan Number</th>
                <th style="display:none">Plan ID</th>
				<!-- <th>Plan Order</th> -->
                <th>Action Name</th>
                <th>Function Name</th>
                <th>SLA</th>
                <th>Status Active</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($list_activity_plan as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->plan_id."</td>";
				 // echo "<td>".$p->plan_order."</td>";
				 echo "<td>".$p->action_name."</td>";
				 echo "<td>".$p->function_name."</td>";
				 echo "<td>".$p->sla."</td>";
				 echo "<td>".$p->status_active_name."</td>";
				 echo "<td><a href='#modal_edit_activity_plan' data-toggle='modal' onClick='form_edit(\"".$p->plan_id."\",\"".$p->action_name."\",\"".$p->function_name."\",\"".$p->sla."\",\"".$p->status_active."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
<a href='#modal_add_activity_plan' data-toggle="modal" class="btn btn-primary"><i class="icon-plus icon-white"> </i> Add Activity Plan</a>
</form>
<!-- M013 -->
<!--<?php //echo form_open_multipart('admin/ctr_manage_ticket_template/do_upload2?ticket_template_id=' . $ticket_template_id . "&ticket_template_name=" . $ticket_template_name);?>
<input type="file" id="file_upload" name="userfile" size="20" />
<input type="submit" value="Upload" />
<?php //echo form_close();?>-->
<!-- M013 -->

	<div id="modal_add_activity_plan" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>New Activity Plan</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_new_activity_plan" id="form_new_activity_plan" method="post" action="">
				<div class="control-group cso-form-row">
					<label for="plan_id" class="cso-form-label">Plan ID</label>
					<input type="text" id="text-plan_id" name="text-plan_id" value="<?php echo $last_activity_plan_id;?>" disabled="disabled">
				</div>
       			<!-- <div class="control-group cso-form-row">
					<label for="plan_order" class="cso-form-label">Plan Order</label>
					<input type="text" id="text-plan_order" name="text-plan_order" onkeypress="return check_numberkey(event)">
                    <span style="color:#F00;">* input number only</span>
			 	</div> -->
				<div class="control-group cso-form-row">
					<label for="action_name" class="cso-form-label">Action Name</label>
					<input type="text" id="text-action_name" name="text-action_name">
			 	</div>
                <div class="control-group cso-form-row">
					<label for="function_name" class="cso-form-label">Function Name</label>
					<input type="text" id="text-function_name" name="text-function_name">
			 	</div>
                <div class="control-group cso-form-row">
					<label for="sla" class="cso-form-label">SLA</label>
					<input type="text" id="text-sla" name="text-sla" onkeypress="return check_numberkey(event)">
                    <span style="color:#F00;">* input number only</span>
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
				<button type="button" class="btn btn_primary" id="add_activity_plan" data-dismiss="modal" onClick="cekData_Add()">Add Activity Plan</button>
			</div>
	</div>
		
	<div id="modal_edit_activity_plan" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Activity Plan</h3>
			</div>
			<div class="modal-body-script">
				<form name="form_edit_activity_plan" id="form_edit_activity_plan" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="plan_id_edit" class="cso-form-label">Plan ID</label>
						<input type="text" id="text-plan_id_edit" name="text-plan_id_edit" disabled="disabled">
				</div>
				<!-- <div class"control-group cso-form-row">
						<label for="plan_order_edit" class="cso-form-label">Plan Order</label>
						<input type="text" id="text-plan_order_edit" name="text-plan_order_edit" onkeypress="return check_numberkey(event)">
                      	<span style="color:#F00;">* input number only</span>
				 </div> -->
                 <div class="control-group cso-form-row">
					<label for="action_name_edit" class="cso-form-label">Action Name</label>
					<input type="text" id="text-action_name_edit" name="text-action_name_edit">
			 	</div>
                 <div class="control-group cso-form-row">
					<label for="function_name_edit" class="cso-form-label">Function Name</label>
					<input type="text" id="text-function_name_edit" name="text-function_name_edit">
			 	</div>
                <div class="control-group cso-form-row">
					<label for="sla_edit" class="cso-form-label">SLA</label>
					<input type="text" id="text-sla_edit" name="text-sla_edit" onkeypress="return check_numberkey(event)">
                    <span style="color:#F00;">* input number only</span>
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
				<button type="button" class="btn btn_primary" id="edit_activity_plan" data-dismiss="modal" onClick="saveData_Edit()">Edit Activity Plan</button>
		</div>
	</div>
</body>
</html>
