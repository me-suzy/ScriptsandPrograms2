<?php

  $l = "journal";

  include"top.php";

  include"redcms_bb.php";

  // Connect to database

  connect();

  // Fetch from database

  if($id) {

    $sql = "SELECT * FROM redcms_journal LEFT JOIN redcms_users ON redcms_journal.user_id = redcms_users.user_id WHERE redcms_journal.journal_id ='" . $id . "'";

  } else {

    $sql="SELECT * FROM redcms_journal LEFT JOIN redcms_users ON redcms_journal.user_id = redcms_users.user_id ORDER BY redcms_journal.journal_id DESC";

  }


  $result = mysql_query($sql);
  $num = mysql_num_rows($result);

  if($num == 0) {

    echo "ERROR: No Journal Entries Found.";

    include"bottom.php";

    exit();

  }


  for($i=0; $i<$num; $i++) {

    $journalID = mysql_result($result,$i,"redcms_journal.journal_id");
    $journalDate = mysql_result($result,$i,"redcms_journal.journal_date");
    $journalTime = mysql_result($result,$i,"redcms_journal.journal_time");
    $journalFormatDate = mysql_result($result,$i,"redcms_journal.journal_rdate");
    $journalTitle = mysql_result($result,$i,"redcms_journal.journal_title");
    $journalText = mysql_result($result,$i,"redcms_journal.journal_text");
    $authorID = mysql_result($result,$i,"redcms_users.user_id");
    $authorUName = mysql_result($result,$i,"redcms_users.user_uname");

    // BB it

    $journalTitle = bbIt($journalTitle);
    $journalText = bbIt($journalText);



?>

  <div class="redJournal" style="border-bottom: 1px solid black">
    <div align="right">
      <?php echo $journalDate . " at " . $journalTime ?>
    </div><br>
    <b><?php echo $journalTitle; ?></b><br>
    <?php echo $journalText; ?>
    <br><br>Posted by <a href="profile.php?id=<?php echo $authorID; ?>"><?php echo $authorUName; ?></a>.<br><br>
  </div><br><br>

<?php

  }

?>

<div class="redjournal">

<?php

  $upto = $p * $limit;

  if($p = 0) { $p = 1; }

  $lastP = $p - 1;
  $nextP = $p + 1;

  $last = "<a href='?p=" . $lastP . "'>Previous " . $limit . "</a>";
  $next = "<a href='?p=" . $nextP . "'>Next " . $limit . "</a>";

  if($i != $limit) {

    if($num >= $limit) {

     echo $last;

    }

    if($num < $upto) {

     echo $next;

    }

  } else {

    echo $next;

  }

?>

</div>

<?php

  include"bottom.php";

?>
