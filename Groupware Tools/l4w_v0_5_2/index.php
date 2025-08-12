<?php
   	/*=====================================================================
	// $Id: index.php,v 1.8 2005/07/09 08:51:45 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

	// does configuration file exist?
	if (!file_exists("config/config.inc.php")) {
		?>
		<script language=javascript>
			window.location.href = "main.php";
		</script>
		<?php
		die ("</body></html>");
	}
	 
	include ("config/config.inc.php");
	include ("inc/functions.inc.php");
	
	if (!defined("LOGIN_PAGE")) 
	    define ("LOGIN_PAGE", "main.php");
?>

<html>
<head>
	<title>Willkommen bei leads4web</title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="copyright"			content="evandor media GmbH">
	<meta name="author" 			content="Carsten Gräf, Martin Wiedemann, Oliver Gräber, Stefan Jaeckel">
	<meta name="publisher"			content="evandor media GmbH">
	<link REL="SHORTCUT ICON" HREF="http://www.evandor.com/icon.ico">
	<meta name="description" content="leads4web is a customer relationship management tool for small to medium companies."> 
	<meta name="keywords" content="CRM, leads4web, carsten, gräf, l4w, evandor, media, customer, relationship, management, download, open source"> 
</head>
<body>

<?php

    // --- check if installation has been changed -------------------
    // todo!!!
    list ($name, $versionFromIniFile) = getInstalledApplication();
    $redirect = LOGIN_PAGE;
    switch ($versionFromIniFile) {
        case "0.5.2":
            if ($version != $versionFromIniFile) // version from config
                $redirect = "install/leads4web_upgrade.php";
            break;
        default: 
    }    
?>

<script language=javascript>
	window.location.href = "<?=$redirect?>";
</script>
</body>
</html>