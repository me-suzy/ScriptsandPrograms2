<?php
    require_once("topheader.php");
    require_once("config.php");

    
    if ($_POST["upass"] != "")
    {
        foreach($_POST as $key=>$val) 
        {
            $$key = $val;
        }
        //check for login
        $link = mysql_connect($dbhost,$dbuser,$dbpass);
        mysql_select_db($dbname);
        $result = mysql_query("select * from users where uname = \"$uname\"");
        $row = mysql_fetch_array($result,MYSQL_BOTH);
        $dbpassl = $row["pass"];
        $dbuserl = $row["uname"];
        
        
        $checkpass = md5($upass);
        $yesno = 1;
        
        if (($dbpassl != $checkpass)||($dbuserl != $uname)) 
        {
            $bad = "Invalid username or password";
        }
        else
        {
            $_SESSION['login'] = $row["modstatus"];
        }
        include("admin.php");
    }
    else
    {
        echo "You can't access this directly";
        exit();
    }
?>