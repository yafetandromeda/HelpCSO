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
	
	function search_script() {
	 		var text_search_script  = document.getElementById('text-search_script').value;
			
			if(text_search_script == ''){
				location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_reported_script";
			}
			else {	
				location.href = "<?php echo base_url();?>index.php/admin/ctr_manage_reported_script/search_script?text_search_script=" +encodeURIComponent(text_search_script);
			}
	}
	
	function search_script_suggestion(event) {
	  var text_search_suggestion = document.getElementById('text-search_script').value;
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
						   document.getElementById('li' + j).className = document.getElementById('li' + j).className.replace('hovered','');	
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
	
						url: "<?php echo base_url();?>index.php/admin/ctr_manage_reported_script/search_script_suggestion?text_search_suggestion=" +encodeURIComponent(text_search_suggestion),
					   success: function(data_script){
							if(data_script){
								$("#search_suggestion").html(data_script);
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
</script>

<div style="font-weight:bold;font-size:18px;">Script Report &nbsp;&nbsp;&nbsp;
		<input type="text" name="text-search_script" id="text-search_script" placeholder="Search Script Report" onKeyUp="search_script_suggestion(event)">
		<input type="button" class='btn btn-primary' name="search_script" id="search_script"  value="Search" onclick="search_script()" />
</div>
<div id="search_suggestion"></div>
<form id="form1" name="form1" method="post">
<div id="search_script_user" style="margin: 1% 0px;">
	<table id="tabledata" class="display table table-bordered table-hover">
		<thead>
			<tr>
            	<th>Script Number</th>
				<th style="display:none">Script ID</th>
				<th>Script Question</th>
				<th>Count Reported</th>
			</tr>
		</thead>
		<tbody>
		<?php
			$number = 0;
			foreach ($reported_script as $p):
				 $number = $number + 1;
				 echo "<tr style=\"background-color:" . $p->status_color . "\" class='ticket_row'>" ;
				 echo "<td>".$number."</td>";
				 echo "<td style='display:none'>".$p->script_id."</td>";
				 echo "<td><a href='".base_url()."index.php/admin/ctr_manage_reported_script/form_manage_reported_script?script_id=".$p->script_id."'>".$p->question."</a></td>";
				 echo "<td>".$p->count_reported_script."</td>";
				 echo "</tr>";
			  endforeach;
		?>
		</tbody>
	</table>
</div>
</form>