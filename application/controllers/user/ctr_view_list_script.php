<!-- M015 - YA - Search Script -->
<?php
class Ctr_view_list_script extends CI_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->model('user/mdl_categories');
		$this->load->model('user/mdl_view_list_script');
		$this->load->model('user/mdl_activity');
		$this->load->model('user/mdl_script_request');
		$this->load->model('user/mdl_script_report');
		$this->load->library("cso_template", array("templateFile" => "user/user_template"));
	}
	
	function index() {
		$data['list_script']=$this->mdl_view_list_script->get_all_script();
		$this->load->view('user/view_list_script',$data);
	}
		
	function search_script()
	{
		$text_search_script=$_GET['text_search'];
		$category_id=$_GET['category_id'];
		$data['list_script'] = $this->mdl_view_list_script->search_script(3,$text_search_script,$category_id); 
		$this->cso_template->view('user/cso_view_list_script',$data);
	  }
	  
	  function search_script_suggestion()
	{
		$text_search_suggestion=$_GET['text_search_suggestion'];
		$category_id=$_GET['category_id'];
		$data_script_suggestion = $this->mdl_view_list_script->search_script(1,$text_search_suggestion,$category_id); 
		$jumlah_data_suggestion = $this->mdl_view_list_script->search_script(2,$text_search_suggestion,$category_id); 
		if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
			echo " ";
		}
		else {	
			$i = 1;
		 foreach ($data_script_suggestion as $p):
		 	 $i++;
			 echo "<div id='script_" . $p->script_id . "' class='suggestion_item'>" .$p->question. "</div>";
		  endforeach;
		}
	}

	// M015
	function view_script($script_id){
		$data['script_result'] = $this->mdl_view_list_script->view_script($script_id);
		$this->mdl_view_list_script->count_view($script_id);		
		$data['activity_type'] = $this->mdl_activity->get_by_parent();	
		$this->cso_template->view('user/cso_view_script', $data);
	}

	function view_script_by_activity_code($activity_code){
		$cek = mysql_num_rows(mysql_query('select activity_code from helpcso_script where activity_code="'.$activity_code.'"'));
		if($cek > 0){
		$data['script_result'] = $this->mdl_view_list_script->view_script_by_activity_code($activity_code);
		}else{
			$data['script_result'] = $this->mdl_view_list_script->view_no_script();
		}
		$this->mdl_view_list_script->count_view($activity_code);
		$data['top_scripts'] = $this->mdl_view_list_script->top_scripts(10);
		$data['pil_category'] = $this->mdl_categories->get_by_level(1);
		$data['activity_type'] = $this->mdl_activity->get_by_parent();
		$this->cso_template->view('user/cso_view_script', $data);
	}
	// M015

	function request_script(){
		$request_content = $_POST['txt-request'];
		$user_id = $_POST['user_id'];
		$this->mdl_script_request->add($user_id, $request_content);
	}
	function report_script(){
		$report_content = $_POST['txt-report'];
		$user_id = $_POST['user_id'];
		$script_id = $_POST['script_id'];
		$this->mdl_script_report->add($user_id, $report_content, $script_id);
	}
}
?>