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

if(isset($_SESSION['userId'])) {
    try {
        $loggedUser = User::get($_SESSION['userId']);
    } catch (ObjectNotFoundException $e) {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'GET') {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Creating new user</title>
</head>
<body>
<?php
echo "Wellcome, " . $loggedUser->getName() . " [ <a href=\"index.php\">Home</a> ] [ <a href=\"logout.php\">Logout</a> ] [ <a href=\"new.php\">Create New User</a> ] [ <a href=\"xmldump.php\">Dump Database To Xml</a> ]<br/><br/>";
echo "<form name=\"search\" action=\"search.php\" method=\"post\">Search: <input type=\"text\" name=\"username\"/> <input type=\"submit\" value=\"Go\"/></form>";
?>
<form name="edit" method="post" action="new.php">
<table width="100%"  border="1" cellspacing="0" cellpadding="3">
<caption>
 Create User
</caption>
  <tr>
    <td><strong>Name:</strong></td>
    <td><input name="name" type="text" id="name" /></td>
  </tr>
  <tr>
    <td><strong>User Name: </strong></td>
    <td><input name="username" type="text" id="username" /></td>
  </tr>
  <tr>
    <td><strong>Password:</strong></td>
    <td><input name="password" type="password" id="password" /></td>
  </tr>
  <tr>
    <td><strong>Password (Confirm):</strong></td>
    <td><input name="password2" type="password" id="password2" /></td>
  </tr>
  <tr>
    <td><strong>Email:</strong></td>
    <td><input name="email" type="text" id="email" /></td>
  </tr>
  <tr>
    <td><strong>Active:</strong></td>
    <td><select name="active" id="active">
      <option value="Y" selected="selected">Yes</option>
      <option value="N">No</option>
    </select></td>
  </tr>
  <tr>
    <td><strong>Admin:</strong></td>
    <td><select name="admin" id="admin">
      <option value="Y" selected="selected">Yes</option>
      <option value="N" >No</option>
    </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
    <input type="submit" name="Submit" value="Create" />
    <input type="reset" name="Reset" value="Reset" />
    </td>
  </tr>
</table>
</form>
</body>
</html>
<?php
}
else {
    try {
        $user = new User();
        $user->setName(trim($_POST['name']));
        $user->setUserName(trim($_POST['username']));
        $user->setEmail(trim($_POST['email']));
        $user->setAdmin($_POST['admin'] == "Y");
        $user->setActive($_POST['active'] == "Y");
        $user->setCreationdatetime(time());
        if($_POST['password'] != $_POST['password2']) {
            throw new InvalidValueException('password');
        }
        
        $user->setPassword($_POST['password']);
        
        DbModel::insert($user);
        echo "Wellcome, " . $loggedUser->getName() . " [ <a href=\"logout.php\">Logout</a> ] [ <a href=\"index.php\">Home</a> ]<br/><br/>";
        echo "A new user with id = " .$user->getUserid() . " is created";
        exit;
    } catch (InvalidValueException $e) {
        die("Error :" . $e->getMessage() .' is invalid');
    } catch (UniqueKeyExistsException $e) {
        die("Error : Unique key " . $e->getMessage() . " exists");
    }
}
?>