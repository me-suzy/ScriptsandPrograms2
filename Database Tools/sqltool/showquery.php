<?php
require_once("DB.php");
flush();

// Fetch available database types
$dbtypes = array();
if (function_exists("mssql_connect")) {
  $dbtypes[] = "mssql";
}
if (function_exists("mysql_connect")) {
  $dbtypes[] = "mysql";
}

// Fetch connection parameters, with appropriate defaults
$dbpasswd = (!empty($HTTP_POST_VARS["dbpasswd"])) ? $HTTP_POST_VARS["dbpasswd"]
    : ((! empty($HTTP_POST_VARS["dbpasswdClear"])) ? ""
    : @$HTTP_POST_VARS["dbpasswdOld"]);
$dbhost = (! empty($HTTP_POST_VARS["dbhost"])) ? $HTTP_POST_VARS["dbhost"]
    : "localhost";
$dbname = @$HTTP_POST_VARS["dbname"];
$dbuser = @$HTTP_POST_VARS["dbuser"];
$dbtype = (! empty($HTTP_POST_VARS["dbtype"])) ? $HTTP_POST_VARS["dbtype"]
    : $dbtypes[0];
$query = @$HTTP_POST_VARS["query"];

?>
<html>
<head>
  <title>SQL Query at <?php print $COMPUTERNAME ? $COMPUTERNAME : $HOST ?></title>
  <style>
th { color: white; background-color: black; font-weight: bold }
  </style>
  <script language="javascript" type="text/javascript">
function getVal(inputObj) {
  if (inputObj.type=="select-one") {
    var opt=inputObj.options[inputObj.selectedIndex];
    return typeof(opt.value)=="undefined" ? opt.text : opt.value;
  } else {
    return inputObj.value;
  }
}

function prepDump() {
  var frm1=document.forms[0];
  var frm2=document.forms[1];
  frm2.dbname.value = getVal(frm1.dbname);
  frm2.dbhost.value = getVal(frm1.dbhost);
  frm2.dbtype.value = getVal(frm1.dbtype);
  frm2.dbuser.value = getVal(frm1.dbuser);
  frm2.dbpasswd.value = getVal(frm1.dbpasswd) ? getVal(frm1.dbpasswd)
        : (frm1.dbpasswdClear.checked ? '' : getVal(frm1.dbpasswdOld));

  if (frm2.dumpToFile.checked) {
    frm2.target = "";
  } else {
    frm2.target = "_blank";
  }
}
  </script>
</head>



<body>
<h2>SQL Query at <?php print ($COMPUTERNAME ? $COMPUTERNAME : $HOST); ?></h2>
<table><tr><td>

<form method="POST" action="showquery.php" enctype="multipart/form-data"
    id="form1" name="form1">
<table style="border: 1px solid black" width="100%" summary="Connection parameters"><tr>
  <td>
    Database:
    <input type="text" name="dbname" size="20"
        value="<?php print htmlentities($dbname); ?>" />
  </td>
  <td>
    Username:
    <input type="text" name="dbuser" size="10"
        value="<?php print htmlentities($dbuser); ?>" />
  </td>
</tr><tr>
  <td>
    Server:
    <input type="text" name="dbhost" size="20"
        value="<?php print htmlentities($dbhost); ?>" />
  </td>
  <td>
    Password:
    <input type="password" name="dbpasswd" size="10" value="" />
    <input type="checkbox" name="dbpasswdClear" value="1"/>(No Password)
    <input type="hidden" name="dbpasswdOld"
       value="<?php print htmlentities($dbpasswd) ?>" />
  </td>
</tr><tr>
  <td>Type:
      <select name="dbtype">
<?php
  foreach ($dbtypes as $dbtype2) {
      print "      <option".(@$dbtype==$dbtype2?" selected":"").">"
          . htmlentities($dbtype2) . "</option>\n";
  }
?>
    </select>
  </td>
</tr></table>

<table width="100%" summary="Query input"><tr>
  <td colspan="2">
    Query:<br/>
    <textarea name="query" rows="6" cols="80"><?php
      print htmlentities(@$query)
    ?></textarea>
  </td>
</tr><tr>
  <td>
    <input type="button" value="Clear"
         onClick="document.forms[0].query.value=''" />
  </td>
  <td>
    Or use file <input name="queryfile" type="file" />
  </td>
</tr></table>
<?php if ($OS=="Windows_NT") { ?>
<p>
  Warning: running large batches of queries (>100 queries) may
  cause Windows NT computers to run out of desktop heap.<br />
  For more information, see
  <a target="_blank"
  href="http://support.microsoft.com/default.aspx?kbid=217202">here</a>,
  <a target="_blank"
  href="http://support.microsoft.com/default.aspx?kbid=184802">here</a> and
  <a target="_blank"
  href="http://support.microsoft.com/default.aspx?kbid=142676">here</a>.
</p>
<?php } ?>

<table style="border: 1px solid black" width="100%">
  <tr><td>
    <input type="submit" value="Execute Query" />
  </td></tr>
</table>
</form>

<form method="POST" action="dumpdb.php" enctype="multipart/form-data"
    id="form2" name="form2" onSubmit="return prepDump()" target="_blank">
  <input type="hidden" name="dbname" value="">
  <input type="hidden" name="dbhost" value="">
  <input type="hidden" name="dbtype" value="">
  <input type="hidden" name="dbuser" value="">
  <input type="hidden" name="dbpasswd" value="">
<table style="border: 1px solid black" width="100%" summary="DumpDB Form">
  <tr><td>
    <input type="submit" value="Dump DB" />
    <input type="checkbox" name="dumpToFile" value="1"/>to file
    <select name="targetType">
      <option value="" selected>as native SQL</option>
      <option value="mysql">as MySQL</optio>
      <option value="mssql">as MSSQL</option>
    </select>
  </td></tr>
  <tr><td>
    add contents for tables whose names match RegEx pattern
    /<input type="text" name="dumpTablesPattern" value="." size="15">/i
  </td></tr>
  <tr><td>
    <input type="checkbox" name="addReconnect" value="1">
      add RECONNECT every
    <input type="text" name="reconnectInterval" value="100" size="3"> queries
  </td></tr>
  <tr><td>
    <input type="checkbox" name="addDropTables" value="1">
      add DROP TABLE commands
  </td></tr>
  <tr><td>
    <input type="checkbox" name="addFulltextIndex" value="1">
      add full text indices
  </td></tr>
</table>
</form>

</td></tr></table>

<?php
flush();


// Start running the queries
if (! (empty($query) && ! isset($HTTP_POST_FILES["queryfile"]))) {
  $connectSpecs = array(
      "phptype" => $dbtype,
      "hostspec" => $dbhost,
      "username" => $dbuser,
      "password" => $dbpasswd,
      "database" => $dbname
  );
  $connectOptions = array(
      "autofree" => true
  );
  $db = DB::connect($connectSpecs, $connectOptions);
  if (DB::isError($db)) {
    die("SQL connect error: ".$db->getDebugInfo()."\n");
  }
}

function doQuery($sql0) {
  // This function executes a single query
  global $db, $connectSpecs, $connectOptions;
  $sql = trim($sql0);
  if (empty($sql)) {
    return;
  }
  print nl2br(htmlentities("$sql;\n"));
  flush();
  // If the query is the special 'RECONNECT' command, perform it
  if (preg_match("/^RECONNECT/is", $sql)) {
    $db->disconnect();
    $db = DB::connect($connectSpecs, $connectOptions);
    if (DB::isError($db)) {
      die("SQL connect error: ".$db->getDebugInfo()."\n");
    }
    print "Reconnected to server<br />";
    return;
  }
  // Perform query
  $result=$db->query($sql);
  if (DB::isError($result)) {
    print nl2br(htmlentities("SQL error: ".$result->getDebugInfo()."\n"));
    return;
  }
  $affected = $db->affectedRows();
  $resultInfo = $db->tableInfo($result, DB_TABLEINFO_ORDER);
  // If the query didn't output a result table, show the nr of rows affected
  if (! is_array($resultInfo["order"])) {
    print "$affected records are affected<br />";
    return;
  }
  // Show the result of the query in table format
  print "<table width=\"90%\" align=\"center\" border=\"1\""
      ." cellpadding=\"2\" cellspacing=\"0\">\n";
  $row0 = array_keys($resultInfo["order"]);
  print "  <tr>";
  foreach($row0 as $val) {
    print "    <th>";
    print htmlentities($val);
    print "</th>";
  }
  print "  </tr>\n";
  while ($result->fetchInto($row)) {
    print "  <tr>";
    foreach($row as $val) {
      print "    <td>";
      if ($val===NULL) {
        print "<em>NULL</em>";
      } elseif (preg_match("/^\s*$/s", $val)) {
        print "&nbsp;";
      } else {
        print nl2br(htmlentities($val));
      }
      print "</td>";
    }
    print "  </tr>\n";
  }
  print "  <tr>";
  foreach($row0 as $val) {
    print "    <th>";
    print htmlentities($val);
    print "</th>";
  }
  print "  </tr>\n";
  print "</table><br /><br />\n";
  flush();
  $result->free();
}

// We handle compound SQL queries as multiple batches,
// in order to obtain multiple result sets
// Note that this means that single-session scope entities (such as
// local variables) effectively cannot be used

$fp="";
if (isset($HTTP_POST_FILES["queryfile"])
    && $HTTP_POST_FILES["queryfile"]["size"]>0) {
  $fp = fopen($HTTP_POST_FILES["queryfile"]["tmp_name"], "r");
  $query = fread($fp, 8192);
} elseif (! isset($query)) {
  $query = "";
}

$sql0 = "";
$quoteMode = false;
$done = false;
while (! $done) {
  // Go through the query, gathering text in $sql0 until we encounter a
  // semicolon which is not inside a quoted string or escaped, then
  // execute the query and repeat
  preg_match('/^([^;\'\\\\]*)(\'|;|$|\\\\$|\\\\.)/s', $query, $match);
  $sql0 .= $match[1];
  $query = substr($query, strlen($match[1]));
  switch($match[2]) {
   case "'":
    // We are at the start or end of quoted string literal
    $quoteMode = ! $quoteMode;
    $sql0 .= $match[2];
    $query = substr($query, 1);
    break;
   case ";":
    // We encounter a semicolon: if we are not inside a quoted string literal,
    // we can execute a query
    if ($quoteMode) {
      $sql0 .= $match[2];
      $query = substr($query, 1);
    } else {
      doQuery($sql0);
      $sql0 = "";
      $query = substr($query, 1);
    }
    break;
   case "":
    // We are at the end of the current buffer
   case "\\":
    // ... with backslash still pending
    if ($fp && !feof($fp)) {
      // Fetch more bytes
      $query .= fread($fp, 8192);
    } else {
      // No more bytes: do query and we're done
      doQuery($sql0 . $query);
      $done = true;
    }
    break;
   default:
    // Escaped character
    $sql0 .= $match[2];
    $query = substr($query, 2);
    break;
  }
}

?>
</body>
</html>
