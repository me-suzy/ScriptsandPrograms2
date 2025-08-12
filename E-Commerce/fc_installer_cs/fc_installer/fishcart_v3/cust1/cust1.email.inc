<?php 
// round a floating point number to 2 decimals
function rnd ($n) {
	$t1=(int)($n*1000);
	$t2=$t1+5;
	$t=(int)($t2/10)*10;
	return((double)($t)/1000);
}

// Output fixed length records.
error_reporting(0);

$lr=odbc_exec($sd,"select * from ORDERLINE where orderid='$cartid'"); 
$lc=odbc_fetch_row($lr);
if($lc==0){
	echo "Your shopping basket is empty!<br>";
	exit;
}

if($shipping_first==""){$shipping_first=$billing_first;};
if($shipping_last==""){$shipping_last=$billing_last;};
if($shipping_address1==""){$shipping_address1=$billing_address1;};
if($shipping_address2==""){$shipping_address2=$billing_address2;};
if($shipping_address3==""){$shipping_address3=$billing_address3;};
if($shipping_city==""){$shipping_city=$billing_city;};
if($shipping_state==""){$shipping_state=$billing_state;};
if($shipping_zip==""){$shipping_zip=$billing_zip;};
if($shipping_country==""){$shipping_country=$billing_country;};
if($shipping_phone==""){$shipping_phone=$billing_phone;};

require("cust1.order.inc");
require("CONFFILE");

odbc_exec($sd,"delete from ORDERHEAD where orderid='$cartid'"); 
odbc_exec($sd,"delete from ORDERLINE where orderid='$cartid'"); 
setcookie("CookieCOMPANYCart","");

$sd=odbc_connect("TCP/IP PORTNUM","USERID","USERPW");
$wr=odbc_exec($sd,"select * from WEBTABLE where webzid=$zid and weblid=$lid"); 
$wc=odbc_fetch_row($wr);

require("$final");
?>
