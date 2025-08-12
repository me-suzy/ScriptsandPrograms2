<?php
$inpage=40;

$by=$HTTP_GET_VARS["by"]=="hits"?"hits":"hosts";
$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;by=".$by."&amp;filter=".urlencode($filter);

$fname="reports/_phrases";
$quer="";
$cnt=0;
if (file_exists($fname)) {
	$fp=fopen($fname,"rt");
	while (!feof($fp)) {
		$str=fgets($fp,16384);
		$url=trim(substr($str,0,29));
		if (!empty($url)) {
			$name=trim(substr($str,29,20));
			$perem=trim(substr($str,49,strlen($str)-49));
			$quer=$quer."(IF(LOCATE('$url',referer)!=0,SUBSTRING(referer,".(strlen($perem)+1)."+LOCATE('$perem=',referer)),\n";
			$cnt++;
			}
		}
	fclose($fp);
	$quer=$quer."'no'";

	for ($i=0;$i<$cnt;$i++) {
		$quer=$quer."))";
		$quer1=$quer1."))";
		}


	if ($by=="hits") {
		$ADMENU.="<a href='index.php?st=".$st."&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=hosts'>".$LANG["by hosts"]."</a><br>";
		$ADMENU.=$LANG["by hits"];
		$howhh="";
		}
	else {
		$ADMENU.=$LANG["by hosts"]."<br>";
		$ADMENU.="<a href='index.php?st=".$st."&amp;stm=".$stm."&amp;ftm=".$ftm.RemoveVar("by",$DATELINK)."&amp;by=hits'>".$LANG["by hits"]."</a>";
		$howhh=" type=1 AND ";
		}

	$sqlflt=GenerateFilter($filter);
	$r=cnstats_sql_query("SELECT $quer,count(referer),referer
              FROM cns_log
              WHERE $howhh date>'$startdate' AND date<'$enddate' AND referer LIKE 'http%' ".$sqlflt."
              GROUP BY $quer
              ORDER BY 2 desc;");


	$PH=Array();
	while ($a=mysql_fetch_row($r)) {
		$data=$a[0];
		$cnt=$a[1];

		$data=DecodePhrase($data,$a[2]);

		if (substr($data,0,5)!="tp://" && $data!="no") {
			$data=strrtolower(strtolower($data));
			if (isset($PH[$data])) $PH[$data]+=$cnt; else $PH[$data]=$cnt;
			}
		}

	arsort($PH);

	$count=0;
	while (list ($key, $val) = each ($PH)) {
		if ($count>=$start && $count<$start+$inpage) {
			$TABLED[]=$key;
			$TABLEC[]=$val;
			}
		$count++;
	    }

	LeftRight($start,$inpage,$num,$count,0);
	ShowTable($start);
	LeftRight($start,$inpage,$num,$count);

	}
?>
