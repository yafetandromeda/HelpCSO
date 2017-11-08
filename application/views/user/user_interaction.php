<!-- M045 - YA - Ubah Field -->
<!-- M046 - YA - Ubah tampilan baloon, hyperlink di showlist,  tambah status draft, tambah SLA -->
<!-- MD01 - YA - Advance user profile, untuk mengatur field yang muncul hanya di user tertentu saja, atau hal – hal yang bisa dijadikan default terhadap user tersebut. -->
<!-- MD02 - YA - Interaction status menggunakan model button, bukan combo box, tidak perlu tombol save &  System Autosave supaya jika pindah tab informasi sebelumnya tidak hilang -->
<!-- MD03 - YA - add information pada ticket, Tambah button interaksi di activity plan untuk create new interaksi -->
<?php
    $userid = $this->session->userdata('session_user_id');
    $username = $this->session->userdata('session_user_name');
    $level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
    <a href='<?php echo base_url() . "index.php/user/ctr_interaction/form/" . $interaction_id; ?>'>Interaction</a>
    <div class='pull-right'>
      <div class='btn-group'>
        <a class="btn btn-primary" href='<?php echo base_url() . "index.php/user/ctr_interaction/form/" . $interaction_id; ?>'>Detail</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_interaction/activities/" . $interaction_id; ?>'>Activities</a>
        <a class="btn btn-danger" href='<?php echo base_url() . "index.php/user/ctr_interaction/attachments/" . $interaction_id; ?>'>Attachments</a>
       </div>
    </div>
</div>
<form method="post" action="<?php echo base_url(); ?>index.php/user/ctr_interaction/form_action" name="form_interaction" id="form_interaction">
    <!-- MD03 -->
    <?php if (isset($get_ticket_id[0]->ticket_id) != ""){ ?>
       <a href='<?php echo base_url() . "index.php/user/ctr_ticket/form/" . $get_ticket_id[0]->ticket_id; ?>' class="btn btn-danger">Back To Form Ticket</a>
       <?php }?>
    <!-- MD03 -->
<div class='user-page-content row'>
 <?php if (!isset($interaction_status_id) || (isset($interaction_status_id) && $interaction_status_id != 2 && $interaction_status_id != 5 && $userid == (isset($creator_id) ? $creator_id : $userid))){ // if not closed and not cancelled ?>
    <div class='col-md-12' align="right">
    <?php if ($interaction_type_id != 1){
        if ($interaction_status_id != 3){?>
        <button class="btn btn-warning" type="submit" name="btn-scheduled" id="btn-scheduled" value="btn-scheduled" onclick="return validate_scheduled()"><span class='glyphicon glyphicon-floppy-saved'> </span> Scheduled</button>
        <?php }} ?>
        <button class="btn btn-success" type="submit" name="btn-inprogress" id="btn-inprogress" value="btn-inprogress"><span class='glyphicon glyphicon-floppy-saved'> </span> In Progress</button>
        <button class="btn btn-primary" type="submit" name="btn-canceled" id="btn-canceled" value="btn-canceled"><span class='glyphicon glyphicon-floppy-saved'> </span> Canceled</button>
        <button class="btn btn-danger" type="submit" name="btn-closed" id="btn-closed" value="btn-closed"><span class='glyphicon glyphicon-floppy-saved'> </span> Closed</button>
    </div>
    <?php } ?><br>
    <div class='col-md-6'>
        <div class='user-input-group'>
            <label for='txt-interaction-id'>Interaction Number</label>
            <input type='hidden' class="form-control" name="txt-interaction-id" id="txt-interaction-id" value="<?php echo $interaction_id; ?>" readonly="readonly" />
            <input type='text' class="form-control" name="txt-interaction-code" id="txt-interaction-code" value="<?php echo $code_interaction; ?>" readonly="readonly" />
        </div>
        <div class='user-input-group'>
            <label for='cmb-interaction-type'>Interaction Type</label>
            <select name="cmb-interaction-type" id='cmb-interaction-type' class='form-control' onchange="autosave_interaction_type()">
            <?php foreach($interaction_type as $record){
                    echo "<option value='" . $record->interaction_type_id . "' " 
                        . (($interaction_type_id == $record->interaction_type_id) ? "selected" : "") . ">" 
                        . $record->interaction_type_name 
                        . "</option>";
            }?>
            </select>
        </div>
        <div class='user-input-group'>
            <label for='txt-customer-name'>Customer Name</label>
            <input type='text' class="form-control" name="txt-customer-name" id="txt-customer-name" value="<?php echo isset($customer_name) ? $customer_name : ""; ?>" onchange="autosave_interaction();"/>
        </div>
        <div class='user-input-group'>
            <label for='txt-phone-no'>Phone Number</label>
            <input type='text' class="form-control" name="txt-phone-no" id="txt-phone-no" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="<?php echo isset($customer_phone) ? $customer_phone : ""; ?>" onchange="autosave_interaction()"/>
        </div>
        <div class='user-input-group'>
            <label for='txt-email'>Email</label>
            <input type='email' class="form-control" name="txt-email" id="txt-email" value="<?php echo isset($customer_email) ? $customer_email : ""; ?>" onchange="autosave_interaction()" />
        </div>
        <!-- MD01 -->
        <?php if ( $userid == 1 || $user_group_field[0]->queue_number == 1 ){ ?>
        <div class='user-input-group'>
            <label for='txt-queue-no'>Queue Number</label>
            <input type='text' class="form-control" name="txt-queue-no" id="txt-queue-no" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="<?php echo isset($queue_number) ? $queue_number : ""; ?>" onchange="autosave_interaction()"/>
        </div>
        <?php } ?>
        <!-- MD01 -->
        <!-- M046 -->
        <div class='user-input-group'>
            <label for='txt-id-pesanan'>ID Pesanan</label>
            <input type='text' class="form-control" name="txt-id-pesanan" id="txt-id-pesanan" value="<?php echo isset($id_pesanan) ? $id_pesanan : ""; ?>" onchange="autosave_interaction()"/>
        </div>
        <!-- M55 -->
        <div class='user-input-group'>
            <label for='txt-so-number'>SO Number</label>
            <input type='text' class="form-control" name="txt-so-number" id="txt-so-number" value="<?php echo isset($so_number) ? $so_number : ""; ?>" onchange="autosave_interaction()"/>
        </div>
        <!-- M55 -->
        <!-- M046 -->
        <div class='user-input-group'>
            <label for='cmb-interaction-status'>Interaction Status</label>
            <select name="cmb-interaction-status" id="cmb-interaction-status" class='form-control' disabled="disabled" />
            <?php foreach($interaction_status as $record){
                echo "<option value='" . $record->interaction_status_id . "' " 
                    . (isset($interaction_status_id) && ($interaction_status_id == $record->interaction_status_id) ? "selected" : "") . ">" 
                    . $record->interaction_status_name 
                    . "</option>";
            }?>
            </select>
        </div>
    </div>
    <div class='col-md-6'>
        <div class='user-input-group'>
            <label for='txt-creator-id'>Created By</label>
            <input type='hidden' name="txt-creator-id" id="txt-creator-id" value="<?php echo isset($creator_id) ? $creator_id : $userid; ?>" />
            <input type='text' class="form-control" name="txt-creator-name" id="txt-creator-name" value="<?php echo isset($creator_name) ? $creator_name : $username; ?>" readonly="readonly" />
        </div>
        <div class='user-input-group'>
            <label for='txt-create-datetime'>Created Date</label>
            <input type='text' class="form-control" name="txt-create-datetime" id="txt-create-datetime" value="<?php echo isset($creator_datetime) ? date("m/d/Y H:i:s", strtotime($creator_datetime)) : ""; ?>" readonly="readonly" />
        </div>
        <div class='user-input-group'>
            <label for='cmb-interaction-priority'>Priority</label>
            <select name="cmb-interaction-priority" id='cmb-interaction-priority' class='form-control' onchange='autosave_interaction()'>
            <?php foreach($interaction_priority as $record){
                    echo "<option value='" . $record->interaction_priority_id . "' " 
                        . ($interaction_priority_id == $record->interaction_priority_id ? "selected" : 
                            (($record->interaction_priority_default == '1') ? "selected" : "")) . ">" 
                        . $record->interaction_priority_name 
                        . "</option>";
            }?>
            </select>
        </div>
        <!-- MD01 -->
        <?php if ($userid == 1 || $user_group_field[0]->planned_start_date == 1){ ?>
        <div class='user-input-group'>
            <label for='txt-create-datetime'>Planned Start Date</label>
                <input data-format="DD MMM YYYY HH:mm:ss" type="text" class="form-control" name="txt-planned-datetime" id="txt-planned-datetime" value="<?php echo isset($planned_start_datetime) ? date("m/d/Y H:i:s", strtotime($planned_start_datetime)) : ""; ?>" onchange="autosave_planned();" <?php if (isset($interaction_status_id) && $interaction_status_id != 1 && $interaction_status_id != 3) echo "disabled"; ?> />
        </div>
        <?php } ?>
        <!-- MD01 -->
         <input type="hidden" class="form-control" name="planned-datetime" id="planned-datetime" value="<?php echo isset($planned_start_datetime) ? date("Y-M-d H:i:s", strtotime($planned_start_datetime)) : ""; ?>" />
        <div class='user-input-group'>
            <label for='txt-create-datetime'>Actual Start Date</label>
            <input type='text' class="form-control" name="txt-start-datetime" id="txt-start-datetime" value="<?php echo isset($actual_start_datetime) ? date("m/d/Y H:i:s", strtotime($actual_start_datetime)) : ""; ?>" disabled="disabled" />
        </div>
        <div class='user-input-group'>
            <label for='txt-create-datetime'>Actual Cancel Date</label>
            <input type='text' class="form-control" name="txt-cancel-datetime" id="txt-cancel-datetime" value="<?php echo isset($actual_cancel_datetime) ? date("m/d/Y H:i:s", strtotime($actual_cancel_datetime)) : ""; ?>" disabled="disabled" />
        </div>
        <div class='user-input-group'>
            <label for='txt-create-datetime'>Actual End Date</label>
            <input type='text' class="form-control" name="txt-end-datetime" id="txt-end-datetime" value="<?php echo isset($actual_end_datetime) ? date("m/d/Y H:i:s", strtotime($actual_end_datetime)) : ""; ?>" disabled="disabled" />
        </div>                        
    </div>
</div>    
    <div class='user-page-content row'>
        <br />
        <textarea placeholder="Description" name="txt-interaction-description" id="txt-interaction-description"></textarea>
        <div class="hidden" id="hdn-interaction-description">
        <?php echo (isset($interaction_description) ? $interaction_description : ""); ?>
        </div><br>
        <button class="btn btn-primary" type="submit" id="btn-save"><span class='glyphicon glyphicon-floppy-saved'> </span> Save Note</button>
    </div>
</form>
<script type='text/javascript'>
$("#navbarItemInteraction").attr('class', 'active');
CKEDITOR.replace('txt-interaction-description', 
    {
    toolbarGroups: [
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },                                                                   
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    ]
    }
);
CKEDITOR.instances['txt-interaction-description'].setData(document.getElementById('hdn-interaction-description').innerHTML);
<?php if ($interaction_status_id == 2 || $interaction_status_id == 5 || $userid != (isset($creator_id) ? $creator_id : $userid)){ // if not closed and not cancelled ?>
$("input, select, textarea").attr('disabled', 'disabled');
<?php  }?>
$('#txt-planned-datetime').datetimepicker();
function validate_interaction(){
    var cmbstatus = document.getElementById('cmb-interaction-status').value;
    var solved = <?php echo $interaction_activity_solved; ?>;
    if ((cmbstatus == 2 || cmbstatus == 5) && solved == 0){
        alert("Mohon solve semua activity untuk menutup interaksi ini");
        return false;
    }
    if (cmbstatus == 3 && $("#txt-planned-datetime").val() == ""){
        alert("Pilih tanggal yang di-schedule");
        return false;
    }
    var custname = document.getElementById('txt-customer-name').value;
    if (custname == ""){
        alert("Masukkan nama customer");
        return false;
    }
    
    var phone = document.getElementById('txt-phone-no').value;
    var email = document.getElementById('txt-email').value;
    var queue = document.getElementById('txt-queue-no').value;
    
    if ($("#txt-planned-datetime").val() != ""){
        var txt = new Date($('#txt-planned-datetime').data("DateTimePicker").getDate());
        document.getElementById('planned-datetime').value = txt.getFullYear() + "-" + (txt.getMonth() + 1) + "-" + txt.getDate() + " " + txt.getHours() + ":" + txt.getMinutes() + ":" + txt.getSeconds();
    }
    if (phone == "" && email == "" && queue == ""){
        alert("Masukkan nomor telepon / email / nomor antrian customer");
        return false;
    }
    return true;
}
// MD02
function validate_scheduled(){
    var planned_start_date = document.getElementById('txt-planned-datetime').value;
    if (planned_start_date == "") {
        alert("Pilih tanggal yang di-schedule");
        return false;
    };
}
function autosave_interaction_type(){
    var interaction_id = document.getElementById('txt-interaction-id').value;
    var interaction_type = document.getElementById('cmb-interaction-type').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>index.php/user/ctr_interaction/autosave_interaction_type',
        data: "interaction_id=" + interaction_id + "&interaction_type_id=" + interaction_type
    }).done(function(message){
        // alert("Activity has been edited successfully");
        location.href = "<?php echo base_url();?>index.php/user/ctr_interaction/form/" + interaction_id;
    })
}
function autosave_interaction(){
    var planned_start_date = document.getElementById('txt-planned-datetime').value;
    var interaction_id = document.getElementById('txt-interaction-id').value;
    var customer_name = document.getElementById('txt-customer-name').value;
    var customer_phone = document.getElementById('txt-phone-no').value;
    var customer_email = document.getElementById('txt-email').value;
    var queue_number = document.getElementById('txt-queue-no').value;
    var id_pesanan = document.getElementById('txt-id-pesanan').value;
    // M55
    var so_number = document.getElementById('txt-so-number').value;
    // M55
    var priority_id = document.getElementById('cmb-interaction-priority').value;
    var interaction_type = document.getElementById('cmb-interaction-type').value;
    var interaction_description = CKEDITOR.instances['txt-interaction-description'].getData();
    interaction_description = interaction_description.replace(/(?:&nbsp;|<br>)/g,'');
    // M55
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>index.php/user/ctr_interaction/autosave_interaction',
        data: "interaction_id=" + interaction_id + "&interaction_type_id=" + interaction_type + "&customer_name=" + customer_name + "&customer_phone=" + customer_phone + 
        "&customer_email=" + customer_email + "&queue_number=" + queue_number + "&id_pesanan=" + id_pesanan + "&so_number=" + so_number +
        "&priority_id=" + priority_id + "&planned_start_datetime=" + planned_start_date + "&interaction_description=" + interaction_description
    });
    // M55
    // .done(function(message){
    //     alert("Activity has been edited successfully");
    //     location.href = "<?php echo base_url();?>index.php/user/ctr_interaction/form/" + interaction_id;
    // }).fail(function(){
    //     alert("Sorry, an error occcured. Please try again.");
    //     location.href = "<?php echo base_url();?>index.php/user/ctr_interaction/form/" + interaction_id;
    // });
}
function autosave_planned(){
    var planned_start_date = document.getElementById('txt-planned-datetime').value;
    var interaction_id = document.getElementById('txt-interaction-id').value;
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>index.php/user/ctr_interaction/autosave_planned',
        data: "interaction_id=" + interaction_id + "&planned_start_datetime=" + planned_start_date
    });
    // .done(function(message){
    //     alert("Activity has been edited successfully");
    //     location.href = "<?php echo base_url();?>index.php/user/ctr_interaction/form/" + interaction_id;
    // }).fail(function(){
    //     alert("Sorry, an error occcured. Please try again.");
    //     location.href = "<?php echo base_url();?>index.php/user/ctr_interaction/form/" + interaction_id;
    // });
}
// function save_description(){
//     var interaction_description = document.getElementById('txt-interaction-description').value;
//     // var interaction_description = CKEDITOR.instances['txt-interaction-description'].getData();
//     // interaction_description = interaction_description.replace(/(?:&nbsp;|<br>)/g,'');
//     $.ajax({
//         type: 'POST',
//         url: '<?php echo base_url(); ?>index.php/user/ctr_interaction/autosave_interaction',
//         data: "interaction_id=" + interaction_id + "&interaction_description=" + interaction_description
//     })
//     .done(function(message){
//         alert("Activity has been edited successfully");
//         location.href = "<?php echo base_url();?>index.php/user/ctr_interaction/form/" + interaction_id;
//     }).fail(function(){
//         alert("Sorry, an error occcured. Please try again.");
//         location.href = "<?php echo base_url();?>index.php/user/ctr_interaction/form/" + interaction_id;
//     });
// }

// MD02
</script>
