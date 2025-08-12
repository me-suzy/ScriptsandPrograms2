<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 19th August 2005                        #||
||#     Filename: sqlBackup.php                          #||
||#                                                      #||
||########################################################||
/*========================================================*/

/**
	@author Paul Mcilwaine - Webmobo
	@version 1.0
	@package AdminCP
*/

if (!defined('wbnews'))
	die ("Hacking Attempt");
  
class sqlBackup extends backup
{
    
    var $code;
    var $wbVersion;
    
    /**
        @access public
        @param Array config - The Config Array with Database Properties
    */
    function sqlBackup($config)
    {
        $this->wbVersion = $config['version'];
        $this->DB($config['dbhost'], base64_decode($config['dbname']), base64_decode($config['dbuser']), base64_decode($config['dbpass']));
        $this->db_connect();
        $this->code = $this->sqlHeader();
        $this->code .= trim($this->sqlTable());
    }
    
    /**
        @access private
        @return String
    */
    function sqlHeader()
    {
        return "-- WBNews SQL Dump\n-- version " . $this->wbVersion . "\n-- http://webmobo.com\n--\n-- Host: ". $this->dbhost . "\n--" .
               " Time Generated: " . date("M d, Y \a\\t h:i A") . "\n-- Server Version: " . $this->getVersion() . "\n-- " .
               "PHP Version: " . phpversion() . "\n--\n-- Database: `".$this->dbname."`\n--\n\n";
    }
    
    /**
        @access private
        @return String
    */
    function sqlTable()
    {
        $string = "";
        
        $tableList = $this->listTables();
        $numTables = sizeof($tableList);
        
        for ($i = 0; $i < $numTables; $i++)
        {
            $string .= "--\n-- Table structure for `" . $tableList[$i] . "`".
                       "\n--\n\n";
                       
            $string .= "CREATE TABLE `" . $tableList[$i] . "` (\n";
            // get fields and keys
            $fields = $this->listTableFields($tableList[$i]);
            $numFields = sizeof($fields);
            
            for ($j = 0; $j < $numFields; $j++)
            {
                $string .= "   `" . $fields[$j]['Field'] . "` " . $fields[$j]['Type'];
                $string .= (empty($fields[$j]['Null']) || $fields[$j]['Null'] == 'No' ? " NOT NULL" : "");
                $string .= ($fields[$j]['Extra'] != 'auto_increment' && $fields[$j]['Type'] != 'text' ? " default '".$fields[$j]['Default']. "'" : "");
                $string .= (!empty($fields[$j]['Extra']) ? " " . $fields[$j]['Extra'] : "");
                $string .= ",\n";
            }
            
            // get keys
            $string .= $this->sqlKeys($tableList[$i]);
            
            $string .= ");\n\n";
            
            $string .= $this->sqlData($tableList[$i]);
        }
        
        return $string;
        
    }
    
    /**
        @access private
        @param String table - The Table name to get Records From
        @return String
    */
    function sqlData($table)
    {
        $string = "";
        
        $string .= "--\n-- Dumping data for table `".$table."`\n--\n";
        
        $records = $this->getRecords($table);
        $numRecords = sizeof($records);
        
        if (is_array($records[0]))
        {
            for ($i = 0; $i < $numRecords; $i++)
            {
                $string .= "INSERT INTO `".$table."` VALUES (";
            
                if (is_array($records[$i]))
                {
                    $j = 1;
                    $numFields = sizeof($records[$i]);
                    foreach ($records[$i] as $key => $value)
                    {
                        if (empty($value))
                            $string .= "'', ";
                        else if (is_numeric($value))
                            $string .= $value . ", ";
                        else
                            $string .= "'" . str_replace(array("\n", "\r", "'"), array("\\n", "\\r", "\\\'"), addslashes($value)) . "', ";
                            
                        $j++;
                    }
                    
                    $string = substr($string, 0, -2);
                    
                }
                $string .= ");\n";
                
            }
        }
        
        $string .= "\n-- --------------------------------------------------------\n\n";
        
        return $string;
    }
    
    /**
        @access private
        @param String table - The Table name to get Records From
        @return String
    */
    function sqlKeys($table)
    {
        $keys = $this->getTableKeys($table);
        $numKeys = sizeof($keys);
        
        $primaryKey = "";
        $uniqKey = array();
        $fulltextKey = array();
        $indexKeys = array();
        
        // SHOW KEYS FROM testing
        for ($i = 0; $i < $numKeys; $i++)
        {
            if ($keys[$i]['Key_name'] == "PRIMARY")
            {
                // primary key
                $primaryKey = $keys[$i]['Column_name'];
            }
            else if ($keys[$i]['Non_unique'] == 0)
            {
                // unique key
                if (isset($uniqKey[$keys[$i]['Key_name']]))
                    array_push($uniqKey[$keys[$i]['Key_name']], $keys[$i]['Column_name']);
                else
                    $uniqKey[$keys[$i]['Key_name']] = array($keys[$i]['Column_name']);
            }
            else if ($keys[$i]['Non_unique'] == 1 && $keys[$i]['Index_type'] == "FULLTEXT")
            {
                // fulltext key
                if (isset($fulltextKey[$keys[$i]['Key_name']]))
                    array_push($fulltextKey[$keys[$i]['Key_name']], $keys[$i]['Column_name']);
                else
                    $fulltextKey[$keys[$i]['Key_name']] = array($keys[$i]['Column_name']);
            }
            else
            {
                // index key
                if (isset($indexKeys[$keys[$i]['Key_name']]))
                    array_push($indexKeys[$keys[$i]['Key_name']], $keys[$i]['Column_name']);
                else
                    $indexKeys[$keys[$i]['Key_name']] = array($keys[$i]['Column_name']);
            }
        }
        
        $string = "";
        $string .= (!empty($primaryKey) ? "   PRIMARY KEY (`".$primaryKey."`),\n" : "");
        
        
        // unqiue key
        foreach ($uniqKey as $key => $value)
        {
            $string .= "   UNIQUE KEY `" . $key. "` (";
            
            $numUni = sizeof($value);
            for ($i = 0; $i < $numUni; $i++)
                $string .= "`".$value[$i]."`, ";
            
            // remove (, ) without ()
            $string = substr($string, 0, -2);
            $string .= "),\n";
        }
        
        // index
        foreach ($indexKeys as $key => $value)
        {
            $string .= "   KEY `" . $key. "` (";
            
            $numUni = sizeof($value);
            for ($i = 0; $i < $numUni; $i++)
                $string .= "`".$value[$i]."`, ";
            
            // remove (, ) without ()
            $string = substr($string, 0, -2);
            $string .= "),\n";
        }
        
        // full text
        foreach ($fulltextKey as $key => $value)
        {
            $string .= "   KEY `" . $key. "` (";
            
            $numUni = sizeof($value);
            for ($i = 0; $i < $numUni; $i++)
                $string .= "`".$value[$i]."`, ";
            
            // remove (, ) without ()
            $string = substr($string, 0, -2);
            $string .= "),\n";
        }
        
        return substr($string, 0, -2) . "\n";
    }
    
    /**
        @access public static
        @return string - format
    */
    function backupFormat()
    {
        return "sql";
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
