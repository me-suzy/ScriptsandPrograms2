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
include_once('./rebuild_f.php');

if ($s[secretword] != $HTTP_GET_VARS[word]) exit;
include ('./data/time.php');
daily_job($s[cas],0);

?>