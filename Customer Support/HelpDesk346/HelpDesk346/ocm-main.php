<?php
	include_once "./checksession.php"; 
	include_once "./includes/settings.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Property Managment  Customer Support Help Desk</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<link href="style.css" rel="stylesheet" type="text/css">
<body>
<table width="137%" border="0">
  <tr bgcolor="#CCCCCC"> 
    <td height="59" bgcolor="#FFFFFF">
    <?php
    	if ($OBJ->get('navigation') == 'B')
    		include_once 'dataaccessheader.php';
    	else 
    		include_once 'textnavsystem.php';
    ?>
    </strong></td>
  </tr>
</table>
<table width="99%" border="0" cellpadding="0">
  <tr> 
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top"> <p align="left"><strong>OCM: 
        Our Crap Management.</strong></p></td>
  </tr>
  <tr> 
    <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="524" valign="top">
<p>Please 
        provide us with the details necessary so that we can quickly diagnose 
        your technical problem.<br>
      </p>
      <form action="ocmAdd.php" method="post" name="helpDesk" id="helpDesk">
        <table width="65%" border="0">
          <tr> 
            <td colspan="4"><br> </td>
          </tr>
          <tr> 
            <td width="10%"> Part#: <br> </td>
            <td width="31%"><input name="partNum" type="text" id="LastName24"></td>
            <td width="22%">Serial Number of Part </td>
            <td width="37%"><input name="serial" type="text" id="LastName33"></td>
          </tr>
          <tr> 
            <td>Location of part <br> </td>
            <td><input name="location" type="text" id="LastName44"></td>
            <td>Price of Part:</td>
            <td><input name="price" size="5" maxlength="10" type="text" /> </tr>
          <tr> 
            <td colspan="2"><p>Description of part:</p>
              <p> 
                <textarea name="description" cols="30" rows="5" id="description"></textarea>
              </p></td>
            <td colspan="2">&nbsp;</td>
          </tr>
        </table>
        <p> 
          <input type="submit" name="Submit" value="Submit" class="button">
        </p>
      </form>
      <p align="center"><font size="2" face="Times New Roman, Times, serif">CopyRight 
        2005 Help Desk Reloaded<br>
        <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software for 
        Tomorrows Problem.</a><br>
        Version 3.4.2.F</font></p></td>
  </tr>
</table>
</body>
</html>
