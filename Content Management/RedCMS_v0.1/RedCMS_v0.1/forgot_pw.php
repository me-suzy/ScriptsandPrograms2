<?php

  include"top.php";

  connect();

  if($getpw) {

    $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_uname = '" . $username . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num == 1) {

      $userID = mysql_result($result, "0", "redcms_users.user_id");
      $userKey = mysql_result($result, "0", "redcms_users.user_key");
      $userEmail = mysql_result($result, "0", "redcms_users.user_email");

      if($key == $userKey) {

        $randPass = rand(111111111,999999999);

        $to      = $userEmail;
        $subject = 'New Password';
        $message = "Your password has been set to: " . $randPass;
        $headers = 'From: webmaster@' . $_SERVER['SERVER_NAME'] . "\r\n" .
        'Reply-To: webmaster@' . $_SERVER['SERVER_NAME'] . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);

        $randPass = md5($randPass);

        $sql = "UPDATE redcms_users SET redcms_users.user_password = '" . $randPass . "' WHERE redcms_users.user_id = '" . $userID . "'";

        $result = mysql_query($sql) or die("ERROR: Failed to update new password.");

        echo "You will shortly recieve your new password by email.<br><br>";

        } else { echo "ERROR: Invalid Key.<br><br>"; }

    } else {

        echo "ERROR: Invalid Username/Key combination.<br><br>";
 
    }
      

  }

?>

<form method="post" action="<?php echo $PHP_SELF?>">

1. Please go <a href='get_key.php'>here</a> and get a new activation key.<br><br>

2. Fill in the form below and press the reset button.<br><br>

<table>

  <tr><td>Username:</td><td> <input type="text" name="username" value="<?php echo $username; ?>"></td></tr>

  <tr><td>Key:</td><td> <input type="text" name="key" value="<?php echo $key; ?>"></td></tr>

  <tr><td><input type="Submit" name="getpw" value="Reset"></td><td></td></tr>

</table>

</form>

<br><a href='login.php'>Login!</a><br><br>

<?php

  include"bottom.php";

?>