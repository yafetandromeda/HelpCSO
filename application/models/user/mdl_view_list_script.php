<!-- M021 - YA - Search Script Option -->
<?php
class Mdl_view_list_script extends CI_Model{	

	
	function get_all_script()
	{	$sqlquery = "Select script_id,question 
					 from helpcso_script  
					 LIMIT 10";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	
	function search_script($flag,$text_search_script,$category_id='')
	{
		$arrkeyword = explode(" ", $text_search_script);
		$keywords = implode("%", $arrkeyword);
		
		$category_sqlquery = "";
		if ($category_id != ""){
			$category_sqlquery = "and (category_id = " . $category_id . " or tracking_category like '%" . $category_id . ";%')";
		}
		
		if ($flag == '1' || $flag == '2'){
			$limit = "LIMIT 4";
			}
		else if ($flag == '3'){
			$limit = "";
			}
		$sqlquery = "Select script_id,question 
					 from helpcso_script
					 where 
					 	(question like '%" . $keywords . "%' 
						 or tag like '%" . $keywords . "%' 
					 	)
						" . $category_sqlquery . "
					 order by count_view desc"; // M021
					 
		$query = $this->db->query($sqlquery);
		if($flag == '1'||$flag == '3') {
			return $query->result();
		}
		elseif ($flag == '2'){
			return $query->num_rows();
		}
	}
	
	function top_scripts($limit){
		$sqlquery = "select script_id, question
					 from helpcso_script
					 order by count_view desc
					 limit " . $limit;
		$query = $this->db->query($sqlquery);
		return $query->result();
	}
	function view_script($script_id){
		$sqlquery = "select script_id, question, REPLACE(answer, '\n', '<br />') as answer, tag, helpcso_script.category_id, category
					 from helpcso_script inner join mst_category on helpcso_script.category_id = mst_category.code_id
					 where script_id = " . $script_id;
		$query = $this->db->query($sqlquery);
		return $query->result();
	}

	function view_script_by_activity_code($activity_code){
		$sqlquery = "select script_id, question, REPLACE(answer, '\n', '<br />') as answer, tag, helpcso_script.category_id, category
					 from helpcso_script inner join mst_category on helpcso_script.category_id = mst_category.code_id
					 where activity_code = " . $activity_code;
		$query = $this->db->query($sqlquery);
		return $query->result();
	}

	function view_no_script(){
		$sqlquery = "select script_id, question, REPLACE(answer, '\n', '<br />') as answer, tag, helpcso_script.category_id, category
					 from helpcso_script inner join mst_category on helpcso_script.category_id = mst_category.code_id
					 where activity_code = 1104020002";
		$query = $this->db->query($sqlquery);
		return $query->result();
	}

	function count_view($script_id){
		$sqlquery = "update helpcso_script set count_view = count_view + 1 where script_id = " . $script_id;
		$this->db->query($sqlquery);
	}
}
?>