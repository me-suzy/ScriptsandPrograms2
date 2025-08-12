<?php
$pagetitle="Import subscribers";
$sel_tab="2.3";
require("functions.php");
sidehoved();
if ($importfil) {
    if (!is_file($importfil)) {
        fejl($s100);
    }

    $liste = addslashes($_REQUEST['liste']);
    $filtabel = file($importfil);

    for ($i = 0; $i < sizeof($filtabel); $i++) {
        $adresse = chop($filtabel[$i]);
        $adresse = addslashes($adresse);
        $uid = uniqid("pl");

        mysql_prefix_query("insert into $liste values ( '$adresse', '$uid', '1' )");
    }

    echo "$s101\n";

    sidefod();

    exit;
}



if ($eksport) {
    if (!is_dir($eksport)) {
        fejl($s110);
    }

    # Removing the slash at the end of the directory name:
    $eksport = ereg_replace("/$", "", $eksport);

    $fp = fopen("$eksport/postlister-$liste.txt", "a");

    $kommando = mysql_prefix_query("select epostadresse from ".addslashes($_REQUEST['liste'])." where godkendt = '1'");

    while ($resultat = mysql_fetch_row($kommando)) {
        $adr = trim($resultat[0]);
        fwrite($fp, "$adr\n");
    }

    fclose($fp);

    echo "$s111\n";

    sidefod();
    exit;
}



vaelgListe($PHP_SELF);



echo "<font size=2><b>$s98</b></font>\n<br>";

echo "$s102<p>\n";

echo "<form action='$PHP_SELF?liste=$liste' method='post'>\n";
echo "$s103<br>\n";
echo "<input type='text' name='importfil' size='40' value='$DOCUMENT_ROOT'><br>\n";
echo "<input type='submit' value='$s99'>\n";
echo "</form><br><br>\n";

echo "<hr>\n";
echo "<font size=2><b>$s106</b></font>\n<br>";

echo "$s108<p>\n";

echo "<form action='$PHP_SELF?liste=$liste' method='post'>\n";
echo "$s109<br>\n";
echo "<input type='text' name='eksport' size='40' value='$DOCUMENT_ROOT'><br>\n";
echo "<input type='submit' value='$s107'>\n";
echo "</form>\n";

sidefod();
?>