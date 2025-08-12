<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$errors=0;
$errmsg="";
if(!isset($poster) || !isset($pid))
{
	$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
	$errmsg.="$l_callingerror</td></tr>";
	$errors=1;
}
else
{
	$sql="select * from ".$tableprefix."_poster where entrynr='$poster' and pid='$pid'";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if(!$myrow=mysql_fetch_array($result))
	{
		$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
		$errmsg.="$l_nosuchrequest</td></tr>";
		$errors=1;
	}
}
if($errors==1)
{
	include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_proppw?></font></td></tr>
<?php
	echo $errmsg;
	echo "</table></td></tr></table>";
	include_once("./includes/footer.inc");
	exit;
}
$sql="update ".$tableprefix."_poster set pid=0, pwconfirmed=1 where entrynr='$poster'";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
$redirect=0;
include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_proppw?></font></td></tr>
<?php
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "$l_pwconfirmed</font></td></tr>";
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
?>