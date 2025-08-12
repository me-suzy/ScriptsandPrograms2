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

if ($_POST["do"]=="edit") {

	$title=mhtml(substr($_POST["title"],0,256));
	$url=mhtml(substr($_POST["url"],0,256));
	$email=mhtml(substr($_POST["email"],0,256));
	$description=mhtml(substr($_POST["description"],0,2048));
	$resfield1=mhtml(substr($_POST["resfield1"],0,2048));
	$resfield2=mhtml(substr($_POST["resfield2"],0,2048));
	$resfield3=mhtml(substr($_POST["resfield3"],0,2048));
	$ref=mhtml($_POST["ref"]);
	$c1=intval($_POST["c1"]);

	$error="";
	if (empty($email)) $error.="<LI>".$LANG["mustbeemail"];
	if (empty($url)) $error.="<LI>".$LANG["mustbeurl"];
	if (empty($title)) $error.="<LI>".$LANG["mustbetitle"];
	if (empty($description)) $error.="<LI>".$LANG["mustbedescription"];

	$lid=intval($_POST["lid"]);
	$gin=intval($_POST["gin"]);
	$gout=intval($_POST["gout"]);

	if (empty($error)) {
		mysql_query("UPDATE ".$db["prefix"]."main SET title='$title', description='$description', url='$url', cat1='$c1', email='$email', gin='$gin',gout='$gout',resfield1='$resfield1',resfield2='$resfield2',resfield3='$resfield3' WHERE lid='$lid';") or die(mysql_error());
		sync();
		
		if (!empty($_POST["ref"])) $ref=urldecode($_POST["ref"]); else $ref="../";
		print ("<HTML><HEAD>\n");
		print ("<META HTTP-EQUIV=refresh CONTENT='0;url=".$ref."'>\n");
		print ("</HEAD></HTML>\n");
		die();
		}
	$r=mysql_query("SELECT insert_date FROM ".$db["prefix"]."main WHERE lid='$lid'");
	$R=mysql_fetch_array($r);
	$R["title"]=$title;
	$R["description"]=$description;
	$R["url"]=$url;
	$R["cat1"]=$c1;
	$R["email"]=$email;
	$R["gin"]=$gin;
	$R["gout"]=$gout;
	$R["resfield1"]=$resfield1;
	$R["resfield2"]=$resfield2;
	$R["resfield3"]=$resfield3;
	}
else {
	$lid=intval($_GET["lid"]);
	$r=mysql_query("SELECT title,description,url,cat1,email,gin,gout,resfield1,resfield2,resfield3,insert_date FROM ".$db["prefix"]."main WHERE lid='$lid'");
	$R=mysql_fetch_array($r);
	}

include "_top.php";   	

if (!empty($error)) {
	print "<H4>".$LANG["errorsfound"]."</H4>\n";
	print "<font color=red><UL>\n".$error."\n</UL></font>\n";
	}

?>

<table cellspacing=1 cellpadding=10 border=0 width=600>
<form action=edit.php method=post>
<input type=hidden name='do' value='edit'>
<input type=hidden name='start' value='<?=$start;?>'>
<input type=hidden name='type' value='<?=$type;?>'>
<input type=hidden name='lid' value='<?=$lid;?>'>
<input type=hidden name='ref' value='<?=urlencode($_SERVER["HTTP_REFERER"]);?>'>
<td colspan=2><B><?=$LANG["edit"];?></B></td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>

<tr><td>
<?=$LANG["idanddate"];?>:
</td><td>
<B><?=$lid;?></B> <?=$R["insert_date"];?>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>

<tr><td>
<?=$LANG["category"];?>:
</td><td width=70%>
<select style='width:100%' name=c1>
<option value=0><?=$LANG["notselected"];?>
<?
$r=mysql_query("SELECT cid,name FROM ".$db["prefix"]."cat_linear ORDER by name;") or die(mysql_error());
while ($row = mysql_fetch_array($r)) {
	if ($row["name"]=="") $row["name"]=$LANG["rootcat"]; else $row["name"]="...".$row["name"];
	if ($row["cid"]==$R["cat1"]) $sel="selected"; else $sel="";
	echo "<OPTION $sel value='".$row["cid"]."'>".$row["name"]."\n";
	}
?>
</select>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>

<tr><td>
<?=$LANG["sitetitle"];?>:
</td class=t1><td>
<input  style='width:100%'type=text name=title value='<?=$R["title"];?>'>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>

<tr><td>
<?=$LANG["siteurl"];?>:
</td><td>
<input  style='width:100%'type=text name=url value='<?=$R["url"];?>'>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>

<tr><td>
<?=$LANG["sitedescription"];?>:
</td><td>
<textarea style='width:100%' name=description rows=6><?=$R["description"];?></textarea>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>

<tr><td>
<?=$LANG["email"];?>:
</td><td>
<input  style='width:100%'type=text name=email value='<?=$R["email"];?>'>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>

<?if (!empty($cat["resfield1"])) { ?>
<tr><td>
<?=$cat["resfield1"];?>
</td><td>
<input  style='width:100%'type=text name=resfield1 value='<?=$R["resfield1"];?>'>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>
<?}?>

<?if (!empty($cat["resfield2"])) { ?>
<tr><td>
<?=$cat["resfield2"];?>
</td><td>
<input  style='width:100%'type=text name=resfield2 value='<?=$R["resfield2"];?>'>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>
<?}?>

<?if (!empty($cat["resfield3"])) { ?>
<tr><td>
<?=$cat["resfield3"];?>
</td><td>
<input  style='width:100%'type=text name=resfield3 value='<?=$R["resfield3"];?>'>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>
<?}?>

<tr><td>
<?=$LANG["fromsite"];?>:
</td><td>
<input  style='width:100%'type=text name=gin value='<?=$R["gin"];?>'>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>

<tr><td>
<?=$LANG["tosite"];?>:
</td class=t1><td>
<input  style='width:100%'type=text name=gout value='<?=$R["gout"];?>'>
</td></tr>
<td colspan=2 background=../cat/dots.gif></td></tr>

<tr><td colspan=2 align=center>
<input type=submit value='<?=$LANG["change"];?>' class=submit>
<input type=reset class=submit>
<input type=button class=submit value='<?=$LANG["back"];?>' OnClick='document.location="<?=$_SERVER["HTTP_REFERER"];?>"'>
</td></tr>

</table>

<?
include "_bottom.php";
?>