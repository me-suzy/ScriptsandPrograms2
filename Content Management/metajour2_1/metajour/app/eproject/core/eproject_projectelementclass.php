<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage core
 * $Id: eproject_projectelementclass.php,v 1.4 2005/02/02 14:22:13 SYSTEM Exp $
 */

require_once($system_path."core/basicclass.php");

class eproject_projectelement extends basic {

	function eproject_projectelement() {
		$this->basic();
		$this->setobjecttype('projectelement');
		$this->setsupertype('project');
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('content',0,UI_TEXT_WRAP);
		$this->addcolumn('comment',0,UI_TEXT_WRAP);
		#$this->addcolumn('status',0,UI_HIDDEN);
		$this->addcolumn('dato1',0,UI_DATE);
		$this->addcolumn('dato2',0,UI_DATE);
		$this->addcolumn('messageto',0,UI_RELATION,'user');

		$this->removeview('createvariant');
		$this->removeview('access');
		$this->removeview('category');
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'content';
		$arr[] = 'dato1';
		$arr[] = 'dato2';
		$arr[] = 'messageto';
		$arr[] = 'changed';
		return $arr;
	}

	function initLayout() {
		parent::initLayout();
		$this->addcolumnstyle('content','width: 650px; height: 80px');
		$this->addcolumnstyle('comment','width: 650px; height: 80px');
        $this->addcolumnstyle('dato1','width: 60px;');
        $this->addcolumnstyle('dato2','width: 60px;');
	}

}
