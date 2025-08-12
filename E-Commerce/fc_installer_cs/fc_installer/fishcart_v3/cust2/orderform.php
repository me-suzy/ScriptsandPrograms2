<?php 
// Final stop to process an order.

require("cc.php");

$sd=odbc_connect("TCP/IP PORTNUM","USERID","USERPW");
$lr=odbc_exec($sd,"select langfinl from LANGTABLE where langid=$lid");
if($lr==""){ echo "Error getting the language zone record.<br>"; exit; }
$lc=odbc_fetch_row($lr);
$final=odbc_result($lr,"langfinl");
odbc_free_result($lr);

if($billing_first=="" || $billing_last=="" ||
  ($billing_address1=="" && $billing_address2=="") ||
  $billing_city=="" || $billing_state=="" ||
  $billing_zip==""  || $billing_email=="" ||
  ($onoff=="on"&&$cc_name=="") ||
  ($onoff=="on"&&$ccexp_month=="") ||
  ($onoff=="on"&&$ccexp_year=="") ||
  ($onoff=="on"&&$cc_number=="") ){
  echo "</center><p><b>A required field has been left blank. ";
  echo "Please click the &quot;Back&quot; button on your browser ";
  echo "and make sure they are properly filled in.  Thank you.</b>\n";
  exit;
}

if($onoff=="on"||($onoff=="off"&&$cc_number!="")){
 if($cctype==""){
  echo "</center><p><b>";
	echo "Please click the &quot;Back&quot; button on your browser and ";
	echo "select the type of credit card.  Thank you.</b>\n";
  exit;
 }
 /* verify the cc */
 $cc_number=ereg_replace(" ","",$cc_number);
 $cc_number=ereg_replace("-","",$cc_number);	
 $cctype=strtoupper($cctype);
 $rv=CC($cctype,$cc_number);
 if($rv==0){?>
</center><br><b>
The credit card number as entered does not appear to be a valid
number.  Please click the &quot;Back&quot; button on your browser and
make sure that it is entered correctly.  To help make the number more 
readable, you may separate the credit card number into groups with blanks
or spaces as shown by the examples below.  Thank you.</b>
<pre>
1111 2222 3333 4444
  
  or

1111-2222-3333-4444
</pre>
<?php 
  exit;
 }
 
 $ccexp_year=(int)ereg_replace(" ","",$ccexp_year);
 // assume no CCs have expire years over 5 years out
 $lower=(int)date("Y",time());
 $upper=5+$lower;
 $res=(int)ereg("^[0-9]+$", $ccexp_year);
 if($res==0 || $lower > $ccexp_year || $upper < $ccexp_year){
  echo "</center><p><b>";
	echo "Please click the &quot;Back&quot; button on your browser and ";
	echo "enter a valid credit card expiration year.  Thank you.</b>\n";
  exit;
 }

 $ccexp_month=(int)ereg_replace(" ","",$ccexp_month);
 $res=(int)ereg("^[0-9]+$", $ccexp_month);
 if($res==0 || $ccexp_month < 1 || $ccexp_month > 12){
  echo "</center><p><b>";
	echo "Please click the &quot;Back&quot; button on your browser and ";
	echo "enter a valid credit card expiration month.  Thank you.</b>\n";
  exit;
 }

 if(strlen($ccexp_year)!=4){
  echo "</center><p><b>";
	echo "Please click the &quot;Back&quot; button on your browser and ";
	echo "enter a four digit credit card expiration year.  Thank you.</b>\n";
  exit;
 }
}  // onoff=="on"

$billing_areacode=
	substr(sprintf("%3d",ereg_replace("[^0-9]+","",$billing_areacode)),0,3);
$billing_phone=
	substr(sprintf("%7d",ereg_replace("[^0-9]+","",$billing_phone)),0,7);

$shipping_areacode=
	substr(sprintf("%3d",ereg_replace("[^0-9]+","",$shipping_areacode)),0,3);
$shipping_phone=
	substr(sprintf("%7d",ereg_replace("[^0-9]+","",$shipping_phone)),0,7);

$billing_zip =ereg_replace("[^0-9A-Za-z]+","",$billing_zip);
$shipping_zip=ereg_replace("[^0-9A-Za-z]+","",$shipping_zip);

$sd=odbc_connect("TCP/IP PORTNUM","USERID","USERPW");
$vr=odbc_exec($sd,"select * from VENDORTABLE where vendzid=$zid"); 
$vc=odbc_fetch_row($vr);

/* filter nasty shell escapes from the email address */
$billing_email=EscapeShellCmd($billing_email);

/* update inventory quantity in a central place */
$lr=odbc_exec($sd,"select * from ORDERLINE where orderid='$cartid'"); 
$lc=odbc_fetch_row($lr);
if($lc>0){
  while($lc){
	$sku=odbc_result($lr,"sku");
	$qty=(int)odbc_result($lr,"qty");
	$pr=odbc_exec($sd,"select produseinvq,prodinvqty from PRODTABLE ".
		"where prodzid=$zid and prodsku='$sku'");
	$pc=odbc_fetch_row($pr);
	$useq=(int)odbc_result($pr,"produseinvq");
	if($useq>0){
	 $iqty=(int)odbc_result($pr,"prodinvqty");
	 $iqty=$iqty-$qty;
	 $pr=odbc_exec($sd,"update PRODTABLE set prodinvqty=$iqty ".
		"where prodzid=$zid and prodsku='$sku'");
	}
	odbc_free_result($pr);
	$lc=odbc_fetch_row($lr);
  }
}

if($onoff=="on"){
	$fd=odbc_result($vr,"vendonline");
}elseif($onoff=="off"){
	$fd=odbc_result($vr,"vendofline");
}
require("$fd");
?>
