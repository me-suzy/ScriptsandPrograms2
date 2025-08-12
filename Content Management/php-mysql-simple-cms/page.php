<?php 
include "config.php";
?>
<html>
<head>
<title>contentframe</title>
<link href="/style.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
$id = $_GET['id'];
database_connect();
$query = "SELECT * from content
          WHERE id = $id";
$error = mysql_error();
if (!$result = mysql_query($query)) {
    print "$error";
	exit;
	}

while($row = mysql_fetch_object($result)){
  $content = $row->text;
  print("$content");
	}
 
?>
</body>
</html>
