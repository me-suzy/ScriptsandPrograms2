<?php

$_SESSION['showShared'] = !$_SESSION['showShared'];

header("Location: index.php?page=editJobs&client_id=$_REQUEST[id]");

?>
