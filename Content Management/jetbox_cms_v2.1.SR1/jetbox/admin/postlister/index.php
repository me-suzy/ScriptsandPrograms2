<?php
$pagetitle="Compose";
$sel_tab="2.1";

require("functions.php");

sidehoved($s2);
vaelgListe($PHP_SELF);

if ($skrivSend) {
	if ($skrivLinjebrydning == 1) {
		$skrivEbrev = ereg_replace("(.{1,71}) ", "\\1\n", $skrivEbrev." ");
	}

	mysql_query("update $mainTable
		set afsender = '".addslashes($skrivAfsender)."',
		emne = '".addslashes($skrivEmne)."',
		ebrev = '".addslashes($skrivEbrev)."'
		where liste = '".addslashes($liste)."'");

	echo "<table border=0 cellspacing=0 cellpadding=5>\n";
	echo "<tr><td style=\"background: black\">\n";

	echo "<table border=0 cellspacing=0 cellpadding=5>\n";
	echo "<tr><td style=\"background: silver; vertical-align: top\">\n";

	echo "<b>$s52</b><br>\n";
	echo "<b>$s66</b><br>\n";
	echo "<b>$s53</b>\n";

	echo "</td>\n";
	echo "<td style=\"background: silver; vertical-align: top\">\n";

	echo "".htmlspecialchars(stripslashes($skrivAfsender))."<br>\n";
	echo "[RCPT_EMAIL]<br>\n";
	echo "".htmlspecialchars(stripslashes($skrivEmne))."\n";

	echo "</td></tr>\n";
	echo "<tr><td colspan=2 style=\"background: white; vertical-align: top\">\n";

	echo "<pre>\n";
	echo "".htmlspecialchars(stripslashes($skrivEbrev))."<br>\n";
	echo "</pre>\n";

	echo "</td></tr>\n";
	echo "</table>\n";

	echo "</td></tr>\n";
	echo "</table>\n";



	# Getting the number of confirmed email addresses in the table:
	$iAlt = mysql_num_rows(mysql_query("select godkendt from ".addslashes($liste)." where godkendt = '1'"));

	echo "<form action=\"send.php\" method=get>\n";
	echo "<input type=hidden name=liste value=\"$liste\">\n";
	echo "<input type=hidden name=start value=0>\n";
	echo "<input type=hidden name=iAlt value=$iAlt>\n";
	echo "<input type=submit value=\"$s67\">\n";
	echo "<input type=button value=\"$s68\" onClick=history.back()>\n";
	echo "</form>\n";

	sidefod();
	exit;
}



$liste = addslashes($liste);
$kommando = mysql_query("select standardafsender, signatur from $mainTable where liste = '$liste'");
$resultat = mysql_fetch_array($kommando);

$vaerdiStandardafsender = htmlspecialchars(stripslashes($resultat[standardafsender]));
$vaerdiSignatur = htmlspecialchars(stripslashes($resultat[signatur]));
if ($vaerdiSignatur) $vaerdiSignatur = "\n\n\n-- \n$vaerdiSignatur";



echo "<script language=javascript><!--\n";
echo "function funktioner() {\n";
echo "	formular = document.forms[0]\n";
echo "\n";
echo "		tekst = document.forms[0].skrivEbrev.value\n";
echo "\n";
echo "		antalTegn = tekst.length\n";
echo "		antalOrd = tekst.split(\" \")\n";
echo "\n";
echo "		alert(\"$s60 \"+antalTegn+\"\\n$s61 \"+antalOrd.length)\n";
echo "}\n";

echo "// --></script>\n";



echo "<form action=\"$PHP_SELF?liste=$liste\" method=post>\n";
echo "<h3>$s51</h3>\n";
echo "$s52<br>\n";
echo "<input type=text name=skrivAfsender size=50 value=\"$vaerdiStandardafsender\"><br>\n";
echo "$s53<br>\n";
echo "<input type=text name=skrivEmne size=50 value=\"\"><br>\n";
echo "$s54<br>\n";
echo "<textarea name=skrivEbrev cols=80 rows=30 wrap=soft>$vaerdiSignatur</textarea><br>\n";
echo "<input type=checkbox name=skrivLinjebrydning value=1 checked>\n";
echo "$s55<br>\n";
echo "<input type=submit name=skrivSend value=\"$s56\">\n";
echo "<a href=\"javascript:funktioner('statistik');\">$s58</a>";
echo "</form>\n";
echo "<hr>\n";
echo "$s63\n";
echo "<ul>\n";
echo "<li><b>[RCPT_EMAIL]</b><br>\n";
echo "$s64<p>\n";
echo "<li><b>[UNSUBSCRIBE_URL]</b><br>\n";
echo "$s65\n";
echo "</ul>\n";
sidefod();
?>
