<?php
//Connect to mysql
   mysql_connect($DBHOST, $DBUSER, $DBPASS) or die(mysql_error());
//Connect to database
   mysql_select_db($DBNAME) or die(mysql_error());
?>