<?php
   	/*=====================================================================
	// $Id: guest.php,v 1.1 2005/03/29 15:35:34 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

	include ("config/config.inc.php");
    if (!ALLOW_GUEST_USER) die ("Guest login not allowed!");
    
?>

<html>
<head>
	<title>Welcome</title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="copyright"			content="evandor media GmbH">
	<meta name="author" 			content="Carsten Gräf, Martin Wiedemann, Oliver Gräber, Stefan Jaeckel">
	<meta name="publisher"			content="evandor media GmbH">
	<link REL="SHORTCUT ICON" HREF="http://www.evandor.com/icon.ico">
	<meta name="description" content="leads4web is a customer relationship management tool for small to medium companies."> 
	<meta name="keywords" content="CRM, leads4web, carsten, gräf, l4w, evandor, media, customer, relationship, management, download, open source"> 
</head>
<body>
<script language=javascript>
	window.location.href = "check_login.php?login=guest&passwort=";
</script>
</body>
</html>