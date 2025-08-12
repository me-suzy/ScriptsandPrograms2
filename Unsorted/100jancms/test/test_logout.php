<?php 
/***************************************\
|					|
|	    100janCMS v1.01		|
|    ------------------------------	|
|    Supplied & Nulled by GTT '2004	|
|					|
\***************************************/
setcookie ("website_member", "cookie_expired", time()-360000000);
header("Location: test_index.php");
?>