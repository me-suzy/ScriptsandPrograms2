<?php
singletonQueue();
@reset($HTTP_GET_VARS); 
while(list($key, $val) = @each($HTTP_GET_VARS)) 
$$key = $val;

@reset($HTTP_POST_VARS); 
while(list($key, $val) = @each($HTTP_POST_VARS)) 
$$key = $val;

?>