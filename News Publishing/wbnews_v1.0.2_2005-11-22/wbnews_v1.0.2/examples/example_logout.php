<?php

/*

	Can be used best when using Send to friend to link to this page. 

*/

define('wbnews',true);

include "../global.php";
include $config['installdir']."/users.php";

$user = new user($newsConfig);
if ($user->protectedArea()!=true)
    echo "Not Logged in";
else
    $user->logout();

?>
