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
 File: mcp.comment.php
-----------------------------------------------------
 Purpose: Commenting class - CP
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Comment_CP {

    var $version = '1.0';
    


    // --------------------------------
    //  Delete comment notification
    // --------------------------------

    function delete_comment_notification()
    {
        global $IN, $DB, $OUT, $PREFS, $LANG;
        
        if ( ! $id = $IN->GBL('id'))
        {
            return false;
        }
        
        $LANG->fetch_language_file('comment');
        
        $DB->query("UPDATE exp_comments SET notify = 'n' WHERE comment_id = '".$DB->escape_str($id)."'");
                
        $data = array(	'title' 	=> $LANG->line('cmt_notification_removal'),
        				'heading'	=> $LANG->line('thank_you'),
        				'content'	=> $LANG->line('cmt_you_have_been_removed'),
        				'redirect'	=> '',
        				'link'		=> array($PREFS->ini('site_url'), $PREFS->ini('site_name'))
        			 );
        
		$OUT->show_message($data);
    }
    // END



    // --------------------------------
    //  Module installer
    // --------------------------------

    function comment_module_install()
    {
        global $DB;        
        
        $sql[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Comment', '$this->version', 'n')";
        $sql[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Comment', 'insert_new_comment')";
        $sql[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Comment_CP', 'delete_comment_notification')";
    
    
        foreach ($sql as $query)
        {
            $DB->query($query);
        }
        
        return true;
    }
    // END
    
    
    // -------------------------
    //  Module de-installer
    // -------------------------

    function comment_module_deinstall()
    {
        global $DB;   
        
        $query = $DB->query("SELECT module_id FROM exp_modules WHERE module_name = 'Comment'"); 
                
        $sql[] = "DELETE FROM exp_module_member_groups WHERE module_id = '".$query->row['module_id']."'";
        $sql[] = "DELETE FROM exp_modules WHERE module_name = 'Comment'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Comment'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Comment_CP'";
        
    
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