<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage core
 * $Id: edocument_edocformclass.php,v 1.5 2005/04/29 05:23:17 jan Exp $
 */
global $system_path;
require_once($system_path."core/basicclass.php");
define('CASE_OPEN',0);
define('CASE_CLOSED',1);
define('CASE_DONE',2);

class edocument_edocform extends basic {

	function edocument_edocform() {
		$this->basic();
		$this->setobjecttype('edocform');
		
		$this->addcolumn('errorcodeid',F_REL,'relation','edocerrorcode');
		$this->addcolumn('correctionid',0,UI_RELATION,'edoccorrection');
		$this->addcolumn('responsibleid',0,UI_RELATION,'edocresponsible');
		$this->addcolumn('handleid',0,UI_RELATION,'user');
		$this->addcolumn('infoid',0,UI_RELATION_MULTIPLE,'user');
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('cno',0,UI_STRING);
		$this->addcolumn('cname',0,UI_STRING);
		$this->addcolumn('comment1',0,UI_TEXT_LITERAL_WRAP);
		$this->addcolumn('comment2',0,UI_TEXT_LITERAL_WRAP);
		$this->addcolumn('comment3',0,UI_TEXT_LITERAL_WRAP);
		$this->addcolumn('status',0,UI_HIDDEN);

		$this->removeview('createvariant');
		$this->removeview('access');
		$this->removeview('delete');
		$this->removeview('category');
		
		$this->addview('search');
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'itemtext';
		$arr[] = 'cno';
		$arr[] = 'cname';
		$arr[] = 'errorcodeid';
		$arr[] = 'createdbyname';
		$arr[] = 'changed';
		return $arr;
	}
	
	function initLayout() {
		parent::initLayout();
		$field =& $this->getColObj('errorcodeid');
		$field->setDisabledOnValue(true);
		
/*		$this->byside3('itemtext', 'itemnum', 'compunitid');
		$this->byside2('cpostalcode', 'ccity');
		$this->addcolumnstyle('itemtext', 'width: 200px');
		$this->addcolumnstyle('itemnum', 'width: 150px');
		$this->addlabelstyle('itemnum','width: 100px;');
		$this->addcolumnstyle('compunitid', 'width: 150px');
		$this->addlabelstyle('compunitid','width: 100px;');
		$this->addcolumnstyle('compcauseid', 'width: 206px');
		$this->addcolumnstyle('description','width: 740px; height: 80px');
		$this->addcolumnstyle('cpostalcode', 'width: 50px');
		$this->addcolumnstyle('ccity', 'width: 175px');
		$this->addlabelstyle('ccity','width: 100px;');
		$this->addcolumnstyle('compcountryid', 'width: 356px');
		$this->addcolumnstyle('comment','width: 740px; height: 80px');
		$this->addcolumnstyle('comment1','width: 740px; height: 80px');*/
	}

}

?>
