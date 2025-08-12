<?php
print "<center><b><U>The Grid Game</U></b></center><br>";
$rtime=time();
$playing=mysql_num_rows(mysql_query("select * from gridgame where owner='$user[id]' and status='0'"));
if($act==newgame&&$playing<=0){
	$hoursago2=(1*3600);
	$hoursago=$rtime-$hoursago2;
	$recentplays=mysql_num_rows(mysql_query("select * from gridgame where time>='$hoursago' and owner='$user[id]' and status='1'"));
	if($recentplays>=5){
		$intime=mysql_fetch_array(mysql_query("select * from gridgame where status='1' and time>='$hoursago' and owner='$user[id]' order by time asc limit 1"));
		$timeleft=$hoursago2-($rtime-$intime[time]);
		$minleft=0;
		while($timeleft>60){
			$minleft=$minleft+1;
			$timeleft=$timeleft-60;
		}
		print "Sorry, you have played too many games. Try again in $minleft minutes<br>";
	}else{
		$spots="";
		$i=1;
		while($i<=25){
			$spotstest=substr_count($spots,"k");
			//print "$i. spotstest=$spotstest<br>";
			$cashamt=0;
			$cashhere=rand(1,5);
			
			if($spotstest<5&&$i>=21){
				$spots.="k";
			}elseif($cashhere==1){
				$cashamt=rand(1,100);
				$spots.="$cashamt";
			}elseif($cashhere==2){
				$checkagain=rand(1,2);
				if($checkagain==1){
					$cashamt=rand(150,300);
				}else{
					$cashamt=0;
				}
				$spots.="$cashamt";
			}elseif($cashhere==3&&$spotstest<5){
				$spots.="k";
			}else{
				$cashamt=0;
				$spots.="$cashamt";
			}
			
			if($i<25){
				$spots.=",";
			}
			$i=$i+1;
		}
		mysql_query("insert into gridgame (`owner`,`spots`,`time`) values('$user[id]','$spots','$rtime')");
		print "<b>New game started</b><br><br>";
	}
}
$game=mysql_fetch_array(mysql_query("select * from gridgame where owner='$user[id]' and status='0' limit 1"));
if($game[id]<=0){
	print "<a href=$PHP_SELF?p=gridgame&act=newgame>Start a New Game</a>";
	$cashgiven=0;
	$allgamesel=mysql_query("select * from gridgame where status='1'");
	while($allgame=mysql_fetch_array($allgamesel)){
		$cashgiven=$cashgiven+$allgame[cash]+$allgame[treasure];
	}
	$cashgiven=number_format($cashgiven);
	print "<br><br>This game has given out $cashgiven cash since June 20, 2005";
}else{
	$spots=explode(",",$game[spots]);
	if($pick>=1&&$pick<=25){
		$pick=$pick-1;//because of zero-indexing of arrays
		if($spots[$pick]=="x" || $spots[$pick]=="kf"){
			print "You have already looked in this spot<br><br>";
		}elseif($spots[$pick]=="k"){
			print "You found: <b>A Key!</b><br><br>";
			$spots[$pick]="kf";
		}elseif($spots[$pick]==0){
			print "You found: Nothing<br><br>";
			$spots[$pick]="x";
		}elseif($spots[$pick]>0){
			mysql_query("update characters set cash=cash+$spots[$pick] where id='$stat[id]'");
			mysql_query("update gridgame set cash=cash+$spots[$pick] where id='$game[id]'");
			print "You found: <b>$spots[$pick] Cash!</b><br><br>";
			$spots[$pick]="cf";
		}

	}
	print "<table cellspacing=0 border=0 cellpadding=0><tr><td valign=top>";
	print "<table cellspacing=0 border=0 cellpadding=0><tr>";
	$i=1;
	$jump=5;
	$jump2=$jump;
	$numx=0;
	$numkf=0;
	$imgheight=60;
	$imgwidth=60;
	while($i<=25){
		print "<td>";
		if($spots[$i-1]=="x"){
			print "<img src=\"img/xmark.gif\" height=$imgheight width=$imgwidth>";
			$numx=$numx+1;
		}elseif($spots[$i-1]=="cf"){
			print "<img src=\"img/cashmark.gif\" height=$imgheight width=$imgwidth>";
			$numx=$numx+1;
		}elseif($spots[$i-1]=="kf"){
			print "<img src=\"img/keymark.gif\" height=$imgheight width=$imgwidth>";
			$numkf=$numkf+1;
		}else{
			print "<a href=$PHP_SELF?p=gridgame&pick=$i>";
			print "<img src=\"img/questionmark.gif\" border=0 height=$imgheight width=$imgwidth>";
			print "</a>";
		}
		print "</td>";
		if($i>=$jump&&$i<25){
			print "</tr><tr>";
			$jump=$jump+$jump2;
		}
		$i=$i+1;
	}
	print "</tr></table>";
	$newspots=implode(",",$spots);
	mysql_query("update gridgame set spots='$newspots' where id='$game[id]'");
	print "</td><td valign=top>";
	$xspotsleft=10-$numx;
	if($numx<10){
		print "&nbsp;&nbsp;&nbsp;You can pick <b>$xspotsleft</b> more spot";
		if($xspotsleft>1){print "s";}
	}
	if($numx>=10 || $numkf>=5){
		print "<br><center>";
		if($numkf>=5){
			$wincash=rand(1000,5000);
			$treasurenum=mysql_num_rows(mysql_query("select * from gridgame where treasure>'0'"));
			$treasurenum=$treasurenum+1;
			while($treasurenum>100){
				$treasurenum=$treasurenum-100;
			}
			$wincash=$wincash+($treasurenum*100);
			$special=100-$treasurenum;
			mysql_query("update gridgame set treasure='$wincash' where id='$game[id]'");
			print "<b>You win the treasure of $wincash cash!!!</b>";
			$e=0;
			while($e<=4){
				$etest[$e]=rand(1,2);
				if($etest[$e]==1){
					$e=$e+1;
				}else{
					break;
				}
			}//this makes it exponentially hard to get the next key (1/2^$e)
			$keyid=61700+$e;//61700=Yellow Key , 61701=Orange Key, 61702=Red Key, 61703=Blue Key, 61704=Black Key
			$key=mysql_fetch_array(mysql_query("select * from items where id='$keyid' limit 1"));
			additem($keyid,$user[id]);
			print "<br>You also get a $key[name]<br>";

			if($special>0){
				print "<br><font size=1><i>Something special might happen when $special more treasures are unlocked</i></font>";
			}else{
				$i=0;
				$itemsel=mysql_query("select * from items where owner='0' and rarity<='100' order by id asc");
				while($item=mysql_fetch_array($itemsel)){
					$itemarray[$i]=$item[id];
					$i=$i+1;
				}
				$randitem=rand(0,$i);
				$citem=mysql_fetch_Array(mysql_query("select * from items where id='$itemarray[$randitem]'"));
				print "<b><font size=5>Congratulations!</font> For unlocking the 100th treasure, you win:</b><br>$citem[name]";
				additem($citem[id],$user[id]);
			}

			print "<br><br>";
			mysql_query("update characters set cash=cash+$wincash where id='$stat[id]'");
		}
		print "<a href=$PHP_SELF?p=gridgame><font size=5><b>This game is finished</b></font></a></center>";
		mysql_query("update gridgame set status='1' where id='$game[id]'");
	}
	print "</td></tr></table>";
}