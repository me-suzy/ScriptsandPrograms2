<?php
$inpage=40;

$by=$HTTP_GET_VARS["by"]=="hits"?"hits":"hosts";
$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;by=".$by."&amp;filter=".urlencode($filter);

if ($by=="hits") {
	$ADMENU.="<a href='index.php?st=servers&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=hosts'>".$LANG["by hosts"]."</a><br>";
	$ADMENU.=$LANG["by hits"];
	$howhh="";
	}
else {
	$ADMENU.=$LANG["by hosts"]."<br>";
	$ADMENU.="<a href='index.php?st=servers&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=hits'>".$LANG["by hits"]."</a><br>";
	$howhh=" type=1 AND ";
	}

$quer=GenerateFilter($filter);
$r=cnstats_sql_query("SELECT IF(LOCATE('/',referer,8)=0,CONCAT(referer,'/'),LEFT(referer,LOCATE('/',referer,8))),count(referer)
              FROM cns_log
              WHERE ".$howhh." date>'".$startdate."' AND date<'".$enddate."' AND referer LIKE 'http%' ".$quer."
              GROUP BY IF(LOCATE('/',referer,8)=0,CONCAT(referer,'/'),LEFT(referer,LOCATE('/',referer,8)))
              ORDER BY 2 desc;");

$TABLED=$TABLEC=Array();

$count=mysql_num_rows($r);
if ($start+$inpage>$count) $finish=$count; else $finish=$start+$inpage;
$num=$start;
for ($i=$start;$i<$finish;$i++) {
	$data=urldecode(mysql_result($r,$i,0));
	$cnt=mysql_result($r,$i,1);
	$num++;
	if (!($data=="undefined" || empty($data))) {
		$TABLEU[]=$TABLED[]=$data;
		$TABLEC[]=$cnt;
		}
	}

LeftRight($start,$inpage,$num,$count,0);
ShowTable($start);
LeftRight($start,$inpage,$num,$count);
?>