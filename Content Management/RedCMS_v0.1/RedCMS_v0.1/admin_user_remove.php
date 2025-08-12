<?php

  session_start();

  include"top.php";

  connect();

  access("10");

  if($end) {

    $sql = "DELETE FROM redcms_users WHERE user_id ='" . $id . "'";

    mysql_query($sql) or die($sql);

    echo "User has been removed from the database.<br><br>";

  }

  if($step) {

    $sql = "SELECT * FROM redcms_users WHERE user_id = '" . $id . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    $username = mysql_result($result, "0", "redcms_users.user_uname");

?>

 2. Please confirm that you would like to remove <?php echo $username; ?> from the users database.<br><br>

<form method="post" action="<?php echo $PHP_SELF?>">

    <input type="hidden" name="id" value="<?php echo $id; ?>">

<input type="Submit" name="end" value="Remove">

<?php

  include"bottom.php";
  exit();

  }

?>

  1. Select a username to delete from the list below.<br><br>

<?php

  $sql = "SELECT * FROM redcms_users ORDER BY redcms_users.user_uname DESC";

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