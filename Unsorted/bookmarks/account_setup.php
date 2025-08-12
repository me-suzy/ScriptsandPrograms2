<?

$APB_SETTINGS['allow_search_box'] = 0;

if ($username && $password && ($password == $password_verify)) {
    $crypted_password = crypt($password, '27');
    debug("Username: $username", 2);
    debug("Password: $password -> $crypted_password", 2);

    $query = "
        INSERT INTO apb_users
        (username, password)
        VALUES
        ('$username', '$crypted_password')
    ";
//echo $query;
    $result = mysql_db_query($APB_SETTINGS['apb_database'], $query);

    if (!$result) {   echo "<p>Error!!! No account created.  You may want to close your browser and try again."; }
    else { echo "<p>Account created!  Now <a href='cookie_auth.php?action=cookie_login'>login</a> to start using APB.\n";; }

} else {

$APB_SETTINGS['allow_login'] = 0;

?>
<p>Welcome to Active PHP Bookmarks.  Please set up your account...

<?

// Display password verify error.
if ($password != $password_verify) { echo "<p><font color='red'>Your passwords didn't match, try again.</font>"; }

?>

<p><form action="<?= $SCRIPT_NAME ?>" method="post">
<table cellpadding="0" cellspacing="5" border="0">
<tr>
  <td>Username:</td>
  <td><input type="text" name="username" value="<?= $username ?>"></td>
</tr>
<tr>
  <td>Password:</td>
  <td><input type="password" name="password"></td>
</tr>
<tr>
  <td>Verify Password:</td>
  <td><input type="password" name="password_verify"></td>
</tr>
</table>

<p><input type="submit" value="Create Account">

</form>

<?
}
?>
