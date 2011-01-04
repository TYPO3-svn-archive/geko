<?php

class geko_smarty_helper{

	/*
		Wraper for typo3 form naming
	*/
	public static function form_open($params = false, $smarty = false){
		$geko_view = (array)$smarty->_tpl_vars["geko_view"];
		
		$default_params = array(
			"method" => "post",
			"name" => $geko_view["fe_plugin"],
			"action" => $geko_view["typo3_action"]);
		
		$assign_params = array_merge($default_params,(array)$params);
		
		return '<form id="'.$geko_view["fe_plugin"].'-'.$geko_view["action"].'"
		method="'.$assign_params["method"].'" name="'.$assign_params["name"].'" action="'.$assign_params["action"].'">'.PHP_EOL;
	}
	
	/*
		Wrapper for typo3 form field naming
	*/
	public static function form_input($params, $smarty = false){
		$geko_view = (array)$smarty->_tpl_vars["geko_view"];
		$name = trim($params["name"]);
		$prefixId = $geko_view["fe_plugin"];
		$multiple = isset($params["multiple"])?"[]":"";
		
		return sprintf('id="%s[%s]%s" name="%s[%s]%s" ',
			$prefixId,$name,$multiple,$prefixId,$name,$multiple);
	}
	
	/*
		Get Doctrine errors for field
	*/
	public static function input_errors($params, $smarty=false){
		$geko_view = (array)$smarty->_tpl_vars["geko_view"];
		$object = $smarty->_tpl_vars[trim($params["object"])];
		$field = $params["field"];
		$prefixId = $geko_view["fe_plugin"];
		
		$errors = (array)$object->getErrorStack()->get($params["field"]);
		
		if(count($errors)==0) return "";
		
		return '<span class="error">*</span>';
		
		/*
		foreach($errors as $error){
			echo smarty_block_translate(array("label"=>$error),$error);	
			exit;
		};
		*/
	}
	
	/*
		Return field but localized
	*/
	public static function local($params = false, $smarty = false){
		$geko_view = (array)$smarty->_tpl_vars["geko_view"];
		$LLkey = ($geko_view["LLkey"]=="default")?"":$geko_view["LLkey"];
		
		if( isset($params["obj"][$params["field"].$LLkey])){
			return (string)$params["obj"][$params["field"].$LLkey];
		} else {
			return (string)$params["obj"][$params["field"]];
		};
	}
	
	/*
		This helper is used for select component when you want to have selected item
	*/
	public static function selected($params = false, $smarty = false){
		if(isset($params["what"]) && isset($params["who"]))
			if($params["what"]==$params["who"])
				return 'selected="selected" ';	
	}
	

}