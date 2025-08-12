<?php
$_REQUEST['id'] = intval($_REQUEST['id']);

$Q="DELETE FROM tasks WHERE id='$_REQUEST[id]'";
mysql_query($Q);

header("Location: index.php?page=showTasks&id=$_REQUEST[job_id]");


?>
