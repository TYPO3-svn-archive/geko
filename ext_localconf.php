<?php

if (!defined ('TYPO3_MODE'))
	die ('Access denied.');

# $TYPO3_CONF_VARS['FE']['eID_include'][$_EXTKEY] = sprintf('EXT:%s/%s',$_EXTKEY,"tend_sical_eidws.php");


require_once(t3lib_extMgm::extPath($_EXTKEY)."lib/class.geko.php");

foreach(glob(t3lib_extMgm::extPath($_EXTKEY)."lib/class.*.php") as $mod)
	require_once($mod);
