<?php

  $l = "register"; include"top.php";

  if($register) {

    $error = 0;
    $errorMessage = "";

    if($username == NULL) { $error = 1; $errorMessage .= "Username must not be blank.<br>"; }
    if($pass1 == NULL) { $error = 1; $errorMessage .= "Password must not be blank.<br>"; }
    if($pass2 == NULL) { $error = 1; $errorMessage .= "Password Again must not be blank.<br>"; }
    if($name == NULL) { $error = 1; $errorMessage .= "Name must not be blank.<br>"; }
    if($email == NULL) { $error = 1; $errorMessage .= "Email must not be blank.<br>"; }
    if($gender == NULL) { $error = 1; $errorMessage .= "Gender must not be blank.<br>"; }
    if($day == NULL || $month == NULL || $year == NULL) { $error = 1; $errorMessage .= "Date of Birth must not be blank.<br>"; }

    if(!checkdate($month, $day, $year)) { $error = 1; $errorMessage .= "Date of Birth is invalid.<br>"; }

    if($pass1 != $pass2) { $error = 1; $errorMessage .= "Password and Password Again do not match.<br>"; }

    if($error == 1) { echo "<b>ERROR:</b><br>" . $errorMessage . "</b><br>"; } else {

      connect();

      $password = md5($pass1);
      $level = 0;
      $dob = $year . "-" . $month . "-" . $day;
      $key = rand(111111111,999999999);

      $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_uname = '" . $username . "' OR redcms_users.user_email = '" . $email . "'";

      $result = mysql_query($sql);

      $num = mysql_num_rows($result);

      if($num == 0) {

        $sql = "INSERT INTO redcms_users VALUES ('', '" . $username . "', '" . $password . "', '" . $level . "', '" . $name . "', '" . $email . "', '" . $location . "', '" . $gender . "', '" . $dob . "', '" . $website . "', '" . $msn . "', '" . $aim . "', '" . $yahoo . "', '" . $icq . "', NOW(), NOW(), 'FALSE', '" . $key . "')";

        $result = mysql_query($sql);

        $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_uname = '" . $username . "'";

        $result = mysql_query($sql);

        $userID = mysql_result($result, "0", "redcms_users.user_id");
        $userName = mysql_result($result, "0", "redcms_users.user_id");
        $userKey = mysql_result($result, "0", "redcms_users.user_key");

        $sql = "INSERT INTO redcms_user_themes VALUES ('" . $userID . "', '1')";

        $result = mysql_query($sql);

        $to      = $email;
        $subject = 'Activation';
        $message = "Welcome, please activate at:\nhttp://" . $_SERVER['SERVER_NAME'] . "/activate.php?id=" . $userID . "&key=" . $userKey . "\nYour key is: " . $key . " \nThanks";
        $headers = 'From: webmaster@' . $_SERVER['SERVER_NAME'] . "\r\n" .
        'Reply-To: webmaster@' . $_SERVER['SERVER_NAME'] . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);

        echo $username . " you are now registered.";

        echo '<meta http-equiv="Refresh" content="0;url=index.php">';

        include"bottom.php";

        exit();

      } else {

        echo "<b>Registration Failed: Username or email address is already registered. </b><br><br>";

      }
 
    }

  }

  if($gender == NULL) { $gender = 'Male'; $othergender = 'Female'; } else {

    if($gender == 'Male') { $othergender = 'Female'; } else { $othergender = 'Male'; } 

  }

?>

<form method="post" action="<?php echo $PHP_SELF?>">

<table>

  <tr><td>Username:</td><td> <input type="text" name="username" value="<?php echo $username; ?>"></td></tr>

  <tr><td>Password:</td><td> <input type="password" name="pass1"></td></tr>

  <tr><td>Password Again:</td><td> <input type="password" name="pass2"></td></tr>

  <tr><td>Name:</td><td> <input type="text" name="name" value="<?php echo $name; ?>"></td></tr>

  <tr><td>Email:</td><td> <input type="text" name="email" value="<?php echo $email; ?>"></td></tr>

  <tr><td>Location:</td><td> <input type="text" name="location" value="<?php echo $location; ?>"></td></tr>

  <tr><td>Gender:</td><td> <select name="gender"> <option value="<?php echo $gender; ?>" SELECTED><?php echo $gender; ?> <option value="<?php echo $othergender; ?>"><?php echo $othergender; ?> </select></td></tr>

  <tr><td>Date of Birth:</td><td> 

<select Name="day">
<option value='01'>01</option>
<option value='02'>02</option>
<option value='03'>03</option>
<option value='04'>04</option>
<option value='05'>05</option>
<option value='06'>06</option>
<option value='07'>07</option>
<option value='08'>08</option>
<option value='09'>09</option>
<option value='00'>10</option>
<option value='11'>11</option>
<option value='12'>12</option>
<option value='13'>13</option>
<option value='14'>14</option>
<option value='15'>15</option>
<option value='16'>16</option>
<option value='17'>17</option>
<option value='18'>18</option>
<option value='19'>19</option>
<option value='20'>20</option>
<option value='21'>21</option>
<option value='22'>22</option>
<option value='23'>23</option>
<option value='24'>24</option>
<option value='25'>25</option>
<option value='26'>26</option>
<option value='27'>27</option>
<option value='28'>28</option>
<option value='29'>29</option>
<option value='30'>30</option>
<option value='31'>31</option>
</select>

<select Name="month">
<option value='01'>January</option>
<option value='02'>February</option>
<option value='03'>March</option>
<option value='04'>April</option>
<option value='05'>May</option>
<option value='06'>June</option>
<option value='07'>July</option>
<option value='08'>August</option>
<option value='09'>September</option>
<option value='10'>October</option>
<option value='11'>November</option>
<option value='12'>December</option>
</select>

<select Name="year">

<?php

  $j = date("Y") - 8;
  $k = $j - 80;

  for($i = $j; $i > $k; $i--) {
    echo"<option value='" . $i . "'>" . $i . "</option>";
  }

?>

</select>

</td></tr>

  <tr><td>Website:</td><td> <input type="text" name="website" value="<?php echo $website; ?>"></td></tr>

  <tr><td>MSN:</td><td> <input type="text" name="msn" value="<?php echo $msn; ?>"></td></tr>

  <tr><td>AIM:</td><td> <input type="text" name="aim" value="<?php echo $aim; ?>"></td></tr>

  <tr><td>Yahoo:</td><td> <input type="text" name="yahoo" value="<?php echo $yahoo; ?>"></td></tr>

  <tr><td>ICQ:</td><td> <input type="text" name="icq" value="<?php echo $icq; ?>"></td></tr>

  <tr><td><input type="Submit" name="register" value="Register"></td><td></td></tr>

</table>

</form>

<br>

<?php

  include"bottom.php";

?>