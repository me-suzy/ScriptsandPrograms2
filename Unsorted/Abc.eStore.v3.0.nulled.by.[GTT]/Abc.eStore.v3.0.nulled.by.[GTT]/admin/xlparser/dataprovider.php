<?php

//------------------------------------------------------------------------
// ABC Excel Parser Pro (DataProvoider class)
//
// Version: 3.0
// PHP compatibility: 4.3.x
//
//------------------------------------------------------------------------

require_once("debug.php");

define ( DP_EMPTY, 			0 );
define ( DP_STRING_SOURCE, 	1 );
define ( DP_FILE_SOURCE, 	2 );

//------------------------------------------------------------------------

class ExcelParserUtil
{
	function str2long($str) {
		return ord($str[0]) + 256*(ord($str[1]) +
			256*(ord($str[2]) + 256*(ord($str[3])) ));
	}
}

//------------------------------------------------------------------------

class DataProvider
{
	function DataProvider( $data, $dataType )
	{
		switch( $dataType )
		{
		case DP_FILE_SOURCE:
			if( !( $this->_data = @fopen( $data, "rb" )) ) return;
			$this->_size = @filesize( $data );
			if( !$this->_size ) _die("Failed to determine file size.");
			break;
		case DP_STRING_SOURCE:
			$this->_data = $data;
			$this->_size = strlen( $data );
			break;
		default:
			_die("Invalid data type provided.");
		}
		
		$this->_type = $dataType;		
		register_shutdown_function( array( $this, "close") );
	}
	
	function get( $offset, $length )
	{
		if( !$this->isValid() )
			_die("Data provider is empty.");
		if( $this->_baseOfs + $offset + $length > $this->_size )
			_die("Invalid offset/length.");
			
		switch( $this->_type )
		{
		case DP_FILE_SOURCE:
		{
			if( @fseek( $this->_data, $this->_baseOfs + $offset, SEEK_SET ) == -1 )
				_die("Failed to seek file position specified by offest.");
			return @fread( $this->_data, $length );
		}
		case DP_STRING_SOURCE:
		{
			$rc = substr( $this->_data, $this->_baseOfs + $offset, $length );
			return $rc;
		}
		default:
			_die("Invalid data type or class was not initialized.");
		}
	}
	
	function getByte( $offset )
	{
		return $this->get( $offset, 1 );
	}
	
	function getOrd( $offset )
	{
		return ord( $this->getByte( $offset ) );
	}
	
	function getLong( $offset )
	{
		$str = $this->get( $offset, 4 );
		return ExcelParserUtil::str2long( $str );
	}
	
	function getSize()
	{
		if( !$this->isValid() )
			_die("Data provider is empty.");
		return $this->_size;
	}
	
	function getBlocks()
	{
		if( !$this->isValid() )
			_die("Data provider is empty.");
		return (int)(($this->_size - 1) / 0x200) - 1;
	}
	
	function ReadFromFat( $chain, $gran = 0x200 )
	{
		$rc = '';
		for( $i = 0; $i < count($chain); $i++ )
			$rc .= $this->get( $chain[$i] * $gran, $gran );
		return $rc;
	}
	
	function close()
	{
		switch($this->_type )
		{
		case DP_FILE_SOURCE:
			@fclose( $this->_data );
		case DP_STRING_SOURCE:
			$this->_data = null;
		default:
			$_type = DP_EMPTY;
			break;
		}
	}
	
	function isValid()
	{
		return $this->_type != DP_EMPTY;
	}
	
	var $_type = DP_EMPTY;
	var $_data = null;
	var $_size = -1;
	var $_baseOfs = 0;
}

//------------------------------------------------------------------------

?>