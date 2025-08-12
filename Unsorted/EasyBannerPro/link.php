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

include('./common.php');
// link.php?from=1&size=1&to=1&url=http://....

if ($HTTP_GET_VARS[from])
{ $r = mysql_query("SELECT hits FROM $s[pr]ip WHERE number='$HTTP_GET_VARS[from]' AND ip='$HTTP_SERVER_VARS[REMOTE_ADDR]'");
  $u = mysql_fetch_row($r);
  if ($u[0]) mysql_query("update $s[pr]ip set hits = hits + 1 where number='$HTTP_GET_VARS[from]' AND ip='$HTTP_SERVER_VARS[REMOTE_ADDR]'");
  if ( ($u[0]) AND ($u[0]<$s[count_ip]) AND ($HTTP_GET_VARS[from]) AND ($HTTP_GET_VARS[size]) )
  { mysql_query("update $s[pr]stats$HTTP_GET_VARS[size] set c_m = c_m+1, forclicks = forclicks+forclick, i_nu=i_nu+forclick where number = '$HTTP_GET_VARS[from]'");
    mysql_query("update $s[pr]day set cl_m = cl_m+1 where number = '$HTTP_GET_VARS[from]' AND size = '$HTTP_GET_VARS[size]'");
  }
}
if ( (!$linkid) OR (!$HTTP_GET_VARS[url]) OR (!$HTTP_GET_VARS[size]) ) exit;

//echo "update $s[pr]stats$HTTP_GET_VARS[size] set c_w = c_w+1, c_nu = c_nu-1 where number = '$HTTP_GET_VARS[to]'<br>";
//echo "update $s[pr]day set cl_w = cl_w+1 where number = '$HTTP_GET_VARS[to]' AND size = '$HTTP_GET_VARS[size]'<br>";
//echo "update $s[pr]b$HTTP_GET_VARS[size] set c$HTTP_GET_VARS[b] = c$HTTP_GET_VARS[b]+1 where number = '$HTTP_GET_VARS[to]'";

if (!$HTTP_GET_VARS[nc]) // not count
{ mysql_query("update $s[pr]stats$HTTP_GET_VARS[size] set c_w = c_w+1, c_nu = c_nu-1 where number = '$HTTP_GET_VARS[to]'");
  mysql_query("update $s[pr]day set cl_w = cl_w+1 where number = '$HTTP_GET_VARS[to]' AND size = '$HTTP_GET_VARS[size]'");
  if ($HTTP_GET_VARS[b]) mysql_query("update $s[pr]b$HTTP_GET_VARS[size] set c$HTTP_GET_VARS[b] = c$HTTP_GET_VARS[b]+1 where number = '$HTTP_GET_VARS[to]'");
}
header ("Location: $HTTP_GET_VARS[url]");

mysql_close($linkid);

?>