<?
$inpage=40;

$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;filter=".urlencode($filter);

$IP=Array();
$URLS=Array();
$CNTS=Array();

$sqlflt=GenerateFilter($filter);
$r=cnstats_sql_query("SELECT ip,proxy,page FROM cns_log WHERE date>'".$startdate."' AND date<'".$enddate."' ".$sqlflt." ORDER BY id DESC");
while ($b=mysql_fetch_array($r,MYSQL_ASSOC)) {
	$key=$b["ip"]."-".$b["proxy"];

	if (!isset($IP[$key])) {
		$url=urldecode($b["page"]);
		$crc=crc32($url);
		$URLS[$crc]=$url;
		if (isset($CNTS[$crc])) $CNTS[$crc]++; else $CNTS[$crc]=1;

		$IP[$key]=1;
		}
	}

arsort($CNTS);

$count=count($CNTS);
if ($start+$inpage>$count) $finish=$count; else $finish=$start+$inpage;
$num=0;

while (list ($key, $val) = each ($CNTS)) {

	if ($num>=$finish) break;
	if ($num>=$start) {
		$url=$URLS[$key];
		$TABLED[]="<A href='".$url."' target=_blank>".$url."</a>";
		$TABLEU[]="";
		$TABLEC[]=$val;
		}
	$num++;
    }

LeftRight($start,$inpage,$num,$count,0);
ShowTable($start);
LeftRight($start,$inpage,$num,$count);
?>
