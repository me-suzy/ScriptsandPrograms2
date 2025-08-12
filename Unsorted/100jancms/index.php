<?php
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/

//configuration file
include 'config_inc.php'; 

?>
<html>
<head>
<title>100janCMS Articles Control</title>
<?php echo "$text_encoding"; ?>
<link REL = "SHORTCUT ICON" href="images/app/icon.ico">
<link href="cms_style.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" scroll="auto" onload="document.login.username.focus()" class="maintext">

<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" class="maintext">
  <tr>
    <td height="20" align="center" valign="top"> <p><br>
        <br>
        <img src="images/app/logo_login.jpg" width="128" height="44">        <br>
        <span class="maintext"><br>
        <br>
        </span> 
      <form action="login.php" method="POST" name="login" id="login" >
        <table width="342" height="118" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="335" align="center" valign="middle" bgcolor="#F0F0F0"><br>
              <table width="213" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="90" class="maintext"><strong>Username:</strong></td>
                  <td width="123" align="right"> <input name="username" type="text" class="formfields" id="username" size="20" maxlength="255"></td>
                </tr>
                <tr> 
                  <td class="maintext"><strong>Password:</strong></td>
                  <td align="right"> <input name="password" type="password" class="formfields" id="password" size="20" maxlength="255"></td>
                </tr>
                <tr> 
                  <td height="30" class="maintext">&nbsp;</td>
                  <td height="30" align="right" valign="bottom"> 
                    <input type="submit" name="submit" value="Login &gt;" style="width: 70px; height: 25px;" class="formfields2"> 
                  </td>
                </tr>
              </table> </td>
          </tr>
        </table>
      </form>

      
      <br>
      <span class="maintext">Copyright &copy; 2004 100jan Design Studio.</span><span class="maintext"> 
      All Rights Reserved.<br>
      Nullified by GTT '2004</span>
	  
	<br>
	<br>
	<br>
	  
	  </td>
  </tr>
</table>

</body>
</html>
