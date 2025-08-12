<?php
$inpage=40;

$by=($HTTP_GET_VARS["by"]=="hits")?"hits":"hosts";
$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;by=".$by."&amp;filter=".urlencode($filter);

if ($by=="hits") {
	$ADMENU.="<a href='index.php?st=referers&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=hosts'>".$LANG["by hosts"]."</a><br>";
	$ADMENU.=$LANG["by hits"];
	$howhh="";
	}
else {
	$ADMENU.=$LANG["by hosts"]."<br>";
	$ADMENU.="<a href='index.php?st=referers&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=hits'>".$LANG["by hits"]."</a>";
	$howhh=" type=1 AND ";
	}

$quer=GenerateFilter($filter);
$r=cnstats_sql_query("select referer,count(referer) from cns_log WHERE ".$howhh." date>'".$startdate."' AND date<'".$enddate."' ".$quer." group by referer order by 2 desc");

$count=mysql_num_rows($r);
if ($start+$inpage>$count) $finish=$count; else $finish=$start+$inpage;
for ($i=$start;$i<$finish;$i++) {
	$data=mysql_result($r,$i,0);
	$cnt=mysql_result($r,$i,1);
	$TABLEC[]=$cnt;
	if (!($data=="blockedReferrer" || $data=="nojs" || $data=="undefined" || empty($data))) $TABLED[]=$TABLEU[]=$data;
	else {
		$TABLEU[]="";
		if (!($data=="blockedReferrer" || $data=="nojs")) $TABLED[]=$LANG["noreferer"];
		else $TABLED[]=$data;
		}
	}

LeftRight($start,$inpage,$num,$count,0);
ShowTable($start);
LeftRight($start,$inpage,$num,$count);
?>
