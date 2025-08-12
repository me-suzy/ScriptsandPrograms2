<?php
	session_start();

	include ("language_files/".$_REQUEST["language"]."/gui.inc.php");
	include ("config/config.inc.php");
	
	//--- check the user name and password
	$bAccessAllowed = false;

	$nConnection = mysql_connect($DATABASE_HOST, $DATABASE_UID, $DATABASE_PWD);
	if ($nConnection)
	{
		if (mysql_select_db($DATABASE_DB, $nConnection))
		{
			$strMd5Password = md5($_REQUEST["Password"]);
			
			$query = "select * from cf_user where strPassword = '$strMd5Password' AND strUserId = '".$_REQUEST["UserId"]."'";
			
			$nResult = mysql_query($query, $nConnection);

			if ($nResult)
			{
				if (mysql_num_rows($nResult) > 0)
				{		
					$arrRow = mysql_fetch_array($nResult);
					
					if ($arrRow)
					{
						$nID = $arrRow["nID"];
					}

					if ( ($arrRow["nAccessLevel"] == 2) || ($arrRow["nAccessLevel"] == 4))
					{
						$bAccessAllowed = true;	
					}
				}
			}
		}
		else 
		{
			//echo "DB not selected:".mysql_error()."<br>";
		}
	}

	$_SESSION["SESSION_CUTEFLOW_USERNAME"] = $_REQUEST["UserId"];
	$_SESSION["SESSION_CUTEFLOW_USERID"] = $nID;
	$_SESSION["SESSION_CUTEFLOW_PASSWORD"] = md5($_REQUEST["Password"]);
	$_SESSION["SESSION_CUTEFLOW_ACCESSLEVEL"] = $arrRow["nAccessLevel"];
	
	if ($bAccessAllowed == false)
	{
		session_unset();   //--- Unset session variables.
		session_destroy(); //--- End Session we created earlier.
?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
		<html>
		<head>
			<title></title>
			<link rel="stylesheet" href="pages/format.css" type="text/css">
		</head>
		<body>
			<table width="100%" height="100%">
				<tr>
					<td align="center" valign="middle">
						<table width="300px" class="note">
							<tr>
								<td valign="top">
									<img src="images/stop2.png" height="48" width="48" alt="Stop">
								</td>
								<td>
									<?php echo $LOGIN_FAILURE;?><br>
									<br>
									<a href="javascript:history.back();"><?php echo $BTN_BACK;?></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>		
			</table>	
		</body>
		</html>
<?php
	}
	else
	{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

		<html>
			<head>
				<title></title>
				<link rel="stylesheet" href="format.css" type="text/css">
				<script language="JavaScript">
				<!--
					parent.location.href = "frame.php?language=<?php echo $_REQUEST["language"];?>";
				//-->
				</script>
			</head>
			<body>
			</body>
		</html>
<?php
	}
?>