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
 File: db.mysql.php
-----------------------------------------------------
 Purpose: SQL database abstraction: MySQL
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


//---------------------------------------    
//    DB Cache Class
//---------------------------------------

// This object gets serialized and cached.
// It provides a simple mechanism to store queries
// that are portable as objects

class DB_Cache {

    var $result   = array();
    var $row      = array();
    var $num_rows = 0;
    var $q_count  = 0;
}
// END CLASS



//---------------------------------------    
//	DB Class
//---------------------------------------

class DB {

    // Public variables

    var $hostname       	= 'localhost';
    var $username      	 	= 'root';
    var $password      	 	= '';
    var $database       	= '';
    var $prefix         	= 'exp_';       // Table prefix
    var $conntype       	= 1;            // 1 = persistent.  0 = non
    var $cache_dir      	= 'db_cache/';  // Cache directory/path with trailing slash.
    var $debug          	= 0;            // Manually turns on debugging
    var $enable_cache   	= TRUE;         // true/false Enables query caching
    var $array_result   	= MYSQL_ASSOC;  // Options: MYSQL_BOTH  MYSQL_ASSOC  MYSQL_NUM


    // Private variables. 

    var $exp_prefix     	= 'exp_';
    var $cache_path     	= '';
    var $cache_file     	= '';
    var $sql_table      	= '';
    var $insert_id      	= '';
    var $q_count        	= 0;
    var $affected_rows  	= 0;
    var $instantiated   	= FALSE;
    var $conn_id        	= FALSE;
    var $query_id       	= FALSE;
    var $fetch_fields   	= FALSE;
    var $field_names    	= array();
    var $show_queries		= FALSE;		// Enables queries to be shown for debugging
    var $queries			= array();		// Stores the queries


    // ---------------------------------------    
    //	Constructor
    // ---------------------------------------    

    function DB($settings)
    {
        global $PREFS;
        
		$db_settings = array(
								'hostname', 
								'username',
								'password',
								'database',
								'conntype',
								'prefix',
								'debug',
								'show_queries',
								'enable_cache'
							);
       
		foreach ($db_settings as $item)
		{
			if (isset($settings[$item]))
			{
				$this->$item = $settings[$item];
			}
		}
                
		if ( ! ereg("_$", $this->prefix))
		{
            $this->prefix .= '_';
		}
        
        if ($this->enable_cache == TRUE)
        {
			$this->cache_dir = PATH_CACHE.$this->cache_dir; 
			
			if ( ! ereg('/$', $this->cache_dir))
			{
				$this->cache_dir .= '/';
			}
        }
    }
    // END


    // ---------------------------------------    
    //	Connect to database
    // ---------------------------------------    
    
    function db_connect($select_db = TRUE)
    {    
        $this->conn_id = ($this->conntype == 0) ?
          @mysql_connect ($this->hostname, $this->username, $this->password):
          @mysql_pconnect($this->hostname, $this->username, $this->password);
        
        if ( ! $this->conn_id)
        {
            if ($this->debug)
            {
                return("Unable to connect to the database.");
            }
            
            return false;        
        }
        
        if ($select_db == TRUE)
        {
        	if ( ! $this->select_db())
        	{
        		return false;	
        	}
        }
        
        return true;
    }
    // END


    //---------------------------------------    
    // Select database
    //---------------------------------------

    function select_db()
    {
        if ( ! @mysql_select_db($this->database, $this->conn_id))
        {
            if ($this->debug)
            {
                return $this->db_error("MySQL Error: Unable to select database: ".$this->database);
            }
            
            return false;
        }
        
        return true;
	}
	// END


    //---------------------------------------    
    // Close database connection
    //---------------------------------------

    function db_close()
    {
        if ($this->conn_id)
            mysql_close($this->conn_id);
    }         
    // END
    

    // ---------------------------------------    
    //	DB Query
    // ---------------------------------------
    
    function query($sql)
    {       
        // Store the query for debugging
        
        if ($this->show_queries == TRUE)
        {
        	$this->queries[] = $sql;
        }
           
        // Verify table prefix and replace if necessary.
            
        if ($this->prefix != $this->exp_prefix)
        { 
           $sql = preg_replace("/".$this->exp_prefix."(\S+?)/", $this->prefix."\\1", $sql);
        }
                
        if ($this->enable_cache == TRUE)
        {
        	global $IN;
        
			// The URI being requested will become the name of the cache directory
					
			$this->cache_path = ($IN->URI == '') ? $this->cache_dir.md5('index').'/' : $this->cache_path = $this->cache_dir.md5($IN->URI).'/';
	
					
			// Convert the SQL query into a hash.  This will become the cache file name.
		
			$this->cache_file = md5($sql);
	
		
			// Is this query a read type?  
			// If so, return the previously cached data if it exists and bail out.
			
			if (stristr($sql, 'SELECT'))
			{
				if (FALSE !== ($cache = $this->get_cache()))
				{
					return $cache;
				}
			}
		}
        
        // Connect to the DB if we haven't done so on a previous query
        
        if ( ! $this->conn_id)    
        {
            $this->db_connect();
        }

        // Execute the query
                
        if ( ! $this->query_id = mysql_query($sql, $this->conn_id))
        {
            if ($this->debug)
            {
                return $this->db_error("MySQL ERROR:", $this->conn_id, $sql);
            }
          
          return false;
        }


        // Increment the query counter
        
        $this->q_count ++;
        

        // Determine if the query is one of the 'write' types. If so, gather the
        // affected rows and insert ID, and delete the existing cache file.

        $qtypes = array('INSERT', 'UPDATE', 'DELETE', 'CREATE', 'ALTER', 'DROP');
                
        foreach ($qtypes as $type)
        {
            if (eregi("^$type", $sql))
            {  
                $this->affected_rows = mysql_affected_rows($this->conn_id);
                
                if ($type == 'INSERT')
                {
                    $this->insert_id = mysql_insert_id($this->conn_id);
                }
                
                // Delete the cache file since the data in it is no longer current.
                
                if ($this->enable_cache == TRUE)
                {
                    $this->delete_cache();
                }
                
                return;  // Bail out.  We are done
            }
        }
        
        
        // Fetch the field names, but only if explicitly requested
        // We use this in our SQL utilities functions
        
        if ($this->fetch_fields == TRUE)
        { 
            $this->field_names = array();
            
            while ($field = mysql_fetch_field($this->query_id))
            {
                $this->field_names[] = $field->name;       
            }
         }
                

        // Fetch the result of the query and assign it to an array.
        // I know, the result *is* an array.  But we want our own
        // numerically indexed array so we can cache it.
            
        $i = 0;
        
        while ($row = mysql_fetch_array($this->query_id, $this->array_result)) 
        {                                    
            $result[$i] = $row;
            
            $i++;
        }
        
        
        // Free the result.  Optional with MySQL, but might as well be thorough
        
        mysql_free_result($this->query_id);


        // Instantiate the cache super-class and assign the data 
        // to it if a subsequent query hasn't already done so
        
        if ($this->instantiated == FALSE)
        {
            $DBC = &new DB_Cache;
        
            $this->instantiated = TRUE;    
        }
        
            $DBC->result   = &$result;
            $DBC->row      = &$result['0'];
            $DBC->num_rows = $i;
        
                
        // Serialize the class and store it in a cache file
        
        if ($this->enable_cache == TRUE)
        {
            $this->store_cache(serialize($DBC));
        }
            
        // Assign the query count to the super-class.  
        // The query count only applies to non-cached queries,
        // so we add it after the class has already been cached.
        
        $DBC->q_count = &$this->q_count;
        $DBC->fields  = &$this->field_names;
        
        // Return it    
        return $DBC;        
    }
    // END


    // ---------------------------------------    
    //	Fetch SQL tables
    // ---------------------------------------

    function fetch_tables()
    {            
        $tables  = mysql_list_tables($this->database);
        $results = mysql_numrows($tables);

        $rows = array();

        $i = 0;
                
        while ($i < $results) 
        {
            $row = mysql_tablename($tables, $i);
        
            if (ereg("^$this->prefix", $row))
            {
                $rows[] = $row;
            }
            
            $i++;   
        }
        
        return $rows;
    }
    // END
    

    // ---------------------------------------    
    //  Cache a query
    // ---------------------------------------    

    function store_cache($object)
    {       
        if ( ! @is_dir($this->cache_path))
        {
            if ( ! @mkdir($this->cache_path, 0777))
            { 
                return;
            }
            
            @chmod($this->cache_path, 0777);            
        }

        if ( ! $fp = @fopen($this->cache_path.$this->cache_file, 'wb'))
            return;

        flock($fp, LOCK_EX);
        fwrite($fp, $object);
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    // END


    // ---------------------------------------    
    //	Retreive a cached query
    // ---------------------------------------    

    function get_cache()
    {            
        if ( ! @is_dir($this->cache_path))
            return false;    
        
        if ( ! file_exists($this->cache_path.$this->cache_file))
            return false;
        
        if ( ! $fp = @fopen($this->cache_path.$this->cache_file, 'rb'))
            return false;

        flock($fp, LOCK_SH);
        
        $cachedata = @fread($fp, filesize($this->cache_path.$this->cache_file));
        
        flock($fp, LOCK_UN);
        
        fclose($fp);
        
        return unserialize($cachedata);            
    }
    // END
    

    // ---------------------------------------    
    //	Delete cache files
    // ---------------------------------------    

    function delete_cache()
    {    
        if ( ! @is_dir($this->cache_path))
            return;
    
        if ( ! $fp = @opendir($this->cache_path)) 
        { 
            return $this->db_error("Unable to open cache directory");
        } 
        
        while (false !== ($file = @readdir($fp))) 
        {
             if ($file != "."  AND  $file != "..")
             {
                if ( ! @unlink($this->cache_path.$file))
                {
                    return $this->db_error("Error: Unable to delete the following file: ".$file);
                }
            }
        }
                
        closedir($fp); 
    }
    // END



    // ---------------------------------------    
    //	MySQL escape string
    // ---------------------------------------    

    function escape_str($str)    
    {    
        return mysql_escape_string(stripslashes($str));
    }
    // END
    

    // ---------------------------------------    
    //	Error Message
    // ---------------------------------------    
    
    function db_error($msg, $id="", $sql="") 
    {    
        if ($id) 
        { 
            $msg .= "<br /><br />";
            $msg .= "Error Number: " . mysql_errno($id);
            $msg .= "<br /><br />";
            $msg .= "Description: "  . mysql_error($id);
        }
        
        if ($sql)
            $msg .= "<br /><br />Query: ".$sql;
            
        exit($msg);
    }    
  
  
    // ---------------------------------------    
    //	Write an INSERT string
    // ---------------------------------------    

    // This function simplifies the process of writing database inserts.  
    // It returns a correctly formatted SQL insert string.
    //
    // Example:
    //
    //  $data = array('name' => $name, 'email' => $email, 'url' => $url);
    //
    //  $str = $DB->insert_string('exp_weblog', $data);
    //
    //  Produces:  INSERT INTO exp_weblog (name, email, url) VALUES ('Joe', 'joe@joe.com', 'www.joe.com')

    function insert_string($table, $data)
    {
        $fields = '';      
        $values = '';
        
        foreach($data as $key => $val) 
        {
            $fields .= $key . ', ';
            $values .= "'".$this->escape_str($val)."'".', ';
        }
        
        $fields = preg_replace( "/, $/" , "" , $fields);
        $values = preg_replace( "/, $/" , "" , $values);

        return 'INSERT INTO '.$table.' ('.$fields.') VALUES ('.$values.')';
    }    
    // END


    // ---------------------------------------    
    //	Write an UPDATE string
    // ---------------------------------------    

    // This function simplifies the process of writing database updates.  
    // It returns a correctly formatted SQL update string.
    //
    // Example:
    //
    //  $data = array('name' => $name, 'email' => $email, 'url' => $url);
    //
    //  $str = $DB->update_string('exp_weblog', $data, "author_id = '1'");
	//
    //  Produces:  UPDATE exp_weblog SET name = 'Joe', email = 'joe@joe.com', url = 'www.joe.com' WHERE author_id = '1'

    function update_string($table, $data, $where)
    {
        if ($where == '')
            return false;
    
        $str  = '';
        $dest = '';
        
        foreach($data as $key => $val) 
        {
            $str .= $key." = '".$this->escape_str($val)."', ";
        }

        $str = preg_replace( "/, $/" , "" , $str);
        
        if (is_array($where))
        {
            foreach ($where as $key => $val)
            {
                $dest .= $key." = '".$this->escape_str($val)."' AND ";
            }
            
            $dest = preg_replace( "/AND $/" , "" , $dest);
        }
        else
            $dest =& $where;

        return 'UPDATE '.$table.' SET '.$str.' WHERE '.$dest;        
    }    
    // END


}
// END CLASS
?>