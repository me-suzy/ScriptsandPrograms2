<?php
$inpage=40;

$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;filter=".urlencode($filter);

if (!isset($type)) $type="";

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

	$fltsql=GenerateFilter($filter);
	$r=cnstats_sql_query("SELECT ".$query.",count(referer)
              FROM cns_log
              WHERE type=1 AND date>'$startdate' AND date<'$enddate' AND referer LIKE 'http%' ".$fltsql."
              GROUP BY ".$query."
              ORDER BY 2 desc;");

	while ($a=mysql_fetch_array($r,MYSQL_NUM)) {
		$TABLED[]=$a[0];
		$TABLEC[]=$a[1];
		}

	ShowTable(0);
	}
?>