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
// | $RCSfile: gateway.paypal.php,v $ - $Revision: 1.5 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);

// ############################################################################
// PayPal information
$_processor_info['paypal'] = array(
	'name' => 'PayPal',
	'url' => 'http://www.paypal.com/',
	'email' => '',
	'form' => array(
		'action' => 'https://www.paypal.com/cgi-bin/webscr',
		'method' => 'post',
		'fields' => array(
			'cmd' => '_xclick',
			'business' => '$processor[email]',
			'item_name' => '$planname',
			'item_number' => '$cartid',
			'amount' => '$cost',
			'no_shipping' => '1',
			'return' => '$appurl/options.subscription.php?cmd=thankyou',
			'cancel_return' => '$appurl/options.subscription.php',
			'no_note' => '1',
			'currency_code' => 'USD',
		),
	),
);

?>