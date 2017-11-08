<script type="text/javascript">
function confirm_logout(){
    var valid = false;
    if (confirm("Are you sure you want to logout?")== true) {
             valid = true;
             window.location='/HelpCSO/index.php/ctr_helpcso_login/logout'
    }
    return valid;
}
</script>
<div id="notification" class="alert alert-info">
	<h3>Notifications</h3>
	<?php 
	if ($count_unread_script <> '0') { 
    	echo "<a href='".base_url()."index.php/admin/ctr_manage_reported_script'>".$count_unread_script."</a> Script Report<br />";
		} 
	else echo "There is no script report<br />";
	if ($count_unread_request <> '0') {
     	echo "<a href='".base_url()."index.php/admin/ctr_manage_requested_script'>".$count_unread_request."</a> New Script Request <br />";
	 }
	 else echo "There is no script request<br />";
	 ?>
</div>

 