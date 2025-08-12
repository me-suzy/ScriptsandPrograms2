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

if (!$HTTP_POST_VARS) form();
save_data($HTTP_POST_VARS);

function save_data($form) {
global $s;
include('./_head.txt');
if ( (!$form[username]) OR (!$form[password]) ) problem("Please go back and fill in both required fields.");
if ( ((strlen($form[username])) < 6) OR ((strlen($form[username])) > 15) ) problem('Username must be 6-15 characters long. Please try again.');
if ( ((strlen($form[password])) < 6) OR ((strlen($form[password])) > 15) ) problem('Password must be 6-15 characters long. Please try again.');
$password = md5($form[password]);
$q = dq("insert into $s[pr]moderators values (NULL,'$form[username]','$password','none','none','0','1','1','1','1','1','1','1','1','1','1','1','1','1','1')",1);
echo iot('New moderator '.$form[username].' has been created.');
exit;
}

function form() {
include('./_head.txt');
?>
<span class="text13">Use this tool to create a new moderator with all available rights<br>if you have forget your administration username and/or password.</span>
<br>
<form method="POST" action="set_pass.php">
<table border="0" cellspacing="2" cellpadding="4" class="table1">
<tr>
<td align="left"><span class="text13">Username</span></td>
<td align="left"><INPUT class="field1" maxLength=15 size=15 name="username"></td>
</tr>
<tr>
<td align="left"><span class="text13">Password</span></td>
<td align="left"><INPUT class="field1" maxLength=15 size=15 name="password"></td>
</tr>
<tr>
<td align="middle" width="100%" colSpan=2><INPUT type=submit value="Save" name=D1 class="button1"></TD>
</TR></TBODY></TABLE></FORM>
</center><br>
<?PHP
exit;
}

?>