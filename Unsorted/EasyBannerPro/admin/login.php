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
if ($HTTP_GET_VARS[action]=='log_off') log_off();
login($HTTP_POST_VARS);

function log_off() {
global $s;
session_destroy();
if (!$s[info])
$s[info] = iot('You have been logged off');
}


function login($form) {
global $s,$HTTP_SESSION_VARS;
if (!$form)
{ echo '<script>
  <!--
  if (window!= top)
  top.location.href=location.href
  // -->
  </script>';
  include('./_head.txt');
  echo $s[info];
  ?>
  <table border="0" width="200" cellspacing="2" cellpadding="4" class="table1">
  <form method="POST" action="login.php">
  <tr>
  <td align="left"><span class="text13">Username</span></td>
  <td align="left"><input class="field1" name="username" size=15 maxlength=15 value="<?PHP echo $s[pusername] ?>"></td>
  </tr>
  <tr>
  <td align="left"><span class="text13">Password</span></td>
  <td align="left"><input class="field1" type="password" name="password" size=15 maxlength=15 value="<?PHP echo $s[pupassword] ?>"></td>
  </tr>
  <tr><td colspan=2 align="center"><input type="submit" value="&nbsp;Submit&nbsp;" name="B1" class="button1"></td>
  </tr></form></table>
  <?PHP
  exit;
}
$password = md5($form[password]);
$q = dq("select number from $s[pr]moderators where username='$form[username]' AND password='$password'",1);
$data = mysql_fetch_row($q);
if (!$data[0])
{ $s[info] = iot('Wrong username or password. Please try again.');
  $s[pusername] = $form[username]; $s[pupassword] = $form[password];
  login(0); exit;
}
$HTTP_SESSION_VARS['admuser'] = $form[username]; $HTTP_SESSION_VARS['number'] = $data[0];
header ("Location: index.php");
}

?>