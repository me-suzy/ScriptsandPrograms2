<html>
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.login {
	font: bold 13px "Arial", "Helvetica", "sans-serif";
	color: #333333;
	text-decoration: none;
	background: #eeeeee;
	border-top: 1px dashed #666666;
	border-right: 1px none #666666;
	border-bottom: 1px dashed #666666;
	border-left: 1px none #666666;
}
-->
</style>
</head>

<body topmargin="30" leftmargin="250">
<form action="DoLogin.php" method="post">
<table width="500" border="0" cellspacing="0" cellpadding="5" class="login">
  <tr> 
      <td width="152">UserName</td>
      <td width="320">
        <input name="userName" type="text" id="userName"></td>
  </tr>
  <tr> 
    <td>PassWord:</td>
    <td><input name="passWord" type="text" id="passWord"></td>
  </tr>
<tr> 
      <td>Verify Image:<br>
		<img src="../inc/image.php"></td>  <td valign="top">
        <input name="verifyImage" type="text">
      </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td><input type="submit" name="Submit" value="Submit"></td>
  </tr>
</table>
</form>
</body>
</html>
