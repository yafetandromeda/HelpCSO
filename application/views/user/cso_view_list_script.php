<?php
	$session_userid = $this->session->userdata('session_user_id');
	$session_username = $this->session->userdata('session_user_name');

	$userid = $this->session->userdata('session_user_id');
	$username = $this->session->userdata('session_user_name');
	$level = $this->session->userdata('session_level');
?>
<div class='user-page-title'>
	Script
</div>    
<?php $this->load->view("user/user_wording_greetings"); ?>    
<div class="well alert alert-block">
    <form id="form_search_script" name="form_search_script" 
        class="form-horizontal" method="get" 
        action="<?php echo base_url(); ?>index.php/user/ctr_view_list_script/search_script">
        <div id='div_category'>
            <label for="category1" id="category_label">Category</label>
            <select name="category1" id="category1" class="combobox_category" data-level='1'>
            </select>
        </div>
        <label for="text_search" id="keyword_label">Keyword</label>
        <input type="search" placeholder='' id="text_search" name="text_search" />
        <input type="hidden" name="category_id" id="category_id" value="" />
        <button type="submit" class="btn btn-primary" id="btn-search">Search</button>
    </form>
    <div id="cso_search_suggestion"></div>
    <div id='cso-content'>
        <div id="list_script">
        <form id="form1" name="form1" method="post" action="">
        <div id="search_list_user">
            <table id="tablecsodata" class="display table table-bordered">
                <thead>
                    <tr>
                        <th>Search Result	</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($list_script as $p):
                         echo "<tr>";
                         echo "<td><a href='".base_url()."index.php/user/ctr_view_list_script/view_script/".$p->script_id."'>".$p->question."</a></td>";
                         echo "</tr>";
                      endforeach;
                ?>
                </tbody>
            </table>
        </div>
        </form>						
        </div>
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
</script>
