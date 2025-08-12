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

class company extends basic {

	function company() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL,'string');
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
		$this->addcolumn('email',F_LITERAL,'email');
		$this->addcolumn('timezone',F_LITERAL,'string');
		$this->addcolumn('comment',F_LITERAL,'text');

		$this->removeview('createvariant');
	}

	function initLayout() {
		basic::initLayout();
		$this->addRelationDatatype('contact','objectid','companyid');
		$this->addRelationDatatype('task','objectid','companyid');
		$this->addRelationDatatype('meeting','objectid','companyid');
		$this->byside3('postalcode','city', 'state');
		$this->byside3('telephone1','telephone2','telephone3');
		$this->addcolumnstyle('postalcode','width: 44px;');
		$this->addcolumnstyle('city','width: 125px;');
		$this->addcolumnstyle('state','width: 50px;');
		$this->addcolumnstyle('telephone1','width: 66px;');
		$this->addcolumnstyle('telephone2','width: 66px;');
		$this->addcolumnstyle('telephone3','width: 66px;');
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'address1';
		$arr[] = 'postalcode';
		$arr[] = 'city';
		$arr[] = 'telephone1';
		$arr[] = 'email';
		$arr[] = 'changed';
		return $arr;
	}

}