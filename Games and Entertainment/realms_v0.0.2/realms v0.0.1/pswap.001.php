

<?php
ini_set(display_errors,on);
if(!$typet){
        print"
        Convert to <a href=\"$GAME_SELF?p=pswap&amp;typet=stat\">Stat points</a>?<BR>
        Convert to <a href=\"$GAME_SELF?p=pswap&amp;typet=ac\">Account Credits</a>?<BR>
        ";

}elseif($typet==stat){


        print "Here, you can use points. swap them to stat points here";


        print"<form method=post action=\"$GAME_SELF?p=pswap&amp;swap=yes&amp;typet=stat\">
        <table><tr>";
        print"<th>type</th><th>&nbsp;</th><th>&nbsp;</th><th>exhange rate</th><th>&nbsp;&nbsp;&nbsp;&nbsp;you have</th>
        </tr><tr>";

        if($stat[battle]>=3){
        print"<td>Battle points </td><td> x </td><td><input type=text name=battle size=3 maxlength=3></td><td><center>3bp = 1sp</center></td><td><div align=right>$stat[battle]</div></td>
        </tr><tr>";
        }

        if($user[vp]>=1){
        print"<td>Vote points </td><td> x </td><td><input type=text name=vp size=3 maxlength=3></td><td><center>1vp = 1sp</center></td><td><div align=right>$user[vp]</div></td>
        </tr><tr>";
        }

        if($stat[quest]>=4){
        print"<td>Quest points </td><td> x </td><td><input type=text name=quest size=3 maxlength=3></td><td><center>4qp = 1sp</center></td><td><div align=right>$user[quest]</div></td>
        </tr><tr>";
        }

        if($user[glomps]>=1){
        print"<td>Glomps (referal points) </td><td> x </td><td><input type=text name=glomps size=3 maxlength=3></td><td><center>1 glomp = 5sp</center></td><td><div align=right>$user[glomps]</div></td>
        </tr><tr>";
        }

        if($user[forump]>=10){
        print"<td>Forum points </td><td> x </td><td><input type=text name=forump size=3 maxlength=3></td><td><center>10fp = 1sp</center></td><td><div align=right>$user[forump]</div></td>
        </tr><tr>";
        }


        if($user[credits]>0){
        print"<td>Account Credits </td><td> x </td><td><input type=text name=credits size=3 maxlength=3></td><td><center>1ac = 5sp</center></td><td><div align=right>$user[credits]</div></td>
        </tr><tr>";
        }

        if($stat[cash]>1000){
        print"<td>Cash</td><td> x </td><td><input type=text name=cash size=5 maxlength=5></td><td><center>1000 cash = 1sp</center></td><td><div align=right>$stat[cash]</div></td>
        </tr><tr>";
        }



        print"<td colspan=\"5\"><input type=submit value=go></td>";

        print"</tr></table></form>";



        if ($swap=="yes") {

        $battleb = $battle / 3;
        $battlec = floor($battleb);
        $battled = $battlec * 3;

        $cashb = $cash / 1000;
        $cashc = floor($cashb);
        $cashd = $cashc * 1000;

        $vpc = floor($vp);

        $questb = $quest / 4;
        $questc = floor($questb);
        $questd = $questc * 4;

        $forumpb = $forump / 10;
        $forumpc = floor($forumpb);
        $forumpd = $forumpc * 10;

        $glompc = $glomp * 5;

        $creditsc = $credits * 5;

        $tog = $battlec + $vpc + $questc + $forumpc + $glompc + $creditsc + $cashc;


        if ($stat[battle] < $battled) {
                print "You don't have enough battle points.";
        }elseif ($stat[cash] < $cashd) {
                print "You don't have enough cash.";
        }elseif ($stat[quest] < $questd) {
                print "You don't have enough quest points.";
        }elseif ($user[forump] < $forumpd) {
                print "You don't have enough forum points.";
        }elseif ($user[vp] < $vpc) {
                print "You don't have enough vote points.";
        }elseif ($user[credits] < $credits) {
                print "You don't have enough account credits.";
        }elseif ($user[glomps] < $glomps) {
                print "You don't have enough glomps.";
        }else{



        mysql_query("update characters set battle=battle-$battled where id=$stat[id]");
        mysql_query("update characters set quest=quest-$questd where id=$stat[id]");
        mysql_query("update characters set cash=cash-$cashd where id=$stat[id]");
        mysql_query("update users set forump=forump-$forumpd where id=$user[id]");
        mysql_query("update users set vp=vp-$vpc where id=$user[id]");
        mysql_query("update users set credits=credits-$credits where id=$user[id]");
        mysql_query("update users set glomps=glomps-$glomps where id=$user[id]");


        mysql_query("update characters set stat=stat+$tog where id=$stat[id]");

        print "<meta http-equiv='refresh' content='0; url=$PHP_SELF?p=overview'>";
}
}
}elseif($typet==ac){


        print "Here, you can use points. Swap them to account credits here";


        print"<form method=post action=\"$GAME_SELF?p=pswap&amp;swap=yes&amp;typet=ac\">
        <table><tr>";
        print"<th>type</th><th>&nbsp;</th><th>&nbsp;</th><th>exhange rate</th><th>&nbsp;&nbsp;&nbsp;&nbsp;you have</th>
        </tr><tr>";

        if($stat[battle]>=25){
        print"<td>Battle points </td><td> x </td><td><input type=text name=battle size=3 maxlength=4></td><td><center>25bp = 1ac</center></td><td><div align=right>$stat[battle]</div></td>
        </tr><tr>";
        }

        if($user[vp]>=6){
        print"<td>Vote points </td><td> x </td><td><input type=text name=vp size=3 maxlength=4></td><td><center>6vp = 1ac</center></td><td><div align=right>$user[vp]</div></td>
        </tr><tr>";
        }

        if($stat[quest]>=35){
        print"<td>Quest points </td><td> x </td><td><input type=text name=quest size=3 maxlength=4></td><td><center>35qp = 1ac</center></td><td><div align=right>$user[quest]</div></td>
        </tr><tr>";
        }

        if($user[glomps]>=4){
        print"<td>Glomps (referal points) </td><td> x </td><td><input type=text name=glomps size=3 maxlength=3></td><td><center>4 glomps = 1ap</center></td><td><div align=right>$user[glomps]</div></td>
        </tr><tr>";
        }

        if($user[forump]>=100){
        print"<td>Forum points </td><td> x </td><td><input type=text name=forump size=3 maxlength=3></td><td><center>100fp = 1sp</center></td><td><div align=right>$user[forump]</div></td>
        </tr><tr>";
        }


        if($stat[stat]>=8){
        print"<td>Stat points </td><td> x </td><td><input type=text name=stata size=3 maxlength=3></td><td><center>8sp = 1ac</center></td><td><div align=right>$stat[stat]</div></td>
        </tr><tr>";
        }

        if($stat[cash]>7500){
        print"<td>Cash</td><td> x </td><td><input type=text name=cash size=5 maxlength=5></td><td><center>7500 cash = 1ac</center></td><td><div align=right>$stat[cash]</div></td>
        </tr><tr>";
        }



        print"<td colspan=\"5\"><input type=submit value=go></td>";

        print"</tr></table></form>";



        if ($swap=="yes") {

        $battleb = $battle / 25;
        $battlec = floor($battleb);
        $battled = $battlec * 25;

        $cashb = $cash / 7500;
        $cashc = floor($cashb);
        $cashd = $cashc * 7500;

        $vpb = $vp / 6;
        $vpc = floor($vpb);
        $vpd = $vpc * 6;

        $questb = $quest / 35;
        $questc = floor($questb);
        $questd = $questc * 35;

        $forumpb = $forump / 100;
        $forumpc = floor($forumpb);
        $forumpd = $forumpc * 100;

        $glompb = $glomp / 4;
        $glompc = floor($glompb);
        $glompd = $glompc * 4;

        $statb = $stata / 8;
        $statc = floor($statb);
        $statd = $statc * 8;

        $tog = $battlec + $vpc + $questc + $forumpc + $glompc + $creditsc + $cashc;


        if ($stat[battle] < $battled) {
                print "You don't have enough battle points.";
        }elseif ($stat[cash] < $cashd) {
                print "You don't have enough cash.";
        }elseif ($stat[quest] < $questd) {
                print "You don't have enough quest points.";
        }elseif ($user[forump] < $forumpd) {
                print "You don't have enough forum points.";
        }elseif ($user[vp] < $vpd) {
                print "You don't have enough vote points.";
        }elseif ($stat[stat] < $statd) {
                print "You don't have enough statpoints.";
        }elseif ($user[glomps] < $glompd) {
                print "You don't have enough glomps.";
        }else{



        mysql_query("update characters set battle=battle-$battled where id=$stat[id]");
        mysql_query("update characters set quest=quest-$questd where id=$stat[id]");
        mysql_query("update characters set cash=cash-$cashd where id=$stat[id]");
        mysql_query("update users set forump=forump-$forumpd where id=$user[id]");
        mysql_query("update users set vp=vp-$vpd where id=$user[id]");
        mysql_query("update characters set stat=stat-$statd where id=$stat[id]");
        mysql_query("update users set glomps=glomps-$glompd where id=$user[id]");


        mysql_query("update users set credits=credits+$tog where id=$user[id]");

        print "<meta http-equiv='refresh' content='0; url=$PHP_SELF?p=overview'>";
}
}
}

?>