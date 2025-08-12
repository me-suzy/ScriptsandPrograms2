<html>
<head>
<title>Sgal 2 Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
a  { color: #ff9900; text-decoration: none }
a:visited  { color: #ff9900; text-decoration: none }
a:hover  { color: #ff9900; text-decoration: none }
a:active  { color: #ff9900; text-decoration: none }
</style>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table id="Table_01" width="800" height="600" border="0" cellpadding="0" cellspacing="0" align="center">
        <tr>
                <td rowspan="2">
                        <img src="images/template_01.gif" width="458" height="136" alt=""></td>
                <td colspan="3">
                        <img src="images/template_02.gif" width="342" height="102" alt=""></td>
        </tr>
        <tr height="34">
                <td height="34">
                        <img src="images/template_03.gif" width="22" height="34" alt=""></td>
                <td rowspan="2" align="center" width="278" background="images/template_04.gif"><a href="admin.php">Main</a>  <a href="admin.php?page=add">Add</a> <a href="admin.php?page=options">Options</a></b></td>
                <td height="34">
                        <img src="images/template_05.gif" width="42" height="34" alt=""></td>
        </tr>
        <tr>
                <td colspan="2">
                        <img src="images/template_06.gif" width="480" height="16" alt=""></td>
                <td>
                        <img src="images/template_07.gif" width="42" height="16" alt=""></td>
        </tr>
        <tr>
                <td colspan="4" valign="top" width="800">
                        <div width="800" style="overflow:auto;height:410px">
<b>Sgal Install:</b><br>
<?php
/*
--------------------------------------------------------------
|Sgal 2.0                                                    |
|(c)Adrian Wisernig 2005                                     |
|For help or more scripts go to:                             |
|http://www.statc.net                                        |
--------------------------------------------------------------
*/
if(!isset($_POST['install']))
{
echo'
<form action="" method="POST"><center>
<font color="#ff9900">Username for upload:<br>
<input type="text" size="15" name="username"><br>
Password:<br>
<input type="text" size="15" name="password"><br>
Display Style:<br>
<SELECT NAME="style" SIZE="2"><OPTION VALUE="t">Slide Show</OPTION><OPTION VALUE="g">Gallery Page</OPTION></select><br></font>
<br><input type="submit" name="install" value="Install">
</center>
</form>';
}
if(isset($_POST['install'])){
if(is_file("config.php")){ unlink("config.php");}
switch ($_POST['style']) {
                             case "t":
                             $intdis=1;
                             break;
                             case "g":
                             $intdis=0;
                             break;
                             }
        $config = '<?php $maximages=4;$validuser= "' . $_POST['username'] . '"; $validpass= "' . $_POST['password'] . '";$intdis= ' . $intdis . ';?>';
$handle=fopen("config.php","a+");
$write=fwrite($handle,$config);
fclose($handle);
echo'Sgal was installed.';}



?>
</div>
                        </td>
        </tr>
        <tr>
                <td colspan="4">
                        <img src="images/template_09.gif" width="800" height="16" alt=""></td>
        </tr>
        <tr>
                <td colspan="4">
                        <img src="images/template_10.gif" width="800" height="20" alt=""></td>
        </tr>
</table>
</body>
</html>
