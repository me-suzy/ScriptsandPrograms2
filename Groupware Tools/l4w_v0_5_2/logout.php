<?php

        /*=====================================================================
        // $Id: logout.php,v 1.6 2005/04/03 06:30:09 carsten Exp $
        // copyright evandor media Gmbh 2003
        //=====================================================================*/

        include ("config/config.inc.php");
        include ("connect_database.php");
        include ("inc/functions.inc.php");
        
        @session_name (SESSION_NAME);
        @session_start();
        
        $result=mysql_query("DELETE FROM ".TABLE_PREFIX."useronline WHERE user_id='".$_SESSION['user_id']."'");
        logDBError (__FILE__, __LINE__, mysql_error());

        set_page_stats (__FILE__);
        
        session_unset ();
        session_destroy();


?>
<script language=javaScript>

        function go() {
                parent.parent.location.href="index.php";
        }

</script>
<html>
<body onLoad="go();">
</body>


