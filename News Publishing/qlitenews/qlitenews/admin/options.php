<div class="title">Options</div>
<div>
  <form method="post" action="index.php?page=options">
    <strong>New Username:</strong><br/>
    <input type="text" name="nname" maxlength="20" size="40"/><br/>
    <strong>New Password:</strong><br/>
    <input type="text" name="npass" size="40"/><br/>
    <input type="submit" name="nupdate" value="Update"/>
  </form>
</div>
<p>Other qliteNews options can be change in config.php file inside the admin folder. Explanation of all options are also explained in the file.</p>
<?php
  if ($_POST['nupdate']) {
    $user = $_POST['nname'];
    $pass = $_POST['npass'];
    $pass = md5($pass);
    if ($user == "" || $pass == "") {
      echo "<p><strong>Error: You cannot leave a blank field. Please make sure you fill in both username and password field.</strong></p>";
    }
    else {
      include("config.php");
      $db = mysql_connect($dbhost,$dbuser,$dbpass); 
      mysql_select_db($dbname) or die("Cannot connect to database");
      mysql_query("UPDATE qlitenews_users SET user='$user'");
      mysql_query("UPDATE qlitenews_users SET password='$pass'");
      echo "<p><strong>Your login information has been changed. Please remember your new username and password.</strong></p>";
    }
  }
?>