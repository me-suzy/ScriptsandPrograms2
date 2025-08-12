<?php  $title = "Craps"; ?>

<? php;

//Guarantee Clear roll Count
$toss =0;

function throw_dice($dice, $toss)
{

        //Get die info
        $dice[$toss][0] = rand(1,6);
        $dice[$toss][1] = rand(1,6);
        $dice[$toss][2] = $dice[$toss][0] + $dice[$toss][1];

        //get odds
        switch ($dice[$toss][2])
        {
                case 2:
                        $odds="1 out of 6";
                        break;

                case 12:
                        $odds="1 out of 6";
                        break;

                case 3:
                        $odds="2 out of 6";
                        break;

                case 11:
                        $odds="2 out of 6";
                        break;

                case 4:
                        $odds="3 out of 6";
                        break;

                case 10:
                        $odds="3 out of 6";
                        break;

                case 5:
                        $odds="4 out of 6";
                        break;

                case 9:
                        $odds="4 out of 6";
                        break;

                case 6:
                        $odds="5 out of 6";
                        break;

                case 8:
                        $odds="5 out of 6";
                        break;

                case 7:
                        $odds="6 out of 6";
                        break;

        }
        $dice[$toss][3]=$odds;


        return $dice;
}

function win_or_loose($dice, $toss)
{
        $toss1 = $toss - 1;

        if ($dice[0][2]==7  or $dice[0][2]==11)
        {
                $win="win";
        }
        elseif ($dice[$toss][2]==2)
        {
                $win="snake eyes";
        }
        elseif ($toss > 0)
        {
                if ($dice[$toss][2]==7 or $dice[$toss][2]==11)
                {
                        $win="loss";
                }
                elseif ($dice[$toss][2] == $dice[0][2])
                {
                        $win="win";
                }
                else
                {
                        $win="retoss";
                }

        }
        return $win;
}
$bet=$_POST[bet];
if ($play!="go")
{
$bet=10;
}
//Play Button
print "<form action=$GAME_SELF?p=craps method=post>\n";
print "It's just
like playing Craps<br><br>Bet:<input name=bet value=$bet type=text size=5 maxlength=5>\n";
print "<input type=submit name=play value=go>\n";


//Start Game

if ($play=="go")
{
        if ($stat[credits]<$bet || $bet<0)
        {
print "Not enough credits";
include("tccfooter.php");
die;
        }

print "<table border=1 align=1><tr><td
colspan=5 align=center><font
color=#blue><font size=14>Throws</font></font></td></tr>\n";
        print "<tr><td>Toss #</td><td>Dice 1</td><td>Dice
2</td><td>total</td><td>Odds</td></tr>\n";

        do
        {
                $dice = throw_dice($dice, $toss);
        $toss1=$toss+1;
print "<tr><td>".$toss1."</td><td><img src=img/dice".$dice[$toss][0].".jpg></td><td><img src=img/dice"
.$dice[$toss][1].".jpg></td><td>".$dice[$toss][2]."</td><td>".$dice[$toss][3].
"</tr>\n";

                $win = win_or_loose($dice, $toss);
        if ($win == "win")
        {
print "<tr><td colspan=5><font color=#red><font size=14>WINNER + [$bet]</font></font></td></tr>";
print "</table>";
mysql_query("update users set credits=credits+$bet where id=$stat[id]");
break;
        }
        elseif ($win == "snake eyes")
        {
print "<tr><td colspan=5><font size=14>Snake Eyes Turkey - broke even</font></td></tr></table>";
break;
        }
        elseif ($win == "loss")
        {
print "<tr><td colspan=5><font size=14>Lose - [$bet]</font></td></tr></table>";
mysql_query("update users set credits=credits-$bet where id=$stat[id]");
break;
        }

                $toss ++;

        }
        while ($win != "win");


}


?>