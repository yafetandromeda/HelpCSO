<?php
	$userid = $this->session->userdata('user_id');
	$username = $this->session->userdata('user_name');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User Management</title>
<link rel="stylesheet" href="css/style.css" type="text/css" />
	<style type="text/css">
		@import "/HelpCSO/tools/datatables/media/css/demo_table_jui.css";
		@import "/HelpCSO/tools/datatables/media/themes/smoothness/jquery-ui.css";
	</style>

<script src="/HelpCSO/tools/datatables/media/js/jquery-1.8.3.min.js"></script>
<script src="/HelpCSO/tools/datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#tabledata').dataTable({
			"sPaginationType":"full_numbers",
			"bJQueryUI":true
		});
		
	});
	
	
</script>

</head>

<body>
<form id="form1" name="form1" method="post" action="/HelpCSO/index.php/admin/ctr_manage_user/add_user?flag=0" >
	<table id="tabledata" class="display">
		<thead>
			<tr>
				<th>No</th>
				<th>Nama</th>
				<th>Level</th>
				<th>Edit</th>
				<th>Delete</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($data_user as $p):
				 echo "<tr>";
				 echo "<td>".$p->user_id."</td>";
				 echo "<td>".$p->user_name."</td>";
				 echo "<td>".$p->level."</td>";
				 echo "<td><a href='edit_id?id=".$p->user_id."&user_name=".$p->user_name."&flag=0'><img src='/HelpCSO/tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "<td><a href='delete_id?id=".$p->user_id."' id='delete_user'><img src='/HelpCSO/tools/datatables/media/icon/24x24/Delete.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
	<input type='submit' name='add_button' id='add_button' value='add'/>
</form>
</body>
</html>
