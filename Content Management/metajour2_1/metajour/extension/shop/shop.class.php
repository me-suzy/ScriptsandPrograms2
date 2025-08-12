<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path . "extension/basicextension.class.php");

class ext_shop extends basicextension {

	function ext_shop() {
		$this->basicextension();
		$this->extname = 'shop';
		$this->addextparam('templatename_list');
		$this->addextparam('templateid_list');
	}

	function getcurrency() {
		$obj = owNew('currency');
		if (empty($_SESSION[$this->extname][$this->extconfigset]['currencyid'])) {
			$_SESSION[$this->extname][$this->extconfigset]['currencyid'] = $obj->locatedefault();
		}
		if ($_SESSION[$this->extname][$this->extconfigset]['currencyid'] <> $obj->locatedefault()) $this->extresult['viewcur'] = true;
		if ($_SESSION[$this->extname][$this->extconfigset]['currencyid']) {
			$obj = owRead($_SESSION[$this->extname][$this->extconfigset]['currencyid']);
			return $obj->elements[0]['currency'];
		} else {
			return 1;
		}
	}
	
	function calcprice($itemno, $itemnum = 1, $rounding = true) {
		#$custdiscpercent = 10.00;
		/*
		rabat kan være baseret på kunde, kunde+prisgruppe eller vare+kvantum eller på vare
		*/
		$currency = $this->getcurrency();
	
		$obj = owRead($itemno);
		$vatid = $obj->elements[0]['vatid'];
		if ($vatid == 0) {
			$vatobj = owNew('vat');
			$vatid = $vatobj->locatedefault();
		}
		if ($vatid) $vatobj = owRead($vatid); else $vatid = 1;
		$vat = (100 + $vatobj->elements[0]['vat']) / 100;
	
		
		$res['price'] = $obj->elements[0]['price'];
		$res['pricecur'] = $obj->elements[0]['price'] / $currency;
		$res['disc'] = ($obj->elements[0]['price'] / 100) * $custdiscpercent;
		$res['disccur'] = (($obj->elements[0]['price'] / 100) * $custdiscpercent) / $currency;
		$res['actprice'] = $res['price'] - $res['disc'];
		$res['actpricecur'] = $res['actprice'] / $currency;
		$res['pricevat'] = $res['price'] * $vat;
		$res['pricecurvat'] = $res['pricecur'] * $vat;
		$res['discvat'] = $res['disc'] * $vat;
		$res['disccurvat'] = $res['disccur'] * $vat;
		$res['actpricevat'] = $res['actprice'] * $vat;
		$res['actpricecurvat'] = $res['actpricecur'] * $vat;
	
		$res['sumprice'] = $res['price'] * $itemnum;
		$res['sumdisc'] = $res['disc'] * $itemnum;
		$res['sumactprice'] = $res['actprice'] * $itemnum;
		$res['sumactpricecur'] = $res['actpricecur'] * $itemnum;
		$res['sumpricevat'] = $res['pricevat'] * $itemnum;
		$res['sumdiscvat'] = $res['discvat'] * $itemnum;
		$res['sumactpricevat'] = $res['actpricevat'] * $itemnum;
		$res['sumactpricecurvat'] = $res['actpricecurvat'] * $itemnum;

		if ($rounding) {
			foreach ($res as $key => $val) {
				$res[$key] = number_format(round($val, 2),2,',','.');
			}
		}
	
		return $res;
	}

	function _doadd() {
		$i = -1;
		if ($_SESSION[$this->extname][$this->extconfigset]['basket']) {
			foreach($_SESSION[$this->extname][$this->extconfigset]['basket'] as $key => $val) {
				if ($_SESSION[$this->extname][$this->extconfigset]['basket'][$key]['objectid'] == $_REQUEST['_ext_itemid']) {
					$i = $key;
				}
			}
		}
		if ($i == -1) $i = sizeof($_SESSION[$this->extname][$this->extconfigset]['basket']);
		$_SESSION[$this->extname][$this->extconfigset]['basket'][$i]['objectid'] = $_REQUEST['_ext_itemid'];
		$_SESSION[$this->extname][$this->extconfigset]['basket'][$i]['num'] = @$_SESSION[$this->extname][$this->extconfigset]['basket'][$i]['num'] + $_REQUEST['_ext_num'];
		if ($_REQUEST['_ext_goto'] == 'list') $this->_dolist();
		if ($_REQUEST['_ext_goto'] == 'item') $this->_doitem();
		if ($_REQUEST['_ext_goto'] == 'basket') $this->_dobasket();
	}

	function _doempty() {
		unset($_SESSION[$this->extname][$this->extconfigset]['basket']);
		if ($_REQUEST['_ext_goto'] == 'list') $this->_dolist();
		if ($_REQUEST['_ext_goto'] == 'item') $this->_doitem();
		if ($_REQUEST['_ext_goto'] == 'basket') $this->_dobasket();
	}

	function _doremove() {
		$i = -1;
		if ($_SESSION[$this->extname][$this->extconfigset]['basket']) {
			foreach($_SESSION[$this->extname][$this->extconfigset]['basket'] as $key => $val) {
				if ($_SESSION[$this->extname][$this->extconfigset]['basket'][$key]['objectid'] == $_REQUEST['_ext_itemid']) {
					$i = $key;
				}
			}
		}
		if ($i != -1) unset($_SESSION[$this->extname][$this->extconfigset]['basket'][$i]);
		if ($_REQUEST['_ext_goto'] == 'list') $this->_dolist();
		if ($_REQUEST['_ext_goto'] == 'item') $this->_doitem();
		if ($_REQUEST['_ext_goto'] == 'basket') $this->_dobasket();
	}

	function _getordervars() {
		$this->extresult['order']['shippingid'] = $_SESSION[$this->extname][$this->extconfigset]['shippingid'];
		$this->extresult['order']['paymentid'] = $_SESSION[$this->extname][$this->extconfigset]['paymentid'];
		$this->extresult['order']['customerid'] = $_SESSION[$this->extname][$this->extconfigset]['customerid'];
		$this->extresult['order']['userid'] = $_SESSION[$this->extname][$this->extconfigset]['userid'];
		$this->extresult['order']['currencyid'] = $_SESSION[$this->extname][$this->extconfigset]['currencyid'];

		$arr = $_SESSION[$this->extname][$this->extconfigset]['basket'];
		if ($arr) {
			foreach($arr as $key => $val) {
				if ($arr[$key]['objectid']) {
					$obj = owRead($arr[$key]['objectid']);
					$arr[$key]['item'] = $obj->elements[0];
					$i = sizeof($this->orderlinedata);
					$this->orderlinedata[$i] = array_merge($arr[$key],$this->calcprice($arr[$key]['objectid'],$arr[$key]['num'],false));
					$this->orderlinedata[$i]['itemnum'] = $arr[$key]['num'];
					$this->orderlinedata[$i]['name'] = $arr[$key]['item']['name'];
					$this->orderlinedata[$i]['itemtext'] = $arr[$key]['item']['content1'];
					$this->orderlinedata[$i]['itemid'] = $arr[$key]['objectid'];
					$arr[$key] = array_merge($arr[$key],$this->calcprice($arr[$key]['objectid'],$arr[$key]['num']));
				}
			}
		}

		$this->extresult['orderline'] = $arr;

		if ($arr) {
			foreach($arr as $key => $val) {
				if ($arr[$key]['objectid']) {
					$obj = owRead($arr[$key]['objectid']);
					$p = $this->calcprice($arr[$key]['objectid'],$arr[$key]['num'],false);
					$this->extresult['order']['totalsum'] = $this->extresult['order']['totalsum'] + $p['sumprice'];
					$this->extresult['order']['totaldiscsum'] = $this->extresult['order']['totaldiscsum'] + $p['sumdisc'];
					$this->extresult['order']['totalactsum'] = $this->extresult['order']['totalactsum'] + $p['sumactprice'];
					$this->extresult['order']['totalsumvat'] = $this->extresult['order']['totalsumvat'] + $p['sumpricevat'];
					$this->extresult['order']['totaldiscsumvat'] = $this->extresult['order']['totaldiscsumvat'] + $p['sumdiscvat'];
					$this->extresult['order']['totalactsumvat'] = $this->extresult['order']['totalactsumvat'] + $p['sumactpricevat'];
				}
			}
		}

		### calculate shipping costs
		if ($this->extresult['order']['shippingid']) {
			$shipobj = owRead($this->extresult['order']['shippingid']);
			$totalweight = 1.00; #dummy
			$this->extresult['order']['shippingprice'] = $shipobj->elements[0]['init'] + ($totalweight * $shipobj->elements[0]['perweight']);
	
			$vatid = $shipobj->elements[0]['vatid'];
			if ($vatid == 0) {
				$vatobj = owNew('vat');
				$vatid = $vatobj->locatedefault();
			}
			$vatobj = owRead($vatid);
			$this->extresult['order']['shippingpricevat'] = $this->extresult['order']['shippingprice'] * ((100 + $vatobj->elements[0]['vat']) / 100);
		} else {
			$this->extresult['order']['shippingprice'] = 0;
			$this->extresult['order']['shippingpricevat'] = 0;
		}
		###
	
		### calculate payment costs
		if ($this->extresult['order']['paymentid']) {
			$payobj = owRead($this->extresult['order']['paymentid']);
			$this->extresult['order']['paymentprice'] = $payobj->elements[0]['init'] + (($this->extresult['order']['totalactsumvat'] * $payobj->elements[0]['percentage']) / 100);
	
			$vatid = $payobj->elements[0]['vatid'];
			if ($vatid == 0) {
				$vatobj = owNew('vat');
				$vatid = $vatobj->locatedefault();
			}
			$vatobj = owRead($vatid);
			$this->extresult['order']['paymentpricevat'] = $this->extresult['order']['paymentprice'] * ((100 + $vatobj->elements[0]['vat']) / 100);
		} else {
			$this->extresult['order']['paymentprice'] = 0;
			$this->extresult['order']['paymentpricevat'] = 0;
		}
		###
		
		$this->extresult['order']['total'] = $this->extresult['order']['totalactsum'] + $this->extresult['order']['shippingprice'] + $this->extresult['order']['paymentprice'];
		$this->extresult['order']['totalvat'] = $this->extresult['order']['totalactsumvat'] + $this->extresult['order']['shippingpricevat'] + $this->extresult['order']['paymentpricevat'];

		#genberegn cur udgaver af alle variable
		$currency = $this->getcurrency();

		$this->extresult['order']['totalsumcur'] = $this->extresult['order']['totalsum'] / $currency;
		$this->extresult['order']['totaldiscsumcur'] = $this->extresult['order']['totaldiscsum'] / $currency;
		$this->extresult['order']['totalactsumcur'] = $this->extresult['order']['totalactsum'] / $currency;
		$this->extresult['order']['totalsumcurvat'] = $this->extresult['order']['totalsumvat'] / $currency;
		$this->extresult['order']['totaldiscsumcurvat'] = $this->extresult['order']['totaldiscsumvat'] / $currency;
		$this->extresult['order']['totalactsumcurvat'] = $this->extresult['order']['totalactsumvat'] / $currency;
		$this->extresult['order']['totalcur'] = $this->extresult['order']['total'] / $currency;
		$this->extresult['order']['totalcurvat'] = $this->extresult['order']['totalvat'] / $currency;
		$this->extresult['order']['shippingpricecur'] = $this->extresult['order']['shippingprice'] / $currency;
		$this->extresult['order']['shippingpricecurvat'] = $this->extresult['order']['shippingpricevat'] / $currency;
		$this->extresult['order']['paymentpricecur'] = $this->extresult['order']['paymentprice'] / $currency;
		$this->extresult['order']['paymentpricecurvat'] = $this->extresult['order']['paymentpricevat'] / $currency;

		$this->orderdata = $this->extresult['order'];
		
		#al afrunding sker til sidst

		$this->extresult['order']['totalsum'] = round($this->extresult['order']['totalsum'],2);
		$this->extresult['order']['totaldiscsum'] = round($this->extresult['order']['totaldiscsum'],2);
		$this->extresult['order']['totalactsum'] = round($this->extresult['order']['totalactsum'],2);
		$this->extresult['order']['totalsumvat'] = round($this->extresult['order']['totalsumvat'],2);
		$this->extresult['order']['totaldiscsumvat'] = round($this->extresult['order']['totaldiscsumvat'],2);
		$this->extresult['order']['totalactsumvat'] = round($this->extresult['order']['totalactsumvat'],2);
		$this->extresult['order']['total'] = round($this->extresult['order']['total'],2);
		$this->extresult['order']['totalvat'] = round($this->extresult['order']['totalvat'],2);
		$this->extresult['order']['shippingprice'] = round($this->extresult['order']['shippingprice'],2);
		$this->extresult['order']['shippingpricevat'] = round($this->extresult['order']['shippingpricevat'],2);
		$this->extresult['order']['paymentprice'] = round($this->extresult['order']['paymentprice'],2);
		$this->extresult['order']['paymentpricevat'] = round($this->extresult['order']['paymentpricevat'],2);

		$this->extresult['order']['totalsumcur'] = round($this->extresult['order']['totalsumcur'],2);
		$this->extresult['order']['totaldiscsumcur'] = round($this->extresult['order']['totaldiscsumcur'],2);
		$this->extresult['order']['totalactsumcur'] = round($this->extresult['order']['totalactsumcur'],2);
		$this->extresult['order']['totalsumcurvat'] = round($this->extresult['order']['totalsumcurvat'],2);
		$this->extresult['order']['totaldiscsumcurvat'] = round($this->extresult['order']['totaldiscsumcurvat'],2);
		$this->extresult['order']['totalactsumcurvat'] = round($this->extresult['order']['totalactsumcurvat'],2);
		$this->extresult['order']['totalcur'] = round($this->extresult['order']['totalcur'],2);
		$this->extresult['order']['totalcurvat'] = round($this->extresult['order']['totalcurvat'],2);
		$this->extresult['order']['shippingpricecur'] = round($this->extresult['order']['shippingpricecur'],2);
		$this->extresult['order']['shippingpricecurvat'] = round($this->extresult['order']['shippingpricecurvat'],2);
		$this->extresult['order']['paymentpricecur'] = round($this->extresult['order']['paymentpricecur'],2);
		$this->extresult['order']['paymentpricecurvat'] = round($this->extresult['order']['paymentpricecurvat'],2);
		
		# formatering
		
		$this->extresult['order']['totalsum'] = number_format($this->extresult['order']['totalsum'],2,',','.');
		$this->extresult['order']['totaldiscsum'] = number_format($this->extresult['order']['totaldiscsum'],2,',','.');
		$this->extresult['order']['totalactsum'] = number_format($this->extresult['order']['totalactsum'],2,',','.');
		$this->extresult['order']['totalsumvat'] = number_format($this->extresult['order']['totalsumvat'],2,',','.');
		$this->extresult['order']['totaldiscsumvat'] = number_format($this->extresult['order']['totaldiscsumvat'],2,',','.');
		$this->extresult['order']['totalactsumvat'] = number_format($this->extresult['order']['totalactsumvat'],2,',','.');
		$this->extresult['order']['total'] = number_format($this->extresult['order']['total'],2,',','.');
		$this->extresult['order']['totalvat'] = number_format($this->extresult['order']['totalvat'],2,',','.');
		$this->extresult['order']['totalsumcur'] = number_format($this->extresult['order']['totalsumcur'],2,',','.');
		$this->extresult['order']['totaldiscsumcur'] = number_format($this->extresult['order']['totaldiscsumcur'],2,',','.');
		$this->extresult['order']['totalactsumcur'] = number_format($this->extresult['order']['totalactsumcur'],2,',','.');
		$this->extresult['order']['totalsumcurvat'] = number_format($this->extresult['order']['totalsumcurvat'],2,',','.');
		$this->extresult['order']['totaldiscsumcurvat'] = number_format($this->extresult['order']['totaldiscsumcurvat'],2,',','.');
		$this->extresult['order']['totalactsumcurvat'] = number_format($this->extresult['order']['totalactsumcurvat'],2,',','.');
		$this->extresult['order']['totalcur'] = number_format($this->extresult['order']['totalcur'],2,',','.');
		$this->extresult['order']['totalcurvat'] = number_format($this->extresult['order']['totalcurvat'],2,',','.');

		$this->extresult['order']['shippingprice'] = number_format($this->extresult['order']['shippingprice'],2,',','.');
		$this->extresult['order']['shippingpricevat'] = number_format($this->extresult['order']['shippingpricevat'],2,',','.');
		$this->extresult['order']['paymentprice'] = number_format($this->extresult['order']['paymentprice'],2,',','.');
		$this->extresult['order']['paymentpricevat'] = number_format($this->extresult['order']['paymentpricevat'],2,',','.');
		$this->extresult['order']['shippingpricecur'] = number_format($this->extresult['order']['shippingpricecur'],2,',','.');
		$this->extresult['order']['shippingpricecurvat'] = number_format($this->extresult['order']['shippingpricecurvat'],2,',','.');
		$this->extresult['order']['paymentpricecur'] = number_format($this->extresult['order']['paymentpricecur'],2,',','.');
		$this->extresult['order']['paymentpricecurvat'] = number_format($this->extresult['order']['paymentpricecurvat'],2,',','.');
		
	}
	
	function _doorder() {
		$this->useTemplate('templatename_order','templateid_order','standard_shop_order');
		if ($this->userhandler->GetLevel() >= 10) {
			#find customer record hvor userid = $CUSER['objectid']
			$_SESSION[$this->extname][$this->extconfigset]['customerid'] = $xxx;
			$_SESSION[$this->extname][$this->extconfigset]['userid'] = $xxx;
		}
		$this->_getordervars();
	}
	
	function _doprocessorder() {
		if ($this->userhandler->GetLevel() >= 10) {
			#find customer record hvor userid = $CUSER['objectid']
			$_SESSION[$this->extname][$this->extconfigset]['customerid'] = $xxx;
			$_SESSION[$this->extname][$this->extconfigset]['userid'] = $xxx;
		} else {
			$obj = owNew('customer');
			$arr['name'] = $_REQUEST['_ext_name'];
			$arr['address1'] = $_REQUEST['_ext_address1'];
			$arr['address2'] = $_REQUEST['_ext_address2'];
			$arr['address3'] = $_REQUEST['_ext_address3'];
			$arr['address4'] = $_REQUEST['_ext_address4'];
			$arr['postalcode'] = $_REQUEST['_ext_postalcode'];
			$arr['city'] = $_REQUEST['_ext_city'];
			$arr['state'] = $_REQUEST['_ext_state'];
			$arr['country'] = $_REQUEST['_ext_country'];
			$arr['telephone1'] = $_REQUEST['_ext_telephone1'];
			$arr['telephone2'] = $_REQUEST['_ext_telephone2'];
			$arr['telephone3'] = $_REQUEST['_ext_telephone3'];
			$arr['telefax'] = $_REQUEST['_ext_telefax'];
			$arr['website'] = $_REQUEST['_ext_website'];
			$arr['email1'] = $_REQUEST['_ext_email1'];
			$arr['email2'] = $_REQUEST['_ext_email2'];
			$arr['email3'] = $_REQUEST['_ext_email3'];
			$arr['comment'] = $_REQUEST['_ext_comment'];
			$obj->createobject($arr);
			$_SESSION[$this->extname][$this->extconfigset]['userid'] = 0;
			$_SESSION[$this->extname][$this->extconfigset]['customerid'] = $obj->getobjectid();
		}
		$_SESSION[$this->extname][$this->extconfigset]['shippingid'] = $_REQUEST['_ext_shippingid'];
		if ($_REQUEST['_ext_goto'] == 'accept') $this->_doaccept();
		if ($_REQUEST['_ext_goto'] == 'payment') $this->_dopayment();
		if ($_REQUEST['_ext_goto'] == 'finish') $this->_dofinish();
	}

	function _doprocesssetcurrency() {
		$_SESSION[$this->extname][$this->extconfigset]['currencyid'] = $_REQUEST['_ext_currencyid'];
		#if ($_REQUEST['_ext_goto'] == 'list') 
		if ($_REQUEST['_ext_goto'] == 'item') {
			$this->_doitem();
		} else {
			$this->_dolist();
		}
		#if ($_REQUEST['_ext_goto'] == 'basket') $this->_dobasket();
	}
	
	function _doaccept() {
		# valg af betalingsmetode
		$this->useTemplate('templatename_accept','templateid_accept','standard_shop_accept');
		$this->_getordervars();
	}

	function _doprocessaccept() {
		# gemmer valg af betalingsmetode
		$_SESSION[$this->extname][$this->extconfigset]['paymentid'] = $_REQUEST['_ext_paymentid'];
		if ($_REQUEST['_ext_goto'] == 'payment') $this->_dopayment();
	}
	
	function _dopayment() {
		$this->_getordervars();
		#skriv ordren som objekt
		$obj = owNew('shoporder');
		$obj->createobject($this->orderdata);
		$pid = $obj->getobjectid();
		foreach ($this->orderlinedata as $arr) {
			#var_dump($arr);
			$objline = owNew('shoporderline');
			$objline->createobject($arr,$pid);
		}
		#viser enten en kreditkortindtastning, eller blot en godkend knap
		$this->useTemplate('templatename_payment','templateid_payment','standard_shop_payment');
	}

	function _doprocesspayment() {
		#echo "doprocesspayment";
		#retur hertil når kunden endeligt godkender køb
		if ($_REQUEST['_ext_goto'] == 'finish') $this->_dofinish();
	}

	function _dopaymentaccept() {
		#retur hertil når kreditkort trans godkendt
		#skriv i ordren at betaling er gennemført
		$this->_dofinish();
	}

/*	function _dopaymentcancel() {
		#retur hertil når kreditkort trans annulleret
		#skriv i ordren at betaling er annulleret
		$this->_docancel();
	}*/
	
	function _dofinish() {
		$this->useTemplate('templatename_finish','templateid_finish','standard_shop_finish');
		$this->_getordervars();
		unset($_SESSION[$this->extname][$this->extconfigset]['basket']);
		# send to gange mails
	}

	function _getbasketvars() {
			$this->extresult['basket'] = $_SESSION[$this->extname][$this->extconfigset]['basket'];
			if ($this->extresult['basket']) {
				foreach($this->extresult['basket'] as $key => $val) {
					if ($this->extresult['basket'][$key]['objectid']) {
						$obj = owRead($this->extresult['basket'][$key]['objectid']);
						$this->extresult['basket'][$key]['item'] = $obj->elements[0];
						$this->extresult['basket'][$key] = array_merge($this->extresult['basket'][$key],$this->calcprice($this->extresult['basket'][$key]['objectid'],$this->extresult['basket'][$key]['num']));
					}
				}
			}
	}
					
	function _dobasket() {
		$this->useTemplate('templatename_basket','templateid_basket','standard_shop_basket');
		$this->_getbasketvars();
	}
		
	function _dolist() {
		$this->installtemplate('standard_shop_nav');
		$this->installtemplate('standard_shop_orderdetails');
		$this->useTemplate('templatename_list','templateid_list','standard_shop_list');
			$obj = owNew('itemgroup');
			if ($_REQUEST['_ext_parentid']) {
				$obj->listobjects($_REQUEST['_ext_parentid']);
			} else {
				$obj->listobjects();
			}
			$this->extresult['groups'] = $obj->elements;

			$obj = owNew('item');
			$mycols = owDatatypeCols('item');
			foreach ($mycols as $cur) {
				if (isset($_REQUEST[$cur['name']]) && !empty($_REQUEST[$cur['name']])) {
					$obj->setfilter_search($cur['name'],$_REQUEST[$cur['name']],EQUAL);
				}
			}
			if ($_REQUEST['_ext_parentid']) {
				$obj->listobjects($_REQUEST['_ext_parentid']);
			} else {
				$obj->listobjects();
			}
			$this->extresult['items'] = $obj->elements;
			if (!empty($this->extresult['items'])) {
				foreach ($this->extresult['items'] as $key => $val) {
					$this->extresult['items'][$key] = array_merge($this->extresult['items'][$key],$this->calcprice($this->extresult['items'][$key]['objectid']));
				}
			}
	}
	
	function _doitem() {
		$this->useTemplate('templatename_item','templateid_item','standard_shop_item');
			$obj = owNew('item');
			if ($_REQUEST['_ext_itemid']) {
				$obj->readobject($_REQUEST['_ext_itemid']);
			}
			$this->extresult['item'] = array_merge($obj->elements[0],$this->calcprice($_REQUEST['_ext_itemid']));

			$obj = owNew('itemgroup');
			if ($_REQUEST['_ext_parentid']) {
				$obj->listobjects($_REQUEST['_ext_parentid']);
			} else {
				$obj->listobjects();
			}
			$this->extresult['groups'] = $obj->elements;

			$obj = owNew('item');
			if ($_REQUEST['_ext_parentid']) {
				$obj->listobjects($_REQUEST['_ext_parentid']);
			} else {
				$obj->listobjects();
			}
			$this->extresult['items'] = $obj->elements;
			foreach ($this->extresult['items'] as $key => $val) {
				$this->extresult['items'][$key] = array_merge($this->extresult['items'][$key],$this->calcprice($this->extresult['items'][$key]['objectid']));
			}
	}
	
	function _do() {
		$this->extresult['viewcur'] = false;
		$this->extresult['viewdisc'] = false;
		switch ($this->extcmd) {

		case "add":
			$this->_doadd();
			break;
						
		case "empty":
			$this->_doempty();
			break;
						
		case "basket" :
			$this->_dobasket();
			break;

		case "item" :
			$this->_doitem();
			break;

		case "list" :
			$this->_dolist();
			break;

		case "order" :
			$this->_doorder();
			break;

		case "processorder" :
			$this->_doprocessorder();
			break;

		case "accept" :
			$this->_doaccept();
			break;

		case "processaccept" :
			$this->_doprocessaccept();
			break;

		case "payment" :
			$this->_dopayment();
			break;

		case "processpayment" :
			$this->_doprocesspayment();
			break;

		case "paymentaccept" :
			$this->_dopaymentaccept();
			break;

		case "finish" :
			$this->_dofinish();
			break;

		case "currency" :
			$this->_doprocesssetcurrency();
			break;

		default:
			$this->_dolist();
			
		}

		$obj = owNew('currency');
		$obj->listobjects();
		$this->extresult['currency'] = $obj->elements;
		$this->extresult['currencyid'] = $_SESSION[$this->extname][$this->extconfigset]['currencyid'];

		$obj = owNew('freight');
		$obj->listobjects();
		$this->extresult['shipping'] = $obj->elements;
		$this->extresult['shippingid'] = $_SESSION[$this->extname][$this->extconfigset]['shippingid'];

		$obj = owNew('payment');
		$obj->listobjects();
		$this->extresult['payment'] = $obj->elements;
		$this->extresult['paymentid'] = $_SESSION[$this->extname][$this->extconfigset]['paymentid'];

	}
}
?>