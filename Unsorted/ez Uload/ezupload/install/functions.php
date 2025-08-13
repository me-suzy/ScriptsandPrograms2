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
  function test( $results )
  {
    global $numerrors;
	
	if( $results )
	{
	  echo( "<b>OK</b><br>" );
	}
	else
	{
	  echo( "<font color='red'><b>Failed</b></font><br>" );
	  $numerrors++;
	}
	
	return $results;
  } 
  
  function adduploadinfo( $uploadid, $name, $value )
  {
    global $UPLOADINFO;
	
	$infoid = $UPLOADINFO->addrow();
	$UPLOADINFO->setval( $uploadid, "upload", $infoid );
	$UPLOADINFO->setval( $name, "name", $infoid );
	$UPLOADINFO->setval( $value, "value", $infoid );
  }

  function splitsqlfile( &$ret, $sql )
  {
    $sql = trim( $sql );
    $sql_len = strlen( $sql );
    $char = '';
    $string_start = '';
    $in_string = false;

    for( $i=0; $i<$sql_len; ++$i )
	{
      $char = $sql[$i];

      if( $in_string )
	  {
        for(;;) 
		{
          $i = strpos($sql, $string_start, $i);
				
          if( !$i )
		  {
            $ret[] = $sql;
            return true;
          }
          elseif( $string_start == '`' || $sql[$i-1] != '\\' )
		  {
            $string_start = '';
            $in_string = false;
            break;
          }
          else
		  {
            $j = 2;
            $escaped_backslash = false;
					
            while( $i-$j>0 && $sql[$i-$j]=='\\' )
			{
               $escaped_backslash = !$escaped_backslash;
               $j++;
            }
					
            if ($escaped_backslash)
			{
              $string_start  = '';
              $in_string = false;
              break;
            }
            else
			{
              $i++;
            }
          }
        }
      }
      elseif( $char == ';' )
	  {
        $ret[] = substr($sql, 0, $i);
        $sql = ltrim(substr($sql, min($i + 1, $sql_len)));
        $sql_len = strlen($sql);
            
	    if ($sql_len)
          $i = -1;
		else
          return true;
      }
      elseif( ($char=='"') || ($char=='\'') || ($char=='`') )
	  {
        $in_string = true;
        $string_start = $char;
      }
      elseif( $char=='#' || ( $char==' ' && $i>1 && $sql[$i-2] . $sql[$i-1]=='--' ) )
	  {
        $start_of_comment = ( ($sql[$i]=='#') ? $i : $i-2 );
			
        $end_of_comment = (strpos(' ' . $sql, "\012", $i+2))
                        ? strpos(' ' . $sql, "\012", $i+2)
                        : strpos(' ' . $sql, "\015", $i+2);
							  
        if (!$end_of_comment)
		{
          $ret[]   = trim(substr($sql, 0, $i-1));
          return true;
        }
		else
		{
          $sql = substr($sql, 0, $start_of_comment) . ltrim(substr($sql, $end_of_comment));
          $sql_len = strlen($sql);
          $i--;
        }
      }
    }

    if( !empty($sql) && ereg('[^[:space:]]+', $sql) )
	{
      $ret[] = $sql;
    }

    return true;
  }

  function loadsqlfile( $filename )
  {
    $numerrors = 0;
    $queries = array();
  
	$fp = fopen( $filename, "r" );
	$queryfile = fread( $fp, filesize($filename) );
	fclose( $fp );
	
	splitsqlfile( $queries, $queryfile );
	
	foreach( $queries AS $query )
	{
	  $result = mysql_query( $query );
	  if( !$result ) return false;
	}
	
	return true;
  }
  
  // convert tablefile to tablemysql
  // the mysql table must already exists
  function convertfiletosql( $TABLEFILE, $tablename )
  {
    global $dbcnx;
	
	$TABLESQL = new tablesql( $dbcnx, $tablename );
	
	$rows = $TABLEFILE->get();
	
	foreach( $rows AS $row )
	{
          // add a row with the ID
	  $id = $row['id'];
          $TABLESQL->addrow( $id );

	  // add the values except ID
	  $TABLESQL->setrow( $row, $id );
	}
	
	return $TABLESQL;
  }
  
?>