<?php

  session_start();

  $l = "profile";

  include"top.php";

  connect();

  if($changeTheme) {

      $sql = "UPDATE redcms_user_themes SET theme_id = '" . $theme . "' WHERE user_id ='" . $_SESSION['redUserID'] . "'";

      mysql_query($sql) or die("ERROR: Could not change theme.");

      $sql = "SELECT * FROM redcms_user_themes LEFT JOIN redcms_themes ON redcms_user_themes.theme_id = redcms_themes.theme_id WHERE redcms_user_themes.user_id ='" . $_SESSION['redUserID'] . "'";

      $result = mysql_query($sql);

      $num = mysql_num_rows($result);

      if($num == 0) { 

        $sql = "INSERT INTO redcms_user_themes VALUES ('" . $_SESSION['redUserID'] . "', '" . $theme . "')";

        mysql_query($sql) or die(mysql_error());

        $sql = "SELECT * FROM redcms_user_themes LEFT JOIN redcms_themes ON redcms_user_themes.theme_id = redcms_themes.theme_id WHERE redcms_user_themes.user_id ='" . $_SESSION['redUserID'] . "'";

        $result = mysql_query($sql);

      }

      $themePath = mysql_result($result, "0", "redcms_themes.theme_path");

      $_SESSION['redThemePath'] = $themePath;

      echo"Your chosen theme has been updated.<br><br>";

  }

  if($edit) {

    echo "editing...<br><br>";

    if($pass1 != NULL && $pass1 == $pass2) {

      $password = md5($pass1);

      $sql = "UPDATE redcms_users SET user_password = '" . $password . "' WHERE user_id ='" . $_SESSION['redUserID'] . "'";

      mysql_query($sql) or die($sql);

      echo"Your password has been updated.<br><br>";

    }

    $dob = $year . "-" . $month . "-" . $day;

    $sql = "UPDATE redcms_users SET user_uname = '" . $username . "', user_name = '" . $name . "', user_email = '" . $email . "', user_location = '" . $location . "', user_gender = '" . $gender . "', user_dob = '" . $dob . "', user_site = '" . $website . "', user_msn = '" . $msn . "', user_yahoo = '" . $yahoo . "', user_aim = '" . $aim . "', user_icq = '" . $icq . "' WHERE user_id ='" . $_SESSION['redUserID'] . "'";

    mysql_query($sql) or die($sql);

    echo"Your profile has been updated.<br><br>";

  }

  if(!$id && !$u) {

    if(loggedIn() == 'TRUE') { $id = $_SESSION['redUserID']; }

  }

  if($u) {

    $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_uname = '" . $u . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num != 0) { $id = mysql_result($result, "0", "redcms_users.user_id"); }

  }

  if($id == $_SESSION['redUserID']) { $e = 'TRUE'; }

  $sql = "SELECT * FROM redcms_users LEFT JOIN redcms_user_levels ON redcms_users.user_level = redcms_user_levels.level_id WHERE redcms_users.user_id = '" . $id . "'";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  if($num == 1) {

    $userID = mysql_result($result, "0", "redcms_users.user_id");
    $userUName = mysql_result($result, "0", "redcms_users.user_uname");
    $userLevel = mysql_result($result, "0", "redcms_users.user_level");

    $levelName = mysql_result($result, "0", "redcms_user_levels.level_name");

    $userName = mysql_result($result, "0", "redcms_users.user_name");
    $userEmail = mysql_result($result, "0", "redcms_users.user_email");
    $userLocation = mysql_result($result, "0", "redcms_users.user_location");
    $userGender = mysql_result($result, "0", "redcms_users.user_gender");
    $userDOB = mysql_result($result, "0", "redcms_users.user_dob");
    $userWebsite = mysql_result($result, "0", "redcms_users.user_site");
    $userMSN = mysql_result($result, "0", "redcms_users.user_msn");
    $userAIM = mysql_result($result, "0", "redcms_users.user_aim");
    $userYahoo = mysql_result($result, "0", "redcms_users.user_yahoo");
    $userICQ = mysql_result($result, "0", "redcms_users.user_icq");
    $userJoinedDate = mysql_result($result, "0", "redcms_users.user_joined_date");
    $userJoinedTime = mysql_result($result, "0", "redcms_users.user_joined_time");

    $userDOBYear = $userDOB{0} . $userDOB{1} . $userDOB{2} . $userDOB{3};
    $userDOBMonth = $userDOB{5} . $userDOB{6};
    $userDOBDay = $userDOB{8} . $userDOB{9};

    $userAge = age($userDOB);

    if($e == 'TRUE') { $options = "[ <a href='?ed=1'>edit</a> ]"; }

    if($ed == 1) {

      if($userGender == 'Male') { $gender = 'Male'; $othergender = 'Female'; } else { $gender = 'Female'; $othergender = 'Male'; }

?>

<form method="post" action="<?php echo $PHP_SELF?>">

<table>

  <tr><td class="td1">Username:</td><td class="td2"> <input type="text" name="username" value="<?php echo $userUName; ?>" readonly></td></tr>

  <tr><td class="td1">New Password:</td><td class="td2"> <input type="password" name="pass1"></td></tr>

  <tr><td class="td1">New Password Again:</td><td class="td2"> <input type="password" name="pass2"></td></tr>

  <tr><td class="td1">Name:</td><td class="td2"> <input type="text" name="name" value="<?php echo $userName; ?>"></td></tr>

  <tr><td class="td1">Email:</td><td class="td2"> <input type="text" name="email" value="<?php echo $userEmail; ?>"></td></tr>

  <tr><td class="td1">Location:</td><td class="td2"> <input type="text" name="location" value="<?php echo $userLocation; ?>"></td></tr>

  <tr><td class="td1">Gender:</td><td class="td2"> <select name="gender"> <option value="<?php echo $gender; ?>" SELECTED><?php echo $gender; ?> <option value="<?php echo $othergender; ?>"><?php echo $othergender; ?> </select></td></tr>

  <tr><td class="td1">Date of Birth:</td><td class="td2">

<select Name="day">

<?php

  for($i = 0; $i <= 31; $i++) {
    if($userDOBDay == $i) { $selected = "SELECTED"; } else { $selected = ""; }
    echo"<option value='" . $i . "'" . $selected . ">" . $i . "</option>";
  }

?>

</select>

<select Name="month">

<?php if($userDOBMonth == '01') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='01' <?php echo $selected; ?>>January</option>
<?php if($userDOBMonth == '02') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='02' <?php echo $selected; ?>>February</option>
<?php if($userDOBMonth == '03') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='03' <?php echo $selected; ?>>March</option>
<?php if($userDOBMonth == '04') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='04' <?php echo $selected; ?>>April</option>
<?php if($userDOBMonth == '05') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='05' <?php echo $selected; ?>>May</option>
<?php if($userDOBMonth == '06') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='06' <?php echo $selected; ?>>June</option>
<?php if($userDOBMonth == '07') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='07' <?php echo $selected; ?>>July</option>
<?php if($userDOBMonth == '08') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='08' <?php echo $selected; ?>>August</option>
<?php if($userDOBMonth == '09') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='09' <?php echo $selected; ?>>September</option>
<?php if($userDOBMonth == '10') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='10' <?php echo $selected; ?>>October</option>
<?php if($userDOBMonth == '11') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='11' <?php echo $selected; ?>>November</option>
<?php if($userDOBMonth == '12') { $selected = "SELECTED"; } else { $selected = ""; } ?>
<option value='12' <?php echo $selected; ?>>December</option>
</select>

<select Name="year">

<?php

  $j = date("Y") - 8;
  $k = $j - 80;

  for($i = $j; $i > $k; $i--) {
    if($userDOBYear == $i) { $selected = "SELECTED"; } else { $selected = ""; }
    echo"<option value='" . $i . "'" . $selected . ">" . $i . "</option>";
  }

?>

</select>

</td></tr>

  <tr><td class="td1">Website:</td><td class="td2"> <input type="text" name="website" value="<?php echo $userWebsite; ?>"></td></tr>

  <tr><td class="td1">MSN:</td><td class="td2"> <input type="text" name="msn" value="<?php echo $userMSN; ?>"></td></tr>

  <tr><td class="td1">AIM:</td><td class="td2"> <input type="text" name="aim" value="<?php echo $userAIM; ?>"></td></tr>

  <tr><td class="td1">Yahoo:</td><td class="td2"> <input type="text" name="yahoo" value="<?php echo $userYahoo; ?>"></td></tr>

  <tr><td class="td1">ICQ:</td><td class="td2"> <input type="text" name="icq" value="<?php echo $userICQ; ?>"></td></tr>

  <tr><td class="td1"><input type="Submit" name="edit" value="Edit"></td><td></td></tr>

</form>

<tr><td></td><td><br></td></tr>

<form method="post" action="<?php echo $PHP_SELF?>">

  <tr><td>Theme</td><td>

<select name="theme">

<?php

  $sql = "SELECT * FROM redcms_themes ORDER BY redcms_themes.theme_name ASC";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  for($i = 0; $i < $num; $i++) {

    $themeID = mysql_result($result, $i, "redcms_themes.theme_id");
    $themeName = mysql_result($result, $i, "redcms_themes.theme_name");
    $themePath = mysql_result($result, $i, "redcms_themes.theme_path");

    if($themePath == $_SESSION['redThemePath']) { $option = "SELECTED"; } else { $option = ""; }

    echo '<option value="' . $themeID . '"' . $option . '>' . $themeName;

  }

?>

</select>
</td></tr>
<tr><td><input type="Submit" name="changeTheme" value="Change Theme"></td><td></td></tr>

</form>

</table>

<?php

    } else {

      echo"<table>";

      echo "<tr><td class='td1'><b>" . $userUName . "'s Profile</b></td><td class='td2'>" . $options . "</td></tr>";
      echo "<tr><td class='td1'>Level:</td><td class='td2'> " . $levelName . "</td></tr>";
      echo "<tr><td class='td1'>Name:</td><td class='td2'> " . $userName . "</td></tr>";
      echo "<tr><td class='td1'>Gender:</td><td class='td2'> " . $userGender . "</td></tr>";
      echo "<tr><td class='td1'>Email:</td><td class='td2'> <a href='mailto:" . $userEmail . "'>" . $userEmail . "</a></td></tr>";
      echo "<tr><td class='td1'>Location:</td><td class='td2'> <a href='http://maps.google.co.uk/?q=" . $userLocation . "'>" . $userLocation . "</a></td></tr>";
      echo "<tr><td class='td1'>Age:</td><td class='td2'> " . $userAge . "</td></tr>";
      echo "<tr><td class='td1'>Website:</td><td class='td2'> <a href='" . $userWebsite . "' target='_blank'>" . $userWebsite . "</a></td></tr>";

      if($userMSN != NULL) { echo "<tr><td class='td1'>MSN:</td><td class='td2'> " . $userMSN . "</td></tr>"; }
      if($userAIM != NULL) { echo "<tr><td class='td1'>AIM:</td><td class='td2'> " . $userAIM . "</td></tr>"; }
      if($userYahoo != NULL) { echo "<tr><td class='td1'>Yahoo:</td><td class='td2'> " . $userYahoo . "</td></tr>"; }
      if($userICQ != NULL) { echo "<tr><td class='td1'>ICQ:</td><td class='td2'> " . $userICQ . "</td></tr>"; }

      echo "<tr><td class='td1'>Date Joined:</td><td class='td2'> " . $userJoinedDate . "</td></tr>";

      echo"</table>";

    }


  } else {

    echo "<b>Loading Failed: Invalid UserID. </b><br><br>";

  }

?>

<?php

  include"bottom.php";

?>
