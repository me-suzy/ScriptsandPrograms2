<?php
$action=$HTTP_GET_VARS["action"];
$filter=$HTTP_GET_VARS["filter"];

if ($action==2) {
	$start=intval($HTTP_GET_VARS["start"]);
	$n=intval($HTTP_GET_VARS["n"]);
	$per=intval($HTTP_GET_VARS["per"]);
	if ($per<500) $per=500;

?>
<HTML>
<HEAD>
<TITLE>Reading file GeoIPCountryWhois.csv from <?=$start;?></TITLE>
<STYLE>
body {font-family:tahoma;font-size:11px;}
</STYLE>
</HEAD>
<BODY>
<?php
	$fw=@fopen("GeoIPCountryWhois.csv","rt");
	if (!$fw) {
		print $LANG["geoipnotfound"];
		exit;
		}
	if ($start==0) {
		cnstats_sql_query("DELETE FROM cns_countries;");
		}
	else fseek($fw,$start,SEEK_SET);

	$str=0;
	while (!feof($fw)) {
		$d=fgets($fw,1024);
		$e=explode(",",$d);
		$tld=substr($e[4],1,-1);
		$e[4]=ord($e[4][1])*256+ord($e[4][2]);
		$e[0]=substr($e[0],1,-1);
		$e[1]=substr($e[1],1,-1);
		if ($e[4]!=0) {
			$sql="INSERT INTO cns_countries SET c='".$e[4]."', ip1=INET_ATON('".$e[0]."'), ip2=INET_ATON('".$e[1]."');";
			cnstats_sql_query($sql);
			}
		
		if ($str%100==0) {
			print "<B>".$n."</B>: ".$tld." ".$e[0]."-".$e[1]."<br>\n";
			flush();
			}
		$str++;$n++;
		if ($str>=$per) {
?>
<SCRIPT language="JavaScript" type="text/javascript">
<!--
document.location="index.php?action=2&nowrap=1&st=<?=$st;?>&stm=<?=$stm;?>&ftm=<?=$ftm;?>&start=<?=ftell($fw);?>&n=<?=$n;?>&per=<?=$per;?>&filter=<?=urlencode($filter);?>";
//-->
</SCRIPT>
</BODY>
</HTML>
<?php
			fclose($fw);
			exit;
			}
		}
	print "<br><B>Done!</B>";
?>
<SCRIPT language="JavaScript" type="text/javascript">
<!--
document.location="index.php?st=<?=$st;?>&stm=<?=$stm;?>&ftm=<?=$ftm;?>&filter=<?=urlencode($filter);?>";
//-->
</SCRIPT>
</BODY>
</HTML>
<?php
	fclose($fw);
	exit;	
	}

if ($action==1) {
	$lang=$HTTP_GET_VARS["lang"];
	$lang=str_replace(";","_",$lang);$lang=str_replace(",","_",$lang);$lang=str_replace(".","_",$lang);
	$lang=cnstats_mhtml($lang);

	$gauge=$HTTP_GET_VARS["gauge"]=="on"?1:0;
	$percents=$HTTP_GET_VARS["percents"]=="on"?1:0;
	$hints=$HTTP_GET_VARS["hints"]=="on"?1:0;
	$antialias=$HTTP_GET_VARS["antialias"]=="on"?1:0;
	$diagram=intval($HTTP_GET_VARS["diagram"]);
	$date_format=cnstats_mhtml($HTTP_GET_VARS["date_format"]);
	$shortdate_format=cnstats_mhtml($HTTP_GET_VARS["shortdate_format"]);
	$shortdm_format=cnstats_mhtml($HTTP_GET_VARS["shortdm_format"]);
	$datetime_format=cnstats_mhtml($HTTP_GET_VARS["datetime_format"]);
	$datetimes_format=cnstats_mhtml($HTTP_GET_VARS["datetimes_format"]);

	cnstats_sql_query("UPDATE cns_config SET diagram='".$diagram.
                                         "', antialias='".$antialias.
                                         "', language='".$lang.
                                         "', gauge='".$gauge.
                                         "', hints='".$hints.
                                         "', percents='".$percents.
                                         "', date_format='".$date_format.
                                         "', shortdate_format='".$shortdate_format.
                                         "', shortdm_format='".$shortdm_format.
                                         "', datetime_format='".$datetime_format.
                                         "', datetimes_format='".$datetimes_format.
                                         "';");
	header("Location: index.php?st=config&stm=".$stm."&ftm=".$ftm."&filter=".$filter);
	exit;
	}

function YesNo($name,$value,$disabled="",$def="") {
	if (!empty($disabled)) $value=$def;

	print "<SELECT name=\"".$name."\" ".$disabled.">\n";
	print "<OPTION value=\"on\"".($value==1?" selected":"").">Yes\n";
	print "<OPTION value=\"off\"".($value==0?" selected":"").">No\n";
	print "</SELECT>\n";
	}

$r=cnstats_sql_query("SELECT * FROM cns_config;");
$a=mysql_fetch_array($r);

if (empty($a["date_format"])) $a["date_format"]=$LANG["date_format"];
if (empty($a["shortdate_format"])) $a["shortdate_format"]=$LANG["shortdate_format"];
if (empty($a["shortdm_format"])) $a["shortdm_format"]=$LANG["shortdm_format"];
if (empty($a["datetime_format"])) $a["datetime_format"]=$LANG["datetime_format"];
if (empty($a["datetimes_format"])) $a["datetimes_format"]=$LANG["datetimes_format"];

if ($a["timeoffset"]==1) $a["timeoffset"]=date("Z")/3600;
?>
<form action='index.php' method='get'>
<?=$TABLE;?>
<tr class="tbl0"><td></td><td width="170"></td></tr>
<tr class="tbl0"><td colspan="2" align="center"><b><?=$LANG["configmain"];?></b></td></tr>
<tr class="tbl2"><td width="100%"><?=$LANG["show diagrams"];?></td><td width="1%"><?=YesNo("gauge",$a["gauge"]);?></td></tr>
<tr class="tbl2"><td><?=$LANG["show percents"];?></td><td><?=YesNo("percents",$a["percents"]);?></td></tr>
<tr class="tbl2"><td><?=$LANG["default diagrams"];?></td><td>

<table>
<tr><td><input <?=(gdVersion()==0?"disabled":"");?> type="radio" name="diagram" value="1" <?=($a["diagram"]==1?"checked":"");?>></td><td><img src="img/graph_1_c.gif" vspace="2" width="130" height="75"></td></tr>
<tr><td><input <?=(gdVersion()==0?"disabled":"");?> type="radio" name="diagram" value="2" <?=($a["diagram"]==2?"checked":"");?>></td><td><img src="img/graph_2_c.gif" vspace="2" width="130" height="75"></td></tr>
<tr><td><input <?=(gdVersion()==0?"disabled":"");?> type="radio" name="diagram" value="3" <?=($a["diagram"]==3?"checked":"");?>></td><td><img src="img/graph_3_c.gif" vspace="2" width="130" height="75"></td></tr>
</table>

<tr class="tbl2"><td><?=$LANG["antialias"];?></td><td><?=YesNo("antialias",$a["antialias"],gdVersion()<2?"disabled":"","no");?></td></tr>


<tr class="tbl2"><td><?=$LANG["text_date_format"];?></td><td><input type="text" name="date_format" value="<?=$a["date_format"];?>" style="width:160px"></td></tr>
<tr class="tbl2"><td><?=$LANG["text_shortdate_format"];?></td><td><input type="text" name="shortdate_format" value="<?=$a["shortdate_format"];?>" style="width:160px"></td></tr>
<tr class="tbl2"><td><?=$LANG["text_shortdm_format"];?></td><td><input type="text" name="shortdm_format" value="<?=$a["shortdm_format"];?>" style="width:160px"></td></tr>
<tr class="tbl2"><td><?=$LANG["text_datetime_format"];?></td><td><input type="text" name="datetime_format" value="<?=$a["datetime_format"];?>" style="width:160px"></td></tr>
<tr class="tbl2"><td><?=$LANG["text_datetimes_format"];?></td><td><input type="text" name="datetimes_format" value="<?=$a["datetimes_format"];?>" style="width:160px"></td></tr>
<tr class="tbl2"><td><?=$LANG["language"];?></td><td><SELECT name="lang" style="width:160px">
<?php
$lng=$a["language"];

$d=dir("lang/");
while ($entry=$d->read()) {
	if (substr($entry,0,4)=="lang") {
		$lang=substr($entry,5,-4);
		if ($lang!=$lng) print "<OPTION>".$lang."\n";
		else print "<OPTION SELECTED>".$lang."\n";
		}
	}
?>
</SELECT></td></tr>
<tr class="tbl1"><td colspan="2" align="center"><input type="submit" value="<?=$LANG["save"];?>"></td></tr>
</table>
<input type="hidden" name="action" value="1">
<input type="hidden" name="st" value="config">
<input type="hidden" name="nowrap" value="1">
<input type="hidden" name="hints" value="off">
<?php
print "<input type='hidden' name='stm' value='".$stm."'>\n";
print "<input type='hidden' name='ftm' value='".$ftm."'>\n";
print "<input type='hidden' name='filter' value='".urlencode($filter)."'>\n";
?>
</form>

<form action='index.php' method='get'>
<?=$TABLE;?>
<tr class="tbl0"><td></td><td width="170"></td></tr>
<tr class="tbl0"><td colspan="2" align=center><b><?=$LANG["geocountries"];?>: <?=intval(@mysql_result(mysql_query("SELECT count(*) FROM cns_countries;"),0,0));?>)</b></td></tr>
<tr class='tbl2'><td><?=$LANG["geoipperpage"];?>:</td><td><input type="text" name="per" value="5000" style="width:160px"></td></tr>
<tr class='tbl1'><td colspan="2" align='center'><input type='submit' value='<?=$LANG["update"];?>'></td></tr>
</table>
<input type='hidden' name='action' value='2'>
<?php
print "<input type='hidden' name='st' value='config'>\n";
print "<input type='hidden' name='stm' value='".$stm."'>\n";
print "<input type='hidden' name='ftm' value='".$ftm."'>\n";
print "<input type='hidden' name='filter' value='".$filter."'>\n";
print "<input type='hidden' name='nowrap' value='1'>\n";
?>
</form>
<?php
$NOFILTER=1;
?>