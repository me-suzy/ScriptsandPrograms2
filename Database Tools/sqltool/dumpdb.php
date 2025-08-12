<?php
require_once("DB.php");
//edit these lines with your connection details
if (@$HTTP_POST_VARS["dumpToFile"]) {
  header("Content-type: application/sql");
  header("Content-Disposition: attachment; filename=\"$dbname.sql\"");
} else {
  header("Content-type: text/plain");
  header("Content-Disposition: inline; filename=\"$dbname.sql\"");
}

// Fetch connection/dump parameters, with appropriate defaults
$dbpasswd = @$HTTP_POST_VARS["dbpasswd"];
$dbhost = (! empty($HTTP_POST_VARS["dbhost"])) ? $HTTP_POST_VARS["dbhost"]
    : "localhost";
$dbname = @$HTTP_POST_VARS["dbname"];
$dbuser = @$HTTP_POST_VARS["dbuser"];
$dbtype = (! empty($HTTP_POST_VARS["dbtype"])) ? $HTTP_POST_VARS["dbtype"]
    : (function_exists("mssql_connect") ? "mssql" : "mysql");
$targetType = (! empty($HTTP_POST_VARS["targetType"]))
     ? $HTTP_POST_VARS["targetType"] : $dbtype;
$dumpTablesPattern = @$HTTP_POST_VARS["dumpTablesPattern"];
$addReconnect = @$HTTP_POST_VARS["addReconnect"];
$reconnectInverval = max(5, @$HTTP_POST_VARS["reconnectInterval"]);
$addDropTables = @$HTTP_POST_VARS["addDropTables"];
$addFulltextIndex = @$HTTP_POST_VARS["addFulltextIndex"];

// Establish connection
$db = DB::connect(
    array(
      "phptype" => $dbtype,
      "hostspec" => $dbhost,
      "username" => $dbuser,
      "password" => $dbpasswd,
      "database" => $dbname),
    array(
      "autofree" => true
    ));

if (DB::isError($db)) {
  die("SQL error: ".$db->getDebugInfo()."\n");
}
$db->setFetchMode(DB_FETCHMODE_ASSOC);
if ($targetType==$dbtype) {
  $tdb = $db;
} else {
  $tdb = DB::factory($targetType);
}
// PEAR DB library Bug workaround
// DB::mysql::quoteSmart() does not work without a connection
$qdb = ($targetType=="mysql" ? $db : $tdb);

$prepSql = array();
$postSql = array();
if ($targetType=="mssql") {
  $prepSql[] = "SET DATEFORMAT ymd";
  $prepSql[] = "SET ANSI_NULLS ON";
  $prepSql[] = "SET ANSI_NULL_DFLT_ON ON";
  $prepSql[] = "SET ANSI_PADDING ON";
  //  $postSql[] = "COMMIT TRAN";
}
if ($dbtype=="mssql") {
  $db->query("SET DATEFORMAT ymd");
}

function printQuery($query) {
  // This function outputs a single query
  // It automatically inserts 'RECONNECT' every $reconnectInterval queries,
  // and ensures every batch of queries starts with $prepSql and ends with
  // $postSql
  global $prepSql, $postSql, $targetType, $addReconnect, $reconnectInterval;
  static $countQueries;
  if ($query=="END") {
    // We've reached the end of the dump
    print "\n";
    if (count($postSql) >0) {
      print join(";\n", $postSql).";\n";
    }
    return;
  }
  if (@$addReconnect && $countQueries+count($postSql)>=$reconnectInterval) {
    // We should issue a RECONNECT command
    print "\n";
    if (count($postSql) >0) {
      print join(";\n", $postSql).";\n";
    }
    print "RECONNECT;\n";
    $countQueries = 0;
  }
  if (empty($countQueries) && count($prepSql) >0) {
    // Either we just RECONNECTed or we're just starting: either way, do Prep
    print join(";\n", $prepSql).";\n\n";
    $countQueries = count($prepSql);
  }
  // Print the query
  $countQueries++;
  print "$query;\n";
}

function mapType($colType) {
  global $targetType, $dbtype;
  $colType = strtoupper($colType);
  if ($targetType=="mssql") {
    switch($colType) {
      case "MEDIUMINT":
        return "INT";
      case "TIMESTAMP":
        return ($dbtype=="mysql" ? "DATETIME" : "TIMESTAMP");
      case "DATE":
      case "TIME":
      case "YEAR":
        return "DATETIME";
      case "BLOB":
      case "TINYBLOB":
      case "MEDIUMBLOB":
      case "LONGBLOB":
        return "TEXT";
      case "TEXT":
      case "TINYTEXT":
      case "MEDIUMTEXT":
      case "LONGTEXT":
        return "TEXT";
      case "ENUM":
      case "STRING":
      case "CHAR":
        return "VARCHAR";
    }
  } elseif ($targetType=="mysql") {
    switch($colType) {
      case "BIT":
        return "TINYINT";
      case "MONEY":
        return "BIGINT";
      case "SMALLMONEY":
        return "INT";
      case "SMALLDATETIME":
        return "DATETIME";
      case "NTEXT":
        return "TEXT";
      case "BINARY":
      case "VARBINARY":
        return "BLOB";
      case "IMAGE":
        return "LONGBLOB";
      case "STRING":
      case "CHAR":
        return "VARCHAR";
    }
  }
  return $colType;
}


function typeDefault($colType) {
  global $targetType, $dbtype, $qdb;
  switch($colType) {
    case "INT":
    case "TINYINT":
    case "SMALLINT":
    case "MEDIUMINT":
    case "BIGINT":
    case "FLOAT":
    case "DOUBLE":
    case "DECIMAL":
    case "NUMERIC":
    case "BIT":
    case "MONEY":
    case "SMALLMONEY":
      return $qdb->quoteSmart(0);
    case "DATETIME":
    case "SMALLDATETIME":
      return $targetType=="mssql" ? "CURRENT_TIMESTAMP" : "";
    case "VARCHAR":
    case "CHAR":
    case "TEXT":
    case "NATIONAL CHAR":
    case "NATIONAL VARCHAR":
    case "NATIONAL TEXT":
    case "BINARY":
    case "VARBINARY":
    case "IMAGE":
    case "TINYBLOB":
    case "TINYTEXT":
    case "BLOB":
    case "TEXT":
    case "MEDIUMBLOB":
    case "MEDIUMTEXT":
    case "LONGBLOB":
    case "LONGTEXT":
    case "ENUM":
      return  $qdb->quoteSmart("");
    default:
      return "";
  }
}

// Get list of tables
switch($dbtype) {
  case "mssql":
    $dbres = $db->query("EXEC sp_tables @table_type=\"'TABLE'\"");
    if (DB::isError($dbres)) {
       die("SQL error: ".$dbres->getDebugInfo()."\n");
    }
    $tblNames = array();
    while($dbres->fetchInto($tblrec)) {
      if ($tblrec["TABLE_NAME"] != "dtproperties") {
        $tblNames[] = $tblrec["TABLE_NAME"];
      }
    }
    break;
  case "mysql":
    $tblNames = $db->getCol("SHOW TABLES");
    if (DB::isError($tblNames)) {
       die("SQL error: ".$tblNames->getDebugInfo()."\n");
    }
    break;
  default:
    die("Unknown database type $dbtype");
}


// Dump each table
$constraintNames=array();
foreach($tblNames as $tblName) {
  // fetch info about table to write the CREATE command for this table
  $tblInfo = $db->tableInfo("$tblName");
  $tblHasIncrementingKey = 0;
  $colDefinitions = array();
  $colList = array();
  $multikeys = array();
  $datefields = array();
  $tblNameQuoted = $tdb->quoteIdentifier($tblName);
  $primaryConstraint = "";
  foreach($tblInfo as $colInfo) {
    // get info about column
    $colName = $colInfo["name"];
    $colList[] = $tdb->quoteIdentifier($colName);
    $colType = mapType($colInfo["type"]);
    $colLength = $colInfo["len"];
    $colAllowsNull = ! preg_match("/not_null/i", $colInfo["flags"]);
    $colIsIncrementing = preg_match("/increment/i", $colInfo["flags"]);
    $tblHasIncrementingKey = $tblHasIncrementingKey || $colIsIncrementing;
    $colIsUnique = false;
    if (preg_match("/primary/i", $colInfo["flags"])) {
      $multikeys["PRIMARY KEY"][] = $colName;
    } elseif (preg_match("/unique/i", $colInfo["flags"])) {
       if (preg_match("/multiple/i", $colInfo["flags"])||$targetType=="mysql"){
         $multikeys["UNIQUE"][] = $colName;
       } else {
         $colIsUnique = true;
       }
    } elseif ((preg_match("/multiple/i", $colInfo["flags"]) || $colIsIncrementing) && $targetType=="mysql") {
      $multikeys["KEY"][] = $colName;
    }
    if (preg_match("/date/i", $colType)) {
      $datefields[] = $colName;
    }
    if ($colIsIncrementing) {
      $colDefault = "";
    } elseif ($colAllowsNull) {
      $colDefault = "NULL";
    } else {
      $colDefault = typeDefault($colType);
    }
    $colDefinitions[] = $tdb->quoteIdentifier($colName)
      . " " . $colType
      . ($colType=="VARCHAR"||$colType=="CHAR" ? "($colLength)" : "")
      . ($colAllowsNull ? " NULL" : " NOT NULL")
      . ($colIsIncrementing ? 
          ($targetType=="mssql"?" IDENTITY":" AUTO_INCREMENT") : "")
      . ($colIsUnique ? " UNIQUE" : "")
      . ($colDefault!="" ? " DEFAULT $colDefault" : "");
  }
  if (count($multikeys)>0) {
    // Add primary- and multiple-key constraints
    foreach($multikeys as $multitype => $multikey) {
      $multiname = join("_", $multikey).(count($multikey)==1?"_constraint":"");
      $postfix = "";
      // Make sure every constraint has a unique name
      while (in_array($multiname.$postfix, $constraintNames)) {
        $postfix++;
      }
      $multiname.=$postfix;
      $constraintNames[] = $multiname;
      if ($multitype=="PRIMARY KEY") {
        $primaryConstraint = $multiname;
      }
      $multiname = $tdb->quoteIdentifier($multiname);
      $multikey2 = array();
      foreach ($multikey as $multikeyfield) {
        $multikey2[] = $tdb->quoteIdentifier($multikeyfield);
      }
      $colDefinitions[] = 
          ($targetType=="mssql" ? "CONSTRAINT $multiname $multitype"
          : "$multitype $multiname") . " (" . join(", ", $multikey2) . ")";
    }
  }
  if ($addDropTables) {
    printQuery("DROP TABLE $tblNameQuoted");
  }
  printQuery("CREATE TABLE $tblNameQuoted (\n  "
      . join(",\n  ", $colDefinitions) . "\n)");
  print "\n";
  // Add fulltext indexes/catalogs
  $fulltext = array();
  $fulltextname = "";
  if ($addFulltextIndex) {
    if ($dbtype == "mysql") {
      foreach($db->getAll("SHOW INDEX FROM $tblName") as $colInfo) {
        if ($colInfo["Index_type"]=="FULLTEXT") {
          $fulltext[] = $colInfo["Column_name"];
          $fulltextname = $colInfo["Key_name"];
        }
      }
    }
  }
  if (count($fulltext)>0) {
    if ($targetType == "mysql") {
      $fulltextQuoted = array();
      foreach($fulltext as $colName) {
        $fulltextQuoted = $tdb->quoteIdentifier($colName);
      }
      printQuery("ALTER TABLE $tblNameQuoted"
         ." ADD FULLTEXT ".$tdb->quoteIdentifier($fulltextname)
         ." (".join(", ", $fulltextQuoted).")");
    } else {
      // MSSQL full text indices are a bit more work
      printQuery("EXEC sp_fulltext_catalog "
          . $qdb->quoteSmart($fulltextname).", "
          . $qdb->quoteSmart('create'));
      printQuery("EXEC sp_fulltext_table "
          . $qdb->quoteSmart($tblName).", "
          . $qdb->quoteSmart('create').", "
          . $qdb->quoteSmart($fulltextname).", "
          . $qdb->quoteSmart($primaryConstraint));
      foreach($fulltext as $colName) {
        printQuery("EXEC sp_fulltext_column "
          . $qdb->quoteSmart($tblName).", "
          . $qdb->quoteSmart('add'));
      }
      printQuery("EXEC sp_fulltext_table "
          . $qdb->quoteSmart($tblName).", "
          . $qdb->quoteSmart('activate'));
      printQuery("EXEC sp_fulltext_table "
          . $qdb->quoteSmart($tblName).", "
          . $qdb->quoteSmart('start_full'));
      printQuery("EXEC sp_fulltext_table "
          . $qdb->quoteSmart($tblName).", "
          . $qdb->quoteSmart('start_background_updateindex'));
    }
  }

  if (empty($dumpTablesPattern) 
       || preg_match("/(?:$dumpTablesPattern)/i", $tblName)) { 
    // Dump the contents of the table (if any)
    $tblres=$db->query("SELECT * FROM $tblName");
    if ($tblres->fetchInto($tblrow)) {
      if ($tblHasIncrementingKey && $targetType=="mssql") {
        printQuery("SET IDENTITY_INSERT $tblNameQuoted ON");
        $prepSql[] = "SET IDENTITY_INSERT $tblNameQuoted ON";
        $postSql[] = "SET IDENTITY_INSERT $tblNameQuoted OFF";
      }
      do {
        if (count($datefields)>0 && $targetType!=$dbtype) {
          if ($targetType=="mysql") {
            // Reformat date fields mssql=>mysql
            foreach ($datefields as $fld) {
              $tblrow[$fld] = strftime("%Y-%m-%d %H:%M:%S",
                  strtotime($tblrow[$fld]));
            }
          } else  {
            // Reformat date fields mysql=>mssql
            foreach ($datefields as $fld) {
              if (preg_match("/^(\d\d\d\d)(\d\d)(\d\d)(\d\d)(\d\d)(\d\d)$/",
                  $tblrow[$fld], $match)) {
                $tblrow[$fld] = sprintf("%04d-%02d-%02d %02d:%02d:%02d",
                    $match[1], $match[2], $match[3],
                    $match[4], $match[5], $match[6]);
              }
            }
          }
        }
        $valuesQuoted = array();
        foreach ($tblrow as $i => $value) {
          $valuesQuoted[] = $qdb->quoteSmart($value);
        }
        printQuery("INSERT INTO $tblNameQuoted\n  ("
            .join(", ", $colList) .")\n"
            . "  VALUES (".join(", ", $valuesQuoted).")");
      } while($tblres->fetchInto($tblrow));
      if ($tblHasIncrementingKey && $targetType=="mssql") {
        printQuery("SET IDENTITY_INSERT $tblNameQuoted OFF");
        array_pop($prepSql);
        array_pop($postSql);
      }
      print "\n\n";
    }
    print "\n";
  }

}
printQuery("END");
