<?php

  session_start();

  include"top.php";

  connect();

  if($id != NULL && $key != NULL) {

    $sql = "UPDATE redcms_users SET user_active = 'TRUE', user_level = '1' WHERE redcms_users.user_id ='" . $id ."' AND redcms_users.user_key = '" . $key . "' LIMIT 1";

    mysql_query($sql) or die("INVALID");

    echo"Your account has been activated.<br><br>";

  }

  if($activate) {

    $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_uname = '" . $username . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num == 1) {

      $userID = mysql_result($result, "0", "redcms_users.user_id");
      $userKey = mysql_result($result, "0", "redcms_users.user_key");

      if($key == $userKey) {

        $sql = "UPDATE redcms_users SET user_active = 'TRUE' WHERE user_id ='" . $userID ."'";

        mysql_query($sql) or die($sql);

        echo"Your account has been activated.<br><br>";

      } else {

        echo "ERROR: Invalid Key/Username combination.<br><br>";
 
      }

    }      

  }

?>

<form method="post" action="<?php echo $PHP_SELF?>">

Please enter your username and activation key and then press the activate button.<br><br>

<table>

  <tr><td>Username:</td><td> <input type="text" name="username" value="<?php echo $username; ?>"></td></tr>

  <tr><td>Activate Key:</td><td> <input type="text" name="key"></td></tr>

  <tr><td><input type="Submit" name="activate" value="Activate"></td><td></td></tr>

</table>

</form>

<br><a href='get_key.php'>lost your key?</a><br><br>

<?php

  include"bottom.php";

?>