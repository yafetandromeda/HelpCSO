<!-- M006 - YA - Export Script to excel -->
<!-- M010 - YA - Import script -->
<!-- M025 - YA - Ubah Tampilan Script -->

<?php
class Mdl_manage_script extends CI_Model{	

		function __construct()
    	{
        	parent::__construct();
    	}

		function get_last_scriptid()
		{
			$sqlquery = "Select total_script from mst_count_data";
			return $this->db->query($sqlquery);
		}

		function get_last_id()
		{
			$q = $this->db->query("select MAX(script_id) as script_id from helpcso_script");
	        $code = 0;
	        if($q->num_rows()>0){
	            foreach($q->result() as $cd){
	                $tmp = ((int)$cd->script_id)+1;
	                $code = $tmp;
	            }
	        }else{
	            $code = "1";
	        }
	        return $code;
		}
// M025
		function get_all_script()
		{	$sqlquery = "Select
							script.script_id as script_id, 
						 	script.question as question, 
							REPLACE(script.answer, '\n', '<br />') as answer,
							script.count_view as count_view,
							script.category_id as category_id, 
							script.tag as tag,
							script.visibility as visibility,
							script.create_datetime as create_datetime,
							script.last_edited_datetime as last_edited_datetime,
							script.tracking_category as tracking_category,
							script.activity_code as activity_code
						 from helpcso_script script
						 inner join mst_category mst_category on script.category_id = mst_category.code_id";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
// M025
// M006
		function get_all_script_toexcel()
		// {	$sqlquery = "Select
		// 					script.script_id as script_id, 
		// 				 	script.question as question, 
		// 					REPLACE(REPLACE(REPLACE(REPLACE(script.answer, '<br />', ' '), '<p>', ''), '</p>', ''), '\r\n', '') as answer,
		// 					script.count_view as count_view,
		// 					script.count_reported as count_reported,
		// 					script.category_id as category_id, 
		// 					script.tag as tag,
		// 					script.visibility as visibility,
		// 					script.create_datetime as create_datetime,
		// 					script.last_edited_datetime as last_edited_datetime,
		// 					script.tracking_category as tracking_category
		// 				 from helpcso_script script
		// 				 inner join mst_category mst_category on script.category_id = mst_category.code_id";
			{
				$sqlquery= "Select activity.activity_code as activity_code,
						activity.parent_code as parent_code,
						activity.activity_description as activity_description,
						activity.activity_definition as activity_definition,
						activity.activity_level as activity_level,
						activity.status_active as status_active,
						REPLACE(answer, '<br />', '\n') as answer
						FROM helpcso_activity activity inner join helpcso_script on activity.activity_code = helpcso_script.activity_code";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
// M006
// M010
		function import_script($dataexcel, $user_id){
			for($i=0;$i<count($dataexcel);$i++){
	            $data = array(
	                'script_id'=>$dataexcel[$i]['script_id'],
	                'question'=>$dataexcel[$i]['question'],
	                'answer'=>$dataexcel[$i]['answer'],
					'count_view'=> 0,
					'count_reported'=> 0,
	                'tag'=>"",
	                'category_id'=>53,
	                'tracking_category'=>"",
					'user_create_id'=> $user_id,
	                'visibility'=>1,
					'create_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'activity_code'=>$dataexcel[$i]['activity_code']
	            );
				$this->db->insert('helpcso_script',$data);

				$data_temp = array(
	                'script_id'=>$dataexcel[$i]['script_id'],
	                'question'=>$dataexcel[$i]['question'],
	                'answer'=>$dataexcel[$i]['answer'],
	                'tag'=>"",
	                'category_id'=>53,
	                'tracking_category'=>"",
					'user_create_id'=> $user_id,
	                'visibility'=>1,
					'create_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'activity_code'=>$dataexcel[$i]['activity_code']
	            );
				$this->db->insert('helpcso_script_temp',$data_temp);

				$data_jumlah = array(
							'total_script'=> $dataexcel[$i]['script_id']
							);
				$this->db->update('mst_count_data',$data_jumlah);
	        }
		}

		function import_script_edit($dataexcel, $user_id){
			for($i=0;$i<count($dataexcel);$i++){
	            $data = array(
	                // 'script_id'=>$dataexcel[$i]['script_id'],
	                'question'=>$dataexcel[$i]['question'],
	                'answer'=>$dataexcel[$i]['answer'],
	                'tag'=>"",
	                'category_id'=>53,
	                'tracking_category'=>"",
					'user_create_id'=> $user_id,
	                'visibility'=>1,
					'create_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'activity_code'=>$dataexcel[$i]['activity_code']
	            );
	             $param = array(
	               'activity_code'=>$dataexcel[$i]['activity_code']
	            );
				$this->db->where($param);
				return $this->db->update('helpcso_script',$data);

				$data_temp = array(
	                // 'script_id'=>$dataexcel[$i]['script_id'],
	                'question'=>$dataexcel[$i]['question'],
	                'answer'=>$dataexcel[$i]['answer'],
	                'tag'=>"",
	                'category_id'=>53,
	                'tracking_category'=>"",
					'user_create_id'=> $user_id,
	                'visibility'=>1,
					'create_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
					'activity_code'=>$dataexcel[$i]['activity_code']
	            );
	            $param_temp = array(
	               'activity_code'=>$dataexcel[$i]['activity_code']
	            );
				$this->db->where($param_temp);
				return $this->db->update('helpcso_script_temp',$data_temp);
	        }
		}

        function cek_import($dataexcel){
	        for($i=0;$i<count($dataexcel);$i++){
	            $cek = array(
	                'activity_code'=>$dataexcel[$i]['activity_code']
	            );
			}
			$data = array();
			$this->db->select('activity_code');
			$this->db->where($cek);
			$this->db->limit(1);
			$Q = $this->db->get('helpcso_script');
			if($Q->num_rows() > 0){
				$data = $Q->row_array();
			}
			$Q->free_result();
			return $data;
		}

		function cek_act_code($dataexcel){
	        for($i=0;$i<count($dataexcel);$i++){
	            $cek = array(
	                'activity_code'=>$dataexcel[$i]['activity_code']
	            );
			}
			$data = array();
			$this->db->select('activity_code');
			$this->db->where($cek);
			$this->db->limit(1);
			$Q = $this->db->get('helpcso_activity');
			if($Q->num_rows() > 0){
				$data = $Q->row_array();
			}
			$Q->free_result();
			return $data;
		}
// M010
		function get_pil_category()
		{
			$sqlquery = "Select * from mst_category where record_flag = '1'";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
		
		function get_pil_category_1()
		{
			$sqlquery = "Select * from mst_category where level = '1' and record_flag = '1'";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
	
		function par_category_get($level,$code_id)
		{
			$sqlquery = "Select * 
						 from mst_category 
						 where level = '" .$level."' 
						 	   and record_flag = '1'
							   and code_id <> '".$code_id."'";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
		
		function pil_level_script_category()
		{
			$sqlquery = "Select * from mst_level_script_category";
			$query = $this->db->query($sqlquery);
			return $query->result();
		}
		
		function add_script($script_id,$question,$answer,$tag,$category_id,$user_id,$visibility,$tracking_category)
		{
			$data = array(
						'script_id'=>$script_id,
						'question'=>$question,
						'answer'=> $answer,
						'count_view'=> 0,
						'count_reported'=> 0,
						'tag'=> $tag,
						'category_id'=> $category_id,
						'tracking_category'=> $tracking_category,
						'user_create_id'=> $user_id,
						'visibility'=> $visibility,
						'create_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
						'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)
						);
			$this->db->insert('helpcso_script',$data);
			
			$data_temp = array(
						'script_id'=>$script_id,
						'question'=>$question,
						'answer'=> $answer,
						'tag'=> $tag,
						'category_id'=> $category_id,
						'tracking_category'=> $tracking_category,
						'user_create_id'=> $user_id,
						'visibility'=> $visibility,
						'create_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
						'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60)
						);
			$this->db->insert('helpcso_script_temp',$data_temp);
			
			$data_jumlah = array(
							'total_script'=>$script_id
							);
			$this->db->update('mst_count_data',$data_jumlah);
		}
		
		function search_script($flag_query,$flag_number,$text_search_script)
		{
			if ($flag_query == 1){
			$sqlquery = "Select 
							script.script_id as script_id, 
						 	script.question as question, 
							script.answer as answer, 
							script.count_view as count_view,
							script.category_id as category_id, 
							script.tag as tag,
							script.visibility as visibility,
							script.create_datetime as create_datetime,
							script.last_edited_datetime as last_edited_datetime,
							script.tracking_category as tracking_category
						 from helpcso_script script
						 inner join mst_category mst_category on script.category_id = mst_category.code_id
						 where (script.question like '%".str_replace(" ", "%", $text_search_script)."%' 
						 		or script.tag like '%".str_replace(" ", "%", $text_search_script)."%')";
			}
			else if ($flag_query == 2){
			$sqlquery = "Select 
							script.script_id as script_id, 
						 	script.question as question, 
							script.answer as answer, 
							script.count_view as count_view,
							script.category_id as category_id, 
							script.tag as tag,
							script.visibility as visibility,
							script.create_datetime as create_datetime,
							script.last_edited_datetime as last_edited_datetime,
							script.tracking_category as tracking_category
						 from helpcso_script script
						 inner join mst_category mst_category on script.category_id = mst_category.code_id
						 where (script.question like '%".str_replace(" ", "%", $text_search_script)."%' 
						 		or script.tag like '%".str_replace(" ", "%", $text_search_script)."%') 
						 group by script.question LIMIT 5";
			}
			$query = $this->db->query($sqlquery);
			if($flag_number == 1) {
				return $query->result();
			}
			elseif ($flag_number == 2){
				return $query->num_rows();
			}
		}
		
		function search_script_bydate($flag,$flag_query,$startDate,$endDate,$text_search_script)
		{
			if ($flag_query == 1){
			$sqlquery = "Select 
							script.script_id as script_id, 
						 	script.question as question, 
							script.answer as answer, 
							script.count_view as count_view,
							script.category_id as category_id, 
							script.tag as tag,
							script.visibility as visibility,
							script.create_datetime as create_datetime,
							script.last_edited_datetime as last_edited_datetime,
							script.tracking_category as tracking_category
						 from helpcso_script script
						 inner join mst_category mst_category on script.category_id = mst_category.code_id
						 where script.create_datetime between '".$startDate." 00:00:00' and '".$endDate." 23:59:59'
						 order by script_id ASC";
			}
			else if ($flag_query == 2){
			$sqlquery = "Select 
							script.script_id as script_id, 
						 	script.question as question, 
							script.answer as answer, 
							script.count_view as count_view,
							script.category_id as category_id, 
							script.tag as tag,
							script.visibility as visibility,
							script.create_datetime as create_datetime,
							script.last_edited_datetime as last_edited_datetime,
							script.tracking_category as tracking_category
						 from helpcso_script script
						 inner join mst_category mst_category on script.category_id = mst_category.code_id
						 where (script.question like '%".str_replace(" ", "%", $text_search_script)."%' 
						 		or script.tag like '%".str_replace(" ", "%", $text_search_script)."%') 
						 		and script.create_datetime between '".$startDate." 00:00:00' and '".$endDate." 23:59:59'
						 order by script_id ASC";
			}
			$query = $this->db->query($sqlquery);
			if($flag == '1'){
				return $query->result();
			}
			elseif ($flag == '2'){
				return $query->num_rows();
			}
		}
		
		function save_edited_script($script_id,$question,$answer,$category_id,$tag,$visibility,$tracking_category)
		{	
			$data_edit = array(
						'question'=>$question,
						'answer'=>$answer,
						'category_id'=>$category_id,
						'tag'=>$tag,
						'visibility'=> $visibility,
						'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
						'tracking_category'=>$tracking_category
						);
			$this->db->where('script_id',$script_id);
			$this->db->update('helpcso_script',$data_edit);
			
			$data_edit_temp = array(
						'question'=>$question,
						'answer'=>$answer,
						'category_id'=>$category_id,
						'tag'=>$tag,
						'visibility'=> $visibility,
						'last_edited_datetime'=> gmdate("Y-m-d H:i:s", time() + 7 * 60 * 60),
						'tracking_category'=>$tracking_category
						);
			$this->db->where('script_id',$script_id);
			$this->db->update('helpcso_script_temp',$data_edit_temp);
		}
		
		function category_get_all(){
			$query = $this->db->query("select code_id, category,category_code, level, parent_id,
									  (select category from mst_category cat2 where cat2.code_id = cat1.parent_id) as category_parent
									   from mst_category cat1 where record_flag = '1'");
			return $query->result();
		}
		
		
		function get_pil_subcategory($flag,$parent_id){
			$query = $this->db->query("select code_id, category, level, parent_id
									   from mst_category where parent_id = '".$parent_id."' and record_flag = '1'");
			if ($flag == 1){
				return $query->result();
			}
			else if ($flag == 2){
				return $query;
			}
		}
		function check_child_category($flag,$code_id){
			$query = $this->db->query("select category
									   from mst_category where parent_id = '".$code_id."' and record_flag = '1' LIMIT 1");
			if ($flag == 1){
				return $query;
			}
			else if ($flag == 2){
				return $query->num_rows();
			}
		}
		
		function check_tracking_category($flag,$code_id){
			$query = $this->db->query("select script_id,question
									   from helpcso_script where tracking_category like '%".$code_id."%' LIMIT 1");
			if ($flag == 1){
				return $query;
			}
			else if ($flag == 2){
				return $query->num_rows();
			}
		}
		
		function category_last_id(){
			$sqlquery = "select max(code_id) as last_id from mst_category";
			$query = $this->db->query($sqlquery);
			$result = $query->result();
			return $result[0]->last_id;
		}
		function category_add($cat_data){
			$cat_data['code_id'] = $this->category_last_id() + 1;
			$cat_data['record_flag'] = "1";
			$this->db->insert('mst_category', $cat_data);
		}
		function category_update($code_id, $cat_data){
			$this->db->update('mst_category', $cat_data, 'code_id = ' . $code_id);
		}
		function category_delete($code_id){
			$this->db->update('mst_category', array("record_flag" => "0"), 'code_id = ' . $code_id);
		}
		function category_search($flag_query,$flag,$keyword){
		if ($flag_query == 1){
			$query = $this->db->query("select code_id, category,category_code, level, parent_id,
									  (select category from mst_category cat2 where cat2.code_id = cat1.parent_id) as category_parent
									  from mst_category cat1
									  where record_flag = '1' and category like '%" . str_replace(" ", "%", $keyword) . "%'");
		}
		else if ($flag_query == 2){
			$query = $this->db->query("select code_id, category,category_code, level, parent_id,
									  (select category from mst_category cat2 where cat2.code_id = cat1.parent_id) as category_parent
									  from mst_category cat1
									  where record_flag = '1' and category like '%" . str_replace(" ", "%", $keyword) . "%' LIMIT 5");
		}
		if ($flag == 1){
			return $query->result();
		}
		else {
			return $query->num_rows();
		}
	}
}
?>