<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: gateway.2co.php,v $ - $Revision: 1.3 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// PayPal information
$_processor_info['2co'] = array(
	'name' => '2CheckOut',
	'url' => 'http://www.2checkout.com/',
	'sellerid' => '',
	'secretkey' => '',
	'form' => array(
		'action' => 'https://www.2checkout.com/cgi-bin/sbuyers/cartpurchase.2c',
		'method' => 'get',
		'fields' => array(
			'sid' => '$processor[sellerid]',
			'cart_order_id' => '$cartid',
			'total' => '$cost',
		),
	),
);

?>