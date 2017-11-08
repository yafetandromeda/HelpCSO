<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>List Script</title>
</head>
<link rel="stylesheet" type="text/css" />
	<style type="text/css">
		@import "<?php echo base_url(); ?>tools/datatables/media/css/demo_table_jui.css"; 
		@import "<?php echo base_url(); ?>tools/datatables/media/themes/smoothness/jquery-ui.css"; 
		@import "<?php echo base_url(); ?>tools/css/modal_reveal.css"; 
	</style>
<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>tools/datatables/media/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>tools/jq/modal_reveal.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('#tabledata').dataTable({
			"sPaginationType":"full_numbers",
			"bJQueryUI":true,
			"bFilter":false	
		});
	});
	
	function search_script() {
	  var text_search_script  = document.getElementById('text-search_script').value;
	 		if(text_search_script == ''){
				location.href = "<?php echo base_url(); ?>index.php/user/ctr_view_list_script";
			}
			else {	
				location.href = "<?php echo base_url(); ?>index.php/user/ctr_view_list_script/search_script?text_search_script=" + text_search_script
			}	
	}
	
	function search_script_suggestion() {
	  var text_search_suggestion  = document.getElementById('text-search_script').value;
	  document.getElementById('search_suggestion').style.visibility="visible";
		$.ajax({	

					url: "<?php echo base_url(); ?>index.php/user/ctr_view_list_script/search_script_suggestion?text_search_suggestion=" +text_search_suggestion,
				   success: function(data_script_suggestion){
						if(data_script_suggestion){
							$("#search_suggestion").html(data_script_suggestion);
						}
					}   
			   });
	}
	function chosenText1(){
			document.getElementById('text-search_script').value = document.getElementById('li1').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
			
	}
	function chosenText2(){
			document.getElementById('text-search_script').value = document.getElementById('li2').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText3(){
			document.getElementById('text-search_script').value = document.getElementById('li3').textContent;
			$("#search_suggestion").html("");
	}
	function chosenText4(){
			document.getElementById('text-search_script').value = document.getElementById('li4').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}



</script>
<body>
<div id="list_script">
	<div style="font-weight:bold;font-size:18px;">Search Script&nbsp;&nbsp;&nbsp;
		<input type="text" name="text-search_script" id="text-search_script" onKeyUp="search_script_suggestion()">
		<input type="button" name="search_script" id="search_script"  value="Search" onclick="search_script()" />
	</div>
<div id="search_suggestion"></div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_user">
	<table id="tabledata" class="display">
		<thead>
			<tr>
				<th>Script ID</th>
				<th>Question</th>
			</tr>
		</thead>
		<tbody>
		<?php
			foreach ($list_script as $p):
				 echo "<tr>";
				 echo "<td>".$p->script_id."</td>";
				 echo "<td><a href='".base_url()."index.php/admin/ctr_manage_script/view_script?script_id=".$p->script_id."'>".$p->question."</a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
</form>						
</div>
</body>
</html>
