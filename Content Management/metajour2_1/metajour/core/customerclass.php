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

class customer extends basic {

	function customer() {
		$this->basic();
		$this->addcolumn('userid',0,UI_RELATION_NODEFAULT,'user');
		$this->addcolumn('companyid',0,UI_RELATION_NODEFAULT,'company');
		$this->addcolumn('contactid',0,UI_RELATION_NODEFAULT,'contact');
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('address1',0,UI_STRING);
		$this->addcolumn('address2',0,UI_STRING);
		$this->addcolumn('address3',0,UI_STRING);
		$this->addcolumn('address4',0,UI_STRING);
		$this->addcolumn('postalcode',0,UI_STRING);
		$this->addcolumn('city',0,UI_STRING);
		$this->addcolumn('state',0,UI_STRING);
		$this->addcolumn('country',0,UI_STRING);
		$this->addcolumn('telephone1',0,UI_STRING);
		$this->addcolumn('telephone2',0,UI_STRING);
		$this->addcolumn('telephone3',0,UI_STRING);
		$this->addcolumn('telefax',0,UI_STRING);
		$this->addcolumn('website',F_LITERAL,'url');
		$this->addcolumn('email1',F_LITERAL,'email');
		$this->addcolumn('email2',F_LITERAL,'email');
		$this->addcolumn('email3',F_LITERAL,'email');
		$this->addcolumn('comment',0,UI_TEXT);
	
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'address1';
		$arr[] = 'postalcode';
		$arr[] = 'city';
		$arr[] = 'telephone1';
		$arr[] = 'email1';
		$arr[] = 'changed';
		return $arr;
	}

}
