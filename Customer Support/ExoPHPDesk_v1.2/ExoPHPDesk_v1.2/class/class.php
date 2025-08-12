<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk mySQL and Template Class
// >>
// >> CLASS . PHP File - mySQL handler and Template Parser
// >>
// << -------------------------------------------------------------------- >>

class mySQL
{
	// Array Of Db Configurations
	var $db = array ( 	"db_user"  => "",
						"db_pass"  => "",
						"db_host"  => "",
						"db_datab" => "",
						"db_ext"   => ""
						
						);

	// Errors Array
	var $error = array();
	
	// Reset Query Count
	var $count = 0;

	// Reset Connection ID
	var $con = "";
	
	// Total Time By mySQL
	var $exec_time = "";
	
	// Reset Query Results
	var $query_id = "";
	
	// collect queries
	var $query_collect = "";
	
	/*********************************************************************
	*  CONNECT()
	*  Connect to the mySQL server using the information provided.
	*  
	*  Returns errors if any.
	*  @access public
	*********************************************************************/
	function connect()
	{
		// Connect To mySQL Server
		$this->con = mysql_connect($this->db['db_host'], $this->db['db_user'], $this->db['db_pass'])
			OR $this->error($error = 1,"Can't connect to mySQL Server.");

		// Select mySQL Database
		mysql_select_db($this->db['db_datab'], $this->con)
			OR $this->error($error = 1,"Can't select mySQL Database.");
	
	}

	/*********************************************************************
	*  QUERY()
	*  Execute a mySQL query to the current connection handler and replace
	*  the table extension with the right one.
	*  
	*  Returns the query results.
	*  @access public
	*********************************************************************/	
	function query( $sql )
	{
		// Replace Table Extensions If Available
		if ( !empty ( $this->db['db_ext'] ) )
		{
			$sql =  preg_replace ( '/phpdesk_/i', $this->db['db_ext'], $sql );
		}
		
		// start the timer :)
		$start = start_time();
		
		// mySQL Query
		$this->query_id = mysql_query($sql, $this->con) OR $this->error($error = 1, mysql_error());
		
		// end the timer :)
		$end = end_time();
		
		// update total time taken..
		$this->exec_time += $end - $start;
		
		// query collection
		$this->query_collect[] = array($sql, $this->exec_time);
		
		// Increase The Query Count
		$this->count++;

		// Return The Query
		return $this->query_id;
	}

	/*********************************************************************
	*  FETCH()
	*  Simply run the mysql_fetch_array() command to the current connection
	*  handler.
	*  
	*  Returns fetched data array.
	*  @access public
	*********************************************************************/
	function fetch( $query = "" )
	{
		if(! $query )
		{
			$query = $this->query_id;
		}
		
		// start the timer :)
		$start = start_time();
		
		// query..
		$query = mysql_fetch_array($query);
		
		// end the timer :)
		$end = end_time();
		
		// update total time taken..
		$this->exec_time += $end - $start;

		return $query;
	}

	/*********************************************************************
	*  FETCH_R()
	*  Run the mysql_fetch_row() command to the current connection handler
	*  Its slightly different than the FETCH() function.
	*  
	*  Returns fetched row data array.
	*  @access public
	*********************************************************************/	
	function fetch_r( $query = "" )
	{
		if(! $query )
		{
			$query = $this->query_id;
		}
		
		$query = mysql_fetch_row($query);

		return $query;
	}

	/*********************************************************************
	*  NUM()
	*  Execute the num() command to the current connection handler.
	*  
	*  Returns results.
	*  @access public
	*********************************************************************/		
	function num( $query = "" )
	{
		if(! $query )
		{
			$query = $this->query_id;
		}
		
		// start the timer :)
		$start = start_time();
		
		// query..
		$query = mysql_num_rows($query);
		
		// end the timer :)
		$end = end_time();
		
		// update total time taken..
		$this->exec_time += $end - $start;

		return $query;
	}
	
	/*********************************************************************
	*  field_name()
	*  Get the name of the specified field index.
	*  
	*  Returns results.
	*  @access public
	*********************************************************************/		
	function field_name( $offset, $query )
	{
		$name = mysql_field_name( $query, $offset );

		return $name;
	}

	/*********************************************************************
	*  result_fields()
	*  Get the fields names
	*  
	*  Returns results.
	*  @access public
	*********************************************************************/		
	function result_fields( $query=NULL )
	{
		if( !$query )
		{
			$query = $this->query_id;
		}
		
		while( $field = mysql_fetch_field($query) )
		{
			$fields[] = $field;
		}

		return $fields;
	}
	/*********************************************************************
	*  END()
	*  The regular mysql_close() function. This function closes the current
	*  mySQL connection. It isn't required on the modern mySQL servers with
	*  latest PHP. It was a good idea when this class was written though.
	*  
	*  Returns errors if any.
	*  @access public
	*********************************************************************/	
	function end()
	{
		// Close mySQL Connection
		mysql_close($this->con);
	
	}
	
	/*********************************************************************
	*  ERROR()
	*  All the mySQL errors are parsed and listed through this function.
	*  
	*  Returns all mySQL errors.
	*  @access public
	*********************************************************************/	
	function error($error = 0, $quote)
	{
		// If any Errors
		if($error = 1)	
		{
			// List the errors
			echo "Error : $quote";
		
		}
	
	}

} // END OF CLASS

// --------------------------------------------------------------------
// Template Class
//
// It parses all the templates where called and returns them. 
// Main feature is to parse the {VARS} to $VARS.
// --------------------------------------------------------------------

class template 
{
	var $path;
	var $lang;
	var $read;
	var $stuff;
	var $message;

	/*********************************************************************
	*  INCLUDE_FILES()
	*  If any {INCLUDE} tag is found in the file, then it includes the
	*  required files at the place the INCLUDE tag was available.
	*  
	*  CURRENTLY NOT IN USE BY ExoPHPDesk
	*  @access public
	*********************************************************************/
    function include_files( $file_name )
    {

  		if( preg_match('/\{(INCLUDE)\}/Ums', $file_name) )
    	{
			
			// PREPARE SOME TEMPORARY VARS
   			$tmp_op  = strpos($file_name, '{INCLUDE}');

    		$tmp_op2 = strpos($file_name, '{/INCLUDE}');
                
   			$tmp_ops = $tmp_op;
			
    		// $tmp_op =  $tmp_op+7;
			
	    	$totals = $tmp_op2 - $tmp_op ;
			
		    while ( $y <= $totals )
   			{
				
    			$y++; 
                
	    		$tmp_read .= $file_name[(($tmp_op++)-1)];
			
		    }

   			$tmp_read = substr($tmp_read, 10);
			
    		$fps = fopen($tmp_read, 'r');
			
	    	$get_read = fread ( $fps, filesize($tmp_read) );
			
		    fclose($fps);
			
   			$get_it_tmp_1 = substr($file_name, 0, $tmp_ops);

    		$get_it_tmp_2 = substr($file_name, ($tmp_op2+10));
			
	    	$read_it_ok = $get_it_tmp_1.$get_read.$get_it_tmp_2;
			
		    $read_it_ok = str_replace('{INCLUDE}'.$tmp_read.'{/INCLUDE}', '', $read_it_ok);
                
            return $read_it_ok;			
		}

	}
	
	// immunize content against detection of template tags
	function immunize($content) {
		return str_replace(array('{', '}', '#', '<'), array('*ç%', '¨~£', '§°?', '$´`'), $content);
	}
	
	// de-immunize content for output
	function de_immunize($content) {
		return str_replace(array('*ç%', '¨~£', '§°?', '$´`'), array('{', '}', '#', '<'), $content);
	}
	
	/*********************************************************************
	*  PARSE()
	*  This is the actuall template parser which does the variable parsing
	*  i.e. replaces all the {VAR} with $VAR and prepares the output.
	*  
	*  Its the key function for this class.
	*  @access public
	*********************************************************************/
	function parse($path, $lang) 
	{
		
		// FILE HANDLER
		if ( $fp   = fopen( $this->path, 'r' ) )
		{
			// READ THE FILE AND PREPARE A VAR
			$temp = fread($fp, filesize($this->path));
			
			// CLOSE THE HANDLER
			fclose ($fp);
		
		}
		else
		{
			// OUTPUT AN ERROR
			return "..ERROR WITH PARSING..";
					
		}
		
		// Remove everything before Begin
		if(strstr($temp, '[#BEGIN]')) 
		{
			$ps_1 = strpos($temp, '[#BEGIN]');

			$this->read = substr($temp, ($ps_1+8));
		}
		
		if(isset($this->stuff))
		{
			$this->read = str_replace($this->stuff['0'], $this->stuff['1'], $this->read);
		}
		
		// Remove everything after End
		if(strstr($this->read, '[#END]')) 
		{
		
			$ps_1 = strpos($this->read, '[#END]');		
			
			$this->read = substr($this->read, 0, $ps_1);
		
		}
		
		/*
		// CHECK FOR INCLUDES TAGS IN THE TEMPLATE FILE
		while ( preg_match('/\{(INCLUDE)\}/Ums', $this->read) )
		{
			
			$file_name = $this->read;
			
			$stuffs = $this->include_files( $file_name );
            
            $this->read = $stuffs;
			
		}
		*/
        
		// INCLUDE THE FILE IF LANG FILE EXISTS
		if(!empty($this->lang) && file_exists($this->lang)) 
		{

			include($this->lang);

		}
		       
		// Do the required replacing
		// IMPORTANT: avoid that template tags in the included variable contents are 
		//   parsed as well!
		/*
		while ( preg_match ( '/[\{][a-z0-9\_\:\[\]]{0,40}[\}]/i', $this->read, $match ) )
		{
			
            foreach ( $match as $matched )
            {
            	// GET TPL VARIABLE NAME
                $name = substr ( $matched, 1, -1 );
            
            }
			
			// IF ITS AN ARRAY
			if ( preg_match ( '/\[(.*)\]/', $name ) )
			{
				$TMP   =  preg_replace ( '/(.*)\[(.*)\]/', "\\2", $name );
				$NAME  =  preg_replace ( '/\[(.*)\]/', "", $name );
				$this->read  =  str_replace ( '{' . $name . '}', $GLOBALS[$NAME][$TMP], $this->read );
			}
			else
			{
				$this->read = preg_replace ( '/\{' . $name . '\}/', $GLOBALS[$name], $this->read );
			}
		} // END REPLACING LOOP
		*/
		
		$match_array = array();
		$repl_array = array();

		// NOTE: You may nest references if you follow some rules:
		//       - references to globals are allowed as the index of a global array
		//         {some_var[{another_var}]}
		//       - references to globals (arrays and non arrays) are allowed within a function call.
		//         <#some_function({parameters})/#>
		//         or even <#{function_call}/#>      
		
		// search eferences to globals (no arrays)
		preg_match_all('%\{([a-z0-9\_\:]{1,40})\}%i', $this->read, $matches);
		for ($i = 0; $i < count($matches[1]); $i++) {
			$match_array[] = $matches[0][$i];
			$repl_array[] = $this->immunize($GLOBALS[$matches[1][$i]]);
		}

		// insert values
		$this->read = str_replace($match_array, $repl_array, $this->read);
		$match_array = array();
		$repl_array = array();

		// search references to global arrays
		preg_match_all('%\{([a-z0-9\_\:]{1,40})\[([a-z0-9\_]{1,40})\]\}%i', $this->read, $matches);
		for ($i = 0; $i < count($matches[1]); $i++) {
			$match_array[] = $matches[0][$i];
			$repl_array[] = $this->immunize($GLOBALS[$matches[1][$i]][$matches[2][$i]]);
		}

		// insert values
		$this->read = str_replace($match_array, $repl_array, $this->read);
		$match_array = array();
		$repl_array = array();

		/*
		// Do The Functions Stuff
		while( preg_match( '/<#(.*)\((.*)\)\/#>/i', $this->read, $Func ))
		{
			$This = '$This = '. $Func[1] .'(' . $Func[2] .');';
			@eval( $This );
			$this->read = str_replace( $Func[0], $This, $this->read );
		}
		*/

		// search references to functions
		// NOTE: functions are also evaluated within if clauses whose condition is not true!
		preg_match_all('/<#(.*)\((.*)\)\/#>/iU', $this->read, $matches);
		for ($i = 0; $i < count($matches[1]); $i++) {
			$This = '$This = '. $matches[1][$i] .'(' . $matches[2][$i] .');';
			@eval($This);
			$match_array[] = $matches[0][$i];
			$repl_array[] = $this->immunize($This);
		}

		// insert values
		$this->read = str_replace($match_array, $repl_array, $this->read);
		$match_array = array();
		$repl_array = array();

		// search for php sections
		preg_match_all('/<php>(.+?)<\/php>/is', $this->read, $matches);
		for ($i = 0; $i < count($matches[1]); $i++) {
			// start the object buffering! (object buffering is stackable)
			ob_start();
			// eval the code!
			eval( $matches[1][$i] );

			// get the evaled content..
			$This = ob_get_contents();
			
			// end and clean output buffers!
			ob_end_clean();
			
			$match_array[] = $matches[0][$i];
			$repl_array[] = $this->immunize($This);
		}

		// insert values
		$this->read = str_replace($match_array, $repl_array, $this->read);
		$match_array = array();
		$repl_array = array();

		/*
		while( preg_match( '/<php>(.+?)<\/php>/is', $this->read, $Eval ))
		{
			// start the object buffering!
			ob_start();
			// eval the code!
			eval( $Eval[1] );

			// get the evaled content..
			$This = ob_get_contents();
			
			// end and clean output buffers!
			ob_end_clean();
			
			// finally do the replacements!
			$this->read = str_replace( $Eval[0], $This, $this->read );
		}
		*/
		
		// Match some ifs and put it into an array!
		preg_match_all('/<if([0-9]+)>/i', $this->read, $match);
		for($i = 0; $i <= count($match[1]); $i++) {
			$logic_st[] = $match[1][$i];
		}

		// sort the array!
		sort($logic_st, SORT_NUMERIC);
		
		foreach ($logic_st as $G) {
		
		// Run a loop to match all the logic statements..
		if(preg_match('/<if'.$G.'>(.+?)if\((.+?)\)(.+?)\{(.+?)\}(.+?)<\/if'.$G.'>/is', $this->read, $match)) {
			$TRUE = FALSE;
			
			$split = explode(" && ", $match[2]);
			foreach($split as $lg)
			{
				// match some logic stuff..
				$COM  = preg_match( '/^((.*)"(.*)"(.*))(!=|==|<=|>=)(.*)"(.*)"(.*)$/i', $lg, $logic );
				$logic[1] = str_replace($logic[1], $logic[3], $logic[1]);
				
				switch( $logic[5] )
				{

				case '!=':
					if ($logic[1] != $logic[7]) {
						$TRUE = TRUE;
					} else {
						$TRUE = FALSE;
					}
					break;

				case '==':
					if ($logic[1] == $logic[7]) {
						$TRUE = TRUE;
					} else {
						$TRUE = FALSE;
					}
					break;

				case '<=':
					if ($logic[1] <= $logic[7])	{
						$TRUE = TRUE;
					} else {
						$TRUE = FALSE;
					}
					break;
					
				case '>=':
					if ($logic[1] >= $logic[7])	{
						$TRUE = TRUE;
					} else {
						$TRUE = FALSE;
					}
					break;
				}
			}

			// if logic returns true..
			if ($TRUE == TRUE) {
				$this->read = str_replace ( $match[0], $match[4], $this->read );
			} else {
				$this->read = str_replace ( $match[0], NULL, $this->read );
			}
			
		  } 
		} // end logic stuff!
		
		/*// Set A Var		
		$G = 1;
		
		// Do If Stuff
		while ( preg_match( '/<if'. $G .'>(.+?)if\((.+?)\)(.+?)\{(.+?)\}(.+?)<\/if'. $G .'>/is', $this->read, $match ))
		{
			$G++;
			
			$TRUE = FALSE;
			$COM  = preg_match( '/^(.*)(!=|==|<=|>=)(.*)"(.*)"(.*)$/i', $match[2], $logic );
			
			$logic[1] = preg_replace( '/(.*)"(.*)"(.*)/i', "\\2", $logic[1] );

			switch( $logic[2] )
			{

				case '!=':
					if ( $logic[1] != $logic[4] )
					{
						$TRUE = TRUE;
					}
					break;

				case '==':
					if ( $logic[1] == $logic[4] )
					{
						$TRUE = TRUE;
					}
					break;

				case '<=':
					if ( $logic[1] <= $logic[4] )
					{
						$TRUE = TRUE;
					}
					break;
					
				case '>=':
					if ( $logic[1] >= $logic[4] )
					{
						$TRUE = TRUE;
					}
					break;
			
			}

			if( preg_match( '/(.*)&&(.*)"(.*)"(.*)(!=|==|<=|>=)(.*)"(.*)"(.*)/i', $logic[0], $logic2 ))
			{
	
				switch( $logic2[5] )
				{
	
					case '!=':
						if ( $logic2[3] != $logic2[7] )
						{
							$TRUE = TRUE;
						}
						else
						{
							$TRUE = FALSE;
						}
						break;

					case '==':
						if ( $logic2[3] == $logic2[7] )
						{
							$TRUE = TRUE;
						}
						else
						{
							$TRUE = FALSE;
						}					
						break;

					case '<=':
						if ( $logic2[3] <= $logic2[7] )
						{
							$TRUE = TRUE;
						}
						else
						{
							$TRUE = FALSE;
						}					
						break;
					
					case '>=':
						if ( $logic2[3] >= $logic2[7] )
						{
							$TRUE = TRUE;
						}
						else
						{
							$TRUE = FALSE;
						}
						break;
			
				} // End Switch
			}

			if ( $TRUE == TRUE )
			{
				$this->read = str_replace ( $match[0], $match[4], $this->read );
			}
			else
			{
				$this->read = str_replace ( $match[0], NULL, $this->read );
			}
					
		}	*/
		
		// Insert A MaxLength Into All Input Fields	
		$this->read = preg_replace( '/<input(.*)name=/i', "<input\\1maxlength='255' name=", 
										$this->read );
					
		$this->read = $this->de_immunize($this->read);
	} // END OF PARSER FUNCTION
	
} // END OF CLASS

?>