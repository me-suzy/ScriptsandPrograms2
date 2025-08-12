<?php

  $l = "messenger";

  include"top.php";

  isLoggedIn();

  connect();

  if($delete) {

   $sql = "DELETE FROM redcms_messenger WHERE redcms_messenger.message_id = '" . $id . "' AND redcms_messenger.to_user_id = '" . $_SESSION['redUserID'] . "'";

  mysql_query($sql) or die("ERROR: Failed to delete message");

  echo "Message has been deleted.";

  include"bottom.php";

  exit();

  }

  $sql = "SELECT * FROM redcms_messenger WHERE redcms_messenger.to_user_id = '" . $_SESSION['redUserID'] . "' AND redcms_messenger.message_read = 'FALSE'";

  $result = mysql_query($sql);

  $numOfNewMessages = mysql_num_rows($result);

  $sql = "SELECT * FROM redcms_messenger WHERE redcms_messenger.to_user_id = '" . $_SESSION['redUserID'] . "'";

  $result = mysql_query($sql);

  $numOfMessages = mysql_num_rows($result);

  $sql = "SELECT * FROM redcms_messenger WHERE redcms_messenger.from_user_id = '" . $_SESSION['redUserID'] . "'";

  $result = mysql_query($sql);

  $numOfSentMessages = mysql_num_rows($result);

?>

  <a href='?read=1'>Inbox (<?php echo $numOfMessages; ?> messages, including <?php echo $numOfNewMessages; ?> Unread/New)</a><br>
  <a href='?outbox=1'>Outbox (<?php echo $numOfSentMessages; ?> messages)</a><br>
  <a href='?send=1'>Send A Message</a><br><br>

<?php

  if($id && !$forward && !$reply) {

    $sql = "SELECT * FROM redcms_messenger LEFT JOIN redcms_users ON redcms_users.user_id = redcms_messenger.from_user_id WHERE redcms_messenger.message_id = '" . $id . "' AND redcms_messenger.to_user_id = '" . $_SESSION['redUserID'] . "'ORDER BY redcms_messenger.message_id DESC";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    if($num == 0) {

      $sql = "SELECT * FROM redcms_messenger LEFT JOIN redcms_users ON redcms_users.user_id = redcms_messenger.from_user_id WHERE redcms_messenger.message_id = '" . $id . "' AND redcms_messenger.from_user_id = '" . $_SESSION['redUserID'] . "'ORDER BY redcms_messenger.message_id DESC";

    }

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

   if($num == 0) { echo "ERROR: Message not found"; include"bottom.php"; exit(); }

    $i = 0;

    $toUserID = mysql_result($result, $i, "redcms_messenger.to_user_id");
    $fromUserID = mysql_result($result, $i, "redcms_messenger.from_user_id");
    $messageID = mysql_result($result, $i, "redcms_messenger.message_id");
    $messageSubject = mysql_result($result, $i, "redcms_messenger.message_subject");
    $messageText = mysql_result($result, $i, "redcms_messenger.message_text");
    $messageDate = mysql_result($result, $i, "redcms_messenger.message_date");
    $messageTime = mysql_result($result, $i, "redcms_messenger.message_time");
    $messageRead = mysql_result($result, $i, "redcms_messenger.message_read");

    $fromUserUName = mysql_result($result, $i, "redcms_users.user_uname");

   if($toUserID == $_SESSION['redUserID']) {

?>

<div align="right">
  <a href='?reply=1&id=<?php echo $id; ?>'>Reply</a> &nbsp;
  <a href='?forward=1&id=<?php echo $id; ?>'>Forward</a> &nbsp;
  <a href='?delete=1&id=<?php echo $id; ?>'>delete</a> &nbsp;
</div>

<?php

  }

    $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_id = '" . $toUserID . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    $toUserUName = mysql_result($result, "0", "redcms_users.user_uname");

    $sql = "UPDATE redcms_messenger SET redcms_messenger.message_read = 'TRUE' WHERE redcms_messenger.message_id = '" . $messageID . "' AND redcms_messenger.to_user_id = '" . $_SESSION['redUserID'] . "'";

    $result = mysql_query($sql);

     echo"<b>To:</b> <a href='profile.php?id=" . $toUserID . "'>" . $toUserUName . "</a><br>";
     echo"<b>From:</b>  <a href='profile.php?id=" . $fromUserID . "'>" . $fromUserUName . "</a><br>";
     echo"<b>Subject:</b> " . $messageSubject . "<br>";
     echo"<i>Sent on " . $messageDate . " at " . $messageTime . "</i><br><br>";
     echo"<b>Content:</b><br> " . $messageText . "<br>";

  }

  if($numOfMessages == 0 && $read == 1) {

   echo"<b>Your inbox is empty!</b>";

  }

  if($numOfSentMessages == 0 && $outbox == 1) {

   echo"<b>Your outbox is empty!</b>";

  }

  if($outbox && $numOfSentMessages != 0) {

  $sql = "SELECT * FROM redcms_messenger LEFT JOIN redcms_users ON redcms_users.user_id = redcms_messenger.to_user_id WHERE redcms_messenger.from_user_id = '" . $_SESSION['redUserID'] . "' ORDER BY redcms_messenger.message_id DESC";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    echo '<table width="100%">';

    echo"<tr><td><b>To</b></td><td><b>Date + Time</b></td><td><b>Subject</b></td></tr>";

    for($i = 0; $i < $num; $i++) {

      $toUserID = mysql_result($result, $i, "redcms_messenger.to_user_id");
      $fromUserID = mysql_result($result, $i, "redcms_messenger.from_user_id");
      $messageID = mysql_result($result, $i, "redcms_messenger.message_id");
      $messageSubject = mysql_result($result, $i, "redcms_messenger.message_subject");
      $messageText = mysql_result($result, $i, "redcms_messenger.message_text");
      $messageDate = mysql_result($result, $i, "redcms_messenger.message_date");
      $messageTime = mysql_result($result, $i, "redcms_messenger.message_time");
      $messageRead = mysql_result($result, $i, "redcms_messenger.message_read");

      $toUserUName = mysql_result($result, $i, "redcms_users.user_uname");

      echo "<tr><td><a href='profile.php?id=" . $toUserID . "'>" . $toUserUName . "</a></td><td>" .  $messageDate . " @ " .  $messageTime ."</td><td><a href='?id=" . $messageID . "'>" . $messageSubject . "</a></td></tr>";
   }

  }

  if($read == 1 && $numOfMessages != 0) {

    $sql = "SELECT * FROM redcms_messenger LEFT JOIN redcms_users ON redcms_users.user_id = redcms_messenger.from_user_id WHERE redcms_messenger.to_user_id = '" . $_SESSION['redUserID'] . "' ORDER BY redcms_messenger.message_id DESC";;

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    echo '<table width="100%">';

    echo"<tr><td><b>From</b></td><td><b>Date + Time</b></td><td><b>Subject</b></td><td><b>Status</b></td></tr>";

    for($i = 0; $i < $num; $i++) {

      $toUserID = mysql_result($result, $i, "redcms_messenger.to_user_id");
      $fromUserID = mysql_result($result, $i, "redcms_messenger.from_user_id");
      $messageID = mysql_result($result, $i, "redcms_messenger.message_id");
      $messageSubject = mysql_result($result, $i, "redcms_messenger.message_subject");
      $messageText = mysql_result($result, $i, "redcms_messenger.message_text");
      $messageDate = mysql_result($result, $i, "redcms_messenger.message_date");
      $messageTime = mysql_result($result, $i, "redcms_messenger.message_time");
      $messageRead = mysql_result($result, $i, "redcms_messenger.message_read");

      $fromUserUName = mysql_result($result, $i, "redcms_users.user_uname");

      if($messageRead == 'FALSE') { $startBold = "<b>"; $endBold = "</b>"; $unread = "unread";  }
      else { $startBold = ""; $endBold = ""; $unread = "read"; }

      echo "<tr><td><a href='profile.php?id=" . $fromUserID . "'>" . $fromUserUName . "</a></td><td>" .  $messageDate . " @ " .  $messageTime ."</td><td><a href='?id=" . $messageID . "'>" . $messageSubject . "</a></td><td>" . $startBold . $unread . $endBold . "</td></tr>";


    }

    echo '</table>';

  } else if($sendMsg) {

    $sql = "SELECT * FROM redcms_users WHERE redcms_users.user_uname = '" . $username . "'";

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    $error = 0;
    $errorMessage = "";

    if($subject == NULL) { $error = 1; $errorMessage .= "Subject must not be blank<br>"; }
    if($message == NULL) { $error = 1; $errorMessage .= "Message must not be blank<br>"; }

    if($num == 0) {

      echo "ERROR: No users found with this username.";

    } else if($error != 0) {

      echo "ERROR:<br>" . $errorMessage;

    } else {

      $fromUserID = $_SESSION['redUserID'];
      $toUserID = mysql_result($result, "0", "redcms_users.user_id");

      $sql = "INSERT INTO redcms_messenger VALUES('', '" . $fromUserID . "', '" . $toUserID . "', '" . $subject . "', '" . $message . "', NOW(), NOW(), 'FALSE')";

      $result = mysql_query($sql) or die("ERROR: Failed to add message to database");

      echo "Message sent";

    }

  }

 if($send == 1 || $sendMsg || $reply || $forward) {

  if($reply == 1 || $forward == 1) {

  $sql = "SELECT * FROM redcms_messenger LEFT JOIN redcms_users ON redcms_users.user_id = redcms_messenger.from_user_id WHERE redcms_messenger.message_id = '" . $id . "' ORDER BY redcms_messenger.message_id DESC";

   $toUserID = mysql_result($result, $i, "redcms_messenger.to_user_id");

    $result = mysql_query($sql);

    $num = mysql_num_rows($result);

    $i = 0;

    if($reply == 1 && $toUserID == $_SESSION['redUserID']) {

      $username = mysql_result($result, $i, "redcms_users.user_uname");

    } else if($forward == 1 && $toUserID == $_SESSION['redUserID']) {

    $message = mysql_result($result, $i, "redcms_messenger.message_text");

  }

  }


?>

<form method="post" action="<?php echo $PHP_SELF?>">

  <table>

    <tr><td valign="top">To (Username): </td><td> <input type="text" name="username" value="<?php echo $username; ?>"></td></tr>
    <tr><td valign="top">Subject: </td><td> <input type="text" name="subject" value="<?php echo $subject; ?>"></td></tr>
    <tr><td valign="top">Message: </td><td> <textarea name="message"><?php echo $message; ?></textarea></td></tr>
    <tr><td><input type="Submit" name="sendMsg" value="Send"></td><td></td></tr>

  </table>

</form>


<?php

  }

  include"bottom.php";

?>