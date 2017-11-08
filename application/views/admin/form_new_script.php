<!DOCTYPE html>
<html>
<head>
<title>New Script</title>
</head>
<script type="text/javascript">
	function cekData(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		
		var question = document.getElementById('text-question').value;
		if(question == '') {
			errtxt = errtxt + '-. Question still empty\n';	
			flag = 1; 
		}
		
		var category = document.getElementById('category').value;
		if(category == '') {
			errtxt = errtxt + '-. Category not chosen\n';	
			flag = 1; 
		}
		
		var answer = document.getElementById('text-answer').value;
		if(answer == '') {
			errtxt = errtxt + '-. Answer still empty\n';	
			flag = 1; 
		}
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				document.form_new_script.action="<?php echo base_url(); ?>index.php/admin/ctr_manage_script/add_new_script?flag=1";
				document.form_new_script.submit();
		}
	}
	window.onload = function(){
		CKEDITOR.replace('text-answer', 
					{
					toolbarGroups: [
							{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
							{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
						]
					}
		);
	}
</script>
<body>
<div class="container">
<h1>New Script</h1>
    <form name="form_new_script" id="form_new_script" method="post" action="">
	<div class"control-group cso-form-row">
			<label for="text-scriptID" class="cso-form-label">Script ID</label>
			<input type="text" id="text-scriptID" name="text-scriptID" value="<?php echo $script_id;?>" disabled="disabled">
    </div>
	<div class"control-group cso-form-row">
			<label for="text-question" class="cso-form-label">Question</label>
			<input type="text" id="text-question" name="text-question">
     </div>
     <div class"control-group cso-form-row">
       <label for="category" class="cso-form-label">Category</label>
	  	<select id="category" name="category">
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
			<input type="text" id="text-tag" name="text-tag">
       </div>
  		<div class="control-group cso-form-row">
        	<input type="button" class="btn btn-primary" value="Insert Question" id="Insert Question" onClick="cekData()"></td>
		</div>
    </table>
</form>
</div>
</body>
</html>

 