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

class shoporderline extends basic {

	function shoporderline() {
		$this->basic();
		$this->addcolumn('name');
		$this->addcolumn('itemtext');
		$this->addcolumn('itemnum');
		$this->addcolumn('itemid');
		$this->addcolumn('orderid');
		$this->addcolumn('price');
		$this->addcolumn('disc');
		$this->addcolumn('actprice');
		$this->addcolumn('actpricecur');
		$this->addcolumn('pricevat');
		$this->addcolumn('discvat');
		$this->addcolumn('actpricevat');
		$this->addcolumn('actpricecurvat');
		$this->addcolumn('sumprice');
		$this->addcolumn('sumdisc');
		$this->addcolumn('sumactprice');
		$this->addcolumn('sumactpricecur');
		$this->addcolumn('sumpricevat');
		$this->addcolumn('sumdiscvat');
		$this->addcolumn('sumactpricevat');
		$this->addcolumn('sumactpricecurvat');
	
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'itemtext';
		$arr[] = 'itemnum';
		$arr[] = 'price';
		$arr[] = 'sumprice';
		$arr[] = 'changed';
		return $arr;
	}

}
