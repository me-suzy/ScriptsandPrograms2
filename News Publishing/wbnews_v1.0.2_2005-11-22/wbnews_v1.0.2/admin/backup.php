<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 19th August 2005                        #||
||#     Filename: backup.php                             #||
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
    
class backup extends DB
{
    
    /**
        @access private
        @return String - The Database Version Used
    */
    function getVersion()
    {
        $version = $this->db_fetcharray($this->db_query("SELECT version() as v"));
        return $version['v'];
    }
    
    /**
        @access private
        @return Array
    */
    function listTables()
    {
        $tbls = $this->db_fetchall("SHOW TABLES");
        $numSize = sizeof($tbls);
        $array = array();
        
        for ($i = 0; $i < $numSize; $i++)
            $array[] = $tbls[$i]['Tables_in_' . $this->dbname];
        
        return $array;
    }
    
    /**
        @access private
        @param String table - The name of the Table
        @return Array - An Array of Fields
    */
    function listTableFields($table)
    {
        return $this->db_fetchall("SHOW FIELDS FROM `". $table."`");
    }
    
    /**
        @access private
        @param String table - The name of the Table
        @return Array - An Array of Field Keys
    */
    function getTableKeys($table)
    {
        return $this->db_fetchall("SHOW KEYS FROM `". $table."`");
    }
    
    /**
        @access private
        @param String table - The name of the Table
        @return Array - A List of records from the table
    */
    function getRecords($table)
    {
        return $this->db_fetchall("SELECT * FROM `". $table."`");
    }

}
    
?>
