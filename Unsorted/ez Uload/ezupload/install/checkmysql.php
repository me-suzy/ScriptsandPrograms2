<?php
/////////////////////////////////////////////////////////////
// Program Name         : EzUpload Pro                       
// Program Version      : 2.20                                
// Program Author       : ScriptsCenter.com                  
// Supplied by          : CyKuH [WTN]                        
// Nullified and tested : CyKuH [WTN]                        
// Distribution         : via WebForum and Forums File Dumps 
//                    WTN Team `2004
/////////////////////////////////////////////////////////////
  echo( "<br><b>CHECKING MYSQL DATABASE</b><br><br>" );

  echo( "Testing MySQL connection ({$_POST['dbuser']}@{$_POST['dbhost']})... " );
  test( $dbcnx = @mysql_connect( $_POST['dbhost'], $_POST['dbuser'], $_POST['dbpass'] ) );

  if( $numerrors==0 )
  {
	echo( "Searching for '{$_POST['dbname']}' database... " );
	$result = test( @mysql_select_db( $_POST['dbname'] ) );
	  
	if( $numerrors>0 )
	{
	  echo( "Attempting to create '{$_POST['dbname']}' database... " );
	  mysql_query( "CREATE DATABASE {$_POST['dbname']}" );
	  $result = test( @mysql_select_db( $_POST['dbname'] ) );
	  
	  // if database created, remove previous error
	  if( $result ) $numerrors--;
	}
	
	if( $numerrors==0 )
	{ 
	  echo( "Testing CREATE privileges... " );
	  test( mysql_query( "CREATE TABLE dummy ( test tinyint(4) NOT NULL default '0' )" ) );

   	  echo( "Testing ALTER privileges... " );
	  test( mysql_query( "ALTER TABLE dummy ADD test2 tinyint(4) NOT NULL default '0'" ) );

      @mysql_query( "DROP TABLE dummy" );  
    }
  }

?>