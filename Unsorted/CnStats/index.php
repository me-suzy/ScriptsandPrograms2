<?
error_reporting(E_ALL & ~E_NOTICE);
$LANG=Array();

if (@is_file("install.php") || !is_file("config.php")) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>CNStats 2.1</TITLE>
</HEAD>
<BODY>
<P>It is necessary to fullfill the following steps to install CNStats: </P>

<OL>
<LI>Run the script "<B>install.php</B>". You may do this by clicking <a href='install.php'>this</a> link. </LI>
<LI>Delete the file "<b>install.php</b>" !!!</LI>
</OL>

<HR size=1>
<P>Äëÿ óñòàíîâêè ñèñòåìû CNStats íà âàø ñåðâåð âàì íåîáõîäèìî âûïîëíèòü ñëåäóþùèå äåéñòâèÿ: </P>
<OL>
<LI>Âûïîëíèòü ñêðèïò "<B>install.php</B>". Ýòî ìîæíî ñäåëàòü ïåðåéäÿ ïî <a href=install.php>ýòîé</a> ññûëêå. </LI>
<LI>Óäàëèòü ôàéë "<b>install.php</b>" !!! </LI>
</OL>
</BODY>
</HTML>
<?
print $LANG["copyright"];
exit;
}

include "_funct.php";

session_start();
session_register("DATA");

mysqlconnect();

$err=0;
if ($HTTP_SERVER_VARS["REQUEST_METHOD"]=="POST") {
if (md5($HTTP_POST_VARS["password"])!=$STATS_CONF["adminpassword"]) $err=1;
if ($HTTP_POST_VARS["login"]!=$STATS_CONF["cnsoftwarelogin"]) $err=2;

if ($err==0) {
$hash=md5(time().$HTTP_POST_VARS["login"].$HTTP_POST_VARS["password"]);
cnstats_sql_query("UPDATE cns_config SET hash='".$hash."';");
if ($HTTP_POST_VARS["store"]=="on")
setcookie("CNSSESSION",$hash,time()+86400*365);
else
setcookie("CNSSESSION",$hash);
header("Location: ./index.php");
exit;
}
}

$CONFIG=mysql_fetch_array(cnstats_sql_query("SELECT * FROM cns_config"));
include "lang/lang_".$CONFIG["language"].".php";

if ($COUNTER["disablepassword"]!="yes") {

if ($CONFIG["hash"]!=$HTTP_COOKIE_VARS["CNSSESSION"] || strlen($CONFIG["hash"])!=32) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?=$LANG["softname"];?></TITLE>
<STYLE type="text/css">
<!--
body,td {font-size:11px;font-family:tahoma,sans-serif}
//-->
</STYLE>
</HEAD>
<BODY>
<table style='width:100%;height:100%'><tr><td>
<form class='m0' action='index.php' method='post' name="web">
<table border='0' cellspacing='1' cellpadding='4' bgcolor='#B8D9D9' align='center' width='250'>
<tr bgcolor='#B6D7D7'><td>
<img src="/cnstats/img/cntg.png" width="32" hspace="5" height="32"
border="0" alt="WDYL-WTN Release"></td>
<td width='100%'> 
<style='font-size:13px;font-family:verdana;color:white;text-decoration:none;'><B><?=$LANG["softname"];?></B></style></td></tr>
<tr><td bgcolor='#FEFEFE' align='center' colspan='2'>

<table>
<tr><td><?=$LANG["login"];?>:  </td><td><input type='text' name='login'>
<? if ($err==2) print "<br><font color=red>".$LANG["incorrect login"]."</font>";?>
</td></tr>
<tr><td><?=$LANG["password"];?>: </td><td><input type='password' name='password'>
<? if ($err==1) print "<br><font color=red>".$LANG["incorrect password"]."</font>";?>
</td></tr>
<tr><td colspan=2>

<input type="checkbox" name="store"> <?=$LANG["store password"];?>

</td></tr>
</table>

</td></tr>
<tr><td bgcolor='#FEFEFE' colspan='2' align='center'><input type='submit' value='<?=$LANG["submit"];?>' style='font-family:verdana,sans-serif;font-size:11px'></td></tr>
</table>
<input type='hidden' name='action' value='enter'>
</form>
</td></tr>
</table>
</BODY>
</HTML>
<?
exit;
}
}

if (empty($CONFIG["date_format"])) $CONFIG["date_format"]=$LANG["date_format"];
if (empty($CONFIG["shortdate_format"])) $CONFIG["shortdate_format"]=$LANG["shortdate_format"];
if (empty($CONFIG["shortdm_format"])) $CONFIG["shortdm_format"]=$LANG["shortdm_format"];
if (empty($CONFIG["datetime_format"])) $CONFIG["datetime_format"]=$LANG["datetime_format"];
if (empty($CONFIG["datetimes_format"])) $CONFIG["datetimes_format"]=$LANG["datetimes_format"];


$MENU_GROUPS=Array(
0=>$LANG["pages"],
1=>$LANG["referers"],
2=>$LANG["geography"],
3=>$LANG["system"],
4=>$LANG["other"],
5=>$LANG["config"]
);

$MENU=Array(
0,"attendance" ,$LANG["attendance"],
0,"pages"      ,$LANG["most popular pages"],
0,"input"      ,$LANG["input pages"],
0,"output"     ,$LANG["output pages"],
0,"domains"    ,$LANG["domain names"],
0,"parts"      ,$LANG["firts level"],

1,"referers"   ,$LANG["refer pages"],
1,"servers"    ,$LANG["refer sites"],
1,"who_s"      ,$LANG["search systems"],
1,"who_c"      ,$LANG["catalogs"],
1,"who_r"      ,$LANG["ratings"],
1,"who"        ,$LANG["popular sites"],
1,"who_history",$LANG["who_history"],
1,"phrases"    ,$LANG["search phrases"],
1,"links"      ,$LANG["search links"],
1,"goodies"    ,$LANG["partners"],
1,"searchpages",$LANG["found pages"],

2,"ip"         ,$LANG["ip adresses"],
2,"subnets"    ,$LANG["nets"],
2,"lang1"      ,$LANG["languages"],

3,"system"     ,$LANG["user-agents"],
3,"lang"       ,$LANG["accept-languages"],
3,"browsers"   ,$LANG["browsers"],
3,"robots"     ,$LANG["robots"],
3,"os"         ,$LANG["operating systems"],

4,"now"        ,$LANG["on-line"],
4,"log"        ,$LANG["log"],


5,"filters"    ,$LANG["filters"],
5,"getcode"    ,$LANG["getcode"],
5,"confmail"   ,$LANG["confmail"],
5,"config"     ,$LANG["config"],
5,"dbsize"     ,$LANG["dbsize"],
5,"logout&amp;nowrap=1"     ,$LANG["logout"],

);

$start=intval($HTTP_GET_VARS["start"]);

$st=$HTTP_GET_VARS["st"];
if (empty($st)) $st="attendance";
if (!file_exists("reports/".$st.".php")) $st="attendance";

if (isset($HTTP_GET_VARS["sd"])) {
$stm=strtotime($HTTP_GET_VARS["sd"]);
$ftm=strtotime($HTTP_GET_VARS["fd"]);
}
else {
$stm=intval($HTTP_GET_VARS["stm"]);
$ftm=intval($HTTP_GET_VARS["ftm"]);
if ($stm==0) {

$stm=mktime(0,0,0,date("m"),date("d"),date("Y"))+$COUNTER["timeoffset"];
$ftm=mktime(23,59,59,date("m"),date("d"),date("Y"))+$COUNTER["timeoffset"];

}
}

$startdate=date("Y-m-d H:i:s",intval($stm));
$enddate=date("Y-m-d H:i:s",intval($ftm));

$DATELINK="";
if ($HTTP_GET_VARS["nowrap"]==1 || $HTTP_POST_VARS["nowrap"]==1) {
include "reports/".$st.".php";
exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?=$LANG["softname"];?></TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?=$LANG["charset"];?>">
<STYLE TYPE="text/css">
<!--
h1 {font-size:14px;padding-left:10px;}
pre {color:#34783E;font-size:12px;font-family:courier new;}
hr {height:1px;color:#B8E1BD;}
ul {margin:10px 0 0 15px;}
.tiny {font-size:9px;}
select,input,td {font-family:tahoma,sans-serif;font-size:11px;}
td.title {font-family:tahoma,sans-serif;font-size:11px;font-weight:bold;}
a,a:visited {text-decoration:none;color:blue;}
a:hover {text-decoration:underline}
a.top_menu {text-decoration:none;font-size:10px;}
.tbl0 {background-color:#D4F3D7;}
.tbl1 {background-color:#E7F9EA;}
.tbl2 {background-color:#F2FCF4;}
.vis1 { visibility:visible; display:inline; }
.vis2 { visibility:hidden; display:none; }
.ttl {width:100%;background:#B8E1BD;background-image:url("img/bg.gif");}
.m0 {margin:0px;}
.hint {font-size:9px;color:gray;}
//-->
</STYLE>
<SCRIPT language="JavaScript" TYPE="text/javascript">
<!--
function Calendar(el) {
wnd=window.open('calendar.php?el='+el,'calendar_'+el,'width=120,height=230,top='+(screen.height/2-115)+', left='+(screen.width/2-60)+', scrollbars=no,resizable=no,status=no,toolbar=no,menubar=no');
}
//-->
</SCRIPT>

</HEAD>
<BODY bgcolor="white" background="img/<?=$BBG;?>" class='m0'>

<table cellspacing=0 cellpadding=0 border=0 width=770><tr><td valign='top' width=170><img alt='' src=img/none.gif width=170 height=1><br>
<?
// MENU
if (isset($HTTP_GET_VARS["filter"])) $flt="&amp;filter=".urlencode($HTTP_GET_VARS["filter"]);

for ($i=0;$i<count($MENU_GROUPS);$i++) {
title($MENU_GROUPS[$i]);
print "<table cellspacing=5 cellpadding=0 border=0><tr><td>\n";
for ($j=0;$j<count($MENU);$j+=3) {
if ($MENU[$j]==$i) {
if ($st==$MENU[$j+1])
print "".$MENU[$j+2]."<br>\n";
else
print "<a href='index.php?st=".$MENU[$j+1]."&amp;stm=".$stm."&amp;ftm=".$ftm.$flt."'>".$MENU[$j+2]."</a><br>\n";
}
}
print "</td></tr></table>\n";
}

title("Nullified");
print "<table cellspacing=5 cellpadding=0 border=0><tr><td>";
print "Release Name: Cnstats 2.2<br>";
print "Released: WDYL-WTN Team";
print "</td></tr></table>";

print "</td><td valign=top width=477 align=center>";
print "<table cellspacing=5 cellpadding=0 border=0><tr><td valign=top>";

$rmn=false;
if ($HTTP_GET_VARS["dateoff"]!=1 && $st!="attendance" && $st!="filters" && $st!="dbsize" && $st!="config" && $st!="confmail" && $st!="getcode" && $st!="who_history") $rmn=true;

if ($rmn) {
print $TABLE."<tr class=tbl2><td style='color:gray;font-size:9px;'>";
if (isset($LANG["reports"][$st])) print $LANG["reports"][$st];
else for ($j=0;$j<count($MENU);$j+=3) if ($st==$MENU[$j+1]) print $MENU[$j+2]."\n";
if (!empty($HTTP_GET_VARS["filter"])) print "<br>".$LANG["filter"].": ".$HTTP_GET_VARS["filter"];
print "<br>".$LANG["report for period from"]." ".date($CONFIG["datetime_format"],strtotime($startdate))." ".$LANG["till"]." ".date($CONFIG["datetime_format"],strtotime($enddate));
print "</td></tr></table>";
print "<img alt='' src=img/none.gif width=1 height=3><br>";
}

$ADMENU="";$NOFILTER=0;
$diftime_start = getmicrotime();
include "reports/".$st.".php";
$diftime_end = getmicrotime();
$diftime = $diftime_end - $diftime_start;

if ($NOFILTER==0) FormFilter($filter);

mysql_close();
print "</td></tr></table>";

print "</td><td width=125 valign=top align=center>";

if (!empty($ADMENU)) {
title($LANG["right additional"]);
print "<table cellspacing=5 cellpadding=0 border=0 width='100%'><tr><td>";
print $ADMENU;
print "</td></tr></table>";
}

if ($rmn) {
$co=time()+$COUNTER["timeoffset"];

title($LANG["right bydates"]);
print "<form class='m0' action='index.php' method='get'><table cellspacing=0 cellpadding=0 border=0>\n";
print "<tr><td colspan=2>".$LANG["right startdate"].":</td></tr>\n";
print "<tr><td><input type=text value='".substr($startdate,0,-3)."' name=sd style='font-size:9px;width:100px;' id=sd></td><td><a href='javascript:Calendar(\"sd\");'><img src='img/calendar.gif' alt='Âûáðàòü äàòó' width=16 height=16 border=0></a></td></tr>\n";
print "<tr><td colspan=2>".$LANG["right enddate"].":</td></tr>\n";
print "<tr><td><input type=text value='".substr($enddate,0,-3)."' name=fd style='font-size:9px;width:100px;' id=fd></td><td><a href='javascript:Calendar(\"fd\");'><img src='img/calendar.gif' alt='Âûáðàòü äàòó' width=16 height=16 border=0></a></td></tr>\n";
print "<tr><td align=center colspan=2><img alt='' src=img/none.gif width=1 height=3><br><input type=submit value='".$LANG["right show"]."' style='font-size:9px;'></td></tr>\n";
print "</table>\n";
print "<input type=\"hidden\" name=\"st\" value='".$st."'>\n";

$fields=explode("&amp;",$DATELINK);
while (list ($key, $val) = each ($fields)) {
list ($vkey,$vval)=explode("=",$val);
if (!empty($vkey)) print "<input type=\"hidden\" name=\"".$vkey."\" value='".cnstats_mhtml(urldecode($vval))."'>\n";
}

print "</form>\n";
print "<img alt='' src=img/none.gif width=1 height=3><br>\n";

title($LANG["right bydays"]);
print "<table cellspacing=5 cellpadding=0 border=0 width='100%'><tr><td>";

print "<a href='index.php?st=".$st."&amp;stm=".(time()-300+$COUNTER["timeoffset"])."&amp;ftm=".(time()+$COUNTER["timeoffset"]).$DATELINK."'>".$LANG["last 5minutes"]."</a><br>\n";
print "<a href='index.php?st=".$st."&amp;stm=".(time()-3600+$COUNTER["timeoffset"])."&amp;ftm=".(time()+$COUNTER["timeoffset"]).$DATELINK."'>".$LANG["last hour"]."</a><br>\n";
print "<a href='index.php?st=".$st."&amp;stm=".(time()-86400+$COUNTER["timeoffset"])."&amp;ftm=".(time()+$COUNTER["timeoffset"]).$DATELINK."'>".$LANG["last day"]."</a><br>\n";
print "<img alt='' src=img/none.gif width=1 height=3><br>";
print "<a href='index.php?st=".$st."&amp;stm=".(mktime(0,0,0,date("m",$co),date("d",$co),date("Y",$co)))."&amp;ftm=".(mktime(23,59,59,date("m",$co),date("d",$co),date("Y",$co))).$DATELINK."'>".$LANG["today"]."</a><br>\n";
print "<a href='index.php?st=".$st."&amp;stm=".(mktime(0,0,0,date("m",$co),date("d",$co)-1,date("Y",$co)))."&amp;ftm=".(mktime(23,59,59,date("m",$co),date("d",$co)-1,date("Y",$co))).$DATELINK."'>".$LANG["yesterday"]."</a><br>\n";
print "<a href='index.php?st=".$st."&amp;stm=".(mktime(0,0,0,date("m",$co),date("d",$co)-7,date("Y",$co)))."&amp;ftm=".(mktime(23,59,59,date("m",$co),date("d",$co),date("Y",$co))).$DATELINK."'>".$LANG["7 days"]."</a><br>\n";
print "<a href='index.php?st=".$st."&amp;stm=".(mktime(0,0,0,date("m",$co),date("d",$co)-30,date("Y",$co)))."&amp;ftm=".(mktime(23,59,59,date("m",$co),date("d",$co),date("Y",$co))).$DATELINK."'>".$LANG["30 days"]."</a><br>\n";
print "</td></tr></table>";
}

if ($st!="getcode" && $st!="manual" && $st!="confmail" && $st!="config" && $st!="ipinfo"  && $st!="who_history") {

}

title($LANG["right time"]);
print "<table cellspacing=5 cellpadding=0 border=0 width='100%'><tr><td align='center'>";
printf ($LANG["report was generated"].":<br>%f ".$LANG["sec"]."<br>".$LANG["current time"].":<br>".date($CONFIG["datetime_format"],time()+$COUNTER["timeoffset"]),$diftime);
print "</td></tr></table>";

print "<img alt='' src=img/none.gif width=125 height=1><br>";

print "</td></tr></table>";
?>