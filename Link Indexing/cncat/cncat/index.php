<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/
error_reporting(E_ALL & ~E_NOTICE);
ini_set("session.use_trans_sid",false);
session_register("cncatsid");

if (is_file("install.php") && !is_file("config.php")) {
	header("Location: install.php");
	exit;
	}

if (version_compare(phpversion(), "4.2.0", ">=")) $ob=TRUE; else $ob=FALSE;

if ($ob) {ob_start();ob_implicit_flush(0);}
require "config.php";
require "lang/".$LANGFILE;
if ($ob) {ob_clean();ob_implicit_flush(1);}

if (is_file("install.php")) die($LANG["remove_install"]);

$o=$_GET["o"];
$c=intval($_GET["c"]);
$start=intval($_GET["start"]);

$defaultorder=intval($cat["defaultorder"]);
if ($defaultorder<0 || $defaultorder>3) $defaultorder=0;
if (($o<0 && $o>3) || !isset($o)) $o=$defaultorder;

$r=mysql_query("SELECT name,html FROM ".$db["prefix"]."templates;") or die(mysql_error());
while ($a=mysql_fetch_assoc($r)) $TMPL[$a["name"]]=$a["html"];

function ShowParts($cid) {
	GLOBAL $cat,$TMPL,$db;

	$r=mysql_query("SELECT name,cid,count FROM ".$db["prefix"]."cat WHERE parent='$cid' ORDER BY name") or die(mysql_error());
	$num=0;
	$cnt=mysql_num_rows($r);
	if ($cnt==0) return;
	print $TMPL["partstop"];
	for ($i=0;$i<$cnt;$i++) {
		$num++;
		if ($num==1) print $TMPL["partsdelimtop"];

		$template=$TMPL["partsbit"];
		$template=str_replace("%CTITLE",mysql_result($r,$i,0),$template);
		$template=str_replace("%CID",mysql_result($r,$i,1),$template);
		$template=str_replace("%CCOUNT",mysql_result($r,$i,2),$template);
		print $template;
		if ($num==$cat["rows"]) {print $TMPL["partsdelimbottom"];$num=0;}
		}
	print $TMPL["partsbottom"];
	}

function ShowCat($cid,$best=0) {
	GLOBAL $start,$pp,$o,$LANG,$TMPL,$db;

	if ($best==1) $bestsql="AND moder_vote=10"; else $bestsql="AND moder_vote!=10";

	if ($o==0) $order="ORDER BY gin DESC,gout DESC";
	if ($o==1) $order="ORDER BY title";
	if ($o==2) $order="ORDER BY moder_vote DESC, gout DESC";
	if ($o==3) $order="ORDER BY insert_date DESC";


	if ($cid!=0) $Q="SELECT lid,url,title,description,gin,gout,moder_vote,resfield1,resfield2,resfield3 FROM ".$db["prefix"]."main WHERE type=1 $bestsql AND cat1=$cid $order LIMIT $start,$pp;";
	else $Q="SELECT lid,url,title,description,gin,gout,moder_vote,resfield1,resfield2,resfield3 FROM ".$db["prefix"]."main WHERE type=1 $bestsql $order LIMIT $start,$pp;";
	$r=mysql_query($Q) or die(mysql_error());
	$cnt=mysql_num_rows($r);
	if ($cnt==0 && $best!=1) print $LANG["linksnotfound"];

	while ($ar=mysql_fetch_array($r)) {
		$admin="";
		if ($_SESSION["cncatsid"]=="thisissomestring") {
			$admin.="<font color=gray size=-3> [<a href=admin/edit.php?lid=".$ar["lid"]."&type=255 class=slink>".$LANG["edit"]."</a>]";
			$admin.="[<a href=admin/moveto.php?lid=".$ar["lid"]."&type=1&to=2 class=slink>".$LANG["delete"]."</a>] [";
			for ($j=1;$j<11;$j++) {
				if ($j==$ar["moder_vote"]) $admin.="<B>".$j."</B> ";
				else $admin.="<a href=admin/vote.php?lid=".$ar["lid"]."&vote=$j&type=255 class=slink>".$j."</a> ";
				}
			$admin.="]</font>";
			}

		$template=($best==1)?$TMPL["bestlinksbit"]:$TMPL["linksbit"];
		$template=str_replace("%ID",$ar["lid"],$template);
		$template=str_replace("%TITLE",$ar["title"],$template);
		$template=str_replace("%GIN",$ar["gin"],$template);
		$template=str_replace("%GOUT",$ar["gout"],$template);
		$template=str_replace("%MODERVOTE",$ar["moder_vote"],$template);
		$template=str_replace("%DESC",$ar["description"],$template);
		$template=str_replace("%URL",$ar["url"],$template);
		$template=str_replace("%BEST","<img src=./cat/star.gif width=15 height=16 hspace=3>",$template);
		$template=str_replace("%BROKENTEXT",$LANG["broken"],$template);
		$template=str_replace("%RESFIELD1",$ar["resfield1"],$template);
		$template=str_replace("%RESFIELD2",$ar["resfield2"],$template);
		$template=str_replace("%RESFIELD3",$ar["resfield3"],$template);
		$template=str_replace("%ADMINIFACE",$admin,$template);
		print $template;
		}
	if ($best!=1) {
		if ($cid!=0) return(mysql_result(mysql_query("SELECT count(*) FROM ".$db["prefix"]."main WHERE type=1 $bestsql AND cat1=$cid;"),0,0));
		else return(mysql_result(mysql_query("SELECT count(*) FROM ".$db["prefix"]."main WHERE type=1 $bestsql;"),0,0));
		}
	}

	$pp=10;
	$cid=$c;$l="";
	do {
		$r=mysql_query("SELECT parent,name,cid FROM ".$db["prefix"]."cat WHERE cid='$cid';") or die(mysql_error());
		if (mysql_num_rows($r)==1) {
			$id=mysql_result($r,0,2);
			$title=mysql_result($r,0,1);
			if ($cid==$c)
				$l=mysql_result($r,0,1).$l;
			else
				$l="<a class=bold href=./?c=".$id.">".$title."</a> &raquo; ".$l;
			$cid=mysql_result($r,0,0);
			}
		else $cid=0;
		} while ($cid!=0);
	$r=mysql_query("SELECT name FROM ".$db["prefix"]."cat WHERE cid='$c';") or die(mysql_error());
	if (mysql_num_rows($r)!=0) $title=mysql_result($r,0,0)." / ".$CATNAME;
	else $title=$CATNAME;

	include "_top.php";

	$template=$TMPL["bmenu"];
	$template=str_replace("%MODERATORSTEXT",$LANG["moderators"],$template);
	$template=str_replace("%ADDLINKTEXT",$LANG["addlink"],$template);
	$template=str_replace("%MAINTEXT",$LANG["main"],$template);
	print $template;

	$template=$TMPL["brokenscript"];
	$template=str_replace("%YESTEXT",$LANG["yes"],$template);
	$template=str_replace("%NOTEXT",$LANG["no"],$template);
	$template=str_replace("%BROKENSURETEXT",$LANG["brokensure"],$template);
	print $template;

	$sform=$TMPL["searchform"];
	$sform=str_replace("%SEARCHTEXT",$LANG["search"],$sform);
	$sform=str_replace("%QUERYTEXT",$q,$sform);
	print $sform;
	
	$template=$TMPL["catname"];
	$template=str_replace("%MAINTEXT",$CATNAME,$template);
	$template=str_replace("%OTHERTEXT",$l,$template);
	print $template;

	ShowParts($c);

	if ($o==0) $sortbypop="<B>".$LANG["popuarity"]."</B>"; else $sortbypop="<a href=./?o=0&c=$c>".$LANG["popuarity"]."</a>";
	if ($o==1) $sortbytitle="<B>".$LANG["title"]."</B>"; else $sortbytitle="<a href=./?o=1&c=$c>".$LANG["title"]."</a>";
	if ($o==2) $sortbymoder="<B>".$LANG["modervote"]."</B>"; else $sortbymoder="<a href=./?o=2&c=$c>".$LANG["modervote"]."</a>";
	if ($o==3) $sortbyin="<B>".$LANG["sortbyin"]."</B>"; else $sortbyin="<a href=./?o=3&c=$c>".$LANG["sortbyin"]."</a>";

	$template=$TMPL["sortby"];
	$template=str_replace("%SORTBYPOP",$sortbypop,$template);
	$template=str_replace("%SORTBYTITLE",$sortbytitle,$template);
	$template=str_replace("%SORTBYMODER",$sortbymoder,$template);
	$template=str_replace("%SORTBYIN",$sortbyin,$template);
	$template=str_replace("%SORTBYTEXT",$LANG["sortby"],$template);
	print $template;


	if (intval($c)==0 && $cat["shownew"]==1) {
		$newlinkstop=$TMPL["newlinkstop"];
		$newlinkstop=str_replace("%NEWLINKSTEXT",$LANG["newlinks"],$newlinkstop);
		print $newlinkstop;
		$r=mysql_query("SELECT lid,title,description,resfield1,resfield2,resfield3 FROM ".$db["prefix"]."main WHERE type=1 ORDER by lid DESC LIMIT ".$cat["shownewcount"].";");
		while ($ar=mysql_fetch_array($r)) {
			if (strlen($ar["description"])>75) $ar["description75"]=substr($ar["description"],0,75)."..."; else $ar["description75"]=$ar["description"];

			$template=$TMPL["newlinkstbit"];
			$template=str_replace("%ID",$ar["lid"],$template);
			$template=str_replace("%TITLE",$ar["title"],$template);
			$template=str_replace("%GIN",$ar["gin"],$template);
			$template=str_replace("%GOUT",$ar["gout"],$template);
			$template=str_replace("%MODERVOTE",$ar["moder_vote"],$template);
			$template=str_replace("%DESC75",$ar["description75"],$template);
			$template=str_replace("%DESC",$ar["description"],$template);
			$template=str_replace("%URL",$ar["url"],$template);
			$template=str_replace("%RESFIELD1",$ar["resfield1"],$template);
			$template=str_replace("%RESFIELD2",$ar["resfield2"],$template);
			$template=str_replace("%RESFIELD3",$ar["resfield3"],$template);
			print $template;
			}
		print $TMPL["newlinkstbottom"];
		}

	if ($cat["linksatmain"]==1 || $c!=0) {
		print $TMPL["linkstop"]; 
		ShowCat($c,1);
		print str_replace("%NUM",(1+$start),$TMPL["linksmiddle"]);
		$total=ShowCat($c);
		print $TMPL["linksbottom"];
		print "<P>".$LANG["linkcount"].": <B>$total</B></P>";
		}

if ($total>$pp) {
	print "<center>";
	if ($start!=0) print "<a href=./?c=$c&o=$o&start=0>&lt;&lt;</a> | ";
	else print "&lt;&lt; | ";
	$sstart=$start-60; if ($sstart<0) $sstart=0;
	$send=$start+60;if ($send>$total) $send=$total;
	for ($i=$sstart;$i<$send;$i+=$pp) {
		if ($start==$i) 
			print "<b>".($i+1)."</b>";
		else
			print "<a href=./?c=$c&o=$o&start=$i>".($i+1)."</a>";
		print " | ";
		}
	if ($start==$i-$pp) 
		print "&gt;&gt;";
	else
		print "<a href=./?c=$c&o=$o&start=".($total-$pp).">&gt;&gt;</a>";
	print "</center>";
	}


$template=$TMPL["bmenu"];
$template=str_replace("%MODERATORSTEXT",$LANG["moderators"],$template);
$template=str_replace("%ADDLINKTEXT",$LANG["addlink"],$template);
$template=str_replace("%MAINTEXT",$LANG["main"],$template);
print $template;
print $COPY;

include "_bottom.php";
?>