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
 File: core.filebrowser.php
-----------------------------------------------------
 Purpose: File Browser class
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class File_Browser {

    var $protocol       = "copy"; // copy or move
    var $thumb_prefix	= "thumb";
    var $upload_path    = "../uploads/";
    var $filelist		= array();
    var $width   		= '';
    var $height  		= '';
    var $imgtype 		= '';



    //-------------------------------------
    //  Constructor
    //-------------------------------------    

    function File_Browser()
    {
        global $DSP, $LANG, $PREFS;
    
        $LANG->fetch_language_file('upload');
        
        if ($PREFS->ini('thumbnail_prefix') != '')
        {
        	$this->thumb_prefix = $PREFS->ini('thumbnail_prefix');
        }
        
        if ( ! ereg("^_", $this->thumb_prefix))
        {
        	$this->thumb_prefix = "_".$this->thumb_prefix;
        }       
    }
    // END    



    //-------------------------------------
    //  List of Files
    //-------------------------------------    

    function create_filelist()
    {
        global $IN, $DSP, $LANG;   

    	if ($handle = @opendir($this->upload_path)) 
    	{ 
    		while (false !== ($file = @readdir($handle)))
    		{
    			if (is_file($this->upload_path.$file) && substr($file,0,1) != '.' && $file != "index.html")
    			{
    				if ( ! $this->image_properties($this->upload_path.$file))
    				{
    					$this->filelist[] = array('type' 	=> "other",
    											  'name' 	=> $file
    											 );
    				}
    				else
    				{
    					$this->filelist[] = array('type' 	=> "image",
    											  'name' 	=> $file,
    											  'width'   => $this->width,
    											  'height'	=> $this->height,
    											  'imgtype'	=> $this->imgtype
    											 );    				
    				}    											
    			}
    		}
    		
    		@closedir($handle);
    		return true;
    	}
    	else
    	{
    		return $DSP->error_message($LANG->line('file_viewing_error'));
    	}
    }
    // END
    
    
    //-------------------------------------
    //  Set upload directory path
    //-------------------------------------    

    function set_upload_path($path)
    {
    	global $DSP, $LANG;
    
        if ( ! is_dir($path))
        {
            return $DSP->error_message($LANG->line('path_does_not_exist'));
        }
        else
        {
            $this->upload_path = $path;
        }
    }
    // END
    


    //-------------------------------------
    //  Set image properties
    //-------------------------------------    

    function image_properties($file)
    {
    	if (function_exists('getimagesize')) 
        {
            if ( ! $D = @getimagesize($file))
            {
            	return FALSE;
            }
            
            $this->width   = $D['0'];
            $this->height  = $D['1'];
            $this->imgtype = $D['2'];
                       
            return TRUE;
        }

        return FALSE;
    }
    // END
    
    

    

}
// END CLASS
?>