<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Edit User</title>
</head>
<script type="text/javascript">
	function cekData(){
		var flag = 0;
		var user_id = var username = document.getElementById('text-userID').value;
		var errtxt = 'ERROR :\n';
		
		var username = document.getElementById('text-username').value;
		if(username == '') {
			errtxt = errtxt + '-. UserName belum diisi\n';	
			flag = 1; 
		}
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				document.form_edit_user.action="<?php echo base_url(); ?>index.php/admin/ctr_manage_user/edit_id?id=" +user_id ;
				document.form_edit_user.submit();
		}
	}
</script>
<body>
    <form name="form_edit_user" id="form_edit_user" method="post" action="">
	<div class"control-group cso-form-row">
			<label for="text-userID" class="cso-form-label">User ID</label>
			<input type="text" id="text-userID" name="text-userID" value="<?php echo $user_id;?>" disabled="disabled">
    </div>
	<div class"control-group cso-form-row">
   	 		<label for="text-username" class="cso-form-label">User Name</label>
			<input type="text" id="text-username" name="text-username" value="<?php echo $user_name;?>">
    </div>
	<div class"control-group cso-form-row">
			<label for="level" class="cso-form-label">Level</label>
			<select name="level">
				<?php foreach ($pil_level as $p):
						echo "<option value='".$p->code_id."'>".$p->level."</option>";
					  endforeach;
				?>
				</select>
 	</div>
	<div class="control-group cso-form-row">
        	<input type="button" class="btn btn-primary" value="Update User" id="update_user" onclick="cekData()">
	</div>
    </form>
</body>
</html>

 