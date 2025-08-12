<?php 
require('round.inc');

/* Output fixed length records. */
error_reporting(0);

$lr=solid_exec($sd,"select * from ORDERLINE where orderid='$cartid'"); 
$lc=solid_fetchrow($lr);
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

include("cust2.order.inc");
include("CONFFILE");

solid_exec($sd,"delete from ORDERHEAD where orderid='$cartid'"); 
solid_exec($sd,"delete from ORDERLINE where orderid='$cartid'"); 
setcookie("CookieCOMPANYCart");

$sd=solid_connect("TCP/IP PORTNUM","USERID","USERPW");
$wr=solid_exec($sd,"select * from WEBTABLE where webzid=$zid and weblid=$lid"); 
$wc=solid_fetchrow($wr);

include("$final");
>
