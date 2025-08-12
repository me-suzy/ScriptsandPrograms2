<?
/******************************************************************************/
/*                         (c) CN-Software CNCat                              */
/*                                                                            */
/*  Do not change this file, if you want to easily upgrade                    */
/*  to newer versions of CNCat. To change appearance set up files: _top.php,  */
/* _bottom.php and config.php                                                 */
/*                                                                            */
/******************************************************************************/

/* PLUGIN: Multiple links confirmation; */
chdir("..");
$ADLINK="../";

include "auth.php";

if ($_SERVER["REQUEST_METHOD"]=="POST") {

	$ID=Array();
	while (list ($key, $val) = each ($_POST)) {
		if (ereg("I([0123456789]+)_url",$key,$r)) {
			if (!empty($val)) $ID[]=$r[0];
			}
	    }

	while (list ($key, $val) = each ($ID)) {
		$aff="I".$key."_";

		$ttitle=mhtml(substr($_POST[$aff."ttitle"],0,256));

		$url=$_POST[$aff."url"];
		if (substr($url,0,7)!="http://") $url="http://".$url;
		$url=mhtml(substr($url,0,256));

		$email=mhtml(substr($_POST[$aff."email"],0,256));
		$description=mhtml(substr($_POST[$aff."description"],0,2048));
		$resfiled1=mhtml(substr($_POST[$aff."resfiled1"],0,2048));
		$resfiled2=mhtml(substr($_POST[$aff."resfiled2"],0,2048));
		$resfiled3=mhtml(substr($_POST[$aff."resfiled3"],0,2048));
		$c1=intval($_POST[$aff."c1"]);
		$type=intval($_POST[$aff."where"]);
		$mvote=intval($_POST[$aff."mvote"]);

		$SQL="INSERT INTO ".$db["prefix"]."main SET insert_date=NOW(), title='$ttitle', description='$description', url='$url', cat1='$c1', gin=0, gout=0, moder_vote='$mvote', email='$email', type='$type', resfield1='$resfield1', resfield2='$resfield2', resfield3='$resfield3';";
		mysql_query($SQL) or die(mysql_error());
		}
	print ("<HTML><HEAD>\n");
	print ("<META HTTP-EQUIV=refresh CONTENT='0;url=multi_add.php'>\n");
	print ("</HEAD></HTML>\n");
	exit;
	}

include "_top.php";

if ($_GET["step"]==1) {
	$count=$_GET["count"];
	if ($count<1 || $count>100) $count=1;

	print "<form action=multi_add.php method=post><center>";
	for ($i=0;$i<$count;$i++) {

?>
<table cellspacing=1 cellpadding=10><form action=order_cy.php method=get>
<th colspan=2><?=$LANG["plugin_multiadd_add"];?><?=($i+1);?></th></tr>
<tr><td>
<img src=../cat/none.gif width=1 height=6><br>
<center><table border=0>
<tr><td valign=top>
<?=$LANG["category"];?>:
</td><td>
<select style='width:320px;' name=I<?=$i;?>_c1>
<option value=0><?=$LANG["notselected"];?>
<?
$r=mysql_query("SELECT cid,name FROM ".$db["prefix"]."cat_linear ORDER by name;") or die(mysql_error());
while ($row = mysql_fetch_array($r)) {
	if ($row["cid"]==$c1) $sel="selected"; else $sel="";
	echo "<OPTION $sel value='".$row["cid"]."'>".$row["name"]."\n";
	}
?>
</select>
</td></tr>

<tr><td valign=top>
<?=$LANG["sitetitle"];?>:&nbsp;&nbsp;
</td><td>
<input style='width:320px;' type=text name=I<?=$i;?>_ttitle value='<?=$ttitle;?>'>
</td></tr>

<tr><td valign=top>
<?=$LANG["siteurl"];?>:
</td><td>
<small style='color:red'><input style='width:320px;' type=text name=I<?=$i;?>_url value='<?=$url;?>'><br>
<?=$LANG["plugin_multiadd_attention"];?></small>
</td></tr>

<tr><td valign=top>
<?=$LANG["email"];?>:
</td><td>
<input style='width:320px;' type=text name=I<?=$i;?>_email value='<?=$email;?>'>
</td></tr>

<?if (!empty($cat["resfield1"])) {?>
<tr><td valign=top>
<?=$cat["resfield1"];?>:
</td><td>
<input style='width:320px;' type=text name=I<?=$i;?>_resfield1 value='<?=$resfield1;?>'>
</td></tr>
<?}?>

<?if (!empty($cat["resfield2"])) {?>
<tr><td valign=top>
<?=$cat["resfield2"];?>:
</td><td>
<input style='width:320px;' type=text name=I<?=$i;?>_resfield2 value='<?=$resfield2;?>'>
</td></tr>
<?}?>

<?if (!empty($cat["resfield3"])) {?>
<tr><td valign=top>
<?=$cat["resfield3"];?>:
</td><td>
<input style='width:320px;' type=text name=I<?=$i;?>_resfield3 value='<?=$resfield3;?>'>
</td></tr>
<?}?>

<tr><td valign=top colspan=2>
<?=$LANG["sitedescription"];?>:<br>

<textarea style='width:100%;' name=I<?=$i;?>_description rows=6><?=$description;?></textarea>
</td></tr>

<tr><td valign=top colspan=2 align=center>
<?
	print "<table><tr>";
	print "<td><input class=checkbox type=radio checked name=I".$i."_where value=0></td><td>".$LANG["new"]."</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_where value=1></td><td>".$LANG["submited"]."</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_where value=2></td><td>".$LANG["deleted"]."</td>";
	print "</tr></table><br>".$LANG["plugin_multiadd_modervote"].":<br>";

	print "<table><tr>";
	print "<td><input class=checkbox type=radio checked name=I".$i."_mvote value=0></td><td>0</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=1></td><td>1</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=2></td><td>2</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=3></td><td>3</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=4></td><td>4</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=5></td><td>5</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=6></td><td>6</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=7></td><td>7</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=8></td><td>8</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=9></td><td>9</td>";
	print "<td><input class=checkbox type=radio name=I".$i."_mvote value=10></td><td>10</td>";
	print "</tr></table>";
?>
<br>
</td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>
</table>

</td></tr></table>
<br>
<?
		}
	print "<input type=submit value='".$LANG["submit"]."' class=small>\n";
	print "</form></center>";
	}
else {
?>
<table cellspacing=1 cellpadding=10 border=0><form action=multi_add.php method=get>
<th colspan=2><?=$LANG["plugin_multiadd"];?></th></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>
<tr><td><?=$LANG["plugin_multiadd_count"];?></td><td><input type=text name=count value=5></td></tr>
<td colspan=2 background=../../cat/dots.gif></td></tr>
<tr><td colspan=2 align=right><input type=submit value='<?=$LANG["plugin_next"];?> &gt;&gt;'></td></tr>
<input type=hidden name='step' value='1'>
</form></table>
<?
	}
include "_bottom.php";
?>
