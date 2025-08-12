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
$ADLINK="";

include "auth.php";
$do=$_GET["do"];

if ($do=="add") {
	$name=mhtml($_GET["name"]);
	$parent=intval($_GET["parent"]);
	mysql_query("INSERT INTO ".$db["prefix"]."cat SET name='$name',parent='$parent'") or die(mysql_error());
	$cid=mysql_result(mysql_query("SELECT LAST_INSERT_ID();"),0,0);
	mysql_query("INSERT INTO ".$db["prefix"]."cat_linear SET name='".GetParentName($cid)."',cid='$cid';") or die(mysql_error());
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=cat.php'>\n");
	print ("</HEAD></HTML>\n");
	die();
	}

if ($do=="del") {
	$cid=intval($_GET["cid"]);
	$r=mysql_query("SELECT count(*) FROM ".$db["prefix"]."cat WHERE parent='$cid';") or die(mysql_error());
	if (mysql_result($r,0,0)!=0) die("<P>Can't delete not empty category.</P><a href=cat.php>Back</a>");
	mysql_query("DELETE FROM ".$db["prefix"]."cat WHERE cid='$cid';") or die(mysql_error());
	mysql_query("DELETE FROM ".$db["prefix"]."cat_linear WHERE cid='$cid';") or die(mysql_error());
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=cat.php'>\n");
	print ("</HEAD></HTML>\n");
	die();
	}

if ($do=="ren") {
	$name=mhtml($_GET["name"]);
	$cid=intval($_GET["cid"]);
	mysql_query("UPDATE ".$db["prefix"]."cat SET name='$name' WHERE cid='$cid'") or die(mysql_error());
	mysql_query("UPDATE ".$db["prefix"]."cat_linear SET name='".GetParentName($cid)."' WHERE cid='$cid';") or die(mysql_error());
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=cat.php'>\n");
	print ("</HEAD></HTML>\n");
	die();
	}

include "_top.php";

print "<h1>".$LANG["editcats"]."</h1>";
?>
<table border=0 cellspacing=1 cellpadding=4 width=600>
<form action=cat.php>
<input type=hidden name='do' value='add'>
<tr><th colspan=2><?=$LANG["createcategory"];?></th></tr>
<tr><td colspan=2 background=../cat/dots.gif></td></tr>
<tr><td>                     
<?=$LANG["createcategoryinsubcat"];?>:
</td><td width=400>
<select name=parent style='width:100%'>
<option value=0><?=$LANG["rootcat"];?>
<?
$r=mysql_query("SELECT cid,name FROM ".$db["prefix"]."cat_linear ORDER by name;") or die(mysql_error());
while ($row = mysql_fetch_array($r)) {
	echo "<OPTION value='".$row["cid"]."'>".$row["name"]."\n";
	}
?>
</select>
</td></tr>
<tr><td colspan=2 background=../cat/dots.gif></td></tr>
<tr><td>
<?=$LANG["categoryname"];?>:
</td><td>
<input type=text name=name style='width:100%'>
</td></tr>
<tr><td colspan=2 background=../cat/dots.gif></td></tr>
<tr><td colspan=2 align=right>
<input type=submit value='<?=$LANG["add"];?>'>
</td></tr>
</form></table>

<br>
<hr size=1>
<br>

<table border=0 cellspacing=1 cellpadding=4 width=600>
<form action=cat.php>
<input type=hidden name='do' value='del'>
<tr><th colspan=2><?=$LANG["deletecategory"];?></th></tr>
<tr><td colspan=2 background=../cat/dots.gif></td></tr>
<tr><td>
<?=$LANG["deletecategory"];?>:
</td><td width=400>
<select name=cid style='width:100%'>
<?
$r=mysql_query("SELECT cid,name FROM ".$db["prefix"]."cat_linear ORDER by name;") or die(mysql_error());
while ($row = mysql_fetch_array($r)) {
	echo "<OPTION value='".$row["cid"]."'>".$row["name"]."\n";
	}
?>
</select>
</td></tr>
<tr><td colspan=2 background=../cat/dots.gif></td></tr>
<tr><td colspan=2 align=right>
<input type=submit value='<?=$LANG["delete"];?>'>
</td></tr>
</form></table>

<br><hr size=1><br>

<table border=0 cellspacing=1 cellpadding=4 width=600>
<tr><th colspan=2><?=$LANG["renamecategory"];?></th></tr>
<tr><td colspan=2 background=../cat/dots.gif></td></tr>
<form action=cat.php>
<input type=hidden name='do' value='ren'>
<tr><td>
<?=$LANG["renamecategory"];?>:
</td><td width=400>
<select name=cid style='width:100%'>
<?
$r=mysql_query("SELECT cid,name FROM ".$db["prefix"]."cat_linear ORDER by name;") or die(mysql_error());
while ($row = mysql_fetch_array($r)) {
	echo "<OPTION value='".$row["cid"]."'>".$row["name"]."\n";
	}
?>
</select>
</td></tr>
<tr><td colspan=2 background=../cat/dots.gif></td></tr>
<tr><td>
<?=$LANG["categoryname"];?>:
</td><td>
<input type=text name=name style='width:100%'>
</td></tr>
<tr><td colspan=2 background=../cat/dots.gif></td></tr>
<tr><td colspan=2 align=right>
<input type=submit value='<?=$LANG["rename"];?>'>
</td></tr>
</form></table>
<br><br>
<?
include "_bottom.php";
?>