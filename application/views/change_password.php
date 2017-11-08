<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
	$session_level = $this->session->userdata('session_level');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1" />
<title>Change Password</title>
</head>
<script type="text/javascript">
	function cekData(){
		var user_level = <?php echo $session_level; ?>;
		var flag = 0;
		var errtxt = 'ERROR :\n';
		
		var new_password = document.getElementById('new-password').value;
		if(new_password == '') {
			errtxt = errtxt + '-. New Password belum diisi\n';	
			flag = 1; 
		}
		var new_password_ulangan = document.getElementById('new-password2').value;
		if(new_password_ulangan == '' || new_password_ulangan != new_password) {
			errtxt = errtxt + '-. Konfirmasi New Password berbeda dengan New Password yang dimasukkan\n';	
			flag = 1; 
		}
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
			if (user_level == 1){
				document.form_change_password.action="<?php echo base_url(); ?>index.php/admin/ctr_manage_user/cek_password";
				document.form_change_password.submit();
			} 
			else {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_user/cek_password",
					data: $("#form_change_password").serialize()
				})
				.done(function(message){
					$("#message").html(message);
				})
				.fail(function(message){
					$("#message").html(message);				
				});
			}
		}
	}
	
	function back_to_home(){	
		if (user_level == 1){
				document.form_change_password.action="<?php echo base_url(); ?>index.php/admin/ctr_manage_user/back_to_home";
				document.form_change_password.submit();
		}
		else {
				$.ajax({
					type: "POST",
					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_user/back_to_home",
					data: $("#form_change_password").serialize()
				})
				.done(function(message){
					$("#message").html(message);
				})
				.fail(function(message){
					$("#message").html(message);				
				});
		}
	}
</script>
<body>
<center>
 <?php if ($session_level == 1){ ?>
	                <h3>Change Password</h3>
 <?php }?>
    <form name="form_change_password" id="form_change_password" method="post" action="">
	<table>
       	<tr>
			<td>User ID</td>
			<td><input type="text" id="text-userID" name="text-userID" value="<?php echo $session_userid;?>" disabled="disabled" class='form-control'></td>
        </tr>
		<tr>
			<td>User Name</td>
			<td><input type="text" id="text-username" name="text-username" value="<?php echo $session_username;?>" disabled="disabled" class='form-control'></td>
        </tr>
		<tr>
			<td>Old Password</td>
			<td><input type="password" id="old-password" name="old-password" class='form-control'></td>
		</tr>
		<tr>
			<td>New Password</td>
			<td><input type="password" id="new-password" name="new-password" class='form-control'></td>
		</tr>
		<tr>
			<td>Konfirmasi New Password</td>
			<td><input type="password" id="new-password2" name="new-password2" class='form-control'></td>
		</tr>
		<tr>
        	<td></td>
        	<td>
            	<input type="button" value="Change Password" id="change_password" name="change_password" onClick="cekData()" class="btn btn-primary">
                <?php if ($session_level == 1){ ?>
	                <input type="button" value="Back" id="back" name="back" onClick="back_to_home()" class="btn">
                <?php }?>
            </td>
		</tr>
    </table>
    </form>
	 <div id="message"><?php echo $message ; ?></div>
</body>
</html>
