<?

include_once("class.text_on_image.php");
$vImage = &new text_on_image();

?>

<html>
<head>
<title>Example Text On Image</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#006699">
     <tr align="center" bordercolor="#FFFFFF" bgcolor="#3399CC">
      <td colspan="2"><font color="#FFFFFF" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Verification Image<br>
       </strong></font> </td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td width="16%" nowrap bgcolor="#AFD8EB"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Image code:<br>
        </font></td>
      <td width="84%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
	  <?
	  	echo $vImage->sessionCode;
	  ?>
	  </strong></font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td nowrap bgcolor="#AFD8EB"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Code Typed:<br>
         </font></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
	  <?
	  	echo $vImage->postCode;
	  ?>
</strong></font></td>
    </tr>
    <tr bordercolor="#FFFFFF">
      <td colspan="2" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
    </tr>
    <tr align="center" bordercolor="#FFFFFF">
      <td colspan="2" nowrap><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
		<? if($vImage->checkCode()) {
			echo "Not Case Sensitive Check = Valid Code!<br>";			
		}else{
			echo "Not Case Sensitive Check = Wrong Code!<br>";
		}
		?>
	 </strong></font></td>
    </tr>		
   <tr align="center" bordercolor="#FFFFFF">
      <td colspan="2" nowrap><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
		<? if($vImage->checkCodeSensitive()) {
			echo "Case Sensitive Check = Valid Code!<br>";			
		}else{
			echo "Case Sensitive Check = Wrong Code!<br>";
		}
		?>
	 </strong></font></td>
    </tr>		

</table>
<div align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">author: <a href="mailto:dooms@terra.com.br">Rafael &quot;DoomsDay&quot; Dohms</a></font></div>
</body>
</html>