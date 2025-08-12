<?php
	include_once "checksession.php";
?>
<html>
	<head>
		<title>Password Change Module</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="Designed by Chad Edwards" content="QuickIntranet.com">
		<script language="JavaScript">
		<!--
		function MM_reloadPage(init) {  //reloads the window if Nav4 resized
		  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
		    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
		  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
		}
		MM_reloadPage(true);
		// -->
		</script>
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body bgcolor="#FFFFFF" text="#000000"  link="#0000FF" alink="#FF0000" vlink="#0000FF">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr><td colspan="2">
				<table width="99%" border="0">
				  <tr> 
				    <td height="127" align="center" valign="top" bgcolor="#FFFFFF"> 
				      <div align="center"> <a href="../index.php"></a></div>
				      <table width="99%" border="0" cellpadding="0">
				        <tr> 
				          <td bordercolor="#FFFFFF" bgcolor="#FFFFFF" height="2" valign="top"> 
				            <img src="images/help-desk-account-managment.jpg" alt="Help Desk Account Managment" width="594" height="176" border="0" usemap="#Map2"><br>
				            <map name="Map">
				              <area shape="rect" coords="543,151,611,195" href="DataAccess.php">
				              <area shape="rect" coords="480,146,542,198" href="search.php">
				               
				              <area shape="rect" coords="280,146,362,194" href="actmgt.php">
				              <area shape="rect" coords="189,146,277,196" href="ocm-first.htm">
				              <area shape="rect" coords="127,148,182,198" href="DataAccessSearch.php">
				              <area shape="rect" coords="76,147,122,196" href="helpDeskAccessAllCalls.php">
				              <area shape="rect" coords="163,2,248,14" href="DataAccess.php">
				              <area shape="rect" coords="2,148,74,200" href="reportproblem.htm">
				            </map> <a href="actmgt.php">Back to help desk control panel.</a></td>
				        </tr>
				       </table>
					</td></tr>
				</table>
			</td></tr>
			<?php
				$error = false;
				if (isset($_POST['submit'])) {
					//validate POSTDATA	
					$_POST['user'] = mysql_result(
										mysql_query("select id from " . DB_PREFIX . "accounts where user = '" . mysql_real_escape_string($_COOKIE['record2']) ."'"),
										0, 'id'
									);
					include_once "./includes/userManage/passwd_validate.php";
				}
	
				if (isset($_POST['submit']) && !$error) {
					echo "<tr><th align=\"left\">Password Changed Successfully</th></tr>\n";
				}
				else {
			?>
			<tr><th colspan="2" algin="left">
				Enter the Following Password Information
			</th></tr>
			<form method="post" action="">
			<tr>
				<td>Enter Current Password:&nbsp;</td>
				<td><input type="password" name="oldPass" size="20" maxlength="30" /></td>
			</tr>
			<tr>
				<td>Enter New Password:&nbsp;</td>
				<td><input type="password" name="newPass1" size="20" maxlength="30" /></td>
			</tr>
			<tr>
				<td>Confirm New Password:&nbsp;</td>
				<td><input type="password" name="newPass2" size="20" maxlength="30" /></td>
			</tr>
			<tr><td colspan="2" style="color:red" align="center">
			<?php echo isset($page_error) ? $page_error : ''; ?>
			</td></tr>
			<tr><td colspan="2" align="center">
				<input type="submit" name="submit" value="Change Password" class="button" />
			</td></tr>
			</form>
		</table>
	</body>
</html>
<?php
				}
?>