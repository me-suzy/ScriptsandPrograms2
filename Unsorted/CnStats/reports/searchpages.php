<?php
$inpage=40;

$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;filter=".urlencode($filter);

$fname="reports/_phrases";
$quer="";
$quer1="";
$quer2="";
$cnt=0;
if (file_exists($fname)) {
	$fp=fopen($fname,"rt");
	while (!feof($fp)) {
		$str=fgets($fp,16384);
		$url=trim(substr($str,0,29));
		if (!empty($url)) {
			$name=trim(substr($str,29,20));
			$perem=trim(substr($str,49,strlen($str)-49));
			$quer=$quer."(IF(LOCATE('$url',referer)!=0,SUBSTRING(referer,".(strlen($perem)+1)."+LOCATE('$perem=',referer)),";
			$quer1=$quer1."(IF(LOCATE('$url',referer)!=0,'$name',";
			$quer2=$quer2." OR LOCATE('$url',referer)!=0";
			$cnt++;
			}
		}
	fclose($fp);

	$quer=$quer."'no'";
	$quer1=$quer1."'no'";
	for ($i=0;$i<$cnt;$i++) {
		$quer=$quer."))";
		$quer1=$quer1."))";
		}
	$quer2=substr($quer2,3);

	$sqlflt=GenerateFilter($filter);
	$r=cnstats_sql_query("SELECT count(*),".$quer.",page,".$quer1.",referer
              FROM cns_log
              WHERE ($quer2) AND type=1 AND date>'".$startdate."' AND date<'".$enddate."' AND referer LIKE 'http%' ".$sqlflt."
              GROUP BY page
              ORDER BY 1 desc;");

	$count=mysql_num_rows($r);
	if ($start+$inpage>$count) $finish=$count; else $finish=$start+$inpage;
	$num=$start;
	$who_num=0;
	$max=0;
	$all=0;

	LeftRight($start,$inpage,$num,$count,0);

	print $TABLE;
	print "<tr class='tbl1'><td align='center' nowrap width=75><b>".$LANG["search system"]."</b></td><td align=center><b>".$LANG["url"]."<br>".$LANG["one of the search phrases"]."</b></td><td align=center width=45><b>".$LANG["count"]."</b></td></tr>";
	for ($i=$start;$i<$finish;$i++) {
		$data=DecodePhrase(mysql_result($r,$i,1),mysql_result($r,$i,4));

		$cnt=mysql_result($r,$i,0);
		$pages=urldecode(mysql_result($r,$i,2));
		$ssystem=urldecode(mysql_result($r,$i,3));
		if (strlen($data)>50) $data=substr($data,0,50)."...";
		if (strlen($pages)>65) $pages1="...".substr($pages,0,65); else $pages1=$pages;
		if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
		print ("\n<tr class='".$class."'>\n<td valign='top'><a target='_blank' href='".mysql_result($r,$i,4)."'>".$ssystem."</a></td>\n<td><a target='_blank' href='".$pages."'>".$pages1."</a>\n<br>".$data."</td>\n<td align='right' valign='top'>&nbsp;".$cnt."&nbsp;</td></tr>\n");
		$num++;
		}

	print "</table>\n";
	LeftRight($start,$inpage,$num,$count);
	}
?>
