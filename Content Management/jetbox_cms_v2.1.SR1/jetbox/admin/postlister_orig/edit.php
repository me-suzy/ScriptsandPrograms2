<?php
$pagetitle="List properties";
$sel_tab="2.4";
require("functions.php");
sidehoved();
vaelgListe($PHP_SELF);
$tilmeldingsbeske=$_REQUEST["tilmeldingsbeske"];
$afmeldingsbesked=$_REQUEST["afmeldingsbesked"];
$standardafsender=$_REQUEST["standardafsender"];
$signatur=$_REQUEST["signatur"];
$liste=$_REQUEST["liste"];

//$tilmeldingsbeske=$_REQUEST["tilmeldingsbeske"];

if ($_REQUEST["send"]) {
	//if (!ereg("\[SUBSCRIBE_URL\]", $tilmeldingsbesked)) {
	//	fejl($s31);
	//}
	//if (!ereg("\[UNSUBSCRIBE_URL\]", $afmeldingsbesked)) {
	//	fejl($s82);
	//}
	$liste = addslashes($_REQUEST['liste']);
	$tilmeldingsbesked = addslashes($tilmeldingsbesked);
	$afmeldingsbesked = addslashes($afmeldingsbesked);
	$signatur = addslashes($signatur);

	mysql_prefix_query("update $mainTable set
		tilmeldingsbesked = '$tilmeldingsbesked',
		afmeldingsbesked = '$afmeldingsbesked',
		standardafsender = '$standardafsender',
		signatur = '$signatur'
	where liste = '$liste'");
	echo "$s33\n";
	sidefod();
	exit;
}
$liste = addslashes($_REQUEST['liste']);
$kommando = mysql_prefix_query("select * from $mainTable where liste = '$liste'");
$resultat = mysql_fetch_array($kommando);
$hentStandardafsender = htmlspecialchars(stripslashes($resultat["standardafsender"]));
$hentSignatur = htmlspecialchars(stripslashes($resultat["signatur"]));
$hentTilmeldingsbesked = htmlspecialchars(stripslashes($resultat["tilmeldingsbesked"]));
$hentAfmeldingsbesked = htmlspecialchars(stripslashes($resultat["afmeldingsbesked"]));
echo "<form action=\"$PHP_SELF?liste=$liste\" method=post>\n";
echo "$s27<br>\n";
echo "<input type=text name=standardafsender value=\"$hentStandardafsender\" size=40 maxlength=100><p>\n";
echo "$s28<br>\n";
echo "<textarea name=signatur cols=72 rows=14 wrap=soft>$hentSignatur</textarea><p>\n";
//echo "$s29<br>\n";
//echo "<textarea name=tilmeldingsbesked cols=72 rows=8 wrap=soft>$hentTilmeldingsbesked</textarea><p>\n";
//echo "$s83<br>\n";
//echo "<textarea name=afmeldingsbesked cols=72 rows=8 wrap=soft>$hentAfmeldingsbesked</textarea><p>\n";
echo "<input type=submit name=send value=\"$s30\">\n";
echo "</form>\n";
sidefod();
?>