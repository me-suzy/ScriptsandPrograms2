<?PHP
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

// Validate session
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
    try {
        $user = User::get($_GET['userid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Editing User Info</title>
</head>
<body>
<?PHP
echo "Wellcome, " . $loggedUser->getName() . " [ <a href=\"index.php\">Home</a> ] [ <a href=\"logout.php\">Logout</a> ] [ <a href=\"new.php\">Create New User</a> ] [ <a href=\"xmldump.php\">Dump Database To Xml</a> ]<br/><br/>";
echo "<form name=\"search\" action=\"search.php\" method=\"post\">Search: <input type=\"text\" name=\"username\"/> <input type=\"submit\" value=\"Go\"/></form>";
?>
<form name="edit" method="post" action="edit.php">
<table width="100%"  border="1" cellspacing="0" cellpadding="3">
<caption>
 Edit User
 <?php if(isset($_GET['updated'])) echo " (Updated)";?>
</caption>
  <tr>
    <td><strong>Name:</strong></td>
    <td><input name="name" type="text" id="name" value="<?php echo$user->getName()?>" /></td>
  </tr>
  <tr>
    <td><strong>User Name: </strong></td>
    <td><input name="username" type="text" id="username" value="<?php echo$user->getUsername()?>" /></td>
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
    <td><input name="email" type="text" id="email" value="<?php echo$user->getEmail()?>" /></td>
  </tr>
  <tr>
    <td><strong>Active:</strong></td>
    <td><select name="active" id="active">
      <option value="Y" <?php echo $user->isActive() ? "selected=\"selected\"" : ""; ?> >Yes</option>
      <option value="N" <?php echo !$user->isActive() ? "selected=\"selected\"" : ""; ?> >No</option>
    </select></td>
  </tr>
  <tr>
    <td><strong>Admin:</strong></td>
    <td><select name="admin" id="admin">
      <option value="Y" <?php echo $user->isAdmin() ? "selected=\"selected\"" : ""; ?> >Yes</option>
      <option value="N" <?php echo !$user->isAdmin() ? "selected=\"selected\"" : ""; ?> >No</option>
    </select></td>
  </tr>
  <tr>
    <td><strong>Creation Date: </strong></td>
    <td><input name="creationdate" type="text" id="creationdate" value="<?php echo$user->getCreationdate()?>" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
    <input type="submit" name="Submit" value="Update" />
    <input type="reset" name="Reset" value="Reset" />
    <input type="hidden" name="userid" value="<?php echo $user->getUserid()?>" />
    </td>
  </tr>
</table>
</form>
</body>
</html>
<?PHP
    } catch (ObjectNotFoundException $e) {
        die('Unknown User!');
    }
}
else {
    try {
        $user = User::get($_POST['userid']);
        $user->setName(trim($_POST['name']));
        $user->setUserName(trim($_POST['username']));
        $user->setEmail(trim($_POST['email']));
        $user->setAdmin($_POST['admin'] == "Y");
        $user->setActive($_POST['active'] == "Y");
        $user->setCreationdate(trim($_POST['creationdate']));
        
        if($_POST['password'] != $_POST['password2']) {
            throw new InvalidValueException('password');
        }
        
        if($_POST['password'] != '') {
            $user->setPassword($_POST['password']);
        }
        
        DbModel::update($user);
        header("Location: edit.php?updated&userid=".$user->getUserid());
        exit;
    } catch (ObjectNotFoundException $e) {
        die($e->getMessage());
    } catch (InvalidValueException $e) {
        die("Error :" . $e->getMessage() .' is invalid');
    } catch (UniqueKeyExistsException $e) {
        die("Error : Unique key " . $e->getMessage() . " exists");
    }
}
?>