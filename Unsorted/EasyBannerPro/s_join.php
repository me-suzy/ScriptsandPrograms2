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
$s[sponsor] = 1;

if ($HTTP_POST_VARS) user_joined($HTTP_POST_VARS);
else page_from_template('s_join.html',$s);

#####################################################################

?>