<?php
$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;filter=".urlencode($filter);

$fname="reports/_os";
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
	$query=$query."'".$LANG["other systems"]."'";
	for ($i=0;$i<$count_query;$i++) $query=$query.")";

	$sqlflt=GenerateFilter($filter);
	$r=cnstats_sql_query("SELECT ".$query.",count(*)
              FROM cns_log
              WHERE type=1 AND date>'".$startdate."' AND date<'".$enddate."' ".$sqlflt."
              GROUP BY ".$query."
              ORDER BY 2 desc;");

	$count=mysql_num_rows($r);

	while ($a=mysql_fetch_array($r,MYSQL_NUM)) {
		$TABLED[]=$a[0];
		$TABLEC[]=$a[1];
		}

	ShowTable(0);
	}
?>
<br>