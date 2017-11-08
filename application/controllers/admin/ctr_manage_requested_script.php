<?php
class Ctr_manage_requested_script extends CI_Controller {
		function __construct(){
			parent::__construct();
			$this->load->model('admin/mdl_manage_script');
			$this->load->model('admin/mdl_requested_script');
			$this->load->library('cso_template',array("templateFile"=>"admin/admin_template"));
		}
		function index() {
			$data['requested_script']=$this->mdl_requested_script->get_requested_script(1);
			$data['jumlah_data_requested']=$this->mdl_requested_script->get_requested_script(2);
			$this->cso_template->view('admin/manage_requested_script',$data);
		}
		
		function form_manage_requested_script(){
			$request_id = $_GET['request_id'];
			$data['requested_script']=$this->mdl_requested_script->get_data_request($request_id);
			$data['request_id'] = $request_id;
			$data['script_id'] =$this->mdl_requested_script->get_last_scriptid()->row('total_script')+1;
			$data['pil_category'] =$this->mdl_manage_script->get_pil_category();
			$this->mdl_requested_script->update_status_request($request_id,1);
			$this->cso_template->view('admin/form_manage_script_request',$data);
		} 
/*		
		function search_script()
		{
			$text_search_script=$_GET['text_search_script'];
			$data['requested_script']=$this->mdl_requested_script->search_requested_script(1,$text_search_script);
			$this->cso_template->view('admin/manage_requested_script',$data);
		  }	  
		  */
		 function search_script_bydate()
		{
			$startDate=$_GET['startDate'];
			$endDate=$_GET['endDate'];
			$data['requested_script']=$this->mdl_requested_script->search_requested_script_bydate(1,$startDate,$endDate);
			$data['jumlah_data_requested']=$this->mdl_requested_script->search_requested_script_bydate(2,$startDate,$endDate);
			$this->cso_template->view('admin/manage_requested_script',$data);
		  }	
		  
		/*  function search_script_suggestion()
		{
			$text_search_suggestion=$_GET['text_search_suggestion'];
			$data_script_suggestion = $this->mdl_requested_script->search_requested_script(1,$text_search_suggestion);
			$jumlah_data_suggestion = $this->mdl_requested_script->search_requested_script(2,$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 1;
			 foreach ($data_script_suggestion as $p):
			 	 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'>" .$p->question. "</li>";
			  endforeach;
			 }
		  }*/
		  
		  function save_requested_script()
		{
			$user_id = $this->session->userdata('session_user_id');
			$script_id =$_POST['text-scriptID'];
			$question=$_POST['text-question'];
			$answer=$_POST['text-answer'];
			$tag=$_POST['text-tag'];
			$category_id=$_POST['category_id'];
			$visibility = $_POST['visibility'];
			$tracking_category = $_POST['tracking_category'];
			$this->mdl_requested_script->save_requested_script($script_id,$question,$answer,$category_id,$tag,$user_id,$visibility,$tracking_category);
		} 
		
		  function solved_requested_script()
		{
			$request_id = $_GET['request_id'];
			$this->mdl_requested_script->update_status_request($request_id,2);
			redirect('admin/ctr_manage_requested_script');
		} 
		
		function script_subcategory(){
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
			echo "<select id='category".$level_subcategory."' name='category".$level_subcategory."' onchange='show_subcategory(1,".$level_subcategory.")'>";
			echo "<option value=''>--choose--</option>";
				foreach ($subcategory as $p):
						echo "<option value='".$p->code_id."'>".$p->category."</option>";
				endforeach;
			echo "</select>";
			}
			else echo " ";
		}
		
}
	
