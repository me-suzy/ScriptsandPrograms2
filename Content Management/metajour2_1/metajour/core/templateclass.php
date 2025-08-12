<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

require_once('basicclass.php');

class template extends basic {

	function template() {
		$this->basic();
		$this->setobjecttable('templates');
		$this->allowduplicate = false;
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('content',0,'html');
		$this->addcolumn('tpltype',0,UI_COMBO);
		$this->addcolumn('htmledit',0,UI_CHECKBOX);
		$this->addcolumn('header',0,UI_TEXT);
		$this->addcolumn('style',0,UI_TEXT);
		$this->addcolumn('param',0,UI_TEXT);
		$this->addcolumn('setting',0,UI_TEXT);
		$this->addcolumn('config',0,UI_TEXT);
		$this->addcolumn('doctype',0,UI_HIDDEN);
	
		$this->removeview('createvariant');
		$this->addview('default');
	}

	function tableUpdate() {
		if (!colExists($this->objecttable, 'htmledit')) {
			$db =& getDbConn();
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `htmledit` INT(11) NOT NULL');
		}
		if (!colExists($this->objecttable, 'config')) {
			$db =& getDbConn();
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `config` MEDIUMBLOB NOT NULL');
		}
	}

	function initLayout() {
		basic::initLayout();
		$this->addcolumnstyle('content','height: 600px');
	}
	
	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'tpltype';
		$arr[] = 'createdbyname';
		$arr[] = 'changed';
		$arr[] = 'language';
		$arr[] = 'objectid';
		return $arr;
	}
		
	function match_include($text) {
		$reg = '/\{include file="([a-z0-9æøåÆØÅ:\/\.\s_-]+)"[^}]*\}/i';
		preg_match_all($reg, $text, $matches);
		return $matches;
	}
	
	function match_modules($text) {
		$reg = "/\{[^\}]*".'\$?document\.ext'."\.([a-z0-9-]+)\.([a-z0-9-]+)\.[^\}]*\}/i";
		preg_match_all($reg, $text, $matches);
		return $matches;
	}
	
	function process_include($text) {
		$module_matches = $this->match_include($text);
		while(list($index, $module) = each($module_matches[1])) {
			$filnavn = $module_matches[1][$index];
			if (!$module or !$filnavn) {
				$this->errorcode = $this->ERR_INCLUDETAG;
				return $this->errorcode;
			}
			if ($filnavn == '__STANDARD__') {
				$tmpid = $this->_adodb->getone("select templates.objectid from templates,object where object.site = '$this->site' and object.objectid = templates.objectid and object.deleted = 0 and object.standard = 1");
				if ($tmpid) {
					$filnavn = $this->_adodb->getone("select templates.name from templates where objectid = $tmpid");
				}
			}
			$row = $this->_adodb->getrow("select templates.objectid as objid from templates,object where object.site = '$this->site' and templates.name = '$filnavn' and object.objectid = templates.objectid and object.deleted = 0");
			if (!is_array($row)) {
				$this->errorinfo = $filnavn;
				$this->errorcode = $this->ERR_INCLUDENOTEXIST;
				return $this->errorcode;
			} else {
				$includeid = $row['objid'];
				$this->_adodb->execute("insert into template_include values (-1, $includeid)");
			}
		}
	}
	
	function process_modules($text) {
		$module_matches = $this->match_modules($text);
		// Modultypen er i $module_matches[1][x]
		// og dataset er i $module_matches[2][x]
	
		while(list($index, $module) = each($module_matches[1])) {
			$dataset = $module_matches[2][$index];
			$this->_adodb->execute("replace into template_modules (template, type, configset) values (-1,'$module','$dataset')");
		}
	}
	
	function prv_createobject(&$arr) {
		parent::prv_createobject($arr);
		$this->process_modules($arr['content']);
		$this->process_include($arr['content']);
		$this->_adodb->execute("update template_modules set template = ".$this->getObjectId()." where template = -1");
		$this->_adodb->execute("update template_include set templateid = ".$this->getObjectId()." where templateid = -1");

		if (isset($arr['config']) && !empty($arr['config'])) {
			if (!$handle = fopen($this->userhandler->getDirTplCfg().$this->getObjectId(), 'w')) {
				# RAISE ERROR FLAG
				return;
			}
			if (fwrite($handle, $arr['config']) === FALSE) {
				# RAISE ERROR FLAG
				return;
			}
			fclose($handle);			
		}
	}
	
	
	function prv_updateobject($arr) {
		parent::prv_updateobject($arr);
		$this->_adodb->execute("delete from template_modules where template = ".$this->getObjectId());
		$this->_adodb->execute("delete from template_include where templateid = ".$this->getObjectId());
		$this->process_modules($arr['content']);
		$this->process_include($arr['content']);
		$row = $this->_adodb->getrow("select * from templates where objectid = ".$this->getObjectId());
		$this->_adodb->execute("update template_modules set template = ".$this->getObjectId()." where template = -1");
		$this->_adodb->execute("update template_include set templateid = ".$this->getObjectId()." where templateid = -1");

		if (isset($arr['config']) && !empty($arr['config'])) {
			if (!$handle = fopen($this->userhandler->getDirTplCfg().$this->getObjectId(), 'w')) {
				# RAISE ERROR FLAG
				return;
			}
			if (fwrite($handle, $arr['config']) === FALSE) {
				# RAISE ERROR FLAG
				return;
			}
			fclose($handle);			
		}
	}

	function prv_deleteobject() {
		$this->_adodb->execute("delete from templates where objectid = ".$this->getObjectId());
		$this->_adodb->execute("delete from template_modules where template = ".$this->getObjectId());
		$this->_adodb->execute("delete from template_include where templateid = ".$this->getObjectId());
		$this->_adodb->execute("delete from template_include where includeid = ".$this->getObjectId());
	}
	
}
