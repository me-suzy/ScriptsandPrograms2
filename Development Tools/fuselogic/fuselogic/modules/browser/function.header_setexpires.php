<?php
/**
* Sends the Expires HTTP 1.0 header.
* @param int number of seconds from now when page expires
*/
function header_setExpires($expires = 0)
{
 header('Expires: ' .
   gmdate('D, d M Y H:i:s', time() + $expires) . 'GMT');
} 

?>
