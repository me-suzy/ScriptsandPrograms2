<?php

  include"top.php";

  connect();

  access(10);

  if($add) {

      $error = 0;

      if($fileName == NULL) { $error = 1; $errorMessage .= "Filename must not be blank.<br>"; }
      if($fileLink == NULL) { $error = 1; $errorMessage .= "Link must not be blank.<br>"; }
      if($fileSize == NULL) { $error = 1; $errorMessage .= "Size must not be blank.<br>"; }
      if($catID == NULL) { $error = 1; $errorMessage .= "Category must not be blank.<br>"; }
      if($fileDesc == NULL) { $error = 1; $errorMessage .= "Description must not be blank.<br>"; }

    if($error == 1) { echo "<b>ERROR:</b><br>" . $errorMessage . "<br>"; } else {

          $sql = "INSERT INTO redcms_files VALUES ('', '" . $catID . "', '" . $fileName . "', '" . $fileDesc . "', '" . $fileLink . "', '" . $fileSize . "', '0', NOW(), NOW())";

          $result = mysql_query($sql) or die("ERROR: Unable to add new file.");

          echo"File has been added.";

        }

  }

  if($delete) {

    $sql = "DELETE FROM redcms_files WHERE redcms_files.file_id = '" . $fileID . "'";

    mysql_query($sql) or die("ERROR: Failed to delete file");

    echo "File has been removed.<br><br>";

  }

  if($edit) {

    // Validate

    $error = 0;

    if($fileName == NULL) { $error = 1; echo "ERROR: File Name must not be blank.<br>"; }
    if($fileSize == NULL) { $error = 1; echo "ERROR: Size must not be blank.<br>"; }
    if($fileLink == NULL) { $error = 1; echo "ERROR: Link must not be blank.<br>"; }
    if($fileDesc == NULL) { $error = 1; echo "ERROR: Description must not be blank.<br>"; }

    if($error != "0") { include"bottom.php"; exit(); }

    // Update

    $sql = "UPDATE redcms_files SET redcms_files.file_name = '" . $fileName . "', redcms_files.file_desc = '" . $fileDesc . "', redcms_files.file_link = '" . $fileLink . "', redcms_files.file_size = '" . $fileSize . "', redcms_files.cat_id = '" . $catID . "' WHERE redcms_files.file_id = '" . $fileID . "'";

    mysql_query($sql) or die("ERROR: Failed to update file");

    echo "File has been updated.<br><br>";

  }

  // Display file information

  $sql = "SELECT * FROM redcms_files LEFT JOIN redcms_file_categories ON redcms_file_categories.cat_id = redcms_files.cat_id ORDER BY redcms_file_categories.cat_name ASC";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  if($num == 0 && !$a) { echo "ERROR: No Files Found."; include "bottom.php"; exit(); }

  if($num != 0) {

    echo'<table><tr class="tr1"><td>Filename</td><td>Size</td><td>No of Downloads</td><td>Date</td><td></td></tr>';

  }

  $lastCatID = -1;

  for($i=0; $i<$num; $i++) {

    $fileID = mysql_result($result, $i, "redcms_files.file_id");
    $fileName = mysql_result($result, $i, "redcms_files.file_name");
    $fileDesc = mysql_result($result, $i, "redcms_files.file_desc");
    $fileLink = mysql_result($result, $i, "redcms_files.file_link");
    $fileSize = mysql_result($result, $i, "redcms_files.file_size");
    $fileDownloads = mysql_result($result, $i, "redcms_files.file_downloads");
    $fileDate = mysql_result($result, $i, "redcms_files.file_date");
    $fileTime = mysql_result($result, $i, "redcms_files.file_time");

    $catID = mysql_result($result, $i, "redcms_file_categories.cat_id");
    $catName = mysql_result($result, $i, "redcms_file_categories.cat_name");

    if($catID != $lastCatID) {

      echo '<tr class="tr1"><td colspan="5">' . $catName . '</td></tr>';

    }

    $lastCatID = $catID;

    $options = "";

    if($a != $fileID) {

      $options .= "[ <a href='?a=" . $fileID . "'>Add</a> ] ";

    } else { $options .= "[ <a href='?'>Add</a> ] "; }

    if($e != $fileID) {

      $options .= "[ <a href='?e=" . $fileID . "'>Edit</a> ] ";

    } else { $options .= "[ <a href='?'>Edit</a> ] "; }

    if($d != $fileID) {

      $options .= "[ <a href='?d=" . $fileID . "'>Delete</a> ] ";

    } else { $options .= "[ <a href='?'>Delete</a> ] "; }

    echo '<tr class="tr2"><td>' . $fileName . '</td><td>' . $fileSize . ' mb</td><td>' . $fileDownloads . '</td><td>' . $fileDate . '</td><td>' . $options . '</td></tr>';

    if($d == $fileID) {

      // Display the delete form

      echo '<tr class="tr3"><td colspan="5"> Are you sure you want to delete this file?';

?>

  <form method="post" action="<?php echo $PHP_SELF?>">

    <input type="hidden" name="fileID" value="<?php echo $fileID; ?>">

    <input type="submit" name="delete" value="Yes">

  </form>

<?php

      echo"</td></tr>";

    }

    if($e == $fileID) {

      // Display the edit form

      echo '<tr class="tr3"><td colspan="5">';

?>

  <form method="post" action="<?php echo $PHP_SELF?>">

   <table>

    <tr><td>File Name:</td><td><input type="text" name="fileName" value="<?php if(!$a) { echo $fileName; } ?>"></td></tr>

    <tr><td>Link:</td><td><input type="text" name="fileLink" value="<?php if(!$a) { echo $fileLink; } ?>"></td></tr>

    <tr><td>Size:</td><td><input type="text" name="fileSize" value="<?php if(!$a) { echo $fileSize; } ?>"></td></tr>

<?php

    $origCatID = $catID;

               echo'<tr><td>Category:</td><td>';
            echo'<select name="catID">';

            $listSql = "SELECT * FROM redcms_file_categories ORDER BY redcms_file_categories.cat_name ASC";
            $listResult = mysql_query($listSql);
            $listNum = mysql_num_rows($listResult);

            for($j=0; $j < $listNum; $j++) {

              $catID = mysql_result($listResult, $j, "redcms_file_categories.cat_id");
              $catName = mysql_result($listResult, $j, "redcms_file_categories.cat_name");

              $option = "";
              if($origCatID == $catID) { $option = "SELECTED"; }

              echo'<option value="' . $catID . '"' . $option . '>' . $catName;

            }

            echo'</select>';

          echo'</td></tr>';

?>

    <tr><td>Description:</td><td><textarea name="fileDesc"><?php if(!$a) { echo $fileDesc; } ?></textarea></td></tr>

    <input type="hidden" name="fileID" value="<?php echo $fileID; ?>"></td></tr>

    <tr><td><input type="submit" name="edit" value="Submit"></td><td></td></tr>

   </table>

  </form>

<?php
      echo"</td></tr>";

    }

  }

  echo "</table>";

  if($a) {

 // Display the add form

    echo '<tr class="tr3"><td colspan="5">';

?>

  <form method="post" action="<?php echo $PHP_SELF?>">

   <table>

    <tr><td>File Name:</td><td><input type="text" name="fileName" value="<?php if(!$a) { echo $fileName; } ?>"></td></tr>

    <tr><td>Link:</td><td><input type="text" name="fileLink" value="<?php if(!$a) { echo $fileLink; } ?>"></td></tr>

    <tr><td>Size:</td><td><input type="text" name="fileSize" value="<?php if(!$a) { echo $fileSize; } ?>"></td></tr>

<?php

               echo'<tr><td>Category:</td><td>';
            echo'<select name="catID">';

            $listSql = "SELECT * FROM redcms_file_categories ORDER BY redcms_file_categories.cat_name ASC";
            $listResult = mysql_query($listSql);
            $listNum = mysql_num_rows($listResult);

            for($j=0; $j < $listNum; $j++) {

              $catID = mysql_result($listResult, $j, "redcms_file_categories.cat_id");
              $catName = mysql_result($listResult, $j, "redcms_file_categories.cat_name");

              echo'<option value="' . $catID . '">' . $catName;

            }

            echo'</select>';

          echo'</td></tr>';

?>

    <tr><td>Description:</td><td><textarea name="fileDesc"><?php if(!$a) { echo $fileDesc; } ?></textarea></td></tr>

    <tr><td><input type="submit" name="add" value="Submit"></td><td></td></tr>

   </table>

  </form>

<?php

  echo"</td></tr>";

  echo "</table>";

  }

  include"bottom.php";

?>
