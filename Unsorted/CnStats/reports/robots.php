<?php
$by=intval($HTTP_GET_VARS["by"]);
$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;by=".$by."&amp;filter=".urlencode($filter);

if ($by==1) {
	$ADMENU.="<a href=\"index.php?st=robots&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=0\">".$LANG["fullreport"]."</a><br>";
	$ADMENU.=$LANG["simplereport"];
	$addfields="";
	}
else {
	$ADMENU.=$LANG["fullreport"]."<br>";
	$ADMENU.="<a href=\"index.php?st=robots&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=1\">".$LANG["simplereport"]."</a>";
	$addfields=",proxy,agent,language,country";
	}

$aquery=$query="";
$d=@file("reports/_robots");
$rc=0;
for ($i=0;$i<count($d);$i++) {
	$rr=trim(substr($d[$i],strpos($d[$i],"*")+1));
	$rn=trim(substr($d[$i],0,strpos($d[$i],"*")));
	$R[$rr]=$rn;
	$rc++;

	$query=$query."IF(LOCATE('".$rr."',CONCAT(' ',agent))!=0,'".$rn."|".$rr."',";
	$aquery=$aquery." agent like '%".$rr."%' OR";
	}

$query=$query."'".$LANG["other queries"]."'";
for ($i=0;$i<$rc;$i++) $query=$query.")";


function getrname($agent) {
	GLOBAL $R;

	reset($R);
	while (list($key,$val)=each($R)) {
		if (strpos(" ".$agent,$key)) return($val);
		}
	return("???");
	}

if ($by==1) {
	$sqlflt=GenerateFilter($filter);
	$r=cnstats_sql_query("SELECT ".$query.",count(*)
              FROM cns_log
              WHERE date>'".$startdate."' AND date<'".$enddate."' ".$sqlflt."
              GROUP BY ".$query."
              ORDER BY 2 desc;");

	while ($a=mysql_fetch_array($r,MYSQL_NUM)) {
		$e=explode("|",$a[0]);

		if ($e[0]==$LANG["other queries"]) $TABLEU[]="";
		else $TABLEU[]="index.php?dateoff=1&amp;st=log&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;sel_agent=1&amp;inp_agent=".urlencode($e[1]);

		$TABLED[]=$e[0];
		$TABLEC[]=$a[1];
		}

	ShowTable(0);
	}
else {
	$sqlflt=GenerateFilter($filter);
	$SQL="
		SELECT agent,MAX(date) as maxd,MIN(date) as mind,count(*) as countd FROM cns_log
		WHERE (".$aquery." 1=0) AND date>'".$startdate."' AND date<'".$enddate."' ".$sqlflt."
		GROUP by agent
		ORDER by countd DESC
		";

	$r=cnstats_sql_query($SQL);

	if (@mysql_num_rows($r)!=0) {
		while ($a=mysql_fetch_array($r)) {

			$logurl1="<a href='index.php?dateoff=1&amp;st=log&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;sel_agent=1&amp;inp_agent=".urlencode($a["agent"])."'>";$logurl2="</a>";
			print $TABLE."\n";
			print "<tr class=\"tbl1\">\n";
			print "\t<td width=\"100\">".$LANG["robot"]."</td><td>".getrname($a["agent"])."</td>";
			print "</tr>\n";
			print "<tr class=\"tbl1\">\n";
			print "\t<td>User-Agent</td><td>".$logurl1.$a["agent"].$logurl2."</td>";
			print "</tr>\n";
			print "<tr class=\"tbl1\">\n";
			print "\t<td>".$LANG["lcount"]."</td><td><b>".$a["countd"]."</b></td>";
			print "</tr>\n";
			print "<tr class=\"tbl2\">\n";
			print "\t<td>".$LANG["lastvisit"]."</td><td>".date($CONFIG["datetime_format"],strtotime($a["maxd"]))."</td>";
			print "</tr>\n";
			print "<tr class=\"tbl2\">\n";
			print "\t<td>".$LANG["firstvisit"]."</td><td>".date($CONFIG["datetime_format"],strtotime($a["mind"]))."</td>";
			print "</tr>\n";
			print "</table><br>\n";
			}
		}
	else {
		print $TABLE."<tr><td class=\"tbl1\">";
		print "<br><center>".$LANG["norobots"]." ".$rc."<br><br></center>";
		print "</tr></td></table>";
		}
	}
?>
