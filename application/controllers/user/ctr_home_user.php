<!-- M015 - YA - Search Script -->
<?php
class Ctr_home_user extends CI_Controller {
		function __construct(){
			parent::__construct();
			// $this->load->model('user/mdl_home_user');
			$this->load->model('user/mdl_categories');
			$this->load->model('user/mdl_view_list_script');
			$this->load->model('user/mdl_script_request');
			// M015
			$this->load->model('user/mdl_activity');
			// M015
			$this->load->model('mdl_wording');
			$this->load->model('mdl_ticket');
			$this->load->library('cso_template', array('templateFile' => 'user/user_template'));
		}
		function index() {
			$data['top_scripts'] = $this->mdl_view_list_script->top_scripts(10);
			$data['pil_category'] = $this->mdl_categories->get_by_level(1);
			$data['activity_type'] = $this->mdl_activity->get_by_parent();
			$this->cso_template->view('user/cso_home', $data);
		}
		function view_detail_script($script_id){
			$data['detail_script'] = $this->mdl_view_list_script->view_detail_script($script_id);
			$data['pil_category'] = $this->mdl_categories->get_by_level(1);
			$this->cso_template->view('user/cso_view_script', $data);
		}
		function request_script(){
			$request_content = $_POST['txt-request'];
			$user_id = $_POST['user_id'];
			$this->mdl_script_request->add_request($user_id, $request_content);
		}
		function ajax_wording($type){
			$wording_record = $this->mdl_wording->get_wording($type);
			
			if ($type == "announcement"){
				if (isset($wording_record[0])){
					$str_to_display = "";
					foreach ($wording_record as $record){
						$str_to_display .= "<div class='cso-announcement-item'>" 
							. $record->wording_content 
							. "<div class='cso-announcement-creator'>By " . $record->user_name . " on " . $record->wording_datetime . "</div>"
							. "</div>";
					}	
					echo $str_to_display;
				}
				else echo "<i>- No Annoucement -</i>";
				}
			else if (isset($wording_record[0]))
				echo $wording_record[0]->wording_content;
		}
		function ajax_category($level, $parent = ''){
			$pil_category = $this->mdl_categories->get_by_level($level, $parent);
			$str_to_display = "";
			foreach ($pil_category as $p){
				$str_to_display .= "<option value='" . $p->code_id . "'>" . $p->category . "</option>";
			}
			echo $str_to_display;
		}
		function ajax_ticketcategories(){
			$pil_category = $this->mdl_ticket->category_get_all();
			$str_to_display = "";
			foreach($pil_category as $p)
				$str_to_display .= "<option value='" . $p->cat_id . "'>" 
					. $p->catname 
					. "</option>";
			echo $str_to_display;
		}
		function ajax_ticketfields(){
			$cat_id = $_POST['cat_id'];
			$result = $this->mdl_ticket->category_detail($cat_id);
			echo str_replace("\\n", " ", $result[0]->fields);
		}
		function ajax_ticketpriorities(){
			$pil_priority = $this->mdl_ticket->priority_get_all(false);
			$str_to_display = "";
			foreach($pil_priority as $p){
				$str_to_display .= "<option value='" . $p->priority_id . "' " . (($p->priority_default == "1") ? "selected" : "") . ">" 
					. $p->priority_name 
					. "</option>";
			}
			echo $str_to_display;
		}
		function ajax_tickethistories($last_ticket = 0){
			
		}
}
