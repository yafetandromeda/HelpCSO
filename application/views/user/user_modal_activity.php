 <div id="modal-activity" class="modal fade">
   <form method="post" name="form-activity" id="form-activity" action='<?php echo $action; ?>'>
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 class="modal-title">Activity</h3>
        </div>
        <div class="modal-body">
	        <div class='user-input-group'>
    			<label for='txt-activity-code'>Activity Code</label>
        		<input type='text' class="form-control" name="txt-activity-code" id="txt-activity-code" value="" readonly  /><button type="button" id="btnTooltip" onclick="getTooltip();" class="btn btn-primary btn-xs"><span class='glyphicon glyphicon-question-sign' id="tooltip" data-toggle="tooltip" data-original-title="" style="cursor:pointer"></span></button>
    		</div>
            <div class='user-input-group'>
    			<label for='txt-activity-code'>Activity Name</label>
        		<input type='text' class="form-control" name="txt-activity-name" id="txt-activity-name" value="" readonly />
    		</div>
			<div class='user-input-group'>
    			<label for='txt-search-activity'>Search</label>
        		<input type='text' class="form-control" name="txt-search-activity" id="txt-search-activity" value="" onkeyup="searchActivity()" />
    		</div>
            <div class='user-input-group'>
    			<label for='cmb-issue-type'>Issue Type</label>
        		<select class="form-control" name="cmb-issue-type" id="cmb-issue-type" onchange="changeActivityCode(1);">
                	<option value="" activity-code="" disabled="disabled" selected="selected">- Pilih -</option>
                	<?php
                    foreach($activity_type as $record){
						echo "<option value='" . $record->activity_id . "' activity-code='" . $record->activity_code . "'>" 
							. $record->activity_description 
							. "</option>";
					}
					?>
                </select>
    		</div>
            <div class='user-input-group'>
    			<label for='cmb-issue-group'>Issue Group</label>
        		<select class="form-control" name="cmb-issue-group" id="cmb-issue-group" onchange="changeActivityCode(2);">
                </select>
    		</div>
            <div class='user-input-group'>
    			<label for='cmb-issue-sub-group'>Issue Sub Group</label>
				<select class="form-control" name="cmb-issue-sub-group" id="cmb-issue-sub-group" onchange="changeActivityCode(3);">
                </select>
    		</div>
            <div class='user-input-group'>
    			<label for='cmb-issue-description'>Issue Description</label>
				<select class="form-control" name="cmb-issue-description" id="cmb-issue-description" onchange="changeActivityCode(4);">
                </select>
    		</div>
        </div>
        <div class="modal-footer">
        	<input type='hidden' id='hdn-activity-id' name='hdn-activity-id' />
            <input type='hidden' id='hdn-interaction-id' name='hdn-interaction-id' value="<?php echo (isset($interaction_id) ? $interaction_id : ""); ?>" />
        	<button name='btn-add' id='btn-add' class="btn btn-primary" onclick="return validateActivity();">OK</button>
            <button name='btn-cancel' id='btn-cancel' class='btn btn-default' data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
    </form>
</div>
<script>
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
</script>