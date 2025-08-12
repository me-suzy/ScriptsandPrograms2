<?php
$filter=$HTTP_GET_VARS["filter"];
$type=intval($HTTP_GET_VARS["type"]);
$day=intval($HTTP_GET_VARS["day"]);
$month=intval($HTTP_GET_VARS["month"]);
$year=intval($HTTP_GET_VARS["year"]);
$prom=intval($HTTP_GET_VARS["prom"]);
$cb_hits=$HTTP_GET_VARS["cb_hits"];
$cb_hosts=$HTTP_GET_VARS["cb_hosts"];
$cb_users=$HTTP_GET_VARS["cb_users"];
$graph=intval($HTTP_GET_VARS["graph"]);
$s=$HTTP_GET_VARS["s"];
	
$r=mysql_query("SHOW TABLE STATUS");
$size=0;
while ($a=mysql_fetch_array($r,MYSQL_ASSOC)) {
	while (list ($key, $val) = each ($a)) {
		if ($key=="Data_length" && (substr($tname,0,4)=="cns_")) $size+=$val;
		if ($key=="Index_length" && (substr($tname,0,4)=="cns_")) $size+=$val;
		if ($key=="Name") $tname=$val;
		if ($key=="Rows" && $tname=="cns_log") $rows=$val;
	    }
	}

$ADMENU=$LANG["basesize"].": <B>".cNumber($size)."</B><br>".$LANG["baserows"].":<br><B>".cNumber($rows)."</B>";

function stable($color,$text) {
	return("<table cellspacing=0 cellpadding=0 border=0><tr><td><table style='width:12px;height:12px;' cellspacing=1 cellpadding=1 border=0 bgcolor='black'><tr><td bgcolor='".$color."'></td></tr></table></td><td>&nbsp;<B>".$text."</B></td></tr></table>");
	}

// Âõîäíûå ïàðàìåòðû
if ($HTTP_GET_VARS["second"]!=1) {
	$cb_hosts=$cb_hits=$cb_users=$table="on";
	$type=1;
	}

// Íà÷àëüíûå çíà÷åíèÿ
$mini=$minh=$minu=99999999;
$maxi=$maxh=$maxu=0;

// Ðàñ÷èòûâàåì çíà÷åíèÿ äëÿ ãðàôèêîâ
$DATA["x"]=$DATA[0]=$DATA[1]=$DATA[2]=Array();
$limit=44;

// Âûâîäèì òàáëèöó #############################################################
if ($type==0) { /* Ïî ÷àñàì ############################################# */

	$tm=time()+$COUNTER["timeoffset"];
	$sdate=date("Y-m-d H:i:s",mktime(0,0,0,date("m",$tm) ,date("d",$tm),date("Y",$tm)));
	$edate=date("Y-m-d H:i:s",mktime(0,0,0,date("m",$tm) ,date("d",$tm)+1,date("Y",$tm)));

	$r=cnstats_sql_query("select LEFT(date,13),count(page),sum(type),sum(type1) from cns_log WHERE date>'$sdate' AND date<'$edate' GROUP BY LEFT(date,13) ORDER BY date desc");

	$html.="<table width=\"".$TW."\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"#D4F3D7\">";
	$html.="<tr class=\"tbl2\"><td align=\"center\"><b>".$LANG["date"]."</b></td><td align=\"center\"><b>".$LANG["hours"]."</b></td><td align=\"center\" width=\"100\"><b>".$LANG["hits"]."</b></td><td align=\"center\" width=\"100\"><b>".$LANG["hosts"]."</b></td><td align=\"center\" width=\"100\"><b>".$LANG["users"]."</b></td></tr>";

	if (mysql_num_rows($r)!=0) $date=substr(mysql_result($r,0,0),0,11);
	else $date=date("Y-m-d H:i:s");

	for ($i=0;$i<24;$i++) {
		$a_hits[$i]="-";
		$a_hosts[$i]="-";
		$a_users[$i]="-";
		} /* of for */

	for ($i=0;$i<mysql_num_rows($r);$i++) {
		$h=intval(substr(mysql_result($r,$i,0),11));
		$a_hits[$h]=mysql_result($r,$i,1);
		$a_hosts[$h]=mysql_result($r,$i,2);
		$a_users[$h]=mysql_result($r,$i,3);

		if ($mini>$a_hits[$h]) $mini=$a_hits[$h];
		if ($maxi<$a_hits[$h]) $maxi=$a_hits[$h];

		if ($minh>$a_hosts[$h]) $minh=$a_hosts[$h];
		if ($maxh<$a_hosts[$h]) $maxh=$a_hosts[$h];

		if ($minu>$a_users[$h]) $minu=$a_users[$h];
		if ($maxu<$a_users[$h]) $maxu=$a_users[$h];
		} /* of for */

	$thi=$tho=$tus=0;
	for ($i=23;$i>=0;$i--) {
		if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
		$html.="<tr class=\"".$class."\">\n";

		$html.="\t<td align=\"center\">".date($CONFIG["date_format"],strtotime($date))."</td>\n";
		$html.="\t<td align=\"center\">".$i."</td>\n";

		$t1=$t2="";
		if ($a_hits[$i]==$mini) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($a_hits[$i]==$maxi) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=\"right\">".$t1.$a_hits[$i].$t2."</td>\n";

		$t1=$t2="";
		if ($a_hosts[$i]==$minh) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($a_hosts[$i]==$maxh) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=\"right\">".$t1.$a_hosts[$i].$t2."</td>\n";

		$t1=$t2="";
		if ($a_users[$i]==$minu) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($a_users[$i]==$maxu) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=\"right\">".$t1.$a_users[$i].$t2."</td>\n";

		if ($cb_hits=="on") $DATA[0][]=intval($a_hits[$i]);
		if ($cb_users=="on") $DATA[1][]=intval($a_users[$i]);
		if ($cb_hosts=="on") $DATA[2][]=intval($a_hosts[$i]);
		$DATA["x"][]=str_pad($i,2,"0",STR_PAD_LEFT);

		if ($hits!="-") $thi+=$a_hits[$i];
		if ($hosts!="-") $tho+=$a_hosts[$i];
		if ($users!="-") $tus+=$a_users[$i];
		$html.="</tr>\n";
		} /* of for */
	$html.="<tr class=\"tbl2\"><td align=\"center\" colspan=\"2\"><b>".$LANG["total"]."</b></td><td align=\"right\" width=\"100\"><b>".$thi."</b></td><td align=right width=100><b>$tho</b></td><td align=right width=100><b>$tus</b></td></tr>";
	$html.="</table></center>";
	} /* of if ($type==0) */

if ($type==1) { /* Ïî äíÿì ############################################## */
	$r=cnstats_sql_query("select LEFT(date,10),hits,hosts,users from cns_counter_total ORDER BY date desc  LIMIT $limit;");

	$html.="<center><br><table width=".$TW." cellspacing=1 cellpadding=3 bgcolor='#D4F3D7'>";
	$html.="<tr class=tbl2><td align=center><b>".$LANG["date"]."</b></td><td align=center width=100><B>".$LANG["hits"]."</b></td><td align=center width=100><b>".$LANG["hosts"]."</b></td><td align=center width=100><b>".$LANG["users"]."</b></td></tr>";

	$thi=$tho=$tus=0;
	for ($i=0;$i<mysql_num_rows($r);$i++) {
		$date=mysql_result($r,$i,0);
		$date=substr($date,0,4).substr($date,5,2).substr($date,8,2);
		$a_hits[$date]=mysql_result($r,$i,1);
		$a_hosts[$date]=mysql_result($r,$i,2);
		$a_users[$date]=mysql_result($r,$i,3);

		if ($mini>$a_hits[$date]) $mini=$a_hits[$date];
		if ($maxi<$a_hits[$date]) $maxi=$a_hits[$date];

		if ($minh>$a_hosts[$date]) $minh=$a_hosts[$date];
		if ($maxh<$a_hosts[$date]) $maxh=$a_hosts[$date];

		if ($minu>$a_users[$date]) $minu=$a_users[$date];
		if ($maxu<$a_users[$date]) $maxu=$a_users[$date];
		} /* of for */

	for ($i=0;$i<$limit;$i++) {
		$time=time()-(86400*($i+1));
		$date=date("Ymd",$time);
		$pdate=date("Y-m-d",$time);
		if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
		$html.="<tr class=$class>\n";
		$html.="\t<td align=center>".date($CONFIG["date_format"],strtotime($pdate))."</td>\n";

		$hits=$a_hits[$date]; 
		$hosts=$a_hosts[$date]; 
		$users=$a_users[$date]; 

		if (empty($hits)) $hits="-"; else $thi+=$hits;
		if (empty($hosts)) $hosts="-"; else $tho+=$hosts;
		if (empty($users)) $users="-"; else $tus+=$users;

		$t1=$t2="";
		if ($hits==$mini) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($hits==$maxi) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=right>".$t1.$hits.$t2."</td>\n";

		$t1=$t2="";
		if ($hosts==$minh) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($hosts==$maxh) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=right>".$t1.$hosts.$t2."</td>\n";

		$t1=$t2="";
		if ($users==$minu) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($users==$maxu) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=right>".$t1.$users.$t2."</td>\n";

		if ($cb_hits=="on") $DATA[0][]=intval($hits);
		if ($cb_users=="on") $DATA[1][]=intval($users);
		if ($cb_hosts=="on") $DATA[2][]=intval($hosts);
		$DATA["x"][]=date($CONFIG["shortdm_format"],$time);

		$html.="</tr>\n";
		} /* of for */
	$html.="<tr class=tbl2><td align=center><b>".$LANG["total"]."</b></td><td align=right width=100><b>$thi</b></td><td align=right width=100><b>$tho</b></td><td align=right width=100><b>$tus</b></td></tr>";
	$html.="</table></center>";
	} /* of if ($type==1) */

if ($type==2) { /* Ïî íåäåëÿì ########################################### */
	$r=cnstats_sql_query("select LEFT(date,10),hits,hosts,users from cns_counter_total ORDER BY date desc  LIMIT ".(7*$limit).";");

	$html.="<center><br><table width=".$TW." cellspacing=1 cellpadding=3 bgcolor='#D4F3D7'>";
	$html.="<tr class=tbl2><td align=center><b>".$LANG["date"]."</b></td><td align=center width=100><b>".$LANG["hits"]."</b></td><td align=center width=100><b>".$LANG["hosts"]."</b></td><td align=center width=100><b>".$LANG["users"]."</b></td></tr>";
	$thi=$tho=$tus=0;
	for ($i=0;$i<mysql_num_rows($r);$i++) {
		$date=mysql_result($r,$i,0);
		$date=substr($date,0,4).substr($date,5,2).substr($date,8,2);
		$a_hits[$date]=mysql_result($r,$i,1);
		$a_hosts[$date]=mysql_result($r,$i,2);
		$a_users[$date]=mysql_result($r,$i,3);
		} /* of for */
	$w=0;
	$day_of_w=1;		
	$w_hits=Array();
	$w_hosts=Array();
	$w_users=Array();
	for ($i=0;$i<(7*$limit);$i++) {
		$time=time()-(86400*($i+1));
		$date=date("Ymd",$time);

		$w_hits[$w]=$w_hits[$w]+$a_hits[$date];
		$w_hosts[$w]=$w_hosts[$w]+$a_hosts[$date];
		$w_users[$w]=$w_users[$w]+$a_users[$date];
		$day_of_w++;
		if ($day_of_w>7) {$day_of_w=1;$w++;}
		} /* of for */


	for ($i=0;$i<$limit;$i++) {
		if ($mini>$w_hits[$i] && $w_hits[$i]!=0) $mini=$w_hits[$i];
		if ($maxi<$w_hits[$i]) $maxi=$w_hits[$i];

		if ($minh>$w_hosts[$i] && $w_hosts[$i]!=0) $minh=$w_hosts[$i];
		if ($maxh<$w_hosts[$i]) $maxh=$w_hosts[$i];

		if ($minu>$w_users[$i] && $w_users[$i]!=0) $minu=$w_users[$i];
		if ($maxu<$w_users[$i]) $maxu=$w_users[$i];
		}

	for ($i=0;$i<$limit;$i++) {
		if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
		$html.="<tr class=\"".$class."\">\n";
		$date1=date($CONFIG["date_format"],time()-((7*($i+1))*86400));
		$date2=date($CONFIG["date_format"],time()-((7*$i+1)*86400));
		$html.="\t<td align=\"center\">".$date1." - ".$date2."</td>\n";

		if ($cb_hits=="on") $DATA[0][]=intval($w_hits[$i]);
		if ($cb_users=="on") $DATA[1][]=intval($w_users[$i]);
		if ($cb_hosts=="on") $DATA[2][]=intval($w_hosts[$i]);
		$DATA["x"][]=date($CONFIG["shortdm_format"],time()-((7*($i+1))*86400));

		$hits=$w_hits[$i];
		$hosts=$w_hosts[$i];
		$users=$w_users[$i]; 
		
		if (empty($hits)) $hits="-"; else $thi+=$hits;
		if (empty($hosts)) $hosts="-"; else $tho+=$hosts;
		if (empty($users)) $users="-"; else $tus+=$users;

		$t1=$t2="";
		if ($hits==$mini) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($hits==$maxi) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=right>".$t1.$hits.$t2."</td>\n";

		$t1=$t2="";     
		if ($hosts==$minh) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($hosts==$maxh) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=right>".$t1.$hosts.$t2."</td>\n";

		$t1=$t2="";
		if ($users==$minu) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($users==$maxu) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=right>".$t1.$users.$t2."</td>\n";

		$html.="</tr>\n";
		} /* of for */
	$html.="<tr class=tbl2><td align=center><b>".$LANG["total"]."</b></td><td align=right width=100><b>$thi</b></td><td align=right width=100><b>$tho</b></td><td align=right width=100><b>$tus</b></td></tr>";
	$html.="</table></center>";
	} /* of if ($type==2) */

if ($type==3) { /* Ïî ìåñÿöàì ########################################### */
	$r=cnstats_sql_query("select LEFT(date,7),sum(hits),sum(hosts),sum(users) from cns_counter_total GROUP BY LEFT(date,7) ORDER BY date desc LIMIT $limit;");

	$html.="<center><br><table width=".$TW." cellspacing=1 cellpadding=3 bgcolor='#D4F3D7'>";
	$html.="<tr class=tbl2><td align=center><b>".$LANG["date"]."</b></td><td align=center width=100><b>".$LANG["hits"]."</b></td><td align=center width=100><b>".$LANG["hosts"]."</b></td><td align=center width=100><b>".$LANG["users"]."</b></td></tr>";
	$thi=$tho=$tus=0;

	for ($i=0;$i<mysql_num_rows($r);$i++) {
		$date=mysql_result($r,$i,0);
		$date=substr($date,0,4).substr($date,5,2);
		$a_hits[$date]=mysql_result($r,$i,1);
		$a_hosts[$date]=mysql_result($r,$i,2);
		$a_users[$date]=mysql_result($r,$i,3);

		if ($mini>$a_hits[$date]) $mini=$a_hits[$date];
		if ($maxi<$a_hits[$date]) $maxi=$a_hits[$date];

		if ($minh>$a_hosts[$date]) $minh=$a_hosts[$date];
		if ($maxh<$a_hosts[$date]) $maxh=$a_hosts[$date];

		if ($minu>$a_users[$date]) $minu=$a_users[$date];
		if ($maxu<$a_users[$date]) $maxu=$a_users[$date];

		} /* of for */

	for ($i=0;$i<$limit;$i++) {
		$date=date( "Ym", mktime(0,0,0,date("m")-$i,0,date("Y")));
		$pdate=date( $CONFIG["shortdate_format"], mktime(0,0,0,date("m")-$i,0,date("Y")));
		if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
		$html.="<tr class=".$class.">\n";
		$html.="\t<td align=center>".$pdate."</td>\n";

		$hits=$a_hits[$date]; 
		$hosts=$a_hosts[$date];
		$users=$a_users[$date];

		if ($cb_hits=="on") $DATA[0][]=intval($hits);
		if ($cb_users=="on") $DATA[1][]=intval($users);
		if ($cb_hosts=="on") $DATA[2][]=intval($hosts);
		$DATA["x"][]=$pdate;

		if (empty($hits)) $hits="-"; else $thi+=$hits;
		if (empty($hosts)) $hosts="-"; else $tho+=$hosts;
		if (empty($users)) $users="-"; else $tus+=$users;

		$t1=$t2="";
		if ($hits==$mini) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($hits==$maxi) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=right>".$t1.$hits.$t2."</td>\n";

		$t1=$t2="";     
		if ($hosts==$minh) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($hosts==$maxh) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=right>".$t1.$hosts.$t2."</td>\n";

		$t1=$t2="";
		if ($users==$minu) {$t1="<font color=red><B>";$t2="</font></B>";}
		if ($users==$maxu) {$t1="<font color=blue><B>";$t2="</font></B>";}
		$html.="\t<td align=right>".$t1.$users.$t2."</td>\n";

		$html.="</tr>\n";
		} /* of for */
	$html.="<tr class=tbl2><td align=center><b>".$LANG["total"]."</b></td><td align=right width=100><b>$thi</b></td><td align=right width=100><b>$tho</b></td><td align=right width=100><b>$tus</b></td></tr>";
	$html.="</table></center>";
	} /* of if ($type==3) */

$DATA[0]=array_reverse($DATA[0]);
$DATA[1]=array_reverse($DATA[1]);
$DATA[2]=array_reverse($DATA[2]);
$DATA["x"]=array_reverse($DATA["x"]);

$HTTP_SESSION_VARS["DATA"]=$DATA;

// Åñëè âûáðàí ãðàôèê â ðó÷íóþ, òî èãíîðèðóåì íàñòðîéêè
if (isset($HTTP_GET_VARS["graph"])) $CONFIG["diagram"]=intval($HTTP_GET_VARS["graph"]);
else $graph=intval($CONFIG["diagram"]);

// Îïðåäåëÿåì òèï ãðàôèêà
$GDVERSION=gdVersion();

// Åñëè GD 2.0, íî àíòè-àëèàñèíã îòêëþ÷åí, òî äåëàåì âèä,
// ÷òî GD 1.0 è òîãäà ãðàôèêè ñãëàæèâàòüñÿ íå áóäóò
if ($GDVERSION==2 && $CONFIG["antialias"]==0) $GDVERSION=1;

// Åñëè íåò GD, òî â ëþáîì ñëó÷àå âêëþ÷àåì HTML ãðàôèê
if ($GDVERSION==0) $CONFIG["diagram"]=0;

if ($CONFIG["diagram"]>0 && $CONFIG["diagram"]<4) {
	switch ($CONFIG["diagram"]) {
		case  2: $g="lines"; break;
		case  3: $g="bar"; break;
		default: $g="3d";
		}
	$img_smooth="s=".($s=="on"?1:0);
	$img_antialias="antialias=".($GDVERSION==1?0:1);
	print "<img src=\"graph/".$g.".php?".$img_smooth."&".$img_antialias."&rnd=".time()."\" width=\"".$IMGW."\" height=\"".$IMGH."\"><br>\n";
	print "<img src=\"img/none.gif\" width=\"1\" height=\"5\">";
	}
else include "graph/html.php";
?>


<br>
<script language="JavaScript" type="text/javascript">
<!--
function redraw(i) {
	var ge=document.getElementById('ge');ge.value=i;
	var gf=document.getElementById('gf');gf.submit();
	}
//-->
</script>

<center>
<form action="index.php" method="get" class="m0" id="gf">
<table width="<?=$TW;?>" cellspacing="1" cellpadding="0" bgcolor="#D4F3D7"><tr class="tbl2"><td>
<table cellspacing="0" cellpadding="2" border="0" width="100%">
<tr>
<?php
if ($GDVERSION>0) {
	if ($graph==1) print "<td><img src=\"img/graph_1_s.gif\" hspace=\"6\" width=\"18\" height=\"18\" border=\"0\"></td>";
	else print "<td><a href=\"javascript:redraw(1);\"><img src=\"img/graph_1.gif\" hspace=\"6\" width=\"18\" height=\"18\" border=\"0\"></a></td>";
	}
?>
	<td>
	<table cellspacing="0" cellpadding="0" border="0"><tr><td><input type="checkbox" name="cb_hits" <?=($cb_hits=="on"?"checked":"");?>></td><td style="color:red;"><B><?=$LANG["hits"];?></B></td></tr></table>
	</td>
	<td>
	<table cellspacing="0" cellpadding="0" border="0"><tr><td><input type="checkbox" name="s" <?=($s=="on"?"checked":"");?>></td><td><?=$LANG["smooth graphics"];?></td></tr></table>
	</td>
</tr>
<tr>
<?php
if ($GDVERSION>0) {
	if ($graph==2) print "<td><img src=\"img/graph_2_s.gif\" hspace=\"6\" width=\"18\" height=\"18\" border=\"0\"></td>";
	else print "<td><a href=\"javascript:redraw(2);\"><img src=\"img/graph_2.gif\" hspace=\"6\" width=\"18\" height=\"18\" border=\"0\"></a></td>";
	}
?>
	<td>
	<table cellspacing="0" cellpadding="0" border="0"><tr><td><input type="checkbox" name="cb_hosts" <?=($cb_hosts=="on"?"checked":"");?>></td><td style="color:blue;"><B><?=$LANG["hosts"];?></B></td></tr></table>
	</td>
	<td>&nbsp;</td>
</tr>
<tr>
<?php
if ($GDVERSION>0) {
	if ($graph==3) print "<td><img src=\"img/graph_3_s.gif\" hspace=\"6\" width=\"18\" height=\"18\" border=\"0\"></td>";
	else print "<td><a href=\"javascript:redraw(3);\"><img src=\"img/graph_3.gif\" hspace=\"6\" width=\"18\" height=\"18\" border=\"0\"></a></td>";
	}
?>
	<td>
	<table cellspacing="0" cellpadding="0" border="0"><tr><td><input type="checkbox" name="cb_users" <?=($cb_users=="on"?"checked":"");?>></td><td style="color:green;"><B><?=$LANG["users"];?></B></td></tr></table>
	</td>
	<td align="right">
	<table width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td>&nbsp;
		<select name="type">
		<option value="0" <?=($type==0?"selected":"");?>><?=$LANG["by hours"];?>
		<option value="1" <?=($type==1?"selected":"");?>><?=$LANG["by days"];?>
		<option value="2" <?=($type==2?"selected":"");?>><?=$LANG["by weeks"];?>
		<option value="3" <?=($type==3?"selected":"");?>><?=$LANG["by moths"];?>
		</select>
	</td><td align="right">
		<input type="submit" value="<?=$LANG["update"];?>">
	</td></tr></table>
	</td>
</tr>
</table>
</td></tr></table>
<input type=hidden name="st" value="<?=$st;?>">
<input type=hidden name="stm" value="<?=$stm;?>">
<input type=hidden name="ftm" value="<?=$ftm;?>">
<input type=hidden name="filter" value="<?=$filter;?>">
<input type=hidden name="day" value="<?=$day;?>">
<input type=hidden name="month" value="<?=$month;?>">
<input type=hidden name="year" value="<?=$year;?>">
<input type=hidden name="graph" value="<?=$graph;?>">
<input type=hidden name="prom" value="<?=$prom;?>">
<input type=hidden name="second" value="1">
<input type=hidden name="graph" value="<?=$graph;?>" id="ge">
</form>

<?php
print $html;
$NOFILTER=1;
?>
