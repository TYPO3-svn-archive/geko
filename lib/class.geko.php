<?php

require_once(t3lib_extMgm::extPath('geko','res/libs/doctrine/lib/Doctrine.php'));
define('DOCTRINE_PATH', t3lib_extMgm::extPath('geko', 'res/libs/doctrine/lib') ); //TEST!

spl_autoload_register(array('Doctrine', 'autoload'));
spl_autoload_register(array('Doctrine', 'modelsAutoload'));		

class Geko{
	public function __construct(){
		
		if($GLOBALS['doctrine_db'] == false){
			$GLOBALS['doctrine_db'] = Doctrine_Manager::connection(
				'mysql://'.TYPO3_db_username.":".TYPO3_db_password."@".
					TYPO3_db_host."/".TYPO3_db,"GekoConnection 1");
					
			if($GLOBALS['doctrine_db'] == false)
				throw new Exception("Doctrine connection error...");
		
			Doctrine_Manager::getInstance()->setAttribute('use_dql_callbacks', true);
			Doctrine_Manager::getInstance()->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);
			Doctrine_Manager::getInstance()->setAttribute('model_loading', 'conservative');
		};
	}
};

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/geko/class.geko.php"])
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/geko/class.geko.php"]);
