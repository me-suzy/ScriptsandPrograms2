<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : MailWorks Professional                           //
//   Release Version      : 1.2                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
	$find = @$_GET["find"];
	
	if($find == "")
		$find = "[Enter Search String]";
?>
<html>
<head>
	<script language="JavaScript">
	
		function ConfirmDelAllSubs()
		{
			// Double confirmation
			if(confirm('WARNING: You are about to delete your ENTIRE SUBSCRIBER LIST. This list can not be retrieved after it has been deleted and is not saved anywhere.\r\n\r\nClick OK if you\'re 100% sure or cancel to stop.'))
			{
				if(confirm('WARNING: If you click the OK button shown below then your entire list of newsletter subscribers will be removed.'))
				{
					parent.location.href = 'subscriber.php?what=deleteAll';
				}
			}
		}
	
	</script>
</head>
<body topmargin="0" leftmargin="0">
	<form action="subscriber.php" method="get" target="_parent">
		<input type="text" name="find" value="<?php echo $find; ?>" onClick="this.value = '';">
		<input type="submit" value="Search >>"> <input onClick="parent.location.href='subscriber.php'" type="button" value="Reset">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="Delete All >>" style="background-color: red; color: white; font-weight: bold; font-size: 10pt" onClick="ConfirmDelAllSubs()">
		<br><br>
	</form>
</body>
</html>