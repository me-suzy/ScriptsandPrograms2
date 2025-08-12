<?php
$pagetitle="Create/ delete lists";
$sel_tab="2.5";
require("functions.php");
$pagetitle="Create/ delete lists";
sidehoved();

if ($listeOpret) {
	# The name of the table must include at least one character (naturally),
	# and numbers are allowed. However, table names consisting *only*
	# of numbers are not allowed.
	if (!ereg("^[A-Za-z]+[0-9]*$", $listeOpret)) {
		# "Error: The name of the table is invalid":
		fejl($s10);
	}
	$listeOpret = addslashes($listeOpret);
	mysql_query("create table $listeOpret (
		epostadresse char ( 50 ),
		id char ( 20 ),
		godkendt int ( 1 ),
		date date
	)");
	mysql_query("insert into $mainTable ( liste, tilmeldingsbesked, afmeldingsbesked ) values (
		'$listeOpret',
		'$s32',
		'$s81'
	)");
	echo "$s22\n";
	sidefod();
	exit;
}

if ($listeSlet) {
	echo "$s23<p>\n";
	echo "<form action=$PHP_SELF method=post>\n";
	echo "<input type=hidden name=listeSletBekraeft value=\"$listeSlet\">\n";
	echo "<input type=submit value=\"$s25\">\n";
	echo "<input type=button value=\"$s24\" onClick=history.back()>\n";
	echo "</form>\n";
	sidefod();
	exit;
}

if ($listeSletBekraeft) {
	$listeSletBekraeft = addslashes($listeSletBekraeft);
	mysql_query("drop table $listeSletBekraeft");
	mysql_query("delete from $mainTable where liste = '$listeSletBekraeft'");
	echo "$s26\n";
	sidefod();
	exit;
}

echo "<font size=2><b>$s16</b></font>\n<br>";
echo "$s18<p>\n";
echo "<form action=\"$PHP_SELF\" method=post>\n";
echo "$s17<br>\n";
echo "<input type=text name=listeOpret size=20 maxlength=20>\n";
echo "<input type=submit value=\"$s15\">\n";
echo "</form><br><br>\n";
echo "<hr>\n";
echo "<font size=2><b>$s19</b></font>\n<br>";
echo "$s20<br>\n";
echo "<form action=\"$PHP_SELF\" method=post>\n";
$kommando = mysql_query("select liste from $GLOBALS[mainTable]");
$antalRaekker = mysql_num_rows($kommando);

if ($antalRaekker == 0) {
	# "There are no lists":
	echo "$s14\n";
}
else {
	echo "<select name=listeSlet>\n";
	while ($resultat = mysql_fetch_array($kommando)) {
		echo "<option value=\"$resultat[liste]\">$resultat[liste]\n";
	}
	echo "</select>\n";
	echo "<input type=submit value=\"$s21\">\n";
}
echo "</form>\n";
sidefod();
?>
