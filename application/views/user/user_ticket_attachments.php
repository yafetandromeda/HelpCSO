<!-- MD03 - YA - add information pada ticket, Tambah button interaksi di activity plan untuk create new interaksi -->
<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Ticket</a>
    <span class='user-page-subtitle'>- attachments</span>    
    <div class='pull-right'>
      <div class='btn-group'>
    	<a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Detail</a>
	    <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/activities/" . $ticket_id; ?>'>Activity Plan</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/interactions/" . $ticket_id; ?>'> Interactions</a>
        <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_ticket/attachments/" . $ticket_id; ?>'>Attachments</a>
        <!-- <a class="btn btn-danger" href='<?php //echo base_url() . "index.php/user/ctr_ticket/notes/" . $ticket_id; ?>'>Notes</a> -->
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/related/" . $ticket_id; ?>'>Related</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/audit_trail/" . $ticket_id; ?>'>Audit Trail</a>
       </div>
	    
    </div>
</div>
<div class='user-page-content'>
	<?php echo ($message != "") ?  "<div class='well " . $message_type . "'>" . urldecode($message) . "</div>" : ""; ?>
	<form name="form-ticket-attachment" id="form-ticket-attachment" method="post" enctype="multipart/form-data" class="panel panel-default" action="<?php echo base_url(); ?>index.php/user/ctr_ticket/attachment_action/add">
	    <div class='panel-heading'>Attach File</div>
    	<div class='panel-body'>        
            <div class="user-input-group">
                <label for="file-ticket-attachment">File <span style="font-size:smaller">(max: 2MB)</span></label>
                <input type='file' name="file-ticket-attachment" id="file-ticket-attachment" />
            </div>        
            <div class="user-input-group">
                <label for="txt-ticket-attachment-description">Description</label>
                <input type='text' class="form-control" name="txt-attachment-description" id="txt-attachment-description" />        
            </div>	
            <div class="user-input-group">
            	<input type='hidden' name="creator_id" value='<?php echo $userid; ?>' />
                <input type='hidden' name="ticket_id" value='<?php echo $ticket_id; ?>' />
                <label for="btn-ticket-attachment-upload"></label>
                <button type='submit' class="btn btn-primary" name="btn-ticket-attachment-upload" id="btn-ticket-attachment-upload" onclick="return verifyUploadTicketAttachment()">Upload</button>
            </div>	
        </div>
    </form>
   	<table class='table' id='tbl-ticket-attachment'>
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
					. "<td><a class='btn btn-success' href='" . base_url() . "index.php/user/ctr_ticket/attachment_action/download/" . $record->attachment_id . "'>Download</a></td>"
					. "</tr>";	
			}
			?>
        </tr>
    </table>
</div>
<script type="text/javascript">
$("#navbarItemTicket").attr('class', 'active');
function verifyUploadTicketAttachment(){
	return confirm("Are you sure to upload this file?");
	}
</script>