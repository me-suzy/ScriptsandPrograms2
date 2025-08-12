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

class shoporder extends basic {

	function shoporder() {
		$this->basic();
		$this->addcolumn('name');
		$this->addcolumn('shippingid',0,UI_RELATION,'freight');
		$this->addcolumn('paymentid',0,UI_RELATION,'payment');
		$this->addcolumn('customerid',0,UI_RELATION,'customer');
		$this->addcolumn('userid',0,UI_RELATION,'user');
		$this->addcolumn('totalsum');
		$this->addcolumn('totaldiscsum');
		$this->addcolumn('totalactsum');
		$this->addcolumn('totalsumvat');
		$this->addcolumn('totaldiscsumvat');
		$this->addcolumn('totalactsumvat');
		$this->addcolumn('total');
		$this->addcolumn('totalvat');
		$this->addcolumn('shippingprice');
		$this->addcolumn('shippingpricevat');
		$this->addcolumn('paymentprice');
		$this->addcolumn('paymentpricevat');
		$this->addcolumn('totalsumcur');
		$this->addcolumn('totaldiscsumcur');
		$this->addcolumn('totalactsumcur');
		$this->addcolumn('totalsumcurvat');
		$this->addcolumn('totaldiscsumcurvat');
		$this->addcolumn('totalactsumcurvat');
		$this->addcolumn('totalcur');
		$this->addcolumn('totalcurvat');
		$this->addcolumn('shippingpricecur');
		$this->addcolumn('shippingpricecurvat');
		$this->addcolumn('paymentpricecur');
		$this->addcolumn('paymentpricecurvat');
	
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
	}

	function initLayout() {
		basic::initLayout();
		$this->addChildDatatype('shoporderline');
	}

	function stdListCol() {
		$arr[] = 'customerid';
		$arr[] = 'shippingid';
		$arr[] = 'paymentid';
		$arr[] = 'totalactsum';
		$arr[] = 'totalactsumvat';
		$arr[] = 'changed';
		return $arr;
	}

}
