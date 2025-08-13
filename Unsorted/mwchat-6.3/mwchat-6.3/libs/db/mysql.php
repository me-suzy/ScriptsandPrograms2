<?PHP

/*
        |: MWChat (My Web based Chat)
        |: Web\HTTP based chat application
        |:
        |: Copyright (C) 2000, 2001, 2002, 2003
        |: Distributed under the terms of license provided.
        |: Available at http://www.appindex.net
        |: Authored by Appindex.net - <support@appindex.net>
*/

function db_connect($szHost, $szPort, $szUser, $szPass, $szDB)
{

  $CONN = mysql_connect("$szHost:$szPort", $szUser, $szPass);

  if ($CONN)

    mysql_select_db($szDB, $CONN);

  return $CONN;

}

function db_pconnect($szHost, $szUser, $szPass, $szDB)
{

  $CONN = mysql_pconnect($szHost, $szUser, $szPass);

  if ($CONN)

    mysql_select_db($szDB, $CONN);

  return $CONN;

}

function db_close($CONN)
{

  return ($CONN) ? mysql_close($CONN) : TRUE;

}

function db_query($szSQL, $CONN)
{

  return ($CONN) ? mysql_query($szSQL, $CONN) : FALSE;

}

function db_numrows($szResult)
{

  return mysql_num_rows($szResult);

}

function db_seek($szResult, $row)
{

  return ($szResult) ? mysql_data_seek($szResult, $row) : FALSE;

}

function db_fetch($szResult)
{

  return mysql_fetch_array($szResult, MYSQL_ASSOC);

}

function db_cleanup($szResult)
{

  return ($szResult) ? mysql_free_result($szResult) : TRUE;

}

function db_get_all($szSQL, $szKey, $CONN)
{

  $szResult = db_query($szSQL, $CONN);

  $retArr = array();

  while ($row = db_fetch($szResult))
  {

    $id = $row[$szKey];

    unset ($row[$szKey]);

    while (list($title,$val) = each($row))

      $retArr[$id][$title] = $val;

  }

  db_cleanup($szResult);

  return $retArr;

}

function db_error($CONN)
{

  return ($CONN) ? mysql_error($CONN) : FALSE;

}

function db_last_id($CONN, $szResult, $szTable)
{

  return ($CONN) ? mysql_insert_id($CONN) : FALSE;

}

function db_start_trans($CONN)
{

  return FALSE;  // Mysql does not support transactions.

}

function db_commit($CONN)
{

  return FALSE;  // Mysql does not support transactions.

}

function db_rollback($CONN)
{

  return FALSE;  // Mysql does not support transactions.

}

?>
