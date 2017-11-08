<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_interaction/form/" . $interaction_id; ?>'>Interaction</a>
    <span class='user-page-subtitle'>- attachments</span>
    <div class='pull-right'>
      <div class='btn-group'>
    	<a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_interaction/form/" . $interaction_id; ?>'>Detail</a>    
	    <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_interaction/activities/" . $interaction_id; ?>'>Activities</a>
	    <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_interaction/attachments/" . $interaction_id; ?>'>Attachments</a>
       </div>
    </div>
</div>
<div class='user-page-content'>
	<?php echo ($message != "") ?  "<div class='well " . $message_type . "'>" . urldecode($message) . "</div>" : ""; ?>
   	<?php if ($interaction_status_id != 2 && $interaction_status_id != 5 && $userid == (isset($creator_id) ? $creator_id : $userid)){ ?>
	<form name="form-interaction-attachment" id="form-interaction-attachment" method="post" enctype="multipart/form-data" class="panel panel-default" action="<?php echo base_url(); ?>index.php/user/ctr_interaction/attachment_action/add">
	    <div class='panel-heading'>Attach File</div>
    	<div class='panel-body'>        
            <div class="user-input-group">
                <label for="file-interaction-attachment">File <span style="font-size:smaller">(max: 2MB)</span></label>
                <input type='file' name="file-interaction-attachment" id="file-interaction-attachment" />
            </div>        
            <div class="user-input-group">
                <label for="txt-interaction-attachment-description">Description</label>
                <input type='text' class="form-control" name="txt-attachment-description" id="txt-attachment-description" />        
            </div>	
            <div class="user-input-group">
            	<input type='hidden' name="creator_id" value='<?php echo $userid; ?>' />
                <input type='hidden' name="interaction_id" value='<?php echo $interaction_id; ?>' />
                <label for="btn-interaction-attachment-upload"></label>
                <button type='submit' class="btn btn-primary" name="btn-interaction-attachment-upload" id="btn-interaction-attachment-upload"  onclick="return verifyUploadIntAttachment();">Upload</button>
            </div>	
        </div>
    </form>
    <?php } ?>
   	<table class='table' id='tbl-interaction-activities'>
    	<tr>
        	<th>Description</th>
            <th>File Name</th>
            <th>Upload Date</th>
            <th>Uploader Name</th>
            <th>Download</th> 
        </tr>
        <tr>
        	<?php 
			foreach($attachments as $record){
				$explodedFilePath = explode("/", $record->attachment_name);
				$expLen = count($explodedFilePath);
				echo "<tr>" 
					. "<td>" . $record->attachment_note . "</td>"
					. "<td>" . $explodedFilePath[$expLen - 1] . "</td>"					
					. "<td>" . date("m/d/Y H:i:s", strtotime($record->attachment_datetime)) . "</td>"
					. "<td>" . $record->creator_name . "</td>"
					. "<td><a class='btn btn-success' href='" . base_url() . "index.php/user/ctr_interaction/attachment_action/download/" . $record->attachment_id . "/" . $interaction_id . "'>Download</a></td>"
					. "</tr>";	
			}
			?>
        </tr>
    </table>
</div>
<script type="text/javascript">
$("#navbarItemInteraction").attr('class', 'active');
function verifyUploadIntAttachment(){
	if (confirm("Are you sure to upload the file?") == true)
		return true;
	return false;
	}
</script>