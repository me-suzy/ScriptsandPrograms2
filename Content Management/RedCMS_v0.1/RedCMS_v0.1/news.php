<?php

  $l = "news";

  include"top.php";

  include"redcms_news_config.php";

  include"redcms_bb.php";

  // Connect to database

  connect();

  // Fetch from database

  if($id) {

    $sql = "SELECT * FROM redcms_news LEFT JOIN redcms_users ON redcms_news.user_id = redcms_users.user_id WHERE redcms_news.news_id ='" . $id . "'";

  } else {

    $sql="SELECT * FROM redcms_news LEFT JOIN redcms_users ON redcms_news.user_id = redcms_users.user_id ORDER BY redcms_news.news_id DESC";

  }


  $result = mysql_query($sql);
  $num = mysql_num_rows($result);

  if($num == 0) {

    echo "ERROR: No News Articles Found.";

    include"bottom.php";

    exit();

  }

  if($p) {
    $end = $p * $limit;
    if($end >= $num) { $end = $num; }
    $start = $end - $limit;
  } else {
    $end = $limit;
    if($end >= $num) { $end = $num; }
    $start = 0;
  }

  for($i=$start; $i<$end; $i++) {

    $newsID = mysql_result($result,$i,"redcms_news.news_id");
    $newsDate = mysql_result($result,$i,"redcms_news.news_date");
    $newsTime = mysql_result($result,$i,"redcms_news.news_time");
    $newsFormatDate = mysql_result($result,$i,"redcms_news.news_rdate");
    $newsTitle = mysql_result($result,$i,"redcms_news.news_title");
    $newsText = mysql_result($result,$i,"redcms_news.news_text");
    $authorID = mysql_result($result,$i,"redcms_users.user_id");
    $authorUName = mysql_result($result,$i,"redcms_users.user_uname");

    // BB it

    $newsTitle = bbIt($newsTitle);
    $newsText = bbIt($newsText);



?>

  <div class="redNews" style="border-bottom: 1px solid black">
    <div align="right">
      <?php echo $newsDate; ?>
    </div><br>
    <b><?php echo $newsTitle; ?></b><br>
    <?php echo $newsText; ?>
    <br><br>Posted by <a href="profile.php?id=<?php echo $authorID; ?>"><?php echo $authorUName; ?></a>.<br><br>
  </div><br><br>

<?php

  }

?>

<div class="redNews">

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
