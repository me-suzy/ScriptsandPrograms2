<html>
<head>
<?php 
/*	administration for develooping flash chat           */
/*	by Juan Carlos Posé                                 */
/*	juancarlos@develooping.com	                        */
/*	version 1.6.5	                                        */
require ('required/config.php');
?>
<title><?php echo htmlentities($intro_admin_title)?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
body {
background-color: #EEEEEE;
font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;  
font-size : 10px;  
}
a:link{ color :#990000;text-decoration: none;}
a:active{ color :#FF9933;text-decoration: none;}
a:visited {  color :#CC6666;text-decoration: none;}
a:hover { text-decoration: underline; 
color : #990000;
}
input, select, textarea{
border : 1px solid #999999;
background-color : #DDDDDD;
color : #666666;
font-size : 10px;
font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
border-width: 1px 0px 0px 1px;
text-indent : 2px;
}
input.but{
border : 1px solid #AAAAAA;
background-color : #CCCCCC;
color : #666666;
font-size : 10px;
font-family : Verdana, Geneva, Arial, Helvetica, sans-serif;
border-width: 2px 3px 3px 2px;
}
</style>
</head>

<body bgcolor="#EEEEEE" text="#000000">
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle"><form name="form1" method="post" action="adminusers.php">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" background="graphics/fondoceldas.jpg">
    <tr>
      <td width="5" height="5" align="left" valign="top"><img src="graphics/esq1.gif" width="5" height="5"></td>
      <td height="5" colspan="5" align="right" background="graphics/la1.gif"><img src="graphics/la1.gif" width="5" height="5"></td>
      <td width="5" height="5" align="right" valign="top"><img src="graphics/esq2.gif" width="5" height="5"></td>
    </tr>
    <tr>
      <td width="5" rowspan="2" align="right" background="graphics/la2.gif"><img src="graphics/la2.gif" width="5" height="5"></td>
      <td colspan="5" align="center" valign="top"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#990000"><?php echo htmlentities($intro_admin_title)?></font>
        <hr size="1" noshade></td>
      <td width="5" rowspan="2" background="graphics/la3.gif"><img src="graphics/la3.gif" width="5" height="5"></td>
    </tr>
    <tr>
      <td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#FFFFFF"><font color="#666666"><?php echo htmlentities($intro_admin_name)?></font>&nbsp;</font></td>
      <td><font face="Verdana, Arial, Helvetica, sans-serif" size="1">
        <input type="text" name="name" size="10" maxlength="12" value="<?php echo $name?>" style="background:#CCCCCC; width:50px;" onfocus="style.backgroundColor='#EEEEEE';" onblur="style.backgroundColor='#CCCCCC';">
      </font></td>
      <td align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="1"> 
        <font color="#FFFFFF">&nbsp;<font color="#666666"><?php echo htmlentities($intro_admin_password)?></font>&nbsp;</font></font></td>
      <td><font face="Verdana, Arial, Helvetica, sans-serif" size="1">
        <input type="password" name="password" size="10" maxlength="12" value="<?php echo $password?>" style="background:#CCCCCC; width:50px;" onfocus="style.backgroundColor='#EEEEEE';" onblur="style.backgroundColor='#CCCCCC';">
      </font></td>
      <td><font face="Verdana, Arial, Helvetica, sans-serif" size="1">
        <input type="submit" name="Submit" value="<?php echo htmlentities($intro_admin_button)?>"  class="but" id="enviar" onmouseover="style.backgroundColor='#DDDDDD'; style.color='#CC0000';" onmouseout="style.backgroundColor='#CCCCCC'; style.color='#666666'; width:75">
        </font></td>
      </tr>
    <tr>
      <td width="5" height="5" align="right" valign="bottom"><img src="graphics/esq3.gif" width="5" height="5"></td>
      <td height="5" colspan="5" align="right" background="graphics/la4.gif"><img src="graphics/la4.gif" width="5" height="5"></td>
      <td width="5" height="5"><img src="graphics/esq4.gif" width="5" height="5"></td>
    </tr>
  </table>
 
</form></td>
  </tr>
</table>


</body>
</html>
