<?php
session_start();
if(!$_SESSION['username']){ 
echo "Please login.";
exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Ice-Downloader</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10px;
	color: #000000;
}
a:link {
	color: #FF0000;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FF0000;
}
a:hover {
	text-decoration: underline;
	color: #FF6600;
}
a:active {
	text-decoration: none;
	color: #FF6600;
}
a {
	font-size: 10px;
}
-->
</style></head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Protected Area</title>
</head>

<body>
<table width="400" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr>
    <td><table width="400" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><div align="center"><img src="logo.jpg" height="130"></div></td>
      </tr>
      <tr>
        <td height="19"><table width="110" border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#000000">
          <tr>
            <td width="110" height="21"> <?php include('menu.php') ?>              <br>
</td>
          </tr>
        </table>
          <table width="290" height="25" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td width="290" height="21" bgcolor="#FFFFFF"><?php 
			$path =  __FILE__;
$path = preg_replace( "'\\\show\.php'", "", $path);
$path = preg_replace( "'/show\.php'", "", $path);
		include("$path/config.php");
$sQuery = "SELECT*FROM downloads ORDER BY id DESC";
			$sResult = MySQL_Query($sQuery);
			$Rows = MySQL_Num_Rows($sResult);
			
			$x = "1";
			while ($Output = MySQL_Fetch_Array($sResult)) {
			$Output = array_reverse($Output);
				($x%2) ? $bg = "#FFFFFF": $bg = "#C4C8D4";
				
					
echo "<table width=\"290\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td><table width=\"20%\" border=\"0\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\">
      <tr>
        <td width=\"20%\" height=\"21\"><center><a href=\"".$Output['picture']."\"><img src=\"".$Output['picture']."\" height=\"60\" width=\"60\" border=\"0\"></a><br> Click To Enlarge</center><br>
		
		Submitted by:   ".$Output['username']."
        </td>
      </tr>
    </table>
      <table width=\"50%\" height=\"25\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tr>
          <td width=\"50%\" height=\"21\" bgcolor=\"\"><h2>".$Output['name']."</h2>
		  ".$Output['description']."<br>
		  <a href=\"".$Output['download']."\">Download</a><br><br><br><br>
		  
		  </td>
        </tr>
      </table></td>
  </tr>
</table>
 <HR align=\"left\" width=\"100%\" SIZE=\"1\" style=\"COLOR: #181b16\">";
				$x++;
			}
			echo "</table>";
			echo "<br><br><center><br><br>Powered by <a href=\"www.ice-host.net\" target=\"_blank\">Ice-Downloader</a></center>";
?>                <br>
                <br>                </td>
            </tr>
          </table>
          </td>
      </tr>
      <tr>
        <td><div align="center"></div></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
