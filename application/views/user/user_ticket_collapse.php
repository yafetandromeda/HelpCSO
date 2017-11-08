<!-- M001 - YA - Ubah Field -->
<!-- M004 - YA - admin super akses -->
<!-- M019 - YA - Recover Script -->
<!-- M026 - YA - Menampilkan Handled, Recovering dan Recovered Ticket -->
<!-- M035 - YA - Menampilkan hour berisi SLA -->
<!-- M038 - YA - menyamakan customer name -->
<!-- M043 - YA - tambah note untuk solved dan closed ticket -->
<!-- M045 - YA - Ubah field -->
<!-- M046 - YA - Ubah tampilan baloon, hyperlink di showlist,  tambah status draft, tambah SLA -->
<!-- M051 - YA - Ubah report activity, ticket, & interaction -->
<!-- MD03 - YA - add information pada ticket, Tambah button interaksi di activity plan untuk create new interaksi -->
<?php
	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
	$usergroupid = $this->session->userdata('session_user_group_id');
    
?>
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Ticket</a>
    
</div>
<body>
<form id="form_ticket" method="post" action="<?php echo base_url() . "index.php/user/ctr_ticket/form_action/save/" . $ticket_id; ?>">
<div class="container">
<div class="row">
<!-- <div class="col-sm-6"> -->
    <div class="panel-group" id="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#one">Detail</a>
            </h4>
        </div>
        <div id="one" class="panel-collapse collapse in">
        <div class="panel-body">
            <div class='user-page-content' id='pnl-ticket'>
    <div class='col-md-12 pull-right' align="right">
    <!--M004-->
      <?php if ($level == 1 || $level == 3 && isset($ticket_data->owner_group_id) && $ticket_data->owner_group_id == $usergroupid) { ?>
    <!--M004-->
        <?php if (isset($ticket_data->owner_name)){ ?>
            <?php if ($ticket_data->owner_name == $username && ($ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 10 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12)){ ?>
                <a class="btn btn-danger" id='btn-unhandle' href='<?php echo base_url() . "index.php/user/ctr_ticket/form_action/unhandle/" . $ticket_id; ?>'>
                    <span class='glyphicon glyphicon-remove'> </span> Unhandle
                </a>
                <a class="btn btn-primary" id='btn-change-owner'>
                    <span class='glyphicon glyphicon-pencil'> </span> Change Owner
                </a>
                <!-- M043 -->
                <button type="button" class="btn btn-warning" id='btn-close'>
                    <span class='glyphicon glyphicon-folder-close'> </span> Close
                </button>
                <button type="button" class="btn btn-success" id='btn-solve'>
                    <span class='glyphicon glyphicon-ok'> </span> Solve
                </button>
                <!-- M043 -->
             <?php } ?>
        <?php } else if ($level==3) {?>
        <a class="btn btn-success" id='btn-handle' href='<?php echo base_url() . "index.php/user/ctr_ticket/form_action/handle/" . $ticket_id; ?>'>
            <span class='glyphicon glyphicon-ok'> </span> Handle
        </a>
        <?php } ?>
      <?php } ?>
      <?php if ((!isset($ticket_data->customer_type) || $ticket_data->customer_type == "") && $userid == (isset($ticket_data->creator_id) ? $ticket_data->creator_id : $userid)){ ?>
        <button class="btn btn-primary" id='btn-submit' onclick="return validate_submit_ticket();">
            <span class='glyphicon glyphicon-floppy-saved'> </span> Submit
        </button>
     <?php } else if ($usergroupid==10) {?>
      <?php if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 7 && $ticket_data->ticket_status != 10 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12) {?>
                <button type="button" class="btn btn-success" id='btn-recover' onclick="return validate_ticket('<?php echo base_url() . "index.php/user/ctr_ticket/form_action/recover/" . $ticket_id; ?>');">
                    <span class='glyphicon glyphicon-ok'> </span> Recover
                </button>
            <?php } else if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 7 && $ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12) {?>
                <!-- M043 -->
                <button type="button" class="btn btn-success" id='btn-success'>
                    <span class='glyphicon glyphicon-ok'> </span> Success
                </button>
                <button type="button" class="btn btn-danger" id='btn-failed'>
                    <span class='glyphicon glyphicon-remove'> </span> Failed
                </button>
                <!-- M043 -->
            <?php }} ?>
    </div>
<br />
<br />
<br />
     <div class='col-md-4 alert alert-ticket'>
        <div class='pnl-ticket-heading'>Ticket Information</div>
        <div class="user-input-group">
            <label for="txt-ticket-number">Ticket Number</label>
            <!-- M051 -->
            <input type='text' class="form-control" name="txt-ticket-id" id="txt-ticket-id" value='<?php echo isset($ticket_data->code_ticket) ? $ticket_data->code_ticket : ""; ?>' readonly="readonly" disabled="disabled" />        
            <!-- M015 -->
        </div>  
        <div class="user-input-group">
            <label for="txt-ticket-number">Interaction</label>
            <input type='text' class="form-control" name="txt-interaction-id" id="txt-interaction-id" value='<?php echo isset($ticket_data->code_interaction) ? $ticket_data->code_interaction : ""; ?>' disabled="disabled" />        
        </div>  
        <div class="user-input-group">
            <label for="txt-ticket-number">Interaction Status</label>
            <input type='text' class="form-control" name="txt-interaction-status" id="txt-interaction-status" value='<?php echo isset($ticket_data->interaction_status) ? $ticket_data->interaction_status : ""; ?>' disabled="disabled" />        
        </div>      
        <div class="user-input-group">
            <label for="txt-ticket-number">Activity Code</label>
            <input type='hidden' name="hdn-activity-id" value='<?php echo isset($ticket_data->activity_id) ? $ticket_data->activity_id : ""; ?>' />
            <input type='text' class="form-control" name="txt-ticket-activity-code" id="txt-ticket-activity-code" value='<?php echo isset($ticket_data->activity_code) ? $ticket_data->activity_code : ""; ?>' disabled="disabled" />    
             <?php if ((!isset($ticket_data->customer_name) || $ticket_data->customer_name == "") && $userid == (isset($ticket_data->creator_id) ? $ticket_data->creator_id : $userid)){ ?>
            <a class="btn btn-xs btn-primary" href='#modal-activity' data-toggle='modal'><div class="glyphicon glyphicon-search"></div></a>
            <?php } ?>
        </div>  
        <?php
        // get activity level
        if (isset($ticket_data->activity_id)){
            $actdata = get_activity_data($ticket_data->activity_id);
            $actlevel = $actdata['level'];
            $act[$actlevel]['id'] = $actdata['id'];
            $act[$actlevel]['code'] = $actdata['code'];
            $act[$actlevel]['description'] = $actdata['description'];
            while ($actlevel > 1){
                $parent = get_activity_parent($act[$actlevel]['id']);
                $actlevel -= 1;
                $act[$actlevel]['description'] = $parent['description'];
                $act[$actlevel]['code'] = $parent['code'];
                $act[$actlevel]['id'] = $parent['id'];              
            }
        }
        
        ?>
        <div class="user-input-group">
            <label for="txt-ticket-number">Issue Type</label>
            <input type='text' class="form-control" name="txt-issue-type" id="txt-issue-type" value='<?php echo (isset($act[1])) ? $act[1]['description'] : ""; ?>' disabled="disabled" title='<?php echo (isset($act[1])) ? $act[1]['description'] : ""; ?>' />        
        </div>  
        <div class="user-input-group">
            <label for="txt-ticket-number">Issue Group</label>
            <input type='text' class="form-control" name="txt-issue-group" id="txt-issue-group" value='<?php echo (isset($act[2])) ? $act[2]['description'] : ""; ?>' disabled="disabled" title='<?php echo (isset($act[2])) ? $act[2]['description'] : ""; ?>' />        
        </div>  
        <div class="user-input-group">
            <label for="txt-ticket-number">Issue Sub Group</label>
            <input type='text' class="form-control" name="txt-issue-subgroup" id="txt-issue-subgroup" value='<?php echo (isset($act[3])) ? $act[3]['description'] : ""; ?>' disabled="disabled" title='<?php echo (isset($act[3])) ? $act[3]['description'] : ""; ?>' />        
        </div>  
        <!-- M046 -->
        <div class="user-input-group">
            <label for="txt-ticket-number">Issue Description</label>
            <input type='text' class="form-control" name="txt-issue-description" id="txt-issue-desription" value='<?php echo (isset($act[4])) ? $act[4]['description'] : ""; ?>' disabled="disabled" title='<?php echo (isset($act[4])) ? $act[4]['description'] : ""; ?>' /><button type="button" onclick="getIssueDescriptionTooltip(<?php echo (isset($act[4])) ? $act[4]['id'] : "0"; ?>);" class="btn btn-primary btn-xs" id="btnIssueDescriptionTooltip"><span class='glyphicon glyphicon-question-sign' id="issueDescriptionTooltip" data-toggle="tooltip" data-original-title="" style="cursor:pointer"></span></button>        
        </div>  
        <!-- M046 -->
    </div>
    
    <div class='col-md-4 alert alert-ticket'>
        <div class='pnl-ticket-heading'>Customer Information</div>
            <div class="user-input-group2">
                <label for="txt-ticket-number">Customer Name</label>
                <input type='text' class="form-control" name="txt-customer-name" id="txt-customer-name" value='<?php echo isset($ticket_data->customer_name) ? $ticket_data->customer_name : ""; ?>' required />        
            </div>
            <div class="user-input-group2">
                <label for="txt-ticket-number">ID Pesanan</label>
                <input type='text' class="form-control" name="txt-id-pesanan" id="txt-id-pesanan" value='<?php echo isset($ticket_data->id_pesanan) ? $ticket_data->id_pesanan : ""; ?>' required />        
            </div>
            <!--M001-->
            <div class="user-input-group2">
                <label for="txt-ticket-number">ID Customer on NAV</label>
                <input type='text' class="form-control" name="txt-customer-type" id="txt-customer-type" value='<?php echo isset($ticket_data->customer_type) ? $ticket_data->customer_type : ""; ?>' required />        
            </div>
            <div class="user-input-group2">
                <label for="txt-ticket-number">Contact Person Name</label>
                <input type='text' class="form-control" name="txt-customer-priority" id="txt-customer-priority" value='<?php echo isset($ticket_data->customer_priority) ? $ticket_data->customer_priority : ""; ?>' required />        
                <div class="ticket-condition">
                    <p>(Corporate customer only)</p>
                </div>
            </div>
            <!--M001-->
            <div class="user-input-group2">
                <label for="txt-ticket-number">Problem Event Date</label>
                <input type='text' class="form-control" name="txt-customer-event-datetime" id="txt-customer-event-datetime" value='<?php echo isset($ticket_data->customer_event_datetime) ? date("m/d/Y", strtotime($ticket_data->customer_event_datetime)) : ""; ?>' required/>   
            </div>
            <div class="user-input-group2">
                    <label for="txt-ticket-number">Phone Number</label>
                    <input type='text' class="form-control" name="txt-customer-phone" id="txt-customer-phone" value='<?php echo isset($ticket_data->customer_phone) ? $ticket_data->customer_phone : ""; ?>' required/>        
                </div>
                <div class="user-input-group2">
                    <label for="txt-ticket-number">Alternate Number</label>
                    <input type='text' class="form-control" name="txt-customer-alt-number" id="txt-customer-alt-number" value='<?php echo isset($ticket_data->customer_alt_number) ? $ticket_data->customer_alt_number : ""; ?>' required/>        
                </div>
                <div class="user-input-group2">
                    <label for="txt-ticket-number">Email Address</label>
                    <input type='text' class="form-control" name="txt-customer-email" id="txt-customer-email" value='<?php echo isset($ticket_data->customer_email) ? $ticket_data->customer_email : ""; ?>' required/>        
                </div>
          </div>
      
      
      <div class='col-md-4 alert alert-ticket'>
        <div class="pnl-ticket-heading">Ticket Status and Ownership</div>
                <div class='user-input-group'>
                    <label for='cmb-ticket-status'>Status</label>
                    <select name="cmb-ticket-status" id='cmb-ticket-status' class='form-control' disabled="disabled">
                    <?php foreach($ticket_status as $record){
                            echo "<option value='" . $record->ticket_status_id . "' " 
                                . (($ticket_data->ticket_status == $record->ticket_status_id) ? "selected" : "") . ">" 
                                . $record->ticket_status_name 
                                . "</option>";
                    }?>
                    </select>
                </div>
                <div class='user-input-group'>
                    <label for='cmb-ticket-substatus'>Sub Status</label>
                    <select name="cmb-ticket-substatus" id='cmb-ticket-substatus' class='form-control'>
                    <?php foreach($ticket_substatus as $record){
                            echo "<option value='" . $record->ticket_substatus_id . "' " 
                                . (($ticket_data->ticket_substatus == $record->ticket_substatus_id) ? "selected" : "") . ">" 
                                . $record->ticket_substatus_name 
                                . "</option>";
                    }?>
                    </select>
                </div>
                 <div class='user-input-group'>
                    <label for='cmb-owner-group'>Owner Group</label>
                    <select name="cmb-owner-group" id='cmb-owner-group' class='form-control'>
                    <?php foreach($user_group as $record){
                            echo "<option value='" . $record->id . "' " 
                                . (($ticket_data->owner_group_id == $record->id) ? "selected" : "") . ">" 
                                . $record->group_name
                                . "</option>";
                    }?>
                    </select>
                </div>
                <div class="user-input-group">
                    <label for="txt-ticket-owner">Owner</label>
                    <input type='text' class="form-control" name="txt-ticket-owner" id="txt-ticket-owner" value='<?php echo isset($ticket_data->owner_name) ? $ticket_data->owner_name : ""; ?>' disabled="disabled" />  
                </div>
               <div class='user-input-group'>
                    <label for='cmb-ticket-priority'>Ticket Priority</label>
                    <select name="cmb-ticket-priority" id='cmb-ticket-priority' class='form-control'>
                    <?php 
                        $has_selected = false;
                        foreach($ticket_priority as $record){
                            $selected = "";
                            
                            if (!$has_selected){
                                if ($ticket_data->ticket_priority == $record->ticket_priority_id)
                                    $selected = "selected";
                                else if ($record->ticket_priority_default == "1")
                                    $selected = "selected";
                                else $selected = "";
                                if ($selected == "selected") $has_selected = true;
                            }
                            
                            echo "<option value='" . $record->ticket_priority_id . "' " 
                                . $selected . ">" 
                                . $record->ticket_priority_name 
                                . "</option>";
                    }?>
                    </select>
                </div>
                <div class="user-input-group">
                    <label for="txt-ticket-number">Created By</label>
                    <input type='text' class="form-control" name="txt-ticket-creator" id="txt-ticket-creator" disabled="disabled" value='<?php echo isset($ticket_data->creator_name) ? $ticket_data->creator_name : ""; ?>' />        
                </div>
                <div class="user-input-group">
                <?php $creatordate = date("m/d/Y H:i:s", isset($ticket_data->creator_datetime) ? strtotime($ticket_data->creator_datetime) : time() + 7 * 60 * 60); ?>
                    <label for="txt-ticket-number">Created Date</label>
                    <input type='text' class="form-control" name="txt-ticket-creatordate" id="txt-ticket-creatordate" value='<?php echo $creatordate; ?>' disabled="disabled" title="<?php echo $creatordate; ?>" />        
                </div>
                <div class="user-input-group">
                    <label for="txt-ticket-owner">Hour</label>
                    <input type='text' class="form-control" name="txt-sla" id="txt-sla" value='<?php echo isset($ticket_data->sla) ? $ticket_data->sla : ""; ?>' disabled="disabled" />  
                </div>
                <div class="user-input-group">
                    <label for="txt-ticket-number">Due Date</label>
                    <input type='text' class="form-control" name="txt-ticket-duedate" id="txt-ticket-duedate" value='<?php echo isset($ticket_data->due_datetime) ? date("m/d/Y H:i:s", strtotime($ticket_data->due_datetime)) : ""; ?>' disabled="disabled" />        
                </div>
                <!-- M026 -->
                <div class="user-input-group">
                    <label for="txt-ticket-number">Handled Date</label>
                    <input type='text' class="form-control" name="txt-ticket-logdate" id="txt-ticket-logdate" value='<?php echo isset($ticket_data->log_datetime) ? date("m/d/Y H:i:s", strtotime($ticket_data->log_datetime)) : ""; ?>' disabled="disabled" />        
                </div>
                <div class="user-input-group">
                    <label for="txt-ticket-number">Closed Date</label>
                    <input type='text' class="form-control" name="txt-ticket-closeddate" id="txt-ticket-closeddate" value='<?php echo isset($ticket_data->closed_datetime) ? date("m/d/Y H:i:s", strtotime($ticket_data->closed_datetime)) : ""; ?>' disabled="disabled" />        
                </div>
                <div class="user-input-group">
                    <label for="txt-ticket-number">Solved Date</label>
                    <input type='text' class="form-control" name="txt-ticket-solveddate" id="txt-ticket-solveddate" value='<?php echo isset($ticket_data->solved_datetime) ? date("m/d/Y H:i:s", strtotime($ticket_data->solved_datetime)) : ""; ?>' disabled="disabled" />        
                </div>
                <div class="user-input-group">
                    <label for="txt-ticket-number">Recovering Date</label>
                    <input type='text' class="form-control" name="txt-ticket-recoveringdate" id="txt-ticket-recoveringdate" value='<?php echo isset($ticket_data->recovering_datetime) ? date("m/d/Y H:i:s", strtotime($ticket_data->recovering_datetime)) : ""; ?>' disabled="disabled" />        
                </div>
                <div class="user-input-group">
                    <label for="txt-ticket-number">Recovered Date</label>
                    <input type='text' class="form-control" name="txt-ticket-recoveringdate" id="txt-ticket-recoveringdate" value='<?php echo isset($ticket_data->recovered_datetime) ? date("m/d/Y H:i:s", strtotime($ticket_data->recovered_datetime)) : ""; ?>' disabled="disabled" />        
                </div>
                <!-- M026 -->
            </div>
       
    <div class="alert" style="clear:both; border: 0px none">
        <div class='pnl-ticket-heading'>Ticket Details</div>
        <div>
             <?php
                if (count($ticket_details) > 0)
                    $ticket_arr = $ticket_details;
                else $ticket_arr = $ticket_fields;
                
                $numFields = count($ticket_arr);
                $numPerCols = ceil($numFields / 2);
                
             ?>
             <div class='col-md-6'>
            <?php for ($idx = 0;  $idx < $numFields; $idx++){ ?>
                <!-- move to column 2 or not -->
                <?php if ($idx == $numPerCols){ ?>
                    </div>
                    <div class='col-md-6'>
                <?php } ?>
                
                <!-- create input -->
                <?php $record = $ticket_arr[$idx]; ?>
                 <div class="user-input-group">
                    <label><?php echo $record->field_name; ?>
                        <span class="required_fields <?php echo ($record->field_mandatory == 1) ? "" : "hidden"; ?>"> 
                            * 
                        </span>
                    </label>
                    <input type='text' class="form-control" 
                        name="txt-field[<?php echo $record->field_id; ?>]" 
                        id="txt-field-<?php echo $record->field_id; ?>" 
                        value='<?php echo isset($record->ticket_detail_content) 
                            ? $record->ticket_detail_content
                            : ""; ?>'
                        <?php echo ($record->field_mandatory == 1) ? "required" : ""; ?> 
                        />        
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
    <div class="alert" style="border: 0px none; clear:both;">
      <div class='pnl-ticket-heading'>Description</div>
      <div class='hidden' id="hdn-ticket-description"><?php 
                        echo isset($ticket_data->detail_info) 
                            ? $ticket_data->detail_info
                            : ""; ?></div>
      <textarea placeholder="Detail Information" name="txt-ticket-description" id="txt-ticket-description"></textarea>
    </div>

<!-- M043 -->
<?php if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 13){ ?>
    <div class="alert" style="border: 0px none; clear:both;">
      <div class='pnl-ticket-heading'>Resolution Notes</div>
      <div class='hidden' id="hdn-ticket-note"><?php 
                        echo isset($ticket_data->resolution_note) 
                            ? $ticket_data->resolution_note
                            : ""; ?></div>
      <textarea placeholder="Detail Note" name="txt-ticket-note" id="txt-ticket-note"><?php echo $ticket_data->resolution_note ?></textarea>
    </div>
<?php } ?>

<?php if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 7 && $ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 13){ ?>
    <div class="alert" style="border: 0px none; clear:both;">
      <div class='pnl-ticket-heading'>Recovery Notes</div>
      <div class='hidden' id="hdn-ticket-recovery-note"><?php 
                        echo isset($ticket_data->recovery_note) 
                            ? $ticket_data->recovery_note
                            : ""; ?></div>
      <textarea placeholder="Detail Note" name="txt-ticket-recovery-note" id="txt-ticket-recovery-note"><?php echo $ticket_data->recovery_note ?></textarea>
    </div>
<?php } ?>
<!-- M043 -->
    
</div>
</form>
<!-- MD03 -->
<!-- <div class='user-page-content'> -->
<div class="alert" style="border: 0px none; clear:both;">
<form method="post" action="<?php echo base_url(); ?>index.php/user/ctr_ticket/notes_action/add">
    <div class='pnl-ticket-heading'>Information</div>
    <textarea name="notes" id="notes"></textarea>
    <br />   
    <input type='hidden' name="ticket_id" id="ticket_id" value="<?php echo $ticket_id; ?>" />
    <input type='hidden' name="creator_id" id="creator_id" value="<?php echo $this->session->userdata('session_user_id'); ?>" />
    <button type='submit' class="btn btn-primary pull-right" name="btn-ticket-notes-add" id="btn-ticket-notes-add" onclick="saveTicketNotes()">Submit Information</button>
</form>
<br />
<br />
<?php foreach ($ticket_notes as $record){ ?>
    <div class='ticket_note'>
        <div class='ticket_note_body'><?php echo $record->notes; ?></div>
        <div class="ticket_note_footer">
            <div class='ticket_note_author'>By <?php echo $record->author; ?></div>
            <div class='ticket_note_datetime'>on <?php echo $record->note_datetime; ?></div>
        </div>
    </div>
<?php }?>
</div>
        </div>
        </div>
    </div>
<?php if ($level != 2){ ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#two">Activity Plan</a>
            </h4>
        </div>
        <div id="two" class="panel-collapse collapse">
        <div class="panel-body">
    <form method="post" action="<?php echo base_url(); ?>index.php/user/ctr_ticket/apply_plan">
    <!--M004-->
        <?php if ($ticket_template_id == "" && $ticket_status == 7 && $level == 3 || $level == 1){ ?>
    <!--M004-->
        <button class="btn btn-success pull-right" id='btn-use-template' onclick="return validateTicketActivities();">
            <div class="glyphicon glyphicon-ok"> </div> Use Template
        </button>
        <?php }?>
        <br /><br />
        <div class='user-input-group'>
            <label for='cmb-ticket-template'>Ticket Template</label>
        <!--M004-->
            <select name="cmb-ticket-template" id='cmb-ticket-template' class='form-control' <?php if ($ticket_template_id != "" || $level == 2) echo "disabled"; ?>>
        <!--M004-->
                <option value='' disabled="disabled" selected="selected">- Select -</option>
            <?php foreach($ticket_template as $record){
                    echo "<option value='" . $record->ticket_template_id . "' " 
                        . (($ticket_template_id == $record->ticket_template_id) ? "selected" : "") . ">" 
                        . $record->ticket_template_name 
                        . "</option>";
            }?>
            </select>
        </div>
        <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>" id="ticket_id" />
       
        
    </form>
    <br />
    <table class='table bordered' id='tbl-ticket-activities'>
        <tr>
            <th>Activity Plan</th>
            <th>Action</th>
            <th>Function</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>Due Date</th>
            <th>Solved Date</th>
            <th>ByPass</th>
            <th>New Interaction</th>
        </tr>
       <tbody id="tbl-ticket-activities-body">
       <?php
       if (isset($ticket_activities)){
            foreach ($ticket_activities as $ticket_activity){
                // MD03
                $interaction = "<a class='btn btn-success' href='" . base_url() . "index.php/user/ctr_interaction/new_ticket_interaction?ticket_id=".$ticket_id."&customer_name=".$ticket_activity->customer_name."&customer_phone=".$ticket_activity->customer_phone."&customer_email=".$ticket_activity->customer_email."&id_pesanan=".$ticket_activity->id_pesanan."'>Interaction</a>";
                // MD03
                    if ($ticket_activity->start_datetime == ""){
                        $bypass = "";
                    //M004
                        if ($level == 1 || $level == 3 && $userid == (isset($owner_id) ? $owner_id : "")){
                    //M004
                            $start = "<a class='btn btn-success' href='" . base_url() . "index.php/user/ctr_ticket/activities_action/start/" . $ticket_activity->ticket_activity_id . "/" . $ticket_id . "'>Start</a>";
                            $bypass = "<a class='btn btn-primary' href='" . base_url() . "index.php/user/ctr_ticket/activities_action/bypass/" . $ticket_activity->ticket_activity_id . "/" . $ticket_id . "'>Bypass</a>";
                            }
                        else $start = "";
                        $end = "";
                        $due = "";
                    }
                    else if ($ticket_activity->start_datetime != ""){
                        $start = date("m/d/Y H:i:s", strtotime($ticket_activity->start_datetime));
                        $due = date("m/d/Y H:i:s", strtotime($ticket_activity->start_datetime) + $ticket_activity->sla * 60 * 60);
                        // $bypass = "";
                        if ($ticket_activity->closed_datetime == ""){
                        //M004
                            if ($level == 1 || $level == 3 && $userid == (isset($owner_id) ? $owner_id : "")){
                        //M004
                                $end = "<a class='btn btn-warning' href='" . base_url() . "index.php/user/ctr_ticket/activities_action/close/" . $ticket_activity->ticket_activity_id . "/" . $ticket_id . "'>Solve</a>";
                            }
                            else $end = "";
                        }
                        else $end = date("m/d/Y H:i:s", strtotime($ticket_activity->closed_datetime));
                        if ($start == $end){
                            $bypass = $start;
                        } else{
                        $bypass = "-";
                        }
                    }
                    else if ($ticket_activity->start_datetime != "" || $ticket_activity->closed_datetime != ""){
                        $start = date("m/d/Y H:i:s", strtotime($ticket_activity->start_datetime));
                        $due = date("m/d/Y H:i:s", strtotime($ticket_activity->due_datetime));
                        $end = date("m/d/Y H:i:s", strtotime($ticket_activity->end_datetime));
                    }
                    
                echo "<tr>" 
                    . "<td>Activity Plan " . $ticket_activity->plan_order . "</td>"
                    . "<td>" . $ticket_activity->action_name . "</td>"
                    . "<td>" . $ticket_activity->function_name . "</td>"
                    . "<td>" . $ticket_activity->status_name . "</td>"
                    . "<td>" . $start . "</td>"
                    . "<td>" . $due . "</td>"
                    . "<td>" . $end . "</td>"
                    . "<td>" . $bypass . "</td>"
                    // MD03
                    . "<td>" . $interaction . "</td>"
                    // MD03
                    . "</tr>";
                    // echo "<form id='form_note' method='post' action=" . base_url() ."'index.php/user/ctr_ticket/activities_action/close/'" . $ticket_activity->ticket_activity_id . "/" . $ticket_id . ">";
                        // if ($ticket_activity->start_datetime != ""){
                        //  if ($ticket_activity->closed_datetime == ""){
                        //      echo "<div class='alert' style='border: 0px none; clear:both;'>
                        //      <tr><th colspan='8'><textarea placeholder='Detail Note' name='txt-ticket-note' id='txt-ticket-note' required></textarea></th></tr>
                        //      </div>";
                        //  }
                        // }
                    // echo "</form>";
            }
       }
       ?>
       </tbody>
    </table>
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#three">Interaction</a>
            </h4>
        </div>
        <div id="three" class="panel-collapse collapse">
        <div class="panel-body">
            <table class='table' id='tbl-interaction-activities'>
        <tr>
            <th>Interaction ID</th>
            <th>Created Datetime</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Customer Phone</th>
            <th>Interaction Creator Name</th>
            <th>Interaction Type</th>
            <th>Status</th>            
            <th>View Detail</th>
        </tr>
            <?php foreach($ticket_interactions as $record){ ?>
        <tr>            
                <td><?php echo $record->interaction_id; ?></td>
                <td><?php echo $record->creator_datetime; ?></td>
                <td><?php echo $record->customer_name; ?></td>
                <td><?php echo $record->customer_email; ?></td>
                <td><?php echo $record->customer_phone; ?></td>
                <td><?php echo $record->creator_name; ?></td>
                <td><?php echo $record->interaction_type_name; ?></td>
                <td><?php echo $record->status_name; ?></td>
                <td><a href="<?php echo base_url(); ?>index.php/user/ctr_interaction/form/<?php echo $record->interaction_id; ?>" class="btn btn-primary btn-xs"><div class="glyphicon glyphicon-search"> </div></a></td>
        </tr>
            <?php }?>
    </table>
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#four">Attachments</a>
            </h4>
        </div>
        <div id="four" class="panel-collapse collapse">
        <div class="panel-body">
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
        </div>
    </div>
    <?php } ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#five">Related</a>
            </h4>
        </div>
        <div id="five" class="panel-collapse collapse">
        <div class="panel-body">
        <div id="announcement">
            <div id='hdn_activity_id' class="hidden"><?php echo $activity_id; ?></div>
    <div id='hdn_ticket_template_id' class="hidden">0</div>
    <div id='hdn_interaction_id' class="hidden">0</div>        
    <div id='hdn_ticket_id' class="hidden"><?php echo $ticket_id; ?></div>     
    <div id='hdn_customer_name' class="hidden"><?php echo $customer_name; ?></div>     
    <div id='hdn_customer_phone' class="hidden"><?php echo $customer_phone; ?></div>     
    <div id='hdn_customer_email' class="hidden"><?php echo $customer_email; ?></div>        
    <table id='tbl-ticket-related' class="display table table-bordered table-hover">
      <thead>
        <tr>
            <th>No</th>
            <th>Created Datetime</th>
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Customer Phone</th>
            <th>Ticket Creator Name</th>
            <th>Activity Code</th>            
            <th>Issue Description</th>
            <th>Priority</th>
            <th>Status</th>
            <th>Ticket Owner Group</th>
            <th>Ticket Owner Name</th>
            <th>View Detail</th>
        </tr>
       </thead>
       <tbody>
       </tbody>
    </table>
        </div>
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#six">Audit Trial</a>
            </h4>
        </div>
        <div id="six" class="panel-collapse collapse">
        <div class="panel-body">
            <div id='hdn_ticket_id' class="hidden"><?php echo $ticket_id; ?></div>        
    <table id='tbl-audit-trail' class="display table table-bordered table-hover">
      <thead>
        <tr><th>Log No</th>
            <th>Datetime</th>
            <th>User Name</th>
            <th>Description</th>
        </tr>
       </thead>
       <tbody>
       </tbody>
    </table>
        </div>
        </div>
    </div>
    </div>
<!-- </div> -->
</div>
</div>
<!-- <script src="js/jquery-1.10.1.min.js"></script>
<script src="js/bootstrap.min.js"></script> -->

<!-- MD03 -->
<?php $this->load->view("user/user_modal_activity", array("action" => base_url() . "index.php/user/ctr_ticket/form_action/changeActivityCode/" . $ticket_id, "activity_type" => $activity_type)); ?>            

<div id="data">
    <script type="text/javascript">
    window.setInterval(function(){
        loadXMLDoc()
    }, 3000);
    </script>
</div>
</body>           
<script type='text/javascript'>
$("#navbarItemTicket").attr('class', 'active');
$("#txt-customer-event-datetime").datepicker({
	dateFormat: "dd M yy"
});
<?php /* if ((isset($ticket_data->customer_name) && $ticket_data->customer_name != "") 
		|| ($level == 3)
		|| ($userid != (isset($ticket_data->creator_id) ? $ticket_data->creator_id : $userid))){ */ ?>
<?php if ((isset($ticket_data->customer_type) && $ticket_data->customer_type != "") || $userid != (isset($ticket_data->creator_id) ? $ticket_data->creator_id : $userid)){ ?>
    $("#form_ticket").find("#cmb-ticket-priority,#cmb-ticket-substatus, #txt-ticket-description").attr('disabled', 'disabled');
    <?php if (isset($ticket_data->ticket_status) && $ticket_data->ticket_status != 7){ ?>
        $("#cmb-owner-group, #txt-ticket-note").attr('disabled', 'disabled');
    <?php } if (isset($ticket_data->ticket_status) && $ticket_data->ticket_status != 10) {?>
        $("#txt-ticket-recovery-note").attr('disabled', 'disabled');
    <?php } ?>
<?php } ?>
if (document.getElementById('btn-change-owner')){
	document.getElementById('btn-change-owner').onclick = function(){
		if (document.getElementById('cmb-owner-group').value.toString() == "<?php echo ($ticket_data->owner_group_id == NULL) ? "" : $ticket_data->owner_group_id; ?>")
			alert("Please choose different value in owner group field");
		else window.location.href = '<?php echo base_url() . "index.php/user/ctr_ticket/form_action/change_owner/" . $ticket_id; ?>' + '?owner_group_id=' + document.getElementById('cmb-owner-group').value;
}
}
function validate_submit_ticket(){
	if ($("#txt-ticket-activity-code").val() == ""){
		alert("Silakan pilih activity code");
		return false;
	}
	return true;
}
function validate_ticket(href){
	var solved = <?php echo $ticket_activity_closed; ?>;
	var templateSelected = true;
	<?php if ($ticket_data->ticket_template_id != NULL && $ticket_data->ticket_template_id != ""){ ?>
		templateSelected = true;
	<?php }  else { ?>
		templateSelected = false;
	<?php } ?>
	if (templateSelected == false){
		alert("Mohon pilih template terlebih dahulu.");	
	}
	else if (solved == 0) {
		alert("Mohon selesaikan semua activity terlebih dahulu.");	
	}
	else {
		window.location.href = href;
	}
}
// M043
<?php if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 13){ ?>
CKEDITOR.replace('txt-ticket-note', 
    {
    toolbarGroups: [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },                                                                   
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    ]
    }
);
CKEDITOR.instances['txt-ticket-note'].setData(document.getElementById('hdn-ticket-note').innerHTML);
<?php } ?>

<?php if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 7 && $ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 13){ ?>
CKEDITOR.replace('txt-ticket-recovery-note', 
    {
    toolbarGroups: [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },                                                                   
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    ]
    }
);
CKEDITOR.instances['txt-ticket-recovery-note'].setData(document.getElementById('hdn-ticket-recovery-note').innerHTML);
<?php } ?>

CKEDITOR.replace('txt-ticket-description', 
	{
	toolbarGroups: [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },																	
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	]
	}
);
CKEDITOR.instances['txt-ticket-description'].setData(document.getElementById('hdn-ticket-description').innerHTML);
function getIssueDescriptionTooltip(activity_id){
	$.ajax({
		type: 'POST',
		url: '<?php echo base_url(); ?>index.php/user/ctr_interaction/ajax_activity',
		data: { 'type': 'definition', 'value': activity_id }
	}).done(function(message){
		var result = message;
		$("#btnIssueDescriptionTooltip .popover-content").html(result);
		$("#btnIssueDescriptionTooltip").popover({ html: true, title: 'Definition', content: result, placement: "right" });
	}).fail(function(){
	});
	}

$(document).ready(function() {
    $("#btn-solve").bind('click', function(){
        txt = CKEDITOR.instances['txt-ticket-note'].getData();
        var solved = <?php echo $ticket_activity_closed; ?>;
        var templateSelected = true;
        <?php if ($ticket_data->ticket_template_id != NULL && $ticket_data->ticket_template_id != ""){ ?>
            templateSelected = true;
        <?php }  else { ?>
            templateSelected = false;
        <?php } ?>
        if (templateSelected == false){
            alert("Mohon pilih template terlebih dahulu."); 
        }
        else if (solved == 0) {
            alert("Mohon selesaikan semua activity terlebih dahulu.");  
        }
        else if (txt.replace(/<[^>]*>|\s/g, '').length >= 160){
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url() . "index.php/user/ctr_ticket/form_action/solve/" . $ticket_id; ?>',
                data: "resolution_note=" + escape(txt)
            }).done(function(message){
                alert("Change saved");          
                // window.location.href = '<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>';
                $(".first:first").after('<tr><td>'+success+'</td></tr>');
            }).fail(function(){
                alert("Sorry, an error occcured. Please try again.");
            });
        }
        else alert("Resolution Notes harus lebih dari 160 karakter");
    });
}); 

$(document).ready(function() {
    $("#btn-close").bind('click', function(){
        txt = CKEDITOR.instances['txt-ticket-note'].getData();
        var solved = <?php echo $ticket_activity_closed; ?>;
        var templateSelected = true;
        <?php if ($ticket_data->ticket_template_id != NULL && $ticket_data->ticket_template_id != ""){ ?>
            templateSelected = true;
        <?php }  else { ?>
            templateSelected = false;
        <?php } ?>
        if (templateSelected == false){
            alert("Mohon pilih template terlebih dahulu."); 
        }
        else if (solved == 0) {
            alert("Mohon selesaikan semua activity terlebih dahulu.");  
        }
        else if (txt.replace(/<[^>]*>|\s/g, '').length >= 160){
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url() . "index.php/user/ctr_ticket/form_action/close/" . $ticket_id; ?>',
                data: "resolution_note=" + escape(txt)
            }).done(function(message){
                alert("Change saved");          
                window.location.href = '<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>';
            }).fail(function(){
                alert("Sorry, an error occcured. Please try again.");
            });
        }
        else alert("Resolution Notes harus lebih dari 160 karakter");
    });
}); 

$(document).ready(function() {
    $("#btn-success").bind('click', function(){
        txt = CKEDITOR.instances['txt-ticket-recovery-note'].getData();
        if (txt.replace(/<[^>]*>|\s/g, '').length >= 160){
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url() . "index.php/user/ctr_ticket/form_action/success/" . $ticket_id; ?>',
                data: "recovery_note=" + escape(txt)
            }).done(function(message){
                alert("Change saved");          
                window.location.href = '<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>';
            }).fail(function(){
                alert("Sorry, an error occcured. Please try again.");
            });
        }
        else alert("Resolution Notes harus lebih dari 160 karakter");
    });
});

$(document).ready(function() {
    $("#btn-failed").bind('click', function(){
        txt = CKEDITOR.instances['txt-ticket-recovery-note'].getData();
        if (txt.replace(/<[^>]*>|\s/g, '').length >= 160){
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url() . "index.php/user/ctr_ticket/form_action/failed/" . $ticket_id; ?>',
                data: "recovery_note=" + escape(txt)
            }).done(function(message){
                alert("Change saved");          
                window.location.href = '<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>';
            }).fail(function(){
                alert("Sorry, an error occcured. Please try again.");
            });
        }
        else alert("Resolution Notes harus lebih dari 160 karakter");
    });
});

$("#navbarItemTicket").attr('class', 'active');
CKEDITOR.replace('notes', 
    {
    toolbarGroups: [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },                                                                   
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    ]
    }
);
// M043
$("#cmb-ticket-template").bind("change", function(){
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>index.php/user/ctr_ticket/ajax_activity_plans/' + $(this).val()
    }).done(function(message){
        $("#tbl-ticket-activities-body").html(message);
    }).fail(function(){
    
    });
});
function validateTicketActivities(){
    if (document.getElementById('cmb-ticket-template').selectedIndex == 0){
        alert("Please select a template first.");
        return false;
        }
    else return confirm("Are you sure to use this template?");
}
function loadXMLDoc(){
    var xmlhttp;
    if (window.XMLHttpRequest){
        // code for IE+7, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp == 200){
            document.getElementById("data").innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET", "config/database.php", true);
    xmlhttp.send();
}

// function Ajax(){
// var xmlhttp;
// if (window.XMLHttpRequest)
//   {// code for IE7+, Firefox, Chrome, Opera, Safari
//   xmlhttp=new XMLHttpRequest();
//   }
// else
//   {// code for IE6, IE5
//   xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
//   }
// xmlhttp.onreadystatechange=function()
//   {
//   if (xmlhttp.readyState==4 && xmlhttp.status==200)
//     {
//     document.getElementById("announcement").innerHTML=xmlhttp.responseText;
//     $(document).ready(function()
//         {
//           $.ajax({
//             type: "GET",
//             url: '<?php echo base_url() . "index.php/user/ctr_ticket/related/" . $ticket_id; ?>',
//             dataType: "html",
//             success: function (html) {
//               // xml contains the returned xml from the backend

//               $("announcement").append($(html).find("html-to-insert").eq(0));
//               eval($(html).find("script").text());
//             }
//           });
//         });
//     }
// }
// xmlhttp.open("get",'<?php echo base_url() . "index.php/user/ctr_ticket/related/" . $ticket_id; ?>');
// xmlhttp.send();
// }

</script>