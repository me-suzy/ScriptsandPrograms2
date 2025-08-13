<?

$dbh=mysql_connect ("localhost", "ebuilder_shop", "30-245346") or die ('I cannot connect to the database.');
mysql_select_db ("ebuilder_shop");
//Paypal Stuff
$paypalemail = "limitup@seekstorm.com";
$returnurl = "http://www.ebuilders.ws/demos/shop/thankyou.php";
$cancelurl = "http://www.ebuilders.ws/demos/shop/cancel.php";
$sslurl = "http://www.ebuilders.ws/demos/shop/graphics/ssllogo.gif";
//Secure URL
$secureurl = "http://www.ebuilders.ws/demos/shop";
//No Secure
$nosecureurl = "http://www.ebuilders.ws/demos/shop";
?>
