<?php
$by=intval($HTTP_GET_VARS["by"]);
$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;by=".$by."&amp;filter=".urlencode($filter);

if ($by==1) {
	$ADMENU.="<a href=\"index.php?st=browsers&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=0\">".$LANG["with versions"]."</a><br>";
	$ADMENU.=$LANG["without versions"];
	$fname="reports/_browsers1";
	}
else {
	$ADMENU.=$LANG["with versions"]."<br>";
	$ADMENU.="<a href=\"index.php?st=browsers&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=1\">".$LANG["without versions"]."</a>";
	$fname="reports/_browsers";
	}

$query="";
$count_query=0;
if (file_exists($fname)) {
	$fp=fopen($fname,"rt");
	while (!feof($fp)) {
		$str=fgets($fp,16384);
		$s=explode("*",$str);
		$s[0]=trim($s[0]);
		$s[1]=trim($s[1]);
		if (!empty($s[0])) {
			$substring=substr($s[1],1,strlen($s[1]));
			$name=$s[0];
			$query=$query."IF(LOCATE('".$substring."',agent)!=0,'".$name."',";
			$count_query++;
			}
		}
	fclose($fp);
	$query=$query."'".$LANG["other browsers"]."'";
	for ($i=0;$i<$count_query;$i++) $query=$query.")";

	$sqlflt=GenerateFilter($filter);
	$r=cnstats_sql_query("SELECT ".$query.",count(*)
              FROM cns_log
              WHERE date>'".$startdate."' AND date<'".$enddate."' ".$sqlflt."
              GROUP BY ".$query."
              ORDER BY 2 desc;");

	while ($a=mysql_fetch_array($r,MYSQL_NUM)) {
		$TABLED[]=$a[0];
		$TABLEC[]=$a[1];
		}

	ShowTable(0);
	}

$DATELINK="&amp;by=".$by;
?>
<br>