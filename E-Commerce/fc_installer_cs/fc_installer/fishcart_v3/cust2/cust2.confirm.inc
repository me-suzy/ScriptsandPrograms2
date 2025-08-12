<?php 
// Final stop to process an order.

if($shipping_first==""){$shipping_first=$billing_first;};
if($shipping_last==""){$shipping_last=$billing_last;};
if($shipping_address1==""){$shipping_address1=$billing_address1;};
if($shipping_address2==""){$shipping_address2=$billing_address2;};
if($shipping_city==""){$shipping_city=$billing_city;};
if($shipping_state==""){$shipping_state=$billing_state;};
if($shipping_zip==""){$shipping_zip=$billing_zip;};
if($shipping_country==""){$shipping_country=$billing_country;};
if($shipping_phone==""){$shipping_phone=$billing_phone;};

$sd=odbc_connect("TCP/IP PORTNUM","USERID","USERPW");
$vr=odbc_exec($sd,"select * from VENDORTABLE where vendzid=$zid"); 
$vc=odbc_fetch_row($vr);

$cf=popen("/usr/sbin/sendmail -t","w");

// filter nasty shell escapes from the email address 
// $billing_email=EscapeShellCmd($billing_email);

fputs($cf,"To: $billing_email\n");
fputs($cf,"From: ORDERCONF\n");
fputs($cf,"Subject: Order Confirmation\n\n");

fputs($cf,"This is the confirmation copy of your order placed to\nCOMPANY.  ");
fputs($cf,"Thank you for your order!\n");

// BILLING INFORMATION BLOCK
fputs($cf,"\n");
$tmp=sprintf("Billing Information:  %s %s\n",
		$billing_first, $billing_last);
fputs($cf,"$tmp");
if($billing_address1!=""){
 $tmp=sprintf("               Attn:  %s\n",$billing_address1);
 fputs($cf,$tmp);
}
if($billing_address2!=""){
 $tmp=sprintf("                      %s\n",$billing_address2);
 fputs($cf,$tmp);
}
$tmp=sprintf("                      %s, ",$billing_city);
fputs($cf,$tmp);
$tmp=sprintf("%s  ",$billing_state);
fputs($cf,$tmp);
$tmp=sprintf("%s  ",$billing_zip);
fputs($cf,$tmp);
$tmp=sprintf("%s\n",$billing_country);
fputs($cf,$tmp);
$tmp=sprintf("                      %s",$billing_phone);
// if($billing_ext!=""){
//   $tmp.=sprintf(" x.%s",$billing_ext);
// }
fputs($cf,$tmp);

fputs($cf,"\n");
$tmp=sprintf("E-Mail Address:       %s\n",$billing_email);
fputs($cf,$tmp);

// PRODUCT INFORMATION BLOCK
fputs($cf,"\n");
$tmp="ITEM         QTY      PRICE     PRODUCT\n\n";
fputs($cf,$tmp);

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
      $prc=(double)odbc_result($pr,"prodsaleprice");
      $slprc=1;
    }else{
      $prc=(double)odbc_result($pr,"prodprice");
    }
	odbc_free_result($pr);
	$ext=rnd((double)$prc*$qty);
	$pr=odbc_exec($sd,"select prodsdescr from PRODLANG ".
		"where prodlzid=$zid and prodlid=$lid and prodlsku='$sku'");
	$pc=odbc_fetch_row($pr);
	$dsc=odbc_result($pr,"prodsdescr");
	odbc_free_result($pr);

	$tmp =sprintf("%-13s",$sku);		/* ProductNumber */
	$tmp.="";
	$tmp.=sprintf("%3d",$qty);			/* Quantity */
	$tmp.="     ";
	$tmp.=sprintf("%8.2f",$ext);		/* ExtPrice */
	$tmp.="   ";
	$tmp.=sprintf("%s",$dsc);														/* ExtPrice */
	$tmp.="\n";
	fputs($cf,$tmp);
	$lc=odbc_fetch_row($lr);
	$i++;
}
fputs($cf,"\n");
$tmp=sprintf("       Subtotal:     %8.2f\n",$stotal);
fputs($cf,$tmp);
$tmp=sprintf("       Shipping:     %8.2f\n",$shamt);
fputs($cf,$tmp);
if($stax>0){
 $tmp=sprintf("      Sales Tax:     %8.2f\n",$stax);
 fputs($cf,$tmp);
}
if($contamt>0){
 $tmp=sprintf("  Product Total:     %8.2f\n",$ototal);
 fputs($cf,$tmp);
 $tmp=sprintf("   Contribution:     %8.2f\n",$contamt);
 fputs($cf,$tmp);
}
$tmp=sprintf("    Order Total:     %8.2f\n",$ttotal);
fputs($cf,$tmp);

/* SHIPPING INFORMATION BLOCK */
fputs($cf,"\n");
$tmp=sprintf("Shipping Address:     %s %s\n", $shipping_first,$shipping_last);
fputs($cf,$tmp);
if($shipping_address1!=""){
 $tmp=sprintf("            Attn:     %s\n",$shipping_address1);
 fputs($cf,$tmp);
}
if($shipping_address2!=""){
 $tmp=sprintf("                      %s\n",$shipping_address2);
 fputs($cf,$tmp);
}
$tmp=sprintf("                      %s, ",$shipping_city);
fputs($cf,$tmp);
$tmp=sprintf("%s  ",$shipping_state);
fputs($cf,$tmp);
$tmp=sprintf("%s  ",$shipping_zip);
fputs($cf,$tmp);
$tmp=sprintf("%s\n",$shipping_country);
fputs($cf,$tmp);

// VENDOR INFORMATION BLOCK
fputs($cf,"\n____________________\n");
$tmp=sprintf("%s\n", odbc_result($vr,"vsvcname"));
fputs($cf,"$tmp");
$tmp=odbc_result($vr,"vsvcaddr1");
if($tmp!=""){
 $tmp=sprintf("%s\n",$tmp);
 fputs($cf,$tmp);
}
$tmp=odbc_result($vr,"vsvcaddr2");
if($tmp!=""){
 $tmp=sprintf("%s\n",$tmp);
 fputs($cf,$tmp);
}
$tmp=sprintf("%s,  %s  %s  %s\n",
	odbc_result($vr,"vsvccity"),
	odbc_result($vr,"vsvcstate"),
	odbc_result($vr,"vsvczip"),
	odbc_result($vr,"vsvcnatl"));
fputs($cf,$tmp);
$tmp=odbc_result($vr,"vsvcphone");
if($tmp!=""){
 $tmp=sprintf("Phone: %s\n", $tmp);
 fputs($cf,$tmp);
}
$tmp=odbc_result($vr,"vsvcfax");
if($tmp!=""){
 $tmp=sprintf("Fax: %s\n", $tmp);
 fputs($cf,$tmp);
}
pclose($cf);
?>

<input type="hidden" name="cartid" value="<?php echo $cartid?>">
<input type="hidden" name="zid" value="<?php echo $zid?>">
<input type="hidden" name="lid" value="<?php echo $lid?>">

</center>
</body>
</html>
