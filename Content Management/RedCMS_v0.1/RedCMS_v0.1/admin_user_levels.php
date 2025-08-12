<?php

  session_start();

  include"top.php";

  connect();

  access("10");

  if($end) {

    $sql = "UPDATE redcms_users SET redcms_users.user_level = '" . $level . "' WHERE redcms_users.user_id ='" . $id . "'";

    mysql_query($sql) or die("ERROR: Failed to execute SQL query");

    echo "User's level has been updated.<br><br>";

  }

  if($step) {

?>

 2. Select the users new access level from the list below.<br><br>

<form method="post" action="<?php echo $PHP_SELF?>">

    <select Name="level">

      <?php 

         $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_id = '" . $id . "'";

         $result = mysql_query($sql);

         $num = mysql_num_rows($result);

         $userLevelID = mysql_result($result, $i, "redcms_users.user_level");

         $sql = "SELECT * FROM redcms_user_levels ORDER BY redcms_user_levels.level_id ASC";

         $result = mysql_query($sql);

         $num = mysql_num_rows($result);

         for($i = 0; $i < $num; $i++) {

           $levelID = mysql_result($result, $i, "redcms_user_levels.level_id");
           $levelName = mysql_result($result, $i, "redcms_user_levels.level_name");

           if($levelID == $userLevelID) { $selected = "SELECTED"; } else { $selected = ""; }

           echo"<option value='" . $levelID . "'" . $selected . ">" . $levelName . "</option>";

         } ?>


    </select>

    <input type="hidden" name="id" value="<?php echo $id; ?>">

<input type="Submit" name="end" value="Next">

<?php

  include"bottom.php";
  exit();

  }

?>

  1. Select a username from the list below.<br><br>

<?php

  $sql = "SELECT * FROM redcms_users ORDER BY redcms_users.user_uname ASC";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

?>

<form method="post" action="<?php echo $PHP_SELF?>">

<?php

  echo '<select Name="id">';

  for($i = 0; $i < $num; $i++) {

    $userID = mysql_result($result, $i, "redcms_users.user_id");
    $userUName = mysql_result($result, $i, "redcms_users.user_uname");

    echo "<option value='" . $userID . "'>" . $userUName . "</option>";

  }

  echo '</select>';

?>

<input type="Submit" name="step" value="Next">

<?php
 
  include"bottom.php";

?>