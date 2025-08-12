<html>
<head>
<title>100janCMS Articles Control: Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="cms_style.css" rel="stylesheet" type="text/css">
<link REL = "SHORTCUT ICON" href="images/app/icon.ico">

<script language="JavaScript" type="text/JavaScript">
function step2_go()
{
if (!agree.checked) 
	{alert("You must agree to this EULA in order to use the product!")}
else 
	{this.location="install_2.php";}
}
</script>

</head>

<body leftmargin="20" rightmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="maintext" scroll="auto">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0" class="mainsectiopntable" >
        
  <tr> 
          
    <td class="titletext0"><span class="maintext"><br>
      <img src="images/app/logo_login.jpg" width="128" height="44"><br>
      <br>
            </span>
 Installation:<span class="titletext0blue"> Step 1</span></td>
  </tr>
      
</table>
<br>
<span class="titletext0blue">Before install:</span> <br>
<br>
&#8226; Make sure the target database exists.<br>
&#8226; Make sure the database user has rights to create database tables and write 
to them.<br>
&#8226; Make sure the following files and folders are writable (CHMOD 666) by application:<br>
<em>[files]:</em><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/config_connection.php<br>
<em>[folders]:</em><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/images/<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/images/articles/<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- http://www.yourdomain.com/100jancms/images/articles/depot/<br>
<br>
<span class="titletext0blue">End User License Agreement:</span> <br>
<br>
<span class="maintext">In order to use this product you must agree to this EULA:</span> <br>
<br>
<iframe src="install_eula.php" name="eula" id="eula" frameborder="0" class="okvir" width="600" height="290">EULA is displayed here</iframe>
<br>
<br>
<br>
<br>
<input name="agree" type="checkbox" id="agree" value="1">
<strong>I agree to the End User License Agreement.</strong> <img src="images/app/asterix.jpg" width="9" height="8" align="absmiddle"><br>
<br>
<input type="button" name="update" value="Next -&gt;" style="width: 75px; height: 30px;" class="formfields2" onClick="step2_go()" align="absmiddle">
<br>
<br>
<br>
<br>
<br>

</body>
</html>
