<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<?php 
	include ("../../language_files/".$_REQUEST["language"]."/gui.inc.php");

	//--- parameterlist without show
	while(list($key, $value) = each($HTTP_GET_VARS))
	{
		if ($key != "show")
		{
				$strURL = $strURL."&$key=".urlencode($value);
		}	
	} 
	
?>

<head>
	<title></title>
	<link rel="stylesheet" href="../format.css" type="text/css">
	<script language="JavaScript1.2">
	<!--
		function printMain()
		{
			parent.frames["Main"].focus();
			parent.frames["Main"].print();
		}
		
		function closeWindow()
		{
			parent.close();
		}
		
	//-->
	</script>
</head>
<body style="background-color:#D6D6D6;">
	<table>
		<tr>
			<td><img src="../../images/printer2.png" height="29" width="29" alt=""></td>
			<td>[ <a href="javascript:printMain();"><?php echo $PRINTBAR_PRINT;?></a> ]</td>
			<td width="20px">&nbsp;</td>
			<td><img src="../../images/close.png" height="29" width="29" alt=""></td>
			<td>[ <a href="javascript:closeWindow();"><?php echo $PRINTBAR_CLOSE;?></a> ]</td>
			<td width="20px">&nbsp;</td>
		</tr>
	</table>
	 
</body>
</html>
