<!-- M006 - YA - Export script to excel -->
<!-- M010 - YA - Import script -->
<!-- M025 - YA - Ubah Tampilan Script -->
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

<script type="text/javascript">
	
	var i = 0;
	var j = 0;
	var k = 0;
	$("body").click(function() {
		document.getElementById('search_suggestion').style.visibility="hidden";
	});
	
	$("#search_suggestion").click(function(e) {
		e.stopPropagation();
	});

	window.onload = function(){
		var level_user = document.getElementById('level_user').value;
		if (level_user != 1)  location.href = "<?php echo base_url();?>"; 
		
		CKEDITOR.replace('text-answer', 
					{
					toolbarGroups: [
							{ name: 'clipboard',   groups: [ 'clipboard', 'undo', 'outdent', 'indent' ] },																	
							{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
							{ name: 'links', groups : [ 'Link','Unlink','Anchor' ] }
						]
					}
		);
		CKEDITOR.replace('text-answer_edit', 
					{
					toolbarGroups: [
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo', 'outdent', 'indent' ] },																	
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
						{ name: 'links', groups : [ 'Link','Unlink','Anchor' ] }
					]
					}
		);
		
		$("#startDate").datepicker({
				dateFormat: "dd M yy",
				defaultDate: 0
		});
		$("#endDate").datepicker({
				dateFormat: "dd M yy",
				defaultDate: 0				
		});
	}
	function cekData_AddScript(){
		var flag = 0;

		var errtxt = 'ERROR :\n';
		
		var script_id = document.getElementById('text-scriptID').value;
		var question = document.getElementById('text-question').value;
		if(question == '') {
			errtxt = errtxt + '-. Question still empty\n';	
			flag = 1; 
		}
		var last_pil_category = document.getElementById('last_pil_category').value;
		var category = document.getElementById('category'+last_pil_category).value;
		var tracking_category = '';
		for (var i = 1; i<=last_pil_category; i++){
			tracking_category = tracking_category + document.getElementById('category'+i).value + ";";
		}
		for (var i = last_pil_category; i<4; i++){
			tracking_category = tracking_category +  " ;";
		}
		var cek_last_pil_category = document.getElementById('cek_last_pil_category').value;
		var cek_last_pil_category2 = document.getElementById('cek_last_pil_category2').value;
		 if(document.getElementById('category'+cek_last_pil_category2).value == '') {
				if (cek_last_pil_category2 == 1) errtxt = errtxt + '-. Ticket Type not chosen\n';	
				else if (cek_last_pil_category2 == 2) errtxt = errtxt + '-. Issue Group not chosen\n';
				else if (cek_last_pil_category2 == 3) errtxt = errtxt + '-. Sub Issue Group not chosen\n';
				else if (cek_last_pil_category2 == 4) errtxt = errtxt + '-. Issue Description not chosen\n';
				flag = 1; 
		}
		else if(document.getElementById('category'+cek_last_pil_category).value == '') {
				if (cek_last_pil_category == 1) errtxt = errtxt + '-. Ticket Type not chosen\n';	
				else if (cek_last_pil_category == 2) errtxt = errtxt + '-. Issue Group not chosen\n';
				else if (cek_last_pil_category == 3) errtxt = errtxt + '-. Sub Issue Group not chosen\n';
				else if (cek_last_pil_category == 4) errtxt = errtxt + '-. Issue Description not chosen\n';
				flag = 1; 
		}
		
		var tag = document.getElementById('text-tag').value;

		var answer = CKEDITOR.instances['text-answer'].getData();
		answer = answer.replace(/(?:&nbsp;|<br>)/g,'');
		if(answer == '') {
			errtxt = errtxt + '-. Answer still empty\n';	
			flag = 1; 
		}
		var visibility = document.getElementById('visibility').value;
		if(visibility == '') {
			errtxt = errtxt + '-. Script visibility not chosen\n';	
			flag = 1; 
		}
		if(flag == 1) { 
						alert(errtxt);
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_script";
		}
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_script/add_new_script',
						data: "text-scriptID=" + script_id +"&text-question=" + encodeURIComponent(question) + "&text-answer=" + escape(answer) + "&text-tag=" + encodeURIComponent(tag) + "&category=" + category + "&visibility=" + visibility + "&tracking_category=" + tracking_category			
					}).done(function(message){
						alert("New data script has been created successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_script";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_script";
					});
		}
	}
	
	function saveData_EditScript(){
		var flag = 0;
		
		var script_id_edit =  document.getElementById('text-scriptID_edit').value;
		var errtxt = 'ERROR :\n';
		
		var question_edit = document.getElementById('text-question_edit').value;
		if(question_edit == '') {
			errtxt = errtxt + '-. Question still empty\n';	
			flag = 1; 
		}
		
		var last_pil_category_edit = document.getElementById('last_pil_category_edit').value;
		var category_edit = document.getElementById('category'+last_pil_category_edit+'_edit').value;
		var tracking_category = '';
		for (var i = 1; i<=last_pil_category_edit; i++){
			tracking_category = tracking_category + document.getElementById('category'+i+'_edit').value + ";";
		}
		for (var i = last_pil_category_edit; i<4; i++){
			tracking_category = tracking_category +  " ;";
		}
		var cek_last_pil_category_edit = document.getElementById('cek_last_pil_category_edit').value;
		var cek_last_pil_category_edit2 = document.getElementById('cek_last_pil_category_edit2').value;
		if(document.getElementById('category'+cek_last_pil_category_edit+'_edit').value == '') {
				if (cek_last_pil_category_edit == 1) errtxt = errtxt + '-. Ticket Type not chosen\n';	
				else if (cek_last_pil_category_edit == 2) errtxt = errtxt + '-. Issue Group not chosen\n';
				else if (cek_last_pil_category_edit == 3) errtxt = errtxt + '-. Sub Issue Group not chosen\n';
				else if (cek_last_pil_category_edit == 4) errtxt = errtxt + '-. Issue Description not chosen\n';
				flag = 1; 
		}
		else if(document.getElementById('category'+cek_last_pil_category_edit2+'_edit').value == '') {
				if (cek_last_pil_category_edit2 == 1) errtxt = errtxt + '-. Ticket Type not chosen\n';	
				else if (cek_last_pil_category_edit2 == 2) errtxt = errtxt + '-. Issue Group not chosen\n';
				else if (cek_last_pil_category_edit2 == 3) errtxt = errtxt + '-. Sub Issue Group not chosen\n';
				else if (cek_last_pil_category_edit2 == 4) errtxt = errtxt + '-. Issue Description not chosen\n';
				flag = 1; 
		}
		var tag_edit = document.getElementById('text-tag_edit').value;
		
		var answer_edit = CKEDITOR.instances['text-answer_edit'].getData();
		answer_edit = answer_edit.replace(/(?:&nbsp;|<br>)/g,'');
		if(answer_edit == '') {
			errtxt = errtxt + '-. Answer still empty\n';	
			flag = 1; 
		}
		var visibility_edit = document.getElementById('visibility_edit').value;
		if(visibility_edit == '') {
			errtxt = errtxt + '-. Script visibility not chosen\n';	
			flag = 1; 
		}
		if(flag == 1) {
			alert(errtxt);
			}
		else if(flag == 0) {
				$.ajax({
						type: 'POST',
						url: '<?php echo base_url(); ?>index.php/admin/ctr_manage_script/save_edited_script',
						data: "script_id_edit=" + script_id_edit + "&text-question_edit=" + encodeURIComponent(question_edit) + "&text-answer_edit=" + escape(answer_edit)
						+ "&text-tag_edit=" + encodeURIComponent(tag_edit) + "&category_edit=" + category_edit + "&visibility_edit=" + visibility_edit + "&tracking_category=" + tracking_category		
					}).done(function(message){
						alert("Data script has been edited successfully");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_script";
					}).fail(function(){
						alert("Sorry, an error occcured. Please try again.");
						location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_script";
					});
		}
	}
	
	function search_script() {
	 		
	  var startDate = $("#startDate").datepicker('getDate');
	  var text_startDate = document.getElementById('startDate').value;
	  var endDate = $("#endDate").datepicker('getDate');
	  var text_endDate = document.getElementById('endDate').value;
	  var text_search_script  = document.getElementById('text-search_script').value;
	  
	  var startDate_validation = new Date(text_startDate);
	  var endDate_validation = new Date(text_endDate);
	  var today = new Date(Date.now());		  
				  
	  var sday = startDate_validation.getDate();
	  var smonth = (startDate_validation.getMonth()+1);
	  var eday = endDate_validation.getDate();
	  var emonth = (endDate_validation.getMonth()+1);
	  var tday = today.getDate();
	  var tmonth = (today.getMonth()+1);
	  
	  var comp_startDate = startDate.getFullYear() + '-' + smonth + '-' + sday;
      var comp_endDate = endDate.getFullYear() +'-'+ emonth + '-' + eday;
      var comp_today = today.getFullYear()+ '-' + tmonth+  '-'+ tday;
	  
	  startDate_validation = smonth+'/'+sday+'/'+startDate_validation.getFullYear();
	  endDate_validation = emonth+'/'+eday+'/'+endDate_validation.getFullYear();
	  today = tmonth+'/'+tday+'/'+today.getFullYear();
	  
	  if(text_startDate != '' || text_endDate != ''){
		  var flag = 0;

	 	  var errtxt = 'ERROR :\n';
	  
		  if(text_startDate == '') {
						errtxt = errtxt + '-. Start Date still empty\n';	
						flag = 1; 
		  }
		  
		  if(text_endDate == '') {
						errtxt = errtxt + '-. End Date still empty\n';	
						flag = 1; 
		  }
					
		  if(comp_startDate  > comp_endDate && text_startDate != '' && text_endDate != '' ) {
						errtxt = errtxt + '-. Date not valid\n';	
						flag = 1; 
		  } 
		  if(comp_today < comp_startDate) {
						errtxt = errtxt + '-. Start Date cannot exceed today\n';	
						flag = 1; 
		  } 
		  if(comp_today < comp_endDate) {
						errtxt = errtxt + '-. End Date cannot exceed today\n';	
						flag = 1; 
		  } 
		  if(flag == 1) alert(errtxt);
			else {	
				if(text_search_script == '' && text_startDate == '' && text_endDate == ''){
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_script";
				}
				else{
					location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_script/search_script?text_search_script=" + encodeURIComponent(text_search_script) + "&startDate="+startDate.getFullYear() + "-" + (startDate.getMonth() + 1) + "-" + startDate.getDate()+"&endDate="+endDate.getFullYear() + "-" + (endDate.getMonth() + 1) + "-" + endDate.getDate();
				}
			}	
	  }
	  else {
		  	if(text_search_script == ''){
				location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_script";
			}
			else {	
				location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_script/search_script?text_search_script=" + encodeURIComponent(text_search_script) + "&startDate=0&endDate=0";
			}	
	  }
}
	
	function search_script_suggestion(event) {
	  var text_search_suggestion  = document.getElementById('text-search_script').value;
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
						//document.getElementById('text-search_script').value = document.getElementById('li' + i).textContent;
						if(j>0){
						document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');
						}
				}
				else { 
					   if (i == 1) j = i - 1;
					   else j = i;
					   i = 1; 
					   document.getElementById('li' + i).className = 'hovered';
					   //document.getElementById('text-search_script').value = document.getElementById('li' + i).textContent;
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
						//document.getElementById('text-search_script').value = document.getElementById('li' + i).textContent;
						document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');
				}
				else {
				for(var data_max=1;data_max<=6;data_max++){
					   if($('#li' + data_max).length == 0) { 
							i = data_max - 1; 
							break;
						}
				}
					   j = 1;
					   document.getElementById('li' + i).className = 'hovered';
					   //document.getElementById('text-search_script').value = document.getElementById('li' + i).textContent;
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
			document.getElementById('text-search_script').value = document.getElementById('li' + i).textContent;
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

					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_script/search_script_suggestion?text_search_suggestion=" +encodeURIComponent(text_search_suggestion),
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
	function chosenText5(){
			document.getElementById('text-search_script').value = document.getElementById('li5').textContent;
			document.getElementById('search_suggestion').style.visibility="hidden";
			$("#search_suggestion").html("");
	}
	
	function show_subcategory(flag,level){
					if (flag == 1) {
						var par_category = document.getElementById('category'+level).value;
						$.ajax({	
				
								url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_script/script_subcategory?flag=" +flag+ "&par_category=" + par_category + "&level=" + level,
								   success: function(data_subcategory){
									if(data_subcategory){
										if (level == 1){
											$("#subcategory1").html(data_subcategory);
											$("#subcategory2").html("");
											$("#subcategory3").html("");
											var level_subcategory = document.getElementById('level_subcategory1').value;
											var level_field_subcategory = document.getElementById('level_field_subcategory1').value;
										}
										else if (level == 2){
											$("#subcategory2").html(data_subcategory);
											$("#subcategory3").html("");
											var level_subcategory = document.getElementById('level_subcategory2').value;
											var level_field_subcategory = document.getElementById('level_field_subcategory2').value;
										}
										else if (level == 3){
											$("#subcategory3").html(data_subcategory);
											var level_subcategory = document.getElementById('level_subcategory3').value;
											var level_field_subcategory = document.getElementById('level_field_subcategory3').value;
										}
										document.getElementById('last_pil_category').value = level;
										document.getElementById('cek_last_pil_category').value = level_subcategory;
										document.getElementById('cek_last_pil_category2').value = level_field_subcategory;
									}  
								   }
						});
					}	
			else if (flag == 2) {
						var par_category = document.getElementById('category'+level+'_edit').value;
						$.ajax({	
					
								url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_script/script_subcategory?flag=" +flag+ "&par_category=" + par_category + "&level=" + level,
								   success: function(data_subcategory_edit){
									if(data_subcategory_edit){
										if (level == 1){
											$("#subcategory1_edit").html(data_subcategory_edit);
											$("#subcategory2_edit").html("");
											$("#subcategory3_edit").html("");
											var level_subcategory_edit = document.getElementById('level_subcategory1').value
										}
										else if (level == 2){
											$("#subcategory2_edit").html(data_subcategory_edit);
											$("#subcategory3_edit").html("");
											var level_subcategory_edit = document.getElementById('level_subcategory2').value
										}
										else if (level == 3){
											$("#subcategory3_edit").html(data_subcategory_edit);
											var level_subcategory_edit = document.getElementById('level_subcategory3').value
										}
										document.getElementById('last_pil_category_edit').value = level;
										document.getElementById('cek_last_pil_category_edit').value = level;
										document.getElementById('cek_last_pil_category_edit2').value = level_subcategory_edit;
									}  
								   }
						});
					}	
	}

	function form_edit(script_id,question,answer_id,tag,visibility,tracking_category)
    {
			document.getElementById("subcategory1_edit").style.visibility = 'visible'
			document.getElementById("subcategory2_edit").style.visibility = 'visible'
			document.getElementById("subcategory3_edit").style.visibility = 'visible'
            document.getElementById("text-scriptID_edit").value = script_id;
            document.getElementById("text-question_edit").value = question;
			var answer = document.getElementById(answer_id).innerHTML;
            CKEDITOR.instances['text-answer_edit'].setData(answer);
			document.getElementById("text-tag_edit").value = tag;
			document.getElementById("visibility_edit").value = visibility;
			
			var category = tracking_category.split(';');
			if (category[1] == null) tracking_category = tracking_category + '; ; ; ;'
			
			if (category[1] != ' ' || category[1] != null) {
			$.ajax({	
					
					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_script/script_subcategory_edit?tracking_category=" + tracking_category + "&flag_level=1",
					success: function(data_subcategory_edit){
					if(data_subcategory_edit){
								$("#subcategory1_edit").html(data_subcategory_edit);
								document.getElementById("category2_edit").value = category[1];
							}  
					}
				});
			}
			
			if (category[2] != ' '){
			$.ajax({	
					
					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_script/script_subcategory_edit?tracking_category=" + tracking_category + "&flag_level=2",
					success: function(data_subcategory_edit){
					if(data_subcategory_edit){
								$("#subcategory2_edit").html(data_subcategory_edit);
								document.getElementById("category3_edit").value = category[2];

							}  
					}
				});
			}
			if (category[3] != ' '){
			$.ajax({	
					
					url: "<?php echo base_url(); ?>index.php/admin/ctr_manage_script/script_subcategory_edit?tracking_category=" + tracking_category + "&flag_level=3",
					success: function(data_subcategory_edit){
					if(data_subcategory_edit){
								$("#subcategory3_edit").html(data_subcategory_edit);
								document.getElementById("category4_edit").value = category[3];
							}  
					}
				});
			}
			
			document.getElementById("category1_edit").value = category[0];
			if (category[1] == ' ' || category[1] == null) var level_subcategory_edit = 1;
			else if (category[2] == ' ') var level_subcategory_edit = 2;
			else if (category[3] == ' ') var level_subcategory_edit = 3;
			document.getElementById('last_pil_category_edit').value = level_subcategory_edit;
			document.getElementById('cek_last_pil_category_edit').value = level_subcategory_edit;
    }
    //M006
    function toexcel(){
		location.href = "<?php echo base_url(); ?>index.php/admin/ctr_manage_script/script_toexcel?";
    }
    //M006
</script>
<input type='hidden' id='last_pil_category' value='1'/>
<input type='hidden' id='cek_last_pil_category' value='1'/>
<input type='hidden' id='cek_last_pil_category2' value='1'/>
<input type='hidden' id='last_pil_category_edit' value='1'/>
<input type='hidden' id='cek_last_pil_category_edit' value='1'/>
<input type='hidden' id='cek_last_pil_category_edit2' value='1'/>
<input type="hidden" id="level_user" name="level_user" value="<?php echo $this->session->userdata('session_level'); ?>">
<body>
<!-- M006 -->
<div style="font-weight:bold;font-size:18px;">Export To Excel&nbsp;&nbsp;&nbsp;
<?php 
echo "<a title='Export To Excel' onclick='toexcel()' style='cursor:pointer')>
	  <img src='".base_url()."tools/datatables/media/icon/24x24/export.png'></a>";
?>
</div>
<!-- M006 -->
<!-- M010 -->
<?php echo form_open_multipart('admin/ctr_manage_script/do_upload');?>
<input type="file" id="file_upload" name="userfile" size="20" />
<input type="submit" value="Upload" />
<?php echo form_close();?>
<!-- M010 -->
	<div style="font-weight:bold;font-size:18px;">Search Script&nbsp;&nbsp;&nbsp;
		<input type="text" name="text-search_script" id="text-search_script" onKeyUp="search_script_suggestion(event)" >
        <!-- <label for="startDate">Date</label>
		<input type="text" name="startDate" id="startDate" /> To <input type="text" name="endDate" id="endDate" /> 
		<input type="button" class='btn btn-primary' name="search_script" id="search_script"  value="Search" onclick="search_script()" /> -->
		<div id="search_suggestion">
		</div>
	</div>
<form id="form1" name="form1" method="get" action="" ?>
<div id="search_list_user">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Activity Code</th>
				<th style="display:none">Script ID</th>
				<th>Question</th>
				<th>Views</th>
                <th>Script Create Datetime</th>
				<th>Edit</th>
			</tr>
		</thead>
		<tbody>
		<?php
/*			$find = array("\r\n","\n","&quot;");
			$replace = array(" "," ","&#39;");*/
			$number = 0;
			foreach ($list_script as $p):
				 $number = $number + 1;
				 echo "<tr>";
				 echo "<td>".$p->activity_code."</td>";
				 echo "<td style='display:none'>".$p->script_id."</td>";
				 echo "<td>".$p->question."</td>";
				 echo "<td>".$p->count_view."</td>";
				 echo "<td>".$p->create_datetime."</td>";
				 echo "<td><span id='answer_".$p->script_id."' style='display:none;'>".$p->answer."</span><a href='#modal_edit_script' data-toggle='modal' onClick='form_edit(\"".$p->script_id."\",\"".$p->question."\",\"answer_".$p->script_id."\",\"".$p->tag."\",\"".$p->visibility."\",\"".$p->tracking_category."\")'><img src='".base_url()."tools/datatables/media/icon/24x24/Edit.png'></a></td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
<a href='#modal_add_script' data-toggle="modal"><input type='submit' name='add_button' id='add_button' value='add' class="btn btn-primary"/></a>
</form>


	<div id="modal_add_script" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>New Script</h3>
			</div>
		<div class="modal-body-script">
			<form name="form_new_script" id="form_new_script" method="post" action="">
			<div class="control-group cso-form-row">
					<label for="text-scriptID" class="cso-form-label">Script ID</label>
					<input type="text" id="text-scriptID" name="text-scriptID" value="<?php echo $script_id;?>" disabled="disabled">
			</div>
			<div class="control-group cso-form-row">
					<label for="text-question" class="cso-form-label">Question</label>
					<input type="text" id="text-question" name="text-question">
			 </div>
			 <!-- <div class="control-group cso-form-row">
			   <label for="category" class="cso-form-label">Ticket Type</label>
				<select id="category1" name="category1" onchange="show_subcategory(1,1)">
						<option value=''>--choose--</option>
						<?php //foreach ($pil_category1 as $p):
								//echo "<option value='".$p->code_id."'>".$p->category."</option>";
							  //endforeach;
						?>
						</select>
			   </div> -->
               <div id="subcategory1" class="control-group cso-form-row">
               </div>
               <div id="subcategory2" class="control-group cso-form-row">
               </div>
               <div id="subcategory3" class="control-group cso-form-row">
               </div>
				<div class="control-group cso-form-row">
					<label for="text-answer" class="cso-form-label">Answer</label>
					<textarea id="text-answer" name="text-answer" cols="20" rows="5"></textarea>
			   </div>
			   <div class="control-group cso-form-row">
					<label for="text-tag" class="cso-form-label">Tag</label>
					<input type="text" id="text-tag" name="text-tag">
			   </div>
               <div class="control-group cso-form-row">
			   <label for="visibility" class="cso-form-label">Script Visibility</label>
				<select id="visibility" name="visibility">
						<option value=''>--choose--</option>
						<option value='1'>Show</option>
                        <option value='0'>Hide</option>
						</select>
			   </div>
			</form>
		</div>
			<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="add_script" data-dismiss="modal" onClick="cekData_AddScript()">Add Script</button>
			</div>
	</div>
		
	<div id="modal_edit_script" class="modal hide fade" style="width: 750px; height: 800px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Edit Script</h3>
			</div>
			<div class="modal-body-script">
				<form name="form_edit_script" id="form_edit_script" method="post" action="">
				<div class"control-group cso-form-row">
						<label for="text-scriptID" class="cso-form-label">Script ID</label>
						<input type="text" id="text-scriptID_edit" name="text-scriptID_edit" disabled="disabled">
				</div>
				<div class"control-group cso-form-row">
						<label for="text-question" class="cso-form-label">Question</label>
						<input type="text" id="text-question_edit" name="text-question_edit">
				 </div>
				 <!-- <div class"control-group cso-form-row">
				   <label for="category" class="cso-form-label">Ticket Type</label>
					<select id="category1_edit" name="category1_edit" onchange="show_subcategory(2,1)" >
							<option value=''>--choose--</option>
							<?php //foreach ($pil_category1 as $p):
									//echo "<option value='".$p->code_id."'>".$p->category."</option>";
								  //endforeach;
							?>
							</select>
				   </div> -->
                   <div id="subcategory1_edit" class="control-group cso-form-row">
                   </div>
                   <div id="subcategory2_edit" class="control-group cso-form-row">
                   </div>
                   <div id="subcategory3_edit" class="control-group cso-form-row">
                   </div>
					<div class"control-group cso-form-row">
						<label for="text-answer" class="cso-form-label">Answer</label>
						<textarea id="text-answer_edit" name="text-answer_edit" cols="20" rows="5"></textarea>
				   </div>
				   <div class"control-group cso-form-row">
						<label for="text-tag" class="cso-form-label">Tag</label>
						<input type="text" id="text-tag_edit" name="text-tag_edit">
				   </div>
                   <div class="control-group cso-form-row">
                   <label for="visibility_edit" class="cso-form-label">Script Visibility</label>
                    <select id="visibility_edit" name="visibility_edit">
                            <option value=''>--choose--</option>
                            <option value='1'>Show</option>
                            <option value='0'>Hide</option>
                            </select>
                   </div>
			</form>	
			</form>
		</div>
		<div class="modal-footer">
				<button type="button" class="btn btn_primary" id="edit_script" data-dismiss="modal" onClick="saveData_EditScript()">Edit Script</button>
		</div>
	</div>
</body>
</html>
