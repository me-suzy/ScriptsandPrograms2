<?php

if($name && $prename && $password && $email && $url && $points)
{
        require('../prepend.inc.php');
        if(account_add($name, $prename, $password, $email, $url, 1, $points, $sid))
        {
                header("Location: ./");
                exit;
        }
}

?>
<?
include("../templates/admin-header.txt");
?>
<?php
if($name && $prename && $email && $url && $password)
        echo "e-mail $email is already in use.";
?>

  <form method="post" action="./add.php">
  <table border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td>Name</td>
      <td>
        <input type="text" name="name" value="<?php echo stripslashes($name); ?>">
      </td>
    </tr>
    <tr>
      <td>Fist name</td>
      <td>
        <input type="text" name="prename" value="<?php echo stripslashes($prename); ?>">
      </td>
    </tr>
    <tr>
      <td height="30">e-mail</td>
      <td height="30">
        <input type="text" name="email" value="<?php echo stripslashes($email); ?>">
      </td>
    </tr>
    <tr>
      <td>URL</td>
      <td>
        <input type="text" name="url" value="<?php echo stripslashes($url); ?>">
      </td>
    </tr>
    <tr>
      <td>Password</td>
      <td>
        <input type="password" name="password" value="<?php echo stripslashes($password); ?>">
      </td>
    </tr>
    <tr>
      <td>Points</td>
      <td>
        <input type="text" name="points" value="<?php echo stripslashes($points); ?>">
      </td>
    </tr>
    <tr>
      <td colspan="2"><br><br>
        <input type="submit" value="Submit">
      </td>
    </tr>
  </table>
</form>
<?
include("../templates/admin-footer.txt");
?>