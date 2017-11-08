<!-- M015 - YA - Search Script -->
<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	Script
</div>    
<?php $this->load->view("user/user_wording_greetings"); ?>
<div class="well alert alert-block">
<!--     <form id="form_search_script" name="form_search_script" 
        class="form-horizontal" method="get" 
        action="<?php //echo base_url(); ?>index.php/user/ctr_view_list_script/search_script"> -->
        <div id='div_category'>
            <label for="category1" id="category_label">Category</label>
            <!-- <select name="category1" id="category1" class="combobox_category" data-level='1'>
            </select> -->

            <!-- M015 -->
            <div class='search-input-group'>
	            <table>
		            <input type='hidden' class="form-control" name="txt-activity-code" id="txt-activity-code" value="" readonly  />
            	
    				<tr><td>
    					<label for='cmb-issue-type'>Issue Type</label></td>
        			<td width="300px">
        				<select class="form-control" name="cmb-issue-type" id="cmb-issue-type" onchange="changeActivityCode(1);">
	                		<option value="" activity-code="" disabled="disabled" selected="selected">- Pilih -</option>
		                	<?php
		                    foreach($activity_type as $record){
								echo "<option value='" . $record->activity_id . "' activity-code='" . $record->activity_parent . "'>" 
									. $record->activity_description 
									. "</option>";
							}
							?>
		                </select>
	                </td></tr>
	    			<tr><td>
	    				<label for='cmb-issue-group'>Issue Group</label></td>
        			<td>
        				<select class="form-control" name="cmb-issue-group" id="cmb-issue-group" onchange="changeActivityCode(2);">
                		</select>
                	</td></tr>
    				<tr><td>
    					<label for='cmb-issue-sub-group'>Issue Sub Group</label></td>
					<td>
						<select class="form-control" name="cmb-issue-sub-group" id="cmb-issue-sub-group" onchange="changeActivityCode(3);">
	                	</select>
                	</td></tr>
    				<tr><td>
    					<label for='cmb-issue-description'>Issue Description</label></td>
					<td>
						<select class="form-control" name="cmb-issue-description" id="cmb-issue-description" onchange="changeActivityCode(4);">
		                </select>
	                </td>
		        	<td width="100px" align="center">
		        		<button type="submit" class="btn btn-primary" id="btn-search" onclick="search()">Search</button><br /></td></tr>
    		<!-- M015 -->
		        	<tr><td>
		        		<label for="text_search" id="keyword_label">Keyword</label></td>
		        	<td>
		        		<input type="search" placeholder='' id="text_search" name="text_search" />
		        	</td></tr>
		        		<input type="hidden" name="category_id" id="category_id" value="" />
		        </table>
		    </div>
        </div>
    <!-- </form> -->
    <div id="cso_search_suggestion"></div>
    <div id='cso-content'>
    	<h5>Top Scripts</h5>
            <ul>
            <?php foreach ($top_scripts as $script): ?>
                <li>
                    <a href="<?php echo base_url(); ?>index.php/user/ctr_view_list_script/view_script/<?php echo $script->script_id; ?>">
                            <?php echo $script->question; ?>
                    </a>
                </li>
            <?php endforeach; ?>
            </ul>
    </div>
</div>
<?php $this->load->view("user/user_wording_reconfirmation"); ?>
<?php $this->load->view("user/user_wording_closing"); ?>
<script type="text/javascript">
$("#navbarItemScript").attr('class', 'active');
$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_category/1'
					}).done(function(message){
						$("#category1").html("<option value=''>-- All Categories --</option>" + message);
					}).fail(function(){
					
					});
				$("#text_search").bind('keyup', function(){
					perform_search();
				});
				$(".combobox_category").live('change', function(){
					category_id = $(this).val();
					level = parseInt($(this).attr('data-level')) + 1;
					level2 = level + 1;
					$("select.combobox_category[data-level=" + level + "]").remove();
					$("select.combobox_category[data-level=" + level2 + "]").remove();
					$("#category_id").val(category_id);
					if (category_id != ''){
						$.ajax({
							type: 'POST',
							url: '<?php echo base_url(); ?>index.php/user/ctr_home_user/ajax_category/' 
								+ level + '/' + category_id
						}).done(function(message){
							var str;
							if (message != ''){
								str = "<select class='combobox_category' id='category" + level + "' data-level='" + level + "'><option value=''>-- All Categories --</option>" + message + "</select>";
								$("#div_category").append(str);
							}
						}).fail(function(){
						
						});
					}
				});
				function perform_search(){
					category_id = $("#category_id").val();
					keyword = $("#text_search").val();
					$.ajax({
						type: "GET",
						url: "<?php echo base_url(); ?>index.php/user/ctr_view_list_script/search_script_suggestion",
						data: "category_id=" + category_id + "&text_search_suggestion=" + keyword
					}).done(function(message){
						$("#cso_search_suggestion").html("<div><h5>Search Result: </h5></div>" + message);
						$("#cso-content").addClass('hidden');
						$(".suggestion_item").on('click', function(){
							var id = $(this).attr('id');
							var script_id = id.split('_')[1];
							window.location.href = "<?php echo base_url(); ?>index.php/user/ctr_view_list_script/view_script/" + script_id;
						});
					}).fail(function(message){
						alert("Sorry, an error occured. Please try again.");
					});			
				}	

$("#txt-activity-code").val($("#cmb-issue-type").val());
// bind event
function selectComboBox(level){
	var $combobox;
	switch (level){
		case 1:
			$combobox = $("#cmb-issue-type");
			break;
		case 2:
			$combobox = $("#cmb-issue-group");
			break;
		case 3:
			$combobox = $("#cmb-issue-sub-group");
			break;
		case 4:
			$combobox = $("#cmb-issue-description");
			break;
	}
	return $combobox;
}
function changeActivityCode(level){
	var $combobox = selectComboBox(level);
	var $selected = $combobox.children("option:selected");
	getChildActivity(level + 1, $combobox.val());
	
	$("#hdn-activity-id").val($selected.val());
	$("#txt-activity-code").val($selected.attr('activity-code'));
	$("#txt-activity-name").val($selected.text());
}
function getChildActivity(level, activity_code){
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url(); ?>index.php/user/ctr_interaction/ajax_activity',
		data: { 'type': 'children', 'value': activity_code }
	}).done(function(message){
		var $combobox = selectComboBox(level);
		$combobox.html(message);
	}).fail(function(){
	});
}
function searchActivity(){
	var txt = document.getElementById('txt-search-activity').value;
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url(); ?>index.php/user/ctr_interaction/ajax_activity',
		data: { 'type': 'word', 'value': txt }
	}).done(function(message){
		var result = message.split("##");
		$("#hdn-activity-id").val(result[0]);
		$("#txt-activity-code").val(result[1]);
		$("#txt-activity-name").val(result[2]);
	}).fail(function(){
	});
}
function getTooltip(){
	var txt = document.getElementById('hdn-activity-id').value;
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url(); ?>index.php/user/ctr_interaction/ajax_activity',
		data: { 'type': 'definition', 'value': txt }
	}).done(function(message){
		var result = message;
		$("#btnTooltip .popover-content").html(result);
		$("#tooltip").popover({ html: true, title: 'Definition', content: result, placement: "bottom" });
	}).fail(function(){
	});
	}
function validateActivity(){
	var act_code = document.getElementById('txt-activity-code').value;
	if (act_code.length == 10)
		return true;
	alert("Silakan pilih issue description dengan tepat");
	return false;
}

function search(){
	var activity_code = document.getElementById('txt-activity-code').value;
	if (activity_code.length == 10){
		location.href = "<?php echo base_url(); ?>index.php/user/ctr_view_list_script/view_script_by_activity_code/" + activity_code;
	}else{
		alert("Silakan pilih issue description dengan tepat");
	}
}
</script>