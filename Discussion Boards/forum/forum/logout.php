<?php

$where = "Logging out...";

include("header.php");


unset($_SESSION["user"]);
unset($_SESSION["pass"]);


include("footer.php");

?>
