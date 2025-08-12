<?php

  include"top.php";

  connect();

  access(10);

  if($edit) {

    // Validate

    $error = 0;

    if($levelName == NULL) { $error = 1; echo "ERROR: Level Name must not be blank."; }

    if($error != "0") { include"bottom.php"; exit(); }

    // Update

    $sql = "UPDATE redcms_user_levels SET redcms_user_levels.level_name = '" . $levelName . "' WHERE redcms_user_levels.level_id = '" . $levelID . "'";

    mysql_query($sql) or die("ERROR: Failed to update user level");

    echo "User level has been updated.<br><br>";

  }

  // Display user level information

  $sql = "SELECT * FROM redcms_user_levels ORDER BY redcms_user_levels.level_id ASC";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  echo "<table>";

  echo '<tr class="tr1"><td>Level</td><td>Name</td><td></td></tr>';

  for($i=0; $i<$num; $i++) {

    $levelID = mysql_result($result, $i, "redcms_user_levels.level_id");
    $levelName = mysql_result($result, $i, "redcms_user_levels.level_name");

    if($e != $levelID) {

      $options = "[ <a href='?e=" . $levelID . "'>Edit</a> ] ";

    } else { $options = "[ <a href='?'>Edit</a> ] "; }

    echo '<tr class="tr2">';

    echo"<td>" . $levelID . "</td><td>" . $levelName . "</td><td>" . $options . "</td></tr>";

    if($e == $levelID) {

      // Display the edit form

      echo '<tr class="tr3"><td colspan="5">';

?>

  <form method="post" action="<?php echo $PHP_SELF?>">

    Level Name: <input type="text" name="levelName" value="<?php echo $levelName; ?>"><br>

    <input type="hidden" name="levelID" value="<?php echo $levelID; ?>"><br>

    <input type="submit" name="edit" value="Submit">

  </form>

<?php
      echo"</td></tr>";

    }

  }

  echo "</table>";

  include"bottom.php";

?>
