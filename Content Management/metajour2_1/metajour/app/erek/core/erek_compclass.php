<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage core
 * $Id: erek_compclass.php,v 1.9 2005/04/07 06:02:53 jan Exp $
 */
global $system_path;
require_once($system_path."core/basicclass.php");
define('CASE_OPEN',0);
define('CASE_CLOSED',1);
define('CASE_DONE',2);
define('CASE_AWAIT',3);

class erek_comp extends basic {

	function erek_comp() {
		$this->basic();
		$this->setobjecttype('comp');
		
		//$this->addcolumn('cno',0,UI_LISTDIALOG,'company');
		$this->addcolumn('cno',0,UI_STRING);
		$this->addcolumn('cname',0,UI_STRING);
		$this->addcolumn('caddress',0,UI_STRING);
		$this->addcolumn('cpostalcode',0,UI_STRING);
		$this->addcolumn('ccity',0,UI_STRING);
		$this->addcolumn('compcountryid',0,UI_RELATION,'compcountry');
		$this->addcolumn('ccontact',0,UI_STRING);

		//$this->addcolumn('name',0,UI_LISTDIALOG,'item'); #varenummer
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('itemtext',0,UI_STRING);
		$this->addcolumn('itemnum',0,'decimal');
		$this->addcolumn('compunitid',0,UI_RELATION,'compunit');
		$this->addcolumn('compcauseid',0,UI_RELATION,'compcause');
		$this->addcolumn('description',0,UI_TEXT_WRAP);
		$this->addcolumn('cemail',0,UI_STRING);
		
		$this->addcolumn('messageto',0,UI_RELATION_MULTIPLE,'user');
		$this->addcolumn('compdecisionid',0,UI_RELATION,'compdecision');
		$this->addcolumn('compsolutionid',0,UI_RELATION,'compsolution');
		$this->addcolumn('credit',0,UI_STRING);
		$this->addcolumn('cost',0,'decimal');
		$this->addcolumn('comment',0,UI_TEXT_WRAP);
		$this->addcolumn('compdepartmentid',0,UI_RELATION,'compdepartment');
		$this->addcolumn('comment1',0,UI_TEXT_WRAP);

		$this->addcolumn('status',0,UI_HIDDEN);
		$this->addcolumn('closedtime', 0, UI_HIDDEN);
		$this->addcolumn('closedby', 0, UI_HIDDEN);

		$this->removeview('createvariant');
		$this->removeview('access');
		$this->removeview('delete');
		
		$this->addview('search');
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'itemtext';
		$arr[] = 'cno';
		$arr[] = 'cname';
		$arr[] = 'compcauseid';
		$arr[] = 'createdbyname';
		$arr[] = 'changed';
		return $arr;
	}
	
	function initLayout() {
		parent::initLayout();
//		$this->byside3('itemtext', 'itemnum', 'compunitid');
		$this->byside2('cpostalcode', 'ccity');
		$this->addcolumnstyle('itemtext', 'width: 200px');
		$this->addcolumnstyle('itemnum', 'width: 200px');
		//$this->addlabelstyle('itemnum','width: 100px;');
		$this->addcolumnstyle('compunitid', 'width: 356px');
		//$this->addlabelstyle('compunitid','width: 100px;');
		$this->addcolumnstyle('compcauseid', 'width: 356px');
		$this->addcolumnstyle('description','width: 350px; height: 80px');
		$this->addcolumnstyle('cpostalcode', 'width: 50px');
		$this->addcolumnstyle('ccity', 'width: 175px');
		$this->addlabelstyle('ccity','width: 100px;');
		$this->addcolumnstyle('compcountryid', 'width: 356px');
		$this->addcolumnstyle('comment','width: 350px; height: 80px');
		$this->addcolumnstyle('comment1','width: 350px; height: 80px');



/*		$this->relateFields('vehiclemakeid','modelid','vehiclemakeid');
		$this->relateFields('agreementid','clientid','agreementid');
		$this->addcolumnstyle('agreementid','width: 156px;');
		$this->addcolumnstyle('clientid','width: 156px;');
		$this->addcolumnstyle('rekv', 'width: 150px');
		$this->addcolumnstyle('rekvphone', 'width: 150px');
		$this->addcolumnstyle('subname','width: 100px;');
		$this->addcolumnstyle('creditcard','width: 156px;');
		$this->addcolumnstyle('creditcardnumber','width: 150px;');
		$this->addcolumnstyle('probloc', 'width: 156px');
		$this->addcolumnstyle('clientdetail','width: 445px;');
		$this->addcolumnstyle('driver','width: 445px;');
		$this->addcolumnstyle('driverphone','width: 150px;');
		$this->addcolumnstyle('regnumber','width: 150px;');
		$this->addcolumnstyle('regdate','width: 150px;');
		$this->addcolumnstyle('chassisnumber','width: 150px;');
		$this->addcolumnstyle('regnumber2','width: 150px;');
		$this->addcolumnstyle('regdate2','width: 150px;');
		$this->addcolumnstyle('chassisnumber2','width: 150px;');
		$this->addcolumnstyle('year','width: 150px;');
		$this->addcolumnstyle('kilometer','width: 150px;');
		$this->addcolumnstyle('enginenumber','width: 150px;');

		$this->addcolumnstyle('fuelid','width: 156px;');
		$this->addcolumnstyle('transmissionid','width: 156px;');
		$this->addcolumnstyle('colorid','width: 156px;');

		$this->addcolumnstyle('vehiclemakeid','width: 156px;');
		$this->addcolumnstyle('vehicletypeid','width: 156px;');
		$this->addcolumnstyle('vehiclemakeid2','width: 156px;');
		$this->addcolumnstyle('vehicletypeid2','width: 156px;');
		$this->addcolumnstyle('modelid','width: 156px;');
		$this->addcolumnstyle('modelid2','width: 156px;');
		$this->addcolumnstyle('width', 'width: 150px');
		$this->addcolumnstyle('weight','width: 150px;');
		$this->addcolumnstyle('length','width: 150px;');
		$this->addcolumnstyle('height','width: 150px;');

		$this->addcolumnstyle('problemid','width: 156px;');
		$this->addcolumnstyle('causeid','width: 156px;');
		$this->addcolumnstyle('warranty','width: 156px;');

		$this->addcolumnstyle('problemcomment','width: 740px; height: 80px');

		$this->clearRelationDatatypes();
		$this->clearChildDatatypes();
		$this->addChildDatatype('caseaction');
		$this->addChildDatatype('casenote');
		$this->addChildDatatype('log');
		
		$this->byside3('clientid', 'rekv', 'rekvphone');
		$this->byside3('creditcard','creditcardnumber', 'probloc');
		$this->byside2('driver','driverphone');
		$this->byside3('regnumber','regdate','chassisnumber');
		$this->byside3('regnumber2','regdate2','chassisnumber2');
		$this->byside3('colorid','year','kilometer');
		$this->byside3('width','length','height');
		$this->byside3('vehicletypeid','vehiclemakeid','modelid');
		$this->byside3('vehicletypeid2','vehiclemakeid2','modelid2');
		$this->byside2('output_created', 'output_createdby');
		$this->byside2('closedtime', 'output_closedby');
		$this->byside3('fuelid','transmissionid','enginenumber');
		$this->byside3('agreementid', 'name', 'subname');
		$this->byside3('problemid','causeid', 'warranty');

		$this->addlabelstyle('driverphone','width: 120px;');
		$this->addlabelstyle('creditcardnumber','width: 120px;');
		$this->addlabelstyle('regdate','width: 120px;');
		$this->addlabelstyle('regdate2','width: 120px;');
		$this->addlabelstyle('vehiclemakeid','width: 120px;');
		$this->addlabelstyle('vehiclemakeid2','width: 120px;');
		$this->addlabelstyle('year','width: 120px;');

		$this->addlabelstyle('modelid','width: 120px;');
		$this->addlabelstyle('modelid2','width: 120px;');
		$this->addlabelstyle('chassisnumber','width: 120px;');
		$this->addlabelstyle('chassisnumber2','width: 120px;');
		$this->addlabelstyle('kilometer','width: 120px;');

		$this->addlabelstyle('transmissionid','width: 120px;');
		$this->addlabelstyle('enginenumber','width: 120px;');

		$this->addlabelstyle('length','width: 120px;');
		$this->addlabelstyle('height','width: 120px;');

		$this->addlabelstyle('causeid','width: 120px;');
		$this->addlabelstyle('warranty','width: 120px;');

		$this->addlabelstyle('probloc', 'width: 120px');
		$this->addlabelstyle('rekv', 'width: 120px');
		$this->addlabelstyle('rekvphone', 'width: 120px');*/
	}

}

?>
