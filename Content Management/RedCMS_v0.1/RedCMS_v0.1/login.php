<?php

  session_start();

  $l = "login";

  include"top.php";

  if($login) {

    $error = 0;
    $errorMessage = "";

    if($username == NULL) { $error = 1; $errorMessage .= "Username must not be blank<br>"; }
    if($password == NULL) { $error = 1; $errorMessage .= "Password must not be blank<br>"; }

    if($error == 1) { echo "<b>ERROR:</b><br>" . $errorMessage . "</b><br>"; } else {

      connect();

      $password = md5($password);

      $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_uname = '" . $username . "' AND redcms_users.user_password = '" . $password . "'";

      $result = mysql_query($sql);

      $num = mysql_num_rows($result);

      if($num == 1) {

        $userID = mysql_result($result, "0", "redcms_users.user_id");
        $userUName = mysql_result($result, "0", "redcms_users.user_uname");
        $userLevel = mysql_result($result, "0", "redcms_users.user_level");

        $sql = "SELECT * FROM redcms_user_themes LEFT JOIN redcms_themes ON redcms_user_themes.theme_id = redcms_themes.theme_id WHERE redcms_user_themes.user_id ='" . $userID . "'";

        $result = mysql_query($sql);

        $themePath = mysql_result($result, "0", "redcms_themes.theme_path");

        $_SESSION['redIn'] = 'TRUE';
        $_SESSION['redUserID'] = $userID;
        $_SESSION['redUserLevel'] = $userLevel;
        $_SESSION['redThemePath'] = $themePath;

        if(isActive() == 'FALSE') { 

          $_SESSION['redIn'] = 'ACTIVATE';

          echo '<meta http-equiv="Refresh" content="0;url=activate.php">';

          include"bottom.php";

          exit();

        }

        echo "Welcome " . $userUName . " you are now logged in.";

        echo '<meta http-equiv="Refresh" content="0;url=index.php">';

        include"bottom.php";

        exit();

      } else {

        echo "<b>Login Failed: Invalid username / password combination. </b><br><br>";

      }
 
    }

  }

  loginForm();

?>

<br>

<a href='forgot_pw.php'>Forgotten your password?</a>

<?php

  include"bottom.php";

?>