<!-- M045 - YA - Ubah field -->
<!-- M046 - YA - Ubah tampilan baloon, hyperlink di showlist,  tambah status draft, tambah SLA -->
<div class='user-page-title'>
	<a href='<?php echo base_url() . "index.php/user/ctr_interaction/showlist/" . $user_id; ?>'>
    	<?php if ($user_id == 0){ ?>
        	All Interactions
        <?php }
			else {?>
           	My Active Interactions
        <?php } ?>
    </a>
    <span class='user-page-subtitle'><?php echo (isset($interaction_status) && $interaction_status != "") ? "- " . strtolower($interaction_status) : ""; ?></span>   
    <div class='hidden' id='tbl-interaction-userid'><?php echo $user_id; ?></div>
    <div class='hidden' id='tbl-interaction-status'><?php echo $interaction_status; ?></div>
    
    <?php
	if ($interaction_status == "OVER")
		$status = "SCHEDULED";
	?>
</div>
<div class='user-page-content'>
	<table id='tbl-interaction-list' class="display table table-bordered table-hover">
      <thead>
    	<tr>
        	<th>No</th>
            <th>Created DateTime</th>
            <!-- M046 -->
            <th>ID Pesanan</th>
            <!-- m046 -->
            <th>Customer Name</th>
            <th>Customer Email</th>
            <th>Customer Phone</th>
            <th>Interaction Creator Name</th>
            <th>Interaction Type</th>
            <!-- M046 -->
            <th>Status</th>
            <!-- M046 -->
        </tr>
       </thead>
       <tbody>
       </tbody>
       <tfoot>
            <td><input type='text' placeholder='Search No' id='txt-tbl-search-no' /></td>
            <td><input type='text' placeholder='Search Datetime' id='txt-tbl-search-datetime' /></td>
            <!-- M046 -->
            <td><input type='text' placeholder='Search ID' id='txt-tbl-id-pesanan' /></td>
            <!-- M046 -->
            <td><input type='text' placeholder='Search Customer' id='txt-tbl-search-customername' /></td>
            <td><input type='text' placeholder='Search Email' id='txt-tbl-search-customeremail' /></td>
            <td><input type='text' placeholder='Search Phone' id='txt-tbl-search-customerphone' /></td>
            <td><input type='text' placeholder='Search Creator' id='txt-tbl-search-creatorname' /></td>
            <td><input type='text' placeholder='Search Type' id='txt-tbl-search-type' /></td>
            <!-- M046 -->
            <td><input type='text' placeholder='Search Status' id='txt-tbl-search-status' value="<?php echo $interaction_status; ?>" /></td>
            <!-- M046 -->
            </tfoot>
    </table>
</div>
<script type="text/javascript">
$("#navbarItemInteraction").attr('class', 'active');
</script>
