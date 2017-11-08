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
<!-- MD4 - YA - auto distribution ticket -->
<!-- M54 - YA - ubah aturan tampilan note -->
<!-- M55 - YA - penambahan SO number pada interaction dan ticket -->
<!-- M56 - YA - Validasi ticket -->
<!-- M57 - YA - Perubahan semua tanggal -->
<!-- M58 - YA - Fitur cancelled untuk ticket junk/salah -->
<?php
    $userid = $this->session->userdata('session_user_id');
    $username = $this->session->userdata('session_user_name');
    $level = $this->session->userdata('session_level');
    $usergroupid = $this->session->userdata('session_user_group_id');
    
?>
<div class='user-page-title'>
    <a href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Ticket</a>
    <div class='pull-right'>
      <div class='btn-group'>
        <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>'>Detail</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/activities/" . $ticket_id; ?>'>Activity Plan</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/interactions/" . $ticket_id; ?>'> Interactions</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/attachments/" . $ticket_id; ?>'>Attachments</a>
        <!-- <a class="btn btn-danger" href='<?php //echo base_url() . "index.php/user/ctr_ticket/notes/" . $ticket_id; ?>'>Notes</a> -->
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/related/" . $ticket_id; ?>'>Related</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_ticket/audit_trail/" . $ticket_id; ?>'>Audit Trail</a>
       </div>
    </div>
</div>
<form id="form_ticket" method="post" action="<?php echo base_url() . "index.php/user/ctr_ticket/form_action/save/" . $ticket_id; ?>">
<div class='user-page-content' id='pnl-ticket'>
    <div class='col-md-12 pull-right' align="right">
    <!-- M58 -->
    <?php if ((($userid == 1 || ($userid == 23 && $ticket_data->owner_group_id == 7)) && $ticket_data->ticket_status != 13 && $ticket_data->ticket_status != 14) || ($ticket_data->creator_id == $userid && $ticket_data->ticket_status == 13)) {?>
        <a class="btn btn-primary" id='btn-cancelled' href='<?php echo base_url() . "index.php/user/ctr_ticket/form_action/cancelled/" . $ticket_id; ?>'>
            <span class='glyphicon glyphicon-floppy-saved'> </span> Cancelled
        </a>
    <?php } ?>
    <!-- M58 -->
    <!--M004-->
      <?php if ($level == 1 || $level == 3 && isset($ticket_data->owner_group_id) && $ticket_data->owner_group_id == $usergroupid) { ?>
    <!--M004-->
            <?php if ($ticket_data->owner_name == $username && ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 10 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12 && $ticket_data->ticket_status != 14)){ ?>
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
        <?php } else if ($level == 3 && $ticket_data->ticket_status == 6 && $ticket_data->owner_group_id != 7 &&($ticket_data->owner_id == $userid || $ticket_data->owner_id == "")) {?>
        <a class="btn btn-success" id='btn-handle' href='<?php echo base_url() . "index.php/user/ctr_ticket/form_action/handle/" . $ticket_id; ?>'>
            <span class='glyphicon glyphicon-ok'> </span> Handle
        </a>
        <?php } ?>
      <?php } ?>
      <?php if (((!isset($ticket_data->customer_type) || $ticket_data->customer_type == "") && (isset($ticket_data->ticket_status) && $ticket_data->ticket_status != 14)) && $userid == (isset($ticket_data->creator_id) ? $ticket_data->creator_id : $userid)){ ?>
        <button class="btn btn-primary" id='btn-submit' onclick="return validate_submit_ticket();">
            <span class='glyphicon glyphicon-floppy-saved'> </span> Submit
        </button>
     <?php } else if ($usergroupid==10) {?>
      <?php if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 7 && $ticket_data->ticket_status != 10 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12 && $ticket_data->ticket_status != 14) {?>
                <button type="button" class="btn btn-success" id='btn-recover' onclick="return validate_ticket('<?php echo base_url() . "index.php/user/ctr_ticket/form_action/recover/" . $ticket_id; ?>');">
                    <span class='glyphicon glyphicon-ok'> </span> Recover
                </button>
            <?php } else if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 7 && $ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12 && $ticket_data->ticket_status != 14) {?>
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
            <!-- M55 -->
            <div class="user-input-group2">
                <label for="txt-ticket-number">SO Number</label>
                <input type='text' class="form-control" name="txt-so-number" id="txt-so-number" value='<?php echo isset($ticket_data->so_number) ? $ticket_data->so_number : ""; ?>' required />        
            </div>
            <!-- M55 -->
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
            <!-- M56 -->
            <div class="user-input-group2">
                <label for="txt-ticket-number">Phone Number</label>
                <input type='text' class="form-control" name="txt-customer-phone" id="txt-customer-phone" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='<?php echo isset($ticket_data->customer_phone) ? $ticket_data->customer_phone : ""; ?>' required/>        
            </div>
            <div class="user-input-group2">
                <label for="txt-ticket-number">Alternate Number</label>
                <input type='text' class="form-control" name="txt-customer-alt-number" id="txt-customer-alt-number" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value='<?php echo isset($ticket_data->customer_alt_number) ? $ticket_data->customer_alt_number : ""; ?>' required/>        
            </div>
            <!-- M56 -->
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
                <div class="user-input-group">
                    <label for="txt-ticket-number">Cancelled Date</label>
                    <input type='text' class="form-control" name="txt-ticket-cancelleddate" id="txt-ticket-cancelleddate" value='<?php echo isset($ticket_data->cancelled_datetime) ? date("m/d/Y H:i:s", strtotime($ticket_data->cancelled_datetime)) : ""; ?>' disabled="disabled" />        
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
        <!-- M54 -->
        <?php if ($ticket_data->ticket_status == 13) { ?>
        <!-- M54 -->
            <div class='hidden' id="hdn-ticket-description"><?php 
                echo isset($ticket_data->detail_info) 
                    ? $ticket_data->detail_info
                    : ""; ?>
            </div>
            <textarea placeholder="Detail Information" name="txt-ticket-description" id="txt-ticket-description"></textarea>
        <!-- M54 -->
        <?php } ?>
        <div class='ticket_note'>
            <div class='ticket_note_body'><?php echo $ticket_data->detail_info ?></div>
        </div>
        <!-- M54 -->
    </div>
<!-- M043 -->
    <div class="alert" style="border: 0px none; clear:both;">
        <div class='pnl-ticket-heading'>Resolution Notes</div>
        <!-- M54 -->
        <?php if ($ticket_data->owner_id == $userid && ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 10 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12 && $ticket_data->ticket_status != 13)){ ?>
        <!-- M54 -->
            <div class='hidden' id="hdn-ticket-note"><?php 
                echo isset($ticket_data->resolution_note) 
                    ? $ticket_data->resolution_note
                    : ""; ?>
            </div>
            <textarea placeholder="Detail Note" name="txt-ticket-note" id="txt-ticket-note"><?php echo $ticket_data->resolution_note ?></textarea>
        <!-- M54 -->
        <?php } ?>
        <div class='ticket_note'>
            <div class='ticket_note_body'><?php echo $ticket_data->resolution_note  ?></div>
        </div>
        <!-- M54 -->
    </div>

    <div class="alert" style="border: 0px none; clear:both;">
        <div class='pnl-ticket-heading'>Recovery Notes</div>
        <!-- M54 -->
        <?php if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 7 && $ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12 && $ticket_data->ticket_status != 13){ ?>
        <!-- M54 -->
            <div class='hidden' id="hdn-ticket-recovery-note"><?php 
                echo isset($ticket_data->recovery_note) 
                    ? $ticket_data->recovery_note
                    : ""; ?>
            </div>
            <textarea placeholder="Detail Note" name="txt-ticket-recovery-note" id="txt-ticket-recovery-note"><?php echo $ticket_data->recovery_note ?></textarea>
        <!-- M54 -->
        <?php } ?>
        <div class='ticket_note'>
            <div class='ticket_note_body'><?php echo $ticket_data->recovery_note  ?></div>
        </div>
        <!-- M54 -->
    </div>
<!-- M043 -->
    
</div>
</form>
<!-- MD03 -->
<!-- <div class='user-page-content'> -->
<div class="alert" style="border: 0px none; clear:both;">
    <form method="post" action="<?php echo base_url(); ?>index.php/user/ctr_ticket/notes_action/add">
        <div class='pnl-ticket-heading'>Information</div>&nbsp&nbsp
        <!-- M54 -->
        <input type="button" class="btn btn-primary pull-left" name="btn-add-information" id="btn-add-information" value="Add Information" 
        onclick="document.getElementById('notes').style.display='inline';document.getElementById('btn-ticket-notes-add').style.display='inline';document.getElementById('btn-add-information').style.display='none';"/>
        <textarea name="notes" id="notes" style="display:none" cols="155" rows="10"></textarea><br>
        <!-- M54 -->
        <br />   
        <input type='hidden' name="ticket_id" id="ticket_id" value="<?php echo $ticket_id; ?>" />
        <input type='hidden' name="creator_id" id="creator_id" value="<?php echo $this->session->userdata('session_user_id'); ?>" />
        <button type='submit' class="btn btn-primary pull-right" name="btn-ticket-notes-add" id="btn-ticket-notes-add" onclick="saveTicketNotes()" style="display:none">Submit</button>
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
<!-- MD03 -->
<?php $this->load->view("user/user_modal_activity", array("action" => base_url() . "index.php/user/ctr_ticket/form_action/changeActivityCode/" . $ticket_id, "activity_type" => $activity_type)); ?>            
            
<script type='text/javascript'>
$("#navbarItemTicket").attr('class', 'active');
$("#txt-customer-event-datetime").datepicker({
    dateFormat: "dd M yy"
});
<?php /* if ((isset($ticket_data->customer_name) && $ticket_data->customer_name != "") 
        || ($level == 3)
        || ($userid != (isset($ticket_data->creator_id) ? $ticket_data->creator_id : $userid))){ */ ?>
<?php if (((isset($ticket_data->customer_type) && $ticket_data->customer_type != "") || (isset($ticket_data->ticket_status) && $ticket_data->ticket_status == 14)) || $userid != (isset($ticket_data->creator_id) ? $ticket_data->creator_id : $userid)){ ?>
    $("#form_ticket").find("input,#cmb-ticket-priority,#cmb-ticket-substatus, #txt-ticket-description").attr('disabled', 'disabled');
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
    // M56
    if ($("#txt-customer-phone").val() == $("#txt-customer-alt-number").val()){
        alert("Alternate number tidak boleh sama dengan phone number");
        return false;
    }
    // M56
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
<?php if ($ticket_data->owner_id == $userid && ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 10 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12 && $ticket_data->ticket_status != 13)){ ?>
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

<?php if ($ticket_data->ticket_status != 6 && $ticket_data->ticket_status != 7 && $ticket_data->ticket_status != 8 && $ticket_data->ticket_status != 9 && $ticket_data->ticket_status != 11 && $ticket_data->ticket_status != 12 && $ticket_data->ticket_status != 13){ ?>
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

<?php if ($ticket_data->ticket_status == 13) { ?>
CKEDITOR.replace('txt-ticket-description', 
    {
    toolbarGroups: [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },                                                                   
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    ]
    }
);
CKEDITOR.instances['txt-ticket-description'].setData(document.getElementById('hdn-ticket-description').innerHTML);
<?php } ?>

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
                window.location.href = '<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $ticket_id; ?>';
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

// $("#navbarItemTicket").attr('class', 'active');
// CKEDITOR.replace('notes', 
//     {
//     toolbarGroups: [
//         { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },                                                                   
//         { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
//     ]
//     }
// );
// M043
</script>