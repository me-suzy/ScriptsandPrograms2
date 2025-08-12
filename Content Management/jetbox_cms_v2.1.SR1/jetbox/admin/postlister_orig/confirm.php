<?php
require("functions.php");

sidehoved("", 0);



if (!$epostadresse) {
	fejl($s90);
}

if (!$liste) {
	fejl($s93);
}

if ($abonner != 0 && $abonner != 1) {
	fejl($s91);
}

if (!$id) {
	fejl($s92);
}



$liste = addslashes($_REQUEST['liste']);
$epostadresse = addslashes($epostadresse);

$kommando = mysql_prefix_query("select id from $liste where epostadresse = '$epostadresse'");
$resultat = mysql_fetch_array($kommando);
$idFraDatabasen = $resultat[id];



if ($id != $idFraDatabasen) {
	fejl($s94);
}



if ($abonner == 1) {
	mysql_prefix_query("update $liste set godkendt = '1' where epostadresse = '$epostadresse'");

	echo "$s95\n";
	sidefod();
}



if ($abonner == 0) {
	mysql_prefix_query("delete from $liste where epostadresse = '$epostadresse'");

	echo "$s96\n";
	sidefod();
}



sidefod();
?>
