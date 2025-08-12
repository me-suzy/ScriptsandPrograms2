<?php 
#####################################################################
# NAME/ PURPOSE - this file is called by every file on the system in 
#      order to set up & maintain sessions 
#
# STATUS - Done
#
# LAST MODIFIED - 02/11/2005
#
# TO DO - nothing. done.
#
# NOTE: Due to the nature of this program being an open-source project,
#       refer to the project website https://sourceforge.net/projects/gssdms/
#		for the most current status on this project and all files within it
#
#####################################################################


    @mysql_connect("$cfg[server]", "$cfg[user]", "$cfg[pass]") or die("Unable to connect to SQL Server.");
    @mysql_select_db("$cfg[db]") or die("Unable to select database '$cfg[db]'");

    function sess_open($p, $n) {
        return true;
    }

    function sess_close() { 
        return true; 
    } 

    function sess_read ($sid) { 

        $sid = addslashes($sid); 
        $data = mysql_query("SELECT frog FROM session WHERE id='$sid'") or die(mysql_error());

        if (mysql_numrows($data) == 1) { 
            return mysql_result($data, 0); 
        } else { 
            return false; 
        } 
    } 

    function sess_write ($sid, $val) { 

        $sid = addslashes($sid); 
        $val = addslashes($val); 
        $exists = mysql_result(mysql_query("SELECT COUNT(*) FROM session WHERE id='$sid'"), 0);
        if ($exists == 0) { 
            $retval = mysql_query("INSERT INTO session (id,active,frog) VALUES ('$sid',UNIX_TIMESTAMP(NOW()),'$val')") or die(mysql_error());
        } else { 
            $retval = mysql_query("UPDATE session SET frog='$val',active=UNIX_TIMESTAMP(NOW()) WHERE id='$sid'") or die(mysql_error());
        } 
        return $retval; 
    } 

    function sess_destroy ($sid) { 
        $sid = addslashes($sid); 
        $retval = mysql_query("DELETE FROM session WHERE id='$sid'") or die(mysql_error());
        return $retval; 
    } 

    function sess_gc ($maxlifetime = 3600) { 
        $gone = time() - $maxlifetime; 
        $retval = mysql_query("DELETE FROM session WHERE active<$gone") or die(mysql_error());
        return $retval; 
    } 

    session_set_save_handler ( 
        'sess_open', 
        'sess_close', 
        'sess_read', 
        'sess_write', 
        'sess_destroy', 
        'sess_gc' 
    ); 

?>
