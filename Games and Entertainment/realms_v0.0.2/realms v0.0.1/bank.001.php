<?php
if ($action == withdraw) {



        if ($with > $user[bank] || $with <= 0) {
                print "You cannot withdraw that amount.<br>";
        }elseif(!ereg("^(0|[1-9][0-9]*)$",$with)){
                        print "<br><br>can only contain numbers<br>";
                        die;
                }else{

                mysql_query("update characters set cash=cash+$with where id=$stat[id]");
                mysql_query("update users set bank=bank-$with where id=$user[id]");
                print "You withdrew $"."$with"." cash.<br>";
                $stat[cash]=$stat[cash]+$with;
                $user[bank]=$user[bank]-$with;
        }
}

if ($action == deposit) {
        if ($dep > $stat[cash] || $dep <= 0) {
                print "You cannot deposit that amount.<br>";
        }elseif(!ereg("^(0|[1-9][0-9]*)$",$dep)){
                        print "<br><br>Bet can only contain numbers<br>";
                        die;
                }else{

                mysql_query("update characters set cash=cash-$dep where id=$stat[id]");
                mysql_query("update users set bank=bank+$dep where id=$user[id]");
                print "You deposited $dep cash.<br>";
                $stat[cash]=$stat[cash]-$dep;
                $user[bank]=$user[bank]+$dep;
        }
}

if ($action == transfer) {
        if ($tran > $user[bank] || $tran <= 0) {
                print "You cannot transfer that amount.<br>";
        }elseif($user[username]==guest){
                print "guest cannot do this";
        }elseif(!ereg("^(0|[1-9][0-9]*)$",$tran)){
                        print "<br><br>Bet can only contain numbers<br>";
                        die;
                }else{
                $rec = mysql_fetch_array(mysql_query("select * from users where id=$tid"));
                if (empty ($rec[id])) {
                        print "User does not exist.<br>";
                        include("footer.php");
                        exit;
                }

                mysql_query("update users set bank=bank-$tran where id=$user[id]");
                mysql_query("update users set bank=bank+$tran where id=$tid");
                mysql_query("insert into log (owner, log) values($user[id],'You sent $rec[username] $tran cash')");
                mysql_query("insert into log (owner, log) values($tid,'You got $tran cash from $user[username]')");
                print "You transferred $tran cash to $rec[username] ($tid).<br>";
                $user[bank]=$user[bank]-$tran;
        }
}

print"Welcome to the bank, sir. You can deposit your extra cash in here, to avoid having them stolen by attackers.

<form method=post action=$GAME_SELF?p=bank&action=withdraw>

I will <input type=submit value=withdraw> <input type=text value=\"$user[bank]\" name=with size=10> cash.

</form>

<form method=post action=$GAME_SELF?p=bank&action=deposit>

I will <input type=submit value=deposit> <input type=text value=\"$stat[cash]\" name=dep size=10> cash.

</form>";

print "
<form method=post action=$GAME_SELF?p=bank&action=transfer>

I will <input type=submit value=transfer> <input type=text value=\"0\" name=tran size=10> cash to  <select name=tid>";
$p4sel=mysql_query("select * from users order by username asc");
while($p4=mysql_fetch_array($p4sel)){
        print "<option value=\"$p4[id]\">$p4[username]</option>";
}
print "</select>.
</form>";

$totalcred=0;
$psel=mysql_query("select * from `users` where `position`!='Admin'");
while($p=mysql_fetch_array($psel)){
        $p2sel=mysql_query("select * from `characters` where `owner`='$p[id]'");
while($p2=mysql_fetch_array($p2sel)){
        $totalcred=$totalcred+$p2[cash];
}
        $totalcred=$totalcred+$p[bank];
}

$mytotal=$stat[cash]+$user[bank];
$percent=$mytotal/$totalcred;
$percent=$percent*100;
$percent=round($percent,2);
$totalcred=number_format($totalcred);
$mytotal=number_format($mytotal);
print "<br><br>You have $percent% of the cash in the game: $mytotal out of $totalcred";
print "<br><br>";
include("economy.001.php");
?>