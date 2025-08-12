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
ini_set("session.use_trans_sid",0);
session_register("cncatsid");

require "config.php";
require "lang/".$LANGFILE;

$r=mysql_query("SELECT name,html FROM ".$db["prefix"]."templates;") or die(mysql_error());
while ($a=mysql_fetch_assoc($r)) $TMPL[$a["name"]]=$a["html"];

$pp=10;
$start=intval($_GET["start"]);
$o=intval($_GET["o"]);

$q=mhtml($_GET["q"]);
$title=htmlspecialchars($_GET["q"]);
include "_top.php";

function hl($str) {
	GLOBAL $lq;
	$str=" ".$str;

	$ph=explode(" ",$lq);
	$lstr=mysql_result(mysql_query("SELECT lower('$str')"),0,0);

	for ($i=0;$i<count($ph);$i++) {
		$lstr=eregi_replace($ph[$i],"<b><font color=red>".$ph[$i]."</font></b>",$lstr);
		}
	
	$p1=1;$p2=1;
	while ($p1!=0 && $p2!=0) {
		if (($p1=strpos($lstr,"<b><font color=red>"))!=0) {
			$lstr[$p1]="|";
			$str=substr($str,0,$p1)."<b><font color=red>".substr($str,$p1);

			if (($p2=strpos($lstr,"</font></b>"))!=0) {
				$lstr[$p2]="|";
				$str=substr($str,0,$p2)."</font></b>".substr($str,$p2);
				}
			}
		}

	$str=substr($str,1);
	return($str);
	}

$template=$TMPL["brokenscript"];
$template=str_replace("%YESTEXT",$LANG["yes"],$template);
$template=str_replace("%NOTEXT",$LANG["no"],$template);
$template=str_replace("%BROKENSURETEXT",$LANG["brokensure"],$template);
print $template;

$template=$TMPL["bmenu"];
$template=str_replace("%MODERATORSTEXT",$LANG["moderators"],$template);
$template=str_replace("%ADDLINKTEXT",$LANG["addlink"],$template);
$template=str_replace("%MAINTEXT",$LANG["main"],$template);
print $template;

$sform=$TMPL["searchform"];
$sform=str_replace("%SEARCHTEXT",$LANG["search"],$sform);
$sform=str_replace("%QUERYTEXT",$q,$sform);

print $sform;

$q=trim($q);
if (empty($q)) {
	print "<P><font color=red>".$LANG["emptyquery"]."</font></P>";
	$total=0;
	}
else {
	$q=trim($q);

	$order="ORDER BY gin DESC,gout DESC";
	if ($o==1) $order="ORDER BY title";
	if ($o==2) $order="ORDER BY moder_vote DESC";

	$lq=mysql_result(mysql_query("SELECT lower('$q');"),0,0);
	$uq=mysql_result(mysql_query("SELECT upper('$q');"),0,0);

	$is=0;
	/* ID Search */
	if (substr($q,0,3)=="id:") {
		$likes="lid=".intval(substr($uq,3));
		$is=1;
		}
	/* URL Search */
	if (substr($q,0,4)=="url:") {
		$tq=trim(substr($uq,4));
		if (substr($tq,0,7)!="http://") $tq="http://".$tq;

		$likes="UPPER(url) like '".$tq."%'";
		$is=1;
		}
	/*  */
	if (substr($q,0,6)=="&quot;" && substr($q,-6)=="&quot;") {
		$uq=trim(substr($uq,6,-6));
		$lq=trim(substr($lq,6,-6));
		$likes.=" OR UPPER(description) regexp '[[:<:]]".$uq."[[:>:]]'";
		$likes.=" OR UPPER(title) regexp '[[:<:]]".$uq."[[:>:]]'";
		$likes.=" OR UPPER(url) regexp '[[:<:]]".$uq."[[:>:]]'";
		$likes=substr($likes,4);
		$is=1;
		}
	/* Simle search */
	if ($is==0) {

		$likes="";
		$ph=explode(" ",$uq);
		for ($i=0;$i<count($ph);$i++) {
			$pq="%".$ph[$i]."%";
			$likes.=" OR UPPER(description) like '$pq'";
			$likes.=" OR UPPER(title) like '$pq'";
			$likes.=" OR UPPER(url) like '$pq'";
			}
		$likes=substr($likes,4);
		}
		
	$rr=mysql_query("SELECT count(*) FROM ".$db["prefix"]."main WHERE type=1 AND ($likes)") or die(mysql_error());
	$total=mysql_result($rr,0,0);
	$r=mysql_query("SELECT * FROM ".$db["prefix"]."main WHERE type=1 AND ($likes) $order LIMIT $start,10") or die(mysql_error());

	$c=mysql_num_rows($r);

	$template=$TMPL["searchtop"];
	$template=str_replace("%STARTNUM",$start+1,$template);
	print $template;

	while ($ar=mysql_fetch_array($r)) {
		$admin="";
		if ($_SESSION["cncatsid"]=="thisissomestring") {
			$admin.="<font color=gray size=-3> [<a href=admin/edit.php?lid=".$ar["lid"]."&type=255 class=slink>".$LANG["edit"]."</a>] ";
			$admin.="[<a href=admin/moveto.php?lid=".$ar["lid"]."&type=1&to=2 class=slink>".$LANG["delete"]."</a>] [";
			for ($j=1;$j<11;$j++) {
				if ($j==$ar["moder_vote"]) $admin.="<B>".$j."</B> ";
				else $admin.="<a href=admin/vote.php?lid=".$ar["lid"]."&vote=$j&type=255 class=slink>".$j."</a> ";
				}
			$admin.="]</font>";
			}

		$template=$TMPL["linksbit"];
		$template=str_replace("%ID",$ar["lid"],$template);
		$template=str_replace("%TITLE",hl($ar["title"]),$template);
		$template=str_replace("%GIN",$ar["gin"],$template);
		$template=str_replace("%GOUT",$ar["gout"],$template);
		$template=str_replace("%MODERVOTE",$ar["moder_vote"],$template);
		$template=str_replace("%DESC",hl($ar["description"]),$template);
		$template=str_replace("%URL",hl($ar["url"]),$template);
		$template=str_replace("%DURL",$ar["url"],$template);
		$template=str_replace("%BEST","<img src=./cat/star.gif width=15 height=16 hspace=3>",$template);
		$template=str_replace("%BROKENTEXT",$LANG["broken"],$template);
		$template=str_replace("%ADMINIFACE",$admin,$template);
		$template=str_replace("%RESFIELD1",$ar["resfield1"],$template);
		$template=str_replace("%RESFIELD2",$ar["resfield2"],$template);
		$template=str_replace("%RESFIELD3",$ar["resfield3"],$template);
		print $template;
		}

	$template=$TMPL["searchbottom"];
	}
print "</OL>";
if ($c==0) {
	print "<P>".$LANG["notfound"]."</P>\n";
	}

print "<table cellspacing=0 cellpadding=0 border=0 width=100% bgcolor=#E0E0E0><tr><td><img src=./cat/none.gif width=1 height=1></td></tr></table>";
print "<P>".$LANG["pagesfound"].": <B>".$total."</B>";

if ($total>$pp) {
	print "<center><br>";
	if ($start!=0) print "<a href=./search.php?o=$o&start=0&q=".urlencode($q).">&lt;&lt;</a> | ";
	else print "&lt;&lt; | ";
	$sstart=$start-60; if ($sstart<0) $sstart=0;
	$send=$start+60;if ($send>$total) $send=$total;
	for ($i=$sstart;$i<$send;$i+=$pp) {
		if ($start==$i) 
			print "<b>".($i+1)."</b>";
		else
			print "<a href=./search.php?o=$o&start=$i&q=".urlencode($q).">".($i+1)."</a>";
		print " | ";
		}
	if ($start==$total-$pp) 
		print "&gt;&gt;";
	else
		print "<a href=./search.php?o=$o&start=".($i-$pp)."&q=".urlencode($q).">&gt;&gt;</a>";
	print "</center>";
	}
print "<br>";

$template=$TMPL["bmenu"];
$template=str_replace("%MODERATORSTEXT",$LANG["moderators"],$template);
$template=str_replace("%ADDLINKTEXT",$LANG["addlink"],$template);
$template=str_replace("%MAINTEXT",$LANG["main"],$template);
print $template;
print $COPY;

include "_bottom.php";
?>
