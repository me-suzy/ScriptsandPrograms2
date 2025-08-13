<?php
#####################################################
#                   Go2! Search                     #
#####################################################
#                                                   #
#                    track.php                     #
#                                                   #
#####################################################
#       Copyright © 2001 W. Dustin Alligood         #
#####################################################

require("./text.php");
require("./config.php");

########## Change Nothing Below This Line ###########

mysql_connect($mysql_host,$mysql_user,$mysql_password) or die ("Could not connect");
mysql_select_db($mysql_database);
$query="update $mysql_table set hits=hits+1 where id='$id'";
$result=mysql_query($query);
header("Location:".$remote_script."?back=".base64_encode($HTTP_REFERER)."&url=".base64_encode($url));
?>