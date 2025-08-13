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
 File: core.language.php
-----------------------------------------------------
 Purpose: This class manages language files.
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Language {
    
    // The $weblog variable allows the word "weblog" to be changed
    // throuout the system.  This is useful if ExpressionEngine is
    // being used as a CMS rather than a blogging tool.
    
    var $weblog     = "weblog";
    
    // Private variables.
    
    var $language   = array();
    var $cur_used   = array();
    
    
    //-------------------------------------
    // Constructor
    //-------------------------------------    

    function Language()
    {
        $this->weblog = strtolower($this->weblog);
    }
    // END
    
    
    //-------------------------------------
    // Fetch a language file
    //-------------------------------------    

    function fetch_language_file($which = '')
    {
        global $IN, $OUT, $LANG, $SESS, $PREFS;
        
        if ($which == '')
        {
            return;
        }
        
        if ($SESS->userdata['language'] != '')
        {
            $user_lang = $SESS->userdata['language'];
        }
        else
        {
        	if ($IN->GBL('language', 'COOKIE'))
        	{
                $user_lang = $IN->GBL('language', 'COOKIE');
        	}
        	elseif ($PREFS->ini('deft_lang') != '')
            {
                $user_lang = $PREFS->ini('deft_lang');
            }
            else
            {
                $user_lang = 'english';
            }
        }

            
        if ( ! in_array($user_lang, $this->cur_used))
        {                
            if ( ! @include PATH_LANG.$user_lang.'/lang.'.$which.EXT)
            {
				if ( ! @include PATH_LANG.'english/lang.'.$which.EXT)
				{
					if ($PREFS->ini('debug') >= 1)
					{
						$error = 'Unable to load the following language file:<br /><br />/lang.'.$which.EXT;
				
						return $OUT->fatal_error($error);
					}
					else
						return;
				}
            }
            
            $this->cur_used[] = $which;
            
            $this->language = array_merge($this->language, $L);
            
            unset($L);     
        }
    }
    // END
    
    
    //-------------------------------------
    //  Fetch a specific line of text
    //-------------------------------------    

    function line($which = '', $label = '')
    {
        if ($which != '')
        {
            $line = ( ! isset($this->language[$which])) ? FALSE : $this->language[$which];
            
            if ($this->weblog != 'weblog')
            {
                $line = str_replace('weblog', strtolower($this->weblog), $line);
                $line = str_replace('Weblog', ucfirst($this->weblog),    $line);
            }
            
            if ($label != '')
            {
                $line = '<label for="'.$label.'">'.$line."</label>";
            }
            
            return stripslashes($line);
        }
    }
    // END
}
// END CLASS
?>