<?php
//Revised July 6, 2005
// By JF
//Revision 2


	include_once 'checksession.php';
	/*
		Page for modifying and delete resolutions, accessible primarily from viewDetails.php
		Delete will dispaly no output, just do its business and be done
		Update will load a small form and have the user update, and then return after the update is made
		
		We will rely on checksession for guarenteeing security
	*/
	
	//get the delete operation out the way
	if (isset($_GET['action']) && $_GET['action'] == 'delete') {
		//call the command
		$cmd = "delete from " . DB_PREFIX . "resolution where resid = " . intval($_GET['resid']);
		mysql_query($cmd) or die("Resolution Delete Failed");
		header("Location: viewDetails.php?record=" . intval($_GET['tickno']));	
	}
	
	if (isset($_POST['submit'])) {
		//update and return
		mysql_query("update " . DB_PREFIX . "resolution set solution = '" . mysql_real_escape_string($_POST['newRes']) . "' where resid = " . intval($_GET['resid'])) or die("Error");
		header("Location: viewDetails.php?record=" . intval($_POST['record']));
	}
	else {
		$res = mysql_result(mysql_query("select solution from " . DB_PREFIX . "resolution where resid = " . intval($_GET['resid'])), 0, 'solution');
		$_POST['record'] = intval($_GET['tickno']);
	}
	
	//now for the update operation
	include_once './dataaccessheader.php';
?>
<html>
	<head>
		<title>Resolution Update Form</title>
		<link rel="stylesheet" href="./styles.css" type="text/css" />
	</head>
	
	<body>
		<table cellpadding="0" cellspacing="0" border="0">
		<form method="post" action="?resid=<?php echo intval($_GET['resid']); ?>">
			<input type="hidden" name="record" value="<?php echo $_POST['record']; ?>" />
			<tr><th align="left">Please Update the Given Resolution:</th></tr>
			<tr><td valign="top">
				<textarea name="newRes" rows="5" cols="45"><?php echo $res; ?></textarea>
			</td></tr>
			<tr><td align="center">
				<input type="submit" name="submit" value="Update Resolution" class="button" />
			</td></tr>
		</form>
		</table>
	</body>
</html>