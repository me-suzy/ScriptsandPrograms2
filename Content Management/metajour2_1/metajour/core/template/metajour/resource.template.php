<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

/*
* Smarty plugin
* ------------------------------------------------------------- 
* File:     resource.template.php
* Type:     resource
* Name:     template
* Purpose:  Fetches templates from the METAZO object database
* Copyright:Jan H. Andersen
* -------------------------------------------------------------
*/

	function installPasswordDialog() {
		$obj = owNew('template');
		$id = $obj->locatebyname('standard_password_dialog');
		if ($id) return $id;
		$uh =& getUserHandler();
		$userid = $uh->getSystemAccountId();
		$path = $uh->getSystemPath()."standard/primary/";
		$cid = array();
		$obj = owImportObj('standard_password_dialog',$path,$cid);
		$obj->setCreatedBy($userid);
		return $obj->getObjectId();
	}

	function smarty_resource_template_source($tpl_name, &$tpl_source, &$smarty) {
		$obj = owNew('template');
		if ($tpl_name == 'standard_password_dialog') {
			$id = installPasswordDialog();
		} elseif ($tpl_name == '__STANDARD__') {
			$id = $obj->locateDefault();
		} else {
			$id = $obj->locatebyname($tpl_name);
		}
		if ($obj->readobject($id)) {
			$tpl_source = $obj->elements[0]['content'];
			if ($obj->elements[0]['config']) {
				$smarty->config_load('file:'.$id,$obj->userhandler->getLanguage());
			}
			if ($obj->elements[0]['setting']) {
				$arr = unserialize($obj->elements[0]['setting']);
				$smarty->assign('setting',$arr);
			}
			return true;
		} else {
			return false;
		}
	}
	
	function smarty_resource_template_timestamp($tpl_name, &$tpl_timestamp, &$smarty) {
		$obj = owNew('template');
		if ($tpl_name == 'standard_password_dialog') {
			$id = installPasswordDialog();
		} elseif ($tpl_name == '__STANDARD__') {
			$id = $obj->locateDefault();
		} else {
			$id = $obj->locatebyname($tpl_name);
		}
		if ($obj->readobject($id)) {
			$tpl_timestamp = strtotime($obj->elements[0]['object']['changed']);
			if ($obj->elements[0]['config']) {
				$smarty->config_load('file:'.$id,$obj->userhandler->getLanguage());
			}
			if ($obj->elements[0]['setting']) {
				$arr = unserialize($obj->elements[0]['setting']);
				$smarty->assign('setting',$arr);
			}
			return true;
		} else {
			return false;
		}
	}
	
	function smarty_resource_template_secure($tpl_name, &$smarty) {
		// assume all templates are secure
		return true;
	}
	
	function smarty_resource_template_trusted($tpl_name, &$smarty) {
		// not used for templates
	}

?>