<?
// GENERIC HEADER FOR ALL APPLICATIONS
	dbconnect($dbusername,$dbuserpasswd);
	$xresult = mysql_query( "select * from userinfo where ipaddress ='$ipaddy'");
	if(mysql_num_rows($xresult) == 1)
		{
		$setting = mysql_fetch_array($xresult);
		} else {
			$setting[login] = "Visitor";
                        $setting[heading_bgcolor] = '0000ff';
			$setting[heading_fontcolor] = 'ffffff';
			$setting[default_bgcolor] = 'ffffff';
			$setting[default_fontcolor] = '000000';
			$setting[heading_fontface] = 'Verdana';
			$setting[default_fontface] = 'Arial';
			$setting[default_fontsize] = '3';
			$setting[heading_fontsize] = '4';
			$visitor='yes';
			}
	$currentuser = $setting[login];
echo "<basefont size='", $setting[default_fontsize], "' face='", $setting[default_fontface], "'></head>";
if($action != 'pf' and $action !='printerfriendly')
	{
	echo "<body ";
	if ($setting[bgwallpaper] != '' and $setting[bgwallpaper] != 'none')
		{
		if($appheaderstring == 'Calendar')
			{
			echo "background='../wallpaper/", $setting[bgwallpaper], "' ";
			} else
				{
		 	       echo "background='wallpaper/", $setting[bgwallpaper], "' ";
				}
		}
	if($setting[default_bgcolor] == 'xxxxxx') { $setting[default_bgcolor] = 'ffffff'; }
	if($setting[default_fontcolor] == '000000') { $setting[default_fontcolor] = '000000'; }
	if($setting[heading_fontcolor] == '000000') { $setting[heading_fontcolor] = '000000'; }
	echo "bgcolor='#", $setting[default_bgcolor], "' text='#", $setting[default_fontcolor], "' link='#", $setting[default_fontcolor], "' vlink='#", $setting[default_fontcolor], "'>";
	}
if (($useheader == 'yes' or ($useheader=='user' and $setting[useheader] == 'y')) and $action != 'pf' and $action != 'printerfriendly')
	{
	echo "<table ";

	echo "width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td ";
	if ($setting[headingwallpaper] != '' and $setting[headingwallpaper] != 'none')
		{
                if($appheaderstring == 'Calendar')
			{
			echo "background='../wallpaper/", $setting[headingwallpaper], "' ";
			} else {
				echo "background='wallpaper/", $setting[headingwallpaper], "' ";
				}
		}
	echo "align='left' valign='top'";
	if($setting[heading_bgcolor] != 'xxxxxx') { echo " bgcolor='", $setting[heading_bgcolor], "'"; }
	echo">";
	echo "<font size='", $setting[heading_fontsize], "' face='", $setting[heading_fontface], "' color='", $setting[heading_fontcolor], "'><b>&nbsp; ", $headerstr, " ", $appheaderstring, "</b></font></td></tr></form></table>";
	}
?>
