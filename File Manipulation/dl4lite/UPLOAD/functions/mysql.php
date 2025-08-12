<?php

class mysql {

    var $sql_database	= "";
	var $sql_user		= "";
    var $sql_pass		= "";
	var $sql_host		= "localhost";
	var $sql_tbl_prefix = "dl_";
                     
	var $query_id      	= "";
    var $db 			= "";
    var $query_count   	= 0;
	var $query_string 	= array();
	
	var $stayAlive 		= 0;		// Beegees tribute
	var $failed			= 0;

	function mysql( $data )
	{
		$this->sql_host = $data["sqlhost"];
		$this->sql_user = $data["sqlusername"];
		$this->sql_pass = $data["sqlpassword"];
		$this->sql_database = $data["sqldatabase"];
		$this->sql_tbl_prefix = $data["sql_tbl_prefix"];

		$this->db = mysql_connect( $this->sql_host, $this->sql_user ,
								   $this->sql_pass );
	}
	
	function selectDB()
	{
		if ( !mysql_select_db( $this->sql_database, $this->db ) )
            $this->doError ("ERROR: Cannot find database ".$this->sql_database);
	}
	
	function query($the_query) 
	{
		$this->selectDB();
		if ($this->sql_tbl_prefix != "dl_")
		   $the_query = preg_replace("/dl_(\S+?)([\s\.,]|$)/", $this->sql_tbl_prefix."\\1\\2", $the_query);
    	
		$this->query_id = mysql_query($the_query, $this->db);
      
        if (! $this->query_id )
            $this->doError ("mySQL query error: $the_query");    
		
		$this->query_count++;
  		$this->query_string[] = $the_query;
        return $this->query_id;
    }
	
	function insert($the_query, $table)
	{
		$fieldArray = "";
		$valueArray = "";
		// Should create a insert statement from a array
		foreach( $the_query as $field => $value )
		{
			$fieldArray .= "`$field`, ";
			$valueArray .= "'$value', ";
		}
		// trim the last comma
		$fieldArray = substr($fieldArray,0, -2);
		$valueArray = substr($valueArray,0, -2);
		$sql = "INSERT INTO `$table` ( ".$fieldArray." ) VALUES ( ".$valueArray." )";
		
		$this->query_id = $this->query($sql);
  
        return $this->query_id;
	}
	
	function update($the_query, $table, $where)
	{
		$fieldArray = "";
		
		// Should create a insert statement from a array
		foreach( $the_query as $field => $value )
		{
			$fieldArray .= "`$field`=";
			if ( stristr( $value, "$field+" ) || stristr( $value, "$field-" ))
				$fieldArray .= "$value, ";
			else
				$fieldArray .= "'$value', ";
		}
		// trim the last comma
		$fieldArray = substr($fieldArray,0, -2);
		$sql = "UPDATE `$table` SET $fieldArray WHERE $where";
		
		$this->query_id = $this->query($sql);
  
        return $this->query_id;
	}
	
	function fetch_row($query_id = "") 
	{
		$this->selectDB();
    	if ($query_id == "")
    		$query_id = $this->query_id;
        $record_row = mysql_fetch_array($query_id);
        return $record_row;
    }
	
	function field_exists($field, $table) 
	{
		$this->stayAlive = 1;
		
		$this->query("SELECT COUNT($field) as count FROM $table");
		
		$return = true;
		
		if ( $this->failed )
			$return = false;
		
		$this->failed = 0;
		
		return $return;
	}
	
	function num_rows($query_id = "") 
	{
		$this->selectDB();
    	if ($query_id == "")
    		$query_id = $this->query_id;
        $num_rows = mysql_num_rows($this->query_id);
		mysql_error();
		return $num_rows;
    }
	
	function affected_rows() 
	{
		$this->selectDB();
    	return mysql_affected_rows($this->db);
    }
	
	function insert_id() 
	{
		$this->selectDB();
        return mysql_insert_id($this->db);
    }  
	
	function close_db() 
	{ 
		$this->selectDB();
        return mysql_close($this->db);
    }
	
	function doError($the_error) 
	{
		global $OUTPUT, $IN;
		
		$this->failed = 1;
		if ( $this->stayAlive) // Dont crash with an error, just return with blisful ignorance
			return;
			
    	$the_error .= "\n\nmySQL error: ".mysql_error($this->db)."\n";
    	$the_error .= "Date: ".date("l dS of F Y h:i:s A");
		ob_start();
		print_r($IN);
		$inarray = ob_get_contents();
		ob_end_clean();
    	$out = "<html><head><title>RW::Download Database Error</title>
    		   <style>body,blockquote,td,th {
							font-family: Verdana, Arial, Helvetica, sans-serif;
							font-size: 10px;
							color: #000000;
						}
						.errorborder{ 
						background-color: #ff0000; 
						color: #ffffff;
						font-family: Verdana, Arial, Helvetica, sans-serif;
						font-size: 10px;
						padding: 5px; 
						font-weight: bold;
						margin: 10px 20px 10px;
					}</style></head><body>
				<div class='errorborder'>Critical Error</div>
				<blockquote><b>A database error has occoured on {$INFO['site_name']}.</b><br>
    		   You can try to refresh the page by clicking <a href='javascript:window.location=window.location;'>here</a>, if this
    		   does not fix the error, you can contact the board administrator by clicking <a href='mailto:{$INFO['admin_email']}?subject=SQL+Error'>here</a>.<br>
			   If you believe this to be a software bug, please post the information below on the bugtracker <a href='http://www.rwscripts.com/bugtrack/'>here</a>.
    		   <br><br><b>Error Returned</b><br>
    		   <form name='mysql'><textarea rows='15' cols='60'>".htmlspecialchars($the_error)."</textarea></form>
			   <form name='in'><textarea rows='15' cols='60'>{$inarray}</textarea></form><br>We apologise for any inconvenience</blockquote></body></html>";
    		   
    
        echo($out);
		die("");
    }
}
?>