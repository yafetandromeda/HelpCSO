<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap.css" />
<!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap-responsive.css" /> -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/cso-style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/admin-style.css" />
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>assets/icon/favicon.ico">
</head>

<body id='body_login'>
<div class="container" align="center">
    <form method="post" action="<?php echo base_url('index.php/ctr_helpcso_login/cek_user'); ?>" class="form-horizontal" id="cso-form-login">
    	<h3>HelpCSO</h3>
        <div id="message" class="text-error"><?php //echo $message ; ?></div>
    	<div class="control-group cso-form-row">
        	<label for="text-username" class="cso-form-label">User Name</label>
        	<input type="text" id="text-username" name="text-username" class="cso-form-input">
        </div>
        <div class="control-group cso-form-row">
        	<label for="text-username" class="cso-form-label">Password</label>
        	<input type="password" id="text-password" name="text-password" class="cso-form-input">
        </div>
        <div class="control-group cso-form-row">
        	<button type="submit" id="login" class='btn btn-primary'>Login</button>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
<script type="text/javascript">
$(document).ready(function(){

});
</script>
</body>
</html>

 