<?php

// date_format.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Giampaolo Panto, $Author: paolo $
// $Id: date_format.php,v 1.1 2005/07/26 14:54:21 paolo Exp $

// check whether lib.inc.php has been included
if (!defined('lib_included')) die('Please use index.php!');


class PHProjekt_Date_Format
{

    /**
    * The available date formats
    * @var array
    */
    var $_formats;

    /**
    * The date format stored in the db
    * @var string
    */
    var $_db_format;

    /**
    * The date format of the current user
    * @var string
    */
    var $_user_format;

    /**
    * The default (fallback) date format
    * @var string
    */
    var $_default_format;


    /**
    * Constructor
    *
    * Define here more date formats:
    * 'text/description' => 'php date() format'
    *
    * @param string $user_format  the date format of the user (eg. "dd-mm-yyyy")
    *
    */
    function PHProjekt_Date_Format($user_format='')
    {
        $this->_db_format = 'Y-m-d';
        $this->_formats = array( 'dd.mm.yyyy' => 'd.m.Y'
                                ,'mm/dd/yyyy' => 'm/d/Y'
                                ,'yyyy-mm-dd' => 'Y-m-d'
                               );
        // default format must be defined in $this->_formats !!!
        $this->_default_format = 'yyyy-mm-dd';

        if (array_key_exists($user_format, $this->_formats)) {
            $this->_user_format = $user_format;
        } else {
            $this->_user_format = $this->_default_format;
        }
    }


    /**
    * Get the available date formats
    *
    * @param bool $as_values  true to get the array keys as values
    *
    * @return array  the available date formats
    */
    function get_formats($as_values=false)
    {
        if ($as_values) {
            return array_keys($this->_formats);
        } else {
            return $this->_formats;
        }
    }


    /**
    * Get the date format of the user
    *
    * @return string  the date format of the user (eg. "dd-mm-yyyy")
    */
    function get_user_format()
    {
        return $this->_user_format;
    }


    /**
    * Check if the given date is valid (according to the date format of the user)
    *
    * @param string $date  the date which should be checked
    *
    * @return mixed  $date as a string on a valid date or false on error
    */
    function check_date($date)
    {
        return $this->convert_date($date, $this->_formats[$this->_user_format], $this->_formats[$this->_user_format]);
    }


    /**
    * Converts the given date from the user format to the db format ("yyyy-mm-dd")
    *
    * @param string $date  the date which should be converted into the db format
    *
    * @return mixed  the converted date as a string or false on error
    */
    function convert_user2db($date)
    {
        return $this->convert_date($date, $this->_formats[$this->_user_format], $this->_db_format);
    }


    /**
    * Converts the given date from the db format ("yyyy-mm-dd") to the user format
    *
    * @param string $date  the date which should be converted into the user format
    *
    * @return mixed  the converted date as a string or false on error
    */
    function convert_db2user($date)
    {
        return $this->convert_date($date, $this->_db_format, $this->_formats[$this->_user_format]);
    }


    /**
    * Common method to convert date formats
    *
    * @param string $input_date     the date which should be converted
    * @param string $input_format   the input date format
    * @param string $output_format  the output date format
    *
    * @return mixed  the converted date as a string or false on error
    */
    function convert_date($input_date, $input_format, $output_format)
    {
        preg_match("/^([\w]*)/i", $input_date, $regs);

        $sep   = substr($input_date, strlen($regs[0]), 1);
        $label = explode($sep, $input_format);
        $value = explode($sep, $input_date);

        if (count($label) != count($value)) {
            return false;
        }

        $date = array();
        for ($ii=0; $ii<count($label); $ii++) {
            $date[$label[$ii]] = $value[$ii];
        }

        if (in_array('Y', $label)) {
            $year = $date['Y'];
        } else if (in_array('y', $label)) {
            $year = $date['y'];
        } else {
            return false;
        }

        if (!checkdate(((int) $date['m']), ((int) $date['d']), ((int) $year))) {
            return false;
        }

        $output = date($output_format, mktime(0,0,0, $date['m'], $date['d'], $year));
        return $output;
    }

}

?>
