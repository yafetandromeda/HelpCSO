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
		document.getElementById("text-scriptID").value = '<?php echo $script_id;?>';
        document.getElementById("text-question").value = '<?php echo $question;?>';
        CKEDITOR.instances['text-answer'].setData('<?php echo mysql_real_escape_string(str_replace("\r\n"," ",$answer));?>');
		document.getElementById("category").value = '<?php echo $category_id;?>';
		document.getElementById("text-tag").value = '<?php echo $tag;?>';
	}
	
	function cekData_save(){
		var flag = 0;
		
		var script_id =  document.getElementById('text-scriptID').value;
		var errtxt = 'ERROR :\n';
		
		var question = document.getElementById('text-question').value;
		if(question == '') {
			errtxt = errtxt + '-. Question still empty\n';	
			flag = 1; 
		}
		var tag = document.getElementById('text-tag').value;
		var category = document.getElementById('category').value;
		if(category == '') {
			errtxt = errtxt + '-. Category not chosen\n';	
			flag = 1; 
		}
		
		var answer = CKEDITOR.instances['text-answer'].getData();
		answer = answer.replace(/(?:&nbsp;|<br>)/g,'');
		if(answer == '') {
			errtxt = errtxt + '-. Answer still empty\n';	
			flag = 1; 
		}
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {				
			$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_reported_script/save_edited_script',
					data: "script_id=" + script_id + "&text-question=" + encodeURIComponent(question) + "&text-answer=" + escape(answer)
						+ "&text-tag=" + tag + "&category=" + category	
					}).done(function(message){
						alert("Data script has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_reported_script";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_reported_script/form_manage_reported_script?script_id=" 
						+ script_id;
					});
		}
	}
		
	function cekData_solved(){
		var script_id =  document.getElementById('text-scriptID').value;
		$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_reported_script/solved_reported_script',
					data: "script_id=" + script_id 	
					}).done(function(message){
						alert("Data script is solved and has been published successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_reported_script";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_reported_script/form_manage_reported_script?script_id=" 
						+ script_id;
					});
	}
	
	function cekData_cancel(){
		location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_reported_script";
	}
	
	
</script>
<div class="container">
<h4>Script Report Detail</h4>
<div id="list_report_script">
<form id="form_list_report" name="form_list_report" method="post" action="">
		<div id="note_from_cso" class="well" >	
			<?php 
			foreach ($reported_script as $p):
				echo "<div id='note'  align='left' style='display:inline-block;width:70%;'>";
				echo " From : ".$p->user_name." <br />";
				echo "<tt>".$p->note."<br />";
				echo "Sent Date : ".$p->report_date."<br />";
				echo "</div>";
            echo "<hr />";
			endforeach;
	  	  ?>
		  </div>				
    	<input type='button' name='solved' id='solved' value='Solved and Publish' onClick='cekData_solved()' class="btn btn-primary pull-right"/>
</form>

<h1>Edit Script</h1>
    <form name="form_edit_script" id="form_edit_script" method="post" action="">
	<div class"control-group cso-form-row">
			<label for="text-scriptID" class="cso-form-label">Script ID</label>
			<input type="text" id="text-scriptID" name="text-scriptID" disabled="disabled">
    </div>
	<div class"control-group cso-form-row">
			<label for="text-question" class="cso-form-label">Question</label>
			<input type="text" id="text-question" name="text-question">
     </div>
     <div class"control-group cso-form-row">
       <label for="category" class="cso-form-label">Category</label>
	  	<select id="category" name="category" >
            	<option value=''>--choose--</option>
				<?php foreach ($pil_category as $p):
						echo "<option value='".$p->code_id."'>".$p->category."</option>";
					  endforeach;
				?>
				</select>
       </div>
		<div class"control-group cso-form-row">
        <label for="text-answer" class="cso-form-label">Answer</label>
			<textarea id="text-answer" name="text-answer" cols="20" rows="5"></textarea>
       </div>
       <div class"control-group cso-form-row">
        <label for="text-tag" class="cso-form-label">Tag</label>
			<input type="text" id="text-tag" name="text-tag" >
       </div>
		 <div class="control-group cso-form-row" align="right">
        	<input type="button" class="btn btn-success" value="Save" id="save" onClick="cekData_save()">
            <input type="button" class="btn btn-danger" value="Cancel" id="cancel" onClick="cekData_cancel()">
		</div>
</form>
</div>
</div>
</body>
</html>
 