<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
		$errors=0;
		$errmsg="";
		if(!isset($email) || !$email)
		{
			$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.= $l_missingemail."</td></tr>";
			$errors=1;
		}
		if(!isset($id) || !$id)
		{
			$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.= $l_missingid."</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql = "select * from ".$tableprefix."_subscriptions where email='$email' and unsubscribeid='$id'";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(!$myrow=mysql_fetch_array($result))
			{
				$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.= $l_noremoveentry."</td></tr>";
				$errors=1;
			}
		}
		if($errors==1)
		{
			include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_subscriptionprelude?></font></td></tr>
<?php
			echo $errmsg;
			echo "</table></td></tr></table>";
			include_once("./includes/footer.inc");
			exit;
		}
		include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_subscriptionprelude?></font></td></tr>
<?php
		$removeprelude = str_replace("{email}",$email,$l_subscriptionremoveprelude);
?>
<TR BGCOLOR="<?php echo $contentbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $removeprelude?></font></td></tr>
<form name="inputform" method="post" action="<?php echo $act_script_url?>">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="email" value="<?php echo $email?>">
<input type="hidden" name="id" value="<?php echo $id?>">
<input type="hidden" name="mode" value="delete">
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" value="<?php echo $l_yes?>"></td></tr></form>
<?php
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
?>