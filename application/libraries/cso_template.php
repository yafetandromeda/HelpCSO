<?php
class Cso_template {
	var $templateFile;
	var $parentTemplate;
	function __construct($param = array()){
		if (!empty($param))
			$this->templateFile = $param['templateFile'];		
	}
	function setTemplateFile($templateFile){
		$this->templateFile = $templateFile;
	}
	function view($filename, $data = array(), $templateFile = ''){
		$ci = &get_instance();
		$template = array();
		$template["data"] = $data;
		$template["filename"] = $filename;
		if ($templateFile != ''){
			$usedTemplate = $templateFile;
		}
		else $usedTemplate = $this->templateFile;
		$ci->load->view($usedTemplate, $template); 
	}
}
?>