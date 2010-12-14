<?php

class geko_view {

	private $format = "html";
	private $controller = null;
	private $action = null;
	private $extKey = null;
	private $smarty = null;
	public $fe_plugin = null;
	public $typo3_action = null;
	
	public function __construct(){
		return $this;
	}

	public function set_extKey($value){
		$this->extKey = (string)$value;
	}

	public function set_smarty($smarty){
		$this->smarty = $smarty;
	}
	
	public function get_smarty(){
		return $this->smarty;
	}

	/*
		Renders view 
	*/
	public function render_view($action=null){
		if($action != null)
			$this->set_action_name((string)$action);
		
		if($this->get_controller_name() == null)
			throw new geko_exception_view("Missing controller name!");
		
		if($this->get_action_name() == null)
			throw new geko_exception_view("Missing action name!");
			
		$this->get_smarty()->setSmartyVar('template_dir',
			sprintf('EXT:%s/app/views/%s/',$this->extKey,$this->controller));
					
		$file = sprintf("%s.%s",$this->action,$this->format);
	
		/* Geko view information */
		$this->get_smarty()->assign("geko_view",array(
			"format" => $this->format,
			"controller" => $this->controller,
			"action" => $this->action,
			"fe_plugin" => $this->fe_plugin,
			"typo3_action" => $this->typo3_action,
			"LLkey" => $this->LLkey,
			"extKey" => $this->extKey));
	
		// Register functions
		$this->get_smarty()->register_function("form_open","geko_smarty_helper::form_open");
		$this->get_smarty()->register_function("form_input","geko_smarty_helper::form_input");
		$this->get_smarty()->register_function("local","geko_smarty_helper::local");
		$this->get_smarty()->register_function("selected","geko_smarty_helper::selected");

		return $this->get_smarty()->display($file);
	}
	
	/*
		Return controller name
	*/
	public function get_controller_name(){
		return $this->controller;
	}
	
	/*
		Get Action name 
	*/
	public function get_action_name(){
		return $this->action;
	}
	
	/*
		Set controller name
	*/
	public function set_controller_name($value){
		$this->controller = (string)$value;
	}
	
	/*
		Set Action name
	*/
	public function set_action_name($value){
		$this->action = (string)$value;
	}
	
	/*
		Add JavaScript to header
	*/
	public function add_javascript($file,$key=false){
        $GLOBALS['TSFE']->additionalHeaderData[$this->extKey."_".$this->fe_plugin.$key."_js"] =
			sprintf('<script src="%s" language="javascript" type="text/javascript"></script> ',$file);
    
	}
	
	public function add_javascript_tmp($file){
		$file = t3lib_extMgm::siteRelPath("tend_sical")."/app/res/js/".$file;
		return $this->add_javascript(TSpagegen::inline2TempFile(file_get_contents($file), 'js'));
	}
	
	/*
		Add StyleSheet to header
	*/
	public function add_stylesheet($file){
		$GLOBALS['TSFE']->additionalHeaderData[$this->extKey."_".$this->fe_plugin."_css"] =
			'<link href="'.$file.'" type="text/css" rel="stylesheet"/>';	
	}
	
	public function add_stylesheet_tmp($file){
		$file = t3lib_extMgm::siteRelPath("tend_sical")."/app/res/css/".$file;
		return $this->add_stylesheet(TSpagegen::inline2TempFile(file_get_contents($file), 'css'));
	}
};

class geko_exception_view extends Exception{};

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/geko/class.geko_view.php"])
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/geko/class.geko_view.php"]);
