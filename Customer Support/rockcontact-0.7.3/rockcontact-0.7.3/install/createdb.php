<html>
<head>
<title>Create Database</title>
</head>
<body>
<?

require_once("../includes/config.inc.php");

exec_query_file("sqlite.sql");

$db = sqlite_open(BD_NAME, 0666, $sqliteerror);

$s_now = sqlite_escape_string(time());
$s_key = sqlite_escape_string("init");
$query = "UPDATE ". TABLE_CONFIG ." SET value=$s_now WHERE key='$s_key'";
echo_query($query);
sqlite_query($db, $query);

$s_key = sqlite_escape_string("last_prune");
$query = "UPDATE ". TABLE_CONFIG ." SET value=$s_now WHERE key='$s_key'";
echo_query($query);
sqlite_query($db, $query);

sqlite_close($db);

function echo_query($query){
  echo "<pre>$query</pre>";
}

function exec_query_file($filename){
  $db = sqlite_open(BD_NAME, 0666, $sqliteerror);
  $file = file_get_contents($filename);
  $querys = explode(";",$file);
  for($x = 0; $x < count($querys); $x++){
    $query = trim($querys[$x]);
    echo_query($query);
    $result = sqlite_query($db, $query);
  }
  sqlite_close($db);
}

?>

<br><br><a href="index.php">CONTINUE</a>

</body>
</html>
