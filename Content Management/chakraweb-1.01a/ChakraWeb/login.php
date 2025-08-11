<?php 
// ----------------------------------------------------------------------
// ModName: login.php
// Porpuse: Login form on maintenance time (for admin only)
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

//this file and all includes file loaded as library
define(LOADED_AS_LIBRARY, 1);

require_once('./_files/library/_defgenerate.php');
require_once('./_files/library/_defgeneral.php');
require_once('./_files/library/fun_utils.php');
require_once('./_files/library/cls_dbase.php');
require_once('./_files/library/fun_session.php');
require_once('./_files/library/fun_dbvars.php');
require_once('./_files/library/fun_dbutils.php');
require_once('./_files/library/fun_system.php');
require_once('./_files/library/fun_user.php');
require_once('./_files/library/fun_member.php');
require_once('./_files/library/fun_string.php');

//reset the logging
$gLogDBase      = false;
$gLogVisitor    = false;

//CONNECTION TO DATABASE
$db = new DBase();
$db->Connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 


// Start session
if (!SessionBegin())
	SystemFatalError('Initiate', 'Session initialisation failed');

$lang_module = "./_lang/".UserGetLID().'/global.php';
require_once($lang_module);

SetDynamicContent();

$op = RequestGetValue('op', 'show');
switch ($op)
{
case 'do':
    DoLogin();
case 'show':
default:
    LoginShowForm();
}

function DoLogin()
{
    $uid = RequestGetValue('uid', '');
    $psw = RequestGetValue('psw', '');

    //check validity of $uid and $psw first
    if (!IsUPValid($uid) || !IsUPValid($psw))
        SystemFatalError(_ERR_LOGIN_FAILED_TITLE, _ERR_LOGIN_FAILED_MESSAGE);

    if (!UserLogin($uid, $psw))
        SystemFatalError(_ERR_LOGIN_FAILED_TITLE, _ERR_LOGIN_FAILED_MESSAGE);

    if (!IsUserAdmin())
    {
        //admin only
        UserLogout();
        SystemFatalError('Login temporary canceled', 'We are on maintenance time.');
    }

    Header("Location: /index.html");
}


function LoginShowForm()
{
print '
<html>
    <head>
    <title>Login Form</title>
    </head>
<body>

<form method="POST" action="/login.php">
<table>
  <tr>
    <td vAlign="top" align="middle" width="70"><img src="/images/info_big.gif" width="36" height="48">
    <td width="400">
      <h1><font face="Verdana" size="5" color="#FF0000">Login Form</font>
      </h1>
      Use this form to login on maintenance time only.<p>User ID: &nbsp; <input type="text" name="uid" size="12"><br>
      Password: <input class="inputbox" type="text" name="psw" size="12"><br>
        <input class="button" type="submit" value="Login">
      </p>
    </td>
  </table>

<input type="hidden" name="folder_id" value="0" />
<input type="hidden" name="page_id" value="0" />
<input type="hidden" name="op" value="do" />
</form>
</body>
</html>';
}


?>
