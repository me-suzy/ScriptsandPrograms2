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
 File: core.prefs.php
-----------------------------------------------------
 Purpose: This class manages system and user prefs.
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Preferences {
    
    var $core_ini = array();

    //------------------------------------------
    //  Fetch a specific core config variable
    //------------------------------------------
    
    function ini($which = '', $slash = false)
    {
        // Note:  Since many prefs we gather are paths, we use the
        // second parameter to checks whether the trailing slash
        // is present.  If not, we'll add it.
        
        if ($which == '')
            return FALSE;
    
        $pref = ( ! isset($this->core_ini[$which])) ? FALSE : $this->core_ini[$which];
        
        if ($pref !== FALSE AND $slash !== FALSE)
        {
            if ( ! ereg("/$", $pref)) 
            {
                $pref .= '/';
            }
        }

        return $pref;
    }
    // END
    
}
// END CLASS
?>