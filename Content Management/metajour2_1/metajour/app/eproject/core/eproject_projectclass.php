<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage core
 * $Id: eproject_projectclass.php,v 1.3 2005/01/12 03:24:08 jan Exp $
 */

require_once($system_path."core/basicclass.php");

define('CASE_OPEN',0);
define('CASE_CLOSED',1);

class eproject_project extends basic {

	function eproject_project() {
		$this->basic();
		$this->setobjecttype('project');
		$this->setsubtype('projectelement');
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('uservarchar1',0,UI_STRING);
		$this->addcolumn('uservarchar2',0,UI_STRING);
		$this->addcolumn('content',0,UI_TEXT_WRAP);
	#	$this->addcolumn('uservarchar3',0,0);
	#	$this->addcolumn('uservarchar4',0,0);
	#	$this->addcolumn('uservarchar5',0,0);
	#	$this->addcolumn('usertext1',0,1);
	#	$this->addcolumn('usertext2',0,1);
	#	$this->addcolumn('usertext3',0,1);
		$this->addcolumn('status',0,UI_HIDDEN);

		$this->removeview('createvariant');
		$this->removeview('access');
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'uservarchar1';
		$arr[] = 'uservarchar2';
		$arr[] = 'content';
		$arr[] = 'createdbyname';
		$arr[] = 'changed';
		return $arr;
	}

	function initLayout() {
		parent::initLayout();
		$this->addcolumnstyle('content','width: 740px; height: 80px');
		$this->addChildDatatype('projectelement');
	}
}
