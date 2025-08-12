<?php

$GLOBALS['page']->head("wordcircle","","Thanks for visiting",0);


setcookie ("loggedin", " ", time() - 3600);

$GLOBALS['page']->pleaseWait("Logout in progress","index.php");

?>