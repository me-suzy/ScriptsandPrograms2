<?php

//------------------------------------------------------------------------
// This script is a part of ABC eStore
//------------------------------------------------------------------------


//
// Special includes

require_once ("LockFile.php");
require_once ("xlparser/excelparser.php");

$lockFile = new LockFile( 'xlupload/xlupload.lock' );

//
// Utility functions

function abcPageMessage( $msg, $color )
{
	echo <<<ERROR
		<li><font color="$color">$msg</font></li>
ERROR;
}

function abcPageError( $msg )
{
	abcPageMessage( $msg, "red" );
}

function abcPageActionComplete( $msg )
{
	abcPageMessage( $msg, "blue" );
}

function abcHandleParserError( $rc )
{
	switch ($rc)
	{
		case 0: break;
		case 1: abcPageError($lng[913]);
		case 2: abcPageError($lng[914]);
		case 3: abcPageError($lng[915]);
		case 4: abcPageError($lng[916]);
		case 5: abcPageError($lng[917]);
		case 6: abcPageError($lng[918]);
		case 7: abcPageError($lng[919]);
		case 8: abcPageError($lng[920]);
	
		default:
			abcPageError($lng[921]);
	}
}

//
// Locking/unlocking helper functions

function abcStartLock( $file )
{
	global $lockFile;
	
	if( file_exists( 'xlupload/uploaded.xls' ) 
		&& !@unlink( 'xlupload/uploaded.xls' ) )
	{
		abcPageError($lng[922]);
		return false;
	}
	
	if( !move_uploaded_file( $file['tmp_name'],'xlupload/uploaded.xls' ) )
	{
		abcPageError($lng[923]);
		return false;
	}

	if( !$lockFile->IsLocked() && !$lockFile->Lock() )
	{
		@unlink( 'xlupload/uploaded.xls' );
		abcPageError($lng[924]);
		return false;
	}
	
	if( !abcParseWorksheets() )
	{
		abcPageError($lng[925]);
		return false;
	}
	
	return true;
}

function uc2html( $str )
{
	$ret = '';
	for( $i=0; $i<strlen($str)/2; $i++ )
	{
		$charcode = ord($str[$i*2])+256*ord($str[$i*2+1]);
		$ret .= '&#'.$charcode;
	}
	return $ret;
}

function abcLoadWorksheets()
{
	if( file_exists( 'xlupload/worksheets' ) ) {
		
		$ws_file = fopen ("xlupload/worksheets", "r");
		while (!feof ($ws_file)) {
			$buffer = fgets($ws_file);
			
			if (!empty($buffer))
			$ws_name[] = explode (" ", $buffer);
			
		}


		return $ws_name;
			
	}
		
	else
		return array();
}

function abcParseWorksheets()
{	
	$exc = new ExcelFileParser("debug.log", ABC_NO_LOG );
	$res = $exc->ParseFromFile( 'xlupload/uploaded.xls' );
	if( $res != 0 )
	{
		abcHandleParserError( $res );
		return false;
	}
	
		// Getting worksheet information
		
		$ws_file = fopen ("xlupload/worksheets", "w");				
		
		for( $ws_num=0; $ws_num<count($exc->worksheet['name']); $ws_num++ ) {	

		if( $exc->worksheet['unicode'][$ws_num] ) { 
		$wsname = uc2html($exc->worksheet['name'][$ws_num]); } else 
		$wsname = $exc->worksheet['name'][$ws_num];
				
		$ws = $exc->worksheet['data'][$ws_num];
		
		if( !is_array($ws) || !isset($ws['max_row']) || !isset($ws['max_col']) ) 
			$succ = fwrite ($ws_file, $wsname . " 0 " . $ws_num . "\n");
		else 	$succ = fwrite ($ws_file, $wsname . " 1 " . $ws_num . "\n");
			
		}
		
		fclose ($ws_file);
		
		
		
	return true;
}

function abcRemoveLock()
{
	global $lockFile;
	
	if( $lockFile->Unlock() )
	{
		$succ = 1;
		
		if( file_exists( 'xlupload/uploaded.xls' ) )
		{
			@unlink( 'xlupload/uploaded.xls' );
			
		} else $succ = 0;

		
		if( file_exists( 'xlupload/worksheets' ) )
		{
			@unlink( 'xlupload/worksheets' );
			
		} else $succ = 0;
		
		
		return $succ;
	
	}
	
	return false;
}

?>