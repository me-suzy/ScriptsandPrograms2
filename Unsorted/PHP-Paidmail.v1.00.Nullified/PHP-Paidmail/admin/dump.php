<?php
    include ("../includes/vars.php");
    $output=system("mysqldump -u ".$dbuser." -p".$dbpwd." ".$dbname." --opt > backup.sql");
    header("Location: backup.sql")
?>
