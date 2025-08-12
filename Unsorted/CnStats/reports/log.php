<?php

$by=intval($HTTP_GET_VARS["by"]);
$filter=$HTTP_GET_VARS["filter"];

$qs=RemoveVar("stm",str_replace("&","&amp;",$HTTP_SERVER_VARS["QUERY_STRING"]));
$qs=RemoveVar("ftm",$qs);
$qs=RemoveVar("st",$qs);
$qs=RemoveVar("start",$qs);

$DATELINK="&amp;".RemoveVar("start",RemoveVar("sd",RemoveVar("fd",$qs)));

function CustomSelect($name,$add="") {
	GLOBAL $HTTP_GET_VARS,$LANG;

	print "<SELECT style=\"width:100%\" OnChange=\"javascript:eSelect(this.value,'inp_".$name."')\" name=\"sel_".$name."\" id=\"sel_".$name."\"".$add.">\n";
	print "<OPTION value=\"0\" ".($HTTP_GET_VARS["sel_".$name]=="0"?"SELECTED":"").">".$LANG["log_any"]."\n";
	print "<OPTION value=\"1\" ".($HTTP_GET_VARS["sel_".$name]=="1"?"SELECTED":"").">".$LANG["log_like"]."\n";
	print "<OPTION value=\"2\" ".($HTTP_GET_VARS["sel_".$name]=="2"?"SELECTED":"").">".$LANG["log_notlike"]."\n";
	print "</SELECT>\n";
	}

function CustomInput($name,$add="") {
	GLOBAL $HTTP_GET_VARS,$LANG;

	if ($HTTP_GET_VARS["sel_".$name]==0) $ds="disabled "; else $ds="";
	print "<input ".$ds."type=\"text\" style=\"width:100%\" id=\"inp_".$name."\" name=\"inp_".$name."\" value=\"".cnstats_mhtml($HTTP_GET_VARS["inp_".$name])."\"".$add.">";
	}

?>
<STYLE>
<!--
.vis1 { visibility:visible; display:inline; }
.vis2 { visibility:hidden; display:none; }
//-->
</STYLE>

<SCRIPT Language="JavaScript" type="text/javascript">
<!--
function eSelect(t,e) {
	var el=document.getElementById(e);
	if (el) {
		if (t==0) el.disabled=true; else el.disabled=false;
		}
	}

function EnaDis(elid,is) {
	var s=document.getElementById(elid);
	if (s) s.disabled=is;
	
	}

function eCountry(t) {
	EnaDis("sel_country",!t);
	if (t) {
		var s=document.getElementById("sel_country");
		EnaDis('inp_country',(s.value==0)?true:false);
		}
	else EnaDis('inp_country',true);
	}

function ptable_ex() {
	var t=document.getElementById("ptable");
	var i=document.getElementById("pimg");
	if (t.className=="vis1") {
		t.className="vis2";
		document.cookie="cnstats_report_log=hidden";
		i.src="img/expand.gif";
		}
	else {
		t.className="vis1";
		document.cookie="cnstats_report_log=visible";
		i.src="img/colapse.gif";
		}
	}

//-->
</SCRIPT>
<?php
print $TABLE;
$expanded=$HTTP_COOKIE_VARS["cnstats_report_log"]=="visible"?true:false;
?>
<tr class="tbl0"><td><a href="JavaScript:ptable_ex();"><img id='pimg' src='img/<?=!$expanded?"expand":"colapse";?>.gif' width=17 height=17 border=0></a></td><td width='95%'><?=$LANG["log_additional"];?>
</td></tr></table>

<table width='<?=$TW;?>' id='ptable' cellspacing='1' border='0' cellpadding='3' bgcolor='#D4F3D7' style='table-layout:fixed;' class="<?=($expanded?"vis1":"vis2");?>">
<form action="index.php" method="get">
<tr class="tbl1"><td width="30%"><?=$LANG["log_page"];?></td><td width="20%"><?=CustomSelect("page");?></td><td width="50%"><?=CustomInput("page");?></td></tr>
<tr class="tbl2"><td nowrap><?=$LANG["log_referer"];?></td><td><?=CustomSelect("referer");?></td><td><?=CustomInput("referer");?></td></tr>
<tr class="tbl1"><td><?=$LANG["log_language"];?></td><td><?=CustomSelect("language");?></td><td><?=CustomInput("language");?></td></tr>
<tr class="tbl2"><td><?=$LANG["log_useragent"];?></td><td><?=CustomSelect("agent");?></td><td><?=CustomInput("agent");?></td></tr>
<tr class="tbl1"><td><?=$LANG["log_ip"];?></td><td>

<SELECT name="sel_ip" style="width:100%" OnClick="EnaDis('inp_ip',this.value!=2?true:false);">
<OPTION <?=$HTTP_GET_VARS["sel_ip"]=="0"?"SELECTED":"";?> value="0"><?=$LANG["log_any"];?>
<OPTION <?=$HTTP_GET_VARS["sel_ip"]=="1"?"SELECTED":"";?> value="1"><?=$LANG["log_hidden"];?>
<OPTION <?=$HTTP_GET_VARS["sel_ip"]=="2"?"SELECTED":"";?> value="2"><?=$LANG["log_calculate"];?>
</SELECT>
                            
</td><td><?=CustomInput("ip",$HTTP_GET_VARS["sel_ip"]==2?"":"disabled");?></td></tr>
<tr class="tbl2"><td><?=$LANG["log_proxy"];?></td><td>

<SELECT name="sel_proxy" style="width:100%" OnClick="EnaDis('inp_proxy',this.value!=3?true:false);">
<OPTION <?=$HTTP_GET_VARS["sel_proxy"]=="0"?"SELECTED":"";?> title="<?=$LANG["log_proxy1"];?>" value="0"><?=$LANG["log_any"];?>
<OPTION <?=$HTTP_GET_VARS["sel_proxy"]=="1"?"SELECTED":"";?> title="<?=$LANG["log_proxy2"];?>" value="1"><?=$LANG["log_without_proxy"];?>
<OPTION <?=$HTTP_GET_VARS["sel_proxy"]=="2"?"SELECTED":"";?> title="<?=$LANG["log_proxy3"];?>" value="2"><?=$LANG["log_any_proxy"];?>
<OPTION <?=$HTTP_GET_VARS["sel_proxy"]=="3"?"SELECTED":"";?> title="<?=$LANG["log_proxy4"];?>" value="3"><?=$LANG["log_with_proxy"];?>
</SELECT>

</td><td><?=CustomInput("proxy",$HTTP_GET_VARS["sel_proxy"]==3?"":"disabled");?></td></tr>


<tr class="tbl1"><td><?=$LANG["log_country"];?></td><td>

<SELECT OnClick="EnaDis('inp_country',this.value==0?true:false);" name="sel_country" style="width:100%" id="sel_country" <?=$HTTP_GET_VARS["hosts"]=="yes"?"":"disabled";?>>
<OPTION value="0" <?=$HTTP_GET_VARS["sel_country"]=="0"?"SELECTED":"";?>><?=$LANG["log_any"];?>
<OPTION value="1" <?=$HTTP_GET_VARS["sel_country"]=="1"?"SELECTED":"";?>><?=$LANG["log_calculate"];?>
</SELECT>

</td><td>

<SELECT name="inp_country" id="inp_country" style="width:100%" <?=($HTTP_GET_VARS["hosts"]=="yes"&&$HTTP_GET_VARS["sel_country"]!=0)?"":"disabled";?>>
<?php
while (list ($key, $val) = each ($COUNTRY)) {
	$code=ord($key[0])*256+ord($key[1]);
	print "<OPTION value=\"".$code."\" ".($HTTP_GET_VARS["inp_country"]==$code?"selected":"").">".$key." / ".$val."\n";
    }
?>
</SELECT>
<tr class="tbl2"><td colspan="3">
<table><tr><td><input <?=($HTTP_GET_VARS["hosts"]=="yes"?"checked":"");?> onClick="eCountry(this.checked)" type="checkbox" name="hosts" value="yes"></td><td><?=$LANG["log_hosts"];?></td></tr></table>
</td></tr>
<tr class="tbl1"><td colspan="3" align="center">
<input type="hidden" name="st" value="log">
<input type="hidden" name="by" value="<?=$by;?>">
<input type="hidden" name="ftm" value="<?=intval($ftm);?>">
<input type="hidden" name="stm" value="<?=intval($stm);?>">
<input type="hidden" name="filter" value="<?=$filter;?>">
<input type="submit" value="<?=$LANG["log_show"];?>">
</td></tr>
</form>
</table>
<?php
$inpage=40;

if ($by==1) {
	$ADMENU.="<a href=\"index.php?st=log&amp;stm=".$stm."&amp;ftm=".$ftm."&amp;by=0&amp;".RemoveVar("by",$qs)."\">".$LANG["fullreport"]."</a><br>";
	$ADMENU.=$LANG["simplereport"];
	$addfields="";
	}
else {
	$ADMENU.=$LANG["fullreport"]."<br>";
	$ADMENU.="<a href=\"index.php?st=log&amp;stm=".$stm."&amp;ftm=".$ftm."&amp;by=1&amp;".RemoveVar("by",$qs)."\">".$LANG["simplereport"]."</a>";
	$addfields=",proxy,agent,language,country";
	}

$where="WHERE 1=1";
if ($HTTP_GET_VARS["hosts"]=="yes") $where.=" AND type=1";

if ($HTTP_GET_VARS["sel_country"]==1) $where.=" AND country='".intval($HTTP_GET_VARS["inp_country"])."'";

if ($HTTP_GET_VARS["sel_proxy"]==1) $where.=" AND proxy=-1";
if ($HTTP_GET_VARS["sel_proxy"]==2) $where.=" AND proxy!=-1";
if ($HTTP_GET_VARS["sel_proxy"]==3) $where.=" AND proxy='".ip2long($HTTP_GET_VARS["inp_proxy"])."'";

if ($HTTP_GET_VARS["sel_ip"]==1) $where.=" AND ip=-1";
if ($HTTP_GET_VARS["sel_ip"]==2) $where.=" AND ip='".ip2long($HTTP_GET_VARS["inp_ip"])."'";

if ($HTTP_GET_VARS["sel_agent"]==1) $where.=" AND agent like '%".cnstats_mhtml($HTTP_GET_VARS["inp_agent"])."%'";
if ($HTTP_GET_VARS["sel_agent"]==2) $where.=" AND agent not like '%".cnstats_mhtml($HTTP_GET_VARS["inp_agent"])."%'";
	
if ($HTTP_GET_VARS["sel_referer"]==1) $where.=" AND referer like '%".cnstats_mhtml($HTTP_GET_VARS["inp_referer"])."%'";
if ($HTTP_GET_VARS["sel_referer"]==2) $where.=" AND referer not like '%".cnstats_mhtml($HTTP_GET_VARS["inp_referer"])."%'";

if ($HTTP_GET_VARS["sel_page"]==1) $where.=" AND page like '%".str_replace("%","\%",urlencode(cnstats_mhtml($HTTP_GET_VARS["inp_page"])))."%'";
if ($HTTP_GET_VARS["sel_page"]==2) $where.=" AND page not like '%".str_replace("%","\%",urlencode(cnstats_mhtml($HTTP_GET_VARS["inp_page"])))."%'";

$sqlflt=GenerateFilter($filter);
$sql="select date,ip,page,referer,id,type".$addfields." from cns_log ".$where." AND date>'".$startdate."' AND date<'".$enddate."' ".$sqlflt." order by 1 desc LIMIT 2000";
$r=cnstats_sql_query($sql);

$count=mysql_num_rows($r);
if ($start+$inpage>$count) $finish=$count; else $finish=$start+$inpage;
$num=$start;

LeftRight($start,$inpage,$num,$count,10,5,"&amp;".RemoveVar("by",$qs));


if ($by==1) {
	print $TABLE;
	print "<tr class='tbl1'><td width=110 valign='top' align='center'><B>".$LANG["date"]."<br>".$LANG["ip"]."</B></td>";
	print "<td align='center'><B>URL<br>".$LANG["refering page"]."</td>";
	print "</tr>\n";

	for ($i=$start;$i<$finish;$i++) {
		$date=date($CONFIG["datetime_format"],strtotime(mysql_result($r,$i,0)));
		$ip=long2ip(mysql_result($r,$i,1));
		$page=urldecode(mysql_result($r,$i,2));
		$from=urldecode(mysql_result($r,$i,3));
		$rid=mysql_result($r,$i,4);
		$type=mysql_result($r,$i,5);
		$num++;
		if (strlen($page)>55) $printpage=substr($page,0,55)."..."; else $printpage=$page;
		if (strlen($from)>55) $printfrom=substr($from,0,55)."..."; else $printfrom=$from;

		if ($type==1) print "<tr class=\"tbl1\">"; else print "<tr class=\"tbl2\">";
		print "<td valign=top>".$date."<br>\n";
		print "<a href=\"index.php?rid=".$rid."&amp;st=ipinfo\">".$ip."</a></td>\n";
		print "<td valign=\"top\"><a href=\"".$page."\">".$printpage."</a>\n<br>";
		print                    "<a href=\"".$from."\">".$printfrom."</a>\n</td>\n";
		}
	print "</table>\n";
	}
else {
	for ($i=$start;$i<$finish;$i++) {
		$date=date($CONFIG["datetime_format"],strtotime(mysql_result($r,$i,0)));
	    $ip=long2ip(mysql_result($r,$i,1));
	    $page=mysql_result($r,$i,2);
	    $from=mysql_result($r,$i,3);
	    $rid=mysql_result($r,$i,4);
	    if ($class!="tbl1") $class="tbl1"; else $class="tbl2";
	    $num++;
		$page=urldecode($page);
		$from=urldecode($from);

		$proxy=mysql_result($r,$i,6)==-1?$LANG["noproxy"]:long2ip(mysql_result($r,$i,6));

	    if (strlen($page)>70) $printdata1=substr($page,0,70)."..."; else $printdata1=$page;
	    if (strlen($from)>70) $printdata2=substr($from,0,70)."..."; else $printdata2=$from;

		if ($ip=="255.255.255.255") $ip=$LANG["unknownip"];
		else $ip="<a href=\"index.php?rid=".$rid."&amp;st=ipinfo&amp;stm=".$stm."&amp;ftm=".$ftm."\">".$ip."</a>";

		$pfiltua=str_replace("%FLT",urlencode(mysql_result($r,$i,7)),$filtua);
		$pfiltip=str_replace("%FLT",mysql_result($r,$i,1),$filtip);

		
		$language=GetLanguage(substr(mysql_result($r,$i,8),0,2));
		if (empty($language)) $language=mysql_result($r,$i,8);
		else $language.=" (".mysql_result($r,$i,8).")";

		print $TABLE;
	    print "<tr class=\"tbl2\"><td width=\"100\">".$LANG["date"]."</td><td>".$date."</td></tr>\n";
	    print "<tr class=\"tbl1\"><td>".$LANG["url"]."</td><td><a href=\"".$page."\" target=\"_blank\">".$printdata1."</a></td></tr>\n";
	    print "<tr class=\"tbl1\"><td>".$LANG["referer"]."</td><td><a href=\"".$from."\" target=\"_blank\">".$printdata2."</a></td></tr>\n";
	    print "<tr class=\"tbl2\"><td>".$LANG["ip"]."</td><td>".$pfiltip.$ip."</td></tr>\n";
	    print "<tr class=\"tbl2\"><td>".$LANG["proxy"]."</td><td>".$proxy."</td></tr>\n";
	    print "<tr class=\"tbl2\"><td>User-Agent</td><td>".$pfiltua.mysql_result($r,$i,7)."</td></tr>\n";
	    print "<tr class=\"tbl2\"><td>".$LANG["language"]."</td><td>".$language."</td></tr>\n";

		
		$country=mysql_result($r,$i,9);
		if ($country!=0) {
			$tld="";
			if ($country=="0") $country=$LANG["other countries"];
			else {
				$tld=chr($country>>8).chr($country&0xFF);
				if (isset($COUNTRY[$tld])) $country=$COUNTRY[$tld];
				else $country=$tld;
	
				$country="<img src=img/countries/".strtolower($tld).".gif width=18 height=12 border=0 align=absmiddle>&nbsp;&nbsp;".$country;
				}
		    print "<tr class=\"tbl2\"><td>".$LANG["country"]."</td><td>".$country."</td></tr>\n";
			}

	    print "</tr>\n";
		print "</table>\n<br>\n";
	    }
	}
?>
