<?
/////////////////////////////////////////////////////////////
// Program Name         : Autolinks Professional            
// Program Version      : 2.0                               
// Program Author       : ScriptsCenter                     
// Supplied by          : CyKuH [WTN] , Stive [WTN]         
// Nullified by         : CyKuH [WTN]                       
// Distribution         : via WebForum and Forums File Dumps
//                   (c) WTN Team `2002
/////////////////////////////////////////////////////////////

  function checkurl( $url )
  {
    if( substr($url, -9) == "index.php" ) $url = ereg_replace( "index.php", "", $url );
    if( substr($url, -9) == "index.htm" ) $url = ereg_replace( "index.htm", "", $url );
    if( substr($url, -10) == "index.html" ) $url = ereg_replace( "index.html", "", $url );
    if( substr($url, -1) != "/" ) $url .= "/";

    return $url;
  }

  function splitsqlfile( &$ret, $sql )
  {
    $sql               = trim($sql);
    $sql_len           = strlen($sql);
    $char              = '';
    $string_start      = '';
    $in_string         = FALSE;

    for ($i = 0; $i < $sql_len; ++$i) {
        $char = $sql[$i];

        // We are in a string, check for not escaped end of strings except for
        // backquotes that can't be escaped
        if ($in_string) {
            for (;;) {
                $i         = strpos($sql, $string_start, $i);
                // No end of string found -> add the current substring to the
                // returned array
                if (!$i) {
                    $ret[] = $sql;
                    return TRUE;
                }
                // Backquotes or no backslashes before quotes: it's indeed the
                // end of the string -> exit the loop
                else if ($string_start == '`' || $sql[$i-1] != '\\') {
                    $string_start      = '';
                    $in_string         = FALSE;
                    break;
                }
                // one or more Backslashes before the presumed end of string...
                else {
                    // ... first checks for escaped backslashes
                    $j                     = 2;
                    $escaped_backslash     = FALSE;
                    while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                        $escaped_backslash = !$escaped_backslash;
                        $j++;
                    }
                    // ... if escaped backslashes: it's really the end of the
                    // string -> exit the loop
                    if ($escaped_backslash) {
                        $string_start  = '';
                        $in_string     = FALSE;
                        break;
                    }
                    // ... else loop
                    else {
                        $i++;
                    }
                }
            }
        }

        // We are not in a string, first check for delimiter...
        else if ($char == ';') {
            // if delimiter found, add the parsed part to the returned array
            $ret[]      = substr($sql, 0, $i);
            $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
            $sql_len    = strlen($sql);
            if ($sql_len) {
                $i      = -1;
            } else {
                // The submited statement(s) end(s) here
                return TRUE;
            }
        }

        // ... then check for start of a string,...
        else if (($char == '"') || ($char == '\'') || ($char == '`')) {
            $in_string    = TRUE;
            $string_start = $char;
        }

        // ... for start of a comment (and remove this comment if found)...
        else if ($char == '#'
                 || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
            // starting position of the comment depends on the comment type
            $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
            // if no "\n" exits in the remaining string, checks for "\r"
            // (Mac eol style)
            $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
                              ? strpos(' ' . $sql, "\012", $i+2)
                              : strpos(' ' . $sql, "\015", $i+2);
            if (!$end_of_comment) {
                // no eol found after '#', add the parsed part to the returned
                // array and exit
                $ret[]   = trim(substr($sql, 0, $i-1));
                return TRUE;
            } else {
                $sql     = substr($sql, 0, $start_of_comment)
                         . ltrim(substr($sql, $end_of_comment));
                $sql_len = strlen($sql);
                $i--;
            }
        }

    }

    // add any rest to the returned array
    if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
        $ret[] = $sql;
    }

    return TRUE;
  }

  function loadsqlfile( $filename, $description )
  {
    echo( $description . "... " );
  
    $numerrors = 0;
	
	$fp = fopen( $filename, "r" );
	$queries = fread( $fp, filesize($filename) );
	fclose( $fp );
	
	$query = array();
	
	splitsqlfile( $query, $queries );
	
	while( list($k,$v) = each($query) )
	{
	  $result = mysql_query( $v );
	  
	  if( !$result )
	  {
	    echo( "Could not execute: $v<br>" );
	    $numerrors++;
	  }
	}
	
	if( $numerrors==0 )
	{
	  echo( "OK!<br>" );
	}
	
	return $numerrors;
  }
  
  // try to create, alter and drop a dummy table
  function test_privileges()
  {
    $numerrors = 0;
  
	echo( "Testing CREATE privileges... " );
    $result = mysql_query( "CREATE TABLE al_dummy ( test tinyint(4) NOT NULL default '0' ) TYPE=MyISAM" );

	if( !$result )
	{
	  echo( "Failed!<br>" );
	  $numerrors++;
	}
	else
	{
	  echo( "OK<br>" );
	}
  
  
    echo( "Testing ALTER privileges... " );
    $result = mysql_query( "ALTER TABLE al_dummy ADD test2 tinyint(4) NOT NULL default '0'" );

	if( !$result )
	{
	  echo( "Failed!<br>" );
	  $numerrors++;
	}
	else
	{
	  echo( "OK<br>" );
	}
	
    @mysql_query( "DROP TABLE al_dummy" );  
	
	return $numerrors;
  }
  
?>