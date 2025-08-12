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

include("./common.php");
if (!$HTTP_SESSION_VARS['admuser']) header('Location: login.php');
?>
<html>
<head>
<title>Easy Banner Pro - Administration</title>
<base target="_self"></head>
<frameset rows="1*" cols="140, 1*" border="0">
<frame name="left" scrolling="auto" marginwidth="0" marginheight="0" src="main.php?action=left_frame" frameBorder=no Resize>
<frame name="right" scrolling="auto" src="main.php?action=home" Resize frameBorder=NO>
</frameset>
</html>
