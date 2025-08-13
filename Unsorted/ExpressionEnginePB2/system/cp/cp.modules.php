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
 File: cp.modules.php
-----------------------------------------------------
 Purpose: The module management class
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Modules {


    // -----------------------------
    //  Constructor
    // -----------------------------   

    function Modules()
    {
        global $IN;
        
        switch($IN->GBL('M'))
        {
            case FALSE  :   $this->module_home_page();
                break;
            case 'INST' :   $this->module_installer();
                break;
            default     :   $this->module_handler();
                break;
        }    
    }
    // END
    
   
    
    // -----------------------------
    //  Module home page
    // -----------------------------   
    
    function module_home_page($message = '')
    {  
        global $DSP, $LANG, $DB;
        
        if ( ! $DSP->allowed_group('can_access_modules'))
        {
            return $DSP->no_access_message();
        }
        
        
        $can_admin = ( ! $DSP->allowed_group('can_admin_modules')) ? FALSE : TRUE;
        
        
        // -----------------------------------------------
        //  Fetch all module names from "modules" folder
        // -----------------------------------------------
                
        $modules = array();
    
        if ($fp = @opendir(PATH_MOD)) 
        { 
            while (false !== ($file = readdir($fp))) 
            {
                if ( ! eregi(".php$",  $file) AND ! eregi(".html$",  $file) AND ! eregi(".DS_Store",  $file) AND ! eregi("\.",  $file))
                {                     
					$LANG->fetch_language_file($file);
                                        
                    $modules[] = ucfirst($file);
                }
            } 
        } 
    
        closedir($fp); 
        
        
        // --------------------------------------
        //  Fetch the installed modules from DB
        // --------------------------------------
        
        $query = $DB->query("SELECT module_name, module_version, has_cp_backend FROM exp_modules ORDER BY module_name");
        
        $installed_mods = array();
        
        foreach ($query->result as $row)
        {
            $installed_mods[$row['module_name']] = array($row['module_version'], $row['has_cp_backend']);
        }
   
   
        // --------------------------------------
        //  Build page output
        // --------------------------------------
        
        $title = $LANG->line('modules');
        
        $r = $DSP->heading($title);
        
        $r .= $message;
        
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '0', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', 
                                array(
										NBS,
                                        $LANG->line('module_name'),
                                        $LANG->line('module_backend'),
                                        $LANG->line('module_description'),
                                        $LANG->line('module_version'),
                                        $LANG->line('module_status'),
                                        $LANG->line('module_action')
                                     )
                                ).
              $DSP->tr_c();

        
        $i = 0;
		$n = 1;
      
        foreach ($modules as $mod)
        {
            $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
            
            $r .= $DSP->tr();

            $r .= $DSP->table_qcell($style, $DSP->qspan('', $n++), '1%');

            
            // Module Name          
                        
            $name = ($LANG->line(strtolower($mod).'_module_name') != FALSE) ? $LANG->line(strtolower($mod).'_module_name') : $mod;            
                    
            if (isset($installed_mods[$mod]))
            {
                if ($installed_mods[$mod]['1'] == 'y')
                {
                    $name = $DSP->anchor(BASE.AMP.'C=modules'.AMP.'M='.$mod, '<b>'.$name.'</b>');
                }
            }
                                                 
            $r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $name), '25%');


            // Module Access  
              
            $backend = '--';

            if (isset($installed_mods[$mod]))
            {
                if (($installed_mods[$mod]['1'] == 'y'))
                {
                    $backend = $DSP->qspan('highlight_alt_bold', $LANG->line('yes'));
                }  
            }
            
            $r .= $DSP->table_qcell($style, $backend, '10%');
            
            
            // Module Description
            
            $r .= $DSP->table_qcell($style, $LANG->line(strtolower($mod).'_module_description'), '32%');
        
            
            // Module Version

            $version = ( ! isset($installed_mods[$mod])) ?  '--' : $installed_mods[$mod]['0'];
            
            $r .= $DSP->table_qcell($style, $version, '10%');


            // Module Status
        
            $status = ( ! isset($installed_mods[$mod]) ) ? 'not_installed' : 'installed';
        
            $show_status = ($status == 'not_installed') ? $DSP->qspan('highlight_bold', $LANG->line($status)) : $DSP->qspan('highlight_alt_bold', $LANG->line($status));
        
            $r .= $DSP->table_qcell($style, $show_status, '12%');
            
            // Module Action
            
            $action = ($status == 'not_installed') ? 'install' : 'deinstall';
            
            $show_action = ($can_admin) ? $DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=INST'.AMP.'MOD='.$mod, $LANG->line($action)) : '--';
            
            $r .= $DSP->table_qcell($style, $show_action, '10%');  
            
            
            $r .= $DSP->tr_c();      
        }

        $r .= $DSP->table_c();


        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        
        if ($message != '')
            $DSP->crumb_ov = TRUE;
        
        $DSP->body  = $r;
        $DSP->title = $title;
        $DSP->crumb = $title;
    }
    // END
    
    
    
    // -----------------------------
    //  Module handler
    // -----------------------------   

    function module_handler()
    {
        global $LANG, $IN, $DSP, $DB, $OUT, $SESS;
        
        if ( ! $DSP->allowed_group('can_access_modules'))
        {
            return $DSP->no_access_message();
        }    
        
        if ( ! $MOD = $IN->GBL('M', 'GET'))
        {
            return false;
        }
        
        
        $module = strtolower($MOD);

        if ($SESS->userdata['group_id'] != 1)
        {
        	$query = $DB->query("SELECT module_id FROM exp_modules WHERE module_name = '".ucfirst($module)."'"); 
			
			if ($query->num_rows == 0)
			{
				return false;
			}
			
			$access = FALSE;
						
			foreach ($SESS->userdata['assigned_modules'] as $key => $val)
			{
				if ($key == $query->row['module_id'])
				{
					$access = TRUE;
					break;
				}
			}
			
			if ($access == FALSE)
			{
				return $DSP->no_access_message();
			}    
		}
        
        $LANG->fetch_language_file($module); 
        
        $class  = ucfirst($MOD).'_CP';
                
        $path = PATH_MOD.$module.'/mcp.'.$module.EXT;
        
        if ( ! is_file($path))
        {
            $OUT->fatal_error($LANG->line('module_can_not_be_found'));
        }
        
        require $path;
        
        $MOD = new $class;
    }
    // END


    
    // ----------------------------------
    //  Module installer / De-installer
    // ----------------------------------

    function module_installer()
    {
        global $LANG, $IN, $DSP, $DB, $OUT;
        
        if ( ! $DSP->allowed_group('can_admin_modules'))
        {
            return $DSP->no_access_message();
        }    
        
        if ( ! $module = $IN->GBL('MOD', 'GET'))
        {
            return false;
        }
        
        $module = strtolower($module);
        
        $class  = ucfirst($module).'_CP';
       
        $query = $DB->query("SELECT count(*) AS count FROM exp_modules WHERE module_name = '".ucfirst($module)."'");
        
        $method = ($query->row['count'] == 0) ? $module.'_module_install' : $method = $module.'_module_deinstall';
        
        $path = PATH_MOD.$module.'/mcp.'.$module.EXT;
        
        if ( ! is_file($path))
        {
            $OUT->fatal_error($LANG->line('module_can_not_be_found'));
        }
        
		if ( ! class_exists($class))
		{
        	require $path;
		}        
        
        $MOD = new $class(0);

        $MOD->$method();
        
        $LANG->fetch_language_file($module);        

        $line = (ereg("deinstall", $method)) ? $LANG->line('module_has_been_removed') : $LANG->line('module_has_been_installed');

        $message = $DSP->qdiv('itemWrapper', $DSP->qspan('success', $line).NBS.'<b>'.$LANG->line($module.'_module_name').'</b>');

        $this->module_home_page($message);
    }
    // END
    
}
// END CLASS
?>