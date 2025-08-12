<?php

  include"top.php";

  connect();

  if($key) {

    $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_uname = '" . $username . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num == 1) {

      $userID = mysql_result($result, "0", "redcms_users.user_id");
      $userKey = mysql_result($result, "0", "redcms_users.user_key");
      $userEmail = mysql_result($result, "0", "redcms_users.user_email");

      $userKey = rand(111111111,999999999);

      $to      = $userEmail;
      $subject = 'Activation';
      $message = "Welcome, please activate at " . $_SERVER['SERVER_NAME'] . "/activate.php \n\n Your key is: " . $userKey . " \n\n Thanks";
      $headers = 'From: webmaster@' . $_SERVER['SERVER_NAME'] . "\r\n" .
      'Reply-To: webmaster@' . $_SERVER['SERVER_NAME'] . "\r\n" .
      'X-Mailer: PHP/' . phpversion();

      mail($to, $subject, $message, $headers);

      $sql = "UPDATE redcms_users SET redcms_users.user_key = '" . $userKey . "' WHERE redcms_users.user_id = '" . $userID . "'";

      $result = mysql_query($sql) or die("ERROR: Failed to update new key.");

      echo "You will shortly recieve your activation key by email, to activate go <a href='activate.php'>here</a>.<br><br>";

    } else {

        echo "ERROR: Invalid Username.<br><br>";
 
    }
      

  }

?>

<form method="post" action="<?php echo $PHP_SELF?>">

Please enter your username then press the Get Key button.<br><br>

<table>

  <tr><td>Username:</td><td> <input type="text" name="username" value="<?php echo $username; ?>"></td></tr>

  <tr><td><input type="Submit" name="key" value="Get Key"></td><td></td></tr>

</table>

</form>

<br><a href='activate.php'>Activate!</a><br><br>

<?php

  include"bottom.php";

?>