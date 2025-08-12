<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/

/* PLUGIN: Import from PHP-Nuke; */
chdir("..");
$ADLINK="../";

include "auth.php";

$step=intval($_GET["step"]);
if (isset($_POST["step"])) $step=intval($_POST["step"]);

if ($step==0) {
	include "_top.php";
?>
<table cellspacing=1 cellpadding=6 border=0><form action=import_phpnuke.php method=post>
<th colspan=2><?=$LANG["plugin_phpnuke"];?></th></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td>Host:</td><td><input type=text name=sqlhost value='localhost'></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td>User:</td><td><input type=text name=sqllogin value='root'></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td>Password:</td><td><input type=password name=sqlpassword value=''></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td>PHP Nuke database:</td><td><input type=text name=sqlbase value='nuke'></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>

<tr><td colspan=2><input type=submit value='<?=$LANG["plugin_next"];?> &gt;&gt;'></td></tr>
<input type=hidden name=step value=1>
</form></table>
<?
	include "_bottom.php";
	}
// Importing categories ########################################################
if ($step==1) {

	$CID=Array();
	$SID=Array();

	include "_top.php";
	print "<P><B>".$LANG["plugin_phpnuke_cat"]."</B><br><br>";

	$sqllogin=$_POST["sqllogin"];
	$sqlpassword=$_POST["sqlpassword"];
	$sqlhost=$_POST["sqlhost"];
	$sqlbase=$_POST["sqlbase"];

	$nuke=mysql_connect($sqlhost,$sqluser,$sqlpassword);
	mysql_select_db($sqlbase,$nuke);
	$cncat=mysql_connect($db["host"],$db["user"],$db["password"]);
	mysql_select_db($db["name"],$cncat);


	$r=mysql_query("select cid,title from links_categories;",$nuke) or die(mysql_error());
	while ($a=mysql_fetch_array($r)) {
		$name=$a["title"];
		$oldcid=$a["cid"];
		$parent=0;

		mysql_query("INSERT INTO ".$db["prefix"]."cat SET name='$name',parent='$parent'",$cncat) or die(mysql_error());
		$cid=mysql_result(mysql_query("SELECT LAST_INSERT_ID();",$cncat),0,0);
		mysql_query("INSERT INTO ".$db["prefix"]."cat_linear SET name='".GetParentName($cid)."',cid='$cid';",$cncat) or die(mysql_error());

		$CID[$oldcid]=$cid;

		print $name."<br>";
		flush();
		}

	print "<P><B>".$LANG["plugin_phpnuke_subcat"]."</B><br><br>";


	$r=mysql_query("select sid,cid,title from links_subcategories;",$nuke) or die(mysql_error());
	while ($a=mysql_fetch_array($r)) {
		$name=$a["title"];
		$oldcid=$a["cid"];
		$oldsid=$a["sid"];
		$parent=$CID[$oldcid];

		mysql_query("INSERT INTO ".$db["prefix"]."cat SET name='$name',parent='$parent'",$cncat) or die(mysql_error());
		$cid=mysql_result(mysql_query("SELECT LAST_INSERT_ID();",$cncat),0,0);
		mysql_query("INSERT INTO ".$db["prefix"]."cat_linear SET name='".GetParentName($cid)."',cid='$cid';",$cncat) or die(mysql_error());

		$SID[$oldsid]=$cid;

		print $name."<br>";
		flush();
		}

	print "<P><B>".$LANG["plugin_phpnuke_links"]."</B><br><br>";

	$r=mysql_query("select lid,cid,sid,title,url,description,hits from links_links;",$nuke) or die(mysql_error());
	while ($a=mysql_fetch_array($r)) {
		$cid=$a["cid"];
		$sid=$a["sid"];
		$title=mhtml($a["title"]);
		$url=$a["url"];
		$description=mhtml($a["description"]);
		$hits=intval($a["hits"]);

		if ($sid!=0) $cat=$SID[$sid];
		else $cat=$CID[$cid];

		mysql_query("INSERT INTO ".$db["prefix"]."main SET title='$title', description='$description', url='$url', cat1='$cat', cat2='0', cat3='0', gin=0, gout=$hits, moder_vote=0, email='-', type=1;",$cncat) or die(mysql_error());
		print $url."<br>";
		flush();
		}

	print "<P><B>".$LANG["plugin_phpnuke_sync"]."</B><br><br>";
	sync();
	sync_names();
	print "<P><B>".$LANG["plugin_phpnuke_finished"]."</B><br><br>";
	include "_bottom.php";
	}
?>
