<?php

/*
+--------------------------------------------------------------------------
|   > $$MYSQ.PHP
|
|	> Version: 1.0.5
+--------------------------------------------------------------------------
*/

class mysql {

    var $obj = array ( "sql_database"   => ""         ,
                       "sql_user"       => "root"     ,
                       "sql_pass"       => ""         ,
                       "sql_host"       => "localhost",
                       "sql_port"       => ""         ,
                       "persistent"     => "0"        ,
                       "sql_tbl_prefix" => "fd_"    ,
                       "cached_queries" => array()    ,
                     );
                     
     var $query_id      = "";
     var $connection_id = "";
     var $query_count   = 0;
     var $record_row    = array();
     var $return_die    = 0;
     var $error         = "";
                  
    /*========================================================================*/
    // Connect to the database                 
    /*========================================================================*/  
                   
    function connect() {
    
    	if ($this->obj['persistent'])
    	{
    	    $this->connection_id = mysql_pconnect( $this->obj['sql_host'] ,
						   $this->obj['sql_user'] ,
						   $this->obj['sql_pass'] 
						 );
        }
        else
        {
			$this->connection_id = mysql_connect( 
			                                      $this->obj['sql_host'] ,
   						              $this->obj['sql_user'] ,
							      $this->obj['sql_pass'] 
							    );
		}
		
        if ( !mysql_select_db($this->obj['sql_database'], $this->connection_id) )
        {
            echo ("ERROR: Cannot find database ".$this->obj['sql_database']);
        }
    }
    
    
    
    /*========================================================================*/
    // Process a query
    /*========================================================================*/
    
    function query($the_query, $bypass=0) {
    	
    	//--------------------------------------
        // Change the table prefix if needed
        //--------------------------------------
        
        if ($bypass != 1)
        {
			if ($this->obj['sql_tbl_prefix'] != "fd_")
			{
			   $the_query = preg_replace("/fd_(\S+?)([\s\.,]|$)/", $this->obj['sql_tbl_prefix']."\\1\\2", $the_query);
			}
        }
        
        $this->query_id = mysql_query($the_query, $this->connection_id);
      
        if (! $this->query_id )
        {
            $this->fatal_error("MySQL query error: $the_query");
        }
        
		$this->query_count++;
        
        $this->obj['cached_queries'][] = $the_query;
        
        return $this->query_id;
    }
    
    
    /*========================================================================*/
    // Fetch a row based on the last query
    /*========================================================================*/
    
    function fetch_row($query_id = "") {
    
    	if ($query_id == "")
    	{
    		$query_id = $this->query_id;
    	}
    	
        $this->record_row = mysql_fetch_array($query_id, MYSQL_ASSOC);
        
        return $this->record_row;
        
    }

	/*========================================================================*/
    // Fetch the number of rows affected by the last query
    /*========================================================================*/
    
    function get_affected_rows() {
        return mysql_affected_rows($this->connection_id);
    }
    
    /*========================================================================*/
    // Fetch the number of rows in a result set
    /*========================================================================*/
    
    function get_num_rows() {
        return mysql_num_rows($this->query_id);
    }
    
    /*========================================================================*/
    // Return the amount of queries used
    /*========================================================================*/
    
    function get_query_cnt() {
        return $this->query_count;
    }
    
    /*========================================================================*/
    // Free the result set from mySQLs memory
    /*========================================================================*/
    
    function free_result($query_id="") {
    
   		if ($query_id == "") {
    		$query_id = $this->query_id;
    	}
    	
    	@mysql_free_result($query_id);
    }
    
    /*========================================================================*/
    // Shut down the database
    /*========================================================================*/
    
    function close_db() { 
        return mysql_close($this->connection_id);
    }
    
    /*========================================================================*/
    // Basic error handler
    /*========================================================================*/
    
    function fatal_error($the_error) {
    	global $INFO;
    	
    	
    	// Are we simply returning the error?
    	
    	if ($this->return_die == 1)
    	{
    		$this->error = mysql_error();
    		return TRUE;
    	}
    	
    	$the_error .= "\n\nmySQL error: ".mysql_error()."\n";
    	$the_error .= "mySQL error code: ".mysql_errno()."\n";
    	$the_error .= "Date: ".date("l dS of F Y h:i:s A");
    	
    	$out = "<html><head><title>Database Error</title>
    		   <style>P,BODY{ font-family:arial,sans-serif; font-size:11px; }</style></head><body>
    		   &nbsp;<br><br><blockquote><b>There appears to be an error with the database.</b><br>
    		   You can try to refresh the page by clicking <a href=\"javascript:window.location=window.location;\">here</a>, if this
    		   does not fix the error, you can contact the site administrator by clicking <a href='mailto:{$INFO['EMAIL_IN']}?subject=SQL+Error'>here</a>
    		   <br><br><b>Error Returned</b><br>
    		   <form name='mysql'><textarea rows=\"15\" cols=\"60\">".htmlspecialchars($the_error)."</textarea></form><br>We apologise for any inconvenience</blockquote></body></html>";
    		   
    
        echo($out);
        die("");
    }
    
    /*========================================================================*/
    // Create an array from a multidimensional array returning formatted
    // strings ready to use in an INSERT query, saves having to manually format
    // the (INSERT INTO table) ('field', 'field', 'field') VALUES ('val', 'val')
    /*========================================================================*/
    
    function compile_db_insert_string($data) {
    
    	$field_names  = "";
		$field_values = "";
		
		foreach ($data as $k => $v) {
			$v = preg_replace( "/'/", "\\'", $v );
			//$v = preg_replace( "/#/", "\\#", $v );
			$field_names  .= "$k,";
			$field_values .= "'$v',";
		}
		
		$field_names  = preg_replace( "/,$/" , "" , $field_names  );
		$field_values = preg_replace( "/,$/" , "" , $field_values );
		
		return array( 'FIELD_NAMES'  => $field_names,
					  'FIELD_VALUES' => $field_values,
					);
	}
	
	/*========================================================================*/
    // Create an array from a multidimensional array returning a formatted
    // string ready to use in an UPDATE query, saves having to manually format
    // the FIELD='val', FIELD='val', FIELD='val'
    /*========================================================================*/
    
    function compile_db_update_string($data) {
		
		$return_string = "";
		
		foreach ($data as $k => $v) {
			$v = preg_replace( "/'/", "\\'", $v );
			$return_string .= $k . "='".$v."',";
		}
		
		$return_string = preg_replace( "/,$/" , "" , $return_string );
		
		return $return_string;
	}
    
} // end class


?>
