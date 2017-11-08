<!-- M018 - YA - Menampilkan Solved dan Closed Datetime -->
<!-- M045 - YA - Ubah field -->
<!-- M046 - YA - Ubah tampilan baloon, hyperlink di showlist,  tambah status draft, tambah SLA -->
<!-- M65 - YA - Related pada ticket ini by automatic fungsi ini akan mencocokan 3 field pada ticket Customer Name & Customer Phone & Customer Email digantikan dengan kombinasi ID Pesanan*(Prioritas) & SO Number & Customer Phone & Customer Email -->

<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_ticket/showlist/" . $user_id; ?>'>
    	<?php if ($user_id == 0){ ?>
        	All Tickets
        <?php }
			else {?>
           	My Active Tickets
        <?php } ?>
    </a>
    <span class='user-page-subtitle'><?php echo (isset($ticket_status) && $ticket_status != "") ? "- " . strtolower($ticket_status) : ""; ?></span>    
    <div class='hidden' id='tbl-ticket-userid'><?php echo $user_id; ?></div>
    <div class='hidden' id='tbl-ticket-usertype'><?php echo $user_type; ?></div>
    <div class='hidden' id='tbl-ticket-status'><?php echo $ticket_status; ?></div>
    <div class='hidden' id='tbl-ticket-sla'><?php echo $sla; ?></div>
    <div class='hidden' id='tbl-ticket-priority'><?php echo $ticket_priority; ?></div>
</div>
<div class='user-page-content'>
	<table id='tbl-ticket-list' class="display table table-bordered table-hover">
      <thead>
    	<tr>
        	<th>No</th>
            <th>Created Datetime</th>
        <!-- M046 -->
            <th>ID Pesanan</th>
        <!-- M046 -->
        <!-- M65 -->
            <th>SO Number</th>
        <!-- M65 -->
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Customer Phone</th>
            <th>Activity Code</th>            
            <th>Issue Description</th>
            <th>Ticket Creator Name</th>
            <th>Ticket Owner Group</th>
            <th>Ticket Owner Name</th>
            <th>SLA</th>
            <th>Priority</th>
            <th>Status</th>
<!-- M018 -->
            <th>Solved Ticket</th>
            <th>Closed Ticket</th>
<!-- M018 -->
        </tr>
       </thead>
       <tbody>
       </tbody>
       <tfoot>
            <td><input type='text' placeholder='Search No' id='txt-tblticket-no' /></td>
            <td><input type='text' placeholder='Search Datetime' id='txt-tblticket-datetime' /></td>
        <!-- M046 -->
            <td><input type='text' placeholder='Search ID Pesanan' id='txt-tblticket-idpesanan' /></td>
            <td><input type='text' placeholder='Search SO Number' id='txt-tblticket-sonumber' /></td>
        <!-- M046 -->
            <td><input type='text' placeholder='Search Customer' id='txt-tblticket-customername' /></td>
            <td><input type='text' placeholder='Search Email' id='txt-tblticket-customeremail' /></td>
            <td><input type='text' placeholder='Search Phone' id='txt-tblticket-customerphone' /></td>
            <td><input type='text' placeholder='Search Activity' id='txt-tblticket-activity' /></td>
            <td><input type='text' placeholder='Search Issue' id='txt-tblticket-issue' /></td>
            <td><input type='text' placeholder='Search Creator' id='txt-tblticket-creatorname' /></td>
            <td><input type='text' placeholder='Search Group' id='txt-tblticket-ownergroup' value="<?php echo ($ticket_status == "GROUP") ? get_group_name($this->session->userdata('session_user_group_id')) : "";?>" /></td>
            <td><input type='text' placeholder='Search Owner' id='txt-tblticket-ownername' /></td>
            <td><input type='text' placeholder='Search SLA' id='txt-tblticket-sla' value="<?php echo ($sla != "GROUP") ? $sla : "OVER SLA"; ?>"/></td>
            <td><input type='text' placeholder='Search Priority' id='txt-tblticket-priority' value="<?php echo ($ticket_priority != "GROUP") ? $ticket_priority : "HIGH"; ?>" /></td>
            <td><input type='text' placeholder='Search Status' id='txt-tblticket-status' value="<?php echo ($ticket_status != "GROUP") ? $ticket_status : "OPEN"; ?>" /></td>
<!-- M018 -->
            <td><input type='text' placeholder='Search Datetime' id='txt-tblticket-datetime' /></td>
            <td><input type='text' placeholder='Search Datetime' id='txt-tblticket-datetime' /></td>
<!-- M018 -->
       </tfoot>
    </table>
    <script type="text/javascript">
	    $("#navbarItemTicket").attr('class', 'active');
    </script>
</div>

