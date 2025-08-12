<?php
/**
* User Manager
*
* Copyright (c) 2004 Erdinc Yilmazel
*
* Example application for MyObjects Object Persistence Library
*
* MyObjects Copyright 2004 Erdinc Yilmazel <erdinc@yilmazel.com>
* http://www.myobjects.org
* 
* @version 1.0
* @author Erdinc Yilmazel
* @package UserManagerExample
*/

require_once('MyObjectsSettings.php');
require_once(MyObjectsRuntimePath . '/Base.php');
session_start();
try {
    $user = User::get(array('username'=>$_POST['username'], 'password'=>md5($_POST['password']), 'admin'=>'Y', 'active'=>'Y'));
    $_SESSION['userId'] = $user->getUserid();
    header("Location: index.php");
    exit;
} catch (ObjectNotFoundException $e) {
    unset($_SESSION['userId']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Login Error</title>
</head>
<body>
The specified user not found or password is not correct.
<form name="loginForm" id="loginForm" method="post" action="login.php">
  <table width="100%"  border="0" cellspacing="0" cellpadding="3" style="font-family: Tahoma, Arial; font-size:12px; margin-bottom:10px;">
    <tr>
      <td><b>User Name:</b></td>
      <td><input name="username" type="text" id="username" /></td>
    </tr>
    <tr>
      <td><b>Password:</b></td>
      <td><input name="password" type="password" id="password" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Login" /> 
        <a href="register.php">Register for a new account</a> </td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
}
?>