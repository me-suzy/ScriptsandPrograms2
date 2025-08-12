<?php  $title = "Slots";
$width=50;
$height=50;

$bet=$_POST[bet];
print "<font face=symbol>&#169; &#168; &#167; &#170; </font><br><form action=$GAME_SELF?p=slots method=post>\n";
print "Just place a bet and go.... <br><br>Bet:<input name=bet type=text size=6 maxlength=6 value=$bet>\n";
print "<input type=submit name=play value=go>\n";

if ($play=="go")
{
$bet=$_POST[bet];
        if ($stat[cash]<$bet || $bet<1)
        {
print "<br><br>Not enough cash<br>";
die;
        }
                if(!ereg("^(0|[1-9][0-9]*)$",$bet)){
                        print "<br><br>Bet can only contain numbers<br>";
                        die;
                }

$aone=rand(1,15);
$atwo=rand(1,15);
$athree=rand(1,15);



        if ($aone>0&&$aone<=3){
$one="<img src=\"img/suit_heart.gif\" width=$width heigh=$height>";
$test=1;
        }elseif($aone>3&&$aone<=5){
$one="<img src=\"img/suit_diamond.gif\" width=$width heigh=$height>";
$test=2;
        }elseif($aone>5&&$aone<=11){
$one="<img src=\"img/suit_club.gif\" width=$width heigh=$height>";
$test=3;
        }else{
$one="<img src=\"img/suit_spade.gif\" width=$width heigh=$height>";
$test=4;
        }
        if ($atwo>0&&$atwo<=3){
$two="<img src=\"img/suit_heart.gif\" width=$width heigh=$height>";
$testb=1;
        }elseif($atwo>3&&$atwo<=5){
$two="<img src=\"img/suit_diamond.gif\" width=$width heigh=$height>";
$testb=2;
        }elseif($atwo>5&&$atwo<=11){
$two="<img src=\"img/suit_club.gif\" width=$width heigh=$height>";
$testb=3;
        }else{
$two="<img src=\"img/suit_spade.gif\" width=$width heigh=$height>";
$testb=4;
        }
        if ($athree>0&&$athree<=4){
$three="<img src=\"img/suit_heart.gif\" width=$width heigh=$height>";
$testc=1;
        }elseif($athree>4&&$athree<=7){
$three="<img src=\"img/suit_diamond.gif\" width=$width heigh=$height>";
$testc=2;
        }elseif($athree>7&&$athree<=11){
$three="<img src=\"img/suit_club.gif\" width=$width heigh=$height>";
$testc=3;
        }else{
$three="<img src=\"img/suit_spade.gif\" width=$width heigh=$height>";
$testc=4;
        }






print"<center><table border=3 cellpadding=0 cellspacing=10 align=center><tr><td bgcolor=white><center>
$one<td bgcolor=white>
<center>$two<td bgcolor=white>
<center>$three</td>
</tr></table>";
}
        if ($test==1&&$test==$testb&&$test==$testc){
$win=$bet*50;
print"JACKPOT!!!!!!!! you won $win cash";
mysql_query("update characters set cash=cash+$win where id=$stat[id]");
}
        elseif ($test==2&&$test==$testb&&$test==$testc){
$win=$bet*5;
print"you won $win cash";
mysql_query("update characters set cash=cash+$win where id=$stat[id]");
}
        elseif ($test==3&&$test==$testb&&$test==$testc){
$win=$bet*2;
print"you won $win cash";
mysql_query("update characters set cash=cash+$win where id=$stat[id]");
}
        elseif ($test==4&&$test==$testb&&$test==$testc){
print"TOO BAD!!! you lost $bet cash AND all your HP";
mysql_query("update characters set cash=cash-$bet where id=$stat[id]");
mysql_query("update characters set hp=hp-hp where id=$stat[id]");
}
elseif($test!=$testb||$test!=$testc) {
print"You lost $bet cash.";
mysql_query("update characters set cash=cash-$bet where id=$stat[id]");
 }else{
mysql_query("update characters set cash=cash-$bet where id=$stat[id]");
 }

print"<hr color=#000001>Instructions:<br>
<table width=300><tr><td width=1>
<img src=\"img/suit_heart.gif\" width=$width heigh=$height><td> X Three =your bet X 50
<tr><td width=1>
<img src=\"img/suit_diamond.gif\" width=$width heigh=$height><td> X Three =Your bet X 5
<tr><td width=1>
<img src=\"img/suit_club.gif\" width=$width heigh=$height><td> X Three =Your bet X 2
<tr><td width=1>
<img src=\"img/suit_spade.gif\" width=$width heigh=$height><td> X Three =Lose all your HP and get no money back.
</td></tr></table>";

print "</form>";
?>