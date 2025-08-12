<?php

  include"top.php";

  connect();

  access(10);

  if($add) {

      $error = 0;

      if($catName == NULL) { $error = 1; $errorMessage .= "Category name must not be blank.<br>"; }

      if($error == 1) { echo "<b>ERROR:</b><br>" . $errorMessage . "<br>"; } else {

        $sql = "INSERT INTO redcms_file_categories VALUES ('', '" . $catName . "')";

        $result = mysql_query($sql) or die("ERROR: Unable To Add New Category.");

        echo "Category has been added.";

      }

  }

  if($delete) {

    $sql = "DELETE FROM redcms_file_categories WHERE redcms_file_categories.cat_id = '" . $catID . "'";

    mysql_query($sql) or die("ERROR: Failed To Delete Category.");

    echo "Category has been removed.<br><br>";

  }

  if($edit) {

    // Validate

    $error = 0;

    if($catName == NULL) { $error = 1; $errorMessage .= "Category name must not be blank.<br>"; }

    if($error != "0") { include"bottom.php"; exit(); }

    // Update

    $sql = "UPDATE redcms_file_categories SET redcms_file_categories.cat_name = '" . $catName . "' WHERE redcms_file_categories.cat_id = '" . $catID . "'";

    mysql_query($sql) or die("ERROR: Failed To Update Category");

    echo "Category has been updated.<br><br>";

  }

  // Display category information

  $sql = "SELECT * FROM redcms_file_categories ORDER BY redcms_file_categories.cat_name ASC";

  $result = mysql_query($sql);

  $num = mysql_num_rows($result);

  if($num == 0 && !$a) { echo "ERROR: No Categories Found."; include "bottom.php"; exit(); }

  if($num != 0) {

    echo'<table><tr class="tr1"><td>Category Name</td><td></td></tr>';

  }

  for($i=0; $i<$num; $i++) {

    $catID = mysql_result($result, $i, "redcms_file_categories.cat_id");
    $catName = mysql_result($result, $i, "redcms_file_categories.cat_name");

    $options = "";

    if($a != $catID) {

      $options .= "[ <a href='?a=" . $catID . "'>Add</a> ] ";

    } else { $options .= "[ <a href='?'>Add</a> ] "; }

    if($e != $catID) {

      $options .= "[ <a href='?e=" . $catID . "'>Edit</a> ] ";

    } else { $options .= "[ <a href='?'>Edit</a> ] "; }

    if($d != $catID) {

      $options .= "[ <a href='?d=" . $catID . "'>Delete</a> ] ";

    } else { $options .= "[ <a href='?'>Delete</a> ] "; }

    echo '<tr class="tr2"><td>' . $catName . '</td><td>' . $options . '</td></tr>';

    if($d == $catID) {

      // Display the delete form

      echo '<tr class="tr3"><td colspan="5"> Are you sure you want to delete this category?';

?>

  <form method="post" action="<?php echo $PHP_SELF?>">

    <input type="hidden" name="catID" value="<?php echo $catID; ?>">

    <input type="submit" name="delete" value="Yes">

  </form>

<?php

      echo"</td></tr>";

    }

    if($e == $catID) {

      // Display the edit form

      echo '<tr class="tr3"><td colspan="5">';

?>

  <form method="post" action="<?php echo $PHP_SELF?>">

   <table>

    <tr><td>Category Name:</td><td><input type="text" name="catName" value="<?php if(!$a) { echo $catName; } ?>"></td></tr>

    <input type="hidden" name="catID" value="<?php echo $catID; ?>"></td></tr>

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

    <tr><td>Category Name:</td><td><input type="text" name="catName" value="<?php if(!$a) { echo $catName; } ?>"></td></tr>

    <tr><td><input type="submit" name="add" value="Submit"></td><td></td></tr>

   </table>

  </form>

<?php

  echo"</td></tr>";

  echo "</table>";

  }

  include"bottom.php";

?>
