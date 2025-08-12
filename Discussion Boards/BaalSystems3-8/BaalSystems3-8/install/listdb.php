<html>

<head>
 <title>Baal Smart Form</title>
<script language="javascript">

function call(a)

{

parent.document.forms[0].dbname.value = a ;

parent.document.forms[0].dbservername.value = "localhost" ;

}

</script>

</head>

<body>

<b>Click to choose existing Database</b> (localhost)

<form method = "post"><?php

include "common_db.php" ;

$link_id  = db_connect();
if ($link_id != 0)
{
$list =   mysql_list_dbs($link_id) ;

while ( $row = mysql_fetch_object($list) )  

{

?><a href = "listdb.php" onClick="call('<?php echo $row->Database; ?>')" ><?php

echo $row->Database ;

echo "<br>" ;

?>

</a>

<?php

}
}
?>

</form>

</body>

</html>



