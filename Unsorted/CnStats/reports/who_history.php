<?php
$filter=$HTTP_GET_VARS["filter"];

if (isset($HTTP_GET_VARS["current"])) {
	$current=intval($HTTP_GET_VARS["current"]);
	$start=intval($HTTP_GET_VARS["start"]);
	$finish=intval($HTTP_GET_VARS["finish"]);
	$t1=date("Y-m-d H:i:s",mktime(0,0,0,date("m",$current),date("d",$current),date("Y",$current)));
	$t2=date("Y-m-d H:i:s",mktime(23,59,59,date("m",$current),date("d",$current),date("Y",$current)));
	$s=date("Y-m-d H:i:s",mktime(0,0,0,date("m",$start),date("d",$start),date("Y",$start)));
	$f=date("Y-m-d H:i:s",mktime(0,0,0,date("m",$finish),date("d",$finish),date("Y",$finish)));

	print "<HTML><HEAD><TITLE>".$LANG["whoh_building"]."</TITLE></HEAD>";

	if ($current>=$HTTP_GET_VARS["finish"]) {
		$current-=86400;
		print "<BODY OnLoad='document.location=\"index.php?nowrap=1&amp;st=".$HTTP_GET_VARS["st"]."&amp;ftm=".$HTTP_GET_VARS["ftm"]."&amp;stm=".$HTTP_GET_VARS["stm"]."&amp;filter=".$HTTP_GET_VARS["filter"]."&amp;start=".$HTTP_GET_VARS["start"]."&amp;finish=".$HTTP_GET_VARS["finish"]."&amp;current=".$current."\";'>";

		$k=100/((strtotime($s)-strtotime($f))/86400);
		$p=intval(((strtotime($s)-strtotime($t1))/86400)*$k);
		$p1=$p2="";
		if ($p<50) $p2=$p."%"; else $p1=$p."%";
		print "<table width=100% height=100%><tr><td align='center'>";
		print "<table width=200 height=100 cellspacing=1 cellpadding=10 border=0 bgcolor=black><tr><td align='center' bgcolor=white>";
		print "<table style='table-layout:fixed;' width=200 bgcolor=black cellspacing=1 cellpadding=5 border=0><tr><td width='".($p*2)."' bgcolor=black align=center style='color:white;'>".$p1."</td><td width='".(200-$p*2)."' bgcolor=white align=center>".$p2."</td></tr></table>";
		print "</td></tr></table>";
		print "</td></tr></table>";
		flush();

		$fname="reports/_servers";
		$query="";
		$count_query=0;
		if (file_exists($fname)) {
			$fp=fopen($fname,"rt");
			while (!feof($fp)) {
				$str=fgets($fp,16384);
				$s=explode("*",$str);
				$s[0]=trim($s[0]);
				$s[1]=trim($s[1]);
				$s[2]=trim($s[2]);
				if (!empty($s[0])) {
					if ($type=="" || $s[0]==$type) {
						$substring=substr($s[2],1,strlen($s[2]));
						$name=addslashes(htmlspecialchars($s[1]));
						$query=$query."IF(LOCATE('".$substring."',referer)!=0,'".$name."',";
						$count_query++;
						}
					}
				}
			fclose($fp);
			$query=$query."'".$LANG["other links"]."'";
			for ($i=0;$i<$count_query;$i++) $query=$query.")";
		
			$r=cnstats_sql_query("SELECT ".$query.",count(referer)
	    	          FROM cns_log
	        	      WHERE type=1 AND date>'".$t1."' AND date<'".$t2."' AND referer LIKE 'http%'
    	        	  GROUP BY ".$query."
		              ORDER BY 2 desc;");
	
			while ($a=mysql_fetch_array($r,MYSQL_NUM)) {
				$SQL="INSERT INTO cns_who_cache SET title='".mysql_escape_string($a[0])."', crc='".crc32($a[0])."', count='".intval($a[1])."', date='".$t1."';";
				cnstats_sql_query($SQL);
				}
			}
		}
	else {
		$current-=86400;
		print "<BODY OnLoad='document.location=\"index.php?st=".$HTTP_GET_VARS["st"]."&amp;ftm=".$HTTP_GET_VARS["ftm"]."&amp;stm=".$HTTP_GET_VARS["stm"]."&amp;filter=".$HTTP_GET_VARS["filter"]."\";'>";
		}

	flush();
	print "</BODY></HTML>";
	exit;
	}

$mindate=946674000;

print $TABLE."<tr><td>";

$r=cnstats_sql_query("SHOW tables;");$found=false;
while ($a=mysql_fetch_array($r,MYSQL_NUM)) if ($a[0]=="cns_who_cache") $found=true;
if (!$found) mysql_query("CREATE TABLE cns_who_cache (date datetime, title varchar(64), crc int(11) NOT NULL default '0', count INT, KEY crc (crc)) TYPE=MyISAM;");
else {
	$r=cnstats_sql_query("SELECT max(UNIX_TIMESTAMP(date)) FROM cns_who_cache;");$found=false;
	if (mysql_num_rows($r)==1) $mindate=intval(mysql_result($r,0,0));
	if ($mindate==0) $mindate=946674000;
	}

if ($mindate==946674000) {
	print $LANG["whoh_noreport"];
	$buildfrom=$LANG["whoh_buildfromdate"];
	$build=$LANG["whoh_build"];
	}
else {
	$r=cnstats_sql_query("SELECT UNIX_TIMESTAMP(min(date)) FROM cns_who_cache;");$found=false;
	if (mysql_num_rows($r)==1) $sdate=mysql_result($r,0,0); else $sdate=$mindate;
	print $LANG["whoh_buildedfromdate"].date($LANG["date_format"],$sdate);
	print " ".$LANG["whoh_buildedtodate"].date($LANG["date_format"],$mindate);

	$buildfrom=$LANG["whoh_addbuildfromdate"];
	$build=$LANG["whoh_addbuild"];
	}
print "<br>";
$r=cnstats_sql_query("SELECT UNIX_TIMESTAMP(min(date)) FROM cns_log;");
$lmindate=mysql_result($r,0,0);
$lmindate=mktime(0,0,0,date("m",$lmindate),date("d",$lmindate),date("Y",$lmindate));

$r=cnstats_sql_query("SELECT UNIX_TIMESTAMP(max(date)) FROM cns_log;");
$lmaxdate=mysql_result($r,0,0)-86400;
$lmaxdate=mktime(0,0,0,date("m",$lmaxdate),date("d",$lmaxdate),date("Y",$lmaxdate));

if ($lmindate<$mindate) $lmindate=$mindate+86400;

if ($lmindate<$lmaxdate) {
	print $buildfrom." ".date($LANG["date_format"],$lmindate)." ";
	print $LANG["whoh_buildtodate"]." ".date($LANG["date_format"],$lmaxdate);

	print "<form action='index.php'>\n";
	print "<input type=\"submit\" value=\"".$build."\">\n";
	print "<input type=\"hidden\" name=\"finish\" value=\"".$lmindate."\">\n";
	print "<input type=\"hidden\" name=\"start\" value=\"".$lmaxdate."\">\n";
	print "<input type=\"hidden\" name=\"current\" value=\"".$lmaxdate."\">\n";
	print "<input type=\"hidden\" name=\"stm\" value=\"".$stm."\">\n";
	print "<input type=\"hidden\" name=\"ftm\" value=\"".$ftm."\">\n";
	print "<input type=\"hidden\" name=\"st\" value=\"".$st."\">\n";
	print "<input type=\"hidden\" name=\"nowrap\" value=\"1\">\n";
	print "<input type=\"hidden\" name=\"filter\" value=\"".$filter."\">\n";
	print "</form>\n";
	}
else print $LANG["whoh_nobuilddata"];
print "</td></tr></table>\n";

$server=$HTTP_GET_VARS["server"];$n=0;
if (is_array($server)) {
	while (list ($key, $val) = each ($server)) {
		if ($n>2) unset($server[$key]);
		$n++;
		}
	}

function Build($crc,$idx) {
	GLOBAL $DATA,$LANG;

	$prev=0;
	$r=cnstats_sql_query("SELECT title,count,UNIX_TIMESTAMP(date) as date FROM cns_who_cache WHERE crc='".$crc."' ORDER by date DESC LIMIT 150");
	while ($a=mysql_fetch_array($r)) {
		$DATA["title"][$idx]=$a["title"];
		if ($prev-$a["date"]!=86400 && $prev!=0) {
			for ($i=$prev-86400;$i>$a["date"];$i-=86400) {
				$DATA[$idx][]=0;
				if ($idx==0) {
					$DATA["x"][]=date($LANG["shortdm_format"],$i);
					$DATA["t"][]=date($LANG["date_format"],$i);
					}
				}
			}
		$prev=$a["date"];

		$DATA[$idx][]=$a["count"];
		if ($idx==0) {
			$DATA["x"][]=date($LANG["shortdm_format"],$a["date"]);
			$DATA["t"][]=date($LANG["date_format"],$a["date"]);
			}
		}
	}

if (is_array($server)) {

	$n=0;
	$DATA["title"]=$DATA["t"]=$DATA[0]=$DATA[1]=$DATA[2]=Array();
	while (list ($key, $val) = each ($HTTP_GET_VARS["server"])) {
		if ($n<3) Build($val,$n);
		$n++;
	    }

	$HTTP_SESSION_VARS["DATA"]=$DATA;
	while (list ($key, $val) = each ($HTTP_SESSION_VARS["DATA"])) $HTTP_SESSION_VARS["DATA"][$key]=array_reverse($HTTP_SESSION_VARS["DATA"][$key]);

	$type=1;
	$GDVERSION=gdVersion();
	if ($GDVERSION==2 && $CONFIG["antialias"]==0) $GDVERSION=1;
	if ($GDVERSION==0) $CONFIG["diagram"]=0;

	if ($CONFIG["diagram"]>0 && $CONFIG["diagram"]<4) {
		$img_antialias="antialias=".($GDVERSION==1?0:1);
		print "<center><img vspace=5 src=\"graph/lines.php?".$img_antialias."&rnd=".time()."\" width=\"".$IMGW."\" height=\"".$IMGH."\"><br>\n";
		}
	else include "graph/html.php";

	print "<br>".$TABLE;

	print "<tr class=\"tbl2\">\n";
	print "<td align=center><B>".$LANG["date"]."</B></td>\n";
	reset($DATA["title"]);
	$i=0;
	$CLR[0]="red";
	$CLR[1]="green";
	$CLR[2]="blue";
	while (list ($key, $val) = each ($DATA["title"])) {
		print "<td align=center style='color:".$CLR[$i]."'><b>".$val."</b></td>\n";
		$i++;
		}
	print "</tr>\n";

	reset($DATA["t"]);
	while (list ($key, $val) = each ($DATA["t"])) {
	    if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
		print "<tr class=\"".$class."\">\n";
		print "<td align=center>".$val."</td>\n";
		if (count($DATA[0])>0) print "<td width=10% align=\"right\">".$DATA[0][$key]."</td>\n";
		if (count($DATA[1])>0) print "<td width=10% align=\"right\">".$DATA[1][$key]."</td>\n";
		if (count($DATA[2])>0) print "<td width=10% align=\"right\">".$DATA[2][$key]."</td>\n";
		print "</tr>\n";
		}
	
	print "</table>";
	}


$r=cnstats_sql_query("SELECT title,crc FROM cns_who_cache GROUP BY crc ORDER by title");
print "<FORM action='index.php' class=\"m0\">";
print "<br>".$TABLE;
$c=0;$notr=true;
while ($a=mysql_fetch_array($r)) {
	$fnd=false;
	if (is_array($server)) {
		reset($server);
		while (list ($key, $val) = each ($server)) if ($val==$a["crc"]) $fnd=true;
		}
	$c++;
	if ($notr) {print "<tr class=\"tbl1\">";$notr=false;}
	print "<td><input name=\"server[]\" ".($fnd?"checked":"")." type=\"checkbox\" value=\"".crc32($a["title"])."\">".($fnd?"<b>":"").$a["title"].($fnd?"</b>":"")."</td>";
	if ($c==3) {print "</tr>";$c=0;$notr=true;}
	}
print "</table><br>";

print "<input type=\"hidden\" name=\"stm\" value=\"".$stm."\">\n";
print "<input type=\"hidden\" name=\"ftm\" value=\"".$ftm."\">\n";
print "<input type=\"hidden\" name=\"st\" value=\"".$st."\">\n";
print "<input type=\"hidden\" name=\"filter\" value=\"".$filter."\">\n";
print "<center><input type=submit value=\"".$LANG["update"]."\"></center>";
print "</form><br>";


$NOFILTER=1;
$NODATES=1;
?>