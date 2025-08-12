<html><head>
<style type="text/css">
<!--
td {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12px;
}
body {
	margin: 0px;
	padding: 0px;
}
.menu {
	color: #FFFFFF;
	text-decoration: underline;
}
-->
</style>
<script language="JavaScript" src="w4ftell.js"></script>
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
if (!$friendemail1) {
?>
<br>
<form action="" method=POST>
<table width="390" border="0" cellspacing="0" cellpadding="3">
  <tr> 
    <td width="95" align="right" valign="top">&nbsp;</td>
    <td width="1">&nbsp;</td>
    <td width="100">Name: </td>
    <td width="194"> E-mail:</td>
  </tr>
  <tr> 
    <td align="right" valign="top"><b>I am:</b></td>
    <td>&nbsp;</td>
    <td> 
      <input type="text" name="name" size="15">
    </td>
    <td> 
      <input type="text" name="email" size="20">
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top"><b>Friend 1:</b></td>
    <td>&nbsp;</td>
    <td> 
      <input type="text" name="friendname1" size="15">
    </td>
    <td> 
      <input type="text" name="friendemail1" size="20">
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top"><b>Friend 2:</b></td>
    <td>&nbsp;</td>
    <td> 
      <input type="text" name="friendname2" size="15">
    </td>
    <td> 
      <input type="text" name="friendemail2" size="20">
    </td>
  </tr>
  <tr> 
    <td align="right" valign="top"><b>Message:</b></td>
    <td>&nbsp;</td>
    <td colspan="2"> 
      <textarea name="text" cols="35" rows="6">
I found this great website and I believe you would be interested. 
Click here to visit the page: <?php echo $QUERY_STRING ?>
</textarea>
    </td>
  </tr>
  <tr> 
    <td align="right"><b></b></td>
    <td>&nbsp; </td>
    <td> 
      <input type="submit" value="   Send   " name="Submit" onClick="MM_validateForm('name','','R','email','','RisEmail','friendname1','','R','friendemail1','','RisEmail','friendemail2','','NisEmail','friendemail3','','NisEmail','text','','R');return document.MM_returnValue">
    </td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<?php
}
else {
if ($friendemail1) { mail( $friendemail1, "Message from $name", "$friendname1,  \n\n".$text ."\n\nYour friend,\n $name", "From: $email"); }
if ($friendemail2) { mail( $friendemail2, "Message from $name", "$friendname2,  \n\n".$text ."\n\nYour friend,\n $name", "From: $email"); }

echo "<center><br><br>Thank you. Your friends have been notified.<br><br><hr=size=1>
	<br><br><a href='javascript:window.close();'>Close this window</a>";
}
?>
</body>
</html>
