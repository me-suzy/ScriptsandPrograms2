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

$docpath="/usr/local/etc/httpd/fishcartsql/ORDACCT";
$ohead="$docpath/ORDERHEAD";
$oline="$docpath/ORDERLINE";

$lr=odbc_exec($sd,"select * from ORDERLINE where orderid='$cartid'"); 
$lc=odbc_fetch_row($lr);
if($lc==0){
	echo "Your shopping basket is empty!<br>";
	exit;
}

// assuming magic_gpm is on, remove the escaped single quotes
$billing_first=    stripslashes($billing_first);
$billing_last=     stripslashes($billing_last);
$billing_address1= stripslashes($billing_address1);
$billing_address2= stripslashes($billing_address2);
$billing_address3= stripslashes($billing_address3);
$billing_city=     stripslashes($billing_city);

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

$oh=fopen($ohead,"a");
if($oh<0){
	$now=date("m/d/y H:i",time());
	mail("mbrennen@fni.com","COMPANY Cart Error",
		"$now error opening $ohead:$oh");?>
<br>An internal error has occurred during order processing; the server
support staff has been contacted automatically.  We apologize for the
inconvenience, and we will investigate it as soon as possible.  Thank you
for your understanding.<p><a href="http://www.DOMAIN.ORG/">Home Page</a><p>
<?php exit;
}

$tmp =sprintf("%-13s",substr($cartid,0,13));			/* TranID */
$tmp.=sprintf("%-4s",substr($billing_sal,0,4));			/* Salutation */
$tmp.=sprintf("%-12s",substr($billing_first,0,12));		/* FirstName */
$tmp.=sprintf("%1s",substr($billing_mi,0,1));			/* MiddleInitial */
$tmp.=sprintf("%-20s",substr($billing_last,0,20));		/* LastName */
$tmp.="            ";									/* Spouse Name */
$tmp.=sprintf("%-30s",substr($billing_address1,0,30));	/* Address1 */
$tmp.=sprintf("%-30s",substr($billing_address2,0,30));	/* Address2 */
$tmp.=sprintf("%-30s",substr($billing_address3,0,30));	/* Address3 */
$tmp.=sprintf("%-20s",substr($billing_city,0,20));		/* City */
$tmp.=sprintf("%-2s",substr($billing_state,0,2));		/* State */
$tmp.=sprintf("%-5s",substr($billing_zip,0,5));			/* PostalCode */
if(5<strlen($billing_zip)){
 $tmp.=sprintf("%-4s",substr($billing_zip,5,4));		/* PostalCode */
}else{
 $tmp.="    ";											/* PostalCode */
}
$tmp.=sprintf("%-13s",substr($billing_phone,0,13));		/* Day Phone */
$tmp.="     ";											/* Extension */
$tmp.="             ";									/* Evening Phone */
$tmp.="    ";											/* Radio Call */
$tmp.=sprintf("%02d/",date("m",time()));				/* Date */
$tmp.=sprintf("%02d/",date("d",time()));				/* Date */
$tmp.=sprintf("%02d",date("y",time()));					/* Date */
$tmp.="     ";											/* Motive Code */

$tmp.="            ";									/* Ship Via */
$tmp.=sprintf("%-5s",substr($shipping_zip,0,5));		/* PostalCode */
if(5<strlen($billing_zip)){
 $tmp.=sprintf("%-4s",substr($shipping_zip,0,4));		/* PostalCode */
}else{
 $tmp.="    ";											/* PostalCode */
}
$ship="$shipping_first "."$shipping_last";
$tmp.=sprintf("%-30s",substr($ship,0,30));				/* ShipName */
$tmp.=sprintf("%-30s",substr($shipping_address1,0,30));	/* Address1 */
$tmp.=sprintf("%-30s",substr($shipping_address2,0,30));	/* Address2 */
$tmp.=sprintf("%-30s",substr($shipping_address3,0,30));	/* Address3 */
$tmp.=sprintf("%-20s",substr($shipping_city,0,20));		/* City */
$tmp.=sprintf("%-2s",substr($shipping_state,0,2));		/* State */
$ship="$shipping_city, $shipping_state $shipping_zip";
$tmp.=sprintf("%-30s",substr($ship,0,30));				/* Ship State */

$tmp.=sprintf("%-36s",substr($cc_number,0,36));			/* CardNumber */
$tmp.=sprintf("%-30s",substr($cc_name,0,30));			/* CardName */
$tmp.=sprintf("%-2s",substr($ccexp_month,0,2));			/* CardExpDate */
$tmp.=sprintf("%-2s",substr($ccexp_year,2,2));			/* CardExpDate */
$tmp.="  ";												/* Account Number */
$tmp.=sprintf("%11.2f",$stotal);						/* Subtotal */
$tmp.=sprintf("%11.2f",$shamt);							/* Shipping */
$tmp.=sprintf("%9.2f",$stax);							/* Salestax */
$tmp.=sprintf("%11.2f",$ototal);						/* Total */
$tmp.=sprintf("%10.2f",$contamt);						/* Contribution */
$tmp.=sprintf("%11.2f",$ttotal);						/* Amount Paid */
$tmp.=sprintf("%-30s",substr($billing_email,0,30));		/* Email */
$tmp.="\n";

// now escape re-escape the single quotes before writing
// quoting magic removes the escaping one.
$tmp=addslashes($tmp);

fputs($oh,$tmp);
fclose($oh);

$of=fopen($oline,"a");
if($of<0){
	$now=date("m/d/y H:i",time());
	mail("mbrennen@fni.com","COMPANY Cart Error",
		"$now error opening $oline:$of");?>
<br>An internal error has occurred during order processing; the server
support staff has been contacted automatically.  We apologize for the
inconvenience, and we will investigate it as soon as possible.  Thank you
for your understanding.<p><a href="http://www.DOMAIN.ORG/">Home Page</a><p>
<?php exit;
}

$i=0;
while($lc){
	$sku=odbc_result($lr,"sku");
	$qty=odbc_result($lr,"qty");
	$now=time();
    $pr=odbc_exec($sd,"select prodprice,prodsaleprice,prodsalebeg,prodsaleend".
        " from PRODTABLE where prodzid=$zid and prodsku='$sku'");
	$pc=odbc_fetch_row($pr);
    $slb=odbc_result($pr,"prodsalebeg");
    $sle=odbc_result($pr,"prodsaleend");
    if($slb<$now&&$now<$sle){
      $prc=doubleval(odbc_result($pr,"prodsaleprice"));
      $slprc=1;
    }else{
      $prc=doubleval(odbc_result($pr,"prodprice"));
    }
	odbc_free_result($pr);
	$ext=rnd(doubleval($prc)*$qty);

	$tmp =sprintf("%-13s",substr($cartid,0,13));		/* TranID */
	$tmp.=sprintf("%-5s",$sku);							/* ProductCode */
	$tmp.=sprintf("%5d",$qty);							/* Quantity */
	$tmp.=sprintf("%11.2f",$ext);						/* ExtPrice */
	$tmp.=sprintf("%4.2f",0);							/* Discount */
	$tmp.="\n";

	fputs($of,$tmp);
	$lc=odbc_fetch_row($lr);
	$i++;
}

fclose($of);

// TEMPORARY EMAIL ORDERS
require("cust1.order.inc");
// END OF TEMPORARY EMAIL ORDERS

require("CONFFILE");

odbc_exec($sd,"delete from ORDERHEAD where orderid='$cartid'"); 
odbc_exec($sd,"delete from ORDERLINE where orderid='$cartid'"); 
setcookie("CookieCOMPANYCart","");

$sd=odbc_connect("TCP/IP PORTNUM","USERID","USERPW");
$wr=odbc_exec($sd,"select * from WEBTABLE where webzid=$zid and weblid=$lid"); 
$wc=odbc_fetch_row($wr);

require("$final");
?>
