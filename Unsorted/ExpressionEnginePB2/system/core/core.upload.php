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
 File: core.upload.php
-----------------------------------------------------
 Purpose: File uploading class
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Upload {

    var $protocol       = "copy"; // copy or move
    var $thumb_prefix	= "thumb";
    var $is_image       = 1;
    var $width			= '';
    var $height			= '';
    var $imgtype		= '';
    var $max_size       = 0;
    var $max_width      = 0;
    var $max_height     = 0;
    var $max_size       = 0;
    var $remove_spaces  = 1;
    var $allowed_types  = "img";  // img or all
    var $file_temp      = "";
    var $file_name      = "";
    var $file_type      = "";
    var $file_size      = "";
    var $upload_path    = "../uploads/";
    var $temp_prefix    = "temp_file_";



    //-------------------------------------
    //  Constructor
    //-------------------------------------    

    function Upload()
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
    //  Upload file
    //-------------------------------------    

    function upload_file()
    {
        global $IN, $DSP, $LANG;        
                
    	if (is_uploaded_file($_FILES['userfile']['tmp_name'])) 
    	{
    		$this->file_temp =& $_FILES['userfile']['tmp_name'];		
    		$this->file_name =& $_FILES['userfile']['name'];
    		$this->file_size =& $_FILES['userfile']['size'];   
    		$this->file_type =& $_FILES['userfile']['type'];
            $this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $this->file_type);
        }
        else
        {
            $error = ( ! isset($_FILES['userfile']['error'])) ? 4 : $_FILES['userfile']['error'];

            switch($error)
            { 
                case 1  :   return $DSP->error_message($LANG->line('file_exceeds_ini_limit'));
                    break;
                case 3  :   return $DSP->error_message($LANG->line('file_partially_uploaded'));
                    break;
                case 4  :   return $DSP->error_message($LANG->line('no_file_selected'));
                    break;
                default :   return $DSP->error_message($LANG->line('file_upload_error'));
                    break;
            }
        }
            
        //-------------------------------------
        //  Determine if the file is an image
        //-------------------------------------    
        
        $this->validate_image();

        //-------------------------------------
        //  Is the filetype allowed?
        //-------------------------------------    
        
        if ( ! $this->allowed_filetype())
        {
            return $DSP->error_message($LANG->line('invalid_filetype'));
        }
      
        //-------------------------------------
        // Is the filesize allowed?
        //-------------------------------------    
        
        if ( ! $this->allowed_filesize())
        {
            return $DSP->error_message($LANG->line('invalid_filesize'));
        }
        
        //-------------------------------------
        //  Are the dimensions allowed?
        //-------------------------------------    

        if ( ! $this->allowed_dimensions())
        {
            return $DSP->error_message($LANG->line('invalid_dimensions'));
        }
        
        //-------------------------------------
        //  Set image properties
        //-------------------------------------    

		$this->set_properties();        
        
        //-------------------------------------
        // Remove white space in file name
        //-------------------------------------    

        if ($this->remove_spaces == 1)
        {
            $this->file_name = preg_replace("/\s+/", "_", $this->file_name);
        }
        
        //-------------------------------------
        //  Does file already exist?
        //-------------------------------------    
        
        if (file_exists($this->upload_path.$this->file_name))
        {
            $name = $this->upload_path.$this->temp_prefix.$this->file_name;
            
            $message = 'exists';
        }
        else
        {
            $name = $this->upload_path.$this->file_name;
            
            $message = 'success';
        }
        
        //---------------------------------------------------
        //  Move the uploaded file to the final destination
        //---------------------------------------------------
        
        if ($this->protocol == "copy")
        {
            if ( ! @copy($this->file_temp, $name))
            {
                return $DSP->error_message($LANG->line('upload_error'));
            } 
        }
        else
        {
            if ( ! move_uploaded_file($this->file_temp, $name))
            {
                return $DSP->error_message($LANG->line('upload_error'));
            }
        }
        
  
        return $message;
    }
    // END
    
  

    //-------------------------------------
    //  Validate image
    //-------------------------------------    

    function validate_image()
    {
        $img_mimes = array(
                            'image/gif',
                            'image/jpeg', 
                            'image/pjpeg',
                            'image/png'
                           );
    

        return $this->is_image = (in_array($this->file_type, $img_mimes)) ? 1 : 0;
    }
    // END
    

    //-------------------------------------
    //  Verify filetype
    //-------------------------------------    

    function allowed_filetype()
    {
        if ($this->allowed_types == 'img')
        {
            if ($this->is_image == 1)
            {   
                return TRUE;                
            }
            else
            {
                return FALSE;
            }        
        }

        return TRUE;    
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
    //  Set maximum filesize
    //-------------------------------------    

    function set_max_filesize($n)
    {
        $this->max_size = ( ! eregi("^[[:digit:]]+$", $n)) ? 0 : $n; 
    }
    // END


    //-------------------------------------
    //  Set maximum width
    //-------------------------------------    

    function set_max_width($n)
    {    
        $this->max_width = ( ! eregi("^[[:digit:]]+$", $n)) ? 0 : $n; 
    }
    // END


    //-------------------------------------
    //  Set maximum height
    //-------------------------------------    

    function set_max_height($n)
    {
        $this->max_height = ( ! eregi("^[[:digit:]]+$", $n)) ? 0 : $n; 
    }
    // END


    //-------------------------------------
    //  Set allowed filetypes
    //-------------------------------------    

    function set_allowed_types($types)
    {
        $options = array('img', 'all');
    
        if ($types == '' OR ! in_array($types, $options))
            $types = 'img';
		    
        $this->allowed_types =& $types; 
    }
    // END


    //-------------------------------------
    //  Verify filesize
    //-------------------------------------    

    function allowed_filesize()
    {        
        if ($this->max_size != 0  AND  $this->file_size > $this->max_size)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    // END


    //-------------------------------------
    //  Verify image dimensions
    //-------------------------------------    

    function allowed_dimensions()
    {
        if ($this->is_image != 1)
        {
            return TRUE;    
        }
    
        if (function_exists('getimagesize')) 
        {
            $D = @getimagesize($this->file_temp);
            
            if ($this->max_width > 0 AND $D['0'] > $this->max_width)
            {
                return FALSE;
            }

            if ($this->max_height > 0 AND $D['1'] > $this->max_height)
            {
                return FALSE;
            }
                       
            return TRUE;
        }

        return TRUE;
    }
    // END


    //-------------------------------------
    //  Set image properties
    //-------------------------------------    

    function set_properties()
    {
        if ($this->is_image != 1)
        {
            return;    
        }
    
        if (function_exists('getimagesize')) 
        {
            $D = @getimagesize($this->file_temp);
            
            $this->width   = $D['0'];
            $this->height  = $D['1'];
            $this->imgtype = $D['2'];
                       
            return TRUE;
        }

        return TRUE;
    }
    // END

    
    //-------------------------------------
    //  File overwrite 
    //-------------------------------------    

    function file_overwrite()
    {        
        global $IN, $DSP, $LANG;   
        
        $original_file = $IN->GBL('original_file', 'POST');
        
        $this->file_name = $IN->GBL('file_name', 'POST'); 
                    
        if (@copy($this->upload_path.$this->temp_prefix.$original_file, $this->upload_path.$this->file_name))
        {
            unlink ($this->upload_path.$this->temp_prefix.$original_file);

            return TRUE;    		
        }			
        else 
        {
            return $DSP->error_message($LANG->line('copy_error'));
        }
    }
    // END
    
        
    //-------------------------------------
    //  Image resize - GD 
    //-------------------------------------    

    function image_resize_gd($props)
    {	
    	global $LANG, $DSP, $PREFS;
    	
    	// 1 = gif
    	// 2 = jpg
    	// 3 = png
    	
		if ($props['type'] != 2 AND $props['type'] != 3 )
		{
			$DSP->error_message($LANG->line('jpg_or_png_required'));
			
			return false;
		}
    
        if ( ! function_exists('imagecreatefromjpeg'))
        { 
			$DSP->error_message($LANG->line('unsupported_protocol'));
			
			return false;
        }
        
		if ( ! ereg("/$", $props['path'])) $props['path'] .= "/";
		
		$s_image = $props['path'].$props['filename'];

		$src_img = ($props['type'] == 2) ? imagecreatefromjpeg($s_image) : imagecreatefrompng($s_image);

		if ($PREFS->ini('image_resize_protocol') == 'gd2')
		{
			$dst_img = imagecreatetruecolor($props['t_width'], $props['t_height']); 
		}
		else
		{
			$dst_img = imagecreate($props['t_width'], $props['t_height']); 
		}	
		
		if ($PREFS->ini('image_resize_protocol') == 'gd2')
		{
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $props['t_width'], $props['t_height'], $props['s_width'], $props['s_height']); 
		}
		else
		{
			imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $props['t_width'], $props['t_height'], $props['s_width'], $props['s_height']); 
		}	
		
		$name = substr($props['filename'], 0, strpos($props['filename'], "."));
		$ext  = substr($props['filename'], strpos($props['filename'], "."));
						
		imagejpeg($dst_img, $props['path'].$name.$this->thumb_prefix.$ext, $props['quality']); 

		imagedestroy($dst_img); 
		imagedestroy($src_img); 
		
		return true;
    }
    // END
    
    
    //-------------------------------------
    //  Image resize - ImageMagick 
    //-------------------------------------    

    function image_resize_imagemagick($props)
    {	
    	global $LANG, $DSP, $PREFS;
    	
    	$retval = 1;
    	
    	$libpath = $PREFS->ini('image_library_path');
    	
    	if ($libpath == '')
    	{
			$DSP->error_message($LANG->line('libpath_invalid'));
			
			return false;
    	}
    	
		if ( ! ereg("/$", $libpath)) $libpath.= "/";
    	        
		$props['path'] = realpath($props['path']).'/';
		
    	$props['path'] = str_replace("\\", "/", $props['path']);    
		
		$s_image = $props['path'].$props['filename'];
		
		$name = substr($props['filename'], 0, strpos($props['filename'], "."));
		$ext  = substr($props['filename'], strpos($props['filename'], "."));
								
		$t_image = $props['path'].$name.$this->thumb_prefix.$ext;	
			
		$cmd = $libpath.'convert -resize '.$props['t_width'].'x'.$props['t_height'].' '.$s_image.' '.$t_image.' 2>&1';;
		
		exec($cmd, $output, $retval);
				
		if ($retval > 0) 
		{
			$DSP->error_message($LANG->line('image_resize_failed'));
			
			return false;
		}		
		
		return true;
    }
    // END
    
    
    
    //-------------------------------------
    //  Image resize - NetPBM 
    //-------------------------------------    

    function image_resize_netpbm($props)
    {	
    	global $LANG, $DSP, $PREFS;
    	    	
    	$libpath = $PREFS->ini('image_library_path');
    	
    	if ($libpath == '')
    	{
			$DSP->error_message($LANG->line('libpath_invalid'));
			
			return false;
    	}
    	
		if ( ! ereg("/$", $libpath)) $libpath .= "/";
    			
    	// 1 = gif
    	// 2 = jpg
    	// 3 = png
		
		if ($props['type'] != 1 AND $props['type'] != 2 AND $props['type'] != 3)
		{
			$DSP->error_message($LANG->line('jpg_gif_png_required'));
			
			return false;
		}
		
		$props['path'] = realpath($props['path']).'/';
		
    	$props['path'] = str_replace("\\", "/", $props['path']);    

		$s_image = $props['path'].$props['filename'];
		
		$name = substr($props['filename'], 0, strpos($props['filename'], "."));
		$ext  = substr($props['filename'], strpos($props['filename'], "."));
		
		$t_image = $name.$this->thumb_prefix.$ext;	
			
		$cmd_end = ' '.$s_image.' | '.$libpath.'pnmscale -xsize='.$props['t_width'].' -ysize='.$props['t_height'].' | '.$libpath; 
			
		switch ($props['type'])
		{
			case 1 : $cmd = $libpath.'giftopnm '.$cmd_end.'ppmquant 256 | '.$libpath.'ppmtogif > '.$t_image;
				break;
			case 2 : $cmd = $libpath.'jpegtopnm '.$cmd_end.'ppmtojpeg > '.$t_image;
				break;
			case 3 : $cmd = $libpath.'pngtopnm '.$cmd_end.'pnmtopng > '.$t_image;
				break;
		}
		
		exec($cmd, $output, $retval);
		
		if ($retval > 0) 
		{
			$DSP->error_message($LANG->line('image_resize_failed'));
			
			return false;
		}		
		
		return true;
    }
    // END
    

}
// END CLASS
?>