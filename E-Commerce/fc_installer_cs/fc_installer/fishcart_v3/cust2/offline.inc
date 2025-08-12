<?php 
require('round.inc');

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

$sd=odbc_connect("TCP/IP PORTNUM","USERID","USERPW");
$vr=odbc_exec($sd,"select * from VENDORTABLE where vendzid=$zid"); 
$vc=odbc_fetch_row($vr);?>

<html>
<head>
<title>
Order Form
</title>
</head>
<h2 align=center>COMPANY</h2>
<h3 align=center>Order Form</h3>

<pre><?php 
$tmp=sprintf("Billing Information:  %s %s\n", $billing_first, $billing_last);
echo "$tmp";
if($billing_address1!=""){
 $tmp=sprintf("               Attn:  %s\n",$billing_address1);
 echo $tmp;
}
if($billing_address2!=""){
 $tmp=sprintf("                      %s\n",$billing_address2);
 echo $tmp;
}
if($billing_address3!=""){
 $tmp=sprintf("                      %s\n",$billing_address3);
 echo $tmp;
}
$tmp=sprintf("                      %s, ",$billing_city);
echo $tmp;
$tmp=sprintf("%s  ",$billing_state);
echo $tmp;
$tmp=sprintf("%s  ",$billing_zip);
echo $tmp;
$tmp=sprintf("%s\n",$billing_country);
echo $tmp;
$tmp=sprintf("                      %s",$billing_phone);
if($billing_ext!=""){
 $tmp.=sprintf(" x.%s",$billing_ext);
}
echo $tmp;

echo "\n";
$tmp=sprintf("E-Mail Address:       %s\n",$billing_email);
echo $tmp;

// PRODUCT INFORMATION BLOCK
echo "\n";
$tmp="ITEM         QTY      PRICE     PRODUCT\n\n";
echo $tmp;

$lr=odbc_exec($sd,"select * from ORDERLINE where orderid='$cartid'"); 
$lc=odbc_fetch_row($lr);
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
	$pr=odbc_exec($sd,"select prodsdescr from PRODLANG ".
		"where prodlzid=$zid and prodlid=$lid and prodlsku='$sku'");
	$pc=odbc_fetch_row($pr);
	$dsc=odbc_result($pr,"prodsdescr");
	odbc_free_result($pr);

	$tmp =sprintf("%5s",$sku);			/* ProductNumber */
	$tmp.="        ";
	$tmp.=sprintf("%3d",$qty);			/* Quantity */
	$tmp.="     ";
	$tmp.=sprintf("%8.2f",$ext);		/* ExtPrice */
	$tmp.="   ";
	$tmp.=sprintf("%s",$dsc);														/* ExtPrice */
	$tmp.="\n";
	echo $tmp;
	$lc=odbc_fetch_row($lr);
	$i++;
}
echo "\n";
$tmp=sprintf("       Subtotal:     %8.2f\n",$stotal);
echo $tmp;
$tmp=sprintf("       Shipping:     %8.2f\n",$shamt);
echo $tmp;
if($stax>0){
 $tmp=sprintf("      Sales Tax:     %8.2f\n",$stax);
 echo $tmp;
}
if($contamt>0){
 $tmp=sprintf("  Product Total:     %8.2f\n",$ototal);
 echo $tmp;
 $tmp=sprintf("   Contribution:     %8.2f\n",$contamt);
 echo $tmp;
}
$tmp=sprintf("    Order Total:     %8.2f\n",$ttotal);
echo $tmp;

// SHIPPING INFORMATION BLOCK
echo "\n";
$tmp=sprintf("Shipping Address:     %s %s\n", $shipping_first,$shipping_last);
echo $tmp;
if($shipping_address1!=""){
 $tmp=sprintf("            Attn:     %s\n",$shipping_address1);
 echo $tmp;
}
if($shipping_address2!=""){
 $tmp=sprintf("                      %s\n",$shipping_address2);
 echo $tmp;
}
if($shipping_address3!=""){
 $tmp=sprintf("                      %s\n",$shipping_address3);
 echo $tmp;
}
$tmp=sprintf("                      %s, ",$shipping_city);
echo $tmp;
$tmp=sprintf("%s  ",$shipping_state);
echo $tmp;
$tmp=sprintf("%s  ",$shipping_zip);
echo $tmp;
$tmp=sprintf("%s\n",$shipping_country);
echo $tmp;

if($cc_number!=""){
 // CREDIT INFORMATION BLOCK
 echo "\n";
 $tmp=sprintf("Credit Card:          %s\n",$cctype);
 echo $tmp;
 $tmp=sprintf("Customer Name on CC:  %s\n",$cc_name);
 echo $tmp;
 $tmp=sprintf("CC #:                 %s\n",$cc_number);
 echo $tmp;
 $tmp=sprintf("CC Exp Date:          %s/%s\n",$ccexp_month,$ccexp_year);
 echo $tmp;
 echo "\nSignature:            _____________________________\n";
}
 
// VENDOR INFORMATION BLOCK
$fax=odbc_result($vr,"vsvcfax");
if($fax==""){?>
_______________________________________________________________
<i>Please print, sign and mail to the address below.  Thank you!</i>
<?php }else{?>
_________________________________________________________________________
<i>Please print, sign and mail or fax to the address below.  Thank you!</i>
<?php }
$tmp=sprintf("\n%s\n", odbc_result($vr,"vsvcname"));
echo "$tmp";
$tmp=odbc_result($vr,"vsvcaddr1");
if($tmp!=""){
 $tmp=sprintf("%s\n",$tmp);
 echo $tmp;
}
$tmp=odbc_result($vr,"vsvcaddr2");
if($tmp!=""){
 $tmp=sprintf("%s\n",$tmp);
 echo $tmp;
}
$tmp=sprintf("%s,  %s  %s  %s\n",
	odbc_result($vr,"vsvccity"),
	odbc_result($vr,"vsvcstate"),
	odbc_result($vr,"vsvczip"),
	odbc_result($vr,"vsvcnatl"));
echo $tmp;
$tmp=odbc_result($vr,"vsvcphone");
if($tmp!=""){
 $tmp=sprintf("Phone: %s\n", $tmp);
 echo $tmp;
}
if($fax!=""){
 $tmp=sprintf("Fax: %s\n", $fax);
 echo $tmp;
}
?></pre>
<p>
<a href="HOMEURL/">COMPANY Home Page</a>
</body>
</html>
<?php 
// delete the order
odbc_exec($sd,"delete from ORDERHEAD where orderid='$cartid'"); 
odbc_exec($sd,"delete from ORDERLINE where orderid='$cartid'"); 
setcookie("CookieCOMPANYCart","");
?>
