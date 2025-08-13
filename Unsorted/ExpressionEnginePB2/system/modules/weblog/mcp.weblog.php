<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: mcp.weblog.php
-----------------------------------------------------
 Purpose: Weblog class - CP
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Weblog_CP {

    var $version        = '1.0';
	var $stats_cache    = array(); // Used by mod.stats.php

    // ----------------------------------------
    //  Module installer
    // ----------------------------------------

    function weblog_module_install()
    {
        global $DB;        
        
        $sql[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Weblog', '$this->version', 'n')";
    
        foreach ($sql as $query)
        {
            $DB->query($query);
        }
        
        return true;
    }
    // END
    
    
    // ----------------------------------------
    //  Module de-installer
    // ----------------------------------------

    function weblog_module_deinstall()
    {
        global $DB;    

        $query = $DB->query("SELECT module_id FROM exp_modules WHERE module_name = 'Weblog'"); 
                
        $sql[] = "DELETE FROM exp_module_member_groups WHERE module_id = '".$query->row['module_id']."'";        
        $sql[] = "DELETE FROM exp_modules WHERE module_name = 'Weblog'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Weblog'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Weblog_CP'";

        foreach ($sql as $query)
        {
            $DB->query($query);
        }

        return true;
    }
    // END



}
// END CLASS
?>