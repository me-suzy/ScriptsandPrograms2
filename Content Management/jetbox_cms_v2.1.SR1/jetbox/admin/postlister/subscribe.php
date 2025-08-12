<?php
require("functions.php");

sidehoved("", 0);



if ($email) {
    # Is the email address valid?
    if (!ereg("^[-0-9A-Za-z._]+@[-0-9A-Za-z.]+\.[A-Za-z]{2,3}$", $email)) {
        fejl($s75);
    }
    if (!$action || ($action != "subscribe" && $action != "unsubscribe")) {
        fejl($s76);
    }
    # Checking to see if the email address already exists on the list:
    $kommando = mysql_query("select epostadresse from $list where epostadresse = '$email'");
    if (mysql_num_rows($kommando) > 0 && $action == "subscribe") {
        # "The email address already exists on the list":
        fejl($s84);
    }
	if (mysql_num_rows($kommando) == 0 && $action == "unsubscribe") {
		# "You can't unsubscribe that email because it doesn't exist
		# on the list":
		fejl($s89);
	}



    $kommando = mysql_query("select tilmeldingsbesked, afmeldingsbesked from $mainTable where liste = '".addslashes($list)."'");
    $resultat = mysql_fetch_array($kommando);

    $tilmeldingsbesked = stripslashes($resultat[tilmeldingsbesked]);
    $afmeldingsbesked = stripslashes($resultat[afmeldingsbesked]);



    if ($action == "subscribe") {
        $tilmeldingsbesked = str_replace("[SUBSCRIBE_URL]", "http://$HTTP_HOST".dirname($PHP_SELF)."/confirm.php?liste=$list&abonner=1&epostadresse=".urlencode($email)."&id=$uniktId", $tilmeldingsbesked);

        mysql_query("insert into $list values (
            '$email',
            '$uniktId',
            '0'
        )");

        mail($email, $s77, $tilmeldingsbesked, "From: $email\n$ekstraHeadere");

        echo "$s79\n";
    }



    else {
	$kommando = mysql_query("select id from $list where epostadresse = '$email'");
	$resultat = mysql_fetch_array($kommando);
	$idFraDatabasen = $resultat[id];

        $afmeldingsbesked = str_replace("[UNSUBSCRIBE_URL]", "http://$HTTP_HOST".dirname($PHP_SELF)."/confirm.php?liste=$list&abonner=0&epostadresse=".urlencode($email)."&id=$idFraDatabasen", $afmeldingsbesked);
        mail($email, $s78, $afmeldingsbesked, "From: $email\n$ekstraHeadere");

        echo "$s80\n";
    }

    sidefod();
    exit;
}



$kommando = mysql_query("select liste from $mainTable");



echo "<center>\n";

echo "<table cellspacing=0 border=0 cellpadding=5>\n";
echo "<form action=\"$PHP_SELF\" method=post>\n";

echo "<tr><td colspan=2 style=\"background: maroon; color: white\">\n";
echo "$s70\n";
echo "</td></tr>\n";

echo "<tr><td class=tilmelding>\n";
echo "$s71\n";
echo "</td>\n";

echo "<td class=tilmelding>\n";
echo "<input type=text name=email size=20>\n";
echo "</td></tr>\n";

echo "<tr><td class=tilmelding>\n";
echo "$s72\n";
echo "</td>\n";

echo "<td class=tilmelding>\n";
if (mysql_num_rows($kommando) == 0) {
    echo "$s14\n";
}
else {
    echo "<select name=list>\n";
    while ($resultat = mysql_fetch_array($kommando)) {
        $res = htmlspecialchars(stripslashes($resultat[liste]));
        echo "<option value=\"$res\">$res\n";
    }
    echo "</select>\n";
}
echo "</td></tr>\n";

echo "<tr><td class=tilmelding>\n";
echo "&nbsp;";
echo "</td>\n";

echo "<td class=tilmelding>\n";
echo "<input type=radio name=action value=\"subscribe\" checked>\n";
echo "$s73<br>\n";
echo "<input type=radio name=action value=\"unsubscribe\">\n";
echo "$s74\n";
echo "</td></tr>\n";

echo "<tr><td class=tilmelding>\n";
echo "&nbsp;";
echo "</td>\n";

echo "<td class=tilmelding>\n";
echo "<input type=submit value=\"$s13\"><p>\n";
echo "<a href=\"http://www.nameless.f2s.com\" style=\"background: black; font-family:lucida,verdana,helvetica; font-size: 10pt; color: white; text-decoration: none\">Postlister $version</a>\n";
echo "</td></tr>\n";

echo "</form>\n";
echo "</table>\n";

sidefod();
