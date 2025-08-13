<?
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////

  ///////////////////////////////////////////////////
  // TABLEFILE CLASS TO STORE INFO ON FILES
  ///////////////////////////////////////////////////

  class tablefile
  {
    var $VARS;
	var $INDEX;
	var $filename;
	var $sortfield;
	var $sorttype;
	
	// read the data using an include file
	function tablefile( $filename="" )
	{
	  $this->VARS = array();
	  $this->INDEX = array();
	  
	  if( $filename!="" )
	  {
	    $this->read( $filename );
	  }
	}
	
	function read( $filename )
	{
	  if( !file_exists($filename) )
	  {
	    echo( "Unable to find file $filenam}!<br>Please make sure it exists." );
		exit;
	  }
	
	  $this->filename = $filename;
	
	  @include( $filename );
	  
	  if( count($VARS)!=0 )
	  {
	    $this->VARS = $VARS;
	  
	    // create the index and strip the slashes
	    while( list($k,$varray) = each($this->VARS) )
		{
		  $this->INDEX[$varray['id']] = $k;
		  
		  while( list($i,$v) = each($varray) )
		  {
		    $this->VARS[$k][$i] = str_replace( "\'", "'", $v );
		  }
		}
	  }
  
	  $this->isread = true;
	}
	
	function getval( $field, $id=1 )
	{
	  $row = $this->INDEX[$id];
	  return $this->VARS[$row][$field];
	}
	
	function getrow( $id=1 )
	{
	  $row = $this->INDEX[$id];
	  return $this->VARS[$row];
	}
	
	function queryrows( $value, $field )
	{
	  $rows = array();
	
	  reset( $this->VARS );
	
	  foreach( $this->VARS AS $varray )
	  {
	    if( $varray[$field] == $value ) $rows[] = $varray;
	  }
	  
	  return $rows;
	}
	
	function get()
	{
	  reset( $this->VARS );
	  return $this->VARS;
	}
	
	function setval( $value, $field, $id=1 )
	{
	  $row = $this->INDEX[$id];
	  
	  // adding 0 works better with floats than intval()
	  if( is_numeric($value) ) $value = 0 + $value;

	  $this->VARS[$row][$field] = $value;
	}
	
	function setrow( $varray, $id=1 )
	{
	  while( list($field, $value) = each($varray) )
	  {
	    $this->setval( $value, $field, $id );
	  }
	}
	
    function compare( $a, $b )
    {
      if( $a[$this->sortfield] == $b[$this->sortfield] ) return 0;
	  
	  if( $this->sorttype=="asc" )
	    return ( $a[$this->sortfield] < $b[$this->sortfield] ) ? -1 : 1;
	  else
	    return ( $a[$this->sortfield] > $b[$this->sortfield] ) ? -1 : 1;
	}
	
	function sortdata( $field, $type )
	{
	  $this->sortfield = $field;
	  $this->sorttype = $type;

      uasort( $this->VARS, array($this,"compare") );
	}
	
	function deleterow( $id )
	{
	  $row = $this->INDEX[$id];
	  unset( $this->VARS[$row] );
	}
	
	function deletevalue( $field, $id=1 )
	{
	  $row = $this->INDEX[$id];
	  unset( $this->VARS[$row][$field] );
	}
	
	function deleterows( $value, $field )
	{
	  reset( $this->VARS );
	
	  while( list($row,$varray) = each($this->VARS) )
	  {
	    if( $varray[$field] == $value ) unset( $this->VARS[$row] );
	  }
	}
	
	function addrow()
	{
	  $row = 0; $id = 0;
	  
	  // find an empty row
	  while( isset($this->VARS[$row]) ) { $row++; };
	  
	  // find the greatest number for the id
	  reset( $this->VARS );
	  foreach( $this->VARS AS $varray ) { if( $varray['id']>$id ) $id = $varray['id']; }
	  $id += 1;

	  $this->INDEX[$id] = $row;
	  $this->VARS[$row]['id'] = $id; 
	  
	  return $id;
	}
	
	function getnumrows( $value=NULL, $field=NULL )
	{
	  if( $field!==NULL )
	  {
	    reset( $this->VARS );
	    $numrows = 0;
	
	    foreach( $this->VARS AS $varray )
	    {
	      if( $varray[$field] == $value ) $numrows++;
	    }
	  }
	  else
	  {
	    $numrows = count( $this->VARS );
	  }
	
	  return $numrows;
	}
	
	function exists( $id )
	{
	  if( empty($id) ) return false;
	
	  $row = $this->INDEX[$id];
	
	  return isset( $this->VARS[$row] );
	}
	
	function normalize_linebreak( $text )
    {
      $text = str_replace( "\r\n", "\n", $text);
      $text = str_replace( "\r", "\n", $text);
      return $text;
    }
	
	function savedata()
	{
	  if( !isset($this->filename) ) return false;
	
      $fp = fopen( $this->filename, "w" );
	  
	  if( !$fp )
	  {
	    echo( "Unable to write on file {$this->filename}!<br>Please make sure it's write-enabled." );
		exit;
	  }
	  
	  fwrite( $fp, "<?php\r\n\r\n" );
  
  	  reset( $this->VARS );
  
	  while( list($row,$varray) = each($this->VARS) )
	  {
	    while( list($field,$value) = each($varray) )
	    {
		  if( $field=="_matches_" ) continue;
		
		  $value = $this->normalize_linebreak( $value );
		
	      if( is_numeric($value) )
	        fwrite( $fp, "  \$VARS[$row]['".addslashes($field)."'] = $value;\r\n" );
	      else
	        fwrite( $fp, "  \$VARS[$row]['".addslashes($field)."'] = \"".addslashes($value)."\";\r\n" );
	    }
	  }
	  
	  fwrite( $fp, "\r\n?>" );
	  fclose( $fp );
	}
	
    function getnummatches( $keywords, $array )
    {
      $nummatches = 0;
	  
      foreach( $array AS $field )
	  {
	    $field = strtolower( $field );
	
	    foreach( $keywords AS $keyword )
	    {
	      $keyword = strtolower( $keyword );

	      if( strstr($field, $keyword) )
		  {
		    $nummatches++;
		  }
	    }
	  }
  
      return $nummatches;
    }
	
	// search query, group by groupby (default id), sort by nummatches
	// and put in array in the form of matches[groupby] = nummatches
	function search( $query, &$matches, $groupby="id" )
	{
	  reset( $matches );
	  reset( $this->VARS );
	  
	  $keywords = explode( " ", $query );
	  
	  foreach( $this->VARS AS $varray )
	  {
	    $nummatches = $this->getnummatches( $keywords, $varray );
	  
	    if( $nummatches>0 ) 
		{
		  $key = $varray[$groupby];
		
		  if( isset($matches[$key]) ) 
		    $matches[$key] += $nummatches;
		  else
		    $matches[$key] = $nummatches;
		}
	  }
	  
	  // sort matches by nummatches while preserving the
	  // association with the key (we reverse it at the end
	  // since asort put the lowest nummatches first)
	  asort( $matches, SORT_NUMERIC );
	  array_reverse( $matches, true );
	  
	  reset( $matches );
	}
  }
  
  
  ///////////////////////////////////////////////////
  // TABLESQL CLASS TO STORE INFO ON MYSQL
  ///////////////////////////////////////////////////

  class tablesql
  {
    var $VARS;
	var $STATUS;
	
	var $dbcnx;
	var $tablename;
	
	var $sortfield;
	var $sorttype;
	
	function tablesql( $dbcnx, $tablename )
	{ 
	  $this->dbcnx = $dbcnx;
	  
	  $this->VARS = array();
	  $this->STATUS = array();
	  
	  $this->read( $tablename );
	}
	
	function read( $tablename )
	{
	  $results = mysql_query( "SELECT * FROM $tablename", $this->dbcnx );

	  if( !$results ) exit( "Table $tablename not found in database" );

	  $this->tablename = $tablename;

	  while( $entry = mysql_fetch_assoc($results) )
	  {
	    $id = $entry['id'];
	  
	    while( list($field,$value) = each($entry) )
		{
		  $this->VARS[$id][$field] = $value;
		  $this->STATUS[$id] = "unchanged";
		}
	  }
	  
	  $this->isread = true;
	}
	
	function changestatus( $id, $newstatus )
	{
	  // don't change status if already set to added or removed
	  if( $this->STATUS[$id]!="added" && $this->STATUS[$id]!="removed" )
	  {
	    $this->STATUS[$id] = $newstatus;
	  }
	}
	
	function getval( $field, $id )
	{
	  return $this->VARS[$id][$field];
	}
	
	function getrow( $id )
	{
	  return $this->VARS[$id];
	}
	
	function queryrows( $value, $field )
	{
	  $rows = array();
	
	  reset( $this->VARS );
	
	  foreach( $this->VARS AS $varray )
	  {
	    if( $varray[$field] == $value ) $rows[] = $varray;
	  }
	  
	  return $rows;
	}
	
	function get()
	{
	  reset( $this->VARS );
	  return $this->VARS;
	}
	
	function setval( $value, $field, $id=1 )
	{
	  // adding 0 works better with floats than intval()
	  if( is_numeric($value) ) $value = 0 + $value;

	  $this->VARS[$id][$field] = $value;
	  
	  $this->changestatus( $id, "modified" );
	}
	
	function setrow( $varray, $id=1 )
	{
	  while( list($field, $value) = each($varray) )
	  {
	    $this->setval( $value, $field, $id );
	  }
	  
	  $this->changestatus( $id, "modified" );
	}
	
    function compare( $a, $b )
    {
      if( $a[$this->sortfield] == $b[$this->sortfield] ) return 0;
	  
	  if( $this->sorttype=="asc" )
	    return ( $a[$this->sortfield] < $b[$this->sortfield] ) ? -1 : 1;
	  else
	    return ( $a[$this->sortfield] > $b[$this->sortfield] ) ? -1 : 1;
	}
	
	function sortdata( $field, $type )
	{
	  $this->sortfield = $field;
	  $this->sorttype = $type;

      uasort( $this->VARS, array($this,"compare") );
	}
	
	function deleterow( $id )
	{
	  unset( $this->VARS[$id] );
	  $this->changestatus( $id, "deleted" );
	}
	
	function deleterows( $value, $field )
	{
	  reset( $this->VARS );
	
	  while( list($id,$varray) = each($this->VARS) )
	  {
	    if( $varray[$field]==$value ) $this->deleterow( $id );
	  }
	}
	
	function addrow( $id=0 )
	{
          // if no ID defined
          if( $id==0 )
          {
	    // find the greatest number for the id
	    reset( $this->VARS );
	    foreach( $this->VARS AS $varray ) { if( $varray['id']>$id ) $id = $varray['id']; }
	    $id += 1;
	  }

	  $this->VARS[$id]['id'] = $id; 
	  $this->changestatus( $id, "added" );
	  
	  return $id;
	}
	
	function getnumrows( $value=NULL, $field=NULL )
	{
	  if( $field!==NULL )
	  {
	    reset( $this->VARS );
	    $numrows = 0;
	
	    foreach( $this->VARS AS $varray )
	    {
	      if( $varray[$field] == $value ) $numrows++;
	    }
	  }
	  else
	  {
	    $numrows = count( $this->VARS );
	  }
	
	  return $numrows;
	}
	
	function exists( $id )
	{
	  if( empty($id) ) return false;
	
	  return isset( $this->VARS[$id] );
	}
	
	function savedata()
	{
  	  reset( $this->VARS );
  
	  while( list($id,$status) = each($this->STATUS) )
	  {
	    if( $status=="modified" || $status=="added" )
		{
		  // PUT TOGETHER THE VALUES IN THIS ROW
		
		  $setarray = array();
		
		  while( list($field,$value) = each($this->VARS[$id]) )
		  {
		    if( $field=="_matches_" ) continue;
		  
		    $setarray[] = "`" . $field . "` = '" . addslashes($value) . "'";
		  }
		  
		  $setstring = implode( ", ", $setarray );
		
		  // INSERT INTO OR UPDATE ROW
		
		  if( $status=="modified" )
		    mysql_query( "UPDATE ".$this->tablename." SET $setstring WHERE id=$id LIMIT 1" );
		  else
		    mysql_query( "INSERT INTO ".$this->tablename." SET $setstring" );
		}
		elseif( $status=="deleted" )
		{
		  mysql_query( "DELETE FROM ".$this->tablename." WHERE id=$id LIMIT 1" );
		}
	  }
	}
	
    function getnummatches( $keywords, $array )
    {
      $nummatches = 0;
	  
      foreach( $array AS $field )
	  {
	    $field = strtolower( $field );
	
	    foreach( $keywords AS $keyword )
	    {
	      $keyword = strtolower( $keyword );

	      if( strstr($field, $keyword) )
		  {
		    $nummatches++;
		  }
	    }
	  }
  
      return $nummatches;
    }
	
	// search query, group by groupby (default id), sort by nummatches
	// and put in array in the form of matches[groupby] = nummatches
	function search( $query, &$matches, $groupby="id" )
	{
	  reset( $matches );
	  reset( $this->VARS );
	
	  $keywords = explode( " ", $query );
	  
	  foreach( $this->VARS AS $varray )
	  {
	    $nummatches = $this->getnummatches( $keywords, $varray );
	  
	    if( $nummatches>0 ) 
		{
		  $key = $varray[$groupby];
		
		  if( isset($matches[$key]) ) 
		    $matches[$key] += $nummatches;
		  else
		    $matches[$key] = $nummatches;
		}
	  }
	  
	  // sort matches by nummatches while preserving the
	  // association with the key (we reverse it at the end
	  // since asort put the lowest nummatches first)
	  asort( $matches, SORT_NUMERIC );
	  $matches = array_reverse( $matches, true );
	  
	  reset( $matches );
	}
  }

  
  
  ///////////////////////////////////////////////////
  // SMTP CLASS TO SEND EMAILS
  ///////////////////////////////////////////////////
  
  class smtp
  { 
    var $subject, $body, $header;
    var $socket; 
    var $line, $result, $result_txt;
	var $host, $port; 
	var $to_email;
	var $from_email, $from_name;
	
    function smtp( $host="localhost", $port=25 ) 
    {     
	  $this->host = $host;
	  $this->port = $port;
	  
      $this->socket = fsockopen( $host, $port ); 
      if( $this->socket<0 ) return false; 

	  $this->fetch_results();

      if( $this->result <> "2" ) return false; 

      return true; 
    } 
     
    function mail( $to_email, $from_email, $from_name, $subject, $body, $header="" ) 
    { 
      $this->subject = $subject; 
      $this->body = $body;
	  $this->header = $header;
	  $this->to_email = $to_email;
	  $this->from_email = $from_email;
	  $this->from_name = $from_name;

      if( $this->helo() == false ) return false; 
      if( $this->mail_from($from_email) == false ) return false;
      if( $this->rcpt_to($to_email) == false ) return false;
      if( $this->body() == false ) return false;
      if( $this->quit() == false ) return false;
	  
	  return true;
    } 
	
	function fetch_results()
	{
      $this->line = fgets( $this->socket, 1024 ); 

      $this->result = substr( $this->line, 0, 1 ); 
      $this->result_txt = substr( $this->line, 0, 1024 ); 
	}

    function helo() 
    { 
      if( fputs($this->socket, "helo\r\n") < 0 ) return false; 
		
      $this->fetch_results();
	  
	  // if simple helo didn't work, try with hostname
      if( $this->result != "2" )
	  {
	    if( fputs($this->socket, "helo {$this->host}\r\n") < 0 ) return false; 
		
		$this->fetch_results();
		
		if( $this->result != "2" ) return false;
	  }

      return true;   
    } 

    function mail_from( $from_email ) 
    { 
      if( fputs($this->socket, "MAIL FROM: <$from_email>\r\n") < 0 ) return false; 

      $this->fetch_results();

      if ($this->result != "2") return false; 

      return true; 
    } 

    function rcpt_to( $to_email ) 
    { 
      if( fputs($this->socket, "RCPT TO: <$to_email>\r\n") < 0 ) return false; 
        
	  $this->fetch_results();

      if( $this->result != "2" ) return false; 
      
	  return true; 
    } 

    function body() 
    { 
      if( fputs($this->socket, "DATA\r\n") < 0 ) return false; 
		
      $this->fetch_results();

      if( $this->result != "3" ) return false; 
       
	  // output standard header info
	  $buffer = "From: {$this->from_name} <{$this->from_email}>\r\n";
	  $buffer .= "Reply-To: {$this->from_email}\r\n";
	  $buffer .= "To: {$this->to_email}\r\n";
	  $buffer .= "Subject: {$this->subject}\r\n";
	  if( fputs($this->socket, $buffer ) < 0 ) return false; 
	   
	  // output any additional header
      if( fputs($this->socket, $this->header."\r\n") < 0 ) return false; 
        
	  if( fputs($this->socket, $this->body."\r\n\r\n") < 0 ) return false; 
     
	  // add a point to notify of the end
	  if( fputs($this->socket, ".\r\n")<0 ) return false;
	 
	  $this->fetch_results();
	  
      if ( $this->result != "2" ) return false;

      return true; 
    } 

    function quit() 
    { 
      if( fputs($this->socket, "QUIT\r\n") < 0 ) return false; 
		
      $this->fetch_results();

      if( $this->result <> "2" ) return false; 
	  
      return true; 
    }  
	
    function close() 
    { 
      fclose( $this->socket ); 
    } 
  } 
  
?>