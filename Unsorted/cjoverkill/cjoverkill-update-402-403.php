<?php

/******************************************************
 * CjOverkill version 4.0.3
 * © Kaloyan Olegov Georgiev
 * http://www.icefire.org/
 * spam@icefire.org
 * 
 * Please read the lisence before you start editing this script.
 * 
********************************************************/
	

    require("cj-conf.inc.php");
    require("cj-functions.inc.php");
    
    cjoverkill_connect();

    mysql_query("DROP TABLE IF EXISTS cjoverkill_blacklist") OR
      cjoverkill_print_error(mysql_error());
    mysql_query("CREATE TABLE cjoverkill_blacklist (
						      domain varchar(250) NOT NULL default '',
						      email varchar(250) NOT NULL default 'some@email.com',
						      icq varchar(50) NOT NULL default '0',
						      reason varchar(250) NOT NULL default '',
						      id int(11) NOT NULL auto_increment,
						      PRIMARY KEY  (id)
						    ) TYPE=MyISAM;") OR
      cjoverkill_print_error(mysql_error());


    cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>CjOverkill Installation</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
	");
    echo ("<div align=\"center\"><b><font size=\"4\">CjOverkill was succesfully upgraded<br>
	    Make sure you delete cjoverkill-install.php, cjoverkill-update.php  and cjoverkill-update-402-403.php!!!<br>
	    <br>
	    </b></div>
	    ");
echo ("</body>
	</html>
	");


?>

