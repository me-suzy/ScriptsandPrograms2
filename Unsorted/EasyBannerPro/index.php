<?PHP

#################################################
##                                             ##
##              Easy Banner Pro                ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                 Version 2.8                 ##
##             copyright (c) 2003              ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

include('./functions.php');
include_once("$s[phppath]/data/messages.php");

if ($HTTP_GET_VARS[n])
{ $q = dq("select userid from $s[pr]members where number = '$HTTP_GET_VARS[n]'",0);
  $r = mysql_fetch_row($q);
  $id = $r[0]; }
else
{ if ($HTTP_GET_VARS[id]) $id = $HTTP_GET_VARS[id];
  else $id = $HTTP_GET_VARS[ID]; }
setcookie ('EB_affiliate',$id,$s[cas]+5184000);
header ("Location: $s[homepage]");
exit;

?>