<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 19th August 2005                        #||
||#     Filename: xmlBackup.php                          #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package Backup
*/

if (!defined('wbnews'))
	die ("Hacking Attempt");

/*  
    Not great OO concepts here since the constructor does all the work and gets the methods
    an easier way would have been just creating functions instead of organizing them 
    in a class. However we needed an easy quick way without using a main function to do it. 
    
    Also since its the Administration Panel it wont get executed all that much, we dont need
    to worry about speed and optimization as much, as the main site.
*/
class xmlBackup extends backup
{
    
    var $code;
    var $tab = "    ";
    
    /**
        @access public
        @param Array config - The Config Array with Database Properties
    */
    function xmlBackup($config)
    {
        // we create a new Database Connection, instead of getting the current one.
        $this->DB($config['dbhost'], base64_decode($config['dbname']), base64_decode($config['dbuser']), base64_decode($config['dbpass']));
        $this->db_connect();
        $this->code = $this->xmlHeader();
        $this->code .= $this->xmlGetData();
        $this->code .= $this->xmlFooter();
    }
    
    /**
        @access public static
        @return string - format
    */
    function backupFormat()
    {
        return "xml";
    }
    
    /**
        @access private
        @return String
    */
    function xmlHeader()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<!DOCTYPE wbsml PUBLIC "-//Webmobo//DTD WBSML 1.0//EN" "/home/paul/public_html/wbnews_development/template-testing/admin/xml/wbsml.dtd">'."\n".'<wbsml application="wbnews">'."\n";
    }
    
    /**
        @access private
    */
    function xmlGetData()
    {
        $tables = $this->listTables();
        $numSize = sizeof($tables);
        
        for ($i = 0; $i < $numSize; $i++)
        {
            $this->code .= $this->tab.'<table name="'.$tables[$i].'">'."\n";
            
            $records = $this->getRecords($tables[$i]);
            $recordSize = sizeof($records);
            // 
            for ($j = 0; $j < $recordSize; $j++)
            {
                if (is_array($records[$j]))
                {
                    $this->code .= str_repeat($this->tab, 2) . '<record>' . "\n";
                    foreach ($records[$j] as $key => $value)
                    {
                        
                        $value = stripslashes($value);
                        
                        if ((preg_match("/<([^>])+>/is", $value) === 1) || (preg_match("/(&gt;|&lt;)/is", $value) === 1))
                            $this->code .= str_repeat($this->tab, 3) . "<field fieldname=\"".$key."\"><![CDATA[" . $value . ']]></field>'."\n"; 
                        else
                           $this->code .= str_repeat($this->tab, 3) . "<field fieldname=\"".$key."\">". $value . '</field>'."\n";
                    }
                    $this->code .= str_repeat($this->tab, 2) . '</record>' . "\n";
                }
            }   
            $this->code .= $this->tab.'</table>'."\n";
        }
    }
    
    /**
        @access private
        @return String
    */
    function xmlFooter()
    {
        return '</wbsml>';
    }
    
    /**
        @access public
        @return String
    */
    function toString()
    {
        return $this->code;
    }
    
}

?>
