<?php
if($logout=='y')
	{
        SetCookie("BHCIntranet",'');
	echo "You are logged out.<br><a href='", $PHP_SELF, "'>Log In</a>";
	} else {
		include("config.php");
		if($BHCIntranet)
			{
			$ipaddy = $BHCIntranet; $refok='yes';
			} else 
				{
				include("identity.php"); $login ='y';
				}
		if($refok == 'yes')
			{
			if($defaultcontent=='') { $defaultcontent = 'news.php'; }
			echo "<html><head><title>", $orgname, " Intranet</title></head>";
			dbconnect($dbusername,$dbuserpasswd);
			$result = mysql_query( "select menu_columns, menu_scroll, menumode from userinfo where ipaddress ='$ipaddy'");
			$row = mysql_fetch_row($result);
			if ($row[1] != "no") { $row[1] = "yes"; }
			if ($row[0] == 1) { $framewidth = "85"; } else { $framewidth = "150"; }
			if ($menumode =='list' or ($menumode=='user' and $row[2] =='list'))
				{ $framewidth='125'; }
			echo "<frameset cols='", $framewidth, ", *' border='0' framespacing=0 frameborder='no'>";
			echo "<frame name='menu' src='menu.php' scrolling=", $row[1], " marginheight=0 marginwidth=0>";
			echo "<frame name='content' src='", $defaultcontent, "' scrolling=yes marginheight=0 marginwidth=0></frameset>";
			} else {
				if($login != 'y') { echo "<font color='red'>Error.</font>"; }
				}
		}
?>
