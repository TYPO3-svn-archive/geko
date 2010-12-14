<?php

class geko_controller extends tslib_pibase{

    var $prefixId      = null;	// 'tx_tendsical_pi1';
    var $scriptRelPath = null;	// 'pi1/class.tx_tendsical_pi1.php';
    var $extKey        = null; 	// 'tend_sical';
    var $fe_plugin	   = null;
    
    private $view	   = null;
    private $controller_name = null;
    private $action_name  = null;
    
    /*
    	geko_conroller extends tslib_pibase.
    */
	public function __construct($extKey=null){
		if($extKey == null)
			throw new geko_controller_exception("Missing extension key!");
	
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
	
		$this->extKey == (string)$extKey;
		
		if($this->prefixId==null)
			$this->prefixId = (string)get_class($this);
		
		if($this->fe_plugin==null)
			$this->fe_plugin = (string)get_class($this);
		
		$pom = (array)explode("_",$this->prefixId);
		
		if(count($pom)!=3)
			throw new geko_controller_exception("Controller must be in the right format!");	
		
		$this->scriptRelPath = $pom[count($pom)-1]."/class.".$this->prefixId.".php";
		
		$this->view = new geko_view();
		$this->view->set_action_name($this->action_name);
		$this->view->set_controller_name($pom[count($pom)-1]);	
		$this->view->set_extKey($this->extKey);
		$this->view->fe_plugin = $this->fe_plugin;
		
		$this->view->set_smarty(tx_smarty::smarty(array()));
		
		$this->geko = new Geko();
		$models_path = t3lib_extMgm::extPath($extKey, "app/models");
		Doctrine_Core::loadModels($models_path);
		
	}
	
	/*
		Return smarty object from current view handler
	*/
	public function get_smarty(){
		return $this->get_view()->get_smarty();
	}
	
	/*
		Return current view handler
	*/
	public function get_view(){
		return $this->view;
	}
	
	/*
		Assing variable to template
	*/
	public function assign($tpl_var, $value = null){
		$this->get_view()->typo3_action = $this->pi_getPageLink($GLOBALS["TSFE"]->id);
		$this->get_view()->LLkey = $this->LLkey;
	
		return call_user_func_array(
			array($this->get_view()->get_smarty(), "assign"),
			array($tpl_var,$value));
	}
	
	/*
		Render view
	*/
	public function render($view,$tpl_var=null){
		if($tpl_var!=null) $this->assign($tpl_var);
		return $this->get_view()->render_view($view);
	}
	
	/*
		Get url arguments for current plugin
	*/
	public function get_args($plugin=null){
		return t3lib_div::_GP($plugin==null?$this->prefixId:$plugin);
	}
	
	/*
		Require file
	*/
	public function load_file($file){
		$path = (t3lib_extMgm::siteRelPath($this->extKey).$file);

		if(!file_exists($path))
			throw new geko_controller_exception("File that you require is missing...");
		
		require_once($path);
	}
	
	/*
		Get flex form configuration
	*/
	public function get_conf_flex_form() {
        $this->pi_initPIflexForm();
        $ff_conf = array();
        $piFlexForm = $this->cObj->data['pi_flexform'];

        foreach ( $piFlexForm['data'] as $sheet => $data )
            foreach ( $data as $lang => $value )
                foreach ( $value as $key => $val )
                    $ff_conf[$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
                    
    	return (array)$ff_conf;
    }

};

class geko_controller_exception extends Exception{};

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/geko/class.geko_controller.php"])
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/geko/class.geko_controller.php"]);
