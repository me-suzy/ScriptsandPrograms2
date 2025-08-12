<?php

    include("config.php");
  
    $link = mysql_connect($dbhost,$dbuser,$dbpass);
        
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
        
    $result = mysql_select_db($dbname);
        
    if (!$result) {
        die ("Can\'t use $dbname : " . mysql_error());
    }

    mysql_select_db($dbname); 
    mysql_query("drop table boxes");
    mysql_query("drop table pages");
    mysql_query("drop table users");
    mysql_query("drop table prefs");
    
    echo "Removed all occurences of SSphp from your database";
    

?>