<?php
include("config.php");
 $del = $_POST['del'];
if ($del == "qwertyuiop321"){
unlink("$logs/log.html");
echo "Log file successfuly deleted!";
} else {
echo "You don't have permission to do this..";
}
?>