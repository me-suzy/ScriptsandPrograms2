<?PHP
/**
* MyObjects
*
* Copyright (c) 2004 R. Erdinc Yilmazel <erdinc@yilmazel.com>
*
* http://www.myobjects.org
*
* @version $Id: StringValidator.php,v 1.5 2004/11/23 23:31:37 erdincyilmazel Exp $
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @package MyObjectsRuntime
*/

/**
* String Validator
*
* This class defines some static methods for validating strings
*
* @author Erdinc Yilmazel <erdinc@yilmazel.com>
* @version 1.0
* @package MyObjectsRuntime
*/
class StringValidator {
    
    /**
    * Checks if the specified string is a valid email address
    *
    * @param string $email The email address that will be validated
    * @return boolean Returns true of the specified string is a valid email
    */
    public static function isEmail($email) {
        // Remove whitespace
        $email = trim($email);
        
        $ret = ereg('^([a-z0-9_]|\-|\.)+' . '@' . '(([a-z0-9_]|\-)+\.)+' . '[a-z]{2,4}$',
                    $email);
        
        return($ret);
    }
    
    /**
    * Checks if the specified string is a valid date
    *
    * This method checks if the specified string is a valid string of
    * MySql date type. MySql date type is in (YYYY-MM-DD) format.
    *
    * @param string $date The date string that will be validated
    * @return boolean Returns true of the specified string is a valid date
    */
    public static function isDate($date) {
        // Remove whitespace
        $date = trim($date);
        
        if(preg_match("'^(\d{4})\-(\d{2})\-(\d{2})$'", $date, $matches)) {
            if((int) $matches[2] > 0 && (int) $matches[2] < 13) {
                if((int) $matches[3] > 0 && (int) $matches[3] < 32) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }
    
    /**
    * Checks if the specified string is a valid time
    *
    * This method checks if the specified string is a valid string of
    * MySql time type. MySql time type is in (HH:MM:SS) format.
    *
    * @param string $date The time string that will be validated
    * @return boolean Returns true of the specified string is a valid time
    */
    public static function isTime($time) {
        // Remove whitespace
        $time = trim($time);
        
        if(preg_match("'^(\d{2}):(\d{2}):(\d{2})$'", $time, $matches)) {
            if((int) $matches[1] >= 0 && (int) $matches[1] < 25) {
                if((int) $matches[2] >= 0 && (int) $matches[2] < 61) {
                    if((int) $matches[3] >= 0 && (int) $matches[3] < 61) {
                        return true;
                    }
                }
            }
        } else {
            return false;
        }
    }
    
    /**
    * Checks if the specified string is a valid datetime
    *
    * This method checks if the specified string is a valid string of
    * MySql datetime type. MySql datetype type is in (YYYY-MM-DD HH:MM:SS) format.
    *
    * @param string $date The datetime string that will be validated
    * @return boolean Returns true of the specified string is a valid datetime
    */
    public static function isDateTime($dateTime) {
        // Remove whitespace
        $dateTime = trim($dateTime);
        
        if(preg_match("'^(\d{4})\-(\d{2})\-(\d{2})\s(\d{2}):(\d{2}):(\d{2})$'",
                      $dateTime, $matches)) {
            
            return (int) $matches[1] >= 1000 && (int) $matches[2] > 0 &&
                   (int) $matches[2] < 13 && (int) $matches[3] > 0 &&
                   (int) $matches[3] < 32 && (int) $matches[4] > 0 &&
                   (int) $matches[4] < 25 && (int) $matches[5] > 0 &&
                   (int) $matches[5] < 61 && (int) $matches[6] > 0 &&
                   (int) $matches[6] < 61;
        } else {
            return false;
        }
    }
    
    /**
    * Checks if the specified string is a valid year
    *
    * This method checks if the specified string is a valid string of
    * MySql year type. MySql year type is in (YYYY) format.
    *
    * @param string $date The year string that will be validated
    * @return boolean Returns true of the specified string is a valid year
    */
    public static function isYear($year) {
        // Remove whitespace
        $year = trim($year);
        
        if(preg_match("'^\d{4}$'", $year)) {
            return (int) $year >= 1000;
        } else {
            return false;
        }
    }

    
    /**
    * Returns if the given text is a text of alpha characters and space.
    *
    * @param string $string String that will be validated
    * @param string $minLength Minimum allowed length for the string
    * @param string $maxLength Maximum allowed length for the string
    * @return boolean Returns true if the specified string is a valid clean text
    */
    public static function isCleanText($string, $minLength = 0, $maxLength = 0) {
        $ret = StringValidator::isValid($string, $minLength, $maxLength,
    "[a-zA-Z[:space:]ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþ`´']+");
        return($ret);
    }
    
    /**
    * Returns if the given text is is formed of word charachters and fits in the
    * required length
    *
    * @param string $string String that will be validated
    * @param string $minLength Minimum allowed length for the string
    * @param string $maxLength Maximum allowed length for the string
    * @return boolean Returns true if the specified string is a valid word
    */
    public static function isWord($string, $minLength = 0, $maxLength = 0) {
        $ret = StringValidator::isValid($string, $minLength, $maxLength, "[A-Za-z0-9_]+");
        return($ret);
    }
    
    /**
    * Validates the given string in terms of the string length and given
    * regular expression
    *
    * @param string $string String that will be validated
    * @param string $minLength Minimum allowed length for the string
    * @param string $maxLength Maximum allowed length for the string
    * @param string $regex The regular expression that the string is going to be
    * validated against
    * @return boolean Returns true if the specified string is valid
    */
    public static function isValid($string, $minLength, $maxLength, $regex) {
        // Check if the string is empty
        $str = trim($string);
        
        // Fix by Jay Kramer
        if($minLength != 0) {
	        if(empty($str)) {
	            return(false);
	        }
        }
        // If the given string does not fit in the regular expression return false;
        if(!eregi("^$regex$", $string)) {
            return(false);
        }
        // Check for the optional length specifiers
        $strLen = strlen($string);
        if (($minLength != 0 && $strLen < $minLength) || ($maxLength != 0 &&
             $strLen > $maxLength)) {
            return(false);
        }
        // Passed all tests
        return(true);
    }
    
    /**
    * Checks the length of the string
    *
    * @param string $string String that will be validated
    * @param string $minLength Minimum allowed length for the string
    * @param string $maxLength Maximum allowed length for the string
    * @return boolean Returns true if the specified string is in the specified
    * length period
    */
    public static function isLengthValid($string, $minLength, $maxLength) {
        // Check if the string is empty
        $str = trim($string);
        
        // Fix by Jay Kramer
        if($minLength != 0) {
	        if(empty($str)) {
	            return(false);
	        }
        }
        // Check for the optional length specifiers
        $strLen = strlen($string);
        if (($minLength != 0 && $strLen < $minLength) || ($maxLength != 0 &&
             $strLen > $maxLength)) {
            return(false);
        }
        // Passed all tests
        return(true);
    }

    /**
    * Checks if the given text is formed of alphabetic characters
    *
    * @param string $string String that will be validated
    * @param string $minLength Minimum allowed length for the string
    * @param string $maxLength Maximum allowed length for the string
    * @return boolean Returns true if the specified string is a valid string
    * of alphabetic characters
    */
    public static function isAlpha($string, $minLength = 0, $maxLength = 0) {
        $ret = StringValidator::isValid($string, $minLength, $maxLength, "[[:alpha:]]+");
        return($ret);
    }
    
    /**
    * Checks if the given text is formed of numeric characters
    *
    * @param string $string String that will be validated
    * @param string $minLength Minimum allowed length for the string
    * @param string $maxLength Maximum allowed length for the string
    * @return boolean Returns true if the specified string is a valid string
    * of numeric characters
    */
    public static function isNumeric($string, $minLength = 0, $maxLength = 0) {
        $ret = StringValidator::isValid($string, $minLength, $maxLength, "[[:digit:]]+");
        return($ret);
    }
}
?>