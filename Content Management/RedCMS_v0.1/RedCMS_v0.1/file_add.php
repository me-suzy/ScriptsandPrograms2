<?php

  include"top.php";

  access(10);

  connect();

  if($addSubmit || $editSubmit) {

      // Process add / edit form.

      $error = 0;
      $errorMessage = "";

      if($fileName == NULL) { $error = 1; $errorMessage .= "Filename must not be blank.<br>"; }
      if($fileLink == NULL) { $error = 1; $errorMessage .= "Link must not be blank.<br>"; }
      if($fileSize == NULL) { $error = 1; $errorMessage .= "Size must not be blank.<br>"; }
      if($catID == NULL) { $error = 1; $errorMessage .= "Category must not be blank.<br>"; }
      if($fileDesc == NULL) { $error = 1; $errorMessage .= "Description must not be blank.<br>"; }

      if($error == 1) { echo "<b>ERROR:</b><br>" . $errorMessage . "<br>"; } else {

        if($editSubmit) {

          $sql = "UPDATE redcms_files SET cat_id = '" . $catID . "', file_name = '" . $fileName . "',  file_desc = '" . $fileDesc . "',  file_link = '" . $fileLink . "',  file_size = '" . $fileSize . "' WHERE redcms_files.file_id = '" . $id . "'";

          $result = mysql_query($sql) or die("ERROR: Unable to edit file.<br>" . $sql);

          echo"File has been edited.";

        } else {

          $sql = "INSERT INTO redcms_files VALUES ('', '" . $catID . "', '" . $fileName . "', '" . $fileDesc . "', '" . $fileLink . "', '" . $fileSize . "', '0', NOW(), NOW())";

          $result = mysql_query($sql) or die("ERROR: Unable to add new file.<br>" . $sql);

          echo"File has been added.";

        }

        include"bottom.php";

        exit();

      }

    } else if($deleteSubmit && $id) {

      // Process delete form.

      $sql = "DELETE FROM redcms_files WHERE redcms_files.file_id = '" . $id . "'";
      $result = mysql_query($sql);

      echo "File has been deleted.";

    } else if($delete) {

      // Display delete form.

      echo'<form method="post" action="' . $PHP_SELF . '">';

        echo'<table>';

        echo'<tr><td>Are you sure you want to delete this file?</td><td></td></tr>';

        echo'<tr><td><input type="hidden" name="id" value="' . $id . '"></td><td></td></tr>';

        echo'<tr><td><input type="Submit" name="deleteSubmit" value="Yes"></td><td></td></tr>';

        echo'</table>';

      echo'</form>';

    } else {

      // Display add form / edit if appropriate.

      if($edit) {

        $sql = "SELECT * FROM redcms_files WHERE redcms_files.file_id = '" . $id . "'";
        $result = mysql_query($sql) or die("ERROR: Invalid file ID.");
        $num = mysql_num_rows($result);

        $origfileID = mysql_result($result, $i, "redcms_files.file_id");
        $origcatID = mysql_result($result, $i, "redcms_files.cat_id");
        $origfileName = mysql_result($result, $i, "redcms_files.file_name");
        $origfileLink = mysql_result($result, $i, "redcms_files.file_link");
        $origfileSize = mysql_result($result, $i, "redcms_files.file_size");
        $origfileDesc = mysql_result($result, $i, "redcms_files.file_desc");

      }

      ?><form method="post" action="<?php echo $PHP_SELF; ?>"><?php

        echo'<table>';

          echo'<tr><td>Filename:</td><td> <input type="text" name="fileName" value="' . $origfileName . '"></td></tr>';
          echo'<tr><td>Link:</td><td> <input type="text" name="fileLink" value="' . $origfileLink . '"></td></tr>';
          echo'<tr><td>Size:</td><td> <input type="text" name="fileSize" value="' . $origfileSize . '"></td></tr>';
          echo'<tr><td>Category:</td><td>';
            echo'<select name="catID">';

            $sql = "SELECT * FROM redcms_file_categories ORDER BY redcms_file_categories.cat_name ASC";
            $result = mysql_query($sql);
            $num = mysql_num_rows($result);

            for($i=0; $i < $num; $i++) {

              $catID = mysql_result($result, $i, "redcms_file_categories.cat_id");
              $catName = mysql_result($result, $i, "redcms_file_categories.cat_name");

              $option = "";
              if($origcatID == $catID) { $option = "SELECTED"; }

              echo'<option value="' . $catID . '"' . $option . '>' . $catName;

            }

            echo'</select>';

          echo'</td></tr>';
          echo'<tr><td valign="top">Description:</td><td><textarea name="fileDesc">' . $origfileDesc . '</textarea></td></tr>';

          if($edit) {
            echo' <input type="hidden" name="id" value="' . $id . '">';
            echo'<tr><td><input type="Submit" name="editSubmit" value="Edit"></td><td></td></tr>';

          } else {

            echo'<tr><td><input type="Submit" name="addSubmit" value="Add"></td><td></td></tr>';

          }

        echo'</table>';

      echo'</form>';

}


  include"bottom.php";

?>
