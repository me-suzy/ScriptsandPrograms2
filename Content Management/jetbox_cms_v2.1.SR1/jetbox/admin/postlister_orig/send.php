<?php
require("functions.php");
$skrivSend=$_REQUEST["skrivSend"];
$skrivAfsender=$_REQUEST["skrivAfsender"];
$skrivEmne=$_REQUEST["skrivEmne"];
$skrivEbrev=$_REQUEST["skrivEbrev"];

$start=$_REQUEST["start"];
$liste=$_REQUEST["liste"];
$iAlt=$_REQUEST["iAlt"];


# No-cache headers:

mysql_prefix_query("update $mainTable set date = '".date("Y-m-d", time()-(60*60*24))."' where liste = '".addslashes($_REQUEST['liste'])."'");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
sidehoved("", 0);
echo "<center>\n";
$kommando = mysql_prefix_query("select afsender, emne, ebrev from $mainTable where liste = '".addslashes($_REQUEST['liste'])."'");
$resultat = mysql_fetch_array($kommando);
$af = stripslashes($resultat[afsender]);
$em = stripslashes($resultat[emne]);
$te = stripslashes($resultat[ebrev]);
if ($start < $iAlt) {
	if (($slut + 10) > $iAlt){
		$slut = $iAlt;
	}
	else{
		$slut = 10;
	}
	//hier query uit webusers waar die mailing list van is.
	if ($liste=='news') {
	    $cl='newsmail';
	}
	elseif ($liste=='events') {
	    $cl='eventmail';
	}
	elseif ($liste=='internalnews') {
	    $cl='internalmail';
	}
	$kommando = mysql_prefix_query("SELECT email, firstname, middlename, lastname FROM webuser WHERE  $cl='1' limit ".addslashes($start).", $slut") or die (mysql_error());
	if (($start + 10) > $iAlt){
		$naeste = $iAlt;
	}
	else{
		$naeste = $start + 10;
	}
	$fra = $start + 1;
	echo "$s86 $fra $s87 $naeste $s97 $iAlt.\n";
	echo "<h3>$s88</h3>\n";
	while ($resultat = mysql_fetch_array($kommando)) {
		$ep = stripslashes($resultat[email]);
		$id = stripslashes($resultat[id]);
		$nyte = str_replace("[RCPT_EMAIL]", $ep, $te);
		//$nyte = str_replace("[SUBSCRIBE_URL]", "http://$HTTP_HOST".dirname($PHP_SELF)."/confirm.php?liste=$liste&abonner=1&epostadresse=".urlencode($ep)."&id=$id", $nyte);
		//$nyte = str_replace("[UNSUBSCRIBE_URL]", "http://$HTTP_HOST".dirname($PHP_SELF)."/confirm.php?liste=$liste&abonner=0&epostadresse=".urlencode($ep)."&id=$id", $nyte);
		if (mail($ep, $em, $nyte, "From: $af\n$ekstraHeadere")){
		}
		else{
			echo "us noet goet";
		}
	}
	echo "<script language=\"javascript\">\n";
	echo "<!--\n";
	echo "window.location.href = \"$jetstream_url/../postlister/$PHP_SELF?liste=$liste&start=$naeste&iAlt=$iAlt\"\n";
	echo "// -->\n";
	echo "</script>\n";
}
else {
	echo "<h3>$s85</h3>\n";
	echo "<form action='index.php' method='post'>\n";
	echo "<input type='submit' value='$s104'>\n";
	echo "</form>\n";
}
echo "</center>\n";
sidefod();
?>