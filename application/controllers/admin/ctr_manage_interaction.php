<!-- M023 - YA - searching by date -->
<!-- M59 - YA - filtering interaction berdasarkan tanggal, nama team dan dama user -->
<!-- M61 - YA - filtering dan reporting summary intercation activity report berdasarkan team, nama dan level -->
<?php
class Ctr_manage_interaction extends CI_Controller 
{
		function __construct(){
			parent::__construct();
			$this->load->model('admin/mdl_manage_interaction');
			$this->load->library("cso_template", array("templateFile" => "admin/admin_template"));
		}
		
		function index_interaction_type() {
		
			$data['data_interaction_type']=$this->mdl_manage_interaction->get_interaction_type(1);
			$data['interaction_type_id'] =$this->mdl_manage_interaction->get_interaction_type(2) + 1;
			$data['pil_active'] =$this->mdl_manage_interaction->get_pil_status_active();
			$this->cso_template->view('admin/manage_interaction_type',$data);
		}
		
		function add_interaction_type() {
				$interaction_type_id =$_POST['text-interaction_type_ID'];
				$interaction_type_name=$_POST['text-interaction_type_name'];
				$status_active=$_POST['status_active'];
				$this->mdl_manage_interaction->add_interaction_type($interaction_type_id,$interaction_type_name,$status_active);
		}
		
		function edit_interaction_type() 
		{	
				$interaction_type_id =$_POST['text-interaction_type_ID_edit'];
				$interaction_type_name=$_POST['text-interaction_type_name_edit'];
				$status_active=$_POST['status_active_edit'];
				$this->mdl_manage_interaction->edit_interaction_type($interaction_type_id,$interaction_type_name,$status_active);
		}
			
		
		function search_interaction_type()
		{
			$text_search_interaction_type=$_GET['text_search_interaction_type'];
			$data['data_interaction_type']=$this->mdl_manage_interaction->search_interaction_type(1,1,$text_search_interaction_type);
			$data['interaction_type_id'] =$this->mdl_manage_interaction->get_interaction_type(2) + 1;
			$data['pil_active'] =$this->mdl_manage_interaction->get_pil_status_active();
			$this->cso_template->view('admin/manage_interaction_type',$data);
		  }	  
		  
		  function search_interaction_type_suggestion()
		{
			$text_search_suggestion=$_GET['text_search_suggestion'];
			$data_suggestion = $this->mdl_manage_interaction->search_interaction_type(2,1,$text_search_suggestion);
			$jumlah_data_suggestion =$this->mdl_manage_interaction->search_interaction_type(2,1,$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 0;
			 foreach ($data_suggestion as $p):
				 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'>" .$p->interaction_type_name. "</li>";
			  endforeach;
			 }
		  }
		 
		// M59 
		function view_report_interaction(){
			$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_report();
			$data['flag_view_detail'] = 0;
			$data['startdate'] = '';
			$data['enddate'] = '';
			$data['id'] = '';
			$data['creator_id'] = '';
			$data['user_group'] = $this->mdl_manage_interaction->get_user_group();
			$data['user_name'] = $this->mdl_manage_interaction->get_user_name();
			$this->cso_template->view("admin/view_interaction_report", $data, "admin/admin_template");
		}
		
		function search_report_interaction_bydate(){
			$id = $_GET['id'];
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$data['list_report_interaction'] = $this->mdl_manage_interaction->report_interaction_search_bydate(1,$text_startDate,$text_endDate,$id);
			$data['flag_view_detail'] = 1;
			$data['id'] = $id;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['activity_code'] = '';
			$data['creator_id'] = '';
			$data['user_group'] = $this->mdl_manage_interaction->get_user_group();
			$data['user_name'] = $this->mdl_manage_interaction->get_user_name();
			$this->cso_template->view("admin/view_interaction_report", $data, "admin/admin_template");
		}

		function search_report_interaction_bycreator(){
			$creator_id = $_GET['creator_id'];
			$id = $_GET['id'];
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$data['list_report_interaction'] = $this->mdl_manage_interaction->report_interaction_search_bycreator(1,$text_startDate,$text_endDate,$creator_id);
			$data['flag_view_detail'] = 1;
			$data['creator_id'] = $creator_id;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['id'] = $id;
			$data['activity_code'] = '';
			$data['user_group'] = $this->mdl_manage_interaction->get_user_group();
			$data['user_name'] = $this->mdl_manage_interaction->get_user_name();
			$this->cso_template->view("admin/view_interaction_report", $data, "admin/admin_template");
		}
		
		function view_report_interaction_detail(){
			$flag_view_detail = $_GET['flag_view_detail'];
			$status = $_GET['status'];
			$interaction_type_id = $_GET['interaction_type_id'];
			
			if ($flag_view_detail == 0) {
				$data['list_report_interaction_detail'] =  $this->mdl_manage_interaction->get_interaction_report_detail(1,'','','',$status,$interaction_type_id);
			    $data['startdate'] = '';
				$data['enddate'] = '';
				$data['id'] = '';
			}
			else {
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$id = $_GET['id'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_report_detail(2,$text_startDate,$text_endDate,$id,$status,$interaction_type_id);
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['id'] = $id;
			}
			$data['flag_view_detail'] = $flag_view_detail;
			$data['status'] = $status;
			$data['interaction_type_id'] = $interaction_type_id;
			$this->cso_template->view("admin/view_interaction_report_detail", $data, "admin/admin_template");
		}
// M61
		function view_report_interaction_detail_bycreator(){
			$flag_view_detail = $_GET['flag_view_detail'];
			$status = $_GET['status'];
			$interaction_type_id = $_GET['interaction_type_id'];
			
			if ($flag_view_detail == 0) {
				$data['list_report_interaction_detail'] =  $this->mdl_manage_interaction->get_interaction_report_detail(1,'','','',$status,$interaction_type_id);
			    $data['startdate'] = '';
				$data['enddate'] = '';
				$data['creator_id'] = '';
			}
			else {
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$creator_id = $_GET['creator_id'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_report_detail_bycreator(2,$text_startDate,$text_endDate,$creator_id,$status,$interaction_type_id);
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['creator_id'] = $creator_id;
				$data['id'] = '';
			}
			$data['flag_view_detail'] = $flag_view_detail;
			$data['status'] = $status;
			$data['interaction_type_id'] = $interaction_type_id;
			$this->cso_template->view("admin/view_interaction_report_detail", $data, "admin/admin_template");
		}

		function interaction_report_toexcel()
		{
		$flag_view_detail = $_GET['flag_view_detail'];
		$status = $_GET['status'];
		$interaction_type_id = $_GET['interaction_type_id'];
		if ($flag_view_detail == 0) {
			$data['hasil'] =  $this->mdl_manage_interaction->get_interaction_report_detail(1,'','','',$status,$interaction_type_id);
		}
		else {
			$text_startDate = $_GET['startdate'];
			$text_endDate = $_GET['enddate'];
			$id = $_GET['id'];
			$data['hasil'] = $this->mdl_manage_interaction->get_interaction_report_detail(2,$text_startDate,$text_endDate,$id,$status,$interaction_type_id);
		}
			$this->load->view('admin/view_interaction_report_toexcel', $data);
		}

		function interaction_report_toexcel_bycreator()
		{
		$flag_view_detail = $_GET['flag_view_detail'];
		$status = $_GET['status'];
		$interaction_type_id = $_GET['interaction_type_id'];
		if ($flag_view_detail == 0) {
			$data['hasil'] =  $this->mdl_manage_interaction->get_interaction_report_detail(1,'','','',$status,$interaction_type_id);
		}
		else {
			$text_startDate = $_GET['startdate'];
			$text_endDate = $_GET['enddate'];
			$creator_id = $_GET['creator_id'];
			$data['hasil'] = $this->mdl_manage_interaction->get_interaction_report_detail_bycreator(2,$text_startDate,$text_endDate,$creator_id,$status,$interaction_type_id);
		}
			$this->load->view('admin/view_interaction_report_toexcel', $data);
		}
		// M59

// M023
		function view_report_interaction_activity(){
			$data['activity_type'] = $this->mdl_manage_interaction->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_interaction->get_user_group();
			$data['user_name'] = $this->mdl_manage_interaction->get_user_name();
			$flag_view = $_GET['flag_view'];
			if ($flag_view == 0) {
				$data['flag_view_detail'] = 0;
				// $data['date'] = '';
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = '';
				$data['creator_id'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(1,'','','','','');
			}
			else if ($flag_view == 1) {
				$data['flag_view_detail'] = 1;
				$text_startDate = $_GET['text_startDate'];
				$text_endDate = $_GET['text_endDate'];
				// $data['date'] = $date;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = '';
				$data['creator_id'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(2,'',$text_startDate,$text_endDate,'','');
			}
			else if ($flag_view == 2) {
				$activity_code = $_GET['activity_code'];
				$data['flag_view_detail'] = 2;
				// $data['date'] = '';
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = $activity_code;
				$data['creator_id'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(3,$activity_code,'','','','');
			}
			else if ($flag_view == 3) {
				// $date = $_GET['text_Date'];
				$data['flag_view_detail'] = 3;
				$text_startDate = $_GET['text_startDate'];
				$text_endDate = $_GET['text_endDate'];
				$activity_code = $_GET['activity_code'];
				// $data['date'] = $date;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = $activity_code;
				$data['creator_id'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(4,$activity_code,$text_startDate,$text_endDate,'','');
			}
			else if ($flag_view == 4) {
				$data['flag_view_detail'] = 4;
				$user_group = $_GET['user_group'];
				$data['user_group'] = $user_group;
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = '';
				$data['creator_id'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(5,'','','',$user_group,'');
			}
			else if ($flag_view == 5) {
				$data['flag_view_detail'] = 5;
				$text_startDate = $_GET['text_startDate'];
				$text_endDate = $_GET['text_endDate'];
				$user_group = $_GET['user_group'];
				$data['user_group'] = $user_group;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = '';
				$data['creator_id'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(6,'',$text_startDate,$text_endDate,$user_group,'');
			}
			else if ($flag_view == 6) {
				$data['flag_view_detail'] = 6;
				$activity_code = $_GET['activity_code'];
				$text_startDate = $_GET['text_startDate'];
				$text_endDate = $_GET['text_endDate'];
				$user_group = $_GET['user_group'];
				$data['user_group'] = $user_group;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = $activity_code;
				$data['creator_id'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(7,$activity_code,$text_startDate,$text_endDate,$user_group,'');
			}
			else if ($flag_view == 7) {
				$data['flag_view_detail'] = 7;
				$creator_id = $_GET['creator_id'];
				$data['creator_id'] = $creator_id;
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(8,'','','','',$creator_id);
			}
			else if ($flag_view == 8) {
				$data['flag_view_detail'] = 8;
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['text_startDate'];
				$text_endDate = $_GET['text_endDate'];
				$data['creator_id'] = $creator_id;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(9,'',$text_startDate,$text_endDate,'',$creator_id);
			}
			else if ($flag_view == 9) {
				$data['flag_view_detail'] = 9;
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['text_startDate'];
				$text_endDate = $_GET['text_endDate'];
				$activity_code = $_GET['activity_code'];
				$data['creator_id'] = $creator_id;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = $activity_code;
				$data['user_group'] = '';
				$data['list_report_interaction'] = $this->mdl_manage_interaction->get_interaction_activity_report(10,$activity_code,$text_startDate,$text_endDate,'',$creator_id);
			}
			$this->cso_template->view("admin/view_interaction_activity_report", $data, "admin/admin_template");
		}
		
		function view_report_interaction_activity_detail(){
			$flag_view_detail = $_GET['flag_view_detail'];
			$activity_id = $_GET['activity_id'];
			if ($flag_view_detail == 0 or $flag_view_detail == 2) {
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(1,'','',$activity_id,'','');
				// $data['date'] = '';
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['user_group'] = '';
				$data['creator_id'] = '';
			echo $flag_view_detail;
			}
			else if ($flag_view_detail == 1 or $flag_view_detail == 3){
				// $date = $_GET['date'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(2,$text_startDate,$text_endDate,$activity_id,'','');
				// $data['date'] = $date;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['user_group'] = '';
				$data['creator_id'] = '';
			echo $flag_view_detail;
			}
			else if ($flag_view_detail == 4){
				// $date = $_GET['date'];
				$user_group = $_GET['user_group'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(3,'','',$activity_id,$user_group,'');
				// $data['date'] = $date;
				$data['user_group'] = $user_group;
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['creator_id'] = '';
			echo $flag_view_detail;
			}
			else if ($flag_view_detail == 5){
				// $date = $_GET['date'];
				$user_group = $_GET['user_group'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(4,$text_startDate,$text_endDate,$activity_id,$user_group,'');
				// $data['date'] = $date;
				$data['user_group'] = $user_group;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['creator_id'] = '';
			echo $flag_view_detail;
			}
			else if ($flag_view_detail == 6){
				// $date = $_GET['date'];
				$user_group = $_GET['user_group'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(5,$text_startDate,$text_endDate,$activity_id,$user_group,'');
				// $data['date'] = $date;
				$data['user_group'] = $user_group;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['creator_id'] = '';
			echo $flag_view_detail;
			}
			else if ($flag_view_detail == 7){
				// $date = $_GET['date'];
				$creator_id = $_GET['creator_id'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(6,'','',$activity_id,'',$creator_id);
				// $data['date'] = $date;
				$data['creator_id'] = $creator_id;
				$data['user_group'] = '';
				$data['startdate'] = '';
				$data['enddate'] = '';
			echo $flag_view_detail;
			}
			else if ($flag_view_detail == 8){
				// $date = $_GET['date'];
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(7,$text_startDate,$text_endDate,$activity_id,'',$creator_id);
				// $data['date'] = $date;
				$data['creator_id'] = $creator_id;
				$data['user_group'] = '';
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			echo $flag_view_detail;
			}
			else if ($flag_view_detail == 9){
				// $date = $_GET['date'];
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(8,$text_startDate,$text_endDate,$activity_id,'',$creator_id);
				// $data['date'] = $date;
				$data['creator_id'] = $creator_id;
				$data['user_group'] = '';
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			echo $flag_view_detail;
			}
			
			$data['activity_id'] = $activity_id;
			$data['flag_view_detail'] = $flag_view_detail;
			$this->cso_template->view("admin/view_interaction_activity_report_detail", $data, "admin/admin_template");
		}
		
		function view_report_interaction_activity_toexcel(){
			$flag_view_detail = $_GET['flag_view_detail'];
			$activity_id = $_GET['activity_id'];
			
			if ($flag_view_detail == 0 or $flag_view_detail == 2) {
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(1,$text_startDate,$text_endDate,$activity_id,'','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			else if ($flag_view_detail == 1 or $flag_view_detail == 3){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(2,$text_startDate,$text_endDate,$activity_id,'','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			else if ($flag_view_detail == 4){
				// $date = $_GET['date'];
				$user_group = $_GET['user_group'];
				$data['list_report_interaction_detail'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(3,'','',$activity_id,$user_group,'');
				// $data['date'] = $date;
				$data['user_group'] = $user_group;
			}
			else if ($flag_view_detail == 5){
				// $date = $_GET['date'];
				$user_group = $_GET['user_group'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(4,$text_startDate,$text_endDate,$activity_id,$user_group,'');
				// $data['date'] = $date;
				$data['user_group'] = $user_group;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			else if ($flag_view_detail == 6){
				// $date = $_GET['date'];
				$user_group = $_GET['user_group'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(5,$text_startDate,$text_endDate,$activity_id,$user_group,'');
				// $data['date'] = $date;
				$data['user_group'] = $user_group;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			else if ($flag_view_detail == 7){
				// $date = $_GET['date'];
				$creator_id = $_GET['creator_id'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(6,'','',$activity_id,'',$creator_id);
				// $data['date'] = $date;
				$data['creator_id'] = $creator_id;
			}
			else if ($flag_view_detail == 8){
				// $date = $_GET['date'];
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(7,$text_startDate,$text_endDate,$activity_id,'',$creator_id);
				// $data['date'] = $date;
				$data['creator_id'] = $creator_id;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;			
			}
			else if ($flag_view_detail == 9){
				// $date = $_GET['date'];
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail(8,$text_startDate,$text_endDate,$activity_id,'',$creator_id);
				// $data['date'] = $date;
				$data['creator_id'] = $creator_id;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			$this->load->view('admin/view_interaction_activity_report_toexcel', $data);
		}
		
		function view_report_interaction_activity_toexcel_summary(){
			$flag_view = $_GET['flag_view'];
			$activity_code = $_GET['activity_code'];
			
			if ($flag_view == 0) {
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(1,'','','','','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			else if ($flag_view == 1){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(2,$text_startDate,$text_endDate,'','','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			else if ($flag_view == 2){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];

				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(3,'','',$activity_code,'','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			else if ($flag_view == 3){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(4,$text_startDate,$text_endDate,$activity_code,'','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			else if ($flag_view == 4){
				$activity_code = $_GET['activity_code'];
				$user_group = $_GET['user_group'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(5,'','',$activity_code,$user_group,'');
				$data['activity_code'] = $activity_code;
				$data['user_group'] = $user_group;
			}
			else if ($flag_view == 5){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$user_group = $_GET['user_group'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(6,$text_startDate,$text_endDate,$activity_code,$user_group,'');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['user_group'] = $user_group;
			}
			else if ($flag_view == 6){
				// $date = $_GET['date'];
				$user_group = $_GET['user_group'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(7,$text_startDate,$text_endDate,$activity_code,$user_group,'');
				// $data['date'] = $date;
				$data['user_group'] = $user_group;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			else if ($flag_view == 7){
				// $date = $_GET['date'];
				$creator_id = $_GET['creator_id'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(8,'','',$activity_code,'',$creator_id);
				// $data['date'] = $date;
				$data['creator_id'] = $creator_id;
			}
			else if ($flag_view == 8){
				// $date = $_GET['date'];
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(9,$text_startDate,$text_endDate,$activity_code,'',$creator_id);
				// $data['date'] = $date;
				$data['creator_id'] = $creator_id;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;			
			}
			else if ($flag_view == 9){
				// $date = $_GET['date'];
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report_detail2(10,$text_startDate,$text_endDate,$activity_code,'',$creator_id);
				// $data['date'] = $date;
				$data['creator_id'] = $creator_id;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
			}
			$data['activity_code'] = $activity_code;
			$this->load->view('admin/view_interaction_activity_report_toexcel', $data);
// M61
			// $flag_view = $_GET['flag_view'];
			// if ($flag_view == 0) {
			// 	$data['flag_view_detail'] = 0;
			// 	// $data['date'] = '';
			// 	$data['activity_code'] = '';
			// 	$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report(1,'','','');
			// }
			// else if ($flag_view == 1) {
			// 	// $date = $_GET['date'];
			// 	$text_startDate = $_GET['startdate'];
			// 	$text_endDate = $_GET['enddate'];
			// 	$data['flag_view_detail'] = 1;
			// 	// $data['date'] = $date;
			// 	$data['startdate'] = $text_startDate;
			// 	$data['enddate'] = $text_endDate;
			// 	$data['activity_code'] = '';
			// 	$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report(2,'',$text_startDate,$text_endDate);
			// }
			// else if ($flag_view == 2) {
			// 	$activity_code = $_GET['activity_code'];
			// 	$data['flag_view_detail'] = 2;
			// 	// $data['date'] = '';
			// 	$data['activity_code'] = $activity_code;
			// 	$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report(3,$activity_code,'','');
			// }
			// else if ($flag_view == 3) {
			// 	// $date = $_GET['date'];
			// 	$text_startDate = $_GET['startdate'];
			// 	$text_endDate = $_GET['enddate'];
			// 	$activity_code = $_GET['activity_code'];
			// 	$data['flag_view_detail'] = 3;
			// 	// $data['date'] = $date;
			// 	$data['startdate'] = $text_startDate;
			// 	$data['enddate'] = $text_endDate;
			// 	$data['activity_code'] = $activity_code;
			// 	$data['hasil'] = $this->mdl_manage_interaction->get_interaction_activity_report(4,$activity_code,$text_startDate,$text_endDate);
			// }
			// $this->load->view('admin/view_interaction_activity_summary_report_toexcel', $data);	
		}

		// function get_user(){
		// 	$group_id = $this->input->post('group_id');
		// 	$user_id = $this->mdl_manage_interaction->get_user($group_id);
		// 	$data .= "<option value = ''>-- Choose --</option>";
		// 	foreach ($user_id as $userid) {
		// 		$data .= "<option value='$userid->user_id>$userid->user_name</option>";
		// 	}
		// 	echo $data;
		// }
}
// M023