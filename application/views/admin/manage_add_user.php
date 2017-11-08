<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add User</title>
</head>
<script type="text/javascript">
/*	$(document).ready(function() {
		$("#form_add_user").validate({
			rules: {
				text-username : "required",
				text-password : {
					required : true,
					minlenght : 3
				},
				text-password2 : {
					required : true,
					equalTo : text-password
				},
				level : "required"
			}
		});
	});*/
	function cekData(){
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
		var password_ulangan = document.getElementById('text-password2').value;
		if(password_ulangan == '' || password_ulangan != password) {
			errtxt = errtxt + '-. Password Confirmation not same \n';	
			flag = 1; 
		}
		
		var level = document.getElementById('level').value;
		if(level == '') {
			errtxt = errtxt + '-. Level not chosen \n';	
			flag = 1; 
		}
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				document.form_add_user.action="/HelpCSO/index.php/admin/ctr_manage_user/add_user?flag=1";
				document.form_add_user.submit();
		}
	}
</script>
<body>
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
			<input type="password" id="text-password2" name="text-password2">
	</div>
	<div class"control-group cso-form-row">
			<label for="level" class="cso-form-label">Level</label>
			<select id="level" name="level">
            	<option value=''>--choose--</option>
				<?php foreach ($pil_level as $p):
						echo "<option value='".$p->code_id."'>".$p->level."</option>";
					  endforeach;
				?>
				</select>
	</div>
	<div class"control-group cso-form-row">
        	<input type="submit" class="btn btn-primary" value=" Add User" id="add_user" onclick="cekData()">
	</div>
    </form>
</body>
</html>

 