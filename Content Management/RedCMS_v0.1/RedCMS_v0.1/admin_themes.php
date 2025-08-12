<?php

  $l = "Themes";

  include"top.php";

  access(10);

  connect();

  if($add) {

    $dir = "redStyles/" . $_FILES['theme']['name'];

    move_uploaded_file($_FILES['theme']['tmp_name'], $dir) or die("ERROR: Failed to upload file.");

    $themeFileName = $_FILES['theme']['name'];

    $sql = "INSERT INTO redcms_themes VALUES ('', '" . $themeName . "', '" . $themeFileName . "')";

    mysql_query($sql) or die("ERROR: Failed to execute SQL query");

    echo "Theme has been added to the database.";

  }

  if($edit) {

    $sql = "UPDATE redcms_themes SET redcms_themes.theme_name = '" . $themeName . "' WHERE redcms_themes.theme_id = '" . $id . "' LIMIT 1";

    mysql_query($sql) or die("ERROR: Failed to execute SQL query");

    echo "Theme has been updated.";

  }

  if($delete) {

    $sql = "DELETE FROM redcms_themes WHERE redcms_themes.theme_id = '" . $id . "' LIMIT 1";

    mysql_query($sql) or die("ERROR: Failed to execute SQL query");

    $sql = "UPDATE redcms_user_themes SET redcms_user_themes.theme_id = '1' WHERE redcms_user_themes.theme_id = '" . $id . "'";

    mysql_query($sql) or die("ERROR: Failed to execute SQL query");

    echo "Theme has been removed from the database.<br>Users with this theme have been set to default.";

  }

  if($a || $ed) {


    if($ed) {

      $sql = "SELECT * FROM redcms_themes WHERE redcms_themes.theme_id = '" . $ed . "'";

      $result = mysql_query($sql);

      $num = mysql_num_rows($result);

      if($num == 0) { echo "ERROR: Theme not found in database."; include"bottom.php"; exit(); }

      $themeID = mysql_result($result, "0", "redcms_themes.theme_id");
      $themeName = mysql_result($result, "0", "redcms_themes.theme_name");

    }

?>

<form method="post" enctype="multipart/form-data" action="<?php echo $PHP_SELF?>">

  <table>

    <tr><td>Theme Name:</td><td><input type="text" name="themeName" value="<?php echo $themeName; ?>"></td></tr>
    <?php if(!$ed) { echo'<tr><td>File:</td><td><input name="theme" type="file"></td></tr>'; } ?>

  </table>

  <input type="hidden" name="id" value="<?php echo $ed; ?>">

<?php

  if($a) {

  echo'<input type="Submit" name="add" value="Add">';

  } else if($ed) {

  echo'<input type="Submit" name="edit" value="Edit">';

  }

?>

</form>

<?php


  }

  if($del) {

    $sql = "SELECT * FROM redcms_themes WHERE redcms_themes.theme_id = '" . $del . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num == 0) { echo "ERROR: Theme not found in database."; include"bottom.php"; exit(); }

    $themeID = mysql_result($result, "0", "redcms_themes.theme_id");
    $themeName = mysql_result($result, "0", "redcms_themes.theme_name");

?>

Are you sure you want to delete <?php echo $themeName; ?>?

<form method="post" action="<?php echo $PHP_SELF?>">

  <input type="hidden" name="id" value="<?php echo $del; ?>">

  <input type="Submit" name="delete" value="Yes">

</form>


<?php

  }

  // Fetch a list of themes

  $sql = "SELECT * FROM redcms_themes ORDER BY redcms_themes.theme_name ASC";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  if($num == 0) { echo "ERROR: No themes found in database."; include"bottom.php"; exit(); }

  echo "<table>";

    echo "<tr class='tr1'><td>Theme</td><td></td><td></td></tr>";

  for($i = 0; $i < $num; $i++) {

    $themeID = mysql_result($result, $i, "redcms_themes.theme_id");
    $themeName = mysql_result($result, $i, "redcms_themes.theme_name");
    $themePath = mysql_result($result, $i, "redcms_themes.theme_path");

    $options = "";

    if($e || $ed) { $options .= "<a href='?ed=" . $themeID . "'>Edit</a>"; }
    if($d || $del) { $options .= "<a href='?del=" . $themeID . "'>Delete</a>"; }

    $rThemeID = $themeID;

    if($themeID == $id) { $themeID = -1; }

    echo "<tr class='tr2'><td><a href='?v=" . $themeID . "'>" .$themeName . "</a></td><td><a href='?v=" . $themeID . "'>View source</a></td><td>" . $options . "</td></tr>";

    if($v == $rThemeID) {

      $lines = file("redStyles/" . $themePath);

      $css = "";

      foreach ($lines as $line_num => $line) {
        $css .= "#<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br>\n";
      }

      echo"<tr class='tr3'><td colspan='3'>" . $css . "</td></tr>";

    }

  }

  echo "</table>";

  include"bottom.php";

?>