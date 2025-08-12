<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Untitled</title>
</head>
<body>
<?php
    @session_start();
		$_SESSION['session_counter']++;
    echo @$_SESSION['session_counter'];
?>
</body>
</html>
