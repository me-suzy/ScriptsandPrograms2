<?php

  include"redcms_news_config.php";
  include"top.php";

  access(10); // Restrict access to admins only

// Connect to the database

    connect();

?>

<a href='admin.php'><< Back</a><br><br>

<?php

  if($edit) {

    $sql = "UPDATE redcms_news SET news_title = '" . $newsTitle . "', news_text = '" . $newsText . "' WHERE news_id = '" . $id . "'";
    $result = mysql_query($sql) or die("ERROR: Failed to edit news article");

    echo "News article has been updated.";

  }

  if($addNews) {

    $error = 0;
    $errorMessage = "";

    $newsRDate = date("r");

    $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_id = '" . $_SESSION['redUserID'] . "'";
    $result = mysql_query($sql);

    $authorID = mysql_result($result, "0", "redcms_users.user_id");
    $authorName = mysql_result($result, "0", "redcms_users.user_name");

    // Validate form content

    if($newsTitle == null) { $errorMessage .= "Title must not be blank.<br>"; $error = 1; }
    if($newsText == null) { $errorMessage .= "Content must not be blank.<br>"; $error = 1; }

    if($error != 0) { echo "ERROR: <br> " . $errorMessage; include"bottom.php"; exit(); } else {

      // Insert article into database

      $sql = "INSERT INTO redcms_news VALUES ('','$authorID','$newsTitle','$newsText','$newsRDate',NOW(),NOW())";

      mysql_query($sql);

      // Confirm that news post has been made

      echo "News article has been posted.";

    }

  } else {

    if($e) {

      $sql = "SELECT * FROM redcms_news LEFT JOIN redcms_users ON redcms_users.user_id = redcms_news.user_id WHERE redcms_news.news_id ='" . $id ."'";
      $result = mysql_query($sql);
      $num = mysql_num_rows($result);

      if($num != 0) {

        $newsID = mysql_result($result, "0", "redcms_news.news_id");
        $newsTitle = mysql_result($result, "0", "redcms_news.news_title");
        $newsText = mysql_result($result, "0", "redcms_news.news_text");

      }

    }

  // Display form

?>

  <form method="post" action="<?php echo $PHP_SELF;?>">

    <table>

    <tr><td>Title:</td><td> <input type="text" name="newsTitle" value="<?php echo $newsTitle; ?>"></td></tr>
    <tr><td>Content:</td><td> <br><textarea cols=60 rows=20 name="newsText"><?php echo $newsText; ?></textarea></td></tr>

<?php

  if($e) {

    echo '<tr><td><input type="hidden" name="id" value="' . $newsID . '"></td><td></td></tr>';

    echo '<tr><td><input type="submit" value="Edit" name="edit"></td><td></td></tr>';

  } else {

    echo '<tr><td><input type="submit" value="Post" name="addNews"></td><td></td></tr>';

  }

?>

    </table>

  </form>

<?php

  }

  include"bottom.php";
?>
