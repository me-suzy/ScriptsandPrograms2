<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                    cache.php3                     #
#                                                   #
#####################################################
#       Copyright © 2001 W. Dustin Alligood         #
#####################################################

require("./text.php");
require("./config.php");

########## Change Nothing Below This Line ###########

$page ="<base href=\"".urldecode($b)."\">";
$page.="<base src=\"".urldecode($b)."\">";
$page.=openpage($c);
print $page;

function openpage($filename){
	global $cache_dir;
	$fd=@fopen($cache_dir."/".$filename, "r");
	$page=@fread($fd, filesize ($cache_dir."/".$filename));
	@fclose($fd);
	return $page;
}

?>