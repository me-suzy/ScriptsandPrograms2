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

class contact extends basic {

	function contact() {
		$this->basic();
		$this->addcolumn('companyid',F_REL,'relationcreate','company');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('jobtitle',F_LITERAL,'string');
		$this->addcolumn('binfile1',F_REL,'splitselect','binfile');
		$this->addcolumn('address1',F_LITERAL,'string');
		$this->addcolumn('address2',F_LITERAL,'string');
		$this->addcolumn('address3',F_LITERAL,'string');
		$this->addcolumn('address4',F_LITERAL,'string');
		$this->addcolumn('postalcode',F_LITERAL,'string');
		$this->addcolumn('city',F_LITERAL,'string');
		$this->addcolumn('state',F_LITERAL,'string');
		$this->addcolumn('country',F_LITERAL,'string');
		$this->addcolumn('telephone1',F_LITERAL,'string');
		$this->addcolumn('telephone2',F_LITERAL,'string');
		$this->addcolumn('telephone3',F_LITERAL,'string');
		$this->addcolumn('telefax',F_LITERAL,'string');
		$this->addcolumn('website',F_LITERAL,'url');
		$this->addcolumn('email1',F_LITERAL,'email');
		$this->addcolumn('email2',F_LITERAL,'email');
		$this->addcolumn('email3',F_LITERAL,'email');
		$this->addcolumn('timezone',F_LITERAL,'string');
		$this->addcolumn('birthday',F_LITERAL,'date');
		$this->addcolumn('comment',F_LITERAL,'text');

		$this->removeview('createvariant');
	}

	function initLayout() {
		basic::initLayout();
		$this->byside3('postalcode','city', 'state');
		$this->byside3('telephone1','telephone2','telephone3');
		$this->addcolumnstyle('postalcode','width: 44px;');
		$this->addcolumnstyle('city','width: 125px;');
		$this->addcolumnstyle('state','width: 50px;');
		$this->addcolumnstyle('telephone1','width: 66px;');
		$this->addcolumnstyle('telephone2','width: 66px;');
		$this->addcolumnstyle('telephone3','width: 66px;');
		$this->addRelationDatatype('letter','objectid','contactid');
		$f =& $this->getColObj('binfile1');
		$f->setShowThumb(true);
	}
	
	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'jobtitle';
		$arr[] = 'address1';
		$arr[] = 'postalcode';
		$arr[] = 'city';
		$arr[] = 'telephone1';
		$arr[] = 'email1';
		$arr[] = 'changed';
		return $arr;
	}
	
	function tableUpdate() {
		if (!colExists($this->objecttable, 'jobtitle')) {
			$db =& getDbConn();
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `jobtitle` VARCHAR(255) NOT NULL');
		}
	}
	
}