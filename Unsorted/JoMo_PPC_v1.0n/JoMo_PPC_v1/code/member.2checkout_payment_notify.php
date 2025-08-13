<?
/*
###############################
#
# JoMo Easy Pay-Per-Click Search Engine v1.0
#
#
###############################
#
# Date                 : September 16, 2002
# supplied by          : CyKuH [WTN]
# nullified by         : CyKuH [WTN]
#
#################
#
# This script is copyright L 2002-2012 by Rodney Hobart (JoMo Media Group),
All Rights Reserved.
#
# The use of this script constitutes acceptance of any terms or conditions,
#
# Conditions:
#  -> Do NOT remove any of the copyright notices in the script.
#  -> This script can not be distributed or resold by anyone else than the
author, unless special permisson is given.
#
# The author is not responsible if this script causes any damage to your
server or computers.
#
#################################

*/
?>
<?PHP	
//MailDump();


$dbSet->open("SELECT optionName, value FROM adminoptions where optionCategory='x2checkout'");
	while ($row=$dbSet->fetchArray()){
	if($row['optionName']=='UnigueNumber')
		$seller_number = $row['value'];
	if($row['optionName']=='UniqueWord')
		$unique_word = $row['value'];;
	}

$string = strtoupper(md5($unique_word."$seller_number"."$order_number"."$total"));

$memberID = $cart_order_id;
$amount = $total;

if($key == $string){
	if ($memberID>0 && $demo!='Y') {
		changeAccountBalance("member",$memberID, $amount, "deposit", 1);
		$msg = "You have done payment.";
		Header("Location: index.php?mode=members&memberMode=account&msg=$msg");
	} else {
		$msg = "You are in Demo Mode.";
		Header("Location: index.php?mode=members&memberMode=account&msg=$msg");
	}
} else {
	$name = "Admin";
	$email = __CFG_ADMIN_EMAIL;
	if ($fromName=="")
  		$from = getOption("notificationName");
  	if ($fromEmail=="")
  		$from = getOption("notificationEmail");
  	$subject = "Wrong Tranzaction from 2checkout.com";
  	$message = "Wrong Tranzaction from 2checkout.com";
	$html = "<b>Hello, $name.</b><br><p>$message</p>";
  	$text = "Hello, $name.\n$message";
  	
	sendMailProfile($name, $email,$fromName,$fromEmail, $subject,$html,$text,array(),array());
}

exit;
?>