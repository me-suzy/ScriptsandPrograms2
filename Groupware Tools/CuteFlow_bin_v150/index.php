<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<?php
	include ("config/config.inc.php");
	
	if ($_REQUEST["language"] == "")
	{
		$_REQUEST["language"] = $DEFAULT_LANGUAGE;	
	}
	
	include ("pages/version.inc.php");
	include ("language_files/".$_REQUEST["language"]."/gui.inc.php");
?>
<html>
<head>
	<title>CuteFlow</title>
	<link rel="stylesheet" href="pages/format.css" type="text/css">
	<script src="pages/jsval.js" type="text/javascript" language="JavaScript"></script>
	<script language="JavaScript1.2">
	<!--
		function setProps()
		{
			var objForm = document.forms["Login"];
			
			objForm.Password.required = 1;
			objForm.Password.err = "<?php echo $LOGIN_ERROR_PASSWORD;?>";
			
			objForm.UserId.required = 1;
			objForm.UserId.err = "<?php echo $LOGIN_ERROR_USERID;?>";
		}
	//-->
	</script>
</head>

<body onLoad="setProps()">
	<br>
	<h2 style="text-align: center; background-color: #DFDFDF; font-weight: bold;"><?php echo $TITLE_1." - ".$TITLE_2;?></h2>
	<br>
	<br>	
	<br>
	<div align="center">
		<strong><?php echo $LOGIN_HEADLINE;?></strong>
		<br>
		<br>
		<br>
		<table>
			<tr>
				<td valign="top">
					<img style="position:relative;top:-15px;left:35px" src="images/login.png" height="48" width="48" alt="login">
				</td>
				<td>
					<form id="Login" action="login.php" method="post" onsubmit="return jsVal(this);">
						<table border="0" class="note">
							<tr>
								<td colspan="2" style="background-color:Red;font-weight:bold;color:White;"><strong style="padding-left:35px"><?php echo $LOGIN_LOGIN;?></strong></td>
							</tr>
							<tr>
								<td colspan="2" height="15px"></td>
							</tr>
							<tr>
								<td><?php echo $LOGIN_USERID;?>:</td>
								<td><input type="text" name="UserId" class="FormInput"></td>
							</tr>
							<tr>
								<td><?php echo $LOGIN_PWD;?>:</td>
								<td><input type="password" name="Password" class="FormInput"></td>
							</tr>
							<tr>
				   				<td colspan="2" style="border-top: 1px solid #B8B8B8;padding: 6px 0px 4px 0px;" align="right">
									<input type="submit" value="<?php echo $BTN_LOGIN;?>" class="Button">
								</td>
				   			</tr>
						</table>
						<input type="hidden" name="language" value="<?php echo $DEFAULT_LANGUAGE;?>">
					</form>
				</td>
				<td width="48px">&nbsp;</td>
			</tr>
		</table>
		<br>
		<br>
		<strong style="font-size:8pt;font-weight:normal">powered by</strong><br>
		<a href="http://cuteflow.fantastic-bits.de" target="_blank"><img src="images/cuteflow_logo_small.png" border="0" /></a><br>
		<strong style="font-size:8pt;font-weight:normal">Version <?php echo $CUTEFLOW_VERSION;?></strong><br> 
		
	</div>
</body>
</html>
