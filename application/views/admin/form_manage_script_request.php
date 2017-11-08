<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<html>
<body>
<script type="text/javascript">
	
	window.onload = function(){
		CKEDITOR.replace('text-answer', 
					{
					toolbarGroups: [
							{ name: 'clipboard',   groups: [ 'clipboard', 'undo', 'outdent', 'indent' ] },																	
							{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
						]
					}
		);
	}
		
	function cekData_save(){
		var request_id = document.getElementById('request_id').value;
		var flag = 0;

		var errtxt = 'ERROR :\n';
		
		var script_id = document.getElementById('text-scriptID').value;
		var question = document.getElementById('text-question').value;
		if(question == '') {
			errtxt = errtxt + '-. Question still empty\n';	
			flag = 1; 
		}
		var last_pil_category = document.getElementById('last_pil_category').value;
		var category = document.getElementById('category'+last_pil_category).value;
		var tracking_category = '';
		for (var i = 1; i<=last_pil_category; i++){
			tracking_category = tracking_category + document.getElementById('category'+i).value + ";";
		}
		for (var i = last_pil_category; i<4; i++){
			tracking_category = tracking_category +  " ;";
		}
		var cek_last_pil_category = document.getElementById('cek_last_pil_category').value;
		if(document.getElementById('category'+cek_last_pil_category).value == '') {
				if (cek_last_pil_category == 1) errtxt = errtxt + '-. Ticket Type not chosen\n';	
				else if (cek_last_pil_category == 2) errtxt = errtxt + '-. Issue Group not chosen\n';
				else if (cek_last_pil_category == 3) errtxt = errtxt + '-. Sub Issue Group not chosen\n';
				else if (cek_last_pil_category == 4) errtxt = errtxt + '-. Issue Description not chosen\n';
				flag = 1; 
		}
		
		var tag = document.getElementById('text-tag').value;

		var answer = CKEDITOR.instances['text-answer'].getData();
		answer = answer.replace(/(?:&nbsp;|<br>)/g,'');
		if(answer == '') {
			errtxt = errtxt + '-. Answer still empty\n';	
			flag = 1; 
		}
		var visibility = document.getElementById('visibility').value;
		if(visibility == '') {
			errtxt = errtxt + '-. Script visibility not chosen\n';	
			flag = 1; 
		}
		if(flag == 1) { 
						alert(errtxt);
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_requested_script/form_manage_requested_script?request_id="+request_id;
		}
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_requested_script/save_requested_script',
						data: "text-scriptID=" + script_id + "&text-question=" + encodeURIComponent(question) + "&text-answer=" + escape(answer) + "&text-tag=" + tag + "&category_id=" + category + "&visibility=" + visibility + "&tracking_category=" + tracking_category			
					}).done(function(message){
						alert("New data script has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_requested_script";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_requested_script/form_manage_requested_script?request_id="+request_id;
					});
		}
	}
	
	function show_subcategory(flag,level){
					if (flag == 1) {
						var par_category = document.getElementById('category'+level).value;
						$.ajax({	
				
								url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_requested_script/script_subcategory?par_category=" + par_category + "&level=" + level,
								   success: function(data_subcategory){
									if(data_subcategory){
										if (level == 1){
											$("#subcategory1").html(data_subcategory);
											$("#subcategory2").html("");
											$("#subcategory3").html("");
											$("#subcategory4").html("");
											var level_subcategory = document.getElementById('level_subcategory1').value
										}
										else if (level == 2){
											$("#subcategory2").html(data_subcategory);
											$("#subcategory3").html("");
											$("#subcategory4").html("");
											var level_subcategory = document.getElementById('level_subcategory2').value
										}
										else if (level == 3){
											$("#subcategory3").html(data_subcategory);
											$("#subcategory4").html("");
											var level_subcategory = document.getElementById('level_subcategory3').value
										}
										else if (level == 4){
											$("#subcategory4").html(data_subcategory);
											var level_subcategory = document.getElementById('level_subcategory4').value
										}
										document.getElementById('last_pil_category').value = level;
										document.getElementById('cek_last_pil_category').value = level_subcategory;
									}  
								   }
						});
					}	
	}
	
	function cekData_solved(){
		var request_id =  document.getElementById('request_id').value;
		location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_requested_script/solved_requested_script?request_id=" + request_id;
	}
	
	function cekData_cancel(){
		location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_requested_script";
	}
	

</script>
<div class="container">
<h4>Script Request Detail</h4>
<div id="list_requested_script">
<form id="form_list_requested" name="form_list_requested" method="post" action="">
		<div id="note_from_cso" class="well" >	
			<?php 
			foreach ($requested_script as $p):
				echo "<div id='note'  align='left' style='display:inline-block;width:70%;'>";
				echo " From : ".$p->user_name." <br />";
				echo "<tt>".$p->note."<br />";
				echo "Sent Date : ".$p->request_date."<br />";
				echo "</div>";
            	echo "<hr />";
			endforeach;
	  	  ?>
		  </div>				
    	<input type='button' name='solved' id='solved' value='Solved' onClick="cekData_solved()" class="btn btn-primary pull-right"/>
</form>

<h1>New Script</h1>
	<input type='hidden' id='last_pil_category' value='1'/>
	<input type='hidden' id='cek_last_pil_category' value='1'/>
	<input type ="hidden" id="request_id" name="request_id" value="<?php echo $request_id;?>">
    <form name="form_new_script" id="form_new_script" method="post" action="">
	<div class="control-group cso-form-row">
					<label for="text-scriptID" class="cso-form-label">Script ID</label>
					<input type="text" id="text-scriptID" name="text-scriptID" value="<?php echo $script_id;?>" disabled="disabled">
			</div>
			<div class="control-group cso-form-row">
					<label for="text-question" class="cso-form-label">Question</label>
					<input type="text" id="text-question" name="text-question">
			 </div>
			 <div class="control-group cso-form-row">
			   <label for="category" class="cso-form-label">Ticket Type</label>
				<select id="category1" name="category1" onChange="show_subcategory(1,1)">
						<option value=''>--choose--</option>
						<?php foreach ($pil_category as $p):
								echo "<option value='".$p->code_id."'>".$p->category."</option>";
							  endforeach;
						?>
						</select>
			   </div>
               <div id="subcategory1" class="control-group cso-form-row">
               </div>
               <div id="subcategory2" class="control-group cso-form-row">
               </div>
               <div id="subcategory3" class="control-group cso-form-row">
               </div>
               <div id="subcategory4" class="control-group cso-form-row">
               </div>
				<div class="control-group cso-form-row">
					<label for="text-answer" class="cso-form-label">Answer</label>
					<textarea id="text-answer" name="text-answer" cols="20" rows="5"></textarea>
			   </div>
			   <div class="control-group cso-form-row">
					<label for="text-tag" class="cso-form-label">Tag</label>
					<input type="text" id="text-tag" name="text-tag">
			   </div>
               <div class="control-group cso-form-row">
			   <label for="visibility" class="cso-form-label">Script Visibility</label>
				<select id="visibility" name="visibility">
						<option value=''>--choose--</option>
						<option value='1'>Show</option>
                        <option value='0'>Hide</option>
						</select>
			   </div>
		 <div class="control-group cso-form-row" align="right">
        	<input type="button" class="btn btn-success" value="Save and Publish" id="save" onClick="cekData_save()">
            <input type="button" class="btn btn-danger" value="Cancel" id="cancel" onClick="cekData_cancel()">
		</div>
</form>
</div>
</div>
</body>
</html>
 