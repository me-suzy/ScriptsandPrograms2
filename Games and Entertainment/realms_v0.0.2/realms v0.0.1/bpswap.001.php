

<?php



        print "Here, you can use battle points to do things. there isnt much to do right now, just convert to stat points, the exchange rate may change. You have <b>$stat[battle]</b> battle points left.";


        print"<form method=post action=\"$GAME_SELF?p=bpswap&amp;upgrade=yes\">
        <table><tr>";
        print"<th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>exchange rate</th>
        </tr><tr>";

        print"<td>Stat Points </td><td> x </td><td><input type=text name=tog size=3 maxlength=3></td><td>
        <center>5bp = 1sp</center></td>
        </tr><tr>";


        print"<td colspan=\"4\"><input type=submit value=go></td>";

        print"</tr></table></form>";



        if ($upgrade=="yes") {

        $gog = $tog / 5;
        $hog = floor($gog);
        $frog = $hog * 5;

        if ($stat[battle] < $frog || $stat[battle] <= 0 || $hog <=0) {
                print "You don't have enough battle points.";
        }else{

mysql_query("update characters set battle=battle-$frog where id=$stat[id]");
mysql_query("update characters set stat=stat+$hog where id=$stat[id]");

        print "<meta http-equiv='refresh' content='0; url=$PHP_SELF?p=overview'>";
}
}
?>