<!-- M003 - YA - Filter by tgl -->
<!-- M008 - YA - export ticket template to excel -->
<!-- M009 - YA - export ticket activityplan to excel -->
<!-- Import Ticket Template -->
<!-- Import Activity plan -->
<!-- M032 - YA - Master activity plan -->
<!-- M033 - YA - add edit template -->
<!-- B02  - YA - Perbaikan search -->
<!-- B04  - YA - Perbaikan Insert & Update activityplan-->
<!-- M60 - YA - filtering , reporting dan export ticket berdasarkan activity code, tanggal, group, user, dan level -->
<!-- M62 - YA - Tombol sakti untuk langsung export seluruh status data ticket dengan tanpa harus terlebih dahulu masuk dulu ke masing2 status ticket untuk melakukan export -->
<?php
class Ctr_manage_ticket_template extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library("cso_template", array("templateFile" => "escalation/esc_template"));
		$this->load->model("admin/mdl_manage_ticket_template");
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('file');
        $this->load->library('form_validation');
	}
	
	function index_ticket_template(){
		$data['list_ticket_template'] = $this->mdl_manage_ticket_template->ticket_template_get_all();
		$data['last_ticket_template_id'] =$this->mdl_manage_ticket_template->get_last_ticket_template_id()+ 1;
		$data['pil_active'] =$this->mdl_manage_ticket_template->get_pil_status_active();
		$this->cso_template->view("admin/manage_ticket_template", $data, "admin/admin_template");
	}

	function manage_ticket_template(){
		$activity_code = $_GET['activity_code'];
		$data['activity_code'] = $_GET['activity_code'];
		$data['last_ticket_template_activity_id'] =$this->mdl_manage_ticket_template->get_last_ticket_template_activity_id()+ 1;
		$data['list_ticket_template_activity'] = $this->mdl_manage_ticket_template->ticket_template_activity_get_all($activity_code);
		$data['list_ticket_template_all'] = $this->mdl_manage_ticket_template->ticket_template_get_all();
		$data['pil_active'] =$this->mdl_manage_ticket_template->get_pil_status_active();
		$this->cso_template->view("admin/manage_ticket_template_activity", $data, "admin/admin_template");
	}
// B04
	function add_ticket_activity_plan(){
		$this->mdl_manage_ticket_template->add_ticket_activity_plan(array(
			"plan_id" => $_POST['plan_id'],
			"ticket_template_id" => $_POST['ticket_template_id'],
			"plan_order" => $_POST['plan_order'],
			"status_active" => $_POST['status_active']
		));
	}
// B04
// M008
	function ticket_template_toexcel(){
			$data['hasil'] =  $this->mdl_manage_ticket_template->ticket_template_get_all();
			$this->load->view('admin/view_ticket_template_toexcel', $data);
		}
// M008
// Import Ticket Template
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
				$dataexcel[$i-3]['ticket_template_id'] = $data['cells'][$i][1];
                $dataexcel[$i-3]['ticket_template_name'] = $data['cells'][$i][2];
                $dataexcel[$i-3]['status_active'] = $data['cells'][$i][3];
                
			}

            delete_files($upload_data['file_path']);
            $this->load->model('admin/mdl_manage_ticket_template');
            $check = $this->mdl_manage_ticket_template->cek_ticket_template($dataexcel);
		    if (count($check) > 0)
		    {
				$this->mdl_manage_ticket_template->import_excel_edit($dataexcel);
		    }else{
				$this->mdl_manage_ticket_template->import_excel($dataexcel);
			}
			$data['helpcso_ticket_template'] = $this->mdl_manage_ticket_template->ticket_template_get_all($ticket_template_id);
		}
		
		$data['list_ticket_template'] = $this->mdl_manage_ticket_template->ticket_template_get_all($ticket_template_id);
		$data['last_ticket_template_id'] =$this->mdl_manage_ticket_template->get_last_ticket_template_id()+ 1;
		$data['pil_active'] =$this->mdl_manage_ticket_template->get_pil_status_active();
		$this->cso_template->view("admin/manage_ticket_template", $data, "admin/admin_template");
	}
// Import Ticket Template
	function add_ticket_template(){
			$this->mdl_manage_ticket_template->add_ticket_template(array(
				"ticket_template_name" => $_POST['ticket_template_name'],
				"status_active" => $_POST['status_active']
			));
		}

	function edit_ticket_template(){
		$this->mdl_manage_ticket_template->edit_ticket_template($_POST['ticket_template_id'], array(
				"ticket_template_name" => $_POST['ticket_template_name'],
				"status_active" => $_POST['status_active']
		));
	}

	function add_ticket_template_activity(){
			$this->mdl_manage_ticket_template->add_ticket_template_activity(array(
				"activity_code" => $_POST['activity_code'],
				"ticket_template_id" => $_POST['ticket_template_id'],
				"status_active" => $_POST['status_active']
			));
		}

	function edit_ticket_template_activity($ticket_plan_id){
		$this->mdl_manage_ticket_template->edit_ticket_template_activity($_POST['ticket_plan_id'], array(
				// "ticket_template_id" => $_POST['ticket_template_id'],
				"status_active" => $_POST['status_active']
		));
	}

	function search_ticket_template(){
		$text_search = $_GET['text_search'];
		$data['list_ticket_template'] = $this->mdl_manage_ticket_template->search_ticket_template(1,1,$text_search);
		$data['last_ticket_template_id'] =$this->mdl_manage_ticket_template->get_last_ticket_template_id()+ 1;
		$data['pil_active'] =$this->mdl_manage_ticket_template->get_pil_status_active();
		$this->cso_template->view("admin/manage_ticket_template", $data, "admin/admin_template");
	}
	
	 function search_ticket_template_suggestion()
	{
			$text_search_suggestion=$_GET['text_search_suggestion'];
			$data_ticket_template_suggestion = $this->mdl_manage_ticket_template->search_ticket_template(2,1,$text_search_suggestion);
			$jumlah_data_suggestion = $this->mdl_manage_ticket_template->search_ticket_template(2,2,$text_search_suggestion);
			if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
				echo " ";
			}
			else {	
				$i = 0;
			 foreach ($data_ticket_template_suggestion as $p):
				 $i++;
				 echo "<li id='li".$i."' onClick='chosenText".$i."()'><div style='cursor:pointer;'>" .$p->ticket_template_name. "</div></li>";
			  endforeach;
		}
	}

//fields
// M033
	function index_activity_plan(){
		$ticket_template_id = $_GET['ticket_template_id'];
		$data['ticket_template_id'] = $_GET['ticket_template_id'];
		// $data['ticket_template_name'] = $_GET['ticket_template_name'];
		$function_name_arr = $this->mdl_manage_ticket_template->activity_plan_get_all($ticket_template_id);
		if (count($function_name_arr) > 0)
			$function_name = $function_name_arr[0]->function_name;
		else $function_name = "";
		$data['list_activity_plan'] = $this->mdl_manage_ticket_template->activity_plan_get_all($ticket_template_id);
		$data['list_activity_plan_all'] = $this->mdl_manage_ticket_template->activity_plan_get_all2();
		$data['last_activity_plan_id'] =$this->mdl_manage_ticket_template->get_activity_plan_last_id() + 1;
		$data['pil_active'] =$this->mdl_manage_ticket_template->get_pil_status_active();
		$this->cso_template->view("admin/manage_activity_plan", $data, "admin/admin_template");
		// print_r($function_name);
	}
// M033
// M032
	function get_activity_plan(){
		// $ticket_template_id = $_GET['ticket_template_id'];
		// $data['ticket_template_id'] = $_GET['ticket_template_id'];
		// $data['ticket_template_name'] = $_GET['ticket_template_name'];
		$data['list_activity_plan'] = $this->mdl_manage_ticket_template->get_all_activity_plan();
		$data['last_activity_plan_id'] =$this->mdl_manage_ticket_template->get_activity_plan_last_id() + 1;
		$data['pil_active'] =$this->mdl_manage_ticket_template->get_pil_status_active();
		$this->cso_template->view("admin/form_manage_activity_plan", $data, "admin/admin_template");
	}
// M032
		
// M009
	// function ticket_activityplan_toexcel(){
	// 		$data['hasil'] = $this->mdl_manage_ticket_template->activity_plan_get_all_toexcel();
	// 		$this->load->view('admin/view_ticket_activityplan_toexcel', $data);
	// 	}


	function ticket_activityplan_byid_toexcel(){
		$ticket_template_id = $_GET['ticket_template_id'];
		$data['ticket_template_id'] = $_GET['ticket_template_id'];
		// $data['ticket_template_name'] = $_GET['ticket_template_name'];
		$data['list_activity_plan'] = $this->mdl_manage_ticket_template->activity_plan_get_all($ticket_template_id);
		$data['hasil'] = $this->mdl_manage_ticket_template->activity_plan_get_all($ticket_template_id);
		$this->load->view('admin/view_ticket_activityplan_toexcel', $data);
		}
// M009

// Import ActivityPlan
	function do_upload2()
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
				$dataexcel[$i-3]['plan_id'] = $data['cells'][$i][1];
                $dataexcel[$i-3]['plan_order'] = $data['cells'][$i][2];
                $dataexcel[$i-3]['action_name'] = $data['cells'][$i][3];
				$dataexcel[$i-3]['function_name'] = $data['cells'][$i][4];
                $dataexcel[$i-3]['sla'] = $data['cells'][$i][5];
                $dataexcel[$i-3]['status_active'] = $data['cells'][$i][6];
                $dataexcel[$i-3]['ticket_template_id'] = $data['cells'][$i][7];
                
			}

            delete_files($upload_data['file_path']);
            $this->load->model('admin/mdl_manage_ticket_template');
            $check = $this->mdl_manage_ticket_template->cek_activityplan($dataexcel);
		    if (count($check) > 0)
		    {
				$this->mdl_manage_ticket_template->import_activityplan_edit($dataexcel);
		    }else{
				$this->mdl_manage_ticket_template->import_activityplan($dataexcel);
			}
            $data['helpcso_ticket_activityplan'] = $this->mdl_manage_ticket_template->activity_plan_get_all($ticket_template_id);
		}
		$ticket_template_id = $_GET['ticket_template_id'];
		$data['ticket_template_id'] = $_GET['ticket_template_id'];
		$data['ticket_template_name'] = $_GET['ticket_template_name'];
		$data['list_activity_plan'] = $this->mdl_manage_ticket_template->activity_plan_get_all($ticket_template_id);
		$data['last_activity_plan_id'] =$this->mdl_manage_ticket_template->get_activity_plan_last_id() + 1;
		$data['pil_active'] =$this->mdl_manage_ticket_template->get_pil_status_active();
		$this->cso_template->view("admin/manage_activity_plan", $data, "admin/admin_template");
	}
// Import ActivityPlan

	function add_activity_plan(){
		// $ticket_template_id = $_POST['ticket_template_id'];
		$this->mdl_manage_ticket_template->add_activity_plan(array(
			// "plan_order" => $_POST['plan_order'],
			"plan_id" => $_POST['plan_id'],
			"action_name" =>  $_POST['action_name'],
			"function_name" =>  $_POST['function_name'],
			"sla" =>  $_POST['sla'],
			"status_active" =>  $_POST['status_active']
		),$ticket_template_id);
	}
	
	function edit_activity_plan($plan_id){
		$this->mdl_manage_ticket_template->edit_activity_plan($_POST['plan_id'], array(
			// "ticket_template_id" => $_POST['ticket_template_id'],
			// "plan_id" => $_POST['plan_id'],
			"plan_order" => $_POST['plan_order'],
			"action_name" =>  $_POST['action_name'],
			"function_name" =>  $_POST['function_name'],
			"sla" =>  $_POST['sla'],
			"status_active" =>  $_POST['status_active']
		));
	}
// B04
	function edit_template_activity_plan($ticket_activityplan_id){
		$this->mdl_manage_ticket_template->edit_ticket_activity_plan($_POST['ticket_activityplan_id'], array(
			"ticket_template_id" => $_POST['ticket_template_id'],
			"plan_id" => $_POST['plan_id'],
			"plan_order" => $_POST['plan_order'],
			"status_active" => $_POST['status_active']
		));
	}
// B04
// B02
	function search_activity_plan(){
		// $plan_id = $_GET['plan_id'];
		$text_search = $_GET['text_search'];
		// $data['plan_id'] = $_GET['plan_id'];
		// $data['action_name'] = $_GET['action_name'];
		$data['list_activity_plan'] = $this->mdl_manage_ticket_template->search_activity_plan(1,1,$text_search);
		$data['list_activity_plan_all'] = $this->mdl_manage_ticket_template->activity_plan_get_all2();
		$data['last_activity_plan_id'] =$this->mdl_manage_ticket_template->get_activity_plan_last_id() + 1;
		$data['pil_active'] =$this->mdl_manage_ticket_template->get_pil_status_active();
		$this->cso_template->view("admin/form_manage_activity_plan", $data, "admin/admin_template");
	}
	
	 function search_activity_plan_suggestion()
	{		
		$text_search_suggestion = $_GET['text_search_suggestion'];
		$data_fields_suggestion = $this->mdl_manage_ticket_template->search_activity_plan(2,1,$text_search_suggestion);
		$jumlah_data_suggestion = $this->mdl_manage_ticket_template->search_activity_plan(2,2,$text_search_suggestion);
		if($text_search_suggestion == '' || $jumlah_data_suggestion == 0) {
			echo " ";
		}
		else {	
			$i = 0;
		foreach ($data_fields_suggestion as $p):
			$i++;
			echo "<li id='li".$i."' onClick='chosenText".$i."()'><div style='cursor:pointer;'>" .$p->action_name. "</div></li>";
		endforeach;
		}
	}
// B02

//report
// M60
		function view_report_ticket(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$flag_view = $_GET['flag_view'];
			if ($flag_view == 0) {
				$data['flag_view_detail'] = 0;
				$data['activity_code'] = '';
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_report(1,'');
			}
			else {
				$activity_code = $_GET['activity_code'];
				$data['activity_code'] = $activity_code;
				$data['flag_view_detail'] = 1;
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_report(2,$activity_code);
			}
			$data['startdate'] = '';
			$data['enddate'] = '';
			$data['id'] = '';
			$data['creator_id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}
		
		function search_report_ticket_bydate(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_bydate(1,$text_startDate,$text_endDate);
			$data['flag_view_detail'] = 2;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['activity_code'] = '';
			$data['id'] = '';
			$data['creator_id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}

		function search_report_ticket_bygroup(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$flag_view = $_GET['flag_view'];
			if ($flag_view == 0) {
				$data['flag_view_detail'] = 0;
				$data['id'] = '';
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_report_bygroup(1,'');
			}
			else {
				$id = $_GET['id'];
				$data['id'] = $id;
				$data['flag_view_detail'] = 4;
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_report_bygroup(2,$id);
			}
			$data['startdate'] = '';
			$data['enddate'] = '';
			$data['activity_code'] = '';
			$data['creator_id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}
		
		function search_report_ticket_bycode_and_date(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$activity_code = $_GET['activity_code'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_bycode_and_date(1,$text_startDate,$text_endDate,$activity_code);
			$data['flag_view_detail'] = 3;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['activity_code'] = $activity_code;
			$data['id'] = '';
			$data['creator_id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}

		function search_report_ticket_byactivity_and_id(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$activity_code = $_GET['activity_code'];
			$id = $_GET['id'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->search_report_ticket_byactivity_and_id(1,$activity_code,$id);
			$data['flag_view_detail'] = 6;
			$data['startdate'] = '';
			$data['enddate'] = '';
			$data['activity_code'] = $activity_code;
			$data['id'] = $id;
			$data['creator_id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}

		function search_report_ticket_bydate_and_id(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$id = $_GET['id'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_bydate_and_id(1,$text_startDate,$text_endDate,$id);
			$data['flag_view_detail'] = 7;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['activity_code'] = '';
			$data['id'] = $id;
			$data['creator_id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}

		function search_report_ticket_byall(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$id = $_GET['id'];
			$activity_code = $_GET['activity_code'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_byall(1,$text_startDate,$text_endDate,$activity_code,$id);
			$data['flag_view_detail'] = 8;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['activity_code'] = $activity_code;
			$data['id'] = $id;
			$data['creator_id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}

		function search_report_ticket_byuser(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$flag_view = $_GET['flag_view'];
			if ($flag_view == 0) {
				$data['flag_view_detail'] = 0;
				$data['creator_id'] = '';
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_report_byuser(1,'');
			}
			else {
				$creator_id = $_GET['creator_id'];
				$data['creator_id'] = $creator_id;
				$data['flag_view_detail'] = 5;
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_report_byuser(2,$creator_id);
			}
			$data['startdate'] = '';
			$data['enddate'] = '';
			$data['activity_code'] = '';
			$data['id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}

		function search_report_ticket_byuser_and_activity(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$activity_code = $_GET['activity_code'];
			$creator_id = $_GET['creator_id'];
			$data['creator_id'] = $creator_id;
			$data['activity_code'] = $activity_code;
			$data['flag_view_detail'] = 9;
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_report_byuser_and_activity(1,$creator_id,$activity_code);
			$data['startdate'] = '';
			$data['enddate'] = '';
			$data['id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}

		function search_report_ticket_byuser_and_date(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$creator_id = $_GET['creator_id'];
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$data['creator_id'] = $creator_id;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['flag_view_detail'] = 10;
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_report_byuser_and_date(1,$text_startDate,$text_endDate,$creator_id);
			$data['activity_code'] = '';
			$data['id'] = '';
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}
		
		function search_report_ticket_byuser_all(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$creator_id = $_GET['creator_id'];
			$activity_code = $_GET['activity_code'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_byuser_all(1,$text_startDate,$text_endDate,$activity_code,$creator_id);
			$data['flag_view_detail'] = 11;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['activity_code'] = $activity_code;
			$data['id'] = '';
			$data['creator_id'] = $creator_id;
			$data['level'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}


		function search_report_ticket_bylevel(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$level = $_GET['level'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_bylevel(1,$level);
			$data['flag_view_detail'] = 12;
			$data['level'] = $level;
			$data['startdate'] = '';
			$data['enddate'] = '';
			$data['activity_code'] = '';
			$data['id'] = '';
			$data['creator_id'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}
		function search_report_ticket_bylevel_and_date(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$level = $_GET['level'];
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_bylevel_and_date(1,$text_startDate,$text_endDate,$level);
			$data['flag_view_detail'] = 13;
			$data['level'] = $level;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['activity_code'] = '';
			$data['id'] = '';
			$data['creator_id'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}
		function search_report_ticket_bylevel_and_group(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$level = $_GET['level'];
			$id = $_GET['id'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_bylevel_and_group(1,$level,$id);
			$data['flag_view_detail'] = 14;
			$data['level'] = $level;
			$data['startdate'] = '';
			$data['enddate'] = '';
			$data['activity_code'] = '';
			$data['id'] = $id;
			$data['creator_id'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}
		function search_report_ticket_bylevel_and_activity(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$level = $_GET['level'];
			$activity_code = $_GET['activity_code'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_bylevel_and_activity(1,$level,$activity_code);
			$data['flag_view_detail'] = 15;
			$data['level'] = $level;
			$data['startdate'] = '';
			$data['enddate'] = '';
			$data['activity_code'] = $activity_code;
			$data['id'] = '';
			$data['creator_id'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}
		function search_report_ticket_bydate_level_and_activity(){
			$data['activity_type'] = $this->mdl_manage_ticket_template->get_by_parent_to_ticket();
			$data['user_group'] = $this->mdl_manage_ticket_template->get_user_group();
			$data['user_name'] = $this->mdl_manage_ticket_template->get_user_name();
			$data['user_level'] = $this->mdl_manage_ticket_template->get_user_level();
			$level = $_GET['level'];
			$activity_code = $_GET['activity_code'];
			$text_startDate = $_GET['text_startDate'];
			$text_endDate = $_GET['text_endDate'];
			$data['list_report_ticket'] = $this->mdl_manage_ticket_template->report_ticket_search_bydate_level_and_activity(1,$text_startDate,$text_endDate,$level,$activity_code);
			$data['flag_view_detail'] = 16;
			$data['level'] = $level;
			$data['startdate'] = $text_startDate;
			$data['enddate'] = $text_endDate;
			$data['activity_code'] = $activity_code;
			$data['id'] = '';
			$data['creator_id'] = '';
			$this->cso_template->view("admin/view_ticket_report", $data, "admin/admin_template");
		}

		function view_report_ticket_detail(){
			$flag_view_detail = $_GET['flag_view_detail'];
			$status = $_GET['status'];
			$substatus = $_GET['substatus'];
			
			if ($flag_view_detail == 0) {
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(1,'','',$status,$substatus,'','','','');
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = '';
				$data['id'] = '';
				$data['creator_id'] = '';
				$data['level'] = '';
			}
			else if ($flag_view_detail == 1){
				$activity_code = $_GET['activity_code'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(2,'','',$status,$substatus,$activity_code,'','','');
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = $activity_code;
				$data['id'] = '';
				$data['creator_id'] = '';
				$data['level'] = '';
			}
			else if ($flag_view_detail == 2){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(3,$text_startDate,$text_endDate,$status,$substatus,'','','','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = '';
				$data['id'] = '';
				$data['creator_id'] = '';
				$data['level'] = '';
			}
			else if ($flag_view_detail == 3){
				$activity_code = $_GET['activity_code'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(4,$text_startDate,$text_endDate,$status,$substatus,$activity_code,'','','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = $activity_code;
				$data['id'] = '';
				$data['creator_id'] = '';
				$data['level'] = '';
			} 
			else if ($flag_view_detail == 4){
				$id = $_GET['id'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(5,'','',$status,$substatus,'',$id,'','');
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = '';
				$data['id'] = $id;
				$data['creator_id'] = '';
				$data['level'] = '';
			}
			else if ($flag_view_detail == 5){
				$creator_id = $_GET['creator_id'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(6,'','',$status,$substatus,'','',$creator_id,'');
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = '';
				$data['id'] = '';
				$data['creator_id'] = $creator_id;
				$data['level'] = '';
			}
			else if ($flag_view_detail == 6){
				$activity_code = $_GET['activity_code'];
				$id = $_GET['id'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(7,'','',$status,$substatus,$activity_code,$id,'','');
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = $activity_code;
				$data['id'] = $id;
				$data['creator_id'] = '';
				$data['level'] = '';
			}
			else if ($flag_view_detail == 7){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$id = $_GET['id'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(8,$text_startDate,$text_endDate,$status,$substatus,'',$id,'','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = '';
				$data['id'] = $id;
				$data['creator_id'] = '';
				$data['level'] = '';
			}
			else if ($flag_view_detail == 8){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$id = $_GET['id'];
				$activity_code = $_GET['activity_code'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(9,$text_startDate,$text_endDate,$status,$substatus,$activity_code,$id,'','');
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = $activity_code;
				$data['id'] = $id;
				$data['creator_id'] = '';
				$data['level'] = '';
			}
			else if ($flag_view_detail == 9){
				$creator_id = $_GET['creator_id'];
				$activity_code = $_GET['activity_code'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(10,'','',$status,$substatus,$activity_code,'',$creator_id,'');
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = $activity_code;
				$data['id'] = '';
				$data['creator_id'] = $creator_id;
				$data['level'] = '';
			}
			else if ($flag_view_detail == 10){
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(11,$text_startDate,$text_endDate,$status,$substatus,'','',$creator_id,'');
				$data['activity_code'] = '';
				$data['id'] = '';
				$data['creator_id'] = $creator_id;
				$data['level'] = '';
			}
			else if ($flag_view_detail == 11){
				$activity_code = $_GET['activity_code'];
				$creator_id = $_GET['creator_id'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['activity_code'] = $activity_code;
				$data['creator_id'] = $creator_id;
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(12,$text_startDate,$text_endDate,$status,$substatus,$activity_code,'',$creator_id,'');
				$data['id'] = '';
				$data['level'] = '';
			}
			else if ($flag_view_detail == 12){
				$level = $_GET['level'];
				$data['level'] = $level;
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(13,'','',$status,$substatus,'','','',$level);
				$data['id'] = '';
				$data['startdate'] = '';
				$data['enddate'] = '';
				$data['activity_code'] = '';
				$data['id'] = '';
				$data['creator_id'] = '';
			}
			else if ($flag_view_detail == 13){
				$level = $_GET['level'];
				$data['level'] = $level;
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(14,$text_startDate,$text_endDate,$status,$substatus,'','','',$level);
				$data['id'] = '';
				$data['activity_code'] = '';
				$data['id'] = '';
				$data['creator_id'] = '';
			}
			else if ($flag_view_detail == 14){
				$level = $_GET['level'];
				$id = $_GET['id'];
				$data['level'] = $level;
				$data['id'] = $id;
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(15,'','',$status,$substatus,'',$id,'',$level);
				$data['activity_code'] = '';
				$data['creator_id'] = '';
				$data['startdate'] = '';
				$data['enddate'] = '';
			}
			else if ($flag_view_detail == 15){
				$level = $_GET['level'];
				$activity_code = $_GET['activity_code'];
				$data['level'] = $level;
				$data['activity_code'] = $activity_code;
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(16,'','',$status,$substatus,$activity_code,'','',$level);
				$data['id'] = '';
				$data['creator_id'] = '';
				$data['startdate'] = '';
				$data['enddate'] = '';
			}
			else if ($flag_view_detail == 16){
				$level = $_GET['level'];
				$activity_code = $_GET['activity_code'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['level'] = $level;
				$data['activity_code'] = $activity_code;
				$data['startdate'] = $text_startDate;
				$data['enddate'] = $text_endDate;
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(17,$text_startDate,$text_endDate,$status,$substatus,$activity_code,'','',$level);
				$data['id'] = '';
				$data['creator_id'] = '';
			}
			$data['flag_view_detail'] = $flag_view_detail;
			$data['status'] = $status;
			$data['substatus'] = $substatus;
			$this->cso_template->view("admin/view_ticket_report_detail", $data, "admin/admin_template");
		}
		
		function view_report_ticket_toexcel(){
			$flag_view_detail = $_GET['flag_view_detail'];
			$status = $_GET['status'];
			$substatus = $_GET['substatus'];
			
			if ($flag_view_detail == 0) {
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(1,'','',$status,$substatus,'','','','');
			}
			else if ($flag_view_detail == 1){
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(2,'','',$status,$substatus,$activity_code,'','','');
			}
			else if ($flag_view_detail == 2){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(3,$text_startDate,$text_endDate,$status,$substatus,'','','','');
			}
			else if ($flag_view_detail == 3){
				$activity_code = $_GET['activity_code'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(4,$text_startDate,$text_endDate,$status,$substatus,$activity_code,'','','');
			}
			else if ($flag_view_detail == 4){
				$id = $_GET['id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(5,'','',$status,$substatus,'',$id,'','');
			}
			else if ($flag_view_detail == 5){
				$creator_id = $_GET['creator_id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(6,'','',$status,$substatus,'','',$creator_id,'');
			}
			else if ($flag_view_detail == 6){
				$activity_code = $_GET['activity_code'];
				$id = $_GET['id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(7,'','',$status,$substatus,$activity_code,$id,'','');
			}
			else if ($flag_view_detail == 7){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$id = $_GET['id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(8,$text_startDate,$text_endDate,$status,$substatus,'',$id,'','');
			}
			else if ($flag_view_detail == 8){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$id = $_GET['id'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(9,$text_startDate,$text_endDate,$status,$substatus,$activity_code,$id,'','');
			}
			else if ($flag_view_detail == 9){
				$creator_id = $_GET['creator_id'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(10,'','',$status,$substatus,$activity_code,'',$creator_id,'');
			}
			else if ($flag_view_detail == 10){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$creator_id = $_GET['creator_id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(11,$text_startDate,$text_endDate,$status,$substatus,'','',$creator_id,'');
			}
			else if ($flag_view_detail == 11){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$creator_id = $_GET['creator_id'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(12,$text_startDate,$text_endDate,$status,$substatus,$activity_code,'',$creator_id,'');
			}
			else if ($flag_view_detail == 12){
				$level = $_GET['level'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(13,'','',$status,$substatus,'','','',$level);
			}
			else if ($flag_view_detail == 13){
				$level = $_GET['level'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(14,$text_startDate,$text_endDate,$status,$substatus,'','','',$level);
			}
			else if ($flag_view_detail == 14){
				$level = $_GET['level'];
				$id = $_GET['id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(15,'','',$status,$substatus,'',$id,'',$level);
			}
			else if ($flag_view_detail == 15){
				$level = $_GET['level'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(16,'','',$status,$substatus,$activity_code,'','',$level);
			}
			else if ($flag_view_detail == 16){
				$level = $_GET['level'];
				$activity_code = $_GET['activity_code'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail(17,$text_startDate,$text_endDate,$status,$substatus,$activity_code,'','',$level);
			}
			$this->load->view('admin/view_ticket_report_toexcel', $data);
		}
// M60
		
	function view_report_ticket_activity(){
			$flag_view = $_GET['flag_view'];
			if ($flag_view == 0) {
				$data['flag_view_detail'] = 0;
				$data['date'] = '';
				$data['activity_code'] = '';
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_activity_report(1,'','');
			}
			else if ($flag_view == 1) {
				$date = $_GET['text_Date'];
				$data['flag_view_detail'] = 1;
				$data['date'] = $date;
				$data['activity_code'] = '';
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_activity_report(2,'',$date);
			}
			else if ($flag_view == 2) {
				$activity_code = $_GET['activity_code'];
				$data['flag_view_detail'] = 2;
				$data['date'] = '';
				$data['activity_code'] = $activity_code;
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_activity_report(3,$activity_code,'');
			}
			else if ($flag_view == 3) {
				$date = $_GET['text_Date'];
				$activity_code = $_GET['activity_code'];
				$data['flag_view_detail'] = 3;
				$data['date'] = $date;
				$data['activity_code'] = $activity_code;
				$data['list_report_ticket'] = $this->mdl_manage_ticket_template->get_ticket_activity_report(4,$activity_code,'');
			}
			$this->cso_template->view("admin/view_ticket_activity_report", $data, "admin/admin_template");
		}
		
		function view_report_ticket_activity_detail(){
			$flag_view_detail = $_GET['flag_view_detail'];
			$activity_id = $_GET['activity_id'];
			if ($flag_view_detail == 0) {
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_activity_report_detail(1,'',$activity_id);
				$data['date'] = '';
			}
			else if ($flag_view_detail == 1){
				$date = $_GET['date'];
				$data['list_report_ticket_detail'] = $this->mdl_manage_ticket_template->get_ticket_activity_report_detail(2,$date,$activity_id);
				$data['date'] = $date;
			
			}
			$data['activity_id'] = $activity_id;
			$data['flag_view_detail'] = $flag_view_detail;
			$this->cso_template->view("admin/view_ticket_activity_report_detail", $data, "admin/admin_template");
		}
		
		function view_report_ticket_activity_toexcel(){
			$flag_view_detail = $_GET['flag_view_detail'];
			$activity_id = $_GET['activity_id'];
			
			if ($flag_view_detail == 0) {
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_activity_report_detail(1,'',$activity_id);
			}
			else if ($flag_view_detail == 1){
				$date = $_GET['date'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_activity_report_detail(2,$date,$activity_id);
			}
			$this->load->view('admin/view_ticket_activity_report_toexcel', $data);
		}
		
		function view_report_ticket_activity_toexcel_summary(){
			$flag_view = $_GET['flag_view'];
			if ($flag_view == 0) {
				$data['flag_view_detail'] = 0;
				$data['date'] = '';
				$data['activity_code'] = '';
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_activity_report(1,'','');
			}
			else if ($flag_view == 1) {
				$date = $_GET['text_Date'];
				$data['flag_view_detail'] = 1;
				$data['date'] = $date;
				$data['activity_code'] = '';
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_activity_report(2,'',$date);
			}
			else if ($flag_view == 2) {
				$activity_code = $_GET['activity_code'];
				$data['flag_view_detail'] = 2;
				$data['date'] = '';
				$data['activity_code'] = $activity_code;
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_activity_report(3,$activity_code,'');
			}
			else if ($flag_view == 3) {
				$date = $_GET['text_Date'];
				$activity_code = $_GET['activity_code'];
				$data['flag_view_detail'] = 3;
				$data['date'] = $date;
				$data['activity_code'] = $activity_code;
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_activity_report(4,$activity_code,'');
			}
			$this->load->view('admin/view_ticket_activity_summary_report_toexcel', $data);	
		}
		
		// M62
		function view_report_all_ticket_toexcel(){
			$flag_view_detail = $_GET['flag_view_detail'];
			
			if ($flag_view_detail == 0) {
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(1,'','','','','','');
			}
			else if ($flag_view_detail == 1){
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(2,'','',$activity_code,'','','');
			}
			else if ($flag_view_detail == 2){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(3,$text_startDate,$text_endDate,'','','','');
			}
			else if ($flag_view_detail == 3){
				$activity_code = $_GET['activity_code'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(4,$text_startDate,$text_endDate,$activity_code,'','','');
			}
			else if ($flag_view_detail == 4){
				$id = $_GET['id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(5,'','','',$id,'','');
			}
			else if ($flag_view_detail == 5){
				$creator_id = $_GET['creator_id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(6,'','','','',$creator_id,'');
			}
			else if ($flag_view_detail == 6){
				$activity_code = $_GET['activity_code'];
				$id = $_GET['id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(7,'','',$activity_code,$id,'','');
			}
			else if ($flag_view_detail == 7){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$id = $_GET['id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(8,$text_startDate,$text_endDate,'',$id,'','');
			}
			else if ($flag_view_detail == 8){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$id = $_GET['id'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(9,$text_startDate,$text_endDate,$activity_code,$id,'','');
			}
			else if ($flag_view_detail == 9){
				$creator_id = $_GET['creator_id'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(10,'','',$activity_code,'',$creator_id,'');
			}
			else if ($flag_view_detail == 10){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$creator_id = $_GET['creator_id'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(11,$text_startDate,$text_endDate,'','',$creator_id,'');
			}
			else if ($flag_view_detail == 11){
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$creator_id = $_GET['creator_id'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(12,$text_startDate,$text_endDate,$activity_code,'',$creator_id,'');
			}
			else if ($flag_view_detail == 12){
				$level = $_GET['level'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(13,'','',$activity_code,'','',$level);
			}
			else if ($flag_view_detail == 13){
				$level = $_GET['level'];
				$text_startDate = $_GET['startdate'];
				$text_endDate = $_GET['enddate'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(14,$text_startDate,$text_endDate,$activity_code,'','',$level);
			}
			else if ($flag_view_detail == 14){
				$level = $_GET['level'];
				$id = $_GET['id'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(15,'','',$activity_code,$id,'',$level);
			}
			else if ($flag_view_detail == 15){
				$level = $_GET['level'];
				$activity_code = $_GET['activity_code'];
				$data['hasil'] = $this->mdl_manage_ticket_template->get_ticket_report_detail2(16,'','',$activity_code,'','',$level);
			}
			$this->load->view('admin/view_ticket_report_toexcel', $data);
		}
		// M62
}
?>

