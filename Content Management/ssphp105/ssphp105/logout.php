<?php
    require_once("topheader.php");
    

    foreach($_SESSION as $key=>$val)
    {
        //echo "key: " . $key . " val: " . $val . "<BR>\n";
        unset($_SESSION[$key]);
    }
    require_once("header.php");
    
    echo lgbox("Logout","<BR><center>You have been logged out<BR><BR>");
        
    require_once("footer.php");

?>