<?php
include 'config.php';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<style type="text/css">
<!--
select, option, textarea, input {
border-right : 1px solid #808080;
border-top : 1px solid #808080;
border-bottom : 1px solid #808080;
border-left : 1px solid #808080;
color : #000000;
font-size : 11px;
font-family : Verdana, Arial, Helvetica, sans-serif;
background-color : #ffffff;
}
body, td, tr {
text-decoration : none;
font-family : Verdana, Arial, Helvetica, sans-serif;
font-size : 8pt;
cursor : default;

}
-->
</style>

<title>N-13 News (1.2) - Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
</head>
<body bgcolor="#FFFFFF">
<div align="center">
<table id="Table_01" width="730" height="33" border="0" cellpadding="0" cellspacing="0">
        <tr>
                <td style="text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; cursor: default" height="1">
                        <img src="images/index_01.gif" width="730" height="33" alt=""></td>
        </tr>
        <tr>
                <td background="images/index_02.gif" height="40">
</td>
        </tr>

</table>
<table class="collapsible" border="0" cellpadding="5" cellspacing="0" width="730" background="images/index_03.gif">
  <tr>
    <td width="100%">
<?php

if($_POST['B1'] == ""){

echo "<form method=\"POST\" action=\"install.php\">\n";
echo "\n";
echo "<div align=\"right\">\n";
echo "    <table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
echo "      <tr>\n";
echo "        <td width=\"50%\" valign=\"bottom\">\n";
echo "Connecting to MySQL Database<br>\n";
echo "        </td>\n";
echo "        <td width=\"50%\" valign=\"bottom\">\n";
echo "OK</td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "        <td width=\"50%\">\n";
echo "</td>\n";
echo "        <td width=\"50%\">\n";
echo "</td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "        <td width=\"50%\" colspan=\"2\">\n";
echo "<p align=\"center\">&nbsp;</td>\n";
echo "        <td width=\"50%\">\n";
echo "</td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "        <td width=\"50%\">\n";
echo "Admin username:</td>\n";
echo "        <td width=\"50%\">\n";
echo "<input type=\"text\" name=\"T1\" size=\"20\"></td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "        <td width=\"50%\">\n";
echo "Admin password:</td>\n";
echo "        <td width=\"50%\">\n";
echo "<input type=\"text\" name=\"T2\" size=\"20\"></td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "        <td width=\"50%\">\n";
echo "Admin email:</td>\n";
echo "        <td width=\"50%\">\n";
echo "<input type=\"text\" name=\"T3\" size=\"20\"></td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "        <td width=\"50%\">\n";
echo "</td>\n";
echo "        <td width=\"50%\">\n";
echo "</td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "        <td width=\"50%\">\n";
echo "</td>\n";
echo "        <td width=\"50%\">\n";
echo "&nbsp;</td>\n";
echo "      </tr>\n";
echo "      <tr>\n";
echo "        <td width=\"50%\">\n";
echo "&nbsp;</td>\n";
echo "        <td width=\"50%\">\n";
echo "<input type=\"submit\" value=\"Install...\" name=\"B1\"></td>\n";
echo "      </tr>\n";
echo "    </table>\n";
echo "</div>\n";
echo "</form>\n";

        }else{
$name = $_POST['T1'];
$pass = md5($_POST['T2']);
$email = $_POST['T3'];
$date = date("l jS Y");

echo "Creating admin table...";


echo "Inserting Admin account database...";

$sql = "INSERT INTO `$newsadmin` VALUES ('$name', '$pass', '$email', '', '1')";
if(mysql_query($sql)){
echo "<font color=\"005500\"><b> Done</b></font>\n";
} else {
echo "<font color=\"FF0000\"> Error, Data has not been inserted.</font>";
}
echo "<br><br><br>";
echo "If your account has succesfully been inserted into the database you can then login <a href=\"admin.php\">here</a>";
}
?>

    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="730">
  <tr>
    <td width="100%">
                        <img src="images/index_04.gif" width="730" height="19" alt=""></td>
  </tr>
</table>
</div>
</body>
</html>