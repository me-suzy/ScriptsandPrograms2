<?php

include_once("class.text_on_image.php");
$vImage = &new text_on_image();

?>
<html>
<head>
<title>Text On Image Example</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<br><br>
  <form name="form1" method="post" action="
<?php
echo index().module().'/check';
?>">
<table border="0" cellpadding="0" cellspacing="0" bordercolor="#006699">
    <tr bordercolor="#FFFFFF" bgcolor="#3399CC">
      <td colspan="2" align="center"><font color="#FFFFFF" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Verification Image<br>
      </strong></font> </td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td colspan="2"><div align="center"><img src="
<?php echo index().module().'/image';
?>
"></div><br><br></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="25%" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please enter the code you see above*:&nbsp;</font></td>
      <td width="75%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
<?
	echo $vImage->showCodeBox();
?>
	</font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td colspan="2" align="center" nowrap>
			  <br><br>
        <input type="submit" name="Submit" value="Send">
      </td>
    </tr>    
</table>		
  </form>


<div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">author: <a href="mailto:dooms@terra.com.br">Rafael &quot;DoomsDay&quot; Dohms</a></font><br>
</div>
</body>
</html>
