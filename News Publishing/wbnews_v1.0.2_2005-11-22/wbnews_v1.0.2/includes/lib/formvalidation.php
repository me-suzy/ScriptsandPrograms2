<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 24th May 2005                           #||
||#     Filename: formvalidation.php                     #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package Library
*/

if (!defined('wbnews'))
	die ("Error, you called for an invalid file");

define("LIB_FORMVAL", true);

class FormVal
{
	
/** @var Array private*/
var $errors=array();
	
	/**
		Checks if a number is Numeric
		@access public
		@param string $string The String to check
		@param string $fieldName The Strings field name
		@return boolean
	*/
	function checkNumeric($string, $fieldName)
	{
		if (is_numeric($string))
			return true;
    	else
		{
			$this->errors[]=$fieldName.' requries numeric data.';
			return false;
		}
	}
	
	/**
		Check a string is less then specified chars allowed
		@access public
		@param string $string String to parse
		@param string $field The Field Name
		@param int $maxChars Integer of total characters allowed
		@return boolean
	*/
	function checkMaxChars($string, $field, $maxChars)
	{
		if (strlen($string) > $maxChars)
		{
			$this->errors[]=$field.' is greater than '.$maxChars;
			return false;
		}
    	else
			return true;
	}
	
	/**
		Check if a string is empty or considered empty with less than min chars
		@access public
		@param string $string The String to parse
		@param string $field The Field name
		@param int $minChars minimum chars allowed
		@return boolean
	*/
	function checkEmpty($string, $field, $minChars)
	{	
		if (empty($string))
		{
			$this->errors[]=$field.' must not be empty';
			return false;
		}
		else
		{
			if (strlen($string) >= $minChars)
				return true;
			else
			{
				$this->errors[]=$field.' must be more than '.$minChars.'Chars';
				return false;
			}
		}
	}
	
	/**
		Check if the Email is in valid Format
		@access public
		@param string $email the string in form of an Email
		@return bolean
	*/
	function validEmail($email)
	{
		if(preg_match("/^([a-zA-Z0-9])+([.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-]+)+[a-zA-Z0-9_-]$/", $email))
			return true;
    	else 
		{
			$this->errors[] = "Invalid Email Format";
			return false;
		}
	}
	
	/**
		Checks to see if both strings are the same
		@access public
		@param string $string1
		@param string $string2
		@param string $field1
		@param string $field2
		@return boolean
	*/
	function match($string1, $string2, $field1, $field2)
	{
		if ($string1 === $string2)
			return true;
		else
		{
			$this->errors[]=$field1.' doesnt match '.$field2;
			return false;
		}
	}
	
	/**
		Checks if a Date is valid
		@access public
		@param string $field Name
		@param int $year
		@param int $month
		@param int $day (optional)
		@return boolean
	*/
	function dateCheck($field, $year, $month, $day='')
	{
		if (!empty($day))
		{
			if (checkdate($month, $day, $year)!=false)
				return true;
			else
			{
				$this->errors[]=$field.' Date invalid';
				return false;
			}
		}
		else
		{
			if (strlen($year)== 4 && ($month >= 1 && $month <= 12))
				return true;
			else
			{
				$this->errors[]=$field.' Date invalid';
				return false;
			}
		}
	}
	
	/** 
		Add an Error
		@access public
		@param string $error
		@return void
	*/
	function addError($error)
	{
		$this->errors[] = $error;
		return;
	}
	
	/**
		Displays all errors with
		@access public
		@param void
		@return string
	*/
	function displayErrors()
	{
		$i=0;
		$msg='';
		$numErrors=sizeof($this->errors) - 1;
		
		foreach ($this->errors as $k => $val)
		{
			if ($i!=$numErrors)
				$msg.=$val.", ";
			else
				$msg.=$val;
			$i++;
		}
		return $msg;
	}
	
}

?>
