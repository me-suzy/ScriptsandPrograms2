<?
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.0                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : Stive [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2002
/////////////////////////////////////////////////////////////

  class tablefile
  {
    var $VARS;
	var $INDEX;
	var $filename;
	var $sortfield;
	var $sorttype;
	
	// read the data using an include file
	function tablefile( $filename )
	{
	  $this->filename = $filename;
	
	  @include( $filename );
	  
	  $this->VARS = $VARS;
	  
	  if( count($this->VARS)==0 )
	  {
	    $this->VARS = array();
		$this->INDEX = array();
	  }
	  else
	  {
	    // create the index
	    while( list($k,$v) = each($this->VARS) )
		{
		  $this->INDEX[$v['id']] = $k;
		}
	  }
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
	  if( is_numeric($value) ) $value = intval( $value );
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

      usort( $this->VARS, array($this,"compare") );
	  
	  // recreate the index
	  reset( $this->VARS );
	  while( list($k,$v) = each($this->VARS) )
	  {
		$this->INDEX[$v['id']] = $k;
	  }
	}
	
	function deleterow( $id )
	{
	  $row = $this->INDEX[$id];
	  unset( $this->VARS[$row] );
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
	
	function savedata()
	{
  	  reset( $this->VARS );
	
      $fp = fopen( $this->filename, "w" );
	  fwrite( $fp, "<?php\n\n" );
  
	  while( list($row,$varray) = each($this->VARS) )
	  {
	    while( list($field,$value) = each($varray) )
	    {
	      if( is_numeric($value) )
	        fwrite( $fp, "\$VARS[$row]['$field'] = $value;\n" );
	      else
	        fwrite( $fp, "\$VARS[$row]['$field'] = \"$value\";\n" );
	    }
	  }
	
	  fwrite( $fp, "\n?>" );
	  fclose( $fp );
	}
  }
  
?>