<?php

/*========================================================*\
||########################################################||
||#                                                      #||
||#     WB News v1.0.0                                   #||
||# ---------------------------------------------------- #||
||#     Copyright (c) 2004-2005                          #||
||#     Created: 15th June 2005                          #||
||#     Filename: db_mysql.php                           #||
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

define("LIB_DB", true);
    
class DB
{

/** @var string private */
var $dbhost = null;
var $dbname = null;
var $dbuser = null;
var $dbpass = null;

/** @var resource private */
var $linkid = null;
	
	/**
		Database Abstration Layer Constructor
        Initializers object variables Database Host, Database Name, Username and Password
		
        @access public
        @param string $dbhost Database Host e.g. Localhost
        @param string $dbuser Database Username
        @param string $dbpass Database Password
        @param string $dbname Database Name
        @return void
    */
	function DB($dbhost, $dbname, $dbuser, $dbpass)
	{
		$this->dbhost = $dbhost;
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
        register_shutdown_function(array( &$this, "db_close" )); //get ready to close the MySQL Connection
        return; 
	}
	
	/**
		Connects to the Database
	
        @access public
        @param void
        @return mixed
    */
    function db_connect()
    {
        $this->linkid = @mysql_connect($this->dbhost ,$this->dbuser,$this->dbpass);
        if (!$this->linkid)
            $this->db_error('db_connect', '', 'connect');
        else
        {
            $select = @mysql_select_db($this->dbname, $this->linkid);
            if (!$select)
                $this->db_error('db_connect', '', 'select');
        }
    }
	
	/**
        Queries the Database
    
        @access public
        @param string $query - A SQL Query
        @return mixed
    */
    function db_query($query)
    {
        if (!($result = @mysql_query($query, $this->linkid)))
            $this->db_error('db_query', $query);
        else
            return $result;
    }
    
    /**
        @access public
        @param resource $resource - MySQL Query Resource
        @return mixed
    */
    function db_fetcharray($resource)
    {
        $array = @mysql_fetch_assoc($resource);
        if (!is_array($array))
            return false;
        else
            return $array;
    }
	
    /**
         @access public
         @param String query - an SQL Query
         @param Mixed key - if set the key to be used to use for the Array
         @param String value - if set the value of the only field to be returned
    */
	function db_fetchall($query, $key = false, $value = '')
	{
		$query = $this->db_query($query);
		if ($this->db_numrows($query))
		{
			$array = array();
            if ($key === false)
			    while ($rows = mysql_fetch_assoc($query))
				    $array[] = $rows;
            else
                while ($rows = mysql_fetch_assoc($query))
                    $array[$rows[$key]] = (!empty($value) ? $rows[$value] : $rows);
            
			return (sizeof($array) != 0 ? $array : false);
		}
		else
			return false;
	}
    
    /**
        @access public
        @param resource $resource - MySQL Query Resource
        @return mixed
    */
    function db_numrows($resource)
    {
        $rows = @mysql_num_rows($resource);
        if ($rows === false)
            $this->db_error('db_numrows');
        else
        {
            if ($rows === 0)
                return false;
            else
                return $rows;
        }
    }
	
	/**
		Returns true or false for Query rows > 0
        @access public
        @param string $query - Query
        @return mixed
    */
	function db_checkRows($query)
	{
		if ($query = $this->db_query($query))
		{
			if ($this->db_numrows($query) != 0)
				return true;
			else
				return false;
		}
		else
			return false;
	}
	
	/**
		Returns the last generated ID
        @access public
        @return int
    */
	function db_insertid()
	{
		return mysql_insert_id($this->linkid);
	}
    
    function db_affectedrows()
    {
        return mysql_affected_rows($this->linkid);
    }
	
	function db_error($method, $query = '', $type = '')
	{
		if ($type){}
		echo "<strong>".$method."</strong><hr />";
		echo '<pre>'.$query.'</pre>';
		if (defined('DEBUG') && DEBUG)
			echo '<blockquote>'.mysql_error().'</blockquote>';
		exit;
	}
	
	/**
        Deconstructor - Closes the MySQL Connection and destroys object variables
        
        @access private
        @param void
        @return void
    */
    function db_close()
    {
        $this->dbhost = null;
        $this->dbuser = null;
        $this->dbname = null;
        $this->dbpass = null;
        if ($this->linkid !== null)
        {
            if (!@mysql_close($this->linkid))
                die ("Database Error: Couldnt Close Database Connection");
        }
    }
}

?>
