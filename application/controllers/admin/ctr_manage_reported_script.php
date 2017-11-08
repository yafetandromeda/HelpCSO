<?php
class Ctr_manage_reported_script extends CI_Controller {
		function __construct(){
			parent::__construct();
			$this->load->model('admin/mdl_manage_script');
			$this->load->model('admin/mdl_reported_script');
			$this->load->library('cso_template',array("templateFile"=>"admin/admin_template"));
		}
		function index() {
			$data['reported_script']=$this->mdl_reported_script->get_reported_script(1);
			$data['jumlah_data_reported']=$this->mdl_reported_script->get_reported_script(2);
			$this->cso_template->view('admin/manage_reported_script',$data);
		}
		
		function form_manage_reported_script(){
			$script_id = $_GET['script_id'];
			$data['reported_script']=$this->mdl_reported_script->get_data_report($script_id);
			$data['script_id'] =$this->mdl_reported_script->get_data_script($script_id)->row('script_id');
			$data['question'] =$this->mdl_reported_script->get_data_script($script_id)->row('question');
			$data['answer'] =$this->mdl_reported_script->get_data_script($script_id)->row('answer');
			$data['category_id'] =$this->mdl_reported_script->get_data_script($script_id)->row('category_id');
			$data['tag'] =$this->mdl_reported_script->get_data_script($script_id)->row('tag');
			$data['user_create_id'] =$this->mdl_reported_script->get_data_script($script_id)->row('user_create_id');
			$data['pil_category'] =$this->mdl_manage_script->get_pil_category();
			$this->mdl_reported_script->update_status_report($script_id);
			$this->cso_template->view('admin/form_manage_script_report',$data);
		} 
		
		function search_script()
		{
			$text_search_script=$_GET['text_search_script'];
			$data['reported_script']=$this->mdl_reported_script->search_reported_script(1,1,$text_search_script);
			$this->cso_template->view('admin/manage_reported_script',$data);
		  }	  
		  
		  function search_script_suggestion()
		{
			$text_search_suggestion=$_GET['text_search_suggestion'];
			$data_script_suggestion = $this->mdl_reported_script->search_reported_script(2,1,$text_search_suggestion);
			$jumlah_data_suggestion = $this->mdl_reported_script->search_reported_script(2,2,$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 0;
			 foreach ($data_script_suggestion as $p):
			 	 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'>" .$p->question. "</li>";
			  endforeach;
			 }
		  }
		  
		  function save_edited_script()
		{
		  	$user_create_id = $this->session->userdata('session_user_id');
			$script_id = $_POST['script_id'];
			$question = $_POST['text-question'];
			$answer = $_POST['text-answer'];
			$category_id = $_POST['category'];
			$tag = $_POST['text-tag'];
			$this->mdl_reported_script->save_edited_script($script_id,$question,$answer,$category_id,$tag,$user_create_id);
		} 
		
		 function solved_reported_script()
		{
			$script_id = $_POST['script_id'];
			$this->mdl_reported_script->solved_reported_script($script_id);
		} 
		
}
?>		
