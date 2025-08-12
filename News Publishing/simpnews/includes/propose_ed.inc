<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
include_once('./includes/sesshandling.inc');
if(!is_loggedin())
{
	include_once("./includes/head.inc");
	$link="$act_script_url?$langvar=$lang&layout=$layout&mode=edlst";
	if(isset($backurl))
		$link.="&backurl=".urlencode($backurl);
?>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo "$l_loginfirst"?></font></td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<a href="<?php echo $link?>" class="actionlink"><?php echo $l_login?></a></font>
</td></tr>
<?php
}
else
{
	if(isset($newsnr))
	{
		if(bittst($proposepermissions,BIT_3))
		{
			$targeturl="$act_script_url?$langvar=$act_lang&layout=$layout&newsnr='$newsnr'&mode=directed&submode=news";
			if(isset($backurl))
				$targeturl.="&backurl=".urlencode($backurl);
			if(isset($actionurl))
				$targeturl.="&actionurl=".urlencode($actionurl);
		}
		else
		{
			$targeturl="$act_script_url?$langvar=$act_lang&layout=$layout&refnewsnr='$newsnr'&mode=refed&submode=news";
			if(isset($backurl))
				$targeturl.="&backurl=".urlencode($backurl);
			if(isset($actionurl))
				$targeturl.="&actionurl=".urlencode($actionurl);
		}
		echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$targeturl\">";
		exit;
	}
	else if(isset($propnr))
	{
		$targeturl="$act_script_url?$langvar=$act_lang&layout=$layout&propnr=$propnr&mode=directed&submode=proposals";
		if(isset($backurl))
			$targeturl.="&backurl=".urlencode($backurl);
		if(isset($actionurl))
			$targeturl.="&actionurl=".urlencode($actionurl);
		echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$targeturl\">";
		exit;
	}
	else
		die("<tr class=\"errorrow\"><td>".$l_callingerror);
}
?>