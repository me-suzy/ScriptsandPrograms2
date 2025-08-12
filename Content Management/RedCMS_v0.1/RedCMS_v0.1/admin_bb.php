<?php

  include"top.php";

  connect();

  access(10);

  if($add) {

      $error = 0;

      if($bbTag == NULL) { $error = 1; $errorMessage .= "BB tag must not be blank.<br>"; }
      if($bbCode == NULL) { $error = 1; $errorMessage .= "HTML code must not be blank.<br>"; }
      if($bbDesc == NULL) { $error = 1; $errorMessage .= "Description must not be blank.<br>"; }

    if($error == 1) { echo "<b>ERROR:</b><br>" . $errorMessage . "<br>"; } else {

          $sql = "INSERT INTO redcms_bb VALUES ('', '" . $bbTag . "', '" . $bbCode . "', '" . $bbDesc . "')";

          $result = mysql_query($sql) or die("ERROR: Unable to add new BB Code.");

          echo"BB Code has been added.";

        }

  }

  if($delete) {

    $sql = "DELETE FROM redcms_bb WHERE redcms_bb.bb_id = '" . $bbID . "'";

    mysql_query($sql) or die("ERROR: Failed to delete BB Code");

    echo "BB Code has been removed.<br><br>";

  }

  if($edit) {

    // Validate

    $error = 0;

      if($bbTag == NULL) { $error = 1; $errorMessage .= "BB tag must not be blank.<br>"; }
      if($bbCode == NULL) { $error = 1; $errorMessage .= "HTML code must not be blank.<br>"; }
      if($bbDesc == NULL) { $error = 1; $errorMessage .= "Description must not be blank.<br>"; }

    if($error != "0") { include"bottom.php"; exit(); }

    // Update

    $sql = "UPDATE redcms_bb SET redcms_bb.bb_tag = '" . $bbTag . "', redcms_bb.bb_desc = '" . $bbDesc . "', redcms_bb.bb_code = '" . $bbCode . "' WHERE redcms_bb.bb_id = '" . $bbID . "'";

    mysql_query($sql) or die("ERROR: Failed to update BB Code");

    echo "BB Code has been updated.<br><br>";

  }

  // Display BB Code information

  $sql = "SELECT * FROM redcms_bb ORDER BY redcms_bb.bb_tag ASC";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  echo "<table>";

  echo'<tr class="tr1"><td>BB Tag</td><td>HTML Code</td><td>Description</td><td></td></tr>';

  for($i=0; $i<$num; $i++) {

    $bbID = mysql_result($result, $i, "redcms_bb.bb_id");
    $bbTag = mysql_result($result, $i, "redcms_bb.bb_tag");
    $bbCode = mysql_result($result, $i, "redcms_bb.bb_code");
    $bbDesc = mysql_result($result, $i, "redcms_bb.bb_desc");

    $options = "";

    if($a != $bbID) {

      $options .= "[ <a href='?a=" . $bbID . "'>Add</a> ] ";

    } else { $options .= "[ <a href='?'>Add</a> ] "; }

    if($e != $bbID) {

      $options .= "[ <a href='?e=" . $bbID . "'>Edit</a> ] ";

    } else { $options .= "[ <a href='?'>Edit</a> ] "; }

    if($d != $bbID) {

      $options .= "[ <a href='?d=" . $bbID . "'>Delete</a> ] ";

    } else { $options .= "[ <a href='?'>Delete</a> ] "; }

    echo '<tr class="tr2"><td>' . $bbTag . '</td><td>' . strip_tags(htmlspecialchars($bbCode)) . '</td><td>' . $bbDesc . '</td><td>' . $options . '</td></tr>';

    if($d == $bbID) {

      // Display the delete form

      echo '<tr class="tr3"><td colspan="5"> Are you sure you want to delete this BB Code?';

?>

  <form method="post" action="<?php echo $PHP_SELF?>">

    <input type="hidden" name="bbID" value="<?php echo $bbID; ?>">

    <input type="submit" name="delete" value="Yes">

  </form>

<?php

      echo"</td></tr>";

    }

    if($e == $bbID || $a == $bbID) {

      // Display the edit form

      echo '<tr class="tr3"><td colspan="5">';

?>

  <form method="post" action="<?php echo $PHP_SELF?>">

   <table>

    <tr><td>BB Tag:</td><td><input type="text" name="bbTag" value="<?php if(!$a) { echo $bbTag; } ?>"></td></tr>

    <tr><td>HTML Code:</td><td><input type="text" name="bbCode" value="<?php if(!$a) { echo $bbCode; } ?>"></td></tr>

    <tr><td>Description:</td><td><textarea name="bbDesc"><?php if(!$a) { echo $bbDesc; } ?></textarea></td></tr>


    <input type="hidden" name="bbID" value="<?php echo $bbID; ?>"></td></tr>

<?php

    if($e == $bbID) { echo '<tr><td><input type="submit" name="edit" value="Submit"></td><td></td></tr>'; }
    if($a == $bbID) { echo '<tr><td><input type="submit" name="add" value="Submit"></td><td></td></tr>'; }

?>

   </table>

  </form>

<?php
      echo"</td></tr>";

    }

  }

  echo "</table>";

  include"bottom.php";

?>
