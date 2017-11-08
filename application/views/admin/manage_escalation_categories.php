<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>List Escalation Categories</title>
</head>

<script type="text/javascript">
	var i = 0;
	var j = 0;
	var k = 0;
	function cekData_AddCategory(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		
		var catname = document.getElementById('catname').value;
		var category_code = document.getElementById('category_code').value;
		if(catname == '') {
			errtxt = errtxt + '-. Category Name is required\n';	
			flag = 1; 
		}
		
		if(category_code == '') {
			errtxt = errtxt + '-. Category Code is required\n';	
			flag = 1; 
		}
		
		var level = document.getElementById('level').value;
		if(level == '') {
			errtxt = errtxt + '-. Level is required\n';	
			flag = 1; 
		}
		else if(level > 1){
			var par_category = 	document.getElementById('par_category').value;
			if(par_category == '') {
				errtxt = errtxt + '-. Category\'s parent is required\n';	
				flag = 1; 
				}
		}
		else if(level == 1) {
			var par_category = 	0;
		} 
		
		if(flag == 1) alert(errtxt);
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/add_new_category',
						data: "catname=" + encodeURIComponent(catname) + "&category_code=" + encodeURIComponent(category_code) +"&level=" + level + "&par_category=" + par_category
					}).done(function(message){
						alert("New categories has been created successfully");
						location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories";
					});
		}
	}
	
	function saveData_EditCategory(){
		var flag = 0;
		var flag2 = 0;
		var errtxt = 'ERROR :\n';

		var catid_edit = document.getElementById('catid_edit').value;		
		var catname_edit = document.getElementById('catname_edit').value;
		var category_code_edit = document.getElementById('category_code_edit').value;
		var level_edit = document.getElementById('level_edit').value;
		var level_first = document.getElementById('level_first').value;
		
		if(catname_edit == '') {
			errtxt = errtxt + '-. Category Name is required\n';	
			flag = 1; 
		}
		
		if(category_code_edit == '') {
			errtxt = errtxt + '-. Category Code is required\n';	
			flag = 1; 
		}
		
		if(level_edit == '') {
			errtxt = errtxt + '-. Activity Level is required\n';	
			flag = 1; 
		}
		else if(level_edit == 1){
			var par_category_edit = 0;
		}
		else if(level_edit > 1) {
			var par_category_edit = document.getElementById('par_category_edit').value;
			if(par_category_edit == '') {
				errtxt = errtxt + '-. Parent Category is required\n';	
				flag = 1; 
			}
		}
		
		if(level_edit != level_first){	
				flag2 = 1; 
		}
		
		
		if (flag2 == 1) {
			if(flag == 1) alert(errtxt);
			else {
				$.ajax({
						url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/check_child_category?catid=" + catid_edit,
						success: function(data_check_child){
						$("#check_child_category").html(data_check_child);
						var flag_check_child_category = document.getElementById('flag_check_child_category').value;
						if (flag_check_child_category > 0){
								var child_category_name = document.getElementById('child_category_name').value;
								alert ("Category '"+child_category_name+"' is attached to this category, Update is aborted");
						}
						else{
							$.ajax({
								type: 'POST',
								url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/edit_category',
								data: "catid=" + catid_edit + "&catname=" + encodeURIComponent(catname_edit) +"&category_code=" + encodeURIComponent(category_code_edit) +"&level=" + level_edit + "&par_category=" + par_category_edit
							}).done(function(message){
								alert("Categories has been edited successfully");
								location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories";
							}).fail(function(){
								alert("Sorry, an error occcured. Please try again.");
								location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories";
							});
						}
					}
				});
			}
	}
		else {
			if(flag == 1) alert(errtxt);
			else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/edit_category',
						data: "catid=" + catid_edit + "&catname=" + encodeURIComponent(catname_edit) +"&category_code=" + encodeURIComponent(category_code_edit) +"&level=" + level_edit + "&par_category=" + par_category_edit
					}).done(function(message){
						alert("Categories has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories";
					});
		}
	}
}
	function search_categories() {
	  var text_search = document.getElementById('text_search').value;
		if(text_search != ''){
			location.href = "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/search_categories?text_search=" + encodeURIComponent(text_search);
		}	
		else {	
				location.href = "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/manage_categories";
		}	
	}
	
	function search_category_suggestion(event) {
	  var text_search_suggestion  = document.getElementById('text_search').value;
	  document.getElementById('search_suggestion').style.visibility="visible";
	  var keyCode = event.keyCode;
	  
	 if(keyCode == 40){
	 		if(text_search_suggestion == ''){
					document.getElementById('search_suggestion').style.visibility="hidden";
				}
			else{
				k = i + 1;
				if($('#li' + k).length > 0) {
						i = i + 1;
						j = i - 1;
						document.getElementById('li' + i).className = 'hovered';
						//document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
						if(j>0){
						document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');
						}
				}
				else { 
					   if (i == 1) j = i - 1;
					   else j = i;
					   i = 1; 
					   document.getElementById('li' + i).className = 'hovered';
					   //document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
					   if (j>0){
					   document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
					}
				}
			}
		}
		else if(keyCode == 38){
			if(text_search_suggestion == ''){
					document.getElementById('search_suggestion').style.visibility="hidden";
				}
			else{
				k = i - 1;
				if($('#li' + k).length > 0) {
						i = i - 1;
						j = i + 1;
						document.getElementById('li' + i).className = 'hovered';
						//document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
						document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');
				}
				else {
				for(var data_max=1;data_max<=5;data_max++){
					   if($('#li' + data_max).length == 0) { 
							i = data_max - 1; 
							break;
						}
				}
					   j = 1;
					   document.getElementById('li' + i).className = 'hovered';
					   //document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
					   document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
					
				}
			}
		}
		else if(keyCode == 37 || keyCode == 39){
			j = i;
			document.getElementById('search_suggestion').style.visibility="hidden";
			if (j > 0){
				document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
			}
			i = 0;
			j = 0;
			k = 0;
		}

		else if (keyCode == 13){
			j = i;
			document.getElementById('text_search').value = document.getElementById('li' + i).textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			if (j > 0){
				document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
			}
			$("#search_suggestion").html("");
			i = 0;
			j = 0;
			k = 0;
		 }
	 else{
		$.ajax({	

					url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/search_category_suggestion?text_search_suggestion=" +encodeURIComponent(text_search_suggestion),
				   success: function(data_script_suggestion){
						if(data_script_suggestion){
							$("#search_suggestion").html(data_script_suggestion);
							i = 0;
							j = 0;
							k = 0;
						}
					}   
			   });
			}
	}
	
	function chosenText1(){
			document.getElementById('text_search').value = document.getElementById('li1').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
			
	}
	function chosenText2(){
			document.getElementById('text_search').value = document.getElementById('li2').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText3(){
			document.getElementById('text_search').value = document.getElementById('li3').textContent;
			$("#search_suggestion").html("");
	}
	function chosenText4(){
			document.getElementById('text_search').value = document.getElementById('li4').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	function chosenText5(){
			document.getElementById('text_search').value = document.getElementById('li5').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	
	function parent_category_fields(flag){
					
					if (flag == 1) {
						document.getElementById("subcategory").style.display = 'table-cell';
						var level =  document.getElementById('level').value
						var cat_id = document.getElementById('catID').value
						$.ajax({	
				
									url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/parent_category?level=" + level + "&cat_id=" + cat_id,
								   success: function(data_subcategory){
										if(data_subcategory){
											$("#subcategory").html(data_subcategory);
										}
									}   
						});
					}
					else if (flag == 2){
						document.getElementById("subcategory_edit").style.display = 'table-cell';
						var level =  document.getElementById('level_edit').value
						var cat_id = document.getElementById('catid_edit').value
						$.ajax({	
				
									url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/parent_category_edit?level=" + level +"&cat_id=" + cat_id + "&flag=1",
								   success: function(data_subcategory){
										if(data_subcategory){
											$("#subcategory_edit").html(data_subcategory);
										}
									}   
						});				
					}		
	}
	
	function form_edit(catid_edit,catname,category_code,level,par_category){
            document.getElementById("catid_edit").value = catid_edit;
            document.getElementById("catname_edit").value = catname;
			document.getElementById("category_code_edit").value = category_code;
			document.getElementById("level_edit").value = level;
			document.getElementById("level_first").value = level;
			if (level > 1){
				document.getElementById("subcategory_edit").style.display = 'table-cell';
				$.ajax({	
					
										url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/parent_category_edit?level=" + level + "&cat_id=" + catid_edit + "&flag=1",
									   success: function(data_subcategory){
											if(data_subcategory){
												$("#subcategory_edit").html(data_subcategory);
												document.getElementById("par_category_edit").value = par_category;
											}
										}   
							});	
				
			}
			else {
			document.getElementById("subcategory_edit").style.display = 'none';
			$.ajax({	
					
										url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/parent_category_edit?level=" + level + "&cat_id=" + catid_edit + "&flag=0",
									   success: function(data_subcategory){
											if(data_subcategory){
												$("#subcategory_edit").html(data_subcategory);
											}
										}   
							});	
			}
    }
	
	function delete_categories(catid,category){
		var confirmation = confirm('Are you sure want to delete ' + category + ' ?');
		
		if (confirmation == true) {
					$.ajax({
						url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/check_child_category?catid=" + catid,
						success: function(data_check_child){
						$("#check_child_category").html(data_check_child);
						var flag_check_child_category = document.getElementById('flag_check_child_category').value;
						if (flag_check_child_category > 0){
								var child_category_name = document.getElementById('child_category_name').value;
								alert ("Category '"+child_category_name+"' is attached to this category, Delete is aborted");
						}
						else{
							$.ajax({
								type: 'POST',
								url: '<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/delete_category',
								data: "cat_id=" + catid
							}).done(function(message){
								alert("Categories has been deleted successfully");
								location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories";
							}).fail(function(){
								alert("Sorry, an error occcured. Please try again.");
								location.href = "<?php echo base_url();?>index.php/ctr_helpcso_escalation/manage_categories";
							});
						}
					}
				});
		}
	}
</script>
<body>
	<div style="font-weight:bold;font-size:18px;">Escalation Categories&nbsp;&nbsp;&nbsp;
    	<input type="text" name="text_search" id="text_search" placeholder="Search Categories" onKeyUp="search_category_suggestion(event)">
		<input type="button" 
        	name="search_categories" id="search_categories"  value="Search" 
            onClick="search_categories()" class="btn btn-primary" />
		<div id="search_suggestion" style="margin: 0px 0px 0px 200px;">
		</div>
	</div>
<form id="form1" name="form1" method="post" action="" ?>
<div id="search_list_category">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Category Number</th>
				<th style="display:none">Category ID</th>
				<th>Category Name</th>
                <th>Category Level</th>
                <th>Category Parent</th>
				<th>Fields</th>
				<th>Edit Category</th>
                <th>Delete</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			$find = array("\r\n","\n","&quot;");
			$replace = array(" "," ","&#39;");
			foreach ($list_categories as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->cat_id."</td>";
				 echo "<td>".$p->catname."</td>";
				 echo "<td>".$p->level."</td>";
				 echo "<td>".$p->category_parent."</td>";
				 echo "<td><a href='".base_url()."index.php/ctr_helpcso_escalation/manage_categories_fields?cat_id=".$p->cat_id."'><img src='".base_url()."tools/datatables/media/icon/24x24/fields.png'></a></td>";
				 echo "<td><a href='#modal_edit_category' data-toggle='modal' onClick='form_edit(\"".$p->cat_id."\",\"".$p->catname."\",\"".$p->category_code."\",\"".$p->level."\",\"".$p->parent_id."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "<td style='cursor:pointer;' onClick='delete_categories(\"".$p->cat_id."\",\"".$p->catname."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Delete.png'></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
<div id="check_child_category" class="control-group cso-form-row">
</div>
<a href='#modal_add_category' data-toggle="modal" class="btn btn-primary"><i class="icon-plus icon-white"> </i> Add Category</a>



</form>		

	<div id="modal_add_category" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>New Category</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_new_category" id="form_new_category" method="post" action="">
				<div class="control-group cso-form-row">
					<label for="catID" class="cso-form-label">Category ID</label>
					<input type="text" id="catID" name="catID" value="<?php echo $category_id;?>" disabled="disabled">
				</div>
                <div class="control-group cso-form-row">
					<label for="catcode" class="cso-form-label">Category Code</label>
					<input type="text" id="category_code" name="category_code">
			 	</div>
       			<div class="control-group cso-form-row">
					<label for="catname" class="cso-form-label">Category Name</label>
					<input type="text" id="catname" name="catname">
			 	</div>
                <div class="control-group cso-form-row">
					<label for="level" class="cso-form-label">Level</label>
					<select id="level" name="level" onChange="parent_category_fields(1)">
                    	<option value=''>--choose--</option>
						<?php foreach ($pil_levelcategory as $p):
								echo "<option value='".$p->level_id."'>".$p->level_category."</option>";
							  endforeach;
						?>
                    </select>
			 	</div>
                <div id="subcategory" class="control-group cso-form-row">
			 	</div>
			</form>
		</div>
			<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="add_categories" data-dismiss="modal" onClick="cekData_AddCategory()">Add Category</button>
			</div>
	</div>
		
	<div id="modal_edit_category" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Category</h3>
			</div>
			<div class="modal-body-script">
				<form name="form_edit_script" id="form_edit_script" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="catid_edit" class="cso-form-label">Category ID</label>
						<input type="text" id="catid_edit" name="catid_edit" disabled="disabled">
				</div>
                 <div class="control-group cso-form-row">
					<label for="catcode_edit" class="cso-form-label">Category Code</label>
					<input type="text" id="category_code_edit" name="category_code_edit">
			 	</div>
				<div class"control-group cso-form-row">
						<label for="catname_edit" class="cso-form-label">Category Name</label>
						<input type="text" id="catname_edit" name="catname_edit">
				 </div>
				<div class="control-group cso-form-row">
					<label for="level_edit" class="cso-form-label">Level</label>
					<select id="level_edit" name="level_edit" onChange="parent_category_fields(2)">
                    	<option value=''>--choose--</option>
						<?php foreach ($pil_levelcategory as $p):
								echo "<option value='".$p->level_id."'>".$p->level_category."</option>";
							  endforeach;
						?>
                    </select>
                     <input type="hidden" id="level_first" name="level_first">
			 	</div>
                <div id="subcategory_edit" class="control-group cso-form-row">
			 	</div>
			</form>
		</div>
		<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="edit_category" data-dismiss="modal" onClick="saveData_EditCategory()">Edit Category</button>
		</div>
	</div>
</body>
</html>
<?php /*<select id="category_fields" onChange="form_add_categoryfields()">
					<option value="0">-- choose --</option>
					<option value="1">aa</option>
					<option value="2">bb</option>
				</select> 
				
				function form_add_categoryfields(){
					var flag =  document.getElementById("category_fields").value
					$.ajax({	
			
								url: "<?php echo base_url(); ?>index.php/ctr_helpcso_escalation/category_fields?flag=" + flag,
							   success: function(data_category_fields){
									if(data_category_fields){
										$("#show_category_fields").html(data_category_fields);
									}
								}   
						   });
				}*/ 
			?>