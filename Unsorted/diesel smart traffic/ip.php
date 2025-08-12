<?php 
if ($ntmishere) setcookie("ntmishere",$ntmishere);
echo $HTTP_SERVER_VARS[REMOTE_ADDR];
?>