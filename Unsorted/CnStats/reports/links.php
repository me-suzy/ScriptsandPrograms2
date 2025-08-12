<?php
$inpage=40;

$by=$HTTP_GET_VARS["by"]=="hits"?"hits":"hosts";
$filter=$HTTP_GET_VARS["filter"];

$DATELINK="&amp;by=".$by."&amp;filter=".urlencode($filter);

$fname="reports/_phrases";
$quer="";
$quer1="";
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
			$quer1=$quer1."(IF(LOCATE('$url',referer)!=0,'$name',\n";
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
	$r=cnstats_sql_query("SELECT ".$quer.",count(referer),".$quer1.",referer
              FROM cns_log
              WHERE ".$howhh." date>'".$startdate."' AND date<'".$enddate."' AND referer LIKE 'http%' ".$sqlflt."
              GROUP BY ".$quer."
              ORDER BY 2 desc;");

	$count=mysql_num_rows($r);
	if ($count>0) {

		LeftRight($start,$inpage,$num,$count,0);
		print $TABLE;

		if ($start+$inpage>$count) $finish=$count; else $finish=$start+$inpage;
		$num=$start;
		$who_num=0;
		$max=0;
		$all=0;
		print "<tr class=tbl1><td align='center' width=75>&nbsp;<b>".$LANG["search system"]."</b></td><td align=center width=251><b>".$LANG["search phrases"]."</b></td><td align='center' width=45><b>".$LANG["count"]."</b></td></tr>";
		for ($i=$start;$i<$finish;$i++) {
			$data=DecodePhrase(mysql_result($r,$i,0),mysql_result($r,$i,3));
			$cnt=mysql_result($r,$i,1);
			$ssystem=mysql_result($r,$i,2);

			if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
			
			if (substr($data,0,5)!="tp://")
				if ($ssystem!="no")
					print ("\n<tr class=".$class.">\n<td>&nbsp;<a href='".mysql_result($r,$i,3)."'>".$ssystem."</a></td>\n<td>".$data."</td>\n<td align=right width='10%'>&nbsp;".$cnt."&nbsp;</td></tr>");
			$num++;
			}
		print "\n</table>\n";
		LeftRight($start,$inpage,$num,$count);
		}
	}
?>