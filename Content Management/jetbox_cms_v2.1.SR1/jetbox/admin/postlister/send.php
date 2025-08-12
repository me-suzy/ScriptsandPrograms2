<?php
require("functions.php");



# No-cache headers:
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");



sidehoved("", 0);
echo "<center>\n";



$kommando = mysql_query("select afsender, emne, ebrev from $mainTable where liste = '".addslashes($liste)."'");
$resultat = mysql_fetch_array($kommando);
$af = stripslashes($resultat[afsender]);
$em = stripslashes($resultat[emne]);
$te = stripslashes($resultat[ebrev]);



if ($start < $iAlt) {
    if (($slut + 10) > $iAlt) $slut = $iAlt;
    else $slut = 10;

    $kommando = mysql_query("select epostadresse, id from ".addslashes($liste)." where godkendt = '1' limit ".addslashes($start).", $slut");

    if (($start + 10) > $iAlt) $naeste = $iAlt;
    else $naeste = $start + 10;

    $fra = $start + 1;

    echo "$s86 $fra $s87 $naeste $s97 $iAlt.\n";
    echo "<h3>$s88</h3>\n";

    while ($resultat = mysql_fetch_array($kommando)) {
        $ep = stripslashes($resultat[epostadresse]);
        $id = stripslashes($resultat[id]);
        $nyte = str_replace("[RCPT_EMAIL]", $ep, $te);
        $nyte = str_replace("[SUBSCRIBE_URL]", "http://$HTTP_HOST".dirname($PHP_SELF)."/confirm.php?liste=$liste&abonner=1&epostadresse=".urlencode($ep)."&id=$id", $nyte);
        $nyte = str_replace("[UNSUBSCRIBE_URL]", "http://$HTTP_HOST".dirname($PHP_SELF)."/confirm.php?liste=$liste&abonner=0&epostadresse=".urlencode($ep)."&id=$id", $nyte);

        mail($ep, $em, $nyte, "From: $af\n$ekstraHeadere");
    }



    echo "<script language=\"javascript\">\n";
    echo "<!--\n";
    echo "window.location.href = \"http://$HTTP_HOST$PHP_SELF?liste=$liste&start=$naeste&iAlt=$iAlt\"\n";
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
