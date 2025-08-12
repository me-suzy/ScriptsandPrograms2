<?php
////////////////////////////////////////////////////////
// Conga Line Script v1.2.1
// ©2005 Nathan Bolender www.nathanbolender.com
// Licensed under the Creative Commons Attribution-NonCommercial-NoDerivs License
// located at http://creativecommons.org/licenses/by-nc-nd/2.0/
////////////////////////////////////////////////////////

include ("config.php");
?>
<?php include "config.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo "$name"; ?> Conga Line :: Add Link</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
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
	color: #FF0000;
}
a:active {
	text-decoration: none;
	color: #FF0000;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: small;
	color: #000000;
}
body {
	background-color: #FFFFFF;
}
-->
</style></head>

<body>
<table width="99%"  border="3" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td bgcolor="#999999"><div align="center"><strong><?php echo "$name"; ?> Conga Line :: Add Link</strong></div></td>
  </tr>
  <tr>
    <td height="107" align="center" valign="middle" bgcolor="#CCCCCC"><p>
	<b>Add link</b></p>
	  
	
	  
	<form action="addlink2.php" method="post" name="addlink" id="addlink">
	    <table width="28%"  border="3" cellpadding="0" cellspacing="0" bordercolor="#000000">
          <tr>
            <td width="46%"><strong>Name</strong></td>
            <td width="54%"><input name="nname" type="text" id="nname"></td>
          </tr>
          <tr>
            <td><strong>Password</strong></td>
            <td><input name="npass1" type="password" id="npass1"></td>
          </tr>
          <tr>
            <td><strong>Password Confirm </strong></td>
            <td>
              <input name="npass2" type="password" id="npass2">
            </td>
          </tr>
          <tr>
            <td><strong>Link</strong></td>
            <td><input name="nlink" type="text" id="nlink" value="http://"></td>
          </tr>
      </table>
	    <p>
	      <input type="submit" name="Submit" value="Submit">
	    </p>
	</form>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td bgcolor="#999999"><div align="center">Conga Line Script - &copy;2005 Nathan Bolender - <a href="http://www.nathanbolender.com">nathanbolender.com</a></div></td>
  </tr>
</table>

</body>
</html>