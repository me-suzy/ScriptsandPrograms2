<?php
	session_start();
	session_unset();   //--- Unset session variables.
	session_destroy(); //--- End Session we created earlier.
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
	<head>
		<title></title>
		<script language="JavaScript">
		<!--
			parent.location.href = "../index.php?language=<?php echo $_REQUEST["language"];?>";
		//-->
		</script>
	</head>
	<body>
	</body>
</html>