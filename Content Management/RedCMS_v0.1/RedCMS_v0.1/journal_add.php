<?php

  include"top.php";

  access(10); // Restrict access to admins only

// Connect to the database

    connect();

?>

<a href='admin.php'><< Back</a><br><br>

<?php

  if($edit) {

    $sql = "UPDATE redcms_journal SET journal_title = '" . $journalTitle . "', journal_text = '" . $journalText . "' WHERE journal_id = '" . $id . "'";
    $result = mysql_query($sql) or die("ERROR: Failed to edit journal article");

    echo "journal article has been updated.";

  }

  if($addjournal) {

    $error = 0;
    $errorMessage = "";

    $journalRDate = date("r");

    $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_id = '" . $_SESSION['redUserID'] . "'";
    $result = mysql_query($sql);

    $authorID = mysql_result($result, "0", "redcms_users.user_id");
    $authorName = mysql_result($result, "0", "redcms_users.user_name");

    // Validate form content

    if($journalTitle == null) { $errorMessage .= "Title must not be blank.<br>"; $error = 1; }
    if($journalText == null) { $errorMessage .= "Content must not be blank.<br>"; $error = 1; }

    if($error != 0) { echo "ERROR: <br> " . $errorMessage; include"bottom.php"; exit(); } else {

      // Insert article into database

      $sql = "INSERT INTO redcms_journal VALUES ('','$authorID','$journalTitle','$journalText','$journalRDate',NOW(),NOW())";

      mysql_query($sql);

      // Confirm that journal post has been made

      echo "journal article has been posted.";

    }

  } else {

    if($e) {

      $sql = "SELECT * FROM redcms_journal LEFT JOIN redcms_users ON redcms_users.user_id = redcms_journal.user_id WHERE redcms_journal.journal_id ='" . $id ."'";
      $result = mysql_query($sql);
      $num = mysql_num_rows($result);

      if($num != 0) {

        $journalID = mysql_result($result, "0", "redcms_journal.journal_id");
        $journalTitle = mysql_result($result, "0", "redcms_journal.journal_title");
        $journalText = mysql_result($result, "0", "redcms_journal.journal_text");

      }

    }

  // Display form

?>

  <form method="post" action="<?php echo $PHP_SELF;?>">

    <table>

    <tr><td>Title:</td><td> <input type="text" name="journalTitle" value="<?php echo $journalTitle; ?>"></td></tr>
    <tr><td>Content:</td><td> <br><textarea cols=60 rows=20 name="journalText"><?php echo $journalText; ?></textarea></td></tr>

<?php

  if($e) {

    echo '<tr><td><input type="hidden" name="id" value="' . $journalID . '"></td><td></td></tr>';

    echo '<tr><td><input type="submit" value="Edit" name="edit"></td><td></td></tr>';

  } else {

    echo '<tr><td><input type="submit" value="Post" name="addjournal"></td><td></td></tr>';

  }

?>

    </table>

  </form>

<?php

  }

  include"bottom.php";
?>
