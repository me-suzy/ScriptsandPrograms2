<?php

  include"top.php";

  access(10); // Restrict access to admins only

  // Connect to database

  connect();

  if($delete) {

    $sql = "DELETE FROM redcms_journal WHERE redcms_journal.journal_id = '" . $id . "' LIMIT 1";

    $result = mysql_query($sql) or die("ERROR: Unable to delete journal article");

    echo "journal article has been deleted";

  }

  if($del) {

?>

Are you sure you want to delete this journal article?

<br><br>

<form method="post" action="<?php echo $PHP_SELF;?>">

    <table>

    <tr><td><input type="hidden" value="<?php echo $id; ?>" name="id"></td><td></td></tr>

    <tr><td><input type="submit" value="Yes" name="delete"></td><td></td></tr>

    </table>

</form>

<br><br>

<?php

  }


  // Fetch from database

  if($id) {

    $sql = "SELECT * FROM redcms_journal LEFT JOIN redcms_users ON redcms_journal.user_id = redcms_users.user_id WHERE redcms_journal.journal_id ='" . $id . "'";

  } else {

    $sql="SELECT * FROM redcms_journal LEFT JOIN redcms_users ON redcms_journal.user_id = redcms_users.user_id ORDER BY redcms_journal.journal_id DESC";

  }

  $result = mysql_query($sql);
  $num = mysql_num_rows($result);

  if($num == 0) {

    echo "ERROR: No Journal Posts Found.";

    include"bottom.php";

    exit();

  }

  echo '<div class="redjournal"><table width="100%">';

  echo'<tr><td><b>Title</b></td><td><b>Date</b></td><td><b>Author</b></td><td></td></tr>';

  for($i=0; $i<$num; $i++) {

    $journalID = mysql_result($result,$i,"redcms_journal.journal_id");
    $journalDate = mysql_result($result,$i,"redcms_journal.journal_date");
    $journalTime = mysql_result($result,$i,"redcms_journal.journal_time");
    $journalFormatDate = mysql_result($result,$i,"redcms_journal.journal_rdate");
    $journalTitle = mysql_result($result,$i,"redcms_journal.journal_title");
    $journalText = mysql_result($result,$i,"redcms_journal.journal_text");

    $authorID = mysql_result($result,$i,"redcms_users.user_id");
    $authorUName = mysql_result($result,$i,"redcms_users.user_uname");

    $options = "";

    if($e) { $options .= "[ <a href='journal_add.php?id=" . $journalID . "&e=1'>Edit</a> ]"; }
    if($d) { $options .= "[ <a href='journal_view.php?id=" . $journalID . "&del=1'>Delete</a> ]"; }

    echo'<tr><td>' . $journalTitle . '</td><td>' . $journalDate . '</td><td><a href="profile.php?id=' . $authorID . '">' . $authorUName . '</td><td>' . $options . '</td></tr>';


  }

  echo '</table></div>';



?>

<br>

<div class="redjournal">
  This journal script is powered by <a href='http://www.redcms.co.uk' target='_blank'>RedCMS : Redjournal Script</a>.
</div>

<?php

  include"bottom.php";

?>
