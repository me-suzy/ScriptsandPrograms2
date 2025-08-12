<?php
	//Revised by Jason Farrell
	//Revised on May 18, 2005
	//Revision Number 1 CopyRight 2005 Help Desk Reloaded
	
	session_start();
	include_once "../config.php";
	include_once "./includes/functions.php";
	
	//this is if an error occured - rough idea of what should be done
	if (isset($_SESSION['error_msg']) and strlen($_SESSION['error_msg'])) {
		echo '<span style="font-weight: bold; color: red">' . $_SESSION['error_msg'] . '</span>';
		unset($_SESSION['error_msg']);
		exit;
	}
?>
<html>
	<head>
		<title>Your Search Results</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="Designed by Chad Edwards" content="QuickIntranet.com">
		<link href="style.css" rel="stylesheet" type="text/css">
		<style type="text/css">
			a {
				text-decoration: none;
				font-family: Arial;
				font-size: 14px;
				font-weight: bold;
			}
		</style>
	</head>
	
	<body>
		<?php 
			if (isset($_SESSION['loggedIn']))
				include_once "./includes/dataaccessheader.php";
			else
				include_once "./includes/otherheader.php"; 

		?>
		<table cellpadding="0" cellspacing="0" border="2" style="width:600px">
			<tr><th align="left" colspan="2" style="padding:2px">
				Your Search Results
			</th></tr>
			<?php
				if (isset($_SESSION['infoArray']) && is_array($_SESSION['infoArray']) && count($_SESSION['infoArray']))
				{
					foreach ($_SESSION['infoArray'] as $array)
					{
			?>
			<tr>
				<td valign="top" style="padding-right:5px; padding-top:5px; width:50px" align="right">
					<?php if (isset($_SESSION['enduser'])) $u = unserialize($_SESSION['enduser']); ?>
					<a href="<?php echo ( (isset($_SESSION['enduser']) && $u->get('securityLevel', 'intval') > ENDUSER_SECURITY_LEVEL) ? '../viewDetails.php' : 'viewTicket.php'); ?>?id=<?php echo $array['id']; ?>">#<?php echo $array['id']; ?></a>
				</td>
				<td valign="top" style="padding:3px; padding-left:5px">
					<b>Description:</b><br/>
					<?php echo stripslashes(nl2br($array['description'])); ?><p/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<?php
							if (isset($array['resolutions']) && is_array($array['resolutions'])) {		//this array will not here if no resolutions match
								echo "<b>Resolutions:</b><br/>\n";
								foreach ($array['resolutions'] as $res)
								{
									echo nl2br($res) . "<p/>\n";
								}
							}
							
							if (isset($_SESSION['useFiles'])) {
					?>
					<br/>
					<b>Associated Files:</b>
					<?php
								if (count($array['files'])) {
					?>
					<div style="padding-left:10px; display:inline">
					<?php
									foreach ($array['files'] as $f)
									{
										echo "$f<br/>\n";
									}
					?>
					</div>
					<?php
								}
							}
					?>
				</td>
			</tr>
			<tr><td height="5"></td></tr>
			<?php
					}
				}
				else {
			?>
			<tr><th style="color:red">
				No Results found based on Search Criteria
			</th></tr>
			<?php	
				}
			?>
		</table>
		<br><br>
	
<p align="left"><font size="2" face="Times New Roman, Times, serif">CopyRight 
  2005 Help Desk Reloaded<br>
  <a href="http://www.helpdeskreloaded.com">Today's Help Desk Software for Tomorrows 
  Problem.</a></font></p>
</body>
</html>