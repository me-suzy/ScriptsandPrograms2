<?php 
// Output fixed length records.
error_reporting(0);
require('round.inc');

$docpath="/usr/local/etc/httpd/fishcartsql/ORDACCT";
$ohead="$docpath/OHEADFILE";
$oline="$docpath/OLINEFILE";

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

if(empty($shipping_first)){$shipping_first=$billing_first;};
if(empty($shipping_last)){$shipping_last=$billing_last;};
if(empty($shipping_address1)){$shipping_address1=$billing_address1;};
if(empty($shipping_address2)){$shipping_address2=$billing_address2;};
if(empty($shipping_city)){$shipping_city=$billing_city;};
if(empty($shipping_state)){$shipping_state=$billing_state;};
if(empty($shipping_zip)){$shipping_zip=$billing_zip;};
if(empty($shipping_country)){$shipping_country=$billing_country;};
if(empty($shipping_areacode)){$shipping_areacode=$billing_areacode;};
if(empty($shipping_phone)){$shipping_phone=$billing_phone;};

$tstamp=time();
$oh=fopen($ohead,"a");
if($oh<0){
	$now=date("m/d/y H:i",$tstamp);
	mail("mbrennen@fni.com","COMPANY Cart Error",
		"$now error opening $ohead:$oh");?>
<br>An internal error has occurred during error processing; the server
support staff has been contacted automatically.  We apologize for the
inconvenience, and we will repair it as soon as possible.  Thank you
for your understanding.<p><a href="http://www.DOMAIN.ORG/">Home Page</a><p>
<?php exit;
}

$ttotalnd=sprintf("%07s",ereg_replace("\.","",(string)$ttotal));
$contamtnd=sprintf("%07s",ereg_replace("\.","",(string)$contamt));
$shamtnd=sprintf("%05s",ereg_replace("\.","",(string)$shamt));

$tmp =sprintf("%-8s",substr($cartid,3,8));				// TranID
$tmp.=sprintf("%-20s",substr($billing_last,0,20));		// LastName
$tmp.=sprintf("%-15s",substr($billing_first,0,15));		// FirstName
$tmp.=sprintf("%-30s",substr($billing_address1,0,30));	// Address1
$tmp.=sprintf("%-30s",substr($billing_address2,0,30));	// Address2
$tmp.=sprintf("%-20s",substr($billing_city,0,20));		// City
$tmp.=sprintf("%-2s",substr($billing_state,0,2));		// State
$tmp.=sprintf("%-6s",substr((string)$billing_zip,0,6));	// PostalCode
$tmp.=sprintf("%-2s",substr($cctype,0,2));				// CardType
$tmp.=sprintf("%-16s",substr($cc_number,0,16));			// CardNumber
$tmp.=sprintf("%7s",$ttotalnd);							// PurchaseAmount
$tmp.=sprintf("%7s",$ttotalnd);							// CardAmount
$tmp.=sprintf("%7s",$contamtnd);						// ContributionAmount
$tmp.=sprintf("%5s",$shamtnd);							// ShipHandleAmount
$tmp.=sprintf("%02d",(int)date("y",$tstamp));			// Creation Date
$tmp.=sprintf("%02d",(int)date("m",$tstamp));			// Creation Date
$tmp.=sprintf("%02d",(int)date("d",$tstamp));			// Creation Date
$tmp.=sprintf("%-20s",substr($shipping_last,0,20));		// ShipLastName
$tmp.=sprintf("%-15s",substr($shipping_first,0,15));	// ShipFirstName
$tmp.=sprintf("%-30s",substr($shipping_address1,0,30));	// Address1
$tmp.=sprintf("%-30s",substr($shipping_address2,0,30));	// Address2
$tmp.=sprintf("%-20s",substr($shipping_city,0,20));		// City
$tmp.=sprintf("%-2s",substr($shipping_state,0,2));		// State
$tmp.=sprintf("%-6s",substr((string)$shipping_zip,0,6));	// PostalCode
$tmp.=sprintf("%-3d",substr($billing_areacode,0,3));	// PhoneNumber
$tmp.=sprintf("%-7d",substr($billing_phone,0,7));		// PhoneNumber
$tmp.=sprintf("%02d",(int)substr($ccexp_month,0,2));	// CardExpDate
$tmp.=sprintf("%02d",(int)substr($ccexp_year,2,2));		// CardExpDate
$tmp.="        ";										// CallLetters
$tmp.=sprintf("%02d",(int)date("H",$tstamp));			// Hours 
$tmp.=sprintf("%02d",(int)date("i",$tstamp));			// Minutes
$tmp.=sprintf("%02d",(int)date("s",$tstamp));			// Seconds
$tmp.="    ";											// Operator
$tmp.="       ";										// NumberCalled
$tmp.="        ";										// SourceCode
$tmp.="        ";										// ProductCode
$tmp.="  ";												// Filler
$tmp.=" ";												// OrderType
$tmp.="        ";										// Account
$tmp.=sprintf("%-50s",substr($billing_email,0,50));		// Email
$tmp.="\n";

// now escape re-escape the single quotes before writing
// quoting magic removes the escaping one.
$tmp=addslashes($tmp);

fputs($oh,$tmp);
fclose($oh);

$of=fopen($oline,"a");
if($of<0){
	$now=date("m/d/y H:i",$tstamp);
	mail("mbrennen@fni.com","COMPANY Cart Error",
		"$now error opening $oline:$of");?>
<br>An internal error has occurred during error processing; the server
support staff has been contacted automatically.  We apologize for the
inconvenience, and we will repair it as soon as possible.  Thank you
for your understanding.<p><a href="http://www.DOMAIN.ORG/">Home Page</a><p>
<?php exit;
}

$i=0;
while($lc){
	$sku=odbc_result($lr,"sku");
	$qty=odbc_result($lr,"qty");
	$now=$tstamp;
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
	$ext=rnd((double)$prc)*$qty;
	$prc=sprintf("%05.2f",$prc);
	$ext=sprintf("%05.2f",$ext);
	$tprc=sprintf("%07s",ereg_replace("\.","",$prc));
	$tmpext=sprintf("%07s",ereg_replace("\.","",$ext));
	$sku=ereg_replace("-","",$sku);		// no special characters

	$tmp =sprintf("%-8s",substr($cartid,3,8));		// TranID
	$tmp.=sprintf("%06d",$i);						// ItemNumber
	$tmp.=sprintf("%-9s",$sku);						// ProductNumber
	$tmp.=" ";										// LanguageCode
	$tmp.="   ";									// Filler
	$tmp.=sprintf("%05d",$qty);						// Quantity
	$tmp.=sprintf("%7s",$tprc);						// PurchaseAmount
	$tmp.=sprintf("%7s",$tmpext);					// ExtPrice
	$tmp.="  ";										// Filler2
	$tmp.="\n";

	fputs($of,$tmp);
	$lc=odbc_fetch_row($lr);
	$i++;
}

fclose($of);

// TEMPORARY EMAIL ORDERS
// include("cust2.order.inc");
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
