<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 12th May 2005                           #||
||#     Filename: template.php                           #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package Library
	@todo Fix Templating System, Add Comments and Form Fields, better error reporting, privatise all object variables
*/

if (!defined('wbnews'))
	die('Hacking attempt');  

define("LIB_TPL", true);

class template
{

/**
	@var string private
*/
var $tplDir;
var $themeDir;
var $directory;

/**
	@var Array private
*/
var $cache = array(); //used to store Templates instead of continuely opening template files

/**
	@var bool private
*/
var $show = true;

	/**
		This firstly checks to make sure all directories used exist. tplDirectory 
		is an absolute path to not relative
		
		@access public
		@param string $tplDirectory Absolute path
		@param string $directory for this its either empty or admin
		@param string $theme the directory name for the Theme being used
		@return mixed
	*/	
	function template($tplDirectory, $directory, $theme)
	{
		if (is_dir($tplDirectory))
		{
			$this->tplDir = $tplDirectory;
			if (is_dir($this->tplDir."/".$theme))
			{
				$this->themeDir = $theme;
				if (!empty($directory) && is_dir($this->tplDir."/".$this->themeDir."/".$directory))
					$this->directory = (substr($directory, -1) == "/" ? $directory : $directory . "/");
				else if (empty($directory))
					$this->directory = '';
				else
				{
					$this->errorHandle("Directory (".$directory.") Doesnt Exist");
					return false;
				}
			}
			else
			{
				$this->errorHandle("Theme Directory (".$theme.") Doesnt Exist");
				return false;
			}
		}
		else
		{
			$this->errorHandle("Template Directory (".$tplDirectory.") Doesnt Exist");
			return false;
		}
	}
	
	/**
		Gets a Template String, it first checks if the template has been already used 
		by searching the cache, if not the template file is opened and placed into cache
		else the cache result it returned
		
		@access public
		@param string $template The Template Name
		@return string
	*/
	function getTemplate($template)
	{
		$filename = $this->tplDir."/".$this->themeDir."/".$this->directory.$template.".tpl";
		if (!array_key_exists($filename, $this->cache))
		{
			$fp = @fopen($filename, "r");
			if (!$fp)
				$this->errorHandle('Couldnt Open File', $template);
			else
			{
				if (($contents=fread($fp, filesize($filename))) != false)
				{
					fclose($fp);
					$this->cache[$filename] = $contents;
					return $contents;
				}
				else
					$this->errorHandle('Couldnt read file', $template);
			}
		}
		else
		{
			//return cache result
			return $this->cache[$filename];
		}
	}
	
	/**
		Very simple templating method used. At the moment it only supports {} templating.
		The function uses a hash Array e.g. Array['name'] = 'value' where {name} will be replaced
		by value.
		
		@access public
		@param string $string
		@param array $array
		@return string
	*/
	function replace($string, $array)
	{
		if (is_array($array))
		{
			foreach ($array as $key => $value)
				$string = str_replace("{".$key."}", $value, $string);
		}
		return $string;
	}
	
	/**
		Method is used to show that the object variable show is a private member.
		show variable is used for the final phase the template process for either
		direct output (echo) or to return a string to be later used by another process
		
		@access public
		@param boolean $outputType
		@return void
	*/
	function tplOutput($outputType)
	{
		$this->show = $outputType;
		return;
	}
	
	/**
		Displays the template depending on use of method tplOutput show is defaulted to true
		which will automatically display output
		
		@access public
		@param string $contents
		@return mixed
	*/
	function displayTemplate($contents)
	{
		if ($this->show == true)
			echo $contents;
		else
			return $contents;
	}
	
	/**
		Form Name, Associate/Hash Array of Option Fields, (Optional) Selected Value, (Optional) 
		Select Type (multiple, normal, none) (None means only Option Fields Returned)
	*/
	function dropdown($name, $fields, $selected = '', $nullSelect = '', $selectType = '')
	{
        $contents = "";
        
        if (!empty($name))
            $contents .= '<select name="'.$name.'"'.(!empty($selectType) ? ' multiple="multiple"' : '').'>';
        
        $contents .= ($nullSelect == 1 ? '<option value="-1"> - - Select - - </option>' : "");
        foreach ($fields as $var => $value)
        {
            if ($selected == $var)
                $contents .= '<option value="'.$var.'" selected="selected">'.$value.'</option>';
            else
                $contents .= '<option value="'.$var.'">'.$value.'</option>';
        }
        if (!empty($name))
            $contents .= '</select>';
            
        return $contents;
	}
	
	/**
		Form Name, (Optional) Seperator (' ', '<br />'), (Optional) Checked Value
	*/
	function yesno($name, $seperator = '', $checked = '')
	{
        if ($checked == 1)
				return '<input type="radio" name="'.$name.'" value="1" checked="checked" /> Yes '.$seperator.' <input type="radio" name="'.$name.'" value="0" /> No';
			else
				return '<input type="radio" name="'.$name.'" value="1" /> Yes '.$seperator.' <input type="radio" name="'.$name.'" value="0" checked="checked" /> No';
	}
	
	/**
		Show text Input field
		@access public
		@param string $name
		@param string $value
		@return string
	*/
	function textinput($name, $value = '')
	{
		return '<input type="text" name="'.$name.'" value="'.$value.'" />';
	}
	
	/**
		Form Name, (Optional) Column Width, (Optional) Rows, (Optional) Field Value
        
        @access public
        @param string $name - Form Element Name
        @param string $value - Form Element Value
	*/
	function textarea($name, $value = '', $cols = 44, $rows = 5)
	{
        return '<textarea name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'">'.$value.'</textarea>';
	}
	
	/**
		Error Message given from a Method, (Optional) Template Filename involved (Can be used as cache)
	*/
	function errorHandle($methodError, $tplFile='')
	{
        echo "<pre><strong>".$methodError.": </strong>". $tplFile;
        if (DEBUG)
            echo '<p />' . print_r($this, true);
        echo '</pre>';
        
		exit;
	}
	
}

?>
