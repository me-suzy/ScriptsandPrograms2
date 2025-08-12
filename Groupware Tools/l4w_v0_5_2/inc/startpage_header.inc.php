<?php

	/*=====================================================================
    // $Id: startpage_header.inc.php,v 1.2 2005/07/08 19:45:59 carsten Exp $
    // copyright evandor media Gmbh 2004
    //=====================================================================*/

    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    
    header("Last-Modified: " . gmdate("D, d M Y H:i:s")." GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");                          

    (isset($_REQUEST['msg'])) ? $msg = $_REQUEST['msg'] : $msg = "";

    //===============================================================
    // Test installation
    //===============================================================
    $config_file = "config/config.inc.php";
    if (!file_exists($config_file)) {
            die ("<br><br><center><i><b>".$config_file."</i> does not exist!</b>
              <br><br>Please read the install notes, edit <i>config.inc.php.default</i> and rename it to <i>$config_file</i>");
    }

    // --- basic includes -------------------------------------------
	include ($config_file);
    include ("connect_database.php");
    include ("inc/functions.inc.php");

    // --- check connection -----------------------------------------
    $check_connection = mysql_select_db ($db_name, $db);
    if (!$check_connection) {
        die ("<br><br><center><i><b>Could not connect to database!</b>
        <br><br>Please read the install notes and edit <i>config.inc.php</i> appropriately");
    }
	 
	// --- Check database installation ------------------------------
	$res = @mysql_query ("SELECT COUNT(*) FROM ".TABLE_PREFIX."useronline");
    if (mysql_error() != "") {
        echo "<br><center><font color='red'>";
		echo "<center>Problemens with database. Please check your installation!</center><br>";
		echo "<center>If you didn't install the application yet, please ";
        echo " <a href='".INSTALL_SCRIPT."'>run</a> the appropriate install skript.</center>";
    	echo "</font>";
    	die ("</body></html>");
	}

    // --- Cookie Handling ------------------------------------------
    if ((!isset($_REQEUST['login_given'])) && (isset($_COOKIE['l4wuser'])))
        $login_given = $_COOKIE['l4wuser'];
    elseif (isset($_REQEUST['login_given']))
        $login_given = $_REQEUST['login_given'];
    elseif (!isset($_COOKIE['l4wuser']))
        $login_given = "";

    // --- update users online --------------------------------------
    $timeout  = time()-300; // 5 minutes
    $result   = mysql_query("DELETE FROM ".TABLE_PREFIX."useronline WHERE timestamp<$timeout");
    logDBError (__FILE__, __LINE__, mysql_error());

    // --- messages -------------------------------------------------
    $error_msg = "";
    if ((isset($msg)) && ($msg == "login_fehler")) {
            $error_msg = "<b>Login oder Passwort nicht korrekt</b>";
    }
    if  ((isset($msg)) && ($msg == "no_access")) {
            $error_msg = "<b>Sie haben keine Berechtigung zur Nutzung von leads4web</b>";
    }
    if  ((isset($msg)) && ($msg == "not_a_groupmember")) {
            $error_msg = "<b>You do not belong to any group. Please contact the administrator</b>";
    }
    if  ((isset($msg)) && ($msg == "logged_in_fehler")) {
            $error_msg = "<b>Someone with your login (youself?) is already logged in... please wait at most 3 min and try again</b>";
    }
    if  ((isset($msg)) && ($msg == "mandator_error")) {
            $error_msg = "<b>You are not allowed to use this mandator</b>";
    }

    // --- application needs to be updated? -------------------------
    list ($name, $versionFromIniFile) = getInstalledApplication();
    if ($version != $versionFromIniFile) 
        die ('Installation has been updated ('.$version.' -> '.$versionFromIniFile.'), please run the update script (go <a href="index.php">here</a>)');
    
?>
<!DOCTYPE HTML SYSTEM "http://www.evandor.de/HTML4evandor.dtd">
<html>
<head>
        <title>Leads4web CRM - Version <?=$version?></title>
        <link href="favicon.gif"        rel="SHORTCUT ICON">
        <meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
        <meta name="copyright"          content="evandor media GmbH">
        <meta name="author"             content="Carsten Gräf">
        <meta name="publisher"          content="evandor media GmbH">
        <meta name="description"        content="leads4web is a customer relationship management tool for small to medium companies.">
        <meta name="keywords"           content="CRM, leads4web, leads4web installation, carsten, gräf, l4w, media, customer, relationship, management, download, open source, evandor media, Munich">

        <style type="text/css">
        <!--
                input.login {
                        color:#000066;
                        border-width:1px;
                        font-size:13px;
                        text-align:left;
                }

                input.loginbutton {
                        color:#000066;
                        background-color='#bfbfff';
                        border-width:1px;
                        border-style:solid;
                        border-color='#000066';
                        font-size:13px;
                        font-weight:bold;
                        text-align:center;
                }
                table.login {
                        background-color='#bfbfbf';
                        border-width:1px;
                        border-style:solid;
                        border-color='#000066';
                }
        -->
        </style>

</head>