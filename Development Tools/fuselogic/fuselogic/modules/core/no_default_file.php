<?php
$FuseLogic->setErrorMessage('Module <b>'.$FuseLogic->module().'</b> can not find file : <b>"'.$FL_MODULE_SETTING['sub_module']['default'].'"</b>');	
require(dirname(__FILE__).'/404.php');
?>
