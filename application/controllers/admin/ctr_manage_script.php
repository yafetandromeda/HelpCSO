<!-- M006 - YA - Export script to excel -->
<!-- M010 - YA - Import script -->
<?php
class Ctr_manage_script extends CI_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('admin/mdl_manage_script');
		$this->load->library('cso_template', array("templateFile" => "admin/admin_template"));
        $this->load->helper('url');	
        $this->load->helper('form');
        $this->load->helper('file');
        $this->load->helper('inflector');
        $this->load->library('form_validation');
	}
	
	function index() {
		$data['list_script']=$this->mdl_manage_script->get_all_script();
		$data['script_id'] =$this->mdl_manage_script->get_last_scriptid()->row('total_script') + 1;
		$data['pil_category1'] =$this->mdl_manage_script->get_pil_category_1();
		$this->cso_template->view('admin/manage_script',$data);
	}
//script
	function add_new_script() {
			$user_id = $this->session->userdata('session_user_id');
			$script_id =$_POST['text-scriptID'];
			$question=$_POST['text-question'];
			$answer=$_POST['text-answer'];
			$tag=$_POST['text-tag'];
			$category_id=$_POST['category'];
			$visibility = $_POST['visibility'];
			$tracking_category = $_POST['tracking_category'];
			$this->mdl_manage_script->add_script($script_id,$question,$answer,$tag,$category_id,$user_id,$visibility,$tracking_category);
	}
	
	function manage_script_report() {
		redirect('admin/ctr_manage_reported_script');
	}
	
// M006
	function script_toexcel()
	{
		$data['hasil'] =  $this->mdl_manage_script->get_all_script_toexcel();
		$this->load->view('admin/view_script_toexcel', $data);
	}
// M006
// M010
	function do_upload()
	{
		$config['upload_path'] = './temp_upload';
		$config['allowed_types'] = 'xls';
		$this->load->library('upload', $config);

		if ( !$this->upload->do_upload())
		{
			$data = array('error' => $this->upload->display_errors());
			
		}
		else
		{
            $data = array('error' => false);
			$upload_data = $this->upload->data();
            $this->load->library('excel_reader');
			$this->excel_reader->setOutputEncoding('CP1251');
			$file =  $upload_data['full_path'];
			$this->excel_reader->read($file);
			error_reporting(E_ALL ^ E_NOTICE);
			// Sheet 1
			$data = $this->excel_reader->sheets[0] ;
                        $dataexcel = Array();

			for ($i = 3; $i <= $data['numRows']; $i++) {
				$dataexcel[0]['script_id'] = $this->mdl_manage_script->get_last_id();
				$user_id = $this->session->userdata('session_user_id');
				//import activity
                $dataexcel[0]['activity_code'] = $data['cells'][$i][1];
				$dataexcel[0]['question'] = $data['cells'][$i][3];
                $dataexcel[0]['answer'] = $data['cells'][$i][7];
                if (strlen($dataexcel[0]['activity_code'])!=10){
				}else{
		            $check = $this->mdl_manage_script->cek_import($dataexcel);
					if (count($check) > 0)
				    {
						$this->mdl_manage_script->import_script_edit($dataexcel, $user_id);
				    }else{
				    	$check2 = $this->mdl_manage_script->cek_act_code($dataexcel);
				    	if (count($check2) > 0){
							$this->mdl_manage_script->import_script($dataexcel, $user_id);
						}
					}
				}
			}

            delete_files($upload_data['file_path']);
            $this->load->model('admin/mdl_manage_script');
            $check = $this->mdl_manage_script->cek_import($dataexcel);
            $data['helpcso_script'] = $this->mdl_manage_script->get_all_script($script_id);
            $data['helpcso_script_temp'] = $this->mdl_manage_script->get_all_script($script_id);
		}
		redirect('admin/ctr_manage_script');
		$data['list_script']=$this->mdl_manage_script->get_all_script();
		$data['script_id'] =$this->mdl_manage_script->get_last_scriptid()->row('total_script') + 1;
		$data['pil_category1'] =$this->mdl_manage_script->get_pil_category_1();
		// $this->cso_template->view('admin/manage_script',$data);
	}
// M010
	function search_script()
	{	
		$startdate = $_GET['startDate'];
		$enddate = $_GET['endDate'];
		$text_search_script=$_GET['text_search_script'];
		if ($startdate == '0') {
			$data['list_script'] = $this->mdl_manage_script->search_script(1,1,$text_search_script); 
			$data['script_id'] =$this->mdl_manage_script->get_last_scriptid()->row('total_script') + 1;
			$data['pil_category'] =$this->mdl_manage_script->get_pil_category();
			$data['pil_category1'] =$this->mdl_manage_script->get_pil_category_1();
			$this->cso_template->view('admin/manage_script',$data);
		}
		else {
			if ($text_search_script == ''){
			$data['list_script'] = $this->mdl_manage_script->search_script_bydate(1,1,$startdate,$enddate,$text_search_script); 
			$data['script_id'] =$this->mdl_manage_script->get_last_scriptid()->row('total_script') + 1;
			$data['pil_category'] =$this->mdl_manage_script->get_pil_category();
			$data['pil_category1'] =$this->mdl_manage_script->get_pil_category_1();
			$this->cso_template->view('admin/manage_script',$data);
			}
		else {
			$data['list_script'] = $this->mdl_manage_script->search_script_bydate(1,2,$startdate,$enddate,$text_search_script); 
			$data['script_id'] =$this->mdl_manage_script->get_last_scriptid()->row('total_script') + 1;
			$data['pil_category'] =$this->mdl_manage_script->get_pil_category();
			$data['pil_category1'] =$this->mdl_manage_script->get_pil_category_1();
			$this->cso_template->view('admin/manage_script',$data);
			}
	  }
	}
	  
	  function search_script_suggestion()
	{
		$text_search_suggestion=$_GET['text_search_suggestion'];
		$data_script_suggestion = $this->mdl_manage_script->search_script(2,1,$text_search_suggestion); 
		$jumlah_data_suggestion = $this->mdl_manage_script->search_script(2,1,$text_search_suggestion); 
		if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
			echo " ";
		}
		else {	
			$i = 0;
		 foreach ($data_script_suggestion as $p):
			 $i++;
			 echo "<li id='li".$i."' onClick='chosenText".$i."()'><div style='cursor:pointer;'>" .$p->question. "</div></li>";
		  endforeach;
		 }
	  }
	  
	   function save_edited_script()
	{
		$script_id = $_POST['script_id_edit'];
		$question = $_POST['text-question_edit'];
		$answer = $_POST['text-answer_edit'];
		$category_id = $_POST['category_edit'];
		$tag = $_POST['text-tag_edit'];
		$visibility = $_POST['visibility_edit'];
		$tracking_category = $_POST['tracking_category'];
		$this->mdl_manage_script->save_edited_script($script_id,$question,$answer,$category_id,$tag,$visibility,$tracking_category);
	} 
	
	function add_script_request(){
		$this->mdl_script_request->add();
	}


//script category		
	function manage_script_categories(){
		$data['list_categories'] = $this->mdl_manage_script->category_get_all();
		$data['category_id'] =$this->mdl_manage_script->category_last_id() + 1;
		$data['pil_levelcategory'] =$this->mdl_manage_script->pil_level_script_category();
		$this->cso_template->view("admin/manage_script_categories", $data, "admin/admin_template");
	}
	

	function parent_category(){
		$level = $_GET['level'];
		$code_id = $_GET['code_id'];
		if ($level == 2){
			$par_category = $this->mdl_manage_script->par_category_get(1,$code_id);
		}
		else if ($level == 3){
			$par_category = $this->mdl_manage_script->par_category_get(2,$code_id);	
		}
		
		if ($level == 1 or $level == ''){
			echo " ";
			echo "<input type='hidden' id='par_category' value=''/>";
		}
		else {
			echo "<label for='par_category_edit' class='cso-form-label'>Category Parent</label>";
			echo "<select id='par_category' name='par_category'>";
			echo "<option value=''>--choose--</option>";
			foreach ($par_category as $p):
					echo "<option value='".$p->code_id."'>".$p->category."</option>";
			endforeach;
			echo "</select>";
		}
	}
	
	function parent_category_edit(){
		$level = $_GET['level'];
		$code_id = $_GET['code_id'];	
		$flag = $_GET['flag'];
		
		if ($flag == 0) {
			echo " ";
			echo "<input type='hidden' id='par_category_edit' value=''/>";
		}
		else {
			if ($level == 2){
				$par_category = $this->mdl_manage_script->par_category_get(1,$code_id);

			}
			else if ($level == 3){
				$par_category = $this->mdl_manage_script->par_category_get(2,$code_id);
			}
			
			if ($level == 1 or $level == ''){
				echo " ";
				echo "<input type='hidden' id='par_category_edit' value=''/>";
			}
			else {
				echo "<label for='par_category_edit' class='cso-form-label'>Category Parent</label>";
				echo "<select id='par_category_edit' name='par_category_edit'>";
				echo "<option value=''>--choose--</option>";
				foreach ($par_category as $p):
								echo "<option value='".$p->code_id."'>".$p->category."</option>";
				endforeach;
				echo "</select>";	
			}
		}
	}
	
	function script_subcategory(){
		$flag = $_GET['flag'];
		$par_category = $_GET['par_category'];
		$level = $_GET['level'] + 1;
		$subcategory = $this->mdl_manage_script->get_pil_subcategory(1,$par_category);
		$level_subcategory = $this->mdl_manage_script->get_pil_subcategory(2,$par_category)->row('level');
		
		if ($level_subcategory <> NULL)
			echo "<input type='hidden' id='level_subcategory".$_GET['level']."' value='".$level."'/>";
		else echo "<input type='hidden' id='level_subcategory".$_GET['level']."' value='".$_GET['level']."'/>";
		
		if ($level_subcategory <> NULL and $par_category <> ''){
			if ($level == 2)  echo "<label for='par_category_edit' class='cso-form-label'>Issue Group</label>"; 
			else if ($level == 3)  echo "<label for='par_category_edit' class='cso-form-label'>Sub Issue Group</label>"; 
			else if ($level == 4)  echo "<label for='par_category_edit' class='cso-form-label'>Issue Description</label>"; 
		if ($flag==1)
			echo "<select id='category".$level_subcategory."' name='category".$level_subcategory."' onchange='show_subcategory(1,".$level_subcategory.")'>";
		else if ($flag==2)	
			echo "<select id='category".$level_subcategory."_edit' name='category".$level_subcategory."_edit' onchange='show_subcategory(2,".$level_subcategory.")'>";
		echo "<option value=''>--choose--</option>";
			foreach ($subcategory as $p):
					echo "<option value='".$p->code_id."'>".$p->category."</option>";
			endforeach;
		echo "</select>";
		echo "<input type='hidden' id='level_field_subcategory".$_GET['level']."' value='".$_GET['level']."'/>";
		}
		else { 
				echo " ";
				echo "<input type='hidden' id='level_field_subcategory".$_GET['level']."' value='".$_GET['level']."'/>";
		}
	}

	function script_subcategory_edit(){
		$tracking_category = explode(";",$_GET['tracking_category']);
		$flag_level = $_GET['flag_level'];
		if($tracking_category[$flag_level] <> ' '){
				$pil_subcategory = $this->mdl_manage_script->get_pil_subcategory(1,$tracking_category[$flag_level - 1]);
				$level_subcategory = $this->mdl_manage_script->get_pil_subcategory(2,$tracking_category[$flag_level - 1])->row('level');
					if ($level_subcategory == 2)  echo "<label for='par_category_edit' class='cso-form-label'>Issue Group</label>"; 
					else if ($level_subcategory == 3)  echo "<label for='par_category_edit' class='cso-form-label'>Sub Issue Group</label>"; 
					else if ($level_subcategory == 4)  echo "<label for='par_category_edit' class='cso-form-label'>Issue Description</label>"; 
			echo "<select id='category".$level_subcategory."_edit' name='category".$level_subcategory."_edit' onchange='show_subcategory(2,".$level_subcategory.")'>";
			echo "<option value=''>--choose--</option>";
				foreach ($pil_subcategory as $p):
						echo "<option value='".$p->code_id."'>".$p->category."</option>";
				endforeach;
			echo "</select>";		
		}
			else echo " "; 				
	}
	function add_script_category(){
		$this->mdl_manage_script->category_add(array(
			"level" => $_POST['level'],
			"parent_id" => $_POST['par_category'],
			"category" => $_POST['category'],
			"category_code" => $_POST['category_code']
		));
	}
	
	function check_child_category(){		
		$child_category_name = $this->mdl_manage_script->check_child_category(1,$_GET['code_id'])->row('category');
		$flag_check_child_category = $this->mdl_manage_script->check_child_category(2,$_GET['code_id']);
		if ($flag_check_child_category > 0){
			echo "<input type='hidden' id='child_category_name' value='".$child_category_name."'/>";
			echo "<input type='hidden' id='flag_check_child_category' value='".$flag_check_child_category."'/>"; 
		}
		else {
			echo "<input type='hidden' id='flag_check_child_category' value='".$flag_check_child_category."'/>"; 
		}
	}
	
	function check_tracking_category(){
		$script_name_tracking = $this->mdl_manage_script->check_tracking_category(1,$_GET['code_id'])->row('question');
		$flag_check_tracking_category = $this->mdl_manage_script->check_tracking_category(2,$_GET['code_id']);	
		if ($flag_check_tracking_category > 0) {
			echo "<input type='hidden' id='script_name_tracking' value='".$script_name_tracking."'/>";
			echo "<input type='hidden' id='flag_check_tracking_category' value='".$flag_check_tracking_category."'/>";
		}
		else{
			echo "<input type='hidden' id='flag_check_tracking_category' value='".$flag_check_tracking_category."'/>";
		}
	}

	function edit_script_category(){			
		$this->mdl_manage_script->category_update($_POST['code_id'], array(
			"level" => $_POST['level'],
			"parent_id" => $_POST['par_category'],
			"category" => $_POST['category'],
			"category_code" => $_POST['category_code']
		));
	}
	function delete_script_category(){
		$this->mdl_manage_script->category_delete($_POST['code_id']);
	}
	
	function search_script_categories(){
		$text_search = $_GET['text_search'];
		$data['list_categories'] = $this->mdl_manage_script->category_search(1,1,$text_search);
		$data['category_id'] =$this->mdl_manage_script->category_last_id() + 1;
		$data['pil_levelcategory'] =$this->mdl_manage_script->pil_level_script_category();
		$this->cso_template->view("admin/manage_script_categories", $data, "admin/admin_template");
	}
	
	 function search_script_category_suggestion()
	{
			$text_search_suggestion=$_GET['text_search_suggestion'];
			$data_category_suggestion = $this->mdl_manage_script->category_search(2,1,$text_search_suggestion);
			$jumlah_data_suggestion = $this->mdl_manage_script->category_search(2,2,$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 0;
			 foreach ($data_category_suggestion as $p):
				 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'><div style='cursor:pointer;'>" .$p->category. "</div></li>";
			  endforeach;
		}
	}
}
?>