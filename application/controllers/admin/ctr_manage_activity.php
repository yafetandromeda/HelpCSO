<!-- M007 - YA - Export activity to excel -->
<!-- M011 - YA - Import activity -->
<?php
class Ctr_manage_activity extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library("cso_template", array("templateFile" => "escalation/esc_template"));
		$this->load->model("admin/mdl_manage_activity");
        $this->load->helper('url');	
        $this->load->helper('form');
        $this->load->helper('file');
        $this->load->helper('inflector');
        $this->load->library('form_validation');
	}
	
	function index_activity(){
		$data['list_activity'] = $this->mdl_manage_activity->activity_get_all();
		$data['last_activity_id'] =$this->mdl_manage_activity->get_last_activity_id()+ 1;
		$data['pil_active'] =$this->mdl_manage_activity->get_pil_status_active();
		$this->cso_template->view("admin/manage_activity", $data, "admin/admin_template");
	}
// M007
	function activity_toexcel(){
			$data['hasil'] =  $this->mdl_manage_activity->activity_get_all_toexcel();
			$this->load->view('admin/view_activity_toexcel', $data);
		}
// M007
// M011
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
					$dataexcel[0]['activity_id'] = $this->mdl_manage_activity->get_last_id();
					$dataexcel[0]['activity_code'] = $data['cells'][$i][1];
	                // $dataexcel[0]['activity_parent'] = $data['cells'][$i][2];
	                $dataexcel[0]['parent_code'] = $data['cells'][$i][2];
	                $dataexcel[0]['activity_description'] = $data['cells'][$i][3];
					$dataexcel[0]['activity_definition'] = $data['cells'][$i][4];
	                $dataexcel[0]['activity_level'] = $data['cells'][$i][5];
	                $dataexcel[0]['status_active'] = $data['cells'][$i][6];
	                
	                $check = $this->mdl_manage_activity->cek_activity($dataexcel);
				    if (count($check) > 0)
				    {
						$this->mdl_manage_activity->import_activity_edit($dataexcel);
				    }else{
						$this->mdl_manage_activity->import_activity($dataexcel);
					}
				}
					$this->mdl_manage_activity->update_act_parent();
            
            delete_files($upload_data['file_path']);
            $this->load->model('admin/mdl_manage_activity');
            $check = $this->mdl_manage_activity->cek_activity($dataexcel);
            $data['helpcso_activity'] = $this->mdl_manage_activity->activity_get_all($activity_id);
		}
		redirect('admin/ctr_manage_activity/index_activity');
		// $data['list_activity'] = $this->mdl_manage_activity->activity_get_all();
		// $data['activity_id'] =$this->mdl_manage_activity->get_last_activity_id()+ 1;
		// $data['pil_active'] =$this->mdl_manage_activity->get_pil_status_active();
	}
// M011
	function add_activity(){
			$this->mdl_manage_activity->add_activity(array(
				"activity_code" => $_POST['activity_code'],
				"activity_parent" => $_POST['activity_parent'],
				"activity_description" => $_POST['activity_description'],
				"activity_definition" => $_POST['activity_definition'],
				"activity_level" => $_POST['activity_level'],
				"status_active" => $_POST['status_active']
			));
		}
	function edit_activity(){
		$this->mdl_manage_activity->edit_activity($_POST['activity_id'], array(
				"activity_code" => $_POST['activity_code'],
				"activity_parent" => $_POST['activity_parent'],
				"activity_description" =>  $_POST['activity_description'],
				"activity_definition" => $_POST['activity_definition'],
				"activity_level" => $_POST['activity_level'],
				"status_active" => $_POST['status_active']
		));
	}
	
	function check_child_activity(){		
			$child_activity_name = $this->mdl_manage_activity->check_child_activity(1,$_GET['activity_id'])->row('activity_description');
			$flag_check_child_activity = $this->mdl_manage_activity->check_child_activity(2,$_GET['activity_id']);
			if ($flag_check_child_activity <> NULL && $flag_check_child_activity > 0) {
				echo "<input type='hidden' id='child_activity_name' value='".$child_activity_name."'/>";
				echo "<input type='hidden' id='flag_check_child_activity' value='".$flag_check_child_activity."'/>"; 
			}
			else
				echo "<input type='hidden' id='flag_check_child_activity' value='0'/>"; 
		}
		
	function parent_activity(){
			$level = $_GET['activity_level'];
			$activity_id = $_GET['activity_id'];
			if ($level == 2){
				$par_activity = $this->mdl_manage_activity->par_activity_get(1,$activity_id);
			}
			else if ($level == 3){
				$par_activity = $this->mdl_manage_activity->par_activity_get(2,$activity_id);	
			}
			else if ($level == 4){
				$par_activity = $this->mdl_manage_activity->par_activity_get(3,$activity_id);	
			}
			
			if ($level == 1 or $level == ''){
				echo " ";
				echo "<input type='hidden' id='par_activity' value=''/>";
			}
			else {
				echo "<label for='par_activity' class='cso-form-label'>Activity Parent</label>";
				echo "<select id='par_activity' name='par_activity'>";
				echo "<option value='0'>--choose--</option>";
				foreach ($par_activity as $p):
						echo "<option value='".$p->activity_id."'>".$p->activity_description."</option>";
				endforeach;
				echo "</select>";
			}
		}
		
		function parent_activity_edit(){
			$level = $_GET['activity_level'];
			$activity_id = $_GET['activity_id'];	
			$flag = $_GET['flag'];	
			
			if ($flag == 2) {
				echo " ";
				echo "<input type='hidden' id='par_activity_edit' value='0'/>";
			}
			else{
				if ($level == 2){
					$par_activity = $this->mdl_manage_activity->par_activity_get(1,$activity_id);
				}
				else if ($level == 3){
					$par_activity = $this->mdl_manage_activity->par_activity_get(2,$activity_id);
				}
				else if ($level == 4){
					$par_activity = $this->mdl_manage_activity->par_activity_get(3,$activity_id);
				}
				
				if ($level == 1 or $level == ''){
					echo " ";
					echo "<input type='hidden' id='par_activity_edit' value='0'/>";
				}
				else {
					echo "<label for='par_activity_edit' class='cso-form-label'>Activity Parent</label>";
					echo "<select id='par_activity_edit' name='par_activity_edit'>";
					echo "<option value='0'>--choose--</option>";
					foreach ($par_activity as $p):
									echo "<option value='".$p->activity_id."'>".$p->activity_description."</option>";
					endforeach;
					echo "</select>";	
				}
			}
		}

	function search_activity(){
		$text_search = $_GET['text_search'];
		$data['list_activity'] = $this->mdl_manage_activity->activity_search(1,1,$text_search);
		$data['last_activity_id'] =$this->mdl_manage_activity->get_last_activity_id()+ 1;
		$data['pil_active'] =$this->mdl_manage_activity->get_pil_status_active();
		$this->cso_template->view("admin/manage_activity", $data, "admin/admin_template");
	}
	
	 function search_activity_suggestion()
	{
			$text_search_suggestion=$_GET['text_search_suggestion'];
			$data_category_suggestion = $this->mdl_manage_activity->activity_search(2,1,$text_search_suggestion);
			$jumlah_data_suggestion = $this->mdl_manage_activity->activity_search(2,2,$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 0;
			 foreach ($data_category_suggestion as $p):
				 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'><div style='cursor:pointer;'>" .$p->activity_description. "</div></li>";
			  endforeach;
		}
	}

//fields
	function manage_activity_field(){
		$activity_id = $_GET['activity_id'];
		$data['activity_id'] = $_GET['activity_id'];
		$data['activity_description'] = $_GET['activity_description'];
		$data['list_activity_field'] = $this->mdl_manage_activity->activity_field_get_all($activity_id);
		$data['last_activity_field_id'] =$this->mdl_manage_activity->get_activity_field_last_id() + 1;
		$data['pil_active'] =$this->mdl_manage_activity->get_pil_status_active();
		$this->cso_template->view("admin/manage_activity_field", $data, "admin/admin_template");
	}
		
	function add_activity_field(){
		$activity_id = $_POST['activity_id'];
		$this->mdl_manage_activity->add_activity_field(array(
			"field_name" => $_POST['field_name'],
			"field_mandatory" =>  $_POST['field_mandatory'],
			"status_active" =>  $_POST['status_active']
		),$activity_id);
	}
	function edit_activity_field(){
		$this->mdl_manage_activity->edit_activity_field($_POST['field_id'], array(
			"field_name" => $_POST['field_name'],
			"field_mandatory" =>  $_POST['field_mandatory'],
			"status_active" =>  $_POST['status_active']
		));
	}

	function search_activity_field(){
		$activity_id = $_GET['activity_id'];
		$text_search = $_GET['text_search'];
		$data['activity_id'] = $_GET['activity_id'];
		$data['activity_description'] = $_GET['activity_description'];
		$data['list_activity_field'] = $this->mdl_manage_activity->search_activity_field(1,1,$activity_id,$text_search);
		$data['last_activity_field_id'] =$this->mdl_manage_activity->get_activity_field_last_id() + 1;
		$data['pil_active'] =$this->mdl_manage_activity->get_pil_status_active();
		$this->cso_template->view("admin/manage_activity_field", $data, "admin/admin_template");
	}
	
	 function search_field_suggestion()
	{		
			$activity_id = $_GET['activity_id'];
			$text_search_suggestion = $_GET['text_search_suggestion'];
			$data_fields_suggestion = $this->mdl_manage_activity->search_activity_field(2,1,$activity_id,$text_search_suggestion);
			$jumlah_data_suggestion = $this->mdl_manage_activity->search_activity_field(2,2,$activity_id,$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 0;
			 foreach ($data_fields_suggestion as $p):
				 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'><div style='cursor:pointer;'>" .$p->field_name. "</div></li>";
			  endforeach;
		}
	}
}
?>