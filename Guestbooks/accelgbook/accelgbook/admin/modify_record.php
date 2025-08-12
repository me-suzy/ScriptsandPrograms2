<?php require_once("adminOnly.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>delete_record</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../forText.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {color: #FF0000}
-->
</style>
</head>

<body>
<form action="modify.php" method="post" name="modify_record" id="modify_record">
  <table width="600" border="0" cellspacing="1" cellpadding="2">
    <tr>
      <td class="forTableBgLeft">Please enter the ID of the record you wish to <span class="style2">modify</span>: </td>
    </tr>
    <tr>
      <td class="forTableBgRight"><input name="modifyID" type="text" class="forForm" id="modifyID"></td>
    </tr>
    <tr>
      <td class="forTableBgRight"><input name="Submit" type="submit" class="forButton" value="Submit"></td>
    </tr>
  </table>
</form>
</body>
</html>
